<?php

namespace App\Rules;

use App\Models\Currency;
use App\Models\Withdraw;
use Illuminate\Contracts\Validation\Rule;

class AddressValidationRule implements Rule
{
    public $currency;

    public $address;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($currency, $address)
    {
        $this->currency = $currency;
        $this->address = $address;
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
        //Firstly lets check if there's a previous withdraw to this address
        $knownAddress = Withdraw::where('address', $this->address)->count();
        if ($knownAddress) {
            return true;
        }

        $symbol = $this->currency->symbol;

        if($this->currency->type == Currency::TYPE_ERC_20_TOKEN) {
            $symbol = Currency::ETH_SYMBOL;
        } elseif($this->currency->type == Currency::TYPE_COINPAYMENTS) {
            $symbol = Currency::TYPE_COINPAYMENTS;
        }

        //If address is not used before, try to validate it via that currency's gateway validate method.
        $facadeName = 'App\Facades\Api\Gateway'.ucfirst(strtolower($symbol));

        return $facadeName::validate($this->address);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.withdraw.invalid_recipient_address');
    }
}
