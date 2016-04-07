<?php
/**
 * Response for a payment capture
 */

namespace Omnipay\AfterPay\Message;

/**
 * Class CaptureResponse
 *
 * @package Omnipay\AfterPay\Message
 */
class CaptureResponse extends Response
{
    /**
     * Get the amount authorised in the Capture
     * @return int
     */
    public function getAmountAuthorised()
    {
        if ($this->isSuccessful()) {
            return $this->data->return->totalInvoicedAmount;
        }

        return 0;
    }

    /**
     * Get the amount captured.
     * @return int
     */
    public function getAmountCaptured()
    {
        if ($this->isSuccessful()) {
            return $this->data->return->totalReservedAmount;
        }

        return 0;
    }
}
