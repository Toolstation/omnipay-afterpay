<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 22/03/2016
 * Time: 09:06
 */

namespace Omnipay\AfterPay\Message;

class Void extends Management
{
    public function getData()
    {
        $this->validate('transactionId', 'country');
//        @todo complete code
    }
}
