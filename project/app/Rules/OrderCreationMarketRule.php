<?php

namespace App\Rules;

use App\Models\Order;
use Illuminate\Contracts\Validation\Rule;

class OrderCreationMarketRule implements Rule
{
    public $order_type;

    public $quantity;

    public $rate;

    public $market;

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
        // Check the buyer's balance
        if ($this->order_type == 'BUY') {

            //Try to find at least one sell_limit order
            $order = Order::where('type', 'SELL_LIMIT')
                ->where('market_id', $this->market->id)
                ->where('quantity_remaining', '>', 0)
                ->orderBy('rate', 'ASC')
                ->first();

            if (! $order) {
                $this->error_message = __('validation.custom.orders.no_matching_sell_order');

                return false;
            }
        }

        // Check the seller's balance
        if ($this->order_type == 'SELL') {

            //Try to find at least one buy_limit order
            $order = Order::where('type', 'BUY_LIMIT')
                ->where('market_id', $this->market->id)
                ->where('quantity_remaining', '>', 0)
                ->active()
                ->where('pending_amount', '>', 0)
                ->first();

            if (! $order) {
                $this->error_message = __('validation.custom.orders.no_matching_buy_order');

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
