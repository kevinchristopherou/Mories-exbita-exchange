<?php

namespace App\Models;

use App\Models\Tx\TxBtc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletAddress extends Model
{
    use SoftDeletes;

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function deposits()
    {
        return $this->hasMany(WalletTransaction::class)->where('type', 'receive');
    }

    public function withdraws()
    {
        return $this->hasMany(WalletTransaction::class)->where('type', 'send');
    }

    public function scopeCurrencyId($query, $id)
    {
        return $query->whereHas('wallet', function ($q) use ($id) {
            return $q->where('currency_id', $id);
        });
    }
}
