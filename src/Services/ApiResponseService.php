<?php

namespace Harrisonratcliffe\LaravelApiResponses\Services;

use Illuminate\Http\JsonResponse;

class ApiResponseService
{
    /**
     * Send a success response.
     *
     * @param  array<string, mixed>|null  $data
     */
    public function success(?string $message = null, array|null $data = null, ?int $statusCode = null): JsonResponse
    {
        $message = $message ?? config('api-responses.success_response');
        $statusCode = $statusCode ?? config('api-responses.success_status_code');

        $responsePayload = [
            'status' => 'success',
            'message' => $message,
        ];

        if ($data !== null) {
            $responsePayload['data'] = $data;
        }

        return $this->jsonResponse($responsePayload, $statusCode);
    }

    /**
     * Send an error response.
     *
     * @param  array<string, mixed>|null  $details
     * @param  array<string, mixed>|null  $debug
     */
    public function error(string $message, int $statusCode = 400, array|null $details = null, ?string $documentation = null, array|null $debug = null): JsonResponse
    {
        $responsePayload = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($details !== null) {
            $responsePayload['details'] = $details;
        }

        if ($documentation !== null) {
            $responsePayload['documentation'] = $documentation;
        }

        if ($debug !== null) {
            $responsePayload['debug'] = $debug;
        }

        return $this->jsonResponse($responsePayload, $statusCode);
    }

    /**
     * Create a new JSON response.
     *
     * @param  array<string, mixed>  $data
     */
    protected function jsonResponse(array $data, int $statusCode): JsonResponse
    {
        return response()->json($data, $statusCode);
    }
}
