<?php

namespace App\Rules;

use App\Models\FiatWallet;
use App\Models\Market;
use App\Models\Order;
use App\Models\Wallet;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class FieldZeroRule implements Rule
{
    private $attribute;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;
        if ((float) $value > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.field_non_zero');
    }
}
