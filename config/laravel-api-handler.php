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
    'success_response' => 'API request processed successfully',
    'success_status_code' => 200,

    'http_not_found' => 'The resource or endpoint cannot be found',
    'unauthenticated' => 'You are not authenticated',
    'model_not_found' => 'Unable to locate the %s you requested',
    'unknown_error' => 'An unknown error occurred'
];
