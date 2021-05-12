<?php

namespace App\Http\Middleware;

use Closure;

class VerifyAccessToken
{
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

        if ($user->two_fa_enabled && ! $user->token()->two_fa_verified) {
            //The HyperText Transfer Protocol (HTTP) 412 Precondition Failed client error response code indicates that access to the target resource has been denied.
            return response()->json(['message' => 'token_not_verified', 'two_fa_method' => $user->two_fa_method], 412);
        }

        return $next($request);
    }
}
