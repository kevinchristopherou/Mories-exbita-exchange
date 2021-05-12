<?php

namespace App\Models;

use App\Traits\HasMoneyFieldsTrait;
use Illuminate\Database\Eloquent\Model;

class MarketHistory extends Model
{
    //@ToDo: add decimals from

    use HasMoneyFieldsTrait;

    protected $dates = ['timestamp'];

    protected $cryptoFields = ['v', 'h', 'l', 'o', 'c'];

    protected $cryptoQuoteFields = ['h', 'l', 'o', 'c'];

    protected $decimalsFrom = 'market';

    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }

    public function getUnixTimestampAttribute()
    {
        return $this->timestamp->timestamp;
    }
}
