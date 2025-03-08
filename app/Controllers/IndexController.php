<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Contracts\FeeCalculatorInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Factory\StreamFactory;

class IndexController
{
    public function __construct(private FeeCalculatorInterface $feeCalculator)
    {
    }

    public function index(Request $request, Response $response): Response
    {
        if (!$this->feeCalculator->validate()) {
            return $response
                ->withHeader('Content-Type', 'text/plain')
                ->withBody((new StreamFactory())->createStream('Validation Error.'));
        }

        return $response
            ->withHeader('Content-Type', 'text/plain')
            ->withBody((new StreamFactory())->createStream(json_encode($this->feeCalculator->process())));
    }
}