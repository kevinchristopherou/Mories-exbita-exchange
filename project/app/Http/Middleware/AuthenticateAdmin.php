<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;

class AuthenticateAdmin
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
        if (! auth()->user() || ! auth()->user()->can('access.admin')) {
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
