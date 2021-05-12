<?php

namespace App\Http\Middleware;

use Closure;

class DemoOnly
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
        if ($request->method() == 'GET') {

            // 2FA
            if(mb_strpos($request->getPathInfo(), 'twofa/setupcomplete') == false && mb_strpos($request->getPathInfo(), 'theme-editor') == false && mb_strpos($request->getPathInfo(), 'user/block') == false) {
                return $next($request);
            }
        }

        if ($request->method() == 'POST') {
            // Login, Logout
            if (mb_strpos($request->getPathInfo(), 'logout') !== false || mb_strpos($request->getPathInfo(), 'login')) {
                return $next($request);

            }

            //Orders
            if (mb_strpos($request->getPathInfo(), 'order/buy') !== false || mb_strpos($request->getPathInfo(), 'order/sell') !== false) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Read-only access in demo version'], 403);
    }
}
