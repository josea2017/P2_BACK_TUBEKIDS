<?php

namespace App\Providers;
use Authy\AuthyApi;
use App\Services\Authy\AuthyService;

use Illuminate\Support\ServiceProvider;

class AuthyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('authy', function() {
            $client = new AuthyApi(env('AUTHY_SECRET'));
            return new AuthyService($client);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
