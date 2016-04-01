<?php
/**
 * Void an order
 */

namespace Omnipay\AfterPay\Message;

/**
 * Class Void
 *
 * @package Omnipay\AfterPay\Message
 */
class Void extends Management
{
    public function getData()
    {
        $this->validate('transactionId', 'country');

        $data = $this->getBaseData();

        $data['orderType'] = $this->setDataOrderType();

        $data['order'] = new \stdClass();

        $data['order']->transactionkey = new \stdClass();
        $data['order']->transactionkey->ordernumber = $this->getTransactionId();

        return $data;
    }

    protected function setDataOrderType()
    {
        $data['orderTypeName'] = 'doVoid';

        $data['orderTypeFunction'] = 'ordermanagementobject';

        return $data;
    }
}
