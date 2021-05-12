<?php

namespace App\Http\Middleware;

use Closure;

class LicenseCheck
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
        if(config('settings.LICENSE_ACTIVATED') !== "installed" || config('settings.LICENSE_ACTIVATED_URL') !== $request->getHttpHost()) {
            return response()->redirectToRoute('license.check');
        }

        return $next($request);
    }
}
