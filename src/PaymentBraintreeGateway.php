<?php
namespace Opine;

class PaymentBraintreeGateway {
	private $config;
	private $db;
	private $MerchantID;
	private $PublicKey;
	private $PrivateKey;
	private $CSE;
	private $Environment;

	public function __construct ($db, $config) {
		$this->db = $db;
		$this->config = $config;
		$braintreeConfig = $this->config->braintree;
		$this->MerchantID = $braintreeConfig['MerchantID'];
		$this->PublicKey = $braintreeConfig['PublicKey'];
		$this->PrivateKey = $braintreeConfig['PrivateKey'];
		$this->CSE = $braintreeConfig['CSE'];
		$this->Environment = $braintreeConfig['Environment'];
	}

	public function payment ($description, $amount, array $paymentInfo, array $billingInfo) {
		\Braintree_Configuration::environment($this->Environment);
		\Braintree_Configuration::merchantId($this->MerchantID);
		\Braintree_Configuration::publicKey($this->PublicKey);
		\Braintree_Configuration::privateKey($this->PrivateKey);

		$result = \Braintree_Transaction::sale(array(
		    'amount' => '1000.00',
		    'creditCard' => array(
		        'number' => '5105105105105100',
		        'expirationDate' => '05/12'
		    )
		));

		if ($result->success) {
		    print_r("success!: " . $result->transaction->id);
		} else if ($result->transaction) {
		    print_r("Error processing transaction:");
		    print_r("\n  code: " . $result->transaction->processorResponseCode);
		    print_r("\n  text: " . $result->transaction->processorResponseText);
		} else {
		    print_r("Validation errors: \n");
		    print_r($result->errors->deepAll());
		}
	}
}