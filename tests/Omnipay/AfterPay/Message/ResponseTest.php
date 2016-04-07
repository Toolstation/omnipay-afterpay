<?php

namespace Omnipay\AfterPay\Message;

use Mockery as m;
use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $soapResponse = $this->getMockSoapResponse('PurchaseSuccess');
        $response = new Response($this->getMockRequest(), $soapResponse);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('10003022', $response->getTransactionReference());
        $this->assertEmpty($response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $soapResponse = $this->getMockSoapResponse('PurchaseFailure');
        $response = new Response($this->getMockRequest(), $soapResponse);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('', $response->getTransactionReference());
        $this->assertSame(
            'Validation failed.  ErrorCode: ValueOutOfLegalRange (fieldname : TotalOrderAmount)',
            $response->getMessage()
        );
    }

    public function testCancelSuccess()
    {
        $soapResponse = $this->getMockSoapResponse('cancelSuccess');
        $response = new Response($this->getMockRequest(), $soapResponse);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('10003022', $response->getTransactionReference());
        $this->assertEmpty($response->getMessage());
    }

    public function testCancelFailure()
    {
        $soapResponse = $this->getMockSoapResponse('cancelFailure');
        $response = new Response($this->getMockRequest(), $soapResponse);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('10003022', $response->getTransactionReference());
    }

    public function testCaptureSuccess()
    {
        $soapResponse = $this->getMockSoapResponse('captureSuccess');
        $response = new Response($this->getMockRequest(), $soapResponse);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('10003022', $response->getTransactionReference());
        $this->assertEmpty($response->getMessage());
    }

    public function testCaptureFailure()
    {
        $soapResponse = $this->getMockSoapResponse('captureFailure');
        $response = new Response($this->getMockRequest(), $soapResponse);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('10003022', $response->getTransactionReference());
    }

    public function testRefundSuccess()
    {
        $soapResponse = $this->getMockSoapResponse('refundSuccess');
        $response = new Response($this->getMockRequest(), $soapResponse);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('10003022', $response->getTransactionReference());
        $this->assertEmpty($response->getMessage());
    }

    public function testRefundFailure()
    {
        $soapResponse = $this->getMockSoapResponse('refundFailure');
        $response = new Response($this->getMockRequest(), $soapResponse);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('10003022', $response->getTransactionReference());
    }

    public function testVoidSuccess()
    {
        $soapResponse = $this->getMockSoapResponse('voidSuccess');
        $response = new Response($this->getMockRequest(), $soapResponse);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('10003022', $response->getTransactionReference());
        $this->assertEmpty($response->getMessage());
    }

    public function testVoidFailure()
    {
        $soapResponse = $this->getMockSoapResponse('voidFailure');
        $response = new Response($this->getMockRequest(), $soapResponse);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('10003022', $response->getTransactionReference());
    }

    private function getMockSoapResponse($responseName)
    {
        return include('../Mock/'.$responseName.'.php');
    }
}
