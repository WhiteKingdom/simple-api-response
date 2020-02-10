<?php

namespace Whiteki\SimpleApiResponse;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\QueryExecuted;
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
        if (!$this->app['config']->get('app.debug')) {
            return;
        }
        DB::listen(function (QueryExecuted $query) {
            $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query->sql);
            $bindings = $query->connection->prepareBindings($query->bindings);
            $pdo = $query->connection->getPdo();
            $realSql = vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings));
            $duration = $this->formatDuration($query->time / 1000);
            Log::channel('sqllog')->debug(sprintf('[%s] %s | %s: %s', $duration, $realSql, request()->method(), request()->getRequestUri()));
        });
    }


    /**
     * Format duration.
     *
     * @param float $seconds
     *
     * @return string
     */
    private function formatDuration($seconds)
    {
        if ($seconds < 0.001) {
            return round($seconds * 1000000).'μs';
        } elseif ($seconds < 1) {
            return round($seconds * 1000, 2).'ms';
        }
        return round($seconds, 2).'s';
    }
}
