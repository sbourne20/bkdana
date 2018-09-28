<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Top_up extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();
		$this->load->model('Member_model');
		

		$this->Content_model->has_login();
		$this->Content_model->is_active_pendana();

		include(APPPATH.'libraries/phpmailer-5.2.23/PHPMailerAutoload.php');
		require_once(APPPATH.'libraries/TCPDF/tcpdf.php');

		error_reporting(E_ALL);
	}
	
	public function index()
	{
		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana

		// cek grade pendana
		if ($logintype == '2')
		{	
			$grade = $this->Content_model->check_grade($uid);
			if ($grade['peringkat_pengguna_persentase'] < $this->config->item('minimum_grade') )
			{
				$this->session->set_userdata('message','Lengkapi Profil Anda agar dapat melakukan Transaksi.');
				$this->session->set_userdata('message_type','error');
				redirect('lengkapi-profil');
				exit();
			}
		}
		// end cek grade

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/jquery-loading-overlay/dist/loadingoverlay.min.js');
		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/transaction.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;

		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);
		$data['history_topup']  = $this->Content_model->history_topup_member($uid);

		//_d($data['history_topup']);

		$data['pages']    = 'v_top_up';
		$this->load->view('template', $data);
	}

	/*function submit_manual()
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$post = $this->input->post(NULL, TRUE);

			$uid       = htmlentities($_SESSION['_bkduser_']);
			$logintype = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana

			if (trim($uid) == '' OR empty($uid)) {
				redirect('home');
				exit();
			}

			$prefixID    = 'T';
			$orderID     = $prefixID.strtoupper(substr(uniqid(sha1(time().$uid)),0,7));
			
	        $exist_order = $this->Content_model->check_topup_code($orderID);	// Cek if order ID exist on Database
			
			// jika order ID sudah ada di Database, generate lagi tambahkan datetime
			if (is_array($exist_order) && count($exist_order) > 0 )
			{
				$orderID = $prefixID.strtoupper(substr(uniqid(sha1(time().$uid.date('yzGis'))),0,7));
			}

			$memberdata  = $this->Member_model->get_member_byid($uid);

			//_d($memberdata);exit();

			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');

			$akun_bank_name   = trim($post['account_bank_name']);
			$no_rekening      = trim($post['account_bank_number']);
			$my_bank_name     = trim($post['my_bank_name']);
			$bank_destination = trim($post['bank_destination']);
			$jml_topup        = trim($post['jml_topup']);

			if ($no_rekening != '' && strlen($no_rekening)>5 && $my_bank_name != '' && $bank_destination != '' &&  $jml_topup!= '' && strlen($jml_topup) >= 3 )
			{

				$filter = explode('.', $jml_topup);
				$total_topup = str_replace(',', '', $filter[0]);

				if ($total_topup < $this->config->item('minimum_topup')) {
					$this->session->set_userdata('message','Top Up gagal. Minimum Top up Rp 100,000.');
					$this->session->set_userdata('message_type','error');
					redirect('top-up');
					exit();
				}

				$indata['kode_top_up']             = $orderID;
				$indata['member_id']               = antiInjection($uid);
				$indata['user_id']                 = antiInjection($memberdata['Id_pengguna']);
				$indata['nama_rekening_pengirim']  = antiInjection($akun_bank_name);
				$indata['nomor_rekening_pengirim'] = antiInjection($no_rekening);
				$indata['bank_pengirim']           = antiInjection($my_bank_name);
				$indata['bank_tujuan']             = antiInjection($bank_destination);
				$indata['jml_top_up']              = antiInjection($total_topup);
				$indata['tipe_top_up']             = 1;  // 1. transfer, 2.virtual account
				$indata['tgl_top_up']              = $nowdatetime;
				$indata['status_top_up']           = 'pending';

				$topupID = $this->Content_model->insert_top_up($indata);

				if ($topupID) {

					$tcount = $this->Content_model->count_topup($indata['member_id']);
					
					if ($tcount['flag_mail'] == 0 && $logintype=='2')
					{
						//echo '--------- Generate PDF Pendana ----------';
						unset($output);
						$output['member']    = $memberdata;
						$output['ordercode'] = date('dmY').$uid;
						$output['tgl_order'] = date('d/m/Y');
						$html = $this->load->view('email/vpendana', $output, TRUE);

						$filename = 'perjanjian-penawaran-pendanaan-'.$output['ordercode'].'.pdf';
						$title    = 'Perjanjian penawaraan pendanaan';
						$attach_file = create_pdf($html, $output['ordercode'], $filename, $title); // go to helper
						// --------- End Generate PDF ----------
						
						$this->send_email($memberdata['mum_email'], $attach_file);

						$pdf_folder = $this->config->item('attach_dir');
						unlink($pdf_folder.$filename);

						$uptpd['flag_mail'] = 1;
						$this->Content_model->update_topup($uid, $uptpd);
					}else{
						//echo 'tidak';
					}

					$this->session->set_userdata('message','Sukses Top Up Transfer Manual');
					$this->session->set_userdata('message_type','success');

					
				}else{
					$this->session->set_userdata('message','Error on Top Up!');
					$this->session->set_userdata('message_type','error');
				}
			}else{
				$this->session->set_userdata('message','Top Up gagal. Isilah semua kolom dengan benar. Cek Nomor Rekening Anda, jumlah top up min. Rp ' . $this->config->item('minimum_topup'));
				$this->session->set_userdata('message_type','error');
			}

			redirect('top-up');
		}else{
			redirect('home');
		}
	}*/

	public function set_payment($memberdata, $indata, $topupID)
	{
		
		require_once(APPPATH . 'libraries/veritrans/Veritrans.php');
		//Set Your server key
		Veritrans_Config::$serverKey = $this->config->item('vServer_key');
		Veritrans_Config::$isProduction = false;
		// Set sanitization on (default)
		Veritrans_Config::$isSanitized = true;
		// Set 3DS transaction for credit card to true
		Veritrans_Config::$is3ds = true;

		// Required
		$transaction_details = array(
		  'order_id'     => $indata['kode_top_up'],
		  'gross_amount' => $indata['jml_top_up'], // no decimal allowed for creditcard
		);

		// Optional
		$item1_details = array(
		    'id'       => $topupID,
		    'price'    => $indata['jml_top_up'],
		    'quantity' => 1,
		    'name'     => 'Top Up'
		);

		// Optional
		$item_details = array ($item1_details);

		// Optional
		$billing_address = array(
		    'first_name'    => $memberdata['mum_fullname'],
		    'address'       => $memberdata['Alamat'],
		    'city'          => $memberdata['Kota'],
		    'postal_code'   => $memberdata['Kodepos'],
		    'phone'         => $memberdata['Mobileno'],
		    'country_code'  => 'IDN'
		);

		// Optional
		$customer_details = array(
			'first_name'       => $memberdata['mum_fullname'],
			'email'            => $memberdata['mum_email'],
			'phone'            => $memberdata['Mobileno'],
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

	function submit_auto()
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$post = $this->input->post(NULL, TRUE);

			$uid = htmlentities($_SESSION['_bkduser_']);
			$logintype = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana

			if (trim($uid) == '' OR empty($uid)) {
				redirect('home');
				exit();
			}

			$prefixID    = 'T';
			$orderID     = $prefixID.strtoupper(substr(uniqid(sha1(time().$uid)),0,7));
	        $exist_order = $this->Content_model->check_topup_code($orderID);	// Cek if order ID exist on Database
			
			// jika order ID sudah ada di Database, generate lagi tambahkan datetime
			if (is_array($exist_order) && count($exist_order) > 0 )
			{
				$orderID = $prefixID.strtoupper(substr(uniqid(sha1(time().$uid.date('yzGis'))),0,7));
			}

			$memberdata  = $this->Member_model->get_member_byid($uid);

			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');

			$akun_bank_name   = trim($post['account_bank_name']);
			$no_rekening      = trim($post['account_bank_number']);
			$my_bank_name     = trim($post['my_bank_name']);
			$bank_destination = trim($post['bank_destination']);
			$jml_topup        = trim($post['jml_topup']);

			if ($no_rekening != '' && strlen($no_rekening)>5 && $my_bank_name != '' && $bank_destination != '' &&  $jml_topup!= '' && strlen($jml_topup) >= 3 )
			{

				$filter = explode('.', $jml_topup);
				$total_topup = str_replace(',', '', $filter[0]);

				if ($total_topup < $this->config->item('minimum_topup')) {
					$this->session->set_userdata('message','Top Up gagal. Minimum Top up Rp 100,000.');
					$this->session->set_userdata('message_type','error');
					redirect('top-up');
					exit();
				}

				$indata['kode_top_up']             = $orderID;
				$indata['member_id']               = antiInjection($uid);
				$indata['user_id']                 = antiInjection($memberdata['Id_pengguna']);
				$indata['nama_rekening_pengirim']  = antiInjection($akun_bank_name);
				$indata['nomor_rekening_pengirim'] = antiInjection($no_rekening);
				$indata['bank_pengirim']           = antiInjection($my_bank_name);
				$indata['bank_tujuan']             = antiInjection($bank_destination);
				$indata['jml_top_up']              = antiInjection($total_topup);
				$indata['tipe_top_up']             = 2;  // 1. transfer manual, 2.virtual account
				$indata['tgl_top_up']              = $nowdatetime;
				$indata['status_top_up']           = 'pending';

				$topupID = $this->Content_model->insert_top_up($indata);

				if ($topupID) {
					$tcount = $this->Content_model->count_topup($indata['member_id']);
					
					if ($tcount['flag_mail'] == 0 && $logintype=='2')
					{
						//echo '--------- Generate PDF Pendana ----------';
						unset($output);
						$output['member']    = $memberdata;
						$output['ordercode'] = date('dmY').$uid;
						$output['tgl_order'] = date('d/m/Y');
						$html = $this->load->view('email/vpendana', $output, TRUE);

						$filename = 'perjanjian-penawaran-pendanaan-'.$output['ordercode'].'.pdf';
						$title    = 'Perjanjian penawaraan pendanaan';
						$attach_file = create_pdf($html, $output['ordercode'], $filename, $title); // go to helper
						// --------- End Generate PDF ----------
						
						$this->send_email($memberdata['mum_email'], $attach_file);

						$pdf_folder = $this->config->item('attach_dir');
						unlink($pdf_folder.$filename);

						$uptpd['flag_mail'] = 1;
						$this->Content_model->update_topup($uid, $uptpd);
					}else{
						//echo 'tidak';
					}
					
					// $this->session->set_userdata('message','Sukses Top Up Virtual Account');
					// $this->session->set_userdata('message_type','success');

					$this->set_payment($memberdata, $indata, $topupID);
				}else{
					$this->session->set_userdata('message','Error on Top Up!');
				redirect('top-up');
					$this->session->set_userdata('message_type','error');
				}
			}else{
				$this->session->set_userdata('message','Top Up gagal. Isilah semua kolom dengan benar. Cek Nomor Rekening Anda, jumlah top up min. Rp ' . $this->config->item('minimum_topup'));
				$this->session->set_userdata('message_type','error');
				redirect('top-up');
			}

		}else{
			redirect('home');
		}
	}

	// Email KE Pendana
	function send_email($email, $file)
	{
		$html_content = '
        Hai '.$email.',<br><br>

            Berikut kami kirimkan perjanjian penawaran pendanaan.<br><br>

            <span style="color:#858C93;">
            	Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke Email ini.
            	<br><br>

            	&copy; BKDana.com, '.date("Y").'. All rights reserved.
            </span>
			';

    	$mail = new phpmailer();
        $mail->IsSMTP();
		$mail->SMTPAuth    = true;
		//$mail->SMTPSecure  = 'ssl';
		$mail->Host        = 'smtp.gmail.com';
		$mail->Port        = 587;
		$mail->IsHTML(true);
		$mail->Username    = $this->config->item('mail_username');
		$mail->Password    = $this->config->item('mail_password');
		$mail->SetFrom('bkdanafinansial@gmail.com', 'BKDana');
		$mail->AddAddress($email);
		$mail->Subject     = 'Perjanjian Penawaran Pendanaan';
		$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($html_content);	
		$mail->SMTPDebug   = 0;
		$mail->addAttachment($file['output_file'], $file['filename']);
        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }	

        return TRUE;
	}
	
}