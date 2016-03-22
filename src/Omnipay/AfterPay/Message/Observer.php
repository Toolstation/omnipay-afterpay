<?php
/**
 * The interface that must be followed by observers.
 */
namespace Omnipay\AfterPay\Message;

/**
 * Interface Observer
 * @package Omnipay\AfterPay\Message
 */
interface Observer
{

    /**
     * Method to be implemented by observers.
     *
     * @param AbstractRequest $observable The observable instance.
     * @param array           $data       Extra data to be returned from the observable.
     *
     * @return mixed
     */
    public function update(AbstractRequest $observable, array $data);
}
