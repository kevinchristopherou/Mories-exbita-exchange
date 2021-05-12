<?php

namespace App\Http\Middleware;

use Closure;

class ApplicationStateCheck
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
        if(config('settings.APPLICATION_STATE') !== "synced") {
            return response()->redirectToRoute('application.check');
        }

        return $next($request);
    }
}
