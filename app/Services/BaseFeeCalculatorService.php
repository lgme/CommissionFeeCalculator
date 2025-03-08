<?php

declare(strict_types = 1);

namespace App\Services;

use App\Contracts\ExchangeRatesInterface;
use App\Contracts\FeeCalculatorInterface;
use App\Services\FileManagers\FileManagerService;

abstract class BaseFeeCalculatorService implements FeeCalculatorInterface
{
    protected const VALID_CURRENCIES = [];
    protected const DEPOSIT_FEE_PERCENTAGE = 0.03;
    protected const WITHDRAW_PRIVATE_FEE_PERCENTAGE = 0.3;
    protected const WITHDRAW_BUSINESS_FEE_PERCENTAGE = 0.5;
    protected const FREE_WITHDRAW_LIMIT = 1000.00;
    protected const FREE_WITHDRAW_COUNT = 3;
    protected const BASE_CURRENCY = 'USD';

    public function __construct(
        protected FileManagerService $input,
        protected ExchangeRatesInterface $exchangeRatesService
    )
    {}

    abstract public function validate(): bool;

    abstract public function process(): array;

    protected function getWeek(string $date): string
    {
        $dateTime = new \DateTime($date);
        $startOfWeek = clone $dateTime->modify('monday this week');
        $endOfWeek = clone $dateTime->modify('sunday this week');

        return $startOfWeek->format('Y-m-d') . ' - ' . $endOfWeek->format('Y-m-d');
    }

    protected function roundUp(float $fee): string
    {
        $precision = 2;
        $value = ceil($fee * pow(10, $precision)) / pow(10, $precision);

        return number_format($value, $precision);
    }
}