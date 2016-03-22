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

    public function setInvoiceNumber($value)
    {
        return $this->setParameter('invoicenumber', $value);
    }

    public function getInvoiceNumber()
    {
        return $this->getParameter('invoicenumber');
    }

    public function setCaptureDelayDays($value)
    {
        return $this->setParameter('capturedelaydays', $value);
    }

    public function getCaptureDelayDays()
    {
        return $this->getParameter('capturedelaydays');
    }

    public function setShippingCompany($value)
    {
        return $this->setParameter('shippingcompany', $value);
    }

    public function getShippingCompany()
    {
        return $this->getParameter('shippingcompany');
    }

    public function setTrackingNumber($value)
    {
        return $this->setParameter('trackingnumber', $value);
    }

    public function getTrackingNumber()
    {
        return $this->getParameter('trackingnumber');
    }

    public function getData()
    {
        $this->validate('transactionId', 'transactionRef', 'invoiceNumber', 'country');

        $data = $this->getBaseData();

        $itemData = $this->getItemData();

        $data['orderType'] = $this->setDataOrderType($itemData);

        $data['captureObject'] = new \stdClass();
        if (count($itemData) > 0) {
            $data['captureObject']->invoicelines = $itemData;
        }
        $data['captureObject']->invoiceNumber = $this->getInvoiceNumber();
        $data['captureObject']->transactionReference = $this->getTransactionReference();
        $data['captureObject']->transactionkey = $this->getTransactionId();
        $data['captureObject']->capturedelaydays = $this->getCaptureDelayDays();
        $data['captureObject']->shippingCompany = $this->getShippingCompany();
        $data['captureObject']->trackingNumber = $this->getTrackingNumber();
    }

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

    protected function setDataOrderType($itemData)
    {
        $data['orderTypeName'] = 'captureFull';

        if (count($itemData) > 0) {
            $data['orderTypeName'] = 'capturePartial';
        }
        $data['orderTypeFunction'] = 'captureObject';
    }
}
