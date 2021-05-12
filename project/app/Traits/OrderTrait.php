<?php

namespace App\Traits;

use Webpatser\Uuid\Uuid;

trait OrderTrait
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Uuid::generate()->string;
        });
    }
}
