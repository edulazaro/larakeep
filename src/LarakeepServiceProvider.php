<?php

namespace EduLazaro\Larakeep;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

use EduLazaro\Larakeep\Console\Commands\MakeKeeperCommand;
use EduLazaro\Larakeep\Concerns\HasKeepers;

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


        $modelPath = app_path('Models');

        if (!File::exists($modelPath)) {
            return;
        }

        $modelFiles = File::allFiles($modelPath);

        foreach ($modelFiles as $file) {

            $relativePath = str_replace([$modelPath, '.php', '/'], ['', '', '\\'], $file->getRealPath());

            $class = "App\\Models" . $relativePath;

            if (class_exists($class) && is_subclass_of($class, Model::class) && in_array(HasKeepers::class, class_uses($class))) {
                $class::bootKeepers();
            }
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