<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if( !Session::has('view_type') )
            Session::put('view_type', 'blocks' );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
