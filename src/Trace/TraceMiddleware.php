<?php

namespace Overphp\LaravelSkeleton\Trace;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TraceMiddleware
{
    /**
     * The URIs that should be excluded from logging.
     *
     * @var array
     */
    protected array $except = [];

    /**
     * The URIs that should be excluded from request logging.
     *
     * @var array
     */
    protected array $request_except = [];

    /**
     * The URIs that should be excluded from response logging.
     *
     * @var array
     */
    protected array $response_except = [];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $this->logRequest($request);

        return $next($request);
    }

    /**
     * 后置中间件
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function terminate(Request $request, Response $response)
    {
        $this->logResponse($request, $response);
    }

    /**
     * 记录请求日志
     * 这里只记录请求内容，没有记录header信息，如需记录header信息可以重写此方法
     *
     * @param Request $request
     * @return void
     */
    protected function logRequest(Request $request)
    {
        if ($this->inRequestExceptArray($request)) {
            Log::debug('request', [
                'path' => $request->path(),
                'data' => $request->all(),
            ]);
        } else {
            Log::info('request', [
                'path' => $request->path(),
                'data' => $request->all(),
            ]);
        }
    }

    /**
     * 记录响应日志
     * 这里只记录json响应日志，如果需要记录其他响应，可以重写此方法
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function logResponse(Request $request, Response $response)
    {
        if ($response instanceof JsonResponse) {
            if ($this->inResponseExceptArray($request)) {
                Log::debug('response', $response->getData(true));
            } else {
                Log::info('response', $response->getData(true));
            }
        }
    }

    /**
     * 判断是否在请求日志白名单内
     *
     * @param Request $request
     * @return bool
     */
    protected function inRequestExceptArray(Request $request): bool
    {
        return $this->inExceptArray($request, array_merge($this->request_except, $this->except));
    }

    /**
     * 判断是否在响应日志白名单内
     *
     * @param Request $request
     * @return bool
     */
    protected function inResponseExceptArray(Request $request): bool
    {
        return $this->inExceptArray($request, array_merge($this->response_except, $this->except));
    }

    /**
     * 判断请求是否在白名单数组内
     *
     * @param Request $request
     * @param array $excepts
     * @return bool
     */
    protected function inExceptArray(Request $request, array $excepts = []): bool
    {
        foreach ($excepts as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
