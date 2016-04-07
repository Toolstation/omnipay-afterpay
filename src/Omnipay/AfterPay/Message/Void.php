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
    /**
     * Get the data to void the payment.
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
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

    /**
     * Set the values to be used in the SOAP request to AfterPay
     * @return array
     */
    protected function setDataOrderType()
    {
        $data['orderTypeName'] = 'doVoid';

        $data['orderTypeFunction'] = 'ordermanagementobject';

        return $data;
    }
}
