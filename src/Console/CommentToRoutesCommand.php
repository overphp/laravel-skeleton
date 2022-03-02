<?php

namespace Overphp\LaravelSkeleton\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use ReflectionClass;

class CommentToRoutesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skeleton:route {controller}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Route generation through controller';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = $this->argument('controller');

        $class = $this->getControllerClass($controller);
        if (empty($class)) {
            $this->error(sprintf('class %s not exists', $this->argument('controller')));
            return Command::INVALID;
        }

        $methods = $this->parseRoutes($class);
        if (empty($methods)) {
            $this->error(sprintf('Public method not found in the class: %s', $this->argument('controller')));
            return Command::FAILURE;
        }

        $this->info("Route code generated successfully.\n\n");
        $codes = $this->buildRouteCodes($class, $methods);

        $this->info($codes);
    }

    /**
     * 获取控制器类
     *
     * @param string $controller
     * @return string
     */
    protected function getControllerClass(string $controller): string
    {
        $controller = $this->getController($controller);

        // 优先查找默认控制器命名空间，如果不存在则直接查找该类
        $class = config('skeleton.namespace.controllers') . '\\' . $controller;
        if (class_exists($class)) {
            return $class;
        } elseif (class_exists($controller)) {
            return $controller;
        }

        return '';
    }

    /**
     * @param string $controller
     * @return string
     */
    protected function getController(string $controller): string
    {
        return str_replace('/', '\\', trim($controller));
    }

    /**
     * 解析路由
     * @param string $class
     * @return array
     * @throws \ReflectionException
     */
    protected function parseRoutes(string $class)
    {
        $result = [];
        $ref = new ReflectionClass($class);
        foreach ($ref->getMethods() as $method) {
            if ($method->isPublic()) {
                $blank_methods = ['__construct', 'middleware', 'getMiddleware', 'callAction', '__call'];
                if (!in_array($method->getName(), $blank_methods)) {
                    $result[] = array_merge(
                        ['name' => $method->getName()],
                        $this->parseRouteByAnnotate($method->getDocComment())
                    );
                }
            }
        }

        return $result;
    }

    /**
     * 解析路由注释
     * @param string $annotate
     * @return array
     */
    protected function parseRouteByAnnotate(string $annotate = ''): array
    {
        $method = 'any';
        $path = '';
        $route_name = '';
        if (!empty($annotate)) {
            $pattern = '/@route (.*+)/i';
            if (preg_match($pattern, $annotate, $matches)) {
                if (isset($matches[1])) {
                    $temp = explode(' ', $this->filterSpace($matches[1]));
                    $method = $temp[0] ?? 'any';
                    $path = $temp[1] ?? '';
                    $route_name = $temp[2] ?? '';
                }
            }
        }

        return [
            'method' => $method,
            'path' => $path,
            'route_name' => $route_name,
        ];
    }

    /**
     * 多个空格过滤为单个
     *
     * @param $str
     * @return string
     */
    protected function filterSpace($str)
    {
        return preg_replace('/\s+/', ' ', $str);
    }

    protected function buildRouteCodes(string $namespace, array $methods = []): string
    {
        // 路由前缀
        $prefix = Str::replaceFirst(config('skeleton.namespace.controllers'), '', $namespace);
        $prefix = Str::replaceLast('Controller', '', $prefix);
        $prefix = trim($prefix, '\\');
        $prefix = Str::snake($prefix);
        $prefix = str_replace('\\_', '/', $prefix);
        $route_name_prefix = str_replace('/', '.', $prefix);

        $routes = '';
        $class = class_basename($namespace);
        foreach ($methods as $item) {
            $path = !empty($item['path']) ? $item['path'] : Str::snake($item['name']);
            if ($item['name'] == 'index' && empty($item['path'])) {
                $path = '/';
            }

            $route_name = !empty($item['route_name']) ? $item['route_name'] : Str::snake($item['name']);

            $routes .= sprintf(
                "\t\tRoute::%s('%s', '%s')->name('%s');\n",
                strtolower($item['method']),
                $path,
                $item['name'],
                $route_name
            );
        }

        return str_replace(
            ['{{namespace}}', '{{class}}', '{{prefix}}', '{{name}}', '{{routes}}'],
            [$namespace, $class, $prefix, $route_name_prefix, $routes],
            $this->getRouteGroupTemplate()
        );
    }

    protected function getRouteGroupTemplate(): string
    {
        return "use {{namespace}};\n\n" .
            "Route::controller({{class}}::class)\n" .
            "\t->prefix('{{prefix}}')\n" .
            "\t->name('{{name}}.')\n" .
            "\t->group(function () {\n" .
            "{{routes}}" .
            "\t});\n";
    }
}
