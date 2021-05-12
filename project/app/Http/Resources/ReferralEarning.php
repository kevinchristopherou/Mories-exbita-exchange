<?php

namespace App\Http\Resources;

use App\Helpers\MoneyHelper;
use Illuminate\Http\Resources\Json\Resource;

class ReferralEarning extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $currency = $this->getCurrency($this->currency_type);

        $data = [
            'currency' => $currency->symbol,
            'date' => $this->created_at->toIso8601String(),
            'amount' => MoneyHelper::toDecimal($this->amount, $currency->decimals),
        ];

        if (auth('api')->user() && auth('api')->user()->hasRole('admin')) {
            $data['user'] = $this->user;
            $data['referrer'] = $this->referrer;
        }

        return $data;
    }
}
