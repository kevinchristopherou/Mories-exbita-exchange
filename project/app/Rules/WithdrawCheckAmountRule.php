<?php

namespace App\Rules;

use App\Helpers\MoneyHelper;
use App\Models\Wallet;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class WithdrawCheckAmountRule implements Rule
{
    public $currency;

    public $amount;

    public $msg;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($currency, $amount)
    {
        $this->currency = $currency;
        $this->amount = $amount;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = auth()->user();

        $wallet = Wallet::where('user_id', $user->id)->where('currency_id', $this->currency->id)->first();
        if (! $wallet) {
            return false;
        }
        $fee = $this->currency->fee_withdraw;

        $cost = MoneyHelper::parseCrypto($this->amount, $this->currency->decimals)->add($fee);
        if ($wallet->available_balance->lessThan($cost)) {
            $this->msg = 'validation.custom.orders.insufficient_balance';

            return false;
        }

        if (MoneyHelper::parseCrypto($this->amount, $this->currency->decimals)->lessThan($this->currency->min_withdraw)) {
            $this->msg = 'validation.custom.withdraw.less_than_min_amount';

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
        return __($this->msg);
    }
}
