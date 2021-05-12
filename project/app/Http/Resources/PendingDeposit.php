<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PendingDeposit extends Resource
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
            'currency' => $this->currency->symbol,
            'amount' => $this->amount_decimal,
            'date' => $this->created_at->toIso8601String(),
            'address' => $this->walletAddress->address,
            'confirmations' => $this->confirmations,
        ];
    }
}
