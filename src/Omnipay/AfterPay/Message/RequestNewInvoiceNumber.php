<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 22/03/2016
 * Time: 09:07
 */

namespace Omnipay\AfterPay\Message;

class RequestNewInvoiceNumber extends Management
{
    public function getData()
    {
        $this->validate('transactionId', 'country');
//        @todo complete code
    }
}
