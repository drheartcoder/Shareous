<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
      
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
       $file_list = glob(app_path().'/Helpers/*.php');
       foreach ($file_list as $key => $file) 
       {
           require_once($file);
       }
    }
}
