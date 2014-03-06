<?php
namespace Opine;

class PaymentBraintreeGateway {
	function _construct ($db,$config) {
		$this->db = $db;
		$this->config = $config;
	}
}