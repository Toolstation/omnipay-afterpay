<?php

namespace Omnipay\AfterPay\Message;

use Omnipay\AfterPay\AfterPayItem;
use Omnipay\Common\ItemBag;
use Omnipay\AfterPay\AfterPayItemBag;

/**
* AfterPay Purchase Request
*/
class PurchaseRequest extends AbstractRequest
{
    private $billToAddress;

    private $shipToAddress;

    private $possibleOrderTypes = ['B2C', 'B2B'];

    public function setOrderType($value)
    {
        return $this->setParameter('orderType', $value);
    }

    public function getOrderType()
    {
        return $this->getParameter('orderType');
    }

    public function setVatCode($value)
    {
        return $this->setParameter('vatCode', $value);
    }

    public function getVatCode()
    {
        return $this->getParameter('vatCode');
    }

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

    public function getData()
    {
        $this->validate('amount', 'currency', 'transactionId', 'clientIp', 'orderType', 'country');

        $data['authorization'] = $this->getAuthorisation();

        $data['orderType'] = $this->setDataOrderType();

        $data['order'] = new \stdClass();
        $data['order']->ordernumber = $this->getTransactionId();
        $data['order']->ipAddress = $this->getClientIp();
        $data['order']->shopper = new \stdClass();
        $data['order']->shopper->profileCreated = (new \DateTime())->format("Y-m-d\TH:i:s");
        $data['order']->bankaccountNumber = $this->getCard()->getNumber();
        $data['order']->currency = $this->getCurrency();
        $data['order']->orderlines = $this->getItemData();
        $data['order']->totalOrderAmount = $this->getAmount();

        $addresses = $this->setAddresses();

        $data['order']->{$this->billToAddress} = $addresses[$this->billToAddress];

        $data['order']->{$this->shipToAddress} = $addresses[$this->shipToAddress];

        $data['endPoint'] = $this->getEndpoint();

        return $data;
    }

    /**
     * Sets the order type and relevant properties
     *
     * @return array
     * @throws \Exception
     */
    public function setDataOrderType()
    {
        if (!in_array($this->getOrderType(), $this->possibleOrderTypes)) {
            throw new \Exception('AfterPay: invalid order type.');
        }

        $data = [];

        switch ($this->getOrderType()) {
            case 'B2C':
                $data['orderType'] = 'B2C';
                $data['orderTypeName'] = 'validateAndCheckB2COrder';
                $data['orderTypeFunction'] = 'b2corder';
                $this->billToAddress = 'b2cbilltoAddress';
                $this->shipToAddress = 'b2cshiptoAddress';
                break;
            case 'B2B':
                $data['orderType'] = 'B2B';
                $data['orderTypeName'] = 'validateAndCheckB2BOrder';
                $data['orderTypeFunction'] = 'b2border';
                $this->billToAddress = 'b2bbilltoAddress';
                $this->shipToAddress = 'b2bshiptoAddress';
                break;
            default:
                // all possible cases should be dealt with, and code shouldn't get here
                // an exception is thrown at the beginning of this function if order type
                // is unknown
                break;
        }

        return $data;
    }

    /**
     * sets the addresses
     *
     * @return array
     */
    public function setAddresses()
    {
        $order = [];
        foreach ([$this->billToAddress, $this->shipToAddress] as $addressId) {
            $order[$addressId] = new \stdClass();
            $order[$addressId]->city = $this->getCard()->getCity();
            $order[$addressId]->housenumber = $this->getCard()->getAddress1();
            $order[$addressId]->isoCountryCode = $this->getCard()->getCountry();
            $order[$addressId]->postalcode = $this->getCard()->getPostcode();
            $order[$addressId]->streetname = $this->getCard()->getAddress2();
            $order[$addressId]->referencePerson = new \stdClass();
            $order[$addressId]->referencePerson->dateofbirth = $this->getCard()->getBirthday("Y-m-d") . 'T00:00:00';
            $order[$addressId]->referencePerson->emailaddress = $this->getCard()->getEmail();
            $order[$addressId]->referencePerson->gender = $this->getCard()->getGender();
            $order[$addressId]->referencePerson->initials = $this->getCard()->getFirstName();
            $order[$addressId]->referencePerson->isoLanguage = $this->getCard()->getCountry();
            $order[$addressId]->referencePerson->lastname = $this->getCard()->getLastName();
            $order[$addressId]->referencePerson->phonenumber1 = $this->getCard()->getPhone();
        }
        return $order;
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
}
