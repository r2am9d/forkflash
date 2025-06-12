<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for API endpoints to prevent abuse
    |
    */
    'api' => [
        'attempts' => env('API_RATE_LIMIT_ATTEMPTS', 60),
        'decay_seconds' => env('API_RATE_LIMIT_DECAY', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for authentication endpoints
    |
    */
    'auth' => [
        'login' => [
            'attempts' => env('AUTH_LOGIN_ATTEMPTS', 5),
            'decay_seconds' => env('AUTH_LOGIN_DECAY', 300), // 5 minutes
        ],
        'registration' => [
            'attempts' => env('AUTH_REGISTER_ATTEMPTS', 3),
            'decay_seconds' => env('AUTH_REGISTER_DECAY', 3600), // 1 hour
        ],
        'password_reset' => [
            'attempts' => env('AUTH_PASSWORD_RESET_ATTEMPTS', 3),
            'decay_seconds' => env('AUTH_PASSWORD_RESET_DECAY', 3600), // 1 hour
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | CORS Configuration
    |--------------------------------------------------------------------------
    |
    | Configure CORS settings for mobile app API access
    |
    */
    'cors' => [
        'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000')),
        'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
        'allowed_headers' => ['*'],
        'exposed_headers' => ['X-Total-Count', 'X-Page-Count'],
        'max_age' => 86400, // 24 hours
        'supports_credentials' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Configure security headers for enhanced protection
    |
    */
    'security_headers' => [
        'hsts' => [
            'enabled' => env('SECURITY_HSTS_ENABLED', true),
            'max_age' => env('SECURITY_HSTS_MAX_AGE', 31536000), // 1 year
            'include_subdomains' => env('SECURITY_HSTS_INCLUDE_SUBDOMAINS', true),
            'preload' => env('SECURITY_HSTS_PRELOAD', true),
        ],
        'csp' => [
            'enabled' => env('SECURITY_CSP_ENABLED', true),
            'report_only' => env('SECURITY_CSP_REPORT_ONLY', false),
        ],
        'referrer_policy' => env('SECURITY_REFERRER_POLICY', 'strict-origin-when-cross-origin'),
        'x_frame_options' => env('SECURITY_X_FRAME_OPTIONS', 'DENY'),
        'x_content_type_options' => env('SECURITY_X_CONTENT_TYPE_OPTIONS', 'nosniff'),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Versioning
    |--------------------------------------------------------------------------
    |
    | Configure API versioning strategy
    |
    */
    'api_versioning' => [
        'default_version' => env('API_DEFAULT_VERSION', 'v1'),
        'supported_versions' => ['v1'],
        'deprecation_notice' => env('API_DEPRECATION_NOTICE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Request Validation
    |--------------------------------------------------------------------------
    |
    | Configure request validation and sanitization
    |
    */
    'validation' => [
        'max_request_size' => env('MAX_REQUEST_SIZE', '10M'),
        'max_file_uploads' => env('MAX_FILE_UPLOADS', 10),
        'allowed_file_types' => [
            'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'documents' => ['pdf', 'doc', 'docx'],
        ],
        'sanitize_input' => env('SANITIZE_INPUT', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    |
    | Configure session security settings
    |
    */
    'session_security' => [
        'regenerate_on_login' => true,
        'invalidate_on_logout' => true,
        'timeout_minutes' => env('SESSION_TIMEOUT', 120), // 2 hours
        'secure_cookies' => env('SESSION_SECURE_COOKIES', true),
        'same_site_cookies' => env('SESSION_SAME_SITE', 'strict'),
    ],
];
