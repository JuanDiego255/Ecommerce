<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Barbero::class => \App\Policies\BarberoPolicy::class,
        \App\Models\Cita::class => \App\Policies\CitaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('is-owner', fn($user) => $user->role === 'owner');
        Gate::define('is-manager', fn($user) => $user->role === 'manager');
        Gate::define('is-barber', fn($user) => $user->role === 'barber');

        // Citas
        Gate::define('citas.view-any', fn($user) => in_array($user->role, ['owner', 'manager']));
        Gate::define('citas.view-own', fn($user) => in_array($user->role, ['owner', 'manager', 'barber']));
        Gate::define('citas.manage-any', fn($user) => in_array($user->role, ['owner', 'manager']));
        Gate::define('citas.manage-own', fn($user) => in_array($user->role, ['owner', 'barber', 'manager']));

        // Catálogo/Barberos/Settings
        Gate::define('catalog.manage', fn($user) => $user->role === 'owner');
        Gate::define('barberos.manage', fn($user) => $user->role === 'owner');
        Gate::define('tenant.settings', fn($user) => $user->role === 'owner');
        //
        // app/Providers/AuthServiceProvider.php
        Gate::define('owner-only', fn($user) => $user->role === 'owner'); // ajusta a tu campo/rol

    }
}
