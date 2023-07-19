<?php

return [
    'common_key' => env('GOEASY_COMMON_KEY', ''),

    'subscribe_key' => env('GOEASY_SUBSCRIBE_KEY', ''),

    'client_key' => env('GOEASY_CLIENT_KEY', ''),

    'rest_key' => env('GOEASY_REST_KEY', ''),

    'secret_key' => env('GOEASY_SECRET_KEY', ''),

    'host' => env('GOEASY_HOST', 'https://rest-hz.goeasy.io'),

    'access_token_ttl' => env('GOEASY_ACCESS_TOKEN_TTL', 10800),
];