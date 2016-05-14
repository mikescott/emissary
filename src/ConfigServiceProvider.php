<?php
namespace mikescott\Emissary;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider{
   public function register()
   {
       $app = $this->app;
       $this->app->singleton('config', function() use ($app) {
          return new Config($app);
       });
   }
}