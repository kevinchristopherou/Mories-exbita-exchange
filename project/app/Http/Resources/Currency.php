<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Currency extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'currency' => $this->symbol,
            'currency_long' => $this->name,
            'min_deposit' => $this->min_deposit_decimal,
            'fee_withdraw' => $this->fee_withdraw_decimal,
            'min_withdraw' => $this->min_withdraw_decimal,
            'maintenance' => $this->maintenance,
        ];

        if (auth('api')->user() && auth('api')->user()->hasRole('admin')) {
            $data['account_balance'] = $this->account_balance_decimal;
            $data['withdraw_pending'] = $this->withdraw_pending_decimal;
            $data['withdraw_pending_count'] = $this->withdraw_pending_count;
            $data['decimals'] = $this->decimals;
            $data['type'] = $this->type;
            $data['extra_data'] = $this->extra_data;
        }

        return $data;
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
