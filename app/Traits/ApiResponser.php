<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * Trait ApiResponser
 * @package App\Http\Controllers
 */
trait ApiResponser
{
    /**
     * Build success responses
     *
     * @param string|array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse($data = [], string $message = "OK", int $code = ResponseAlias::HTTP_OK): JsonResponse
    {
        return new JsonResponse(["success" => true, "message" => $message, "data" => $data], $code);
    }

    /**
     * Build error responses
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse($message, int $code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return new JsonResponse(["success" => false, "message" => $message], $code);
    }

    /**
     * Respond failure with errors
     *
     * @param $message
     * @param array $errors
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponseWithErrors($message, array $errors = [], int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return new JsonResponse(["success" => false, "errors" => $errors, "message" => $message], $code);
    }
}
