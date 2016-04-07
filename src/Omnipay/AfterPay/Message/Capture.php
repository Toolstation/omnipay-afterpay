<?php
/**
 * Capture an order. If items are provided, the capture will be partial, otherwise a full capture is done.
 */

namespace Omnipay\AfterPay\Message;

use Omnipay\AfterPay\AfterPayItemBag;
use Omnipay\Common\ItemBag;

/**
 * Class Capture
 *
 * @package Omnipay\AfterPay\Message
 */
class Capture extends Management
{
    /**
     * Set the items in this order
     *
     * @param ItemBag|array $items An array of items in this order
     * @return ItemBag
     */
    public function setItems($items)
    {
        if ($items && !$items instanceof ItemBag) {
            $items = new AfterPayItemBag($items);
        }

        return $this->setParameter('items', $items);
    }

    /**
     * Set the Invoice Number.
     * @param string $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setInvoiceNumber($value)
    {
        return $this->setParameter('invoiceNumber', $value);
    }

    /**
     * Get the Invoice Number.
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
     * Set the Vat Code
     * @param int $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setVatCode($value)
    {
        return $this->setParameter('vatCode', $value);
    }

    /**
     * Get the Vat Code.
     * @return int
     */
    public function getVatCode()
    {
        return $this->getParameter('vatCode');
    }

    /**
     * Sets an indication that this is a partial capture. If this is not set it is a full capture.
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setPartial($value)
    {
        return $this->setParameter('partial', $value);
    }

    /**
     * Returns the partial setting. If not set (null) this is a full capture.
     * @return mixed
     */
    public function getPartial()
    {
        return $this->getParameter('partial');
    }

    /**
     * Get the data for the refund request.
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionId', 'invoiceNumber', 'country', 'vatCode');

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
     * Get the items on the order to be refunded.
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
     * Set the values to be used in the SOAP call to Afterpay dependant on this being a full or partial refund.
     * @return mixed
     */
    protected function setDataOrderType()
    {
        $data['orderTypeName'] = 'captureFull';

        $partial = $this->getPartial();

        if ($partial !== null) {
            $data['orderTypeName'] = 'capturePartial';
        }

        $data['orderTypeFunction'] = 'captureobject';

        return $data;
    }

    /**
     * Create a respopnse.
     * @param $request
     * @param $response
     *
     * @return CaptureResponse
     */
    public function createResponse($request, $response)
    {
        return new CaptureResponse($request, $response);
    }
}
