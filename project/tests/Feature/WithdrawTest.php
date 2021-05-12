<?php

namespace Tests\Feature;

use App\Events\CurrencyAccountBalanceUpdated;
use App\Facades\Api\GatewayBtc;
use App\Helpers\MoneyHelper;
use App\Jobs\Withdraws\ProcessWithdrawCrypto;
use App\Mail\AdminWithdrawFailEmail;
use App\Models\Currency;
use App\Models\FiatCurrency;
use App\Models\FiatWithdraw;
use App\Models\Withdraw;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\ApiTestCase;

class WithdrawTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        //Make user 2 id verified to use in tests
        $user = User::whereId(2)->first();
        $user->id_verified = 1;
        $user->save();
    }

    protected function isSkip()
    {
        if (env('SKIP_WALLET_TESTS', 0) == 1) {
            $this->assertTrue(true);

            return true;
        }

        return false;
    }

    public function testUserCanWithdrawFiat()
    {
        $user = User::find(2);
        $token = $this->getUserToken($user);
        $headers = $this->getHeaders($token);

        $wallet = $user->getFiatWallet(FiatCurrency::ID_TRY);

        $currency = FiatCurrency::find(FiatCurrency::ID_TRY);
        $currency->withdraw_max_daily = 100000;
        $currency->save();

        //user has 1000.00TRY
        $wallet->setField('available_balance', 100000);

        //withdraw 100.00TRY
        $data = [
            'currency' => $wallet->fiat_currency_id,
            'amount' => 1500,
            'bank_name' => 'Acme Bank',
            'swift_code' => 'SW123',
            'isValidated' => 1,
            'iban' => 'AL47212110090000 0002 3569 8741',
        ];

        $response = $this->apiPost('withdraw/fiat', $data, $headers);
        $response->assertStatus(422);

        $data['amount'] = 100;
        $response = $this->apiPost('withdraw/fiat', $data, $headers);
        $response->assertStatus(200);

        $this->assertDatabaseHas('fiat_withdrawal', [
            'user_id' => $user->id,
            'fiat_currency_id' => $wallet->fiat_currency_id,
            'swift_code' => 'SW123',
            'iban' => 'AL47212110090000000235698741',
            'amount' => 100.00,]);

        $wallet = $wallet->fresh();
        $available_balance = MoneyHelper::parseCrypto(900, 2);
        $withdraw_pending_balance = MoneyHelper::parseCrypto(100 ,2);
        $this->assertTrue($wallet->available_balance->equals($available_balance));
        $this->assertTrue($wallet->withdraw_pending_balance->equals($withdraw_pending_balance));

        //Test account remember
        $data = [
            'remember_name' => 'Acme Account',
            'iban' => 'AL47212110090000 0002 3569 8741',
            'fiat_currency_id' => FiatCurrency::ID_TRY,
        ];

        $response = $this->apiPost('withdraw/fiat/remembered', $data, $headers);
        $response->assertStatus(200);

        $this->assertDatabaseHas('fiat_withdrawal', [
            'user_id' => $user->id,
            'fiat_currency_id' => $wallet->fiat_currency_id,
            'remember_name' => 'Acme Account',
            'iban' => 'AL47212110090000000235698741',]);
    }

    public function testUserCanWithdrawCrypto()
    {
        if ($this->isSkip()) {
            return;
        }

        $user = User::find(2);
        $token = $this->getUserToken($user);
        $headers = $this->getHeaders($token);

        $wallet = $user->getWallet(Currency::ID_BTC);
        $wallet->setField('available_balance', 1000000000);

        //Test that address is invalid and 10 btc amount + fixed fee is not below the balance 10btc
        $data = [
            'currency' => $wallet->currency_id,
            'amount' => 10,
            'address' => '3HpXgfWi3zmwWvxVPccUny95cuzWkUPL96',
            'isValidated' => 1,
        ];
        $response = $this->apiPost('withdraw/crypto', $data, $headers);
        $response->assertStatus(422);
        $this->assertArrayHasKey('address', $response->json()['errors']);
        $this->assertArrayHasKey('amount', $response->json()['errors']);

        $data['amount'] = 1;
        $data['address'] = '2N8hwP1WmJrFF5QWABn38y63uYLhnJYJYTF';
        $response = $this->apiPost('withdraw/crypto', $data, $headers);
        $response->assertStatus(200);

        $this->assertDatabaseHas('withdrawal', [
            'user_id' => $user->id,
            'currency_id' => $wallet->currency_id,
            'status' => 'waiting',
        ]);

        $wallet = $wallet->fresh();

        //Assert the avaialble and pending balances including the fees
        $available_balance = MoneyHelper::parseCrypto(8.9999);
        $withdraw_pending_balance = MoneyHelper::parseCrypto(1.0001);
        $this->assertTrue($wallet->available_balance->equals($available_balance));
        $this->assertTrue($wallet->withdraw_pending_balance->equals($withdraw_pending_balance));

        //Test address remember
        $data = [
            'remember_name' => 'Acme Account',
            'address' => '2N8hwP1WmJrFF5QWABn38y63uYLhnJYJYTF',
            'currency_id' => Currency::ID_BTC,
        ];

        $response = $this->apiPost('withdraw/crypto/remembered', $data, $headers);
        $response->assertStatus(200);

        $this->assertDatabaseHas('withdrawal', [
            'user_id' => $user->id,
            'currency_id' => $wallet->currency_id,
            'remember_name' => 'Acme Account',
            'address' => '2N8hwP1WmJrFF5QWABn38y63uYLhnJYJYTF',]);

        Bus::fake();
        Event::fake();
        Artisan::call('blockchain:queuesendtx', ['currency_id' => Currency::ID_BTC]);

        Bus::assertDispatched(ProcessWithdrawCrypto::class, function ($job) use ($user) {
            return $job->withdraw->user_id === $user->id;
        });

        $withdraw = Withdraw::where('user_id', $user->id)->first();

        //Mock the actual BTC send before processing withdraw.

        //Mock txId
        GatewayBtc::shouldReceive('send')
            ->andReturn('0123456789ABCDEF0123456789ABCDEF');

        //Mock tx fee
        GatewayBtc::shouldReceive('getSendTxFee')
            ->with('0123456789ABCDEF0123456789ABCDEF')
            ->andReturn(0.00023400);

        //Mock update balance
        GatewayBtc::shouldReceive('getAccountBalance')
            ->andReturn(1.2345);

        $withdraw->proceed();

        Event::assertDispatched(CurrencyAccountBalanceUpdated::class, function ($e) {
            return $e->currency['id'] === Currency::ID_BTC;
        });

        $wallet = $wallet->fresh();
        $assertAmount = MoneyHelper::parseCrypto(0);
        $this->assertTrue($wallet->withdraw_pending_balance->equals($assertAmount));

        $this->assertDatabaseHas('wallet_transactions', [
            'txid' => '0123456789ABCDEF0123456789ABCDEF',
            'tx_fee' => 23400,
            'main_balance_after' => 123450000,
        ]);
    }

    public function testWithdrawCryptoFailsDueToInsufficientWalletBalance()
    {
        if ($this->isSkip()) {
            return;
        }

        $btc = Currency::find(Currency::ID_BTC);
        $btc->account_balance = 100000000; //online wallet has 1 BTC

        $user = User::find(2);

        //User has 11 BTC
        $wallet = $user->getWallet(Currency::ID_BTC);
        $wallet->setField('available_balance', 1100000000);

        $token = $this->getUserToken($user);
        $headers = $this->getHeaders($token);

        $data = [
            'currency' => $wallet->currency_id,
            'amount' => 10.5,
            'address' => '2N8hwP1WmJrFF5QWABn38y63uYLhnJYJYTF',
            'isValidated' => 1,
        ];

        $response = $this->apiPost('withdraw/crypto', $data, $headers);
        $response->assertStatus(200);

        //Mock the actual BTC send before processing withdraw.
        GatewayBtc::shouldReceive('send')
            ->andReturn('0123456789ABCDEF0123456789ABCDEF');

        Artisan::call('blockchain:queuesendtx', ['currency_id' => Currency::ID_BTC]);
        Artisan::call('queue:work', ['connection' => 'database', '--once' => true, '--queue' => 'wallets']);

        Mail::assertQueued(AdminWithdrawFailEmail::class);

        $withdraw = Withdraw::where('user_id', $user->id)->first();
        //Make sure withdraw is still waiting.
        $this->assertEquals('waiting', $withdraw->status);
    }

    public function testAdminCanApproveWithdrawFiat()
    {
        $user = User::find(2);
        $wallet = $user->getFiatWallet(FiatCurrency::ID_TRY);

        //user has 1000.00TRY
        $wallet->setField('available_balance', 90000);
        $wallet->setField('withdraw_pending_balance', 10000);
        $wallet = $wallet->fresh();

        $withdraw = new FiatWithdraw();
        $withdraw->user_id = 2;
        $withdraw->fiat_currency_id = $wallet->fiat_currency_id;
        $withdraw->amount = 100;
        $withdraw->bank_name = 'Acme Bank';
        $withdraw->swift_code = '';
        $withdraw->iban = 'AL47 2121 1009 0000 0002 3569 8741';
        $withdraw->save();

        $headers = $this->getAdminHeaders();
        // Approve withdraw
        $response = $this->apiPatch("admin/withdraw/fiat/approved?id={$withdraw->id}", ['note' => 'REF:123'], $headers);
        $response->assertStatus(200);

        // Check deposit status
        $this->assertDatabaseHas('fiat_withdrawal', [
            'id' => $withdraw->id,
            'status' => 'approved',
            'note' => 'REF:123',]);

        $this->assertDatabaseHas('fiat_wallets', [
            'user_id' => $user->id,
            'fiat_currency_id' => FiatCurrency::ID_TRY,
            'withdraw_pending_balance' => 0,
        ]);
    }

    public function testAdminCanRejectWithdrawFiat()
    {
        $user = User::find(2);
        $wallet = $user->getFiatWallet(FiatCurrency::ID_TRY);

        //user has 1000.00TRY
        $wallet->setField('available_balance', 90000);
        $wallet->setField('withdraw_pending_balance', 10000);
        $wallet = $wallet->fresh();

        $withdraw = new FiatWithdraw();
        $withdraw->user_id = 2;
        $withdraw->fiat_currency_id = $wallet->fiat_currency_id;
        $withdraw->amount = 100;
        $withdraw->bank_name = 'Acme Bank';
        $withdraw->swift_code = '';
        $withdraw->iban = 'AL47 2121 1009 0000 0002 3569 8741';
        $withdraw->save();

        $headers = $this->getAdminHeaders();
        // Approve withdraw
        $response = $this->apiPatch("admin/withdraw/fiat/rejected?id={$withdraw->id}", ['note' => 'SomeRejectNote'], $headers);
        $response->assertStatus(200);

        // Check deposit status
        $this->assertDatabaseHas('fiat_withdrawal', [
            'id' => $withdraw->id,
            'status' => 'rejected',
            'note' => 'SomeRejectNote',]);

        $this->assertDatabaseHas('fiat_wallets', [
            'user_id' => $user->id,
            'fiat_currency_id' => FiatCurrency::ID_TRY,
            'withdraw_pending_balance' => 0,
            'available_balance' => 100000,
        ]);
    }

    /*
     * Admin approval on withdraw crypto is removed
     *
    public function testAdminCanApproveWithdrawCrypto()
    {
        $user = User::find(2);
        $wallet = $user->getWallet(Currency::ID_BTC);

        //user withdraws 1 BTC with fee 0.0001
        $wallet->setField('available_balance', 899990000);
        $wallet->setField('withdraw_pending_balance', 100010000);
        $wallet = $wallet->fresh();

        $withdraw = new Withdraw();
        $withdraw->user_id = 2;
        $withdraw->currency_id = $wallet->currency_id;
        $withdraw->amount = 100000000;
        $withdraw->fee = 10000;
        $withdraw->address = '3HpXgfWi3zmwWvxVPccUny95cuzWkUPL96';
        $withdraw->save();

        //Mock the actual BTC send
        GatewayBtc::shouldReceive('send')
            ->andReturn('0123456789ABCDEF0123456789ABCDEF');

        $headers = $this->getAdminHeaders();
        // Approve withdraw
        $response = $this->apiPatch("admin/withdraw/crypto/approved?id={$withdraw->id}", ['note' => ''], $headers);
        $response->assertStatus(200);

        // Check deposit status
        $this->assertDatabaseHas('withdrawal', [
            'id' => $withdraw->id,
            'status' => 'approved'
        ]);

        $wallet = $wallet->fresh();
        $assertAmount = MoneyHelper::parseCrypto(0);
        $this->assertTrue($wallet->withdraw_pending_balance->equals($assertAmount));


        //@TODO: Test if withdraw transaction is created or queued whatever.
    }

    // Check reject of withdraw by admin
    public function testAdminCanRejectWithdrawCrypto()
    {
        $user = User::find(2);
        $wallet = $user->getWallet(Currency::ID_BTC);

        //user withdraws 1 BTC with fee 0.0001
        $wallet->setField('available_balance', 899990000);
        $wallet->setField('withdraw_pending_balance', 100010000);
        $wallet = $wallet->fresh();

        $withdraw = new Withdraw();
        $withdraw->user_id = 2;
        $withdraw->currency_id = $wallet->currency_id;
        $withdraw->amount = 100000000;
        $withdraw->fee = 10000;
        $withdraw->address = '3HpXgfWi3zmwWvxVPccUny95cuzWkUPL96';
        $withdraw->save();

        $headers = $this->getAdminHeaders();
        // Approve withdraw
        $response = $this->apiPatch("admin/withdraw/crypto/rejected?id={$withdraw->id}", ['note' => 'REF:123'], $headers);
        $response->assertStatus(200);

        // Check deposit status
        $this->assertDatabaseHas('withdrawal', [
            'id' => $withdraw->id,
            'status' => 'rejected',
            'note' => 'REF:123']);

        $wallet = $wallet->fresh();

        $assertAmount = MoneyHelper::parseCrypto(0);
        $this->assertTrue($wallet->withdraw_pending_balance->equals($assertAmount));

        $assertAmount = MoneyHelper::parseCrypto(10);
        $this->assertTrue($wallet->available_balance->equals($assertAmount));
    }
    */
}
