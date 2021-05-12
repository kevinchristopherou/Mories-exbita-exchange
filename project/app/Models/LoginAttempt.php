<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    const UPDATED_AT = null;

    public static function isCaptchaRequired($ip)
    {
        $lastLoginAttempt = self::where('ip', $ip)
            ->where('created_at', '>=', Carbon::now()->subMinutes(10))
            ->latest()
            ->first();

        if ($lastLoginAttempt && $lastLoginAttempt->status == 401) {
            return true;
        }

        return false;
    }

    public static function isFirstLogin($email)
    {
        //Check if first login?
        $count = self::where('email', $email)->where('status', 200)->count();

        if ($count === 0) {
            return true;
        }
    }
}
