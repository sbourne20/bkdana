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

	function index(){
	}

	function kilat_post()
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

					$pinjaman_active = $this->Content_model->check_active_pinjaman($memberID);

					if (count($pinjaman_active) > 1)
					{
						$response['response']  = 'fail';
		                $response['status']    = REST_Controller::HTTP_OK;
		                $response['message']   = 'Masih ada transaksi pinjaman yang belum selesai';
		                $response['content']   = '';
						$this->set_response($response, REST_Controller::HTTP_OK);
		                return;
					}else{

						$data['pinjaman']      = $this->Content_model->get_harga_pinjaman_kilat();
						$data['products']   = $this->Content_model->get_pinjaman_pengajuan(1); // type_off_business_id

						//_d($data['harga']);
						//_d($data['products']);

						$response['response'] = 'success';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = $data;
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