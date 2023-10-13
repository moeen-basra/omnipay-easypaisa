<?php

declare(strict_types=1);

namespace Omnipay\Easypaisa\Message;

use Omnipay\Common\Message\ResponseInterface;

final class FetchTransactionRequest extends AbstractRequest
{
    protected array $required = [
        'storeId',
        'accountNum',
        'transactionId',
    ];

    public function getUri(): string
    {
        return $this->getEndPoint() . '/easypay-service/rest/v4/inquire-transaction';
    }

    protected function createResponse(array $data): ResponseInterface
    {
        return $this->response = new FetchTransactionResponse($this, $data);
    }

    public function getData(): array
    {
        $this->validate(...$this->required);

        return [
            'orderId' => $this->getTransactionId(),
            'storeId' => $this->getStoreId(),
            'accountNum' => $this->getAccountNum(),
        ];
    }
}
