<?php

namespace App\Models\Tx;

use App\Models\Currency;
use App\Models\Wallet;
use App\Traits\HasMoneyFieldsTrait;

class TxErc extends Tx
{
    use HasMoneyFieldsTrait;

    protected $cryptoFields = ['amount'];

    protected $curreny_id = null;

    protected $table = 'tx_erc';

}
