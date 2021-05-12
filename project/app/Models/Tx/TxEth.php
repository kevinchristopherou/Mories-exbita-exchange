<?php

namespace App\Models\Tx;

use App\Models\Currency;
use App\Traits\HasMoneyFieldsTrait;

class TxEth extends Tx
{
    use HasMoneyFieldsTrait;

    protected $cryptoFields = ['amount'];

    protected $curreny_id = Currency::ID_ETH;

    protected $table = 'tx_eth';
}
