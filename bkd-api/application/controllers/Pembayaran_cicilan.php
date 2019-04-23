t<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Pembayaran_cicilan extends REST_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');
		$this->load->model('Wallet_model');

		/*error_reporting(E_ALL);
  		ini_set('display_errors', '1');*/

  		ini_set('max_execution_time', 1800);

        include(APPPATH.'libraries/phpmailer-5.2.23/PHPMailerAutoload.php');
	}

	function index(){
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

					$transaksi_id = trim($post['transaksi_id']);
					$jml_bayar    = trim($post['jml_bayar']);

					$kode = substr($transaksi_id, 0, 2);

					if ($kode == 'PK')
					{
						redirect('pembayaran_cicilan/submit_cicilan_kilat?transaksi_id='.$transaksi_id.'&jml_bayar='.$jml_bayar);
					}else{
						redirect('pembayaran_cicilan/submit_cicilan_mikro?transaksi_id='.$transaksi_id.'&jml_bayar='.$jml_bayar);
					}

				}else{
			    	$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			    }

			}else{
				$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
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

	function submit_cicilan_kilat_get()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				$logintype = (int)antiInjection($token->logtype);

				if (!empty($uid) && is_numeric($logintype)) {

					$post = $this->input->get(NULL, TRUE);

					$transaksi_id = trim($post['transaksi_id']);
					$jml_bayar    = trim($post['jml_bayar']);

					if ($transaksi_id != '' AND $jml_bayar != '' AND strlen($jml_bayar) > 4 )
					{
						$check_if_exist = $this->Content_model->check_ordercode_pinjaman($transaksi_id);

						if (isset($check_if_exist['Master_loan_id']) && $check_if_exist['Master_loan_id'] == $transaksi_id && $check_if_exist['Master_loan_status'] == 'complete'  )
						{
							$nowdate     = date('Y-m-d');
							$nowdatetime = date('Y-m-d H:i:s');
							$get_master_wallet = $this->Wallet_model->get_wallet_bymember($uid);

							if (count($get_master_wallet)>0 && isset($get_master_wallet['Id']) && $get_master_wallet['Amount'] >= $jml_bayar)
							{

								$indetail['Master_loan_id']   = antiInjection($transaksi_id);
								$indetail['Date_repaid']      = $nowdate;
								$indetail['Amount_repayment'] = antiInjection($jml_bayar);
								$indetail['Nomor_angsuran']   = 1;

								$detail_id = $this->Content_model->insert_cicilan($indetail);

								if ($detail_id)
								{
									// update profil pinjaman tambah total_loan_repayment, kurangi total_loan_outstanding
									$this->Content_model->update_total_loan_repayment($indetail['Master_loan_id'], $uid, $indetail['Amount_repayment']);

									// master wallet -> kurangi saldo peminjam
									$this->Wallet_model->kurangi_saldo_wallet($uid, $jml_bayar);

									// detail transaksi wallet 
									$detail_w['Id']               = $get_master_wallet['Id'];
									$detail_w['Date_transaction'] = $nowdate;
									$detail_w['Amount']           = $jml_bayar;
									$detail_w['Notes']            = 'Pembayaran pinjaman No.'. $indetail['Master_loan_id'];
									$detail_w['tipe_dana']        = 2;
									$detail_w['User_id']          = $get_master_wallet['User_id'];
									$detail_w['kode_transaksi']   = $indetail['Master_loan_id'];
									$detail_w['balance']          =  $get_master_wallet['Amount'] - $detail_w['Amount'];
									$this->Wallet_model->insert_detail_wallet($detail_w);

									$get_data_pinjam = $this->Content_model->get_transaksi_pinjam_byid($transaksi_id); // get total yg sdh diangsur

									if ($get_data_pinjam['Total_loan_repayment'] >= $get_data_pinjam['Jml_permohonan_pinjaman_disetujui'])
									{
										// ------ status lunas, date close -------
										$this->Content_model->close_pinjaman($indetail['Master_loan_id']);
										$this->Content_model->update_status_pendana($indetail['Master_loan_id'], 'received');
									}

									// ----- pengembalian Saldo ke Pendana ------
									$list_pendana = $this->Content_model->get_pendanaan_byloan($transaksi_id);						

									foreach ($list_pendana as $dp) {
										$get_wallet_pendana = $this->Wallet_model->get_wallet_byuser($dp['User_id']);

										$tambah_saldo = $dp['jml_angsuran_ke_pendana'];

										// tambah saldo pendana
										$this->Wallet_model->update_master_wallet_saldo($dp['User_id'], $tambah_saldo);

										// wallet detail
										$upw_pendana['Id']               = $get_wallet_pendana['Id'];
										$upw_pendana['Date_transaction'] = $nowdate;
										$upw_pendana['Amount']           = $tambah_saldo;
										$upw_pendana['Notes']            = 'Pengembalian dana '.$dp['Id'].' oleh pinjaman No.'. $transaksi_id;
										$upw_pendana['tipe_dana']        = 1;
										$upw_pendana['User_id']          = $dp['User_id'];
										$upw_pendana['kode_transaksi']   = $indetail['Master_loan_id'];
										$upw_pendana['balance']          = $get_wallet_pendana['Amount'] + $upw_pendana['Amount'];
										$this->Wallet_model->insert_detail_wallet($upw_pendana);
									}
									// ----- End of pengembalian Saldo ke Pendana ------

									// update table mod_tempo. isi is_paid =1
									$uptempo['is_paid']     = 1;
									$uptempo['date_paid']   = $nowdatetime;
									$this->Content_model->update_table_tempo($indetail['Master_loan_id'], 1, $uptempo);
									
									$response['response'] = 'success';
				                    $response['status']   = REST_Controller::HTTP_OK;
				                    $response['content']  = '';
				                    $this->set_response($response, REST_Controller::HTTP_OK);						    		
								   	return;
								}else{
									$response = [
					            		'response' => 'fail',
						                'status'   => REST_Controller::HTTP_OK,
						                'content'  => '',
						                'message'  => 'Error Insert System',
						            ];
						    		$http_status = REST_Controller::HTTP_OK;
								}
							}else{
								$response = [
				            		'response' => 'fail',
					                'status'   => REST_Controller::HTTP_OK,
					                'content'  => '',
					                'message'  => 'Saldo Anda tidak mencukupi',
					            ];
					    		$http_status = REST_Controller::HTTP_OK;
							}
						}else{

							if (isset($check_if_exist['Master_loan_status'])) {
								$wording = 'Status Transaksi Anda '. $check_if_exist['Master_loan_status'];
							}else{
								$wording = 'Data tidak ditemukan';
							}
							$response = [
				            		'response' => 'fail',
					                'status'   => REST_Controller::HTTP_OK,
					                'content'  => '',
					                'message'  => $wording,
					            ];
					    		$http_status = REST_Controller::HTTP_OK;
						}

					}else{
						$response = [
		            		'response' => 'fail',
			                'status'   => REST_Controller::HTTP_OK,
			                'content'  => '',
			                'message'  => 'Nomor Transaksi dan Nominal pembayaran harus diisi.',
			            ];
			    		$http_status = REST_Controller::HTTP_OK;
					}

					
			        
			    }else{
			    	$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			    }

			}else{
				$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
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

	function submit_cicilan_mikro_get()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				$logintype = (int)antiInjection($token->logtype);

				if (!empty($uid) && is_numeric($logintype)) {

					$post = $this->input->get(NULL, TRUE);

					$lunas = 0;

					$transaksi_id = trim($post['transaksi_id']);
					$jml_bayar    = trim($post['jml_bayar']);

					if ( $transaksi_id != '' AND $jml_bayar != '' AND strlen($jml_bayar) > 4)
					{
						$nowdate     = date('Y-m-d');
						$nowdatetime = date('Y-m-d H:i:s');
						$get_master_wallet = $this->Wallet_model->get_wallet_bymember($uid);

						// Cek apakah saldo cukup
						if (count($get_master_wallet)>0 && isset($get_master_wallet['Id']) && $get_master_wallet['Amount'] >= $jml_bayar)
						{
							// hitung nomor cicilan ke berapa 
							$last_number   = $this->Content_model->get_nomor_angsuran($transaksi_id);
							$nomor_cicilan = $last_number['itotal'] + 1;

							// hitung total cicilan seluruhnya
							$getlog         = $this->Content_model->get_log_transaksi_pinjam($transaksi_id);
							$total_angsuran = $getlog['ltp_lama_angsuran'] * $getlog['ltp_jml_angsuran'];

							// ------ Insert cicilan ke table detail_profil_permohonan_pinjaman -----
							$indetail['Master_loan_id']   = antiInjection($transaksi_id);
							$indetail['Date_repaid']      = $nowdate;
							$indetail['Amount_repayment'] = antiInjection($jml_bayar);
							$indetail['Nomor_angsuran']   = $nomor_cicilan;

							$detail_id = $this->Content_model->insert_cicilan($indetail);

							if ($detail_id)
							{
								// update profil pinjaman tambah total_loan_repayment, kurangi total_loan_outstanding
								$this->Content_model->update_total_loan_repayment($indetail['Master_loan_id'], $uid, $indetail['Amount_repayment']);

								// master wallet -> kurangi saldo peminjam
								$this->Wallet_model->kurangi_saldo_wallet($uid, $jml_bayar);

								// detail transaksi wallet 
								$detail_w['Id']               = $get_master_wallet['Id'];
								$detail_w['Date_transaction'] = $nowdate;
								$detail_w['Amount']           = $jml_bayar;
								$detail_w['Notes']            = 'Pembayaran cicilan '.$nomor_cicilan.' - '. $indetail['Master_loan_id'];
								$detail_w['tipe_dana']        = 2;
								$detail_w['User_id']          = $get_master_wallet['User_id'];
								$detail_w['kode_transaksi']   = $indetail['Master_loan_id'];
								$detail_w['balance']          = $get_master_wallet['Amount'] - $detail_w['Amount'];
								$this->Wallet_model->insert_detail_wallet($detail_w);

								$get_data_pinjam = $this->Content_model->get_transaksi_pinjam_byid($transaksi_id); // get total yg sdh diangsur

								// cek apakah cicilan sudah seluruhnya dibayar
								if ($get_data_pinjam['Total_loan_repayment'] >= $total_angsuran)
								{
									// ------ status lunas, date close -------
									$this->Content_model->close_pinjaman($indetail['Master_loan_id']);
									$this->Content_model->update_status_pendana($indetail['Master_loan_id'], 'received');

									$lunas = 1;

									if ($getlog['ltp_frozen'] > 1)
									{
										// --------- frozen dicairkan -----------
										$get_master_2 = $this->Wallet_model->get_wallet_bymember($uid);

										// tambah saldo peminjam
										$this->Wallet_model->update_master_wallet_saldo($get_data_pinjam['Id_pengguna'], $getlog['ltp_frozen']);

										// wallet detail
										$sf['Id']               = $get_master_2['Id'];
										$sf['Date_transaction'] = $nowdate;
										$sf['Amount']           = $getlog['ltp_frozen'];
										$sf['Notes']            = 'Saldo Frozen Fee '. $transaksi_id;
										$sf['tipe_dana']        = 1;
										$sf['User_id']          = $get_data_pinjam['Id_pengguna'];
										$sf['kode_transaksi']   = $transaksi_id;
										$sf['balance']          = $get_master_2['Amount'] + $sf['Amount'];
										$this->Wallet_model->insert_detail_wallet($sf);

										// insert table log frozen
										$infr['frozen_amount'] = $getlog['ltp_frozen'];
										$infr['transaksi_id']  = $transaksi_id;
										$infr['Id_pengguna']   = $get_data_pinjam['Id_pengguna'];
										$infr['date']          = $nowdatetime;
										$this->Content_model->insert_log_frozen($infr);
									}
								}

								// update table mod_tempo. isi is_paid =1
								$uptempo['is_paid']     = 1;
								$uptempo['date_paid']   = $nowdatetime;
								$this->Content_model->update_table_tempo($indetail['Master_loan_id'], $nomor_cicilan, $uptempo);

								// ----- pengembalian Saldo ke Pendana ------
								$list_pendana = $this->Content_model->get_pendanaan_byloan($transaksi_id);

								foreach ($list_pendana as $dp) {
									
									$get_wallet_pendana = $this->Wallet_model->get_wallet_byuser($dp['User_id']);

									$tambah_saldo = $dp['jml_angsuran_ke_pendana'];

									// tambah saldo pendana
									$this->Wallet_model->update_master_wallet_saldo($dp['User_id'], $tambah_saldo);

									// wallet detail
									$upw_pendana['Id']               = $get_wallet_pendana['Id'];
									$upw_pendana['Date_transaction'] = $nowdate;
									$upw_pendana['Amount']           = $tambah_saldo;
									$upw_pendana['Notes']            = 'Pengembalian dana '.$nomor_cicilan.' - '.$dp['Id'].' oleh pinjaman No.'. $transaksi_id;
									$upw_pendana['tipe_dana']        = 1;
									$upw_pendana['User_id']          = $dp['User_id'];
									$upw_pendana['kode_transaksi']   = $indetail['Master_loan_id'];
									$upw_pendana['balance']          = $get_wallet_pendana['Amount'] + $upw_pendana['Amount'];
									$this->Wallet_model->insert_detail_wallet($upw_pendana);
									
									$this->send_email($dp['mum_email'], $dp['Id'], $upw_pendana['Amount'], $upw_pendana['Notes']);
									
								}
								// ----- End of pengembalian Saldo ke Pendana ------

								// ------ insert ke LO -------
								$this->pay_to_LO($getlog, $nomor_cicilan);
								
								$response['response'] = 'success';
			                    $response['status']   = REST_Controller::HTTP_OK;
			                    $response['content']  = '';
			                    $this->set_response($response, REST_Controller::HTTP_OK);						    		
							   	return;
							}
						}else{
							$response = [
			            		'response' => 'fail',
				                'status'   => REST_Controller::HTTP_OK,
				                'content'  => '',
				                'message'  => 'Saldo Anda tidak mencukupi',
				            ];
				            $http_status = REST_Controller::HTTP_OK;
						}

					}else{

						$response = [
		            		'response' => 'fail',
			                'status'   => REST_Controller::HTTP_OK,
			                'content'  => '',
			                'message'  => 'Jumlah pembayaran cicilan tidak sesuai.',
			            ];
			            $http_status = REST_Controller::HTTP_OK;
					}

				}else{
			    	$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			    }

			}else{
				$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
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

	function pay_to_LO($logpinjam, $nomor_cicilan)
	{
		if ( $logpinjam['ltp_loan_organizer_id'] !='' OR $logpinjam['ltp_loan_organizer_id'] !='0' )
		{
			// tambah saldo LO
			$this->Wallet_model->update_wallet_bylo($logpinjam['ltp_loan_organizer_id'], $logpinjam['ltp_LO_fee']);
			$master_wallet = $this->Wallet_model->get_wallet_bylo($logpinjam['ltp_loan_organizer_id']);
			
			// wallet detail
			$upw_pendana['Id']                = $master_wallet['Id'];
			$upw_pendana['Date_transaction']  = date('Y-m-d H:i:s');
			$upw_pendana['Amount']            = $logpinjam['ltp_LO_fee'];
			$upw_pendana['Notes']             = 'Pembayaran Cicilan '.$nomor_cicilan.' - '. $logpinjam['ltp_Master_loan_id'];
			$upw_pendana['tipe_dana']         = 1;
			$upw_pendana['User_id']           = 0;
			$upw_pendana['kode_transaksi']    = $logpinjam['ltp_Master_loan_id'];
			$upw_pendana['loan_organizer_id'] = $logpinjam['ltp_loan_organizer_id'];
			$upw_pendana['balance']           = $master_wallet['Amount'] + $upw_pendana['Amount'];
			$this->Wallet_model->insert_detail_wallet($upw_pendana);
		}
	}

	function send_email($email, $code, $jml, $notes)
	{
		$html_content = '
        Hai '.$email.',<br><br>

            Anda telah menerima pengembalian dana dengan rincian sebagai berikut:<br><br>
            No.Pendanaan : '.$code.' <br>
            Nominal : Rp '.number_format($jml).' <br>
            Remark : '.$notes.'
            
            <br><br>

            <span style="color:#858C93;">
            	Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke Email ini.
            	<br><br>

            	&copy; BKDana, '.date("Y").'. All rights reserved.
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
		$mail->Subject     = 'Pengembalian Dana';
		$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($html_content);	
		$mail->SMTPDebug   = 0;
        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }

        return TRUE;
	}

}
			