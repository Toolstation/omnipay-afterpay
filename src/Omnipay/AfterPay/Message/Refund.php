<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 22/03/2016
 * Time: 09:06
 */

namespace Omnipay\AfterPay\Message;

class Refund extends Management
{
    public function setCreditInvoiceNumber($value)
    {
        return $this->setParameter('creditInvoiceNumber', $value);
    }

    public function getCreditInvoiceNumber()
    {
        return $this->getParameter('creditInvoiceNumber');
    }

    public function getData()
    {
        $this->validate('transactionId', 'invoiceNumber', 'country');
//        @todo complete code
    }
}
