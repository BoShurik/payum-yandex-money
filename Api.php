<?php
/**
 * Created by PhpStorm.
 * User: boshurik
 * Date: 15.02.16
 * Time: 15:03
 */

namespace BoShurik\Payum\YandexMoney;

use Payum\Core\Bridge\Spl\ArrayObject;

/**
 * Api
 */
class Api
{
    const FIELD_STATUS = 'status';
    const FIELD_PAYMENT_TYPE = 'payment_type';
    const FIELD_QUICKPAY_FORM = 'quickpay_form';

    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_CAPTURED = 'captured';

    /**
     * Payment from yandex money
     */
    const PAYMENT_PC = 'PC';

    /**
     * Payment from card
     */
    const PAYMENT_AC = 'AC';

    /**
     * Payment from mobile
     */
    const PAYMENT_MC = 'MC';

    /**
     * Shop form
     */
    const QUICKPAY_FORM_SHOP = 'shop';

    /**
     * Donate form
     */
    const QUICKPAY_FORM_DONATE = 'donate';

    /**
     * Button
     */
    const QUICKPAY_FORM_SMALL = 'small';

    /**
     * @var array
     */
    protected $options = array(
        'account' => null,
        'secret' => null,
        'payment_type' => self::PAYMENT_AC,
        'quickpay_form' => self::QUICKPAY_FORM_SHOP,
    );

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $options = ArrayObject::ensureArrayObject($options);
        $options->defaults($this->options);
        $options->validateNotEmpty(array(
            'account',
        ));
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getApiEndpoint()
    {
        return 'https://money.yandex.ru/quickpay/confirm.xml';
    }

    /**
     * @return array
     */
    public function getApiFields()
    {
        return array(
            'receiver' => $this->options['account'],
            'quickpay-form' => $this->options['quickpay_form'],
            'paymentType' => $this->options['payment_type'],
        );
    }

    /**
     * @param array $values
     * @return bool
     */
    public function verifyRequest(array $values)
    {
        $values = ArrayObject::ensureArrayObject($values);
        $values->defaults(array(
            'notification_type' => null,
            'operation_id' => null,
            'amount' => null,
            'currency' => null,
            'sender' => null,
            'codepro' => null,
            'label' => null,
        ));

        $parameters = array();
        $parameters[] = $values['notification_type'];
        $parameters[] = $values['operation_id'];
        $parameters[] = $values['amount'];
        $parameters[] = $values['currency'];
        $parameters[] = $values['datetime'];
        $parameters[] = $values['sender'];
        $parameters[] = $values['codepro'];
        $parameters[] = $this->options['secret'];
        $parameters[] = $values['label'];

        $string = implode('&', $parameters);
        $hash = sha1($string);

        return $hash === $values['sha1_hash'];
    }
}