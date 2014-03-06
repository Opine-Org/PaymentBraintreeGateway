<?php
namespace Opine;

class PaymentBraintreeGatewayTest extends \PHPUnit_Framework_TestCase {
	private $paymentGateway;
    private $db;

    public function setup () {
        date_default_timezone_set('America/New_York');
        $root = getcwd();
        $container = new Container($root, $root . '/container.yml');
        $this->paymentGateway = $container->paymentGateway;
        $this->db = $container->db;
    }

    public function testPaymentSuccess () {
    	$this->paymentGateway->payment('', 9, [], []);
    }
}