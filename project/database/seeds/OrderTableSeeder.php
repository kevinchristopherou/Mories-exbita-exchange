<?php

use App\Models\FiatWallet;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::truncate();
        Transaction::truncate();

        FiatWallet::where('fiat_currency_id', 1)->update([
            'available_balance' => 10000000,   //100,000 TRY
            'pending_balance' => 0,
            'withdraw_pending_balance' => 0,
        ]);

        Wallet::where('currency_id', 1)->update([
            'available_balance' => 1000000000,     // => 10.00000000 BTC
            'pending_balance' => 0,
            'withdraw_pending_balance' => 0,
        ]);
    }
}
