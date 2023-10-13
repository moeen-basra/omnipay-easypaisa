<?php

declare(strict_types=1);

namespace Omnipay\Easypaisa\Tests;

use Omnipay\Common\AbstractGateway;
use Omnipay\Easypaisa\Gateway;
use Omnipay\Easypaisa\Message\FetchTransactionRequest;
use Omnipay\Easypaisa\Message\PurchaseRequest;
use Omnipay\Tests\TestCase;

class GatewayTest extends TestCase
{
    protected AbstractGateway $gateway;


    protected array $options = [];

    protected array $parameters = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = Helper::getOptions();
        $this->parameters = Helper::getParameters();

        $this->gateway->initialize($this->options);
    }

    public function test_gateway_allow_purchase_request(): void
    {
        $this->assertTrue($this->gateway->supportsPurchase());
    }

    public function test_purchase_request_construct_valid(): void
    {
        $request = $this->gateway->purchase($this->parameters);

        $this->assertInstanceOf(PurchaseRequest::class, $request);
        $this->assertArrayHasKey('transactionAmount', $request->getData());
    }

    public function test_purchase_parameters(): void
    {
        foreach ($this->gateway->getDefaultParameters() as $key => $default) {
            // set property on gateway
            $getter = 'get' . ucfirst($this->camelCase($key));
            $setter = 'set' . ucfirst($this->camelCase($key));
            $value = uniqid('', true);
            $this->gateway->$setter($value);

            // request should have matching property, with correct value
            $request = $this->gateway->purchase($this->parameters);
            $this->assertSame($value, $request->$getter());
        }
    }

    public function test_purchase_success(): void
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->gateway->purchase($this->parameters)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('0000', $response->getCode());
        $this->assertSame('SUCCESS', $response->getMessage());
    }

    public function test_purchase_error_server(): void
    {
        $this->setMockHttpResponse('PurchaseFailed_SystemError.txt');
        $response = $this->gateway->purchase($this->parameters)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0001', $response->getCode());
        $this->assertSame('SYSTEM ERROR', $response->getMessage());
    }


    public function test_purchase_error_missing_field(): void
    {
        $this->setMockHttpResponse('PurchaseFailed_FieldMissing.txt');
        $response = $this->gateway->purchase($this->parameters)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0002', $response->getCode());
        $this->assertSame('REQUIRED FIELD MISSING', $response->getMessage());
    }

    public function test_purchase_error_invalid_store_id(): void
    {
        $this->setMockHttpResponse('PurchaseFailed_InvalidStoreId.txt');
        $response = $this->gateway->purchase($this->parameters)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0006', $response->getCode());
        $this->assertSame('INVALID STORE ID', $response->getMessage());
    }

    public function test_purchase_error_invalid_credentials(): void
    {
        $this->setMockHttpResponse('PurchaseFailed_InvalidCredentials.txt');
        $response = $this->gateway->purchase($this->parameters)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('0010', $response->getCode());
        $this->assertSame('INVALID CREDENTIALS', $response->getMessage());
    }

    public function test_gateway_allow_inquiry_request(): void
    {
        $this->assertTrue($this->gateway->supportsFetchTransaction());
    }

    public function test_fetch_request_construct_valid(): void
    {
        $request = $this->gateway->fetchTransaction(Helper::getFetchParameters());

        $this->assertInstanceOf(FetchTransactionRequest::class, $request);
        $this->assertArrayHasKey('orderId', $request->getData());
    }

    public function test_fetch_success(): void
    {
        $this->setMockHttpResponse('FetchSuccess.txt');
        $response = $this->gateway->purchase($this->parameters)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('0000', $response->getCode());
        $this->assertSame('SUCCESS', $response->getMessage());
    }
}
