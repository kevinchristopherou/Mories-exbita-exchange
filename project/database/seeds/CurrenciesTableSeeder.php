<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Currency;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            [
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
                'type' => Currency::TYPE_COIN,
                'decimals' => 8,
                'fee_withdraw' => 10000, //0.0001 BTC
                'min_withdraw' => 100000, //0.001 BTC
                'min_deposit' => 100000, //0.001 BTC
            ],
            [
                'name' => 'Ethereum',
                'symbol' => 'ETH',
                'type' => Currency::TYPE_COIN,
                'decimals' => 18,
                'fee_withdraw' => 10000, //0.0001 ETH
                'min_withdraw' => 100000, //0.001 ETH
                'min_deposit' => 100000, //0.001 ETH
            ],
        ];

        DB::statement('TRUNCATE currencies');

        foreach ($currencies as $currency) {
            $cryptocurrency = new Currency($currency);
            $cryptocurrency->save();
        }
    }
}
