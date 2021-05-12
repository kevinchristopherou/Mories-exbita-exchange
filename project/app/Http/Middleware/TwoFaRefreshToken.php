<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use League\OAuth2\Server\CryptTrait;

/**
 * This middleware intercepts the oauth/token requests with grant_type: refresh_token.
 *
 * If a user has 2FA enabled, two things are handled with this middleware;
 *
 * 1- He should not be able to use refresh token of a non-verified access token
 * 2- New access_token obtained via refresh_token should be pre-verified so that user does not have to re-enter 2fa otp.
 *
 * Class PostRefreshToken
 */
class TwoFaRefreshToken
{
    use CryptTrait;

    public $verifyNewtoken = false;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isRefresh = ($request->path() === 'oauth/token' && $request->get('grant_type') === 'refresh_token');

        if ($isRefresh) {
            //we'll check the refresh token before laravel passport does.
            //If user has 2fa enabled, we should not allow the use of a refresh token whose access token is not verified
            try {
                $this->verifyNewtoken = $this->twoFaCheck($request->get('refresh_token'));
            } catch (\Exception $e) {
                return $this->sendError();
            }
        }

        $response = $next($request);

        if ($isRefresh) {
            //If new token needs to be verified
            if ($this->verifyNewtoken) {
                //Extract the token from response
                $responseJson = json_decode($response->content());
                //Verify its associated access token
                $this->verifyToken($responseJson->refresh_token);
            }
        }

        return $response;
    }

    private function sendError()
    {
        return response()->json(['message' => 'invalid_token'], 401);
    }

    /**
     * @param $refreshToken
     * @return bool
     * @throws \Exception
     */
    private function twoFaCheck($refreshToken)
    {
        $this->setEncryptionKey(app('encrypter')->getKey());
        $tokenDecrpyted = $this->decrypt($refreshToken);
        $tokenDetails = json_decode($tokenDecrpyted);
        if (! $tokenDetails) {
            throw new \Exception();
        }

        $accessToken = DB::table('oauth_access_tokens')
            ->where('id', $tokenDetails->access_token_id)
            ->first();

        if ($accessToken === null) {
            throw new \Exception();
        }

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('id', $tokenDetails->refresh_token_id)
            ->first();

        if ($refreshToken === null) {
            throw new \Exception();
        }

        $user = DB::table('users')->where('id', $accessToken->user_id)->first();

        if ($user === null) {
            throw new \Exception();
        }

        //If 2fa enabled
        if ($user->two_fa_enabled) {
            //and token is not verified
            if (! $accessToken->two_fa_verified) {
                throw new \Exception();
            }

            return true;
        }

        //Access token and refresh token revoked checks are already done by laravel passport

        return false;
    }

    /**
     * Marks the associated access_token of the given refresh token as two_fa_verified.
     * @param $refreshToken
     */
    private function verifyToken($refreshToken)
    {
        $tokenDecrpyted = $this->decrypt($refreshToken);
        $tokenDetails = json_decode($tokenDecrpyted);

        DB::table('oauth_access_tokens')
            ->where('id', $tokenDetails->access_token_id)
            ->update(['two_fa_verified' => true]);
    }
}
