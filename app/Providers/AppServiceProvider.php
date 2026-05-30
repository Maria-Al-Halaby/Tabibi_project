<?php

namespace App\Providers;

use App\Services\EventLogService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen('eloquent.created: *', function (string $eventName, array $data): void {
            if (isset($data[0])) {
                EventLogService::record($data[0], 'add');
            }
        });

        Event::listen('eloquent.deleted: *', function (string $eventName, array $data): void {
            if (isset($data[0])) {
                EventLogService::record($data[0], 'delete');
            }
        });
    }
}
