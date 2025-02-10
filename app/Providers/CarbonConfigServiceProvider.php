<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class CarbonConfigServiceProvider extends ServiceProvider
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
        //#[\ReturnTypeWillChange]
        Carbon::macro('createFromTimestamp', function ($timestamp, $tz = null) {
            return parent::createFromTimestamp($timestamp, $tz);
        });
    }
}
