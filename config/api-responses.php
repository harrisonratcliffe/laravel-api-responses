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
     * Default Response Options
     *
     * The default responses used in API responses.
     */
    'success_response' => 'API request processed successfully.',
    'success_status_code' => 200,

    'http_not_found' => 'The requested resource or endpoint could not be located.',
    'unauthenticated' => 'You must be logged in to access this resource. Please provide valid credentials.',
    'model_not_found' => 'The requested resource could not be found. for doesn\'t exist.',
    'unknown_error' => 'An unexpected error has occurred. Please try again later or contact support if the issue persists.',
];
