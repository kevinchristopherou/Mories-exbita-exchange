<?php

namespace App\Rules;

use App\Models\Order;
use Illuminate\Contracts\Validation\Rule;

class OrderAccessRule implements Rule
{
    public $user = null;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        return Order::where('uuid', $value)
                ->withTrashed()
                ->where('user_id', $this->user->id)
                ->where(function ($query) {
                    $query->orWhere('type', 'BUY_LIMIT');
                    $query->orWhere('type', 'SELL_LIMIT');
                })->count() == 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute is not found.');
    }
}
