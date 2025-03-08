<?php

declare(strict_types = 1);

$app    = require __DIR__ . '/../bootstrap.php';
$router = require CONFIG_PATH . '/routes.php';

$router($app);

$app->run();