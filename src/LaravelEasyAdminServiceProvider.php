<?php
namespace DevsRyan\LaravelEasyAdmin;

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
            __DIR__.'/Assets' => public_path('devsryan/LaravelEasyAdmin'),
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
                Commands\ListImageSizesCommand::class,
                Commands\MakeSeedCommand::class,
                Commands\CustomLink::class
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
        $this->app->make('DevsRyan\LaravelEasyAdmin\Controllers\AdminController');
        $this->app->make('DevsRyan\LaravelEasyAdmin\Controllers\AuthController');
        $this->app->make('DevsRyan\LaravelEasyAdmin\Controllers\ImageApiController');

        $this->loadViewsFrom(__DIR__.'/Views', 'easy-admin');
    }
}
