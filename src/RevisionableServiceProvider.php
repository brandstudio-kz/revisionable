<?php
namespace BrandStudio\Revisionable;

use Illuminate\Support\ServiceProvider;

class RevisionableServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/revisionable.php', 'revisionable');

        if ($this->app->runningInConsole()) {
            $this->publish();
        }
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/database/migrations');
            $this->publish();
        }
    }

    public function publish()
    {
        $this->publishes([
            __DIR__.'/config/revisionable.php' => config_path('revisionable.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

}
