<?php

namespace EduLazaro\Larakeep\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeKeeperCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'make:keeper {name} {model?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Keeper';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Keeper';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = '/stubs/keeper.stub';
        return __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Keepers';
    }

    /**
     * @param string $stub
     * @param string $name
     * 
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $keeperClass = $name;

        $keeperClassSegments = explode('\\', $keeperClass);
        $keeperClass = end($keeperClassSegments);

        if ($this->argument('model')) {

            $modelClass = $this->argument('model');
            $modelClass = trim($this->argument('model'), '\\');

            if (!str_contains($modelClass, '\\')) {
                $modelClass = '\\App\Models\\' . $modelClass;
            }

            $modelClassSegments = explode('\\', $modelClass);
            $usedModelClass = end($modelClassSegments);
        } else {
            $replaceString = preg_quote('Keeper', '/');
            $usedModelClass = preg_replace("/$replaceString$/", '', $keeperClass);
            $modelClass = '\\App\\Models\\' . $usedModelClass;
        }

        $stub = str_replace('{{model_class}}', $modelClass, $stub);
        $stub = str_replace('{{used_model_class}}', $usedModelClass, $stub);
        $stub = str_replace('{{keeper_class}}', $keeperClass, $stub);
        return str_replace('{{model_class_variable_name}}', strtolower($usedModelClass), $stub);
    }
}