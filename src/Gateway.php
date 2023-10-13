<?php

namespace Omnipay\Easypaisa;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Easypaisa\Message\FetchTransactionRequest;
use Omnipay\Easypaisa\Message\PurchaseRequest;

class Gateway extends AbstractGateway
{
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'Easypaisa';
    }

    /**
     * @inheritDoc
     */
    public function getShortName(): string
    {
        return 'Easypaisa';
    }

    /**
     * @inheritDoc
     */
    public function getDefaultParameters(): array
    {
        return [
            'username' => '',
            'password' => '',
            'storeId' => '',
            'accountNum' => '',
        ];
    }

    public function purchase(array $options): AbstractRequest
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function fetchTransaction(array $options): AbstractRequest
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }

    public function setUsername(string $value): static
    {
        return $this->setParameter('username', $value);
    }

    public function getUsername(): string
    {
        return $this->getParameter('username');
    }

    public function setStoreId(string $value): static
    {
        return $this->setParameter('storeId', $value);
    }

    public function getStoreId(): string
    {
        return $this->getParameter('storeId');
    }

    public function setPassword(string $value): static
    {
        return $this->setParameter('password', $value);
    }

    public function getPassword(): string
    {
        return $this->getParameter('password');
    }

    public function setAccountNum(string $value): static
    {
        return $this->setParameter('accountNum', $value);
    }

    public function getAccountNum(): string
    {
        return $this->getParameter('accountNum');
    }
}
