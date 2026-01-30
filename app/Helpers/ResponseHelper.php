<?php

namespace App\Helpers;

class ResponseHelper
{
    /**
     * Success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = null, string $message = 'Success', int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Error response.
     *
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error(string $message = 'Error', $data = null, int $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Validation error response.
     *
     * @param mixed $errors
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function validationError($errors, string $message = 'Validation failed')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $errors
        ], 422);
    }

    /**
     * Not found response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function notFound(string $message = 'Resource not found')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null
        ], 404);
    }

    /**
     * Unauthorized response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null
        ], 401);
    }

    /**
     * Forbidden response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function forbidden(string $message = 'Forbidden')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null
        ], 403);
    }
}
