<?php

namespace Overphp\LaravelSkeleton\Console;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends GeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'skeleton:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Orm class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    public function handle()
    {
        if (parent::handle() === false && !$this->option('force')) {
            return false;
        }

        if ($this->option('filter')) {
            $this->createFilter();
        }

        if ($this->option('migration')) {
            $this->createMigration();
        }

        if ($this->option('seed')) {
            $this->createSeeder();
        }
    }

    /**
     * @return void
     */
    protected function createFilter()
    {
        $filter = Str::studly($this->argument('name'));

        $this->call('skeleton:filter', [
            'name' => "{$filter}Filter",
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);
    }

    /**
     * Create a seeder file for the model.
     *
     * @return void
     */
    protected function createSeeder()
    {
        $seeder = Str::studly(class_basename($this->argument('name')));

        $this->call('make:seeder', [
            'name' => "{$seeder}Seeder",
        ]);
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        $stub = $this->option('filter') ? '/stubs/model.filter.stub' : '/stubs/model.stub';

        return $this->resolveStubPath($stub);
    }

    /**
     * @param $stub
     * @return string
     */
    protected function resolveStubPath($stub): string
    {
        return __DIR__ . $stub;
    }

    /**
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return config('skeleton.namespace.orm');
    }

    /**
     * @return array[]
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
            ['seed', 's', InputOption::VALUE_NONE, 'Create a new seeder for the model'],
            ['filter', 'F', InputOption::VALUE_NONE, 'Create a new filter for the model'],
        ];
    }
}
