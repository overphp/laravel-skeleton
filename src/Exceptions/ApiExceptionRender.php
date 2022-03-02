<?php

namespace Overphp\LaravelSkeleton\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Overphp\LaravelSkeleton\Http\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

trait ApiExceptionRender
{
    /**
     * @param Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws \ReflectionException
     */
    public function render($request, Throwable $e)
    {
        if (
            empty(config('skeleton.exception_render_url_prefix')) ||
            str_starts_with($request->path(), config('skeleton.exception_render_url_prefix'))
        ) {
            return $this->apiRender($request, $e);
        }

        return parent::render($request, $e);
    }

    protected function apiRender($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            // 参数未通过验证
            return ApiResponse::error(Response::HTTP_BAD_REQUEST, Arr::first(Arr::collapse($e->errors())));
        } elseif ($e instanceof NotFoundHttpException) {
            // 未定义的路由: 由于无法匹配到路由，因而不会执行中间件，故在此处直接记录日志
            Log::info('request', [
                'path' => $request->path(),
                'data' => $request->all(),
            ]);

            $response = ApiResponse::error(Response::HTTP_NOT_FOUND, 'API does not exist.');

            Log::info('response', $response->getData(true));

            return $response;
        } elseif ($e instanceof ModelNotFoundException) {
            // 模型未找到数据
            return ApiResponse::error(Response::HTTP_BAD_REQUEST, '无数据');
        }

        return ApiResponse::error($e->getCode(), $e->getMessage());
    }
}
