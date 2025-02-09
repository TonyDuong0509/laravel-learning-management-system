<?php

namespace Modules;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\File;
use Modules\User\src\Repositories\UserRepository;

class ModuleServiceProvider extends ServiceProvider
{
    private $middlewares = [];

    private $commands = [];

    public function boot()
    {
        $modules = $this->getModules();
        if (!empty($modules)) {
            foreach ($modules as $module) {
                $this->registerModule($module);
            }
        }
    }

    public function register()
    {
        // Declare Configs
        $modules = $this->getModules();
        if (!empty($modules)) {
            foreach ($modules as $module) {
                $this->registerConfig($module);
            }
        }

        // Declare Middlewares
        $this->registerMiddleware();

        if (!empty($middlewares)) {
            foreach ($middlewares as $key => $middleware) {
                $this->app['router']->pushMiddlewareToGroup($key, $middleware);
            }
        }

        // Declare Commands
        $this->commands($this->commands);

        $this->app->singleton(
            UserRepository::class
        );
    }

    private function getModules()
    {
        return array_map('basename', File::directories(__DIR__));
    }

    private function registerModule($module)
    {
        $modulePath = __DIR__ . "/{$module}";

        // Declare Routes
        if (File::exists($modulePath . '/routes/routes.php')) {
            $this->loadRoutesFrom($modulePath . '/routes/routes.php');
        }

        // Declare Migrations
        if (File::exists($modulePath . '/migrations')) {
            $this->loadMigrationsFrom($modulePath . '/migrations');
        }

        // Declare Languages
        if (File::exists($modulePath . '/resources/lang')) {
            $this->loadTranslationsFrom($modulePath . '/resources/lang', strtolower($module));
            $this->loadJsonTranslationsFrom($modulePath . '/resources/lang');
        }

        // Declare Views
        if (File::exists($modulePath . '/resources/views')) {
            $this->loadViewsFrom($modulePath . '/resources/views', strtolower($module));
        }

        // Declare Helpers
        if (File::exists($modulePath . '/helpers')) {
            $helperList = File::allFiles($modulePath . '/helpers');
            if (!empty($helperList)) {
                foreach ($helperList as $helper) {
                    $file = $helper->getPathName();
                    require $file;
                }
            }
        }
    }

    private function registerConfig($module)
    {
        $configPath = __DIR__ . '/' . $module . '/configs';
        if (File::exists($configPath)) {
            $configFiles = array_map('basename', File::allFiles($configPath));
            foreach ($configFiles as $config) {
                $alias = basename($config, '.php');
                $this->mergeConfigFrom($configPath . '/' . $config, $alias);
            }
        }
    }

    private function registerMiddleware()
    {
        if (!empty($this->middlewares)) {
            foreach ($this->middlewares as $key => $middleware) {
                $this->app['router']->pushMiddlewareToGroup($key, $middleware);
            }
        }
    }
}
