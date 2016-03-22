<?php
/**
 * Abstract class to be extended by all request classes
 */

namespace Omnipay\AfterPay\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Observers. Used to report requests and responses to.
     *
     * @var Observer[]
     */
    private $observers = [];

    protected $liveEndpoint = [
        'BE' => 'https://www.afterpay.be/soapservices/rm/AfterPaycheck?wsdl',
        'DE' => '',
        'NL' => 'https://www.acceptgirodienst.nl/soapservices/rm/AfterPaycheck?wsdl',
    ];

    protected $testEndpoint = [
        'BE' => 'https://test.afterpay.be/soapservices/rm/AfterPaycheck?wsdl',
        'DE' => 'https://sandboxapi.horizonafs.com/eCommerceServices/AfterPay/RiskManagement/v2/'
            . 'RiskManagementServices.svc?singleWsdl',
        'NL' => 'https://test.acceptgirodienst.nl/soapservices/rm/AfterPaycheck',
    ];

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

    public function getAuthorisation()
    {
        $authorisation = new \stdClass();
        $authorisation->merchantId = $this->getMerchantId();
        $authorisation->password = $this->getPassword();
        $authorisation->portfolioId = $this->getPortfolioId();

        return $authorisation;
    }

    public function sendData($data)
    {
        $soapClient = new \SoapClient(
            $this->getEndpoint(),
            array(
                'location' => $this->getEndpoint(),
                'trace' => true,
                'cache_wsdl' => WSDL_CACHE_NONE
            )
        );

        if (is_object($soapClient)) {
            try {
                $response = $soapClient->__soapCall(
                    $data['orderType']['orderTypeName'],
                    array(
                        $data['orderType']['orderTypeName'] => array(
                            'authorization' => $data['authorization'],
                            $data['orderType']['orderTypeFunction'] => $data['order']
                        )
                    )
                );
            } catch (\Exception $e) {
                $response = $e;
            }
            $request = $soapClient->__getLastRequest();
            $this->notify(
                [
                    'request' => $request,
                    'response' => $response,
                ]
            );
            return new Response($this, $response);
        } else {
            throw new \Exception('AfterPay: couldn\'t make the request.');
        }
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ?
            $this->testEndpoint[$this->getCountry()] :
            $this->liveEndpoint[$this->getCountry()];
    }

    /**
     * Attach an observer.
     *
     * @param Observer $observer
     */
    public function attach(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    /**
     * Detach an attached observer.
     *
     * @param Observer $observer
     */
    public function detach(Observer $observer)
    {
        $this->observers = array_filter(
            $this->observers,
            function ($a) use ($observer) {
                return (!($a === $observer));
            }
        );
    }

    /**
     * Notify all observers.
     *
     * @param $data
     */
    public function notify($data)
    {
        foreach ($this->observers as $observer) {
            $observer->update($this, $data);
        }
    }
}
