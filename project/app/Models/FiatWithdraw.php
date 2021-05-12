<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class FiatWithdraw extends Model
{
    use SearchableTrait, SortableTrait;

    protected $table = 'fiat_withdrawal';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'evaluated_at';

    public $searchable = ['id', 'user_id', 'amount', 'bank_name', 'status', 'iban', 'recipient', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_user_id');
    }

    public function fiatCurrency()
    {
        return $this->belongsTo(\App\Models\FiatCurrency::class, 'fiat_currency_id');
    }
}
