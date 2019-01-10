<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Pinjaman extends REST_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');
		$this->load->model('Member_model');

		error_reporting(E_ALL);
        ini_set('display_errors', '1');
	}

	function index_post(){
		echo 'kilat';
	}

	function pengajuan_kilat_post()
	{
		// ======= User has login: Formulir Pinjaman Kilat ======

		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				$logintype = (int)antiInjection($token->logtype);

				if (!empty($uid) && is_numeric($logintype)) {

					$memberID  = $uid;

					if ($logintype=='2') {	
						redirect('message/restrict_pendana');
						exit();
					}

					//$pinjaman_active = $this->Content_model->check_active_pinjaman($memberID);
					$pinjaman_active = '';

					if (count($pinjaman_active) > 1)
					{
						$response['response']  = 'fail';
		                $response['status']    = REST_Controller::HTTP_OK;
		                $response['message']   = 'Masih ada transaksi pinjaman yang belum selesai';
		                $response['content']   = '';
						$this->set_response($response, REST_Controller::HTTP_OK);
		                return;
					}else{

						$data['pinjaman'] = $this->Content_model->get_harga_pinjaman_kilat();
						$data['products'] = $this->Content_model->get_pinjaman_pengajuan(1); // type_off_business_id
						//$member           = $this->Member_model->data_member($uid);

						$response['response']      = 'success';
	                    $response['status']        = REST_Controller::HTTP_OK;
	                    $response['content']       = $data;
	                    //$response['data_product']  = $data;
	                    $this->set_response($response, REST_Controller::HTTP_OK);
					   	return;
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


	function submit_kilat1_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);
				$logintype = (int)antiInjection($token->logtype);
				//_d($_POST);exit();

				if (!empty($uid) && trim($uid) !='') {

						$memberdata = $this->Member_model->get_member_byid_less($uid);
						$id_pengguna = $memberdata['Id_pengguna'];

						$post = $this->input->post(NULL, TRUE);

						$nowdate     = date('Y-m-d');
						$nowdatetime = date('Y-m-d H:i:s');

						// update table user
						$indata_user['Tempat_lahir']  = trim($post['tempat_lahir']);
						$indata_user['Tanggal_lahir'] = date('Y-m-d', strtotime(trim($post['tanggal_lahir'])));
						$indata_user['Jenis_kelamin'] = trim($post['jenis_kelamin']);
						$indata_user['Id_ktp']        = trim($post['no_nik']);
						$indata_user['Pekerjaan']     = trim($post['pekerjaan']);

						$this->Content_model->update_user($uid, $indata_user);

						// update table user detail
						$indata_udetail['ID_No'] = $indata_user['Id_ktp'];
						$this->Content_model->update_userdetail($id_pengguna, $indata_udetail);
						
						// update table profil geografi
						$indata_geo['Alamat']   = trim($post['alamat']);
						$indata_geo['Kota']     = trim($post['kota']);
						$indata_geo['Provinsi'] = trim($post['provinsi']);
						$indata_geo['Kodepos']  = trim($post['kodepos']);

						$this->Content_model->update_profil_geografi($id_pengguna, $indata_geo);

						$response['response'] = 'success';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = '';
	                    $this->set_response($response, REST_Controller::HTTP_OK);
	                    return;
							
						
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
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			}
		

		$this->set_response($response, $http_status);
        return;
	}

	function submit_kilat2_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);
				$logintype = (int)antiInjection($token->logtype);

				if (!empty($uid) && trim($uid) !='') {

						$memberdata = $this->Member_model->get_member_byid_less($uid);
						$id_pengguna = $memberdata['Id_pengguna'];

						$post = $this->input->post(NULL, TRUE);

						$nowdate     = date('Y-m-d');
						$nowdatetime = date('Y-m-d H:i:s');

						// update table user
						$indata_user['Pendidikan']  = trim($post['pendidikan']);
						$this->Content_model->update_user($uid, $indata_user);

						// update table user detail
						$indata_udetail['company']                                  = trim($post['nama_perusahaan']);
						$indata_udetail['What_is_the_name_of_your_business']        = trim($post['nama_perusahaan']);
						$indata_udetail['How_many_years_have_you_been_in_business'] = trim($post['lama_bekerja']);
						$indata_udetail['Business_phone_no']                        = trim($post['telp_perusahaan']);
						$indata_udetail['status_karyawan']                          = trim($post['status_karyawan']);
						$indata_udetail['nama_atasan']                              = trim($post['nama_atasan']);
						$indata_udetail['referensi_orang_1']                        = trim($post['referensi_1']);
						$indata_udetail['referensi_orang_2']                        = trim($post['referensi_2']);
						$this->Content_model->update_userdetail($id_pengguna, $indata_udetail);
						
						$response['response'] = 'success';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = '';
	                    $this->set_response($response, REST_Controller::HTTP_OK);
	                    return;
							
						
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
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			}
		

		$this->set_response($response, $http_status);
        return;
	}

	function submit_kilat3_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);
				$logintype = (int)antiInjection($token->logtype);

				if (!empty($uid) && trim($uid) !='') {

						$memberdata = $this->Member_model->get_member_byid_less($uid);
						$id_pengguna = $memberdata['Id_pengguna'];

						$post = $this->input->post(NULL, TRUE);

						//_d($memberdata);
						// _d($_FILES);
						// _d($post); exit();

						$nowdate     = date('Y-m-d');
						$nowdatetime = date('Y-m-d H:i:s');

						if( isset($_FILES['foto_file']['name']) && $_FILES['foto_file']['name'] != ''){
							// ----- Process Image Name -----
							$img_info          = pathinfo($_FILES['foto_file']['name']);
							$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
							$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
							$fileExt           = $img_info['extension'];
							$file_foto_name   = $fileName.'.'.$fileExt;
							// ----- END Process Image Name -----
							$u_detail['images_foto_name'] = $file_foto_name;
						}else{
							$file_foto_name   = '';
						}

						if( isset($_FILES['nik_file']['name']) && $_FILES['nik_file']['name'] != ''){
							// ----- Process Image Name -----
							$img_info          = pathinfo($_FILES['nik_file']['name']);
							$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
							$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
							$fileExt           = $img_info['extension'];
							$file_ktp_name   = $fileName.'.'.$fileExt;
							// ----- END Process Image Name -----
							$u_detail['images_ktp_name']  = $file_ktp_name;
						}else{
							$file_ktp_name   = '';
						}

						if( isset($_FILES['foto_surat_ket_kerja']['name']) && $_FILES['foto_surat_ket_kerja']['name'] != ''){
							// ----- Process Image Name -----
							$img_info          = pathinfo($_FILES['foto_surat_ket_kerja']['name']);
							$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
							$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
							$fileExt           = $img_info['extension'];
							$file_surat_kerja_name   = $fileName.'.'.$fileExt;
							// ----- END Process Image Name -----
							$u_detail['images_surat_keterangan_kerja_name']  = $file_surat_kerja_name;
						}else{
							$file_surat_kerja_name   = '';
						}

						if( isset($_FILES['foto_slip_gaji']['name']) && $_FILES['foto_slip_gaji']['name'] != ''){
							// ----- Process Image Name -----
							$img_info          = pathinfo($_FILES['foto_slip_gaji']['name']);
							$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
							$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
							$fileExt           = $img_info['extension'];
							$file_slip_gaji_name   = $fileName.'.'.$fileExt;
							// ----- END Process Image Name -----
							$u_detail['images_slip_gaji_name']  = $file_slip_gaji_name;
						}else{
							$file_slip_gaji_name   = '';
						}

						if( isset($_FILES['foto_pegang_idcard']['name']) && $_FILES['foto_pegang_idcard']['name'] != ''){
							// ----- Process Image Name -----
							$img_info          = pathinfo($_FILES['foto_pegang_idcard']['name']);
							$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
							$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
							$fileExt           = $img_info['extension'];
							$file_foto_pegang_idcard_name   = $fileName.'.'.$fileExt;
							// ----- END Process Image Name -----
							$u_detail['images_with_idcard_name']  = $file_foto_pegang_idcard_name;
						}else{
							$file_foto_pegang_idcard_name   = '';
						}

						// ------------ Insert pinjaman ---------------//
						$total_pinjam = trim($post['jumlah_pinjaman']);
						$productID    = trim($post['product_id']); // tenor

						$prefixID    = 'PK-APP';
						$orderID     = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,12));
				        $exist_order = $this->Content_model->check_ordercode_pinjaman($orderID);	// Cek if order ID exist on Database
						
						// jika order ID sudah ada di Database, generate lagi tambahkan datetime
						if (is_array($exist_order) && count($exist_order) > 0 )
						{
							$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
						}

						// insert profil_permohonan_pinjaman
						$p_pinjam['Master_loan_id']               = $orderID;
						$p_pinjam['Tgl_permohonan_pinjaman']      = $nowdatetime;
						$p_pinjam['Jml_permohonan_pinjaman']      = $total_pinjam;
						$p_pinjam['User_id']                      = $id_pengguna;
						$p_pinjam['Product_id']                   = $productID;
						$p_pinjam['Master_loan_status']           = 'review';
						$p_pinjam['pinjam_member_id']             = $uid;
						$p_pinjam['jml_permohonan_pinjaman_awal'] = $p_pinjam['Jml_permohonan_pinjaman'];
						$p_pinjam['nama_peminjam']                = $memberdata['Nama_pengguna'];

						$pinjamID = $this->Content_model->insert_profil_pinjaman($p_pinjam);

						if ($pinjamID) {

							$produk = $this->Content_model->get_produk($productID);

							// insert Log Transaksi
							$inlog['ltp_Id_pengguna']              = $p_pinjam['User_id'];
							$inlog['ltp_Master_loan_id']           = $p_pinjam['Master_loan_id'];
							$inlog['ltp_total_pinjaman']           = $p_pinjam['Jml_permohonan_pinjaman'];
							$inlog['ltp_total_pinjaman_disetujui'] = 0;
							$inlog['ltp_admin_fee']                = 0;
							$inlog['ltp_bunga_pinjaman']           = 0;
							$inlog['ltp_jml_angsuran']             = 0;
							$inlog['ltp_lama_angsuran']            = 0;
							$inlog['ltp_tgl_jatuh_tempo']          = '0000-00-00';
							$inlog['ltp_platform_fee']             = 0;
							$inlog['ltp_lender_fee']               = 0;
							$inlog['ltp_product_title']            = $produk['product_title'];
							$inlog['ltp_product_id']               = $produk['Product_id'];
							$inlog['ltp_product_interest_rate']    = $produk['Interest_rate'];
							$inlog['ltp_product_loan_term']        = $produk['Loan_term'];
							$inlog['ltp_product_platform_rate']    = $produk['Platform_rate'];
							$inlog['ltp_product_loan_organizer']   = $produk['Loan_organizer'];
							$inlog['ltp_product_investor_return']  = $produk['Investor_return'];
							$inlog['ltp_product_revenue_share']    = $produk['Fee_revenue_share'];
							$inlog['ltp_product_secured_loan_fee'] = $produk['Secured_loan_fee'];
							$inlog['ltp_product_interest_rate_type'] = $produk['type_of_interest_rate'];				
							$inlog['ltp_product_pph']                = $produk['PPH'];				
							$inlog['ltp_type_of_business_id']        = $produk['type_of_business_id'];
							$inlog['ltp_loan_organizer_id']          = 1;
							$inlog['ltp_date_created']               = date('Y-m-d H:i:s');
							$this->Content_model->insert_log_transaksi_pinjam($inlog);

							// -> update table user
							$indata_user['Nomor_rekening']  = trim($post['nomor_rekening']);
							$this->Content_model->update_user($uid, $indata_user);

							// -> update table user detail
							$u_detail['average_monthly_salary']     = trim($post['gaji']);
							$u_detail['Jumlah_permohonan_pinjaman'] = $total_pinjam;
							$updated_udetail = $this->Content_model->update_userdetail($id_pengguna, $u_detail);
						}

						// ----- Destination Foto -----
						$destination_foto = $this->config->item('member_images_dir'). $uid."/foto/";
						$destination_ktp  = $this->config->item('member_images_dir'). $uid."/ktp/";
						$destination_surat_kerja = $this->config->item('member_images_dir'). $uid."/surat_kerja/";
						$destination_slip_gaji   = $this->config->item('member_images_dir'). $uid."/slip_gaji/";
						$destination_hold_idcard = $this->config->item('member_images_dir'). $uid."/hold_idcard/";

						// ----- Upload Foto -----
						if ($updated_udetail) {
							if($_FILES['foto_file']['name'] != ''){
								if (!is_file($destination_foto.$file_foto_name)) {
									mkdir_r($destination_foto);
								}

								unlink($destination_foto.$memberdata['images_foto_name']);
								move_uploaded_file($_FILES['foto_file']['tmp_name'], $destination_foto.$file_foto_name);		
							}

							if($_FILES['nik_file']['name'] != ''){
								if (!is_file($destination_ktp.$file_ktp_name)) {
									mkdir_r($destination_ktp);
								}
								unlink($destination_ktp.$memberdata['images_ktp_name']);
								move_uploaded_file($_FILES['nik_file']['tmp_name'], $destination_ktp.$file_ktp_name);		
							}

							if(isset($_FILES['foto_surat_ket_kerja']['name']) && $_FILES['foto_surat_ket_kerja']['name'] != ''){
								if (!is_file($destination_surat_kerja.$file_surat_kerja_name)) {
									mkdir_r($destination_surat_kerja);
								}
								unlink($destination_surat_kerja.$memberdata['images_surat_keterangan_kerja_name']);
								move_uploaded_file($_FILES['foto_surat_ket_kerja']['tmp_name'], $destination_surat_kerja.$file_surat_kerja_name);
							}

							if(isset($_FILES['foto_slip_gaji']['name']) && $_FILES['foto_slip_gaji']['name'] != ''){
								if (!is_file($destination_slip_gaji.$file_slip_gaji_name)) {
									mkdir_r($destination_slip_gaji);
								}
								unlink($destination_slip_gaji.$memberdata['images_slip_gaji_name']);
								move_uploaded_file($_FILES['foto_slip_gaji']['tmp_name'], $destination_slip_gaji.$file_slip_gaji_name);
							}

							if(isset($_FILES['foto_pegang_idcard']['name']) && $_FILES['foto_pegang_idcard']['name'] != ''){
								if (!is_file($destination_hold_idcard.$file_foto_pegang_idcard_name)) {
									mkdir_r($destination_hold_idcard);
								}
								unlink($destination_hold_idcard.$memberdata['images_with_idcard_name']);
								move_uploaded_file($_FILES['foto_pegang_idcard']['tmp_name'], $destination_hold_idcard.$file_foto_pegang_idcard_name);
							}
						}
						
						$response['response'] = 'success';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = '';
	                    $this->set_response($response, REST_Controller::HTTP_OK);
	                    return;
							
						
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
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			}
		

		$this->set_response($response, $http_status);
        return;
	}

	function submit_p_kilat_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				$logintype = (int)antiInjection($token->logtype);
				_d($_POST);

				if (!empty($uid) && trim($uid) !='') {

						$post = $this->input->post(NULL, TRUE);

						$nowdate     = date('Y-m-d');
						$nowdatetime = date('Y-m-d H:i:s');
						$password     = trim($post['password']);
						$total_pinjam = trim($post['jumlah_pinjaman']);
						$productID    = trim($post['product_id']);

						$member_data     = $this->Member_model->get_member_byid($uid);
						$stored_password = $member_data['mum_password'];

						$user_data = $this->Content_model->get_user($uid); // get data from table user

						if ( $password == '' ) {
							$response['response']  = 'fail';
			                $response['status']    = REST_Controller::HTTP_OK;
			                $response['message']   = 'Password harus diisi!';
			                $response['content']   = '';
							$this->set_response($response, REST_Controller::HTTP_OK);
			                return;

						}else if (!password_verify(base64_encode(hash('sha256', $password, true)), $stored_password)) {
							$response['response']  = 'fail';
			                $response['status']    = REST_Controller::HTTP_OK;
			                $response['message']   = 'Password Anda salah!';
			                $response['content']   = '';
							$this->set_response($response, REST_Controller::HTTP_OK);
			                return;

						}else if ( $total_pinjam != ''
									&& $productID != ''
									&& strlen($password) >= 6 
									&& (password_verify(base64_encode(hash('sha256', $password, true)), $stored_password)) ) 
						{

								$prefixID    = 'PK-';
								$orderID     = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,12));
						        $exist_order = $this->Content_model->check_ordercode_pinjaman($orderID);	// Cek if order ID exist on Database
								
								// jika order ID sudah ada di Database, generate lagi tambahkan datetime
								if (is_array($exist_order) && count($exist_order) > 0 )
								{
									$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
								}

								// profil_permohonan_pinjaman
								$p_pinjam['Master_loan_id']               = $orderID;
								$p_pinjam['Tgl_permohonan_pinjaman']      = $nowdatetime;
								$p_pinjam['Jml_permohonan_pinjaman']      = $total_pinjam;
								$p_pinjam['User_id']                      = $user_data['Id_pengguna'];
								$p_pinjam['Product_id']                   = $productID;
								$p_pinjam['Master_loan_status']           = 'review';
								$p_pinjam['pinjam_member_id']             = $uid;
								$p_pinjam['jml_permohonan_pinjaman_awal'] = $p_pinjam['Jml_permohonan_pinjaman'];
								$p_pinjam['nama_peminjam']                = $member_data['Nama_pengguna'];

								$pinjamID = $this->Content_model->insert_profil_pinjaman($p_pinjam);

								if ($pinjamID) {

									$produk = $this->Content_model->get_produk($productID);

									// Log Transaksi
									$inlog['ltp_Id_pengguna']              = $p_pinjam['User_id'];
									$inlog['ltp_Master_loan_id']           = $p_pinjam['Master_loan_id'];
									$inlog['ltp_total_pinjaman']           = $p_pinjam['Jml_permohonan_pinjaman'];
									$inlog['ltp_total_pinjaman_disetujui'] = 0;
									$inlog['ltp_admin_fee']                = 0;
									$inlog['ltp_bunga_pinjaman']           = 0;
									$inlog['ltp_jml_angsuran']             = 0;
									$inlog['ltp_lama_angsuran']            = 0;
									$inlog['ltp_tgl_jatuh_tempo']          = '0000-00-00';
									$inlog['ltp_platform_fee']             = 0;
									$inlog['ltp_lender_fee']               = 0;
									$inlog['ltp_product_title']            = $produk['product_title'];
									$inlog['ltp_product_id']               = $produk['Product_id'];
									$inlog['ltp_product_interest_rate']    = $produk['Interest_rate'];
									$inlog['ltp_product_loan_term']        = $produk['Loan_term'];
									$inlog['ltp_product_platform_rate']    = $produk['Platform_rate'];
									$inlog['ltp_product_loan_organizer']   = $produk['Loan_organizer'];
									$inlog['ltp_product_investor_return']  = $produk['Investor_return'];
									$inlog['ltp_product_revenue_share']    = $produk['Fee_revenue_share'];
									$inlog['ltp_product_secured_loan_fee'] = $produk['Secured_loan_fee'];
									$inlog['ltp_product_interest_rate_type'] = $produk['type_of_interest_rate'];				
									$inlog['ltp_product_pph']                = $produk['PPH'];				
									$inlog['ltp_type_of_business_id']        = $produk['type_of_business_id'];
									$inlog['ltp_loan_organizer_id']          = 1;
									$inlog['ltp_date_created']               = date('Y-m-d H:i:s');
									$this->Content_model->insert_log_transaksi_pinjam($inlog);
								}

								$response['response'] = 'success';
			                    $response['status']   = REST_Controller::HTTP_OK;
			                    $response['content']  = '';
			                    $this->set_response($response, REST_Controller::HTTP_OK);
			                    return;
							
						}else{
							$response = [
				            		'response' => 'fail',
					                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
					                'message'  => 'Semua kolom harus diisi',
					            ];
					            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
						    return;
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
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			}
		

		$this->set_response($response, $http_status);
        return;
	}
}