<?php
/**
 * Created by PhpStorm.
 * User: boshurik
 * Date: 15.02.16
 * Time: 13:37
 */

namespace BoShurik\Payum\YandexMoney\Action;

use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Reply\HttpPostRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use BoShurik\Payum\YandexMoney\Api;

/**
 * CaptureAction
 */
class CaptureAction extends GatewayAwareAction implements ApiAwareInterface, GenericTokenFactoryAwareInterface
{
    /**
     * Implements ApiAwareInterface
     */
    use ApiAwareTrait;

    /**
     * Implements GenericTokenFactoryAwareInterface
     */
    use GenericTokenFactoryAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (!$this->tokenFactory) {
            throw new \LogicException('GenericTokenFactoryExtension must be enabled.');
        }

        $notifyToken = $this->tokenFactory->createNotifyToken(
            $request->getToken()->getGatewayName(),
            $request->getToken()->getDetails()
        );

        $fields = array(
            'formcomment' =>  $model['description'],
            'short-dest' => $model['description'],
            'targets' => $model['description'],
            'sum' => $model['amount'],

            'label' => $notifyToken->getHash(),
            'successURL' => $request->getToken()->getAfterUrl(),
        );
        if (isset($model[Api::FIELD_PAYMENT_TYPE])) {
            $fields['paymentType'] = $model[Api::FIELD_PAYMENT_TYPE];
        }
        if (isset($model[Api::FIELD_QUICKPAY_FORM])) {
            $fields['quickpay-form'] = $model[Api::FIELD_QUICKPAY_FORM];
        }

        $model->replace(array(
            Api::FIELD_STATUS => Api::STATUS_PENDING,
        ));

        $fields = array_merge($this->api->getApiFields(), $fields);

        throw new HttpPostRedirect($this->api->getApiEndpoint(), $fields);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
