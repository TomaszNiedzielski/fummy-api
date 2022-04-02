<?php

return [
    'frontend_url' => env('FRONTEND_URL', 'http://192.168.0.21:3000'),
    'api_url' => env('APP_URL', 'http://192.168.0.21:8000'),
    'app_env' => env('APP_ENV', 'local'),
    'commission' => env('COMMISSION', 0.2),
    'commission_if_delivery_in_24h' => env('COMMISSION_IF_DELIVERY_IN_24H', 0.15),
    'stripe_secret' => env('STRIPE_SECRET'),
    'stripe_webhook_key' => env('STRIPE_WEBHOOK_KEY'),
    'mail_from_address' => env('MAIL_FROM_ADDRESS'),
];