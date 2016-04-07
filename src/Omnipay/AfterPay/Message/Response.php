<?php
/**
 * A response from AfterPay.
 */
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
    /**
     * Response constructor.
     *
     * @param RequestInterface $request
     * @param mixed            $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        $this->data = $data;
    }

    /**
     * Returns true if the response is successful.
     * @return bool
     */
    public function isSuccessful()
    {
        if (!isset($this->data->return->statusCode)) {
            return false;
        }
        return "A" === (string) $this->data->return->statusCode;
    }

    /**
     * Get the transaction reference.
     * @return string
     */
    public function getTransactionReference()
    {
        if (isset($this->data->return->transactionId)) {
            return (string) $this->data->return->transactionId;
        }

        return '';
    }

    /**
     * Get the AfterPay order reference.
     * @return string
     */
    public function getAfterPayOrderReference()
    {
        if (isset($this->data->return->afterPayOrderReference)) {
            return $this->data->return->afterPayOrderReference;
        }

        return '';
    }

    /**
     * Get the message (generally relevant if the response is not successful)
     * @return string
     */
    public function getMessage()
    {
        $msg = '';

        if (isset($this->data->return->rejectCode)) {
            $msg .= $this->data->return->rejectDescription;
        }

        if (isset($this->data->return->failures->failures->failure)) {
            $msg .= $this->data->return->failures->failures->failure;
        }

        if (isset($this->data->return->failures->failures->fieldName)) {
            $msg .= ' (fieldname : '.$this->data->return->failures->failures->fieldName.')';
        }

        return $msg;
    }
}
