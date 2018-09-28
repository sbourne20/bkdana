<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_bca extends CI_Controller {

	/* Default Statement */
	private static $main_url = 'https://sandbox.bca.co.id'; // Change When Your Apps is Live
	private static $client_id = '8421eda1-9b4e-4fb0-9a2e-13b081df95f4'; // Fill With Your Client ID
	private static $client_secret = '5b042637-d971-42ff-8d56-b8aea07c79c5'; // Fill With Your Client Secret ID
	private static $api_key = '49254e92-6e3f-4286-9701-51594d212a04'; // Fill With Your API Key
	private static $api_secret = 'a13797e3-b812-4b1f-afa0-59fd93fba118'; // Fill With Your API Secret Key
	private static $access_token = null;
	private static $signature = null;
	private static $timestamp = null;
	private static $corporate_id = 'BCAAPI2016'; // Fill With Your Corporate ID. BCAAPI2016 is Sandbox ID
	private static $account_number = '0201245680'; // Fill With Your Account Number. 0201245680 is Sandbox Account
	
	private function getToken()
	{
		$path = '/api/oauth/token';
		$headers = array(
			'Content-Type: application/x-www-form-urlencoded',
			'Authorization: Basic '.base64_encode(self::$client_id.':'.self::$client_secret));
		$data = array(
			'grant_type' => 'client_credentials'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$main_url.$path);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore Verify SSL Certificate
		curl_setopt_array($ch, array(
			CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => http_build_query($data),
		));
		$output = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($output,true);
		self::$access_token = $result['access_token'];
	}

	private function parseSignature($res)
	{
		$explode_response = explode(',', $res);
		$explode_response_1 = explode(':', $explode_response[8]);
		self::$signature = trim($explode_response_1[1]);
	}

	private function parseTimestamp($res)
	{
		$explode_response = explode(',', $res);
		$explode_response_1 = explode('Timestamp: ', $explode_response[3]);
		self::$timestamp = trim($explode_response_1[1]);
	}

	private function getSignature($url,$method,$data)
	{
		$path = '/utilities/signature';
		$timestamp = date(DateTime::ISO8601);
		$timestamp = str_replace('+','.000+', $timestamp);
		$timestamp = substr($timestamp, 0,(strlen($timestamp) - 2));
		$timestamp .= ':00';
		$url_encode = $url;
		$headers = array(
			'Timestamp: '.$timestamp,
			'URI: '.$url_encode,
			'AccessToken: '.self::$access_token,
			'APISecret: '.self::$api_secret,
			'HTTPMethod: '.$method,
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$main_url.$path);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore Verify SSL Certificate
		curl_setopt_array($ch, array(
			CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => http_build_query($data),
		));
		$output = curl_exec($ch);
		curl_close($ch);
		$this->parseSignature($output);
		$this->parseTimestamp($output);
	}
	/* End Default Statement */



	/******************************
				API BCA
	******************************/
	
	/* Get Balance BKDana Account */
	public function get_balance()
	{
		$this->getToken();
		$path = '/banking/v3/corporates/'.self::$corporate_id.'/accounts/'.self::$account_number;
		$method = 'GET';
		$data = array();
		$this->getSignature($path, $method, $data);
		$headers = array(
			'X-BCA-Key: '.self::$api_key,
			'X-BCA-Timestamp: '.self::$timestamp,
			'Authorization: Bearer '.self::$access_token,
			'X-BCA-Signature: '.self::$signature,
			'Content-Type: application/json',
			'Origin: '.$_SERVER['SERVER_NAME']
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$main_url.$path);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore Verify SSL Certificate
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => $headers
		));
		$response = curl_exec($ch); // This is API Response
		curl_close($ch);

		$response_array = array();
		$response_array = json_decode($response);
		echo '<pre>'; print_r($response_array); echo '</pre>';
		echo 'No.Rek : '.$response_array->AccountDetailDataSuccess['0']->AccountNumber.'<br>';
		echo 'Saldo : '.$response_array->AccountDetailDataSuccess['0']->Balance.'<br>';
		echo 'Token : '.self::$access_token;
		echo 'Signature : '.self::$signature;
	}

	/* Transfer only BCA */
	public function post_transfer_bcaonly()
	{
		$this->getToken();
		$path = '/banking/corporates/transfers';
		$method = 'POST';
		$data = array(
			'CorporateID' => self::$corporate_id,
		    'SourceAccountNumber' => self::$account_number,
		    'TransactionID' => '00000001',
		    'TransactionDate' => '2016-01-30',
		    'ReferenceID' => '12345/PO/2016',
		    'CurrencyCode' => 'IDR',
		    'Amount' => '100000.00',
		    'BeneficiaryAccountNumber' => '0201245681',
		    'Remark1' => 'Transfer Test',
		    'Remark2' => 'Online Transfer'
		);
		$this->getSignature($path, $method, $data);
		$headers = array(
			'X-BCA-Key: '.self::$api_key,
			'X-BCA-Timestamp: '.self::$timestamp,
			'Authorization: Bearer '.self::$access_token,
			'X-BCA-Signature: '.self::$signature,
			'Content-Type: application/json',
			'Origin: '.$_SERVER['SERVER_NAME']
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$main_url.$path);
		
		$payload = json_encode($data);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore Verify SSL Certificate
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => TRUE
		));
		$response = curl_exec($ch); // This is API Response
		curl_close($ch);

		$response_array = array();
		$response_array = json_decode($response);
		echo '<pre>'; print_r($response_array); echo '</pre>';
		echo '<pre>'; print_r($payload); echo '</pre>';
		echo 'Token : '.self::$access_token;
		echo 'Signature : '.self::$signature;
	}

	/* Post Fire TeleTransfer to Account */
	public function post_fire_teletransfer()
	{
		$this->getToken();
		$path = '/fire/transactions/to-account';
		$method = 'POST';
		$data = array();
		$this->getSignature($path, $method, $data);

		// param authentication (Required)
		$param_authentication = array(
			'CorporateID' => 'BCAAPI2016',
			'AccessCode' => 'FcgrR21fkzjE7GpuH2Eb',
        	'BranchCode' => 'DUMMYID01',
        	'UserID' => 'DUMMYIDO001',
        	'LocalID' => 'QWERTY54321'	
		);

		// param sender
		$param_senderDetails = array(
			'FirstName' => 'MG',
	        'LastName' => '',
	        'DateOfBirth' => '',
	        'Address1' => 'skgknp',
	        'Address2' => '',
	        'City' => 'India',
	        'StateID' => '',
	        'PostalCode' => '',
	        'CountryID' => 'US',
	        'Mobile' => '',
	        'IdentificationType' => '',
	        'IdentificationNumber' => '',
	        'AccountNumber' => ''
		);

		// param beneficiary (rekening tujuan transfer)
		$param_beneficiaryDetails = array(
			'Name' => 'monica gupt',
	        'DateOfBirth' => '',
	        'Address1' => '',
	        'Address2' => '',
	        'City' => '',
	        'StateID' => '',
	        'PostalCode' => '',
	        'CountryID' => 'ID',
	        'Mobile' => '',
	        'IdentificationType' => '',
	        'IdentificationNumber' => '',
	        'NationalityID' => '',
	        'Occupation' => '',
	        'BankCodeType' => 'BIC',
	        'BankCodeValue' => '260544  XXX',
	        'BankCountryID' => 'ID',
	        'BankAddress' => '',
	        'BankCity' => '',
	        'AccountNumber' => '0106666011'
		);

		// param transaction
		$param_transactionDetails = array(
			'CurrencyID' => 'IDR',
	        'Amount' => '10000000',
	        'PurposeCode' => '011',
	        'Description1' => '',
	        'Description2' => '',
	        'DetailOfCharges' => 'SHA',
	        'SourceOfFund' => '',
	        'FormNumber' => '7632605701245868'
		);

		// post parameter
		$post_param = array(
		    'Authentication' => $param_authentication,
		    'SenderDetails' => $param_senderDetails,
		    'BeneficiaryDetails' => $param_beneficiaryDetails,
		    'TransactionDetails' => $param_transactionDetails
		);
		
		$headers = array(
			'Authorization: Bearer '.self::$access_token,
			'Content-Type: application/json',
			'Origin: '.$_SERVER['SERVER_NAME'],
			'X-BCA-Key: '.self::$api_key,
			'X-BCA-Timestamp: '.self::$timestamp,	
			'X-BCA-Signature: '.self::$signature
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$main_url.$path);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore Verify SSL Certificate
		// curl_setopt($ch, CURLOPT_POSTFIELDS, print_r($post_param)); // For parsing post
		curl_setopt_array($ch, array(
			CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => http_build_query($post_param),
		));
		$response = curl_exec($ch); // This is API Response
		curl_close($ch);

		$response_array = array();
		$response_array = json_decode($response);
		echo '<pre>'; echo http_build_query($post_param); echo '</pre>';
		echo '<pre>'; print_r($response_array); echo '</pre>';
	}

}