<?php

return [
    /**
     * Debug Mode
     *
     * This should only be used in development as it may
     * leak sensitive information.
     */
    'debug_mode' => env('APP_DEBUG', false),

    /**
     * 500/Internal Error Response Message
     *
     * In the event of a 500/internal server error which is usually returned due
     * to a code issue, you can choose whether you want the actual error to be
     * returned or not. If disabled, the unknown_error response will be used.
     */
    'show_500_error_message' => true,

    /**
     * Always Use Default Responses
     *
     * If enabled, the default responses will always be used in API responses. For
     * example, if you did abort(404, "Not Found") in your code, the default http_not_found
     * message would be used instead.
     *
     * 500 errors are excluded from this config based on the show_500_error_message config.
     */
    'always_use_default_responses' => false,

    /**
     * Default Response Options
     *
     * The default responses used in API responses.
     */
    'success_response' => 'API request processed successfully.',
    'success_status_code' => 200,

    'http_not_found' => 'The requested resource or endpoint could not be located.',
    'method_not_allowed' => 'The used method is not allowed for this resource or endpoint.',
    'unauthenticated' => 'You must be logged in to access this resource. Please provide valid credentials.',
    'not_authorized' => 'You are not authorized to access this resource.',
    'validation' => 'There has been one or more validation error with your request.',
    'model_not_found' => 'The requested resource could not be found. for doesn\'t exist.',
    'rate_limit' => 'You have exceeded the API request limit. Please try again later.',
    'unknown_error' => 'An unexpected error has occurred. Please try again later or contact support if the issue persists.',
];
