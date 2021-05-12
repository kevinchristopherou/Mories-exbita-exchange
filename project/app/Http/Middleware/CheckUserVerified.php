<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;

class CheckUserVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $kyc_state = config('settings.KYC_STATE');
        \Log::info($kyc_state);
        if (! $request->user()->id_verified && $kyc_state) {
            $code = 403;

            return response()->json([
                'error' => [
                    'code' => $code,
                    'message' => 'Forbidden.',
                ],
            ], $code);
        }

        return $next($request);
    }
}
