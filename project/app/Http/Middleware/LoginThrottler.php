<?php

namespace App\Http\Middleware;

use App\Mail\UserNewLoginEmail;
use App\Models\LoginAttempt;
use App\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Mail;

class LoginThrottler
{
    const INTERVAL = 60;    //seconds

    const MAX_REQUESTS = 5; //Max requests allowed in interval

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->path() === 'api/'.config('app.api_version').'/user/login') {
            if ($this->maxTriesReached($request, 'email')) {
                return $this->throttleError();
            }
        }

        if ($request->path() === 'oauth/token' && $request->get('grant_type') == 'password') {
            if ($request->ip() !== '127.0.0.1') {
                if ($this->maxTriesReached($request, 'username')) {
                    return $this->throttleError();
                }
            }
        }

        $response = $next($request);

        //Only log successful and credentials failed logins
        //No need to log attempts failed due to form/re-captcha validation results with 422
        if ($response->status() === 401 || $response->status() === 200) {

            //Login requests coming from web app
            if ($request->path() === 'api/'.config('app.api_version').'/user/login') {
                $log = new LoginAttempt();
                $log->email = $request->get('email');
                $log->ip = $request->ip();
                $log->agent = $request->userAgent();
                $log->agent_hash = md5($log->agent);
                $log->client_id = 0;
                $log->status = $response->status();
                $log->save();

                if ($response->status() === 200) {
                    $this->sendLoginEmail($log->email, $request);
                }
            }
            //Don't login oauth/token requests coming from InternalApiClient
            if ($request->path() === 'oauth/token' && $request->get('grant_type') == 'password') {
                if ($request->ip() !== '127.0.0.1') {
                    $log = new LoginAttempt();
                    $log->email = $request->get('username', '-');
                    $log->ip = $request->ip();
                    $log->agent = $request->userAgent();
                    $log->agent_hash = md5($log->agent);
                    $log->client_id = $request->get('client_id');
                    $log->status = $response->status();
                    $log->save();

                    if ($response->status() === 200) {
                        $this->sendLoginEmail($log->email, $request);
                    }
                }
            }
        }

        return $response;
    }

    private function throttleError()
    {
        return response()->json(['message' => 'too_many_requests'], 429);
    }

    private function maxTriesReached($request, $identifierField)
    {
        $email = $request->get($identifierField);
        $count = LoginAttempt::where('email', $email)
            ->where('created_at', '>', Carbon::now()->subSeconds(self::INTERVAL))
            ->where('status', '!=', 200)
            ->count();

        if ($count > self::MAX_REQUESTS * 100) {
            //todo: We may lock the account, some one is trying a brute force attack on the same user
            return true;
        }

        $count = LoginAttempt::where('ip', $request->ip())
            ->where('created_at', '>', Carbon::now()->subSeconds(self::INTERVAL))
            ->where('status', '!=', 200)
            ->count();

        if ($count > self::MAX_REQUESTS) {
            return true;
        }

        return false;
    }

    private function sendLoginEmail($email, $request)
    {
        $user = User::where('email', $email)->first();
        if (! $user->two_fa_enabled) {
            $mail = (new UserNewLoginEmail($user, $request))->onQueue('emails');
            Mail::to($user)->queue($mail);
        }
    }
}
