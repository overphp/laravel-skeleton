<?php

namespace Overphp\LaravelSkeleton\Console;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'skeleton:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * @return bool|void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        if ($this->option('route')) {
            $this->displayRoutes();
            return true;
        }

        if (parent::handle() === false) {
            return false;
        }

        if ($this->option('curd')) {
            $this->createRequest();
            $this->createLogic();
        } else {
            if ($this->option('requests')) {
                $this->createRequest();
            }

            if ($this->option('logic')) {
                $this->createLogic();
            }
        }

        if ($this->option('curd')) {
            $this->displayRoutes();
        }
    }

    /**
     * @return void
     */
    protected function createLogic()
    {
        $logic = Str::studly($this->argument('name'));
        $logic = Str::replaceLast('Controller', '', $logic);

        $this->call('skeleton:logic', [
            'name' => "{$logic}Logic",
            '-c' => $this->option('curd'),
        ]);
    }

    /**
     * @return void
     */
    protected function createRequest()
    {
        $request = Str::studly($this->argument('name'));
        $request = Str::replaceLast('Controller', '', $request);

        $this->call('skeleton:request', [
            'name' => "{$request}Request",
            '-c' => $this->option('curd'),
        ]);
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('curd')) {
            return $this->resolveStubPath('/stubs/controller.curd.stub');
        } elseif ($this->option('logic')) {
            return $this->resolveStubPath('/stubs/controller.logic.stub');
        }
        return $this->resolveStubPath('/stubs/controller.stub');
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
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return config('skeleton.namespace.controllers');
    }

    /**
     * @param $name
     * @return array|string|string[]
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $replace = [];
        if ($this->option('curd')) {
            $replace = $this->buildRequestReplacements($replace, $name);
        }
        if ($this->option('curd') || $this->option('logic')) {
            $replace = $this->buildLogicReplacements($replace, $name);
        }

        return str_replace(array_keys($replace), array_values($replace), parent::buildClass($name));
    }

    /**
     * @param array $replace
     * @param string $namespacedController
     * @return array
     */
    protected function buildLogicReplacements(array $replace, string $namespacedController): array
    {
        $namespacedLogic = Str::replaceFirst(
            config('skeleton.namespace.controllers'),
            config('skeleton.namespace.logic'),
            $namespacedController
        );
        $namespacedLogic = Str::replaceLast('Controller', 'Logic', $namespacedLogic);

        return array_merge($replace, [
            '{{ namespacedLogic }}' => $namespacedLogic,
            '{{ classLogic }}' => class_basename($namespacedLogic),
        ]);
    }

    /**
     * @param array $replace
     * @param string $namespacedController
     * @return array
     */
    protected function buildRequestReplacements(array $replace, string $namespacedController): array
    {
        $namespacedRequest = Str::replaceFirst(
            config('skeleton.namespace.controllers'),
            config('skeleton.namespace.requests'),
            $namespacedController
        );
        $namespacedRequest = Str::replaceLast('Controller', 'Request', $namespacedRequest);

        return array_merge($replace, [
            '{{ namespacedRequest }}' => $namespacedRequest,
            '{{ classRequest }}' => class_basename($namespacedRequest),
        ]);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['logic', 'l', InputOption::VALUE_NONE, 'Generate Logic class.'],
            ['requests', 'R', InputOption::VALUE_NONE, 'Generate FormRequest class.'],
            ['curd', 'c', InputOption::VALUE_NONE, 'Generate curd Controller class.'],
            ['route', '', InputOption::VALUE_NONE, 'Generate routes for Controller class.'],
        ];
    }

    /**
     * @return void
     */
    protected function displayRoutes()
    {
        $this->call('skeleton:route', [
            'controller' => $this->getNameInput(),
        ]);
    }
}
