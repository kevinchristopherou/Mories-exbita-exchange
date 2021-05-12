<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use PragmaRX\Google2FA\Google2FA;

class VerifyTwoFaOtp extends Middleware
{
    public $error = false;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        $message = 'two_fa_incorrect_code';

        if ($user->two_fa_enabled) {
            $otp = $request->get('otp');

            if ($user->two_fa_method == 'ga') {
                if (mb_strlen($otp) < 6) {
                    $message = 'two_fa_incorrect_length';
                    $this->error = true;
                } else {
                    $google2fa = new Google2FA();

                    if (! $google2fa->verify($otp, decrypt($user->two_fa_secret))) {
                        $this->error = true;
                    }
                }
            } elseif ($user->two_fa_method == 'sms') {
                if (mb_strlen($otp) < 6) {
                    $message = 'two_fa_incorrect_length';
                    $this->error = true;
                } else {
                    if (! $user->two_fa_otp || $user->two_fa_otp != $otp) {
                        $this->error = true;
                    } //If two_fa matches, reset it to prevent reuse.
                    else {
                        $user->two_fa_otp = null;
                        $user->update();
                    }
                }
            }

            if ($this->error) {
                return response()->json(['message' => $message, 'two_fa_method' => $user->two_fa_method], 422);
            }
        }

        return $next($request);
    }
}
