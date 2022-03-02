<?php

namespace Overphp\LaravelSkeleton\Console;

use Illuminate\Console\GeneratorCommand as BaseGeneratorCommand;
use Illuminate\Support\Str;

abstract class GeneratorCommand extends BaseGeneratorCommand
{
    /**
     * Qualify the given model class base name.
     *
     * @param string $model
     * @return string
     */
    protected function qualifyModel(string $model)
    {
        $model = ltrim($model, '\\/');

        $model = str_replace('/', '\\', $model);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return $rootNamespace . 'Models\\Orm\\' . $model;
    }
}
