<?php
/**
 * Abstract class to be extended by all Order Management request classes
 */

namespace Omnipay\AfterPay\Message;

/**
 * Class Management
 *
 * @package Omnipay\AfterPay\Message
 */
abstract class Management extends AbstractRequest
{
    /**
     * The live endpoints.
     * @var array
     */
    protected $liveEndpoint = [
        'BE' => 'https://www.afterpay.be/soapservices/om/OrderManagement?wsdl',
        'DE' => '',
        'NL' => 'https://www.acceptgirodienst.nl/soapservices/om/OrderManagement?wsdl',
    ];

    /**
     * The test endpoints
     * @var array
     */
    protected $testEndpoint = [
        'BE' => 'https://test.afterpay.be/soapservices/om/OrderManagement?wsdl',
        'DE' => 'https://sandboxapi.horizonafs.com/eCommerceServices/AfterPay/OrderManagement/v2/'
            . 'OrderManagementServices.svc?singleWsdl',
        'NL' => 'https://test.acceptgirodienst.nl/soapservices/om/OrderManagement?wsdl',
    ];

    /**
     * Get the data that is sent with all order management requests.
     * @return array
     */
    protected function getBaseData()
    {
        $data = ['order_type' => 'OM'];
        $data['authorization'] = $this->getAuthorisation();

        return $data;
    }
}
