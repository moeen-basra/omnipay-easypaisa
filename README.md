# Omnipay: Easypaisa

**Telenor Easypaisa gateway for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements easypaisa support for Omnipay.

## Install

Via Composer

```bash
$ composer require moeen-basra/omnipay-easypaisa
```

## Usage

This gateway provides seamless integration with easypaisa rest API integration.


### Purchase Request
```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Easypaisa');

// initialize with array
$gateway->initialize([
    'storeId' => 'your-store-id',
    'username' => 'your-username',
    'password' => 'your-password',
    'accountNum' => 'your-account-number',
]);

// or individual properties setter

$gateway->setAcountId('your-store-id')
    ->setUsername('your-username')
    ->setPassword('your-password')
    ->setAccountNum('your-account-number');

// set the test mode if needed
$gateway->setTestMode(true);

try {
    $parameters = [
        'transactionId' => '<transId>',
        'amount' => '<amount>', // float
        'paymentMethod' => 'OTC', // OTC or MA
        'emailAddress' => 'customer-email',
        'mobileNumber' => 'customer-phone', // 10 digits phone 03xxxxxxxxx
        'tokenExpiry' => (30 * 60), // 30 minutes
        'extra' => [
            'field_1' => 'value_1',
            'field_2' => 'value_2',
            'field_3' => 'value_3',
            'field_4' => 'value_4',
            'field_5' => 'value_5'
        ],
    ];
    
    $response = $gateway->purchase($parameters)->send();
    
    // var_dump($response->getData());
    
    if ($response->isSuccessful()) {
     // handle success response
    } else {
    // handle failed response
    }
} catch (\Throwable $exception) {
    var_dump($exception);
}
```

### Inquiry Request
```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Easypaisa');

// initialize with array
$gateway->initialize([
    'storeId' => 'your-store-id',
    'username' => 'your-username',
    'password' => 'your-password',
    'accountNum' => 'your-account-number',
]);

// set the test mode if needed
$gateway->setTestMode(true);

try {
    $parameters = [
        'transactionId' => '<transId>',
    ];
    
    $response = $gateway->fetchTransaction($parameters)->send();
    
    // var_dump($response->getData());
    
    if ($response->isSuccessful()) {
     // handle success response
    } else {
    // handle failed response
    }
} catch (\Throwable $exception) {
    var_dump($exception);
}

```

**NOTE:** You can check the tests Mock for sample response data.

## License

This package is released under the [MIT License](https://opensource.org/licenses/MIT). See the [LICENSE](LICENSE) file for details.

## Contact
You can reach me here [moeen.basra@gamil.com](mailto:moeen.basra@gamil.com)
