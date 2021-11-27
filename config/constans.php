<?php

return [
    'frontend_url' => env('FRONTEND_URL', 'http://192.168.0.21:3000'),
    'app_env' => env('APP_ENV', 'local'),
    'commission' => env('COMMISSION', 0.2),
    'commission_if_delivery_in_24h' => env('COMMISSION_IF_DELIVERY_IN_24H', 0.15),
];