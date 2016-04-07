<?php
/**
 * A refund request
 */

namespace Omnipay\AfterPay\Message;

use Omnipay\AfterPay\AfterPayItem;

/**
 * Class Refund
 *
 * @package Omnipay\AfterPay\Message
 */
class Refund extends Management
{
    /**
     * Set the Invoice Number
     * @param string $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setInvoiceNumber($value)
    {
        return $this->setParameter('invoiceNumber', $value);
    }

    /**
     * Get the invoice number.
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->getParameter('invoiceNumber');
    }

    /**
     * Set the Capture Delay Days.
     * @param int $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setCaptureDelayDays($value)
    {
        return $this->setParameter('capturedelaydays', $value);
    }

    /**
     * Get the Capture Delay Days. If not set this will return 0.
     * @return int
     */
    public function getCaptureDelayDays()
    {
        $captureDelayDays = $this->getParameter('capturedelaydays');
        if ($captureDelayDays === null) {
            return 0;
        }

        return $captureDelayDays;
    }

    /**
     * Set the shipping company
     * @param string $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setShippingCompany($value)
    {
        return $this->setParameter('shippingcompany', $value);
    }

    /**
     * Get the Shipping Company. If not set, this will return an empty string.
     * @return string
     */
    public function getShippingCompany()
    {
        $shippingCompany = $this->getParameter('shippingcompany');

        if ($shippingCompany === null) {
            return '';
        }

        return $shippingCompany;
    }

    /**
     * Set the tracking number.
     * @param string $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setTrackingNumber($value)
    {
        return $this->setParameter('trackingnumber', $value);
    }

    /**
     * Get the tracking number.
     * @return string|null
     */
    public function getTrackingNumber()
    {
        return $this->getParameter('trackingnumber');
    }

    /**
     * Sets an indication that this is a partial refund. If this is not set it is a full refund.
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setPartial($value)
    {
        return $this->setParameter('partial', $value);
    }

    /**
     * Returns the partial setting. If not set (null) this is a full refund.
     * @return mixed
     */
    public function getPartial()
    {
        return $this->getParameter('partial');
    }

    /**
     * Get the data for the refund
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionId', 'invoiceNumber', 'country');

        $data = $this->getBaseData();

        $itemData = $this->getItemData();

        $data['orderType'] = $this->setDataOrderType();

        $data['order'] = new \stdClass();
        if (count($itemData) > 0) {
            $data['order']->invoicelines = $itemData;
        }
        $data['order']->invoicenumber = $this->getInvoiceNumber();
        $data['order']->transactionReference = $this->getTransactionReference();
        $data['order']->transactionkey = new \stdClass();
        $data['order']->transactionkey->ordernumber = $this->getTransactionId();
        $data['order']->capturedelaydays = $this->getCaptureDelayDays();
        $data['order']->shippingCompany = $this->getShippingCompany();
        $trackingNumber = $this->getTrackingNumber();
        if ($trackingNumber !== null) {
            $data['order']->trackingNumber = $trackingNumber;
        }

        return $data;
    }

    /**
     * Get the items on the order to be captured.
     * @return array
     */
    protected function getItemData()
    {
        $data = array();
        $items = $this->getItems();
        if ($items) {
            /** @var AfterPayItem $item */
            foreach ($items as $item) {
                $order = new \stdClass();
                $order->articleId = $item->getCode();
                $order->articleDescription = $item->getName();
                $order->quantity = $item->getQuantity();
                $order->unitprice = $this->formatCurrency($item->getPrice());
                $order->vatcategory = $this->getVatCode();
                $data[] = $order;
            }
        }

        return $data;
    }

    /**
     * Set the values to be used in the SOAP call to Afterpay dependant on this being a full or partial capture.
     * @return mixed
     */
    protected function setDataOrderType()
    {
        $data['orderTypeName'] = 'refundFullInvoice';

        $partial = $this->getPartial();

        if ($partial !== null) {
            $data['orderTypeName'] = 'refundInvoice';
        }

        $data['orderTypeFunction'] = 'refundobject';

        return $data;
    }
}
