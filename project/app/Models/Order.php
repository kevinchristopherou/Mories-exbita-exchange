<?php

namespace App\Models;

use App\Helpers\MoneyHelper;
use App\Traits\FieldUpdateTrait;
use App\Traits\HasMoneyFieldsTrait;
use App\Traits\OrderTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use OrderTrait, SoftDeletes, FieldUpdateTrait, HasMoneyFieldsTrait;

    const STATUS_NO_FILL = 0;

    const STATUS_PARTIAL_FILL = 1;

    const STATUS_FILLED = 2;

    public $incrementing = false;

    public $primaryKey = 'uuid';
    protected $keyType = 'string';


    protected $fillable = ['uuid', 'user_id', 'market_id', 'quantity', 'quantity_remaining', 'amount', 'pending_amount', 'fill_status', 'rate', 'type'];

    protected $cryptoFields = ['quantity', 'quantity_remaining', 'rate', 'rate_actual', 'amount', 'pending_amount'];

    protected $cryptoQuoteFields = ['rate', 'rate_actual', 'amount', 'pending_amount'];

    protected $decimalsFrom = 'market';

    /**
     * Scope a query to only include order by defined days.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDaysAgo($query, $days)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDay($days));
    }

    public function scopeType($query, $type)
    {
        if ($type == 'sell') {
            return $query->whereIn('type', ['SELL', 'SELL_LIMIT']);
        } elseif ($type == 'buy') {
            return $query->whereIn('type', ['BUY', 'BUY_LIMIT']);
        }

        return $query;
    }

    public function scopeActive($query)
    {
        return $query->where('fill_status', '<>', self::STATUS_FILLED);
    }

    public function scopeFilled($query)
    {
        return $query->where(function ($query) {
            $query
                ->where('fill_status', self::STATUS_FILLED)
                ->orWhere(function ($query) {
                    $query
                        ->where('fill_status', self::STATUS_PARTIAL_FILL)
                        ->whereNotNull('deleted_at');
                });
        })->withTrashed();
    }

    public function market()
    {
        return $this->belongsTo(\App\Models\Market::class, 'market_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'order_uuid');
    }

    public function toArrayMyOrders()
    {
        return [
            'uuid' => $this->uuid,
            'market' => $this->market->name,
            'market_id' => $this->market_id,
            'type' => $this->type,
            'quantity' => $this->type === 'BUY' ? $this->amount_decimal : $this->quantity_decimal,
            'quantity_remaining' => $this->type === 'BUY' ? $this->amount_remaining_decimal : $this->quantity_remaining_decimal,
            'amount_filled' => $this->type === 'BUY' ? $this->amount_decimal - $this->pending_amount_decimal : $this->quantity_decimal - $this->quantity_remaining_decimal,
            'rate' => $this->rate_decimal,
            'rate_actual' => $this->rate_actual_decimal,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->toIso8601String() : null,
        ];
    }

    public function toArrayOrderBook()
    {
        return [
            'type' => $this->type,
            'rate' => $this->rate_decimal,
            'quantity' => $this->quantity_remaining_decimal,
            'uuid' => $this->uuid,
        ];
    }

    public function isQuick()
    {
        return $this->type == 'BUY' || $this->type == 'SELL';
    }

    public function calculateFillStatus()
    {
        if ($this->type === 'SELL_LIMIT' || $this->type === 'BUY_LIMIT' || $this->type === 'SELL') {
            //order is filled if quantity remaining is almost zero (sometimes only 1 or 2 satoshi remains due to rounding
            if ($this->quantity_remaining->getAmount() < 3) {
                return self::STATUS_FILLED;
            }
            if ($this->quantity_remaining->getAmount() == $this->quantity->getAmount()) {
                return self::STATUS_NO_FILL;
            }

            return self::STATUS_PARTIAL_FILL;
        } elseif ($this->type === 'BUY') {
            //order is filled if pending amount is almost zero (sometimes only 1 or 2 pennies remains due to rounding
            if ($this->pending_amount->getAmount() < 3) {
                return self::STATUS_FILLED;
            }
            if ($this->pending_amount->getAmount() == $this->amount->getAmount()) {
                return self::STATUS_NO_FILL;
            }

            return self::STATUS_PARTIAL_FILL;
        }
    }

    public function updateActualRate($type = 'base')
    {
        $this->load('transactions');
        $this->load('market');

        $transactions = $this->transactions;
        if ($transactions->isEmpty()) {
            return;
        }

        $weightedSum = 0;
        $quantity = 0;

        foreach ($transactions as $transaction) {
            $weightedSum += $transaction->rate_decimal * $transaction->crypto_amount_decimal;

            $quantity += $transaction->crypto_amount_decimal;
        }

        $actualRate = $weightedSum / $quantity;

        if($type == 'quote') {
            $this->setField('rate_actual', MoneyHelper::parseCrypto($actualRate, $this->market->getQuoteCurrency()->decimals)->getAmount());
        } else {
            $this->setField('rate_actual', MoneyHelper::parseCrypto($actualRate, $this->market->getQuoteCurrency()->decimals)->getAmount());
        }
    }
}
