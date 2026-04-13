<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\NullHandler;

return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'channels' => [
        'stack' => [
            'driver'   => 'stack',
            'channels' => ['daily'],
            'ignore_exceptions' => false,
        ],

        'daily' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/laravel.log'),
            'level'  => env('LOG_LEVEL', 'error'),   // <= cut noise
            'days'   => 14,                          // keep 14 days
        ],

        'single' => [
            'driver' => 'single',
            'path'   => storage_path('logs/laravel.log'),
            'level'  => env('LOG_LEVEL', 'error'),
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],
    ],
];
