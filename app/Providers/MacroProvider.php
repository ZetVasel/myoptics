<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MacroProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require base_path() . '/resources/macro/page_macros.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
