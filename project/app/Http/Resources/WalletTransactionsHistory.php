<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class WalletTransactionsHistory extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'txid' => $this->txid,
            'amount' => $this->amount_decimal,
            'currency' => $this->currency->symbol,
            'date' => $this->created_at->toIso8601String(),
        ];
    }
}
