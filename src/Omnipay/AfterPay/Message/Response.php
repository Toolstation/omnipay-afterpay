<?php

namespace Omnipay\AfterPay\Message;

use DOMDocument;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * AfterPay Response
 */
class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        $this->data = $data;
    }

    public function isSuccessful()
    {
        if (!isset($this->data->return->statusCode)) {
            return false;
        }
        return "A" === (string) $this->data->return->statusCode;
    }

    public function getTransactionReference()
    {
        if (isset($this->data->return->transactionId)) {
            return (string) $this->data->return->transactionId;
        }

        return '';
    }

    public function getAfterPayOrderReference()
    {
        if (isset($this->data->return->afterPayOrderReference)) {
            return $this->data->return->afterPayOrderReference;
        }

        return '';
    }

    public function getMessage()
    {
        $msg = '';

        if (isset($this->data->return->rejectCode)) {
            $msg .= $this->data->return->rejectDescription;
        }

        if (isset($this->data->return->failures->failure)) {
            $msg .= $this->data->return->failures->failure;
        }

        if (isset($this->data->return->failures->fieldname)) {
            $msg .= ' (fieldname : '.$this->data->return->failures->fieldname.')';
        }

        return $msg;
    }
}
