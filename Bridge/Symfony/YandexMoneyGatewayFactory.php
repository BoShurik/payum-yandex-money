<?php
/**
 * Created by PhpStorm.
 * User: boshurik
 * Date: 14.02.16
 * Time: 17:04
 */

namespace BoShurik\Payum\YandexMoney\Bridge\Symfony;

use Payum\Bundle\PayumBundle\DependencyInjection\Factory\Gateway\AbstractGatewayFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use BoShurik\Payum\YandexMoney\YandexMoneyGatewayFactory as BaseGatewayFactory;

/**
 * YandexMoneyGatewayFactory for Symfony
 */
class YandexMoneyGatewayFactory extends AbstractGatewayFactory
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return 'yandex_money';
    }

    /**
     * @inheritDoc
     */
    public function addConfiguration(ArrayNodeDefinition $builder)
    {
        parent::addConfiguration($builder);

        $builder
            ->children()
                ->scalarNode('account')->isRequired()->end()
                ->scalarNode('secret')->defaultNull()->end()
            ->end()
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function getPayumGatewayFactoryClass()
    {
        return BaseGatewayFactory::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getComposerPackage()
    {
        return 'boshurik/payum-yandex-money';
    }
}