<?php

namespace Whiteki\SimpleApiResponse;

use Illuminate\Support\ServiceProvider;
use Whiteki\SimpleApiResponse\Commands\ServiceMakeCommand;
use Whiteki\SimpleApiResponse\Commands\RepositoryMakeCommand;
use Whiteki\SimpleApiResponse\Commands\ApiControllerMakeCommand;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ApiControllerMakeCommand::class,
                RepositoryMakeCommand::class,
                ServiceMakeCommand::class
            ]);
        }
    }
}
