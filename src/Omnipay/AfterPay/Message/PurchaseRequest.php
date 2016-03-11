<?php

namespace Omnipay\AfterPay\Message;

use DOMDocument;
use Omnipay\AfterPay\AfterPayItem;
use Omnipay\Common\ItemBag;
use Omnipay\PayPal\AfterPayItemBag;
use SimpleXMLElement;
use Omnipay\Common\Message\AbstractRequest;

/**
* AfterPay Purchase Request
*/
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://www.acceptgirodienst.nl/soapservices/rm/AfterPaycheck?wsdl';
    protected $testEndpoint = 'https://test.acceptgirodienst.nl/soapservices/rm/AfterPaycheck';

    protected $namespace = 'http://www.afterpay.nl/ad3/';

    public function getClientIp()
    {
        $ip = parent::getClientIp();

        if ($ip == '::1') {
            $ip = '127.0.0.1';
        }

        return $ip;
    }
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getPortfolioId()
    {
        return $this->getParameter('portfolioId');
    }

    public function setPortfolioId($value)
    {
        return $this->setParameter('portfolioId', $value);
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
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
        $this->validate('amount', 'currency', 'transactionId');

        $data = new SimpleXMLElement('<validateAndCheckB2COrder/>', LIBXML_NOERROR, false, 'ns1', true);


        // authorization

        $data->authorization->merchantId = $this->getMerchantId();
        $data->authorization->password = $this->getPassword();
        $data->authorization->portfolioId = $this->getPortfolioId();


        // b2corder

        $data->b2corder->bankaccountNumber = $this->getCard()->getNumber();
        $data->b2corder->currency = $this->getCurrency();
        $data->b2corder->ipAddress = $this->getClientIp();


        // b2corder : orderline

        $data->b2corder->orderlines = $this->getItemData();


        $data->b2corder->ordernumber = $this->getTransactionId();


        // b2corder : shopper

        $data->b2corder->shopper->profileCreated = '2012-12-12T00:00:00';


        $data->b2corder->totalOrderAmount = $this->getAmountInteger();


        // b2corder : b2cbilltoAddress

        $data->b2corder->b2cbilltoAddress->city = $this->getCard()->getCity();
        $data->b2corder->b2cbilltoAddress->housenumber = $this->getCard()->getAddress1();
        $data->b2corder->b2cbilltoAddress->isoCountryCode = $this->getCard()->getCountry();
        $data->b2corder->b2cbilltoAddress->postalcode = $this->getCard()->getPostcode();
        $data->b2corder->b2cbilltoAddress->streetname = $this->getCard()->getAddress2();


        // birthday format : 1985-01-24T06:00:00

        $data->b2corder->b2cbilltoAddress->referencePerson->dateofbirth = $this->getCard()->getBirthday('c');

        $data->b2corder->b2cbilltoAddress->referencePerson->emailaddress = $this->getCard()->getEmail();

        // gender : M or F

        $data->b2corder->b2cbilltoAddress->referencePerson->gender = $this->getCard()->getGender();

        $data->b2corder->b2cbilltoAddress->referencePerson->initials = $this->getCard()->getFirstName();
        $data->b2corder->b2cbilltoAddress->referencePerson->isoLanguage = $this->getLanguage();
        $data->b2corder->b2cbilltoAddress->referencePerson->lastname = $this->getCard()->getLastName();
        $data->b2corder->b2cbilltoAddress->referencePerson->phonenumber1 = $this->getCard()->getPhone();


        // b2corder : b2cshiptoAddress

        $data->b2corder->b2cshiptoAddress->city = $this->getCard()->getCity();
        $data->b2corder->b2cshiptoAddress->housenumber = $this->getCard()->getAddress1();
        $data->b2corder->b2cshiptoAddress->isoCountryCode = $this->getCard()->getCountry();
        $data->b2corder->b2cshiptoAddress->postalcode = $this->getCard()->getPostcode();
        $data->b2corder->b2cshiptoAddress->streetname = $this->getCard()->getAddress2();


        // birthday format : 1985-01-24T06:00:00

        $data->b2corder->b2cshiptoAddress->referencePerson->dateofbirth = $this->getCard()->getBirthday('c');

        $data->b2corder->b2cshiptoAddress->referencePerson->emailaddress = $this->getCard()->getEmail();


        // gender : M or F

        $data->b2corder->b2cshiptoAddress->referencePerson->gender = $this->getCard()->getGender();

        $data->b2corder->b2cshiptoAddress->referencePerson->initials = $this->getCard()->getFirstName();
        $data->b2corder->b2cshiptoAddress->referencePerson->isoLanguage = $this->getLanguage();
        $data->b2corder->b2cshiptoAddress->referencePerson->lastname = $this->getCard()->getLastName();
        $data->b2corder->b2cshiptoAddress->referencePerson->phonenumber1 = $this->getCard()->getPhone();

        return $data;
    }

    public function sendData($data)
    {
        $document = new DOMDocument('1.0', 'UTF-8');


        $envelope = $document->appendChild(
            $document->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'SOAP-ENV:Envelope')
        );

        $envelope->setAttribute('xmlns:ns1', 'http://www.afterpay.nl/ad3/');

        $body = $envelope->appendChild($document->createElement('SOAP-ENV:Body'));

        $body->appendChild($document->importNode(dom_import_simplexml($data), true));


        // post

        $xml = $document->saveXML();

        $xml = str_replace('<validateAndCheckB2COrder>', '<ns1:validateAndCheckB2COrder>', $xml);
        $xml = str_replace('</validateAndCheckB2COrder>', '</ns1:validateAndCheckB2COrder>', $xml);


        $xml = trim($xml);

        $headers = array(
            'Content-Type' => 'text/xml; charset=utf-8',
            'SOAPAction' => 'validateAndCheckB2COrder');

        $httpRequest = $this->httpClient->post($this->getEndpoint(), $headers, $xml);

        $httpResponse = $httpRequest->send();

        return $this->response = new Response($this, $httpResponse->getBody());
    }

    protected function getItemData()
    {
        $data = array();
        $items = $this->getItems();
        if ($items) {
            /** @var AfterPayItem $item */
            foreach ($items as $item) {
                $order = new stdClass();
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

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
