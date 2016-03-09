<?php
/**
 * Created by PhpStorm.
 * User: boshurik
 * Date: 17.02.16
 * Time: 19:31
 */

namespace BoShurik\Payum\YandexMoney\Action;

use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\GetToken;
use Payum\Core\Request\Notify;

/**
 * NotifyNullAction
 */
class NotifyNullAction extends GatewayAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param Notify $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        if (!isset($httpRequest->request['label']) || empty($httpRequest->request['label'])) {
            throw new HttpResponse('The notification is invalid. [1]', 400, array(
                'x-reason-code' => 1,
            ));
        }

        $this->gateway->execute($getToken = new GetToken($httpRequest->request['label']));
        $this->gateway->execute(new Notify($getToken->getToken()));
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Notify &&
            null === $request->getModel()
        ;
    }
}