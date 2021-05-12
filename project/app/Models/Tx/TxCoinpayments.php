<?php

namespace App\Models\Tx;

use App\Traits\HasMoneyFieldsTrait;

class TxCoinpayments extends Tx
{
    use HasMoneyFieldsTrait;

    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_WAITING = 'waiting';
    const STATUS_PROCESSED = 'processed';

    protected $cryptoFields = ['amount', 'fee'];

    protected $curreny_id = null;

    protected $table = 'tx_coinpayments';

    public function scopeConfirmed($query) {
        return $query->where('app_status', TxCoinpayments::STATUS_CONFIRMED);
    }

    public function scopeProcessed($query) {
        return $query->where('app_status', TxCoinpayments::STATUS_PROCESSED);
    }

    public function scopeNotProcessed($query) {
        return $query->where('app_status', '!=', TxCoinpayments::STATUS_PROCESSED);
    }

    public function scopeDepositable($query) {
        return $query->where('ignore_deposit', false);
    }
}
