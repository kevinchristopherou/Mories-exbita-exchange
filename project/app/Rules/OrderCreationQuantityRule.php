<?php

namespace App\Rules;

use App\Helpers\ExchangeHelper;
use App\Helpers\MoneyHelper;
use App\Models\FiatWallet;
use App\Models\Wallet;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class OrderCreationQuantityRule implements Rule
{
    public $order_type;

    public $quantity;

    public $rate;

    public $market;

    public $error_message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($data, $market)
    {
        $this->market = $market;
        $this->order_type = $data['order_type'];
        $this->quantity = $data['quantity'];
        $this->rate = $data['rate'] ? $data['rate'] : 0;
        $this->user_id = $data['user_id'];
        $this->error_message = 'validation.custom.orders.insufficient_balance';
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
        //Buy amount min value check for quick buy.
        if ($this->order_type === 'BUY' && $value < $this->market->min_trade_size_quote) {
            $minValue = round($this->market->min_trade_size_quote, 2).' '.$this->market->getQuoteCurrency()->symbol;
            $this->error_message = __('validation.custom.orders.less_than_min_trade_size', ['min_trade_size' => $minValue]);

            return false;
        } //Limit order quantity or quick sell quantity min value check
        elseif ($value < $this->market->min_trade_size) {
            $minValue = $this->market->min_trade_size.' '.$this->market->getCurrency()->symbol;
            $this->error_message = __('validation.custom.orders.less_than_min_trade_size', ['min_trade_size' => $minValue]);

            return false;
        }

        // Check the buyer's balance
        if ($this->order_type == 'BUY' || $this->order_type == 'BUY_LIMIT') {
            if($this->market->isQuoteCurrencyFiat()) {
                $quoteWallet = FiatWallet::where('user_id', $this->user_id)->where('fiat_currency_id', $this->market->getQuoteCurrency()->id)->first();
            } else {
                $quoteWallet = Wallet::where('user_id', $this->user_id)->where('currency_id', $this->market->getQuoteCurrency()->id)->first();
            }
            //Check balance for quantity + costs
            if ($this->order_type == 'BUY_LIMIT') {
                $this->rate = MoneyHelper::parseCrypto($this->rate, $this->market->quoteCurrencyDecimals());
                $cost = $this->rate->multiply($this->quantity);

                Log::error('1:' . $this->quantity);
                Log::error('2:' . $this->market->quoteCurrencyDecimals());
                Log::error($this->rate->getAmount());
                Log::error('4:' . $cost->getAmount());

                $costWithCommission = ExchangeHelper::getTotalCommission($cost)->add($cost);
                Log::error($costWithCommission->getAmount());
                Log::error($quoteWallet->available_balance->getAmount());
                if (! $quoteWallet || $quoteWallet->available_balance->lessThan($costWithCommission)) {
                    return false;
                }
            } //Check balance for quantity
            else {
                $this->quantity = MoneyHelper::parseCrypto($this->quantity, $this->market->quoteCurrencyDecimals());

                if (! $quoteWallet || $quoteWallet->available_balance->lessThan($this->quantity)) {
                    return false;
                }
            }
        }

        // Check the seller's balance
        if ($this->order_type == 'SELL' || $this->order_type == 'SELL_LIMIT') {
            if($this->market->isCurrencyFiat()) {
                $wallet = FiatWallet::where('user_id', $this->user_id)->where('fiat_currency_id', $this->market->getCurrency()->id)->first();
            } else {
                $wallet = Wallet::where('user_id', $this->user_id)->where('currency_id', $this->market->getCurrency()->id)->first();
            }

            if (! $wallet || $wallet->available_balance->lessThan(MoneyHelper::parseCrypto($this->quantity, $this->market->currencyDecimals()))) {
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
        return __($this->error_message);
    }
}
