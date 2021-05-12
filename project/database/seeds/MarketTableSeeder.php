<?php

use App\Models\Currency;
use App\Models\FiatCurrency;
use App\Models\Market;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currency = Currency::first();
        $fiat_currency = FiatCurrency::first();

        if (! $currency || ! $fiat_currency) {
            return false;
        }

        //truncate all markets
        DB::statement('TRUNCATE markets');

        // Save a model
        $data = [
            'id' => 1,
            'name' => $currency->symbol . '-' . $fiat_currency->symbol,
            'is_active' => 1,
            'min_trade_size' => 0.00005,
            'min_trade_size_quote' => 2,
            'currency_id' => $currency->id,
            'currency_type' => 1,
            'quote_currency_id' => $fiat_currency->id,
            'quote_currency_type' => 2,
            'currency_format_decimals' => 4,
            'quote_currency_format_decimals' => 2,
        ];

        $market = new Market($data);
        $market->save();
    }
}
