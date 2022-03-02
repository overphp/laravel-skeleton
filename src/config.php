<?php

return [

    /**
     * --------------------------------------------------------------------------
     * skeleton 命令命名空间
     * --------------------------------------------------------------------------
     * orm:         模型类命名空间
     * filters:     模型过滤器类命名空间
     * logic:       业务逻辑层类命名空间
     * controllers: 控制器类命名空间
     * requests:    请求验证类命名空间
     */
    'namespace' => [
        'orm' => 'App\\Models\\Orm',
        'filters' => 'App\\Models\\Filters',
        'controllers' => 'App\\Http\\Controllers',
        'requests' => 'App\\Http\\Requests',
        'logic' => 'App\\Http\\Logic',
    ],

    /**
     * --------------------------------------------------------------------------
     * 异常全局处理器配置
     * --------------------------------------------------------------------------
     * exception_render_url_prefix: url路径符合这个前缀的则调用自定义的render，
     *                              否则使用框架默认的render
     *
     */
    'exception_render_url_prefix' => 'api'
];
