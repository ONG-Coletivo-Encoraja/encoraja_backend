<?php

namespace App\Providers;

use App\Interfaces\AuthServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\EventServiceInterface;
use App\Interfaces\GraphicsServiceInterface;
use App\Interfaces\InscriptionServiceInterface;
use App\Interfaces\ReportAdminServiceInterface;
use App\Interfaces\ReportCsvServiceInterface;
use App\Interfaces\RequestVolunteerServiceInterface;
use App\Interfaces\ReviewServiceInterface;
use App\Models\Event;
use App\Models\User;
use App\Observers\EventObserver;
use App\Observers\UserObserver;
use App\Services\AuthService;
use App\Services\EventService;
use App\Services\GraphicsService;
use App\Services\InscriptionService;
use App\Services\ReportAdminService;
use App\Services\ReportCsvService;
use App\Services\RequestVolunteerService;
use App\Services\ReviewService;
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
        $this->app->singleton(ReviewServiceInterface::class, ReviewService::class);
        $this->app->singleton(GraphicsServiceInterface::class, GraphicsService::class);
        $this->app->singleton(ReportCsvServiceInterface::class, ReportCsvService::class);
        $this->app->singleton(InscriptionServiceInterface::class, InscriptionService::class);
        $this->app->singleton(ReportAdminServiceInterface::class, ReportAdminService::class);
        $this->app->singleton(RequestVolunteerServiceInterface::class, RequestVolunteerService::class);
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
