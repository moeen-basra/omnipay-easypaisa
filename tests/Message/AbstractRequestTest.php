<?php

declare(strict_types=1);

namespace Omnipay\Easypaisa\Tests\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Easypaisa\Message\AbstractRequest;
use Omnipay\Easypaisa\Message\PurchaseRequest;
use Omnipay\Tests\TestCase;

final class AbstractRequestTest extends TestCase
{
    private AbstractRequest $request;

    public function setUp(): void
    {
        $this->request = new PurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );
    }

    public function test_it_sets_only_allowed_payment_method(): void
    {
        $this->request->setPaymentMethod('OTC');

        $this->assertSame('OTC', $this->request->getPaymentMethod());

        try {
            $this->request->setPaymentMethod('PaymentMethod');
        } catch (InvalidRequestException $exception) {
            $this->assertStringContainsString('The paymentMethod "PaymentMethod" is invalid', $exception->getMessage());
        }
    }

    public function test_it_sets_only_allowed_language(): void
    {
        $this->request->setLanguage('EN');

        $this->assertSame('EN', $this->request->getLanguage());

        try {
            $this->request->setLanguage('DE');
        } catch (InvalidRequestException $exception) {
            $this->assertStringContainsString('The language "DE" is invalid', $exception->getMessage());
        }
    }

    public function test_it_sets_only_allowed_currency(): void
    {
        $this->request->setCurrency('PKR');

        $this->assertSame('PKR', $this->request->getCurrency());

        try {
            $this->request->setCurrency('AUD');
        } catch (InvalidRequestException $exception) {
            $this->assertStringContainsString('The currency "AUD" is invalid', $exception->getMessage());
        }
    }

    public function test_it_generate_valid_endpoints(): void
    {
        $this->request->setTestMode(false);

        $this->assertSame(
            'https://easypay.easypaisa.com.pk',
            $this->request->getEndPoint()
        );

        $this->request->setTestMode(true);

        $this->assertSame(
            'https://easypaystg.easypaisa.com.pk',
            $this->request->getEndPoint()
        );

    }

    public function test_it_set_valid_mobile_number(): void
    {
        $this->request->setMobileNumber('923211234567');

        $this->assertSame('03211234567', $this->request->getMobileNumber());

        $this->request->setMobileNumber('+923211234567');

        $this->assertSame('03211234567', $this->request->getMobileNumber());

        $this->request->setMobileNumber('00923211234567');
    }

    public function test_it_sets_valid_extra_data_fields(): void
    {
        $extra = [
            'field_1' => 'value_1',
            'field_2' => 'value_2',
        ];

        $this->request->setExtra($extra);

        $this->assertSame($extra, $this->request->getExtra());
        $this->assertSame('value_1', $this->request->getExtra('field_1'));
        $this->assertSame(null, $this->request->getExtra('field_3'));
    }

    public function test_it_has_valid_headers(): void
    {
        $this->request->setUsername('username');
        $this->request->setPassword('password');

        $this->assertArrayHasKey('Accept', $this->request->getHeaders());
        $this->assertArrayHasKey('Content-Type', $this->request->getHeaders());
        $this->assertArrayHasKey('Credentials', $this->request->getHeaders());
    }
}
