<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Redeem extends REST_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');
		$this->load->model('Wallet_model');
		$this->load->model('Member_model');

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

			        if (empty($start_index)) {
			        	$start_index = 0;
			        }

					$data['list_redeem'] = $this->Content_model->get_list_myredeem($uid, $start_index, $limit_per_page);

					if (count($data['list_redeem']) > 0) {

			    		$response['response'] = 'success';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = $data['list_redeem'];
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

					$nowdate     = date('Y-m-d');
					$nowdatetime = date('Y-m-d H:i:s');
					$no_rekening = trim($post['nomor_rekening']);
					$bank_name   = trim($post['nama_bank']);
					$jml_redeem  = trim($post['jml_redeem']);
					$total_redeem = $jml_redeem;

					$walletdata          = $this->Wallet_model->get_wallet_bymember($uid);
					$memberdata          = $this->Member_model->get_member_byid_less($uid);
					$check_existing_red  = $this->Content_model->check_pending_redeem($uid);

					if ($check_existing_red['itotal'] >= 1)
					{
						$response['response'] = 'fail';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = '';
	                    $response['message']  = 'Pengajuan Redeem gagal.Transaksi Tarik Tunai Anda sebelumnya belum selesai.';
	                    $this->set_response($response, REST_Controller::HTTP_OK);
	                    return;
					}

					if ($total_redeem < 100000)
					{
						$response['response'] = 'fail';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = '';
	                    $response['message']  = 'Pengajuan Redeem Gagal. Jumlah minimum adalah Rp 100,000';
	                    $this->set_response($response, REST_Controller::HTTP_OK);
	                    return;
					}

					if ( $no_rekening != '' && $bank_name != '' && $jml_redeem != '' && strlen($jml_redeem)> 5)
					{
						// cek saldo
						if ( is_array($memberdata) && is_array($walletdata) && isset($walletdata['Amount']) > 0 && $walletdata['Amount'] >= $total_redeem)
						{
							$prefixID    = 'RDM-';
							$orderID     = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,10));
					        $exist_order = $this->Content_model->check_topup_code($orderID);	// Cek if order ID exist on Database
							
							// jika order ID sudah ada di Database, generate lagi tambahkan datetime
							if (is_array($exist_order) && count($exist_order) > 0 )
							{
								$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
							}

							$inred['redeem_kode']           = $orderID;
							$inred['redeem_amount']         = antiInjection($total_redeem);
							$inred['redeem_nomor_rekening'] = antiInjection($no_rekening);
							$inred['redeem_nama_bank']      = antiInjection($bank_name);
							$inred['redeem_date']           = $nowdatetime;
							$inred['redeem_status']         = 'pending';
							$inred['redeem_member_id']      = $uid;
							$inred['redeem_id_pengguna']    = $memberdata['Id_pengguna'];

							$inserted = $this->Content_model->insert_redeem($inred);

							if ($inserted) {
								$response['response'] = 'success';
			                    $response['status']   = REST_Controller::HTTP_OK;
			                    $response['content']  = '';
			                    $response['message']  = 'Pengajuan Redeem Berhasil';
			                    $this->set_response($response, REST_Controller::HTTP_OK);
			                    return;
							}else{
								$response['response'] = 'fail';
			                    $response['status']   = REST_Controller::HTTP_OK;
			                    $response['content']  = '';
			                    $response['message']  = 'Gagal Insert ke Database';
			                    $this->set_response($response, REST_Controller::HTTP_OK);
			                    return;
							}
						}else{
							$response['response'] = 'fail';
		                    $response['status']   = REST_Controller::HTTP_OK;
		                    $response['content']  = '';
		                    $response['message']  = 'Pengajuan Redeem Gagal. Jumlah redeem tidak boleh melebihi batas Saldo.';
		                    $this->set_response($response, REST_Controller::HTTP_OK);
		                    return;
						}
					}else{
						$response['response'] = 'fail';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = '';
	                    $response['message']  = 'Form tidak lengkap';
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
}