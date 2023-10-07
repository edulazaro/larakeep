<?php

namespace EduLazaro\Larakeep;

use Illuminate\Support\ServiceProvider;
use EduLazaro\Larakeep\Console\Commands\MakeKeeperCommand;

class LarakeepServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([MakeKeeperCommand::class]);
        }
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}