<?php
/**
 * Created by PhpStorm.
 * User: boshurik
 * Date: 17.02.16
 * Time: 19:41
 */

namespace BoShurik\Payum\YandexMoney\Action;

use Payum\Core\Security\GenericTokenFactoryInterface;

/**
 * Implements GenericTokenFactoryAwareInterface
 */
trait GenericTokenFactoryAwareTrait
{
    /**
     * @var GenericTokenFactoryInterface
     */
    protected $tokenFactory;

    /**
     * @inheritDoc
     */
    public function setGenericTokenFactory(GenericTokenFactoryInterface $genericTokenFactory = null)
    {
        $this->tokenFactory = $genericTokenFactory;
    }
}