<?php

namespace Omnipay\Easypaisa\Message;

use DateTime;
use Omnipay\Common\Message\ResponseInterface;

class PurchaseRequest extends AbstractRequest
{
    protected array $required = [
        'storeId',
        'accountNum',
        'transactionId',
        'amount',
        'paymentMethod',
        'mobileNumber',
        'emailAddress',
        'tokenExpiry',
    ];

    public function setEmailAddress(string $value): static
    {
        return $this->setParameter('emailAddress', $value);
    }

    public function getEmailAddress(): string
    {
        return $this->getParameter('emailAddress');
    }

    public function setTokenExpiry(int $value): static
    {
        $timezone = new \DateTimeZone('Asia/Karachi');
        $datetime = new DateTime("now + $value seconds", $timezone);

        return $this->setParameter('tokenExpiry', $datetime->format('Ymd His'));
    }

    public function getTokenExpiry(): string
    {
        return $this->getParameter('tokenExpiry');
    }

    public function getData(): array
    {
        $this->validate(...$this->required);

        $data = array_filter(
            [
            'storeId' => $this->getStoreId(),
            'accountNum' => $this->getAccountNum(),
            'orderId' => $this->getTransactionId(),
            'transactionAmount' => $this->getAmount(),
            'transactionType' => $this->getPaymentMethod(),
            'emailAddress' => $this->getEmailAddress(),
            'tokenExpiry' => $this->getTokenExpiry(),
            'optional1' => $this->getExtra('field_1'),
            'optional2' => $this->getExtra('field_2'),
            'optional3' => $this->getExtra('field_3'),
            'optional4' => $this->getExtra('field_4'),
            'optional5' => $this->getExtra('field_5'),
            ]
        );

        if ($this->getPaymentMethod() === 'MA') {
            $data['mobileAccountNo'] = $this->getMobileNumber();
        } elseif ($this->getPaymentMethod() === 'OTC') {
            $data['msisdn'] = $this->getMobileNumber();
        }

        return $data;
    }

    public function createResponse(array $data): ResponseInterface
    {
        return $this->response = new PurchaseResponse($this, $data);
    }


    public function getUri(): string
    {
        $uri = $this->getPaymentMethod() === 'MA'
            ? '/easypay-service/rest/v4/initiate-ma-transaction'
            : '/easypay-service/rest/v4/initiate-otc-transaction';

        return $this->getEndPoint() . $uri;
    }
}
