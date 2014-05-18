<?php
namespace Opine;

class PaymentBraintreeGatewayTest extends \PHPUnit_Framework_TestCase {
	private $paymentGateway;
    private $db;

    public function setup () {
        date_default_timezone_set('America/New_York');
        $root = __DIR__;
        $container = new Container($root, $root . '/container.yml');
        $this->paymentGateway = $container->paymentGateway;
        $this->db = $container->db;
    }

    public function testPaymentInvalidDescription() {
        $response = [];
        $correctError = false;
        try {
            $this->paymentGateway->payment('', 9, [], [], $response);
        } catch (\Exception $e) {
            if ($e->getMessage() == 'Invalid Description') {
                $correctError = true;
            }
        }
        $this->assertTrue($correctError);
    }

    public function testPaymentInvalidAmount () {
        $response = [];
        $correctError = false;
        try {
            $this->paymentGateway->payment('12 eggs', 'Five', [], [], $response);
        } catch (\Exception $e) {
            if ($e->getMessage() == 'Invalid Amount') {
                $correctError = true;
            }
        }
        $this->assertTrue($correctError);
    }

    public function testPaymentSuccess () {
    	$response = [];
        $result = $this->paymentGateway->payment('12 eggs', 9, [
            'number' => '5105105105105100',
            'expiration' => '05/12'
        ], [], $response);
        $this->assertTrue($result);
    }

    public function testPaymentFailureInvalidCardNumber () {
        $response = [];
        $result = $this->paymentGateway->payment('12 eggs', 9, [
            'number' => '5105105105105199',
            'expiration' => '05/12'
        ], [], $response);
        $this->assertFalse($result);
        $correctError = false;
        if ($response['errorMessage'] == 'Credit card number is invalid.') {
            $correctError = true;
        }
        $this->assertTrue($correctError);
    }
}