<?php

namespace Omnipay\AfterPay;

use Omnipay\Common\AbstractGateway;

/**
 * AfterPay Gateway
 *
 * @link http://
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'AfterPay';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'password' => '',
            'portfolioId' => '',
            'country' => '',
            'testMode' => false,
        );
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

    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    public function getCountry()
    {
        return $this->getParameter('country');
    }

    public function purchase(array $parameters = array())
    {
        /** \Omnipay\AfterPay\Message\PurchaseRequest */
        return $this->createRequest('\Omnipay\AfterPay\Message\PurchaseRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        /** \Omnipay\AfterPay\Message\PurchaseRequest */
        return $this->createRequest('\Omnipay\AfterPay\Message\Capture', $parameters);
    }
}
