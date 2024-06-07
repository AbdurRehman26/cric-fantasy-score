<?php

namespace App\Providers;

use App\Helpers\Toast;
use Illuminate\Database\Eloquent\Model;
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
        if ($this->app->environment('local')) {
            Model::shouldBeStrict();
        }

        $this->app->bind('toast', function () {
            return new Toast;
        });
    }
}
