<?php
/**
 * Created by PhpStorm.
 * User: boshurik
 * Date: 17.02.16
 * Time: 19:00
 */

namespace BoShurik\Payum\YandexMoney\Action;

use BoShurik\Payum\YandexMoney\Api;
use Payum\Core\Exception\UnsupportedApiException;

/**
 * Implements ApiAwareInterface
 */
trait ApiAwareTrait
{
    /**
     * @var Api
     */
    protected $api;

    /**
     * @inheritDoc
     */
    public function setApi($api)
    {
        if (false == $api instanceof Api) {
            throw new UnsupportedApiException('Api is not supported.');
        }

        $this->api = $api;
    }
}