<?php

declare(strict_types=1);

namespace Omnipay\Easypaisa\Message;

final class FetchTransactionResponse extends AbstractResponse
{
    protected array $transactionStatuses = [
        'PAID', 'FAILED', 'PENDING', 'BLOCKED', 'EXPIRED', 'REVERSED'
    ];
}
