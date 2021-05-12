<?php

namespace App\Models;

use App\Traits\HasMoneyFieldsTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;

class ReferralEarning extends Model
{
    protected $with = ['currency'];

    use HasMoneyFieldsTrait, SearchableTrait;

    protected $searchable = ['id', 'currency_id', 'created_at', 'amount'];

    protected $cryptoFields = ['amount'];

    protected $decimalsFrom = '';

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function fiatCurrency()
    {
        return $this->belongsTo(FiatCurrency::class, 'currency_id');
    }

    public function getCurrency($type)
    {
        if($type == "fiat") return $this->fiatCurrency;

        return $this->currency;
    }

}
