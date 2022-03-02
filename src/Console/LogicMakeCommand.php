<?php

namespace Overphp\LaravelSkeleton\Console;

use Symfony\Component\Console\Input\InputOption;

class LogicMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'skeleton:logic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new logic class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Logic';

    /**
     * @return string
     */
    protected function getStub()
    {
        return $this->option('curd') ? $this->resolveStubPath('/stubs/logic.curd.stub') :
            $this->resolveStubPath('/stubs/logic.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
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
        return config('skeleton.namespace.logic');
    }

    /**
     * @return array[]
     */
    protected function getOptions()
    {
        return [
            ['curd', 'c', InputOption::VALUE_NONE, 'Generate curd Logic class.'],
        ];
    }
}
