<?php

namespace Overphp\LaravelSkeleton;

use Illuminate\Support\ServiceProvider;
use Overphp\LaravelSkeleton\Console\CommentToRoutesCommand;
use Overphp\LaravelSkeleton\Console\ControllerMakeCommand;
use Overphp\LaravelSkeleton\Console\FilterMakeCommand;
use Overphp\LaravelSkeleton\Console\LogicMakeCommand;
use Overphp\LaravelSkeleton\Console\ModelMakeCommand;
use Overphp\LaravelSkeleton\Console\RequestMakeCommand;
use Overphp\LaravelSkeleton\Console\TraceMiddlewareMakeCommand;

class SkeletonServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->bootConsole();

            $this->bootPublish();
        }
    }

    public function register()
    {
        $this->setupConfig();
    }

    /**
     * setup config
     *
     * @return void
     */
    protected function setupConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'skeleton');
    }

    /**
     * @return void
     */
    protected function bootPublish()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('skeleton.php'),
        ], 'skeleton-config');

        $this->publishes([
            __DIR__ . '/../lang/zh_CN' => lang_path('zh_CN')
        ], 'skeleton-lang');
    }

    /**
     * @return void
     */
    protected function bootConsole()
    {
        $this->commands([
            ModelMakeCommand::class,
            FilterMakeCommand::class,
            RequestMakeCommand::class,
            ControllerMakeCommand::class,
            LogicMakeCommand::class,
            TraceMiddlewareMakeCommand::class,
            CommentToRoutesCommand::class,
        ]);
    }
}