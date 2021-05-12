<?php

namespace App\Models;

use App\Traits\HasMoneyFieldsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FiatCurrency extends Model
{
    const ID_TRY = 1;

    const ID_USD = 2;

    use SoftDeletes, HasMoneyFieldsTrait;

    protected $cryptoFields = ['withdraw_fee', 'withdraw_min', 'withdraw_max_daily', 'withdraw_max_monthly', 'deposit_min'];

    protected $table = 'fiat_currencies';

    protected $decimalsFrom = false;

    public function toArray()
    {
        $array = parent::toArray();
        $this->addMoneyFieldToArray($array);

        return $array;
    }
}
