<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mobile_payment extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();
		require_once(APPPATH.'libraries/veritrans/Veritrans.php');

		$this->load->model('Content_model');
		$this->load->model('Member_model');
		$this->load->model('Wallet_model');
		$this->load->model('Payment_model');

		error_reporting(E_ALL);
        ini_set('display_errors', '1');
	}

	// Veritrans API (bisa taro diwebconfig)
	// var $vMerchant_id = 'G077099250';
	// var $vClient_key  = 'SB-Mid-client-3L-CVOaUEZHeshIs'; // Sandbox
	// var $vServer_key  = 'SB-Mid-server-7UDjQBASCcq-WJdzM-nJBPZZ'; // sandbox

	// var $set_order_id = rand(); //hanya untuk testing aja
	
	public function index()
	{
		echo 'mob';
	}

	public function vtweb()
	{
		//Set Your server key
		Veritrans_Config::$serverKey = $this->config->item('vServer_key');
		Veritrans_Config::$isProduction = false;
		// Set sanitization on (default)
		Veritrans_Config::$isSanitized = true;
		// Set 3DS transaction for credit card to true
		Veritrans_Config::$is3ds = true;

		$jmltopup = htmlentities(strip_tags(trim($this->uri->segment(3))));
		$memberid = htmlentities(strip_tags(trim(urldecode($this->uri->segment(4)))));
		$orderID = htmlentities(strip_tags(trim(urldecode($this->uri->segment(5)))));

		$member = $this->Member_model->data_member($memberid);
		$dataorder = $this->Content_model->get_topup_bycode($orderID);

		// Required
		$transaction_details = array(
		  'order_id'     => $orderID,
		  'gross_amount' => $jmltopup // no decimal allowed for creditcard
		);

		// Optional
		$item1_details = array(
		    'id' => $orderID,
		    'price' => $jmltopup,
		    'quantity' => 1,
		    'name' => 'Top Up'
		);

		// Optional
		$item_details = array ($item1_details);

		// Optional
		$billing_address = array(
		    'first_name'    => $member['Nama_pengguna'],
		    'address'       => $member['Alamat'],
		    'city'          => $member['Kota'],
		    'postal_code'   => $member['Kodepos'],
		    'phone'         => $member['Mobileno'],
		    'country_code'  => 'IDN'
		);

		// Optional
		$customer_details = array(
			'first_name'       => $member['Nama_pengguna'],
			'email'            => $member['email'],
			'phone'            => $member['Mobileno'],
			'billing_address'  => $billing_address
		);

		// Payment Virtual Account
		$va_details = array(
			'va_number'		 => '1234567890', // Permata bank maksimal 10 numerik (belum jalan, va_number masih bawaan midtrans)
     		'recipient_name' => $member['Nama_pengguna']
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
				// echo '<br><h4 style="text-align:center;">Status Transaksi Anda Pending. Segera lakukan pembayaran di ATM. Cek Email Anda Segera!</h4>';
				echo '<br><h4 style="text-align:center;">Top Up Berhasil</h4>';
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
		Veritrans_Config::$serverKey = $this->config->item('vServer_key');
		Veritrans_Config::$isProduction = false;

		$notif = new Veritrans_Notification();

		$trans_time   = $notif->transaction_time;
		$transaction  = $notif->transaction_status;
		$type         = $notif->payment_type;
		$order_id     = $notif->order_id;
		$fraud        = $notif->fraud_status;
		$gross_amount = $notif->gross_amount;

		$is_paid = 0;
		$balance_wallet = 0;

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

			    $is_paid = 1;
		      }
		    }
		  }
		else if ($transaction == 'settlement'){
		  // TODO set payment status in merchant's database to 'Settlement'
		  echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
		  		$upcheckout['status_order'] = '200';
		  		$upcheckout['status_name']  = 'settlement';
			    $upcheckout['payment_type'] = $type;

			    $is_paid = 1;
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

		$upcheckout['order_code']   = $order_id;
		$upcheckout['fraud_status'] = $fraud;
		$upcheckout['gross_amount'] = $gross_amount;
		$upcheckout['pay_date']     = $trans_time;
		$this->Payment_model->insert_payment_log($upcheckout);

		if ($is_paid == 1) {
			// update top up status
			$topdata['payment_status'] = $upcheckout['status_name'];
			$this->Payment_model->update_top_up($order_id, $topdata);

			// ------- tambah saldo user ------- //
			$data_topup   = $this->Payment_model->get_topup_by($order_id);
			$check_wallet = $this->Wallet_model->get_wallet_byuser($data_topup['user_id']);

			//_d($check_wallet);

			if (count($check_wallet)>1 && isset($check_wallet['User_id'])) {
				//echo 'update master';
				$this->Wallet_model->update_master_wallet_saldo($check_wallet['User_id'], $gross_amount);

				$master_wallet_id = $check_wallet['Id'];
				$balance_wallet = $gross_amount + $check_wallet['Amount'];
			}else{
				//echo  'insert master';
				$inwallet['Date_create']      = date('Y-m-d H:i:s');
				$inwallet['User_id']          = $data_topup['user_id'];
				$inwallet['Amount']           = $gross_amount;
				$inwallet['wallet_member_id'] = $data_topup['member_id'];

				$master_wallet_id = $this->Wallet_model->insert_master_wallet($inwallet);

				$balance_wallet = $gross_amount;
			}

			// insert detail transaksi wallet
			$detail_w['Id']               = $master_wallet_id;
			$detail_w['Date_transaction'] = date('Y-m-d H:i:s');
			$detail_w['Amount']           = $gross_amount;
			$detail_w['Notes']            = 'Top Up';
			$detail_w['tipe_dana']        = 1;
			$detail_w['User_id']          = $data_topup['user_id'];
			$detail_w['kode_transaksi']   = $order_id;
			$detail_w['balance']          = $balance_wallet;
			$this->Wallet_model->insert_detail_wallet($detail_w);
			// End insert detail wallet
		}
	}

}