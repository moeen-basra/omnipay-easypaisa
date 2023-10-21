<?php

declare(strict_types=1);

namespace Omnipay\Easypaisa\Tests;

final class Helper
{
    public static function getOptions(array $options = []): array
    {
        return [
            'storeId' => 'store123',
            'username' => 'username',
            'password' => 'password',
            'accountNum' => 'account123',
            ...$options,
        ];
    }

    public static function getParameters(array $parameters = []): array
    {
        $timezone = new \DateTimeZone('Asia/Karachi');
        $expiry = new \DateTime('now + 30 min', $timezone);
        return [
            'transactionId' => '6433cef9cdc87',
            'amount' => '20',
            'currency' => 'PKR',
            'paymentMethod' => 'MA',
            'emailAddress' => 'test@test.com',
            'mobileNumber' => '923211234567',
            'tokenExpiry' => $expiry,
            'extra' => [
                'field_1' => 'abcdef'
            ],
            ...$parameters
        ];
    }

    public static function getPurchaseParameters(array $parameters = []): array
    {
        return [
            ...self::getOptions(),
            ...self::getParameters(),
            ...$parameters
        ];
    }

    public static function getFetchParameters(array $parameters = []): array
    {
        return [
            ...self::getOptions(),
            'transactionId' => self::getParameters()['transactionId'],
            'transactionReference' => '21154867845',
            ...$parameters
        ];
    }
}
