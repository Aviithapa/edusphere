<?php

namespace App\Providers;

use App\Helpers\Notifier;
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
        $this->app->bind('notifier', fn (): \App\Helpers\Notifier => new Notifier);

    }
}
