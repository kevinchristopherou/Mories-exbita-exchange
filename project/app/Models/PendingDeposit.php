<?php

namespace App\Models;

use App\Traits\HasMoneyFieldsTrait;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;

class PendingDeposit extends Model
{
    protected $with = ['currency'];

    use HasMoneyFieldsTrait, SearchableTrait;

    protected $searchable = ['id', 'currency_id', 'txid', 'user:email', 'created_at', 'amount'];

    protected $cryptoFields = ['amount'];

    protected $decimalsFrom = '';

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
        return $this->belongsTo(Currency::class);
    }
}
