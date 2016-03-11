<?php
/**
 * AfterPay Item
 */

namespace Omnipay\AfterPay;

use Omnipay\Common\Item;

/**
 * Class AfterPayItem
 *
 * @package Omnipay\AfterPay
 */
class AfterPayItem extends Item
{
    /**
     * {@inheritDoc}
     */
    public function getCode()
    {
        return $this->getParameter('code');
    }

    /**
     * Set the item code
     */
    public function setCode($value)
    {
        return $this->setParameter('code', $value);
    }
}
