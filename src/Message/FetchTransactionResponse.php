<?php

declare(strict_types=1);

namespace Omnipay\Easypaisa\Message;

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Easypaisa\Tests\GatewayTest;

final class FetchTransactionResponse extends AbstractResponse implements NotificationInterface
{
    /**
     * The expected transaction status coming from easypaisa
     *
     * @var array|string[]
     */
    protected array $transactionStatuses = [
        'PAID', 'FAILED', 'PENDING', 'BLOCKED', 'EXPIRED', 'REVERSED'
    ];

    /**
     * Get the transaction reference
     * @return string
     *
     * The gateway never return the transaction id in fetch request, in order
     * to get the transaction reference here we need to pass it with the payload check
     * the test for reference {@link GatewayTest::test_gateway_fetch_transaction_success()}
     */
    public function getTransactionReference(): string
    {
        return $this->request->getParameters()['transactionReference'] ?? '';
    }

    public function getTransactionStatus(): string
    {
        $status = !empty($this->data['transactionStatus']) ? $this->data['transactionStatus'] : 'UNKNOWN';

        return match ($status) {
            'PAID' => NotificationInterface::STATUS_COMPLETED,
            'PENDING' => NotificationInterface::STATUS_PENDING,
            default => NotificationInterface::STATUS_FAILED,
        };
    }

    public function getMessage(): string
    {
        if (!empty($this->data['responseDesc'])) {
            return $this->data['responseDesc'];
        }
        // This can be logged
        return 'UNKNOWN';
    }
}
