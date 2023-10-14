<?php

namespace Omnipay\Easypaisa\Message;

use GuzzleHttp\Exception\ClientException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $zeroAmountAllowed = false;

    protected array $allowedLanguages = ['EN'];
    protected array $allowedCurrencies = ['PKR'];
    protected array $allowedPaymentMethods = ['OTC', 'MA'];

    protected string $liveEndpoint = 'https://easypay.easypaisa.com.pk';
    protected string $testEndpoint = 'https://easypaystg.easypaisa.com.pk';

    protected array $required = [];

    abstract public function getUri(): string;

    abstract protected function createResponse(array $data): ResponseInterface;

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

    public function setLanguage(string $value): static
    {
        if (!in_array($value, $this->allowedLanguages)) {
            throw new InvalidRequestException(
                sprintf(
                    'The language "%s" is invalid, must be one of %s',
                    $value,
                    implode(
                        ', ',
                        $this->allowedLanguages
                    )
                )
            );
        }
        return $this->setParameter('language', strtoupper($value));
    }

    public function getLanguage(): string
    {
        return $this->getParameter('language');
    }

    public function setPaymentMethod($value): static
    {
        if (!in_array($value, $this->allowedPaymentMethods)) {
            throw new InvalidRequestException(
                sprintf(
                    'The paymentMethod "%s" is invalid, must be one of %s',
                    $value,
                    implode(
                        ', ',
                        $this->allowedPaymentMethods
                    )
                )
            );
        }
        return $this->setParameter('paymentMethod', $value);
    }

    public function setCurrency($value): static
    {
        if (!in_array($value, $this->allowedCurrencies)) {
            throw new InvalidRequestException(
                sprintf(
                    'The currency "%s" is invalid, must be one of %s',
                    $value,
                    implode(
                        ', ',
                        $this->allowedCurrencies
                    )
                )
            );
        }
        return $this->setParameter('currency', $value);
    }

    public function setMobileNumber(string $value): static
    {
        return $this->setParameter(
            'mobileNumber',
            preg_replace('/^(92|0092|\+92)/', '0', $value)
        );
    }

    public function getMobileNumber(): string
    {
        return $this->getParameter('mobileNumber');
    }

    public function getEndPoint(): string
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function setExtra(array $value): self
    {
        return $this->setParameter('extra', $value);
    }

    public function getExtra(?string $key = null): mixed
    {
        if ($key) {
            if (isset($this->getParameter('extra')[$key])) {
                return $this->getParameter('extra')[$key];
            }
            return null;
        }
        return $this->getParameter('extra') ?? [];
    }

    /**
     * send the data to the gateway and create response
     *
     * @param $data
     * @return ResponseInterface
     * @throws InvalidResponseException
     * @throws \JsonException
     */
    public function sendData($data): ResponseInterface
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->getUri(),
                $this->getHeaders(),
                json_encode($data, JSON_THROW_ON_ERROR)
            );

            $data = json_decode($response->getBody()->getContents(), true);

            return $this->createResponse($data);
        } catch (ClientException $exception) {
            throw new InvalidResponseException($exception->getMessage(), 400, $exception);
        }
    }

    /**
     * prepare the request headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Credentials' => base64_encode(
                sprintf('%s:%s', $this->getUsername(), $this->getPassword())
            )
        ];
    }
}
