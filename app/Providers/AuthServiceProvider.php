<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Site;
use App\Policies\SitePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Site::class => SitePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('ca-access', function ($user) {
            return in_array($user->role, ['admin', 'ca']);
        });
    }
}
