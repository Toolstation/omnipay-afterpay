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
        $data['orderTypeName'] = 'cancelOrder';

        $data['orderTypeFunction'] = 'ordermanagementobject';

        return $data;
    }
}
