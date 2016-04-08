<?php

namespace Omnipay\AfterPay;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setMerchantId('123');
        $this->gateway->setPassword('123');
        $this->gateway->setPortfolioId('1');
        $this->gateway->setTestMode(true);

        $this->options = array(

            'orderType' => 'B2C',
            'country' => 'DE',
            'amount' => '10.00',
            'currency' => 'EUR',
            'transactionId' => '123',
            'clientIp' => '127.0.0.1',
            'vatCode' => 2,

            'card' => new CreditCard(array(
                'address1' => '19',
                'address2' => 'Teststraat',
                'city' => 'Amsterdam',
                'country' => 'NL',
                'email' => 'john@example.com',
                'firstName' => 'John',
                'lastName' => 'Smith',
                'number' => '912367288',
                'phone' => '+31206370705',
                'postcode' => '1012XM',
                'birthday' => '1985-01-24T06:00:00',
                'gender' => 'M'
            )),
        );
    }

    public function testPurchaseSuccess()
    {
        $soapClient = \Mockery::mock('\SoapClient');
        $soapClient->shouldReceive('__soapCall')->andReturn($this->getMockSoapResponse('purchaseSuccess'));

        $purchaseRequest = $this->gateway->purchase($this->options);
        $purchaseRequest->setSoapClient($soapClient);

        $purchaseResponse = $purchaseRequest->send();

        $this->assertTrue($purchaseResponse->isSuccessful());
        $this->assertFalse($purchaseResponse->isRedirect());
    }

    public function testPurchaseRejection()
    {
        $soapClient = \Mockery::mock('\SoapClient');
        $soapClient->shouldReceive('__soapCall')->andReturn($this->getMockSoapResponse('purchaseFailure'));

        $purchaseRequest = $this->gateway->purchase($this->options);
        $purchaseRequest->setSoapClient($soapClient);

        $purchaseResponse = $purchaseRequest->send();

        $this->assertFalse($purchaseResponse->isSuccessful());
    }

    public function testCancelSuccess()
    {
        $soapClient = \Mockery::mock('\SoapClient');
        $soapClient->shouldReceive('__soapCall')->andReturn($this->getMockSoapResponse('cancelSuccess'));

        $cancelRequest = $this->gateway->cancel($this->options);
        $cancelRequest->setSoapClient($soapClient);

        $cancelResponse = $cancelRequest->send();

        $this->assertTrue($cancelResponse->isSuccessful());
    }

    public function testCancelRejection()
    {
        $soapClient = \Mockery::mock('\SoapClient');
        $soapClient->shouldReceive('__soapCall')->andReturn($this->getMockSoapResponse('cancelFailure'));

        $cancelRequest = $this->gateway->cancel($this->options);
        $cancelRequest->setSoapClient($soapClient);

        $cancelResponse = $cancelRequest->send();

        $this->assertFalse($cancelResponse->isSuccessful());
    }

    public function testCaptureSuccess()
    {
        $soapClient = \Mockery::mock('\SoapClient');
        $soapClient->shouldReceive('__soapCall')->andReturn($this->getMockSoapResponse('captureSuccess'));

        $this->options['invoiceNumber'] = 'invoiceNumber';

        $captureRequest = $this->gateway->capture($this->options);
        $captureRequest->setSoapClient($soapClient);

        $captureResponse = $captureRequest->send();

        $this->assertTrue($captureResponse->isSuccessful());
    }

    public function testCaptureRejection()
    {
        $soapClient = \Mockery::mock('\SoapClient');
        $soapClient->shouldReceive('__soapCall')->andReturn($this->getMockSoapResponse('captureFailure'));

        $this->options['invoiceNumber'] = 'invoiceNumber';

        $captureRequest = $this->gateway->capture($this->options);
        $captureRequest->setSoapClient($soapClient);

        $captureResponse = $captureRequest->send();

        $this->assertFalse($captureResponse->isSuccessful());
    }

    public function testRefundSuccess()
    {
        $soapClient = \Mockery::mock('\SoapClient');
        $soapClient->shouldReceive('__soapCall')->andReturn($this->getMockSoapResponse('refundSuccess'));

        $this->options['invoiceNumber'] = 'invoiceNumber';

        $refundRequest = $this->gateway->refund($this->options);
        $refundRequest->setSoapClient($soapClient);

        $refundResponse = $refundRequest->send();

        $this->assertTrue($refundResponse->isSuccessful());
    }

    public function testRefundRejection()
    {
        $soapClient = \Mockery::mock('\SoapClient');
        $soapClient->shouldReceive('__soapCall')->andReturn($this->getMockSoapResponse('refundFailure'));

        $this->options['invoiceNumber'] = 'invoiceNumber';

        $refundRequest = $this->gateway->refund($this->options);
        $refundRequest->setSoapClient($soapClient);

        $refundResponse = $refundRequest->send();

        $this->assertFalse($refundResponse->isSuccessful());
    }

    public function testVoidSuccess()
    {
        $soapClient = \Mockery::mock('\SoapClient');
        $soapClient->shouldReceive('__soapCall')->andReturn($this->getMockSoapResponse('voidSuccess'));

        $this->options['invoiceNumber'] = 'invoiceNumber';

        $voidRequest = $this->gateway->void($this->options);
        $voidRequest->setSoapClient($soapClient);

        $voidResponse = $voidRequest->send();

        $this->assertTrue($voidResponse->isSuccessful());
    }

    public function testVoidRejection()
    {
        $soapClient = \Mockery::mock('\SoapClient');
        $soapClient->shouldReceive('__soapCall')->andReturn($this->getMockSoapResponse('voidFailure'));

        $this->options['invoiceNumber'] = 'invoiceNumber';

        $voidRequest = $this->gateway->void($this->options);
        $voidRequest->setSoapClient($soapClient);

        $voidResponse = $voidRequest->send();

        $this->assertFalse($voidResponse->isSuccessful());
    }


    private function getMockSoapResponse($responseName)
    {
        return include(__DIR__.'/Mock/'.$responseName.'.php');
    }
}
