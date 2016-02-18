<?php
/**
 * Created by PhpStorm.
 * User: boshurik
 * Date: 14.02.16
 * Time: 17:11
 */

namespace BoShurik\Payum\YandexMoney;

use BoShurik\Payum\YandexMoney\Action\ConvertPaymentAction;
use BoShurik\Payum\YandexMoney\Action\CaptureAction;
use BoShurik\Payum\YandexMoney\Action\NotifyAction;
use BoShurik\Payum\YandexMoney\Action\NotifyNullAction;
use BoShurik\Payum\YandexMoney\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class YandexMoneyGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults(array(
            'payum.factory_name' => 'yandex_money',
            'payum.factory_title' => 'Yandex Money',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.notify' => new NotifyAction($config['payum.security.token_storage']),
            'payum.action.notify_null' => new NotifyNullAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
        ));

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'account' => null,
                'secret' => null,
            );
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = array('account');
            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);
                $apiConfig = array(
                    'account' => $config['account'],
                    'secret' => $config['secret'],
                );

                return new Api($apiConfig);
            };
        }
    }
}
