<?php

namespace Harrisonratcliffe\LaravelApiHandler\Services;

use Illuminate\Http\JsonResponse;

class ApiResponseService
{
    /**
     * Send a success response.
     */
    public function successResponse(?string $message = null, mixed $data = null, ?int $statusCode = null): JsonResponse
    {
        if ($message === null) {
            $message = config('laravel-api-handler.success_response');
        }

        if ($statusCode === null) {
            $statusCode = config('laravel-api-handler.success_status_code');
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Send an error response.
     *
     * @param  array<mixed>|null  $debug
     */
    public function errorResponse(string $message, int $statusCode = 400, ?string $documentation = null, ?array $debug = null): JsonResponse
    {
        $errorResponse = [
            'status' => 'error',
            'error' => [
                'code' => $statusCode,
                'message' => $message,
            ],
        ];

        if ($documentation !== null) {
            $errorResponse['error']['documentation'] = $documentation;
        }

        if ($debug !== null) {
            $errorResponse['error']['debug'] = $debug;
        }

        return response()->json($errorResponse, $statusCode);
    }
}
