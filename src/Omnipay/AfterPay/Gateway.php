<?php

namespace Omnipay\AfterPay;

use Omnipay\Common\AbstractGateway;

/**
 * AfterPay Gateway
 *
 * @link https://www.afterpay.nl/en/business-partners-afterpay/afterpay-integration
 */
class Gateway extends AbstractGateway
{
    /**
     * Get the gateway name.
     * @return string
     */
    public function getName()
    {
        return 'AfterPay';
    }

    /**
     * Get an array of the default parameters for an AfterPay request
     * @return array
     */
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

    /**
     * Get the Merchant ID
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * Set the Merchant ID
     * @param string $value
     *
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * Get the AfterPay password.
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set the AfterPay password
     * @param string $value
     *
     * @return $this
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Get the protfolio ID.
     * @return string
     */
    public function getPortfolioId()
    {
        return $this->getParameter('portfolioId');
    }

    /**
     * Set the portfolio ID
     * @param string $value
     *
     * @return $this
     */
    public function setPortfolioId($value)
    {
        return $this->setParameter('portfolioId', $value);
    }

    /**
     * Set the country code ('NL', 'BE' or 'DE')
     * @param string $value
     *
     * @return $this
     */
    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    /**
     * Get teh country code.
     * @return string
     */
    public function getCountry()
    {
        return $this->getParameter('country');
    }

    /**
     * Create a purchase request
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = array())
    {
        /** \Omnipay\AfterPay\Message\PurchaseRequest */
        return $this->createRequest('\Omnipay\AfterPay\Message\PurchaseRequest', $parameters);
    }

    /**
     * Create a Capture request
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function capture(array $parameters = array())
    {
        /** \Omnipay\AfterPay\Message\Capture */
        return $this->createRequest('\Omnipay\AfterPay\Message\Capture', $parameters);
    }

    /**
     * Create a Cancel request
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function cancel(array $parameters = array())
    {
        /** \Omnipay\AfterPay\Message\Cancel */
        return $this->createRequest('\Omnipay\AfterPay\Message\Cancel', $parameters);
    }

    /**
     * Create a Refund request
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function refund(array $parameters = array())
    {
        /** \Omnipay\AfterPay\Message\Refund */
        return $this->createRequest('\Omnipay\AfterPay\Message\Refund', $parameters);
    }

    /**
     * Create a Void request
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function void(array $parameters = array())
    {
        /** \Omnipay\AfterPay\Message\Void */
        return $this->createRequest('\Omnipay\AfterPay\Message\Void', $parameters);
    }
}
