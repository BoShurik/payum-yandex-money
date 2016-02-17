<?php
/**
 * Created by PhpStorm.
 * User: boshurik
 * Date: 15.02.16
 * Time: 17:54
 */

namespace BoShurik\Payum\YandexMoney\Action;

use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\Notify;
use BoShurik\Payum\YandexMoney\Api;

/**
 * NotifyAction
 */
class NotifyAction extends GatewayAwareAction implements ApiAwareInterface
{
    /**
     * Implements ApiAwareInterface
     */
    use ApiAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Notify $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        if (false == $this->api->verifyRequest($httpRequest->request)) {
            throw new HttpResponse('The notification is invalid. [2]', 400);
        }

        if ($model['amount'] != $httpRequest->request['amount']) {
            throw new HttpResponse('The notification is invalid. [3]', 400);
        }

        $model->replace(array(
            Api::FIELD_STATUS => Api::STATUS_CAPTURED,
        ));

        throw new HttpResponse('', 204);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Notify &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
