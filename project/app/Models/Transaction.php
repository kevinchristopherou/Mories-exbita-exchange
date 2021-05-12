<?php

namespace App\Models;

use App\Helpers\MoneyHelper;
use App\Traits\HasMoneyFieldsTrait;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasMoneyFieldsTrait;

    const UPDATED_AT = null;

    protected $fillable = ['user_id', 'tx_uuid', 'order_uuid', 'matched_order_uuid', 'market_id', 'market', 'type', 'is_triggered', 'rate', 'commission', 'tax', 'referral_cut', 'quote_amount', 'crypto_amount'];

    protected $cryptoFields = ['crypto_amount', 'quote_amount', 'rate', 'tax', 'commission', 'referral_cut'];

    protected $cryptoQuoteFields = ['rate'];

    protected $decimalsFrom = '_market';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'uuid')->withTrashed();
    }

    public function _market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }
}
