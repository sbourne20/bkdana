<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Pendanaan extends REST_Controller {

	/* Submit pendanaan */

	function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');
		$this->load->model('Member_model');
		$this->load->model('Wallet_model');
		$this->load->model('Pendanaan_model');

		include(APPPATH.'libraries/phpmailer-5.2.23/PHPMailerAutoload.php');
		include(APPPATH.'libraries/TCPDF/tcpdf.php');

		error_reporting(E_ALL);
        ini_set('display_errors', '1');

        ini_set('max_execution_time', 600);
	}

	function submit_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);
				$logintype = (int)antiInjection($token->logtype);

				if (!empty($uid) && $logintype =='2' ) {

					$post = $this->input->post(NULL, TRUE);

					$kode_pinjaman = trim($post['transaksi_id']);
					$jml_pendanaan = trim($post['nominal_pendanaan']);

					$pdf_folder = $this->config->item('attach_dir');

					if ( $kode_pinjaman!='' && $jml_pendanaan!='' && strlen($kode_pinjaman) > 1 )
					{

						/*
						[transaksi_id] => PK-593A4533E5AF03
					    [jml_pinjaman] => 1000000
					    [jml_pinj_disetujui] => 900000
					    [id_peminjam] => 59
					    [id_peminjam_member] => 59
					    [jml_kredit] => 0
					    [jml_kekurangan] => 1000000
					    [jml_pendanaan] => 1,000,000.00
						*/

						$memdata = $this->Member_model->get_member_byid($uid);
						$data_pinjaman = $this->Pendanaan_model->get_pinjaman_detail($kode_pinjaman);
						
						// Hanya jika status approve
						if ($data_pinjaman['Master_loan_status'] == 'approve') {

							$jmldana               = $jml_pendanaan;
							$jmlpinjaman           = $data_pinjaman['Jml_permohonan_pinjaman'];
							$jmlpinjaman_disetujui = $data_pinjaman['Jml_permohonan_pinjaman_disetujui'];
							$jml_kredit_pinjaman   = $data_pinjaman['jml_kredit'];

							$ID_peminjam  = $data_pinjaman['User_id'];
							$mid_peminjam = $data_pinjaman['pinjam_member_id'];

							$nowdate     = date('Y-m-d');
							$nowdatetime = date('Y-m-d H:i:s');

							if (empty($data_pinjaman['jml_kredit'])) {
		                        $jml_kurang_pinjaman = $jmlpinjaman;
		                    }else{
		                        $jml_kurang_pinjaman = $jmlpinjaman - $data_pinjaman['jml_kredit'];
		                    }

							$get_master_wallet = $this->Wallet_model->get_wallet_bymember($uid);

							if (count($get_master_wallet) < 1) 
							{
								$response['response']      = 'fail';
						        $response['status']        = REST_Controller::HTTP_OK;
						        $response['message']       = 'Proses Pembiayaan gagal. Saldo Anda Rp 0.';
						        $this->set_response($response, REST_Controller::HTTP_OK);
							   	return;

							}else if ( isset($get_master_wallet['Amount'] ) && $get_master_wallet['Amount'] < $jmldana)
							{						

								$response['response']      = 'fail';
						        $response['status']        = REST_Controller::HTTP_OK;
						        $response['message']       = 'Proses Pembiayaan gagal. Total pembiayaan tidak boleh melebihi total Saldo Anda.';
						        $this->set_response($response, REST_Controller::HTTP_OK);
							   	return;

							}else if ($jml_kredit_pinjaman != 0 && $jmldana > $jml_kurang_pinjaman) 
							{

								$response['response']      = 'fail';
						        $response['status']        = REST_Controller::HTTP_OK;
						        $response['message']       = 'Proses Pembiayaan gagal. Total pembiayaan tidak boleh melebihi total tagihan. Jumlah kurang pendanaan Rp '.$jml_kurang_pinjaman;
						        $this->set_response($response, REST_Controller::HTTP_OK);
							   	return;

							}else if ($jmldana < 100000) {

								$response['response']      = 'fail';
						        $response['status']        = REST_Controller::HTTP_OK;
						        $response['message']       = 'Proses Pembiayaan gagal. Jumlah Pembiayaan minimal Rp 100,000.';
						        $this->set_response($response, REST_Controller::HTTP_OK);
							   	return;
							
							}else if ($jmldana > $jmlpinjaman) {

								$response['response']      = 'fail';
						        $response['status']        = REST_Controller::HTTP_OK;
						        $response['message']       = 'Proses Pembiayaan gagal. Jumlah Pembiayaan tidak boleh melebihi jumlah tagihan.';
						        $this->set_response($response, REST_Controller::HTTP_OK);
							   	return;

							}else if ($jmldana != '' && strlen($jmldana) > 5 && $jmldana >= 100000 ) {
								
								$log_tran_pinjam = $this->Content_model->get_log_transaksi_pinjam($kode_pinjaman);
								$loan_term       = $log_tran_pinjam['ltp_product_loan_term'];

								// ------------ hitung admin fee, bunga ----------------
								$hitung_tax = 0;

								if ($log_tran_pinjam['ltp_type_of_business_id'] == '1')
								{
									// KILAT
									$laba                = (($jmldana * $log_tran_pinjam['ltp_product_investor_return'])/100) * $log_tran_pinjam['ltp_product_loan_term'];
									$angsuran_ke_pendana = ($jmldana + $laba)/$log_tran_pinjam['ltp_lama_angsuran'];

									$pph = $log_tran_pinjam['ltp_product_pph'];
									$lender_fee          = $laba;
									$cicilan_pokok       = $jmldana;
									$total_pendapatan_pendana = $angsuran_ke_pendana;

									// hitung TAX -> PPH
									if ($log_tran_pinjam['ltp_product_pph'] != '0')
									{
										//$hitung_tax = ($total_pendapatan_pendana * $log_tran_pinjam['ltp_product_pph'])/100;
										$hitung_tax = ($laba * $log_tran_pinjam['ltp_product_pph'])/100;
										$angsuran_ke_pendana      = $angsuran_ke_pendana-$hitung_tax;
										$total_pendapatan_pendana = $angsuran_ke_pendana;
									}

								}else if ($log_tran_pinjam['ltp_type_of_business_id'] == '3') {
									// MIKRO
									// angsuran ke pendana per minggu
									$cicilan_pokok = $jmldana/$log_tran_pinjam['ltp_lama_angsuran'];
									// lender fee per minggu
									$lender_fee    = (($jmldana*$log_tran_pinjam['ltp_product_investor_return'] * $log_tran_pinjam['ltp_product_loan_term'])/100) /$log_tran_pinjam['ltp_lama_angsuran'];
									$platform_fee  = (($jmldana * $log_tran_pinjam['ltp_product_platform_rate'] * $log_tran_pinjam['ltp_product_loan_term'])/100)/$log_tran_pinjam['ltp_lama_angsuran'];
									$LO_fee        = (($jmldana * $log_tran_pinjam['ltp_product_loan_organizer'] * $log_tran_pinjam['ltp_product_loan_term'])/100)/$log_tran_pinjam['ltp_lama_angsuran'];
									
									$angsuran_ke_pendana = round($cicilan_pokok) + $lender_fee;
									$laba                = ($angsuran_ke_pendana * $log_tran_pinjam['ltp_lama_angsuran']) - $jmldana;
									$total_pendapatan_pendana = $jmldana + ($lender_fee*$log_tran_pinjam['ltp_lama_angsuran']);

									// hitung TAX -> PPH
									if ($log_tran_pinjam['ltp_product_pph'] != '0')
									{
										//$hitung_tax = ($total_pendapatan_pendana * $log_tran_pinjam['ltp_product_pph'])/100;
										$hitung_tax = ($laba * $log_tran_pinjam['ltp_product_pph'])/100;
										$angsuran_ke_pendana      = $angsuran_ke_pendana - ($hitung_tax/$log_tran_pinjam['ltp_lama_angsuran']);
										$total_pendapatan_pendana = $total_pendapatan_pendana - $hitung_tax;
									}
								}

									/*echo 'jmldana: '. ($jmldana);
									echo '<br>';
									echo 'laba: '. ($laba);
									echo '<br>';
									echo 'ltp_lama_angsuran: '. ($log_tran_pinjam['ltp_lama_angsuran']);
									echo '<br>';
									echo 'angsuran_ke_pendana: '. ($angsuran_ke_pendana);
									echo '<br>';
									echo 'total_pendapatan_pendana: '. ($total_pendapatan_pendana);
									echo '<br>';
									echo 'hitung_tax: '. $hitung_tax;
									echo '<br>';
								exit();*/

								$prefixID    = 'DD-APPS-';
								$orderID     = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,12));
						        $exist_order = $this->Content_model->check_ordercode_transaksi_pendanaan($orderID);	// Cek if order ID exist on Database
								
								// jika order ID sudah ada di Database, generate lagi tambahkan datetime
								if (is_array($exist_order) && count($exist_order) > 0 )
								{
									$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
								}

								$status_pendanaan = ($jmldana == $jmlpinjaman)? 'approve' : 'pending';

								// insert profil penawaran pemberian pinjaman
								$tbl_penawaran['Id']                                         = $orderID;
								$tbl_penawaran['Tgl_penawaran_pemberian_pinjaman']           = $nowdate;
								$tbl_penawaran['Jml_penawaran_pemberian_pinjaman']           = $jmldana;
								$tbl_penawaran['Jml_penawaran_pemberian_pinjaman_disetujui'] = $jmldana;
								$tbl_penawaran['Date_create']      = $nowdatetime;
								$tbl_penawaran['Master_loan_id']   = $kode_pinjaman;
								$tbl_penawaran['User_id']          = $memdata['Id_pengguna'];
								$tbl_penawaran['dana_member_id']   = $uid;
								$tbl_penawaran['Product_id']       = 4;
								$tbl_penawaran['pendanaan_status'] = $status_pendanaan;
								$tbl_penawaran['jml_laba']         = $laba;
								$tbl_penawaran['nama_pendana']     = $memdata['Nama_pengguna'];

								$insertMaster = $this->Content_model->insert_profil_pembiayaan($tbl_penawaran);

								if ($insertMaster)
								{
									// table detail_ profile_penawaran_pemberian_pinjaman
									$tbl_detail['Date_create']  = $nowdate;
									$tbl_detail['Investor_id']  = $memdata['Id_pengguna'];
									$tbl_detail['Amount']       = $jmldana;
									$tbl_detail['transaksi_id'] = $orderID;
									$insertDetail = $this->Content_model->insert_detail_profil_pembiayaan($tbl_detail);

									// --------- kurangi saldo pendana => master wallet --------------
									$this->Wallet_model->kurangi_saldo_wallet($uid, $jmldana);

									// detail transaksi wallet pendana
									$detail_w['Id']               = $get_master_wallet['Id'];
									$detail_w['Date_transaction'] = $nowdate;
									$detail_w['Amount']           = $jmldana;
									$detail_w['Notes']            = 'Pembiayaan pinjaman No.'.$tbl_penawaran['Master_loan_id'];
									$detail_w['tipe_dana']        = 2;
									$detail_w['User_id']          = $tbl_penawaran['User_id'];
									$detail_w['kode_transaksi']   = $orderID;
									$detail_w['balance']          = $get_master_wallet['Amount'] - $detail_w['Amount'];
									$this->Wallet_model->insert_detail_wallet($detail_w);
									// ---------- End kurangi saldo pendana ------------------

									// tambah kredit peminjam
									$this->Content_model->tambah_kredit_peminjam($tbl_penawaran['Master_loan_id'], $jmldana);

									// ----------  insert table log transaksi pendana ---------
									$inlogpendana['Master_loan_id']          = $tbl_penawaran['Master_loan_id'];
									$inlogpendana['Id_pendanaan']            = $orderID;
									$inlogpendana['nama_pendana']            = $memdata['Nama_pengguna'];
									$inlogpendana['jml_pendanaan']           = $jmldana;
									$inlogpendana['cicilan_pokok']           = $cicilan_pokok;
									$inlogpendana['lender_fee']              = $lender_fee;
									$inlogpendana['jml_angsuran_ke_pendana'] = $angsuran_ke_pendana;
									$inlogpendana['date_created']            = date('Y-m-d H:i:s');
									$inlogpendana['angsuran_count']          = $log_tran_pinjam['ltp_lama_angsuran'];
									$inlogpendana['total_pendapatan']        = $total_pendapatan_pendana;
									$inlogpendana['total_pajak']             = $hitung_tax;
									$this->Content_model->insert_log_transaksi_pendana($inlogpendana);
									// ----------  End of insert table log transaksi pendana ---------

									// cek jika jml pendanaan = jml pinjaman, maka masuk saldo peminjam
									// ----- Pendanaan sudah terpenuhi 100 % -----
									if ($jmldana == $jmlpinjaman OR $jml_kurang_pinjaman == $jmldana)
									{
										$check_wallet_peminjam = $this->Wallet_model->get_wallet_bymember($mid_peminjam);

										if ( is_array($check_wallet_peminjam) && count($check_wallet_peminjam)>0 )
										{
											// update saldo peminjam
											$this->Wallet_model->update_master_wallet_saldo($ID_peminjam, $jmlpinjaman_disetujui);
											$id_masterwallet_peminjam = $check_wallet_peminjam['Id'];
										}else{
											// insert saldo peminjam
											$inmwallet['Date_create']      = $nowdate;
											$inmwallet['User_id']          = $ID_peminjam;
											$inmwallet['Amount']           = $jmlpinjaman_disetujui;
											$inmwallet['wallet_member_id'] = $mid_peminjam;

											$id_masterwallet_peminjam = $this->Wallet_model->insert_master_wallet($inmwallet);
										}

										// Insert Detail wallet peminjam
										$dwp['Id']               = $id_masterwallet_peminjam;
										$dwp['Date_transaction'] = $nowdatetime;
										$dwp['Amount']           = $jmlpinjaman_disetujui;
										$dwp['Notes']            = 'Pemberian dana pinjaman No.'.$tbl_penawaran['Master_loan_id'];
										$dwp['tipe_dana']        = 1;
										$dwp['User_id']          = $ID_peminjam;
										$dwp['kode_transaksi']   = $tbl_penawaran['Master_loan_id'];
										$dwp['balance']          = $check_wallet_peminjam['Amount'] + $dwp['Amount'];
										$this->Wallet_model->insert_detail_wallet($dwp);

										$memberpinjam = $this->Content_model->get_pinjaman_member($tbl_penawaran['Master_loan_id']);

										// --------- Create Tgl Jatuh Tempo -> Insert ke table Mod_Tempo ---------
										if ($log_tran_pinjam['ltp_type_of_business_id'] == '1')
										{
											// Kilat
											
											$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$loan_term." days"));

											$intempo['kode_transaksi']  = $tbl_penawaran['Master_loan_id'];
											$intempo['tgl_jatuh_tempo'] = $tgl_jatuh_tempo;
											$intempo['no_angsuran']     = 1;
											$this->Content_model->insert_table_tempo($intempo);

											$output['list_pendana'] = $this->Content_model->get_list_pendana($tbl_penawaran['Master_loan_id']);

											$output['member']   = $memberpinjam;
											$output['tgl']      = parseDateTimeIndex(date('Y-m-d'));
											$output['jml_hari'] = $loan_term;

											$html               = $this->load->view('email/vpinjaman-kilat', $output, TRUE);
											
											$filename = 'perjanjian-pinjaman-kilat-'.$tbl_penawaran['Master_loan_id'].'.pdf';
											$title    = 'Perjanjian Pinjaman Kilat '.$tbl_penawaran['Master_loan_id'];
											$label_transaksi = 'Pinjaman Kilat';

										}else{
											// Mikro
											for ($i=1; $i <= $log_tran_pinjam['ltp_lama_angsuran']; $i++) { 
												
												$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$i." week"));

												$intempo['kode_transaksi']  = $tbl_penawaran['Master_loan_id'];
												$intempo['tgl_jatuh_tempo'] = $tgl_jatuh_tempo;
												$intempo['no_angsuran']     = $i;
												$this->Content_model->insert_table_tempo($intempo);
											}

											$output['list_pendana'] = $this->Content_model->get_list_pendana($tbl_penawaran['Master_loan_id']);
											
											$output['member']    = $memberpinjam;
											$output['tgl']       = parseDateTimeIndex(date('Y-m-d'));
											$output['tgl_order'] = date('d/m/Y', strtotime($memberpinjam['Tgl_permohonan_pinjaman']));
											$output['jml_hari'] = $loan_term*30;

											$html                = $this->load->view('email/vpinjaman-mikro', $output, TRUE);
											
											$filename = 'perjanjian-pinjaman-mikro-'.$tbl_penawaran['Master_loan_id'].'.pdf';
											$title    = 'Perjanjian Pinjaman Mikro '.$tbl_penawaran['Master_loan_id'];
											$label_transaksi = 'Pinjaman Mikro';
										}

										// update status pinjaman mjd approve
										$this->Content_model->approve_pinjaman_bycode($tbl_penawaran['Master_loan_id']);
										// update semua pendana status mjd approve
										$this->Content_model->approve_transaksi_pendana_bypinjaman($tbl_penawaran['Master_loan_id']);

										// --------- Generate PDF Peminjam  ----------
										$attach_file = $this->create_pdf($html, $tbl_penawaran['Master_loan_id'], $filename, $title);
										$this->send_mail_peminjam($memberpinjam, $label_transaksi, $attach_file);
										unlink($pdf_folder.$filename);
										// --------- Generate PDF Peminjam  ----------

										unset($output);
										// --------- Generate PDF Pendana ----------
										$output['member']    = $memdata;
										$output['ordercode'] = $orderID;
										$output['tgl_order'] = date('d/m/Y');
										$html = $this->load->view('email/vpendana', $output, TRUE);

										$filename = 'perjanjian-pendana-'.$orderID.'.pdf';
										$title    = 'Perjanjian Pendana '.$orderID;
										$attach_file = $this->create_pdf($html, $output['ordercode'], $filename, $title);
										// --------- End Generate PDF ----------
										
										$this->send_email($memdata['mum_email'], $orderID, $jmldana, $attach_file);
										unlink($pdf_folder.$filename);
									}
									// ------ End Pendanaan 100% -------

									$data['no_transaksi_pendanaan'] = $orderID;

									$response['response']      = 'success';
							        $response['status']        = REST_Controller::HTTP_OK;
							        $response['content']       = $data;
							        $response['message']       = 'Pendanaan Berhasil';
							        $this->set_response($response, REST_Controller::HTTP_OK);
								   	return;

								}else{
									$response = [
					            		'response' => 'fail',
						                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
						                'message'  => 'Error on inserting data',
						            ];
						            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
								}
							}else{
								$response = [
				            		'response' => 'fail',
					                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
					                'message'  => 'Error funding process',
					            ];
					            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
							}
						}else{
							$response = [
				            		'response' => 'fail',
					                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
					                'message'  => 'Transaksi belum approve atau sudah lunas',
					            ];
					            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
						}
					}else{
				    	$response = [
		            		'response' => 'fail',
			                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
			                'message'  => 'Transaksi id dan nominal pendanaan tidak boleh kosong',
			            ];
			            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
				    }
				}else{
			    	$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Wrong member type',
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

	function send_email($email, $code, $jml, $file)
	{
		$html_content = '
        Hai '.$email.',<br><br>

            Penawaran Pendanaan Anda telah disetujui.<br><br>
            No.Pendanaan : '.$code.' <br>
            Nominal : Rp '.number_format($jml).'
            
            <br><br>

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
		$mail->Subject     = 'Penawaran Pendanaan pinjaman disetujui';
		$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($html_content);	
		$mail->SMTPDebug   = 0;
		/*$mail->addAttachment($file['output_file'], $file['filename']);*/
        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }	

        return TRUE;
	}

	function send_mail_peminjam($userdata, $type, $file)
	{
		$subject_title = 'Pinjaman '.$userdata['Master_loan_id'].' disetujui dan mendapat pendanaan';

		$html_content = '
        Hai '.$userdata['Nama_pengguna'].',<br><br>

            Pengajuan Pinjaman Anda di BKDana.com telah disetujui dan sudah mendapat Pendanaan.
            <br><br>
            
            Berikut detail pinjaman Anda:<br><br>

            No. Transaksi: '.$userdata['Master_loan_id'].'
            <br>
            Jenis Transaksi: '.$type.'
            <br>
            Jumlah Pinjaman: Rp '.number_format($userdata['Jml_permohonan_pinjaman']).'
            <br>
            Jumlah Pinjaman diterima: Rp '.number_format($userdata['Jml_permohonan_pinjaman_disetujui']).'
            <br>
            Status: <strong>Sudah mendapat pendanaan</strong>
            <br><br>

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
		$mail->AddAddress($userdata['mum_email']);
		$mail->Subject     = $subject_title;
		$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($html_content);	
		$mail->SMTPDebug   = 0;

		/* Update by desta */
		// Attachments
	    $mail->addAttachment($file['output_file'], $file['filename']);

        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }	

        return TRUE;
	
	}

	function create_pdf($html, $ordercode, $filename, $title)
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('BKDana');
		$pdf->SetTitle($title);
		$pdf->SetSubject($title);

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

		$pdf->SetMargins(12, 15, 16,true);

		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		    require_once(dirname(__FILE__).'/lang/eng.php');
		    $pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);

		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont('helvetica', '', 9);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		// set text shadow effect
		//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

		ob_start();
	    $pdf->writeHTML($html, true, false, false, false, '');
	    ob_end_clean();
		// ---------------------------------------------------------

		$output_file = $this->config->item('attach_dir') . $filename;

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		//$pdf->Output($ordercode. '.pdf', 'I');
		$pdf->Output($output_file,'F');

		$ret = array(
				'filename'    => $filename,
				'output_file' =>$output_file
				);

		return $ret;
	}
}