<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'evaluated_at';

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
