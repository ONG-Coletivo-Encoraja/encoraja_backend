<?php

namespace App\Providers;

use App\Interfaces\AuthServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\EventServiceInterface;
use App\Models\Event;
use App\Models\User;
use App\Observers\EventObserver;
use App\Observers\UserObserver;
use App\Services\AuthService;
use App\Services\EventService;
use App\Services\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserServiceInterface::class, UserService::class);
        $this->app->singleton(AuthServiceInterface::class, AuthService::class);
        $this->app->singleton(EventServiceInterface::class, EventService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Event::observe(EventObserver::class);
    }
}
