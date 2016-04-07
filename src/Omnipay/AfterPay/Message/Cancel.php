<?php
/**
 * Cancel an order.
 */

namespace Omnipay\AfterPay\Message;

/**
 * Class Cancel
 *
 * @package Omnipay\AfterPay\Message
 */
class Cancel extends Management
{
    /**
     * Get the data to send for the cancellation.
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
     * Set the values to be used in the SOAP call to AfterPay
     * @return mixed
     */
    protected function setDataOrderType()
    {
        $data['orderTypeName'] = 'cancelOrder';

        $data['orderTypeFunction'] = 'ordermanagementobject';

        return $data;
    }
}
