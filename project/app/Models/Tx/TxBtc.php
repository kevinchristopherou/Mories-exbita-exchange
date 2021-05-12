<?php

namespace App\Models\Tx;

use App\Models\Currency;
use App\Traits\HasMoneyFieldsTrait;

class TxBtc extends Tx
{
    use HasMoneyFieldsTrait;

    protected $cryptoFields = ['amount'];

    protected $curreny_id = Currency::ID_BTC;

    protected $table = 'tx_btc';
}
