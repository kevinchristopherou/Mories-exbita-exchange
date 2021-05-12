<?php

namespace App\Traits;

use App\Mail\UserNewIpAllowEmail;
use App\Models\LoginAttempt;
use App\Models\UserAllowedIp;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use League\OAuth2\Server\Exception\OAuthServerException;

trait UserPassportTrait
{
    public function revokeAllTokens()
    {
        $this->tokens()->get()->each(function ($token) {
            $token->revoke();
            $token->delete();
        });
    }

    public function revokeOtherTokens($currentToken)
    {
        $this->tokens()->get()->each(function ($token) use ($currentToken) {
            if ($currentToken->id !== $token->id) {
                $token->revoke();
                $token->delete();
            }
        });
    }

    /**
     * This is where we intercept laravel passport's login (oauth/token) by adding additional
     * checks such as account status, account lock etc.
     */
    public function validateForPassportPasswordGrant($password)
    {
        if (! Hash::check($password, $this->getAuthPassword())) {
            return false;
        }

        if ($this->status == 0) {
            throw new OAuthServerException('User account is not activated', 6, 'account_inactive', 401);
        }

        if ($this->status == 2) {
            throw new OAuthServerException('User account is disabled', 6, 'account_disabled', 401);
        }

        //@todo: return a new type of error for this.
        if ($this->is_blocked == 1) {
            throw new OAuthServerException('User account is disabled', 6, 'account_disabled', 401);
        }

        if ($this->last_password_reset !== null) {
            if ($this->last_password_reset->gte(Carbon::now()->subHour(24))) {
                throw new OAuthServerException('User account is locked', 6, 'account_locked_24h', 401);
            }
        }

        //We'll check if user has allowed this ip before. We should not check if this is user's first successful login after signup. The IP of the first login automatically gets added as allowed IP. Also skip this check in phpunit run.
        if (config('app.phpunit') === 'no') {
            $this->checkAllowedIp();
        }

        return true;
    }

    public function checkAllowedIp()
    {
        // Demo version only!
        return true;

        $ip = $this->getRequestIp();

        //Skip ip check if this is the first login.
        if (! LoginAttempt::isFirstLogin($this->email)) {
            //Check if the ip is allowed
            if (! UserAllowedIp::isIpAllowed($ip, $this->id)) {
                $this->sendIpAllowEmail($ip, $this);
                //We should not log this as a failed login attempt, to return 403 instead of 401
                throw new OAuthServerException('Ip is not allowed', 6, 'account_ip_not_allowed', 403);
            }
        }

        //If this is the first login, make this ip address verified
        if (LoginAttempt::isFirstLogin($this->email)) {
            $allowedIp = new UserAllowedIp();
            $allowedIp->user_id = $this->id;
            $allowedIp->ip = $ip;
            $allowedIp->verify_token = User::generateToken();
            $allowedIp->verified = 1;

            $allowedIp->save();
        }
    }

    public function sendIpAllowEmail($ip)
    {
        $allowedIp = UserAllowedIp::requestAccess($ip, $this);
        //if token is returned null, this means access to this ip is already granted, no need to send email
        if ($allowedIp !== null) {
            $mail = (new UserNewIpAllowEmail($this, $allowedIp))->onQueue('emails');
            Mail::to($this)->send($mail);
        }
    }

    /**
     * If request is coming from internal api client, real ip address of the
     * request is sent in client_ip_address value. In that case we extract that ip.
     *
     * @return string
     */
    private function getRequestIp()
    {
        $ip = request()->ip();
        if ($ip == '127.0.0.1' && request('client_ip_address')) {
            $ip = request('client_ip_address');
        }

        return $ip;
    }
}
