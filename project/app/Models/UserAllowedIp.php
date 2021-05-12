<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserAllowedIp extends Model
{
    const UPDATED_AT = null;

    public static function isIpAllowed($ip, $user_id)
    {
        $count = self::where('ip', $ip)
            ->where('user_id', $user_id)
            ->where('verified', 1)
            ->count();

        return $count >= 1;
    }

    public static function requestAccess($ip, $user)
    {
        $allowedIp = self::where('ip', $ip)
            ->where('user_id', $user->id)
            ->first();

        if ($allowedIp !== null) {
            if ($allowedIp->verified) {
                return;
            }

            return $allowedIp;
        }

        $allowedIp = new self();
        $allowedIp->user_id = $user->id;
        $allowedIp->ip = $ip;
        $allowedIp->verify_token = User::generateToken();
        $allowedIp->verified = 0;

        $allowedIp->save();

        return $allowedIp;
    }
}
