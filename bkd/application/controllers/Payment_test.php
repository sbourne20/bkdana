<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_test extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();
		require_once(APPPATH . 'libraries/veritrans/Veritrans.php');
	}

	// Veritrans API (bisa taro diwebconfig)
	var $vMerchant_id = 'G077099250';
	var $vClient_key  = 'SB-Mid-client-3L-CVOaUEZHeshIs'; // Sandbox
	var $vServer_key  = 'SB-Mid-server-7UDjQBASCcq-WJdzM-nJBPZZ'; // sandbox

	var $set_order_id = '192837465'; //hanya untuk testing aja
	
	public function index()
	{
		$this->gateway();
	}

	public function gateway()
	{
		echo '
			<a href="'.site_url('payment/topup').'" class="btn btn-blue">Top Up</a><br>
			<a href="'.site_url('payment/redeem').'" class="btn btn-blue">Redeem</a>
		';
	}

	public function topup()
	{
		//Set Your server key
		Veritrans_Config::$serverKey = $this->vServer_key;
		Veritrans_Config::$isProduction = false;
		// Set sanitization on (default)
		Veritrans_Config::$isSanitized = true;
		// Set 3DS transaction for credit card to true
		Veritrans_Config::$is3ds = true;

		// Required
		$transaction_details = array(
		  'order_id'     => $this->set_order_id,
		  'gross_amount' => 500000 // no decimal allowed for creditcard
		);

		// Optional
		$item1_details = array(
		    'id' => 'tp-'.$this->set_order_id,
		    'price' => 500000,
		    'quantity' => 1,
		    'name' => 'Top Up'
		);

		// Optional
		$item_details = array ($item1_details);

		// Optional
		$billing_address = array(
		    'first_name'    => 'Andhika Desta Permana',
		    'address'       => 'Jl. Pulo Makmur No. 07',
		    'city'          => 'DKI Jakarta',
		    'postal_code'   => '12140',
		    'phone'         => '081806603068',
		    'country_code'  => 'IDN'
		);

		// Optional
		$customer_details = array(
			'first_name'       => 'Andhika Desta Permana',
			'email'            => 'desta@batavialabs.com',
			'phone'            => '081806603068',
			'billing_address'  => $billing_address
		);

		// Payment Virtual Account
		$va_details = array(
			'va_number'		 => '1234567890', // Permata bank maksimal 10 numerik (belum jalan, va_number masih bawaan midtrans)
     		'recipient_name' => 'Andhika Desta Permana'
		);

		$vtweb = array(
			// 'enabled_payments' => array('credit_card'),
			'enabled_payments' => array('bca_va'),
			'bca_va' => $va_details
		);

		// Fill transaction details
		$transaction = array(
			'payment_type' => 'vtweb',
			'vtweb' => $vtweb,
		    'transaction_details' => $transaction_details,
		    'customer_details' => $customer_details,
		    'item_details' => $item_details
		);

		try {
		  // Redirect to Veritrans VTWeb page
		  header('Location: ' . Veritrans_VtWeb::getRedirectionUrl($transaction));
		}
		catch (Exception $e) {
		  echo $e->getMessage();
		  if(strpos ($e->getMessage(), "Access denied due to unauthorized")){
		      echo "<code>";
		      echo "<h4>Please set real server key from sandbox</h4>";
		      echo "In file: " . __FILE__;
		      echo "<br>";
		      echo "<br>";
		      echo htmlspecialchars('Veritrans_Config::$serverKey = \'<your server key>\';');
		      die();
			}
		}

	}

	public function redeem()
	{
	}

	public function phpinfo()
	{
		phpinfo();
	}





	/**********************************************
	********** Controller untuk callback **********
	**********************************************/
	public function success()
	{
		// ------- Checkout berhasil -----

		$get 			  = $this->input->get(NULL, TRUE);
		$orderID          = $get['order_id'];
		$status           = $get['status_code'];
		$status_transaksi = $get['transaction_status'];

		switch ($status) {
			// sukses
			case '200':
				echo '<br><h4 style="text-align:center;">Terima kasih. Transaksi pembayaran Anda telah selesai</h4>';
				echo '<a href="'.site_url('payment_test').'" style="text-align:center;">[Back]</a>';
				break;

			// Pending
			case '201':
				echo '<br><h4 style="text-align:center;">Status Transaksi Anda Pending. Segera lakukan pembayaran di ATM. Cek Email Anda Segera!</h4>';
				echo '<a href="'.site_url('payment_test').'" style="text-align:center;">[Back]</a></h4>';
				break;

			// Denied
			case '202':
				echo '<br><h4 style="text-align:center;">Transaction has been processed but is denied by payment provider.</h4>';
				echo '<a href="'.site_url('payment_test').'" style="text-align:center;">[Back]</a></h4>';
				break;
			
			default:
				echo '<br><h4 style="text-align:center;">Unknown.';
				echo '<a href="'.site_url('payment_test').'" style="text-align:center;">[Back]</a></h4>';
				break;
		}
	}

	public function error()
	{
		echo 'Maaf, kami tidak bisa memproses pembayaran Anda.';
	}

	public function notification()
	{
		/* Notification from Midtrans */
		require_once(APPPATH . 'libraries/veritrans/Veritrans.php');
		//Set Your server key
		Veritrans_Config::$serverKey = $this->vServer_key;
		Veritrans_Config::$isProduction = false;

		$notif = new Veritrans_Notification();

		$transaction = $notif->transaction_status;
		$type        = $notif->payment_type;
		$order_id    = $notif->order_id;
		$fraud       = $notif->fraud_status;

		// --- Midtrans ----
		$midtrans['mid_order_id']    = $order_id;
		$midtrans['mid_transaction'] = $transaction;
		$midtrans['mid_type']        = $type;
		$midtrans['mid_fraud']       = $fraud;
		$midtrans['mid_date']        = date('Y-m-d H:i:s');
		// --- End Midtrans -----

		if($transaction == 'capture'){
		  // For credit card transaction, we need to check whether transaction is challenge by FDS or not
		  if($type == 'credit_card'){
		    if($fraud == 'challenge'){
		      // TODO set payment status in merchant's database to 'Challenge by FDS'
		      // TODO merchant should decide whether this transaction is authorized or not in MAP
		      echo "Transaction order_id: " . $order_id ." is challenged by FDS";
			    $upcheckout['status_name']  = 'challenged by FDS';
			    $upcheckout['payment_type'] = $type;
		      }else {
		      // TODO set payment status in merchant's database to 'Success'
		      echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
		        $upcheckout['status_order'] = '200';
		        $upcheckout['status_name']  = 'success';
			    $upcheckout['payment_type'] = $type;
		      }
		    }
		  }
		else if ($transaction == 'settlement'){
		  // TODO set payment status in merchant's database to 'Settlement'
		  echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
		  		$upcheckout['status_order'] = '200';
		  		$upcheckout['status_name']  = 'settlement';
			    $upcheckout['payment_type'] = $type;
		  } 
		  else if($transaction == 'pending'){
		  // TODO set payment status in merchant's database to 'Pending'
		  echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
		  		$upcheckout['status_order'] = '201';
		  		$upcheckout['status_name']  = 'pending';
			    $upcheckout['payment_type'] = $type;
		  } 
		  else if ($transaction == 'deny') {
		  // TODO set payment status in merchant's database to 'Denied'
		  echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
		  		$upcheckout['status_order'] = '202';
		  		$upcheckout['status_name']  = 'denied';
			    $upcheckout['payment_type'] = $type;
		  }
		  else if ($transaction == 'expire') {
		  // TODO set payment status in merchant's database to 'expire'
		  echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
		  		$upcheckout['status_name']  = 'expired';
			    $upcheckout['payment_type'] = $type;
		  }
		  else if ($transaction == 'cancel') {
		  // TODO set payment status in merchant's database to 'Denied'
		  echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
		  		$upcheckout['status_order'] = '202';
		  		$upcheckout['status_name']  = 'denied';
			    $upcheckout['payment_type'] = $type;
		}

	}

}