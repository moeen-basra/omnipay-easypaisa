<?php

namespace Omnipay\Easypaisa\Message;


abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    protected array $responseCodes = [
        '0000' => 'SUCCESS',
        '0001' => 'SYSTEM ERROR',
        '0002' => 'REQUIRED FIELD MISSING',
        '0003' => 'INVALID ORDER ID',
        '0004' => 'INVALID MERCHANT ACCOUNT NUMBER',
        '0005' => 'MERCHANT ACCOUNT NOT ACTIVE',
        '0006' => 'INVALID STORE ID',
        '0007' => 'STORE NOT ACTIVE',
        '0008' => 'PAYMENT METHOD NOT ENABLED',
        '0010' => 'INVALID CREDENTIALS',
        '0013' => 'LOW BALANCE',
        '0014' => 'ACCOUNT DOES NOT EXIST',
        '0015' => 'INVALID_TOKEN_EXPIRY',
        '0016' => 'Expiry date should be future date',
    ];

    public function isSuccessful(): bool
    {
        return $this->data['responseCode'] === "0000";
    }

    public function getMessage(): string
    {
        return $this->data['responseDesc'];
    }

    public function getCode(): string
    {
        return $this->data['responseCode'];
    }
}
