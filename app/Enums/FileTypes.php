<?php

declare(strict_types = 1);

namespace App\Enums;

enum FileTypes: string
{
    case Csv = 'csv';
    case Pdf = 'pdf';
}