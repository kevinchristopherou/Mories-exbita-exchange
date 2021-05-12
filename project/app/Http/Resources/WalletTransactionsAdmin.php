<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class WalletTransactionsAdmin extends Resource
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
            'user_id' => $this->user_id,
            'user_email' => $this->user->email,
            'txid' => $this->txid,
            'amount' => $this->amount_decimal,
            'currency' => $this->currency->symbol,
            'address' => $this->walletAddress,
            'date' => $this->created_at->toIso8601String(),
            'ignored_deposit' => $this->ignored_deposit,
        ];
    }
}
