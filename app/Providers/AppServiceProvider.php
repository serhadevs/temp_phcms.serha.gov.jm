<?php

namespace App\Providers;

use App\Models\StmpSettings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;

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

        RateLimiter::for('authentication_attempts', function (Request $request) {
        return Limit::perMinute(5) // Clamp execution lines to 5 requests per minute
            ->by($request->input('email') . $request->ip()) // Scope attempts by email AND IP 
            ->response(function (Request $request, array $headers) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Too many login attempts. System locked for 60 seconds.'
                ], 429, $headers);
            });
    });
    }
}
