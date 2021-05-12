<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MarketHistory extends Resource
{
    public function toArray($request)
    {
        return [
            'type' => $this->type,
            'rate' => $this->rate_decimal,
            'amount' => $this->crypto_amount_decimal,
            'time' => $this->created_at->toIso8601String(),
        ];
    }
}
