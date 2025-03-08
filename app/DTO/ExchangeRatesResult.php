<?php

declare(strict_types = 1);

namespace App\DTO;

class ExchangeRatesResult
{
    public function __construct(public readonly float $rate)
    {
    }
}