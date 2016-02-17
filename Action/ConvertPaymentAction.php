<?php
/**
 * Created by PhpStorm.
 * User: boshurik
 * Date: 15.02.16
 * Time: 14:12
 */

namespace BoShurik\Payum\YandexMoney\Action;

use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use BoShurik\Payum\YandexMoney\Api;

/**
 * ConvertPaymentAction
 */
class ConvertPaymentAction extends GatewayAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        $details['amount'] = $payment->getTotalAmount();
        $details['currency'] = $payment->getCurrencyCode();
        $details['number'] = $payment->getNumber();
        $details['description'] = $payment->getDescription();
        $details['user_id'] = $payment->getClientId();
        $details['user_email'] = $payment->getClientEmail();

        $details->defaults(array(
            Api::FIELD_STATUS => Api::STATUS_NEW,
        ));

        $request->setResult((array) $details);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() == 'array'
        ;
    }
}
