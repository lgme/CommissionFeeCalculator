<?php

declare(strict_types = 1);

use App\Config;
use App\Contracts\ExchangeRatesInterface;
use App\Contracts\FeeCalculatorInterface;
use App\Services\ClientFeeCalculatorService;
use App\Services\ExchangeRatesService;
use App\Services\FileManagers\CsvManagerService;

use function DI\create;

return [
    Config::class => create(Config::class)->constructor(require CONFIG_PATH . '/app.php'),
    ExchangeRatesInterface::class => function (Config $config) {
        return new ExchangeRatesService($config->get('apiKeys.exchangeratesapi'));
    },
    FeeCalculatorInterface::class => function (ExchangeRatesInterface $exchangeRatesService) {
        $input = new CsvManagerService(STORAGE_PATH . '/input.csv');
        return new ClientFeeCalculatorService($input, $exchangeRatesService);
    },
];