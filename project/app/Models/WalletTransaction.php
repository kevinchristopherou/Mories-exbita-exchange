<?php

namespace App\Models;

use App\Traits\HasMoneyFieldsTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;

class WalletTransaction extends Model
{
    protected $with = ['currency'];

    use HasMoneyFieldsTrait, SearchableTrait;

    protected $searchable = ['id', 'currency_id', 'txid', 'user:email', 'created_at', 'amount'];

    protected $cryptoFields = ['amount', 'fee', 'main_balance_after', 'tx_fee'];

    protected $decimalsFrom = '';

    const UPDATED_AT = null;

    public function walletAddress()
    {
        return $this->belongsTo(WalletAddress::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class)
            ->withTrashed();
    }
}
