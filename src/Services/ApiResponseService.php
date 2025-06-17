<?php

namespace Harrisonratcliffe\LaravelApiResponses\Services;

use Illuminate\Http\JsonResponse;

class ApiResponseService
{
    /**
     * Send a success response.
     *
     * @param string|null $message
     * @param mixed $data
     * @param int|null $statusCode
     * @return JsonResponse
     */
    public function success(?string $message = null, $data = null, ?int $statusCode = null): JsonResponse
    {
        if ($message === null) {
            $message = config('api-responses.success_response');
        }

        if ($statusCode === null) {
            $statusCode = config('api-responses.success_status_code');
        }

        // Start the success response array
        $successResponse = [
            'status' => 'success',
            'message' => $message,
        ];

        // Create an array to merge
        $additionalData = [];

        if ($data !== null) {
            $additionalData['data'] = $data;
        }

        // Merge the arrays
        $successResponse = array_merge($successResponse, $additionalData);

        return response()->json($successResponse, $statusCode);
    }

    /**
     * Send an error response.
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed $details
     * @param string|null $documentation
     * @param array|null $debug
     * @return JsonResponse
     */
    public function error(string $message, int $statusCode = 400, $details = null, ?string $documentation = null, ?array $debug = null): JsonResponse
    {
        $errorResponse = [
            'status' => 'error',
            'message' => $message,
        ];

        $additionalData = [];

        if ($details !== null) {
            $additionalData['details'] = $details;
        }

        if ($documentation !== null) {
            $additionalData['documentation'] = $documentation;
        }

        if ($debug !== null) {
            $additionalData['debug'] = $debug;
        }

        $errorResponse = array_merge($errorResponse, $additionalData);

        return response()->json($errorResponse, $statusCode);
    }
}
