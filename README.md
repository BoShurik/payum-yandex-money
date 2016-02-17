# Payum Yandex Money gateway

## Installation

```bash
$ composer require boshurik/payum-yandex-money
```

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

## Symfony

### Installation

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

### Configuration
```yaml
payum:
    gateways:
        yandex_money:
            yandex_money:
                account: %yandex_account%
                secret: %yandex_secret%
```

## Resources
* [Payum](http://payum.org/)