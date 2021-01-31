<?php
namespace Raysirsharp\LaravelEasyAdmin;

use Illuminate\Support\ServiceProvider;

class LaravelEasyAdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->publishes([
            __DIR__.'/Assets' => public_path('raysirsharp/LaravelEasyAdmin'),
            __DIR__.'/FileTemplates/AppModelList.template' => app_path('EasyAdmin/AppModelList.php'),
        ], 'public');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\AddModelCommand::class,
                Commands\RemoveModelCommand::class,
                Commands\RefreshModelCommand::class,
                Commands\AddAllCommand::class,
                Commands\ResetModelsCommand::class,
                Commands\UserCommand::class,
                Commands\CreateUserCommand::class,
                Commands\RemoveUserCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Raysirsharp\LaravelEasyAdmin\Controllers\AdminController');
        $this->app->make('Raysirsharp\LaravelEasyAdmin\Controllers\AuthController');

        $this->loadViewsFrom(__DIR__.'/Views', 'easy-admin');
    }
}
