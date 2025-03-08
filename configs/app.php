<?php

declare(strict_types = 1);

return [
    'app_name'              => $_ENV['APP_NAME'],
    'app_version'           => $_ENV['APP_VERSION'] ?? '1.0',
    'display_error_details' => (bool) ($_ENV['APP_DEBUG'] ?? 0),
    'log_errors'            => true,
    'log_error_details'     => true,
    'apiKeys'               => [
        'exchangeratesapi' => $_ENV['EXCHANGERATES_API_KEY'],
    ],
];