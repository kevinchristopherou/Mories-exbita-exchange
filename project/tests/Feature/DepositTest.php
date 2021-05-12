<?php

namespace Tests\Feature;

use App\Mail\UserDepositEmail;
use App\Models\FiatCurrency;
use App\Models\FiatDeposit;
use App\User;
use Illuminate\Support\Facades\Mail;
use Tests\ApiTestCase;

class DepositTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        //Make user 2 id verified to use in tests
        $user = User::whereId(2)->first();
        $user->id_verified = 1;
        $user->save();
    }

    public function testUnverifiedUserCanNotDepositFiat()
    {
        $user = User::find(1);
        $token = $this->getUserToken($user);
        $headers = $this->getHeaders($token);

        $wallet = $user->getFiatWallet(FiatCurrency::ID_TRY);

        $data = [
            'fiat_currency_id' => $wallet->fiat_currency_id,
            'amount' => 1000,
            'description' => '1234567890',

        ];
        $response = $this->apiPost('deposit/fiat', $data, $headers);
        $response->assertStatus(403);
    }

    public function testUserCanDepositFiat()
    {
        $user = User::find(2);
        $token = $this->getUserToken($user);
        $headers = $this->getHeaders($token);

        $wallet = $user->getFiatWallet(FiatCurrency::ID_TRY);

        $data = [
            'fiat_currency_id' => $wallet->fiat_currency_id,
            'amount' => 1000,
            'description' => '1234567890',

        ];
        $response = $this->apiPost('deposit/fiat', $data, $headers);
        $response->assertStatus(200);

        $data['isValidated'] = 1;

        $response = $this->apiPost('deposit/fiat', $data, $headers);
        $response->assertStatus(200);

        $this->assertDatabaseHas('fiat_deposits', ['user_id' => $user->id, 'fiat_currency_id' => $wallet->fiat_currency_id]);

        return $wallet;
    }

    public function testAdminCanApproveFiatDeposit()
    {
        Mail::fake();

        $user = User::find(2);
        $wallet = $user->getFiatWallet(FiatCurrency::ID_TRY);

        $depositAmount = 10000;

        $deposit = new FiatDeposit();
        $deposit->user_id = 2;
        $deposit->fiat_currency_id = $wallet->fiat_currency_id;
        $deposit->amount = $depositAmount;
        $deposit->description = '1234567890';
        $deposit->save();

        $headers = $this->getAdminHeaders();
        // Approve deposit
        $response = $this->apiPatch("admin/deposit/fiat/approved?id={$deposit->id}&code=REF123", [], $headers);

        $response->assertStatus(200);

        // Check deposit status
        $this->assertDatabaseHas('fiat_deposits', [
            'id' => $deposit->id,
            'status' => 'approved',
            'code' => 'REF123',
        ]);

        $this->assertDatabaseHas('fiat_wallets', [
            'user_id' => $user->id,
            'fiat_currency_id' => FiatCurrency::ID_TRY,
            'available_balance' => 11000000,
        ]);

        Mail::assertQueued(UserDepositEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function testAdminCanRejectFiatDeposit()
    {
        $user = User::find(2);
        $wallet = $user->getFiatWallet(FiatCurrency::ID_TRY);

        $depositAmount = 10000;

        $deposit = new FiatDeposit();
        $deposit->user_id = 2;
        $deposit->fiat_currency_id = $wallet->fiat_currency_id;
        $deposit->amount = $depositAmount;
        $deposit->description = '1234567890';
        $deposit->save();

        $headers = $this->getAdminHeaders();
        // Approve deposit
        $response = $this->apiPatch("admin/deposit/fiat/rejected?id={$deposit->id}&note=SomeRejectReason", [], $headers);

        $response->assertStatus(200);

        // Check deposit status
        $this->assertDatabaseHas('fiat_deposits', [
            'id' => $deposit->id,
            'status' => 'rejected',
            'note' => 'SomeRejectReason',
        ]);
    }
}
