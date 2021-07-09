<?php

namespace App\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        if(App::isProduction()) {
            URL::forceScheme('https');
        }
    }
}
