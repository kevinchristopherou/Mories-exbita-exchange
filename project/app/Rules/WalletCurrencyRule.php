<?php

namespace App\Rules;

use App\Models\Currency;
use App\Models\Wallet;
use Illuminate\Contracts\Validation\Rule;

class WalletCurrencyRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = auth()->user();

        $currency = Currency::where('symbol', $value)->first();

        if (! $currency) {
            return false;
        }

        return Wallet::where('currency_id', $currency->id)->where('user_id', $user->id)->first() == true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute wallet is not found.');
    }
}
