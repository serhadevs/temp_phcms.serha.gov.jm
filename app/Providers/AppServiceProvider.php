<?php

namespace App\Providers;

use App\Models\StmpSettings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $carbonConfig = config('app.carbon_config');
        if (is_callable($carbonConfig)) {
            $carbonConfig();
        }
        if (Schema::hasTable('stmp_settings')) {
            $stmpsettings = StmpSettings::first();
            //dd($stmpsettings);

            if ($stmpsettings) {
                $data = [
                    'driver' => $stmpsettings->mailer,
                    'host' => $stmpsettings->host,
                    'port' => $stmpsettings->port,
                    'username' => $stmpsettings->username,
                    'password' => $stmpsettings->password,
                    'encryption' => $stmpsettings->encryption,
                    'from' => [
                        'address' => $stmpsettings->from_address,
                        'name' => 'PHCMS'
                    ]
                ];

                Config::set('mail', $data);
            }
        }
    }
}
