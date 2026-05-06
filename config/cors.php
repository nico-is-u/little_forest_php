<?php

return [
    'allowed_origins' => ['*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    'allowed_headers' => ['*'],
    'supports_credentials' => false,
    'max_age' => 7200,
    'exposed_headers' => [],
    'paths' => ['api/*'],
];
