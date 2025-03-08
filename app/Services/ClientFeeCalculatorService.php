<?php

namespace App\Services;

use App\Enums\OperationTypes;
use App\Enums\UserTypes;

class ClientFeeCalculatorService extends BaseFeeCalculatorService
{
    protected const VALID_CURRENCIES = ['EUR', 'USD', 'JPY'];
    protected const BASE_CURRENCY = 'EUR';

    public function validate(): bool
    {
        $data = $this->input->read();

        foreach ($data as $row) {
            [$date, $userId, $userType, $operationType, $amount, $currency] = $row;

            if (!\DateTime::createFromFormat('Y-m-d', $date)) {
                return false;
            }

            // Validate user ID as integer
            if (!filter_var($userId, FILTER_VALIDATE_INT)) {
                return false;
            }

            // Validate user type
            if (!in_array($userType, array_column(UserTypes::cases(), 'value'))) {
                return false;
            }

            // Validate operation type
            if (!in_array($operationType, array_column(OperationTypes::cases(), 'value'))) {
                return false;
            }

            // Validate operation amount
            if (!is_numeric($amount)) {
                return false;
            }

            // Validate currency
            if (!in_array($currency, self::VALID_CURRENCIES)) {
                return false;
            }
        }

        return true;
    }

    public function process(): array
    {
        $data = $this->input->read();
        $fees = [];
        $userWithdrawalsPerWeek = [];

        foreach ($data as $row) {
            [$date, $userId, $userType, $operationType, $amount, $currency] = $row;

            if ($currency !== self::BASE_CURRENCY) {
                $rate = $this->exchangeRatesService->calculateRate(self::BASE_CURRENCY, $currency, $date)->rate;
                $amount = $amount / $rate;
            }

            $fee = 0.0;

            if ($operationType === OperationTypes::Deposit->value) {
                $fee = $amount * (self::DEPOSIT_FEE_PERCENTAGE / 100);
            }

            if ($operationType === OperationTypes::Withdraw->value) {
                if ($userType === UserTypes::Private->value) {
                    $week = $this->getWeek($date);

                    if (!isset($userWithdrawalsPerWeek[$userId])) {
                        $userWithdrawalsPerWeek[$userId] = [];
                    }
                    if (!isset($userWithdrawalsPerWeek[$userId][$week])) {
                        $userWithdrawalsPerWeek[$userId][$week] = [
                            'count' => 0,
                            'amount' => 0.0
                        ];
                    }

                    $userWithdrawalsPerWeek[$userId][$week]['count']++;

                    if ($userWithdrawalsPerWeek[$userId][$week]['count'] <= self::FREE_WITHDRAW_COUNT) {
                        $freeAmount = self::FREE_WITHDRAW_LIMIT - $userWithdrawalsPerWeek[$userId][$week]['amount'];
                        if ($amount > $freeAmount) {
                            $fee = ($amount - $freeAmount) * (self::WITHDRAW_PRIVATE_FEE_PERCENTAGE / 100);
                            $userWithdrawalsPerWeek[$userId][$week]['amount'] = self::FREE_WITHDRAW_LIMIT;
                        } else {
                            $userWithdrawalsPerWeek[$userId][$week]['amount'] += $amount;
                        }
                    } else {
                        $fee = $amount * (self::WITHDRAW_PRIVATE_FEE_PERCENTAGE / 100);
                    }
                }

                if ($userType === UserTypes::Business->value) {
                    $fee = $amount * (self::WITHDRAW_BUSINESS_FEE_PERCENTAGE / 100);
                }
            }

            if ($currency !== self::BASE_CURRENCY) {
                $fee *= $rate;
            }

            $fees[] = $this->roundUp($fee);
        }

        return $fees;
    }
}