<?php
/**
 * AfterPay Item bag
 */

namespace Omnipay\AfterPay;

use Omnipay\AfterPay\AfterPayItem;
use Omnipay\Common\ItemBag;
use Omnipay\Common\ItemInterface;

/**
 * Class AfterPayItemBag
 *
 * @package Omnipay\AfterPay
 */
class AfterPayItemBag extends ItemBag
{
    /**
     * Add an item to the bag
     *
     * @see Item
     *
     * @param ItemInterface|array $item An existing item, or associative array of item parameters
     */
    public function add($item)
    {
        if ($item instanceof ItemInterface) {
            $this->items[] = $item;
        } else {
            $this->items[] = new AfterPayItem($item);
        }
    }
}
