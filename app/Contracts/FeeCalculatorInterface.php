<?php

declare(strict_types = 1);

namespace App\Contracts;

interface FeeCalculatorInterface
{
    public function validate(): bool;

    public function process(): array;
}