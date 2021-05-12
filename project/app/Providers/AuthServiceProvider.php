<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(now()->addMinutes(config('auth.token_lifetime')));
        Passport::refreshTokensExpireIn(now()->addMinutes(config('auth.token_lifetime') * 2));

        // Define available scopes
        Passport::tokensCan([
            'manage_orders' => __('token.scope_types.manage_orders'),
        ]);
    }
}
