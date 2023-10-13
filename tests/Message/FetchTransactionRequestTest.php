<?php

declare(strict_types=1);

namespace Omnipay\Easypaisa\Tests\Message;

use Omnipay\Easypaisa\Message\AbstractRequest;
use Omnipay\Easypaisa\Message\FetchTransactionRequest;
use Omnipay\Easypaisa\Tests\Helper;
use Omnipay\Tests\TestCase;

final class FetchTransactionRequestTest extends TestCase
{
    private AbstractRequest $request;

    private array $parameters = [];

    public function setUp(): void
    {
        $this->request = new FetchTransactionRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->parameters = Helper::getFetchParameters();
    }

    public function test_it_generate_valid_uri(): void
    {
        $this->request->setTestMode(false);

        $this->assertSame(
            'https://easypay.easypaisa.com.pk/easypay-service/rest/v4/inquire-transaction',
            $this->request->getUri()
        );

        $this->request->setTestMode(true);

        $this->assertSame(
            'https://easypaystg.easypaisa.com.pk/easypay-service/rest/v4/inquire-transaction',
            $this->request->getUri()
        );
    }

    public function test_it_has_valid_data(): void
    {
        $this->request->initialize($this->parameters);

        $this->assertSame([
            'orderId' => '6433cef9cdc87',
            'storeId' => 'store123',
            'accountNum' => 'account123',
        ], $this->request->getData());
    }
}
