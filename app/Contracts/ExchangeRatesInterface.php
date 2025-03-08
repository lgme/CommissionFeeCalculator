<?php

declare(strict_types = 1);

namespace App\Contracts;

use App\DTO\ExchangeRatesResult;

interface ExchangeRatesInterface
{
    public function calculateRate(string $base, string $symbol, string $date): ExchangeRatesResult;
}