<?php

namespace Service;

use PHPUnit\Framework\TestCase;
use App\Contracts\ExchangeRatesInterface;
use App\Services\ClientFeeCalculatorService;
use App\Services\FileManagers\FileManagerService;
use App\DTO\ExchangeRatesResult;

class ClientFeeCalculatorServiceTest extends TestCase
{
    protected function setUp(): void
    {
        $this->fileManagerMock = $this->createMock(FileManagerService::class);
        $this->exchangeRatesMock = $this->createMock(ExchangeRatesInterface::class);

        $this->service = new ClientFeeCalculatorService($this->fileManagerMock, $this->exchangeRatesMock);
    }

    public function testProcess(): void
    {
        $inputData = [
            ['2023-01-01', 1, 'private', 'deposit', 1000.00, 'EUR'],
            ['2023-01-02', 1, 'private', 'withdraw', 200.00, 'EUR'],
            ['2023-01-03', 1, 'private', 'withdraw', 800.00, 'EUR'],
            ['2023-01-04', 1, 'business', 'withdraw', 1000.00, 'EUR'],
        ];

        $this->fileManagerMock
            ->method('read')
            ->willReturn($inputData);

        $this->exchangeRatesMock
            ->method('calculateRate')
            ->willReturn(new ExchangeRatesResult(1.0));

        $expectedFees = ['0.30', '0.00', '0.00', '5.00'];

        $this->assertEquals($expectedFees, $this->service->process());
    }
}