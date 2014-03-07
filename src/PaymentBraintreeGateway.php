<?php
namespace Opine;

class PaymentBraintreeGateway {
	private $config;
	private $db;

	public function __construct ($db, $config) {
		$this->db = $db;
		$this->config = $config;
		$braintreeConfig = $this->config->braintree;
		\Braintree_Configuration::environment($braintreeConfig['Environment']);
		\Braintree_Configuration::merchantId($braintreeConfig['MerchantID']);
		\Braintree_Configuration::publicKey($braintreeConfig['PublicKey']);
		\Braintree_Configuration::privateKey($braintreeConfig['PrivateKey']);
	}

	public function payment ($description, $amount, array $paymentInfo, array $billingInfo, &$response) {
		if (empty($description)) {
			throw new \Exception('Invalid Description');
		}
		if (!is_numeric($amount) || $amount === 0) {
			throw new \Exception('Invalid Amount');
		}
		if (!isset($paymentInfo['number'])) {
			throw new \Exception('Card Number not set');
		}
		if (!isset($paymentInfo['expiration'])) {
			throw new \Exception('expiration');
		}
		$cvv = null;
		if (isset($paymentInfo['cvv'])) {
			$cvv = $paymentInfo['cvv'];
		}

		$result = \Braintree_Transaction::sale(array(
		    'amount' => $amount,
		    'creditCard' => array(
		        'number' => $paymentInfo['number'],
		        'expirationDate' => $paymentInfo['expiration']
		    )
		));
		if ($result->success) {
		    $response = [
		    	'success' => true,
		    	'transaction_id' => $result->transaction->id,
		    	'response' => $result->transaction
		    ];
		    return true;
		} else if ($result->transaction) {
			$response = [
		    	'success' => false,
		    	'errorMessage' => $result->transaction->processorResponseText,
		    	'errorCode' => $result->transaction->processorResponseCode,
		    	'response' => $result->transaction
		    ];
		    return false;
		}
		$error = $result->errors->deepAll()[0];
		$response = [
		    'success' => false,
			'errorMessage' => $error->message,
			'errorCode' => $error->code,
			'response' => $error
		];
		return false;
	}

	private function saveTransactions () {

	}
}