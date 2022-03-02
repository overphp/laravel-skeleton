<?php

namespace Overphp\LaravelSkeleton\Http;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    /**
     * Successful response
     *
     * @param array $data
     * @param string $message
     * @return JsonResponse
     */
    public static function success(array $data = [], string $message = 'success'): JsonResponse
    {
        return self::jsonResponse(Response::HTTP_OK, $message, $data);
    }

    /**
     * Exception response
     *
     * @param int|string $code
     * @param string $message
     * @return JsonResponse
     */
    public static function error(int|string $code, string $message = ''): JsonResponse
    {
        return self::jsonResponse(intval($code), $message);
    }

    /**
     * @param int $code
     * @param string $message
     * @param array $data
     * @return JsonResponse
     */
    protected static function jsonResponse(int $code, string $message, array $data = []): JsonResponse
    {
        return response()->json(
            [
                'code' => $code,
                'message' => $message,
                'request_id' => '',
                'data' => $data,
            ],
            Response::HTTP_OK,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}