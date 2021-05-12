<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletNonce extends Model
{
    use SoftDeletes;

    protected $fillable = ['address', 'nonce'];
}
