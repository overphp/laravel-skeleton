<?php

namespace Overphp\LaravelSkeleton\Console;

class TraceMiddlewareMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'skeleton:trace_middleware';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new log middleware class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'LogMiddleware';

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/middleware.trace.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Http\\Middleware';
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
}
