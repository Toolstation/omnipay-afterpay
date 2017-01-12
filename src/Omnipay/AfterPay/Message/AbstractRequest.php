<?php
/**
 * Abstract class to be extended by all request classes
 */

namespace Omnipay\AfterPay\Message;

/**
 * Class AbstractRequest
 *
 * @package Omnipay\AfterPay\Message
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Live endpoints.
     * @var array
     */
    protected $liveEndpoint = [
        'BE' => 'https://www.afterpay.be/soapservices/rm/AfterPaycheck?wsdl',
        'DE' => '',
        'NL' => 'https://www.acceptgirodienst.nl/soapservices/rm/AfterPaycheck?wsdl',
    ];

    /**
     * Testing endpoints.
     * @var array
     */
    protected $testEndpoint = [
        'BE' => 'https://test.afterpay.be/soapservices/rm/AfterPaycheck?wsdl',
        'DE' => 'https://sandboxapi.horizonafs.com/eCommerceServices/AfterPay/RiskManagement/v2/'
            . 'RiskManagementServices.svc?singleWsdl',
        'NL' => 'https://test.acceptgirodienst.nl/soapservices/rm/AfterPaycheck',
    ];

    /**
     * Observers. Used to report requests and responses to.
     *
     * @var array
     */
    private $observers = [];

    /**
     * Get the Merchant Id.
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * Set the Merchant Id.
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * Get the password
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set the password
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Get the portfolio ID
     * @return string
     */
    public function getPortfolioId()
    {
        return $this->getParameter('portfolioId');
    }

    /**
     * Set the portfolio ID
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setPortfolioId($value)
    {
        return $this->setParameter('portfolioId', $value);
    }

    /**
     * Set the country.
     * @param $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    /**
     * Get the country.
     * @return string
     */
    public function getCountry()
    {
        return $this->getParameter('country');
    }

    public function setSoapClient($value)
    {
        return $this->setParameter('soapClient', $value);
    }

    public function getSoapClient()
    {
        $soapClient = $this->getParameter('soapClient');

        if ($soapClient === null) {
            $soapClient = new \SoapClient(
                $this->getEndpoint(),
                array(
                    'location' => $this->getEndpoint(),
                    'trace' => true,
                    'cache_wsdl' => WSDL_CACHE_NONE
                )
            );
        }

        return $soapClient;
    }
    /**
     * Get the authorisation class to send to AfterPay
     * @return \stdClass
     */
    public function getAuthorisation()
    {
        $authorisation = new \stdClass();
        $authorisation->merchantId = $this->getMerchantId();
        $authorisation->password = $this->getPassword();
        $authorisation->portfolioId = $this->getPortfolioId();

        return $authorisation;
    }

    /**
     * Send the data to AfterPay using SOAP
     * @param mixed $data
     *
     * @return Response
     * @throws \Exception
     */
    public function sendData($data)
    {
        $soapClient = $this->getSoapClient();

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

            $this->notify(
                [
                    'request' => $soapClient->__getLastRequest(),
                    'response' => $response,
                    'event' => $data['orderType']['orderTypeName'],
                ]
            );

            return $this->createResponse($this, $response);
        } else {
            throw new \Exception('AfterPay: couldn\'t make the request.');
        }
    }

    /**
     * Get the endpoint dependant on the country and if this is a test or live request.
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getTestMode() ?
            $this->testEndpoint[$this->getCountry()] :
            $this->liveEndpoint[$this->getCountry()];
    }

    /**
     * Create a response instance.
     * @param $request
     * @param $response
     *
     * @return Response
     */
    public function createResponse($request, $response)
    {
        return new Response($request, $response);
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

    /**
     * Format an amount for the payment currency.
     *
     * @return string
     */
    public function formatCurrency($amount)
    {
        $formatttedAmount = parent::formatCurrency($amount);

        return substr($formatttedAmount, 0, strlen($formatttedAmount) - 3);
    }
}
