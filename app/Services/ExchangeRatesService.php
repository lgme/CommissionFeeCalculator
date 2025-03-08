<?php

declare(strict_types = 1);

namespace App\Services;

use App\Contracts\ExchangeRatesInterface;
use App\DTO\ExchangeRatesResult;
use GuzzleHttp\Client;

class ExchangeRatesService implements ExchangeRatesInterface
{
    private string $baseUrl = 'http://api.exchangeratesapi.io/v1/';

    public function __construct(private readonly string $apiKey)
    {
    }

    public function calculateRate(string $base, string $symbol, string $date): ExchangeRatesResult
    {
        $client = new Client(
            [
                'base_uri' => $this->baseUrl . $date,
                'timeout' => 5
            ]
        );

        $params = [
            'base' => $base,
            'symbol' => $symbol,
            'access_key' => $this->apiKey
        ];

        $response = $client->get('', ['query' => $params]);

        $body = json_decode($response->getBody()->getContents(), true);

        return new ExchangeRatesResult((float)($body['rates'][$symbol]));
    }
}