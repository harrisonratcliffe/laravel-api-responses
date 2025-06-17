<?php

use Harrisonratcliffe\LaravelApiResponses\Services\ApiResponseService;
use Illuminate\Http\JsonResponse;

describe('ApiResponseService', function () {
    it('returns a success response', function () {
        $service = new ApiResponseService;
        /** @var JsonResponse $response */
        $response = $service->success('Operation successful', ['key' => 'value']);

        expect($response->getStatusCode())->toBe(200);
        expect($response->getData()->status)->toBe('success');
        expect($response->getData()->message)->toBe('Operation successful');
        expect((array) $response->getData()->data)->toEqual(['key' => 'value']);
    });

    it('returns a default success response', function () {
        $service = new ApiResponseService;
        $response = $service->success();
        expect($response->getStatusCode())->toBe(config('api-responses.success_status_code'));
        expect($response->getData()->status)->toBe('success');
        expect($response->getData()->message)->toBe(config('api-responses.success_response'));
        expect(isset($response->getData()->data))->toBeFalse();
    });

    it('returns an error response', function () {
        $service = new ApiResponseService;
        $response = $service->error('An error occurred', 400);
        expect($response->getStatusCode())->toBe(400);
        expect($response->getData()->status)->toBe('error');
        expect($response->getData()->message)->toBe('An error occurred');
    });

    it('returns a default error response', function () {
        $service = new ApiResponseService;
        $response = $service->error('Default error');
        expect($response->getStatusCode())->toBe(400);
        expect($response->getData()->status)->toBe('error');
        expect($response->getData()->message)->toBe('Default error');
    });

    it('returns an error response with details, documentation, and debug', function () {
        $service = new ApiResponseService;
        $debug = ['trace' => 'debug info'];
        $response = $service->error('Error with details', 422, ['field' => 'invalid'], 'https://docs.example.com', $debug);
        expect($response->getStatusCode())->toBe(422);
        expect($response->getData()->status)->toBe('error');
        expect($response->getData()->details)->toEqual(['field' => 'invalid']);
        expect($response->getData()->documentation)->toBe('https://docs.example.com');
        expect($response->getData()->debug)->toEqual($debug);
    });

    it('returns a success response with null data', function () {
        $service = new ApiResponseService;
        $response = $service->success('Success', null);
        expect($response->getStatusCode())->toBe(200);
        expect($response->getData()->status)->toBe('success');
        expect(isset($response->getData()->data))->toBeFalse();
    });

    it('returns a success response with a custom status code', function () {
        $service = new ApiResponseService;
        $response = $service->success('Custom status', ['foo' => 'bar'], 201);
        expect($response->getStatusCode())->toBe(201);
        expect($response->getData()->status)->toBe('success');
        expect($response->getData()->message)->toBe('Custom status');
        expect((array) $response->getData()->data)->toEqual(['foo' => 'bar']);
    });
});
