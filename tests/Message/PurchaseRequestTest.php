<?php

declare(strict_types=1);

namespace Omnipay\Easypaisa\Tests\Message;

use Omnipay\Easypaisa\Message\AbstractRequest;
use Omnipay\Easypaisa\Message\PurchaseRequest;
use Omnipay\Easypaisa\Tests\Helper;
use Omnipay\Tests\TestCase;

final class PurchaseRequestTest extends TestCase
{
    private AbstractRequest $request;

    private array $parameters = [];

    public function setUp(): void
    {
        $this->request = new PurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->parameters = Helper::getPurchaseParameters();
    }

    public function test_it_generate_valid_uri(): void
    {
        $this->request->setTestMode(false);
        $this->request->setPaymentMethod('MA');

        // Payment, defaults and all elements set.

        $this->assertSame(
            'https://easypay.easypaisa.com.pk/easypay-service/rest/v4/initiate-ma-transaction',
            $this->request->getUri()
        );

        $this->request->setPaymentMethod('OTC');

        $this->assertSame(
            'https://easypay.easypaisa.com.pk/easypay-service/rest/v4/initiate-otc-transaction',
            $this->request->getUri()
        );
    }

    public function test_it_has_valid_data(): void
    {
        $this->request->initialize($this->parameters);

        $this->assertSame([
            'storeId' => 'store123',
            'accountNum' => 'account123',
            'orderId' => '6433cef9cdc87',
            'transactionAmount' => '20.00',
            'transactionType' => 'MA',
            'emailAddress' => 'test@test.com',
            'tokenExpiry' => $this->parameters['tokenExpiry']->format('Ymd His'),
            'optional1' => 'abcdef',
            'mobileAccountNo' => '03211234567',
        ], $this->request->getData());
    }
}
