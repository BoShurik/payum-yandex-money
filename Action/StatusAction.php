<?php
/**
 * Created by PhpStorm.
 * User: boshurik
 * Date: 15.02.16
 * Time: 13:37
 */

namespace BoShurik\Payum\YandexMoney\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use BoShurik\Payum\YandexMoney\Api;

class StatusAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (!isset($model[Api::FIELD_STATUS]) || Api::STATUS_NEW == $model[Api::FIELD_STATUS]) {
            $request->markNew();

            return;
        }

        if (Api::STATUS_PENDING == $model[Api::FIELD_STATUS]) {
            $request->markPending();

            return;
        }

        if (Api::STATUS_CAPTURED == $model[Api::FIELD_STATUS]) {
            $request->markCaptured();

            return;
        }

        $request->markUnknown();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
