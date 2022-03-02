<?php

namespace Overphp\LaravelSkeleton\Console;

class FilterMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'skeleton:filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent Filter class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Filter';

    protected function getStub()
    {
        return __DIR__ . '/stubs/filter.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return config('skeleton.namespace.filters');
    }
}
