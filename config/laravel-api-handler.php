<?php

return [
    /** Debug Mode
     *
     * This should only be used in development as it may
     * leak sensitive information
     */
    'debug_mode' => config('app.debug', false),

    /**
     * Default Response Options
     */
    'success_response' => 'API request processed successfully.',
    'success_status_code' => 200,

    'http_not_found' => 'The requested resource or endpoint could not be located.',
    'unauthenticated' => 'You must be logged in to access this resource. Please provide valid credentials.',
    'model_not_found' => 'The requested resource could not be found. for doesn\'t exist.',
    'unknown_error' => 'An unexpected error has occurred. Please try again later or contact support if the issue persists.',
];
