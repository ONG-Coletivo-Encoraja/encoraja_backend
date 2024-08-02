<?php

namespace App\Providers;

use App\Interfaces\AuthServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserServiceInterface;
use App\Services\AuthService;
use App\Services\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserServiceInterface::class, UserService::class);
        $this->app->singleton(AuthServiceInterface::class, AuthService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
