<?php
/**
 * Cancel an order. If items are provided, the cancellation will be partial, otherwise a full cancellation is done.
 */

namespace Omnipay\AfterPay\Message;

use Omnipay\AfterPay\AfterPayItemBag;
use Omnipay\Common\ItemBag;

class Cancel extends Management
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

    public function getData()
    {
        $this->validate('transactionId', 'country');
//        @todo complete code
    }
}
