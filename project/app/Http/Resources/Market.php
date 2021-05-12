<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Market extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $currency = $this->getCurrency();
        $quoteCurrency = $this->getQuoteCurrency();

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'bid' => $this->bid_decimal,
            'ask' => $this->ask_decimal,
            'last' => $this->last_decimal,
            'change_24h' => $this->change_24h_decimal,
            'change_24h_percent' => $this->change_24h_percent,
            'high_24h' => $this->high_24h_decimal,
            'low_24h' => $this->low_24h_decimal,
            'volume_24h' => $this->volume_24h_decimal,
            'currency_id' => $this->currency_id,
            'currency_type' => $this->currency_type,
            'currency' => $currency->symbol,
            'currency_decimals' => $currency->decimals,
            'currency_format_decimals' => $this->currency_format_decimals,
            'currency_name' => $currency->name,
            'quote_currency_id' => $this->quote_currency_id,
            'quote_currency_type' => $this->quote_currency_type,
            'quote_currency' => $quoteCurrency->symbol,
            'quote_currency_decimals' => $quoteCurrency->decimals,
            'quote_currency_format_decimals' => $this->quote_currency_format_decimals,
            'quote_currency_name' => $quoteCurrency->name,
        ];

        if (auth('api')->user() && auth('api')->user()->hasRole('admin')) {
            $data['min_trade_size'] = $this->min_trade_size;
            $data['min_trade_size_quote'] = $this->min_trade_size_quote;
        }

        return $data;
    }
}
