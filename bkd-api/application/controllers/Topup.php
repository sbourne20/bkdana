<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Topup extends REST_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');
		$this->load->model('Member_model');

		include(APPPATH.'libraries/phpmailer-5.2.23/PHPMailerAutoload.php');
		require_once(APPPATH.'libraries/TCPDF/tcpdf.php');

		error_reporting(E_ALL);
        ini_set('display_errors', '1');
	}

	function index(){
	}

	function list_get()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				$logintype = (int)antiInjection($token->logtype);

				if (!empty($uid) && is_numeric($logintype)) {
					
					$limit_per_page = (int)$this->input->get('limit', TRUE);
			        $start_index    = (int)$this->input->get('page', TRUE);

			        if (empty($limit_per_page)) {
			        	$limit_per_page = 10;
			        }

			        if (empty($start_index) OR $start_index=='1') {
			        	$start_index = 0;
			        }else{
			        	$start_index = $start_index * $limit;
			        }

					$data['list_topup'] = $this->Content_model->history_topup_member($uid, $start_index, $limit_per_page);

					if (count($data['list_topup']) > 0) {

			    		$response['response'] = 'success';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = $data['list_topup'];
	                    $this->set_response($response, REST_Controller::HTTP_OK);
					   	return;

			    	}else{

			    		$response = [
		            		'response' => 'fail',
			                'status'   => 400,
			                'content'  => '',
			                'message'  => 'data kosong',
			            ];
			    		$http_status = REST_Controller::HTTP_OK;
			    	}
			        
			    }else{
			    	$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'response_code' => 401,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			    }

			}else{
				$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'response_code' => 401,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			}
		}else{
			$response = [
        		'response' => 'fail',
                'status'   => REST_Controller::HTTP_FORBIDDEN,
                'message'  => 'Forbidden',
            ];
            $http_status = REST_Controller::HTTP_FORBIDDEN;
		}

		$this->set_response($response, $http_status);
        return;
	}

	function submit_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				$logintype = (int)antiInjection($token->logtype);

				if (!empty($uid) && is_numeric($logintype)) {
					
					$post = $this->input->post(NULL, TRUE);

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

					$akun_bank_name   = trim($post['nama_rekening']);
					$no_rekening      = trim($post['nomor_rekening']);
					$my_bank_name     = trim($post['nama_bank']);
					$bank_destination = $this->config->item('bank_tujuan');
					$jml_topup        = trim($post['jumlah_topup']);

					if ( $jml_topup != '' && strlen($jml_topup) >= 3 )
					{
						$total_topup = $jml_topup;

						if ($total_topup < $this->config->item('minimum_topup')) {

							$response['response'] = 'fail';
		                    $response['status']   = REST_Controller::HTTP_OK;
		                    $response['content']  = '';
		                    $response['message']  = 'Top Up gagal. Minimum Top up Rp 100,000.';
		                    $this->set_response($response, REST_Controller::HTTP_OK);
		                    return;
						}else{
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
								}

								$memberid = urlencode($uid);
								$orderid = urlencode($orderID);

								$uri_dir = base_url().'mobile-payment/vtweb/'.$jml_topup.'/'.$memberid.'/'.$orderid;

								$response['response'] = 'success';
			                    $response['status']   = REST_Controller::HTTP_OK;
			                    $response['content']  = $uri_dir;
			                    $this->set_response($response, REST_Controller::HTTP_OK);
							   	return;
							}
						}
					}else{
						$response['response'] = 'fail';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = '';
	                    $response['message']  = 'Jumlah Topup harus diisi';
	                    $this->set_response($response, REST_Controller::HTTP_OK);
	                    return;
					}
			        
			    }else{
			    	$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'response_code' => 401,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			    }

			}else{
				$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'response_code' => 401,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			}
		}else{
			$response = [
        		'response' => 'fail',
                'status'   => REST_Controller::HTTP_FORBIDDEN,
                'message'  => 'Forbidden',
            ];
            $http_status = REST_Controller::HTTP_FORBIDDEN;
		}

		$this->set_response($response, $http_status);
        return;
	}

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