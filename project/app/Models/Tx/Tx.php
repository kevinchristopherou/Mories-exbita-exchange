<?php

namespace App\Models\Tx;

use App\Models\Wallet;
use App\Models\WalletAddress;
use Illuminate\Database\Eloquent\Model;

abstract class Tx extends Model
{
    protected $decimalsFrom = 'wallet';

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function walletAddress()
    {
        return $this->belongsTo(WalletAddress::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
