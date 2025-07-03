<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\LandingPage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Brand::class => \App\Policies\BrandPolicy::class,
        LandingPage::class => \App\Policies\LandingPagePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-brand', function ($user, $brand) {
            return $user->id === $brand->user_id || $user->is_admin;
        });

        Gate::define('view-landing-page', function ($user, $landingPage) {
            return $user->id === $landingPage->brand->user_id || $user->is_admin;
        });
    }
}