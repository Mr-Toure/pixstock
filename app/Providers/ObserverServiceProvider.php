<?php

namespace App\Providers;

use App\Models\Photo;
use App\Observers\PhotoObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Photo::observe(PhotoObserver::class);
    }
}
