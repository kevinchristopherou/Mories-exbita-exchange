<?php

namespace App\Rules;

use App\Models\Currency;
use App\Models\FiatCurrency;
use Illuminate\Contracts\Validation\Rule;

class MarketStoreRule implements Rule
{
    public $errorMessage = "Market Pairs can not be the same currencies";

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $name)
    {
        $type = request()->get('currency_type', false);
        $currency_id = request()->get('currency_id', false);
        $quoteType = request()->get('quote_currency_type', false);
        $quote_currency_id = request()->get('quote_currency_id', false);

        if(!$name) return false;

        $pair = explode('-', $name);

        if(count($pair) != 2) return false;

        if($pair[0] == $pair[1]) return false;

        if($type == 1 && !Currency::where('id', $currency_id)->first()) {
            return false;
        }

        if($type == 2 && !FiatCurrency::where('id', $currency_id)->first()) {
            return false;
        }

        if($quoteType == 1 && !Currency::where('id', $quote_currency_id)->first()) {
            return false;
        }

        if($quoteType == 2 && !FiatCurrency::where('id', $quote_currency_id)->first()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
