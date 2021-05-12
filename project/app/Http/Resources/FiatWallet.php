<?php

namespace App\Http\Resources;

use App\Helpers\MoneyHelper;
use Illuminate\Http\Resources\Json\Resource;

class FiatWallet extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fiat_currency_id' => $this->fiat_currency_id,
            'currency' => $this->currency->symbol,
            'available' => $this->available_balance_decimal,
            'pending' => $this->pending_balance_decimal,
            'withdraw_pending' => $this->withdraw_pending_balance_decimal,
            'total_balance' => MoneyHelper::toDecimal($this->available_balance->add($this->pending_balance)->add($this->withdraw_pending_balance), $this->currency->decimals),
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
