<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    static $modules = [];

    static function modules() {
        if (empty(self::$modules)) {
            // $modules_path = realpath(__DIR__ .'/../Mod');
            $modules_path = base_path('module');
            
            $modules_files = glob(\App\Utils::path("{$modules_path}/*"));
            foreach($modules_files as $path) {
                $path = realpath($path);
                $module = (object) pathinfo($path);
                $module->path = $path;
                $module->namespace = $module->filename;
                $module->service_provider = \App\Utils::realpath("{$module->path}\Providers\{$module->namespace}ServiceProvider.php");
                $module->routes_api = \App\Utils::realpath("{$module->path}\\routes\api.php");
                $module->routes_console = \App\Utils::realpath("{$module->path}\\routes\console.php");
                $module->install = \App\Utils::realpath("{$module->path}\\install.php");
                
                // Register namespace
                $loader = require \App\Utils::realpath(base_path('/vendor/autoload.php'));
                $loader->addPsr4("{$module->namespace}\\", $module->path);

                self::$modules[] = $module;
            }
        }
        
        return self::$modules;
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach(self::modules() as $module) {
            if ($module->service_provider) {
                $this->app->register($module->service_provider);
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach(self::modules() as $module) {
            if ($module->routes_api) {
                \Route::group(['prefix'=>'api'], function() use($module) {
                    include $module->routes_api;
                });
            }
        }
    }
}

