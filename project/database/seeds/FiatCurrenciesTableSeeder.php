<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\FiatCurrency;

class FiatCurrenciesTableSeeder extends Seeder
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
                'name' => 'US Dollar',
                'symbol' => 'USD',
                'decimals' => 2,
                'deposit_description' => '',
            ]
        ];

        DB::statement('TRUNCATE fiat_currencies');

        foreach ($currencies as $currency) {
            $fiat = new FiatCurrency($currency);
            $fiat->save();
        }
    }
}
