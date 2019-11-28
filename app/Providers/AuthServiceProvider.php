<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('subscribe-to-device', function ($user, $device) {
            return $user->id === (int) $device->user_id;
        });

        Gate::define('create-department', function () {
            return auth()->user()->email === 'infra@javra.com';
        });

        Gate::define('view-subscriptions', function () {
            return auth()->user()->email === 'infra@javra.com';
        });

    }
}
