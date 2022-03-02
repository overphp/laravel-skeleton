<?php

namespace Overphp\LaravelSkeleton\Console;

use Symfony\Component\Console\Input\InputOption;

class RequestMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'skeleton:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    /**
     * @return string
     */
    protected function getStub()
    {
        $stub = $this->option('curd') ? '/stubs/request.curd.stub' : '/stubs/request.stub';

        return $this->resolveStubPath($stub);
    }

    /**
     * @param $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return __DIR__ . $stub;
    }

    /**
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return config('skeleton.namespace.requests');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['curd', 'c', InputOption::VALUE_NONE, 'Generate a curd Request class.'],
        ];
    }
}
