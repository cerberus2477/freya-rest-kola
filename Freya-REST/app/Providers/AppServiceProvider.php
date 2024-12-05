<?php

namespace App\Providers;

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

    //for debugging reasons
    public function boot()
    {
        \DB::listen(function ($query) {
            \Log::info($query->sql);
        });
    }
}
