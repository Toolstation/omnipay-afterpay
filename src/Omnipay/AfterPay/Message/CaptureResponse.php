<?php
/**
 * Response for a payment capture
 */

namespace Omnipay\AfterPay\Message;

class CaptureResponse extends Response
{
    public function getAmountAuthorised()
    {
        if ($this->isSuccessful()) {
            return $this->data->return->totalInvoicedAmount;
        }

        return 0;
    }

    public function getAmountCaptured()
    {
        if ($this->isSuccessful()) {
            return $this->data->return->totalReservedAmount;
        }

        return 0;
    }
}
