<?php

namespace Omnipay\Easypaisa\Message;

class PurchaseResponse extends AbstractResponse
{
    public function getTransactionId(): string
    {
        return $this->isSuccessful()
            ? $this->data['orderId']
            : $this->request->getParameters()['transactionId'];
    }

    public function getTransactionReference(): ?string
    {
        return $this->isSuccessful()
            ? $this->data['transactionId']
            : null;
    }
}
