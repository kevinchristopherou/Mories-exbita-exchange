<?php

namespace App\Rules;

use App\Helpers\MoneyHelper;
use App\Models\FiatCurrency;
use App\Models\FiatWallet;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class FiatWithdrawCheckAmountRule implements Rule
{
    public $currency_id;

    public $amount;

    public $msg;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->currency_id = $data['currency_id'];
        $this->amount = $data['amount'];
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

        $wallet = FiatWallet::where('user_id', $user->id)->where('fiat_currency_id', $this->currency_id)->first();
        $amount = MoneyHelper::parseCrypto($this->amount, $wallet->currency->decimals);

        if (! $wallet || $wallet->available_balance->lessThan($amount)) {
            $this->msg = __('validation.custom.orders.insufficient_balance');

            return false;
        }

        $currency = FiatCurrency::find($this->currency_id);

        if ($currency->withdraw_max_daily_decimal > 0) {
            $dailyTotal = Withdraw::where('user_id', $user->id)
                ->where('currency_id', $this->currency_id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->where('status', '<>', 'rejected')
                ->sum('amount');

            if ($dailyTotal + $amount->getAmount() > $currency->withdraw_max_daily->getAmount()) {
                $this->msg = __('validation.custom.fiat_withdraw.daily_limit_exceeds');

                return false;
            }
        }

        if ($currency->withdraw_max_monthly_decimal > 0) {
            $monthlyTotal = Withdraw::where('user_id', $user->id)
                ->where('currency_id', $this->currency_id)
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', '<>', 'rejected')
                ->sum('amount');

            if ($monthlyTotal + $amount->getAmount() > $currency->withdraw_max_monthly->getAmount()) {
                $this->msg = __('validation.custom.fiat_withdraw.monthly_limit_exceeds');

                return false;
            }
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
        return $this->msg;
    }
}
