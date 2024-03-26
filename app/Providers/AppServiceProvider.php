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

    /**
     * Bootstrap any application services.
     */
    public function boot() {
        // \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
        //      echo '<pre>';
        //      print_r([ $query->sql, $query->time]);
        //      echo '</pre>';
        // });
    }
    
}
