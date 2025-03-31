<?php

namespace Harrisonratcliffe\LaravelApiResponses\Services;

use Illuminate\Http\JsonResponse;

class ApiResponseService
{
    /**
     * Send a success response.
     */
    public function success(?string $message = null, mixed $data = null, ?int $statusCode = null): JsonResponse
    {
        if ($message === null) {
            $message = config('api-responses.success_response');
        }

        if ($statusCode === null) {
            $statusCode = config('api-responses.success_status_code');
        }

        $successResponse = [
            'status' => 'success',
            'message' => $message,
        ];

        if ($data !== null) {
            $successResponse['data'] = $data;
        }


        return response()->json($successResponse, $statusCode);
    }

    /**
     * Send an error response.
     *
     * @param  array<mixed>|null  $debug
     */
    public function error(string $message, int $statusCode = 400, mixed $details = null, ?string $documentation = null, ?array $debug = null): JsonResponse
    {
        $errorResponse = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($details !== null) {
            $errorResponse['details'] = $details;
        }

        if ($documentation !== null) {
            $errorResponse['documentation'] = $documentation;
        }

        if ($debug !== null) {
            $errorResponse['debug'] = $debug;
        }

        return response()->json($errorResponse, $statusCode);
    }
}
