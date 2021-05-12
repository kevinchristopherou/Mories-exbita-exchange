<?php

namespace App\Rules;

use App\Helpers\ExchangeHelper;
use App\Models\FiatCurrency;
use Illuminate\Contracts\Validation\Rule;

class FiatDepositAmountRule implements Rule
{
    public $limit = 1;

    public $amount;

    public $currency;

    public $symbol;

    public function __construct($currency, $amount)
    {
        $this->currency = $currency;
        $this->amount = $amount;
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
        $currency = FiatCurrency::where('id', $this->currency)->first();

        if (! $currency) {
            return false;
        }

        $this->symbol = $currency->symbol;
        $this->limit = $currency->deposit_min_decimal;

        if ((float) $this->amount <= 0 || $this->amount < $this->limit) {
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
        return __('warnings.minimum_deposit_amount_is')." $this->limit $this->symbol";
    }
}
