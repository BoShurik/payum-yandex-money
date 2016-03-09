# Payum Yandex Money gateway

## Installation

```bash
$ composer require boshurik/payum-yandex-money
```

## Yandex configuration

In your [**profile**][1]
* Enable http-notification
* Set http-address for notifications. For `PayumBundle` users it would be `https://example.com/payment/notify/unsafe/yandex_money`

## Configuration

```php
<?php
//config.php

use Payum\Core\PayumBuilder;
use Payum\Core\Payum;

/** @var Payum $payum */
$payum = (new PayumBuilder())
    ->addDefaultStorages()
    ->addGateway('gatewayName', [
        'factory' => 'yandex_money',
        'account'  => 'change it',
        'secret'  => 'change it',
    ])
    ->getPayum()
;
```

## Payment

### Additional parameters

* Payment type (YandexMoney `paymentType` option)
```php
use BoShurik\Payum\YandexMoney\Api;

/** @var Payment $payment */
$payment->setDetails(array(
    Api::FIELD_PAYMENT_TYPE => Api::PAYMENT_AC, // Default
));
```
* Form type (YandexMoney `quickpay-form` option)
```php
use BoShurik\Payum\YandexMoney\Api;

/** @var Payment $payment */
$payment->setDetails(array(
    Api::FIELD_QUICKPAY_FORM => Api::QUICKPAY_FORM_SHOP, // Default
));
```

## Symfony

### Installation

#### 1.x

```php
<?php
namespace AppBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use BoShurik\Payum\YandexMoney\Bridge\Symfony\YandexMoneyGatewayFactory;
use Payum\Bundle\PayumBundle\DependencyInjection\PayumExtension;

class AppBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var $extension PayumExtension */
        $extension = $container->getExtension('payum');

        $extension->addGatewayFactory(new YandexMoneyGatewayFactory());
    }
}
```

#### 2.x

```yaml
services:
    app.yandex_money.gateway_factory_builder:
        class: Payum\Core\Bridge\Symfony\Builder\GatewayFactoryBuilder
        arguments:
            - BoShurik\Payum\YandexMoney\YandexMoneyGatewayFactory
        tags:
            - { name: payum.gateway_factory_builder, factory: yandex_money }
```

### Configuration

#### 1.x

```yaml
payum:
    gateways:
        yandex_money:
            yandex_money:
                account: %yandex_account%
                secret: %yandex_secret%
```

#### 2.x

```yaml
payum:
    gateways:
        yandex_money:
            factory: yandex_money
            account: %yandex_account%
            secret: %yandex_secret%
```
## Resources
* [Payum](http://payum.org)
* [Yandex](https://money.yandex.ru/doc.xml?id=526991)

[1]: https://money.yandex.ru/myservices/online.xml