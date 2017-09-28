<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register any helper services.
     *
     * @return void
     */
    public function register()
    {
        foreach (glob(app()->path().'/Helpers/*.php') as $filename){
            require_once($filename);
        }
    }
}
