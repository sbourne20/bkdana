<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Collection extends REST_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');
		$this->load->model('Agent_model');

		error_reporting(E_ALL);
        ini_set('display_errors', '1');
	}

	public function data_borrower_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				if (!empty($uid)) {

					$post = $this->input->post(NULL, TRUE);

					$data['data_borrower'] = $this->Content_model->get_collection_approve($post['id_peminjam']);
					
					if(!empty($data['data_borrower'])){

						$response['response'] = 'success';
		                $response['status']   = REST_Controller::HTTP_OK;
		                $response['content']  = $data;
		                $this->set_response($response, REST_Controller::HTTP_OK);
		                return;

					}else{

						$response['response'] = 'fail';
		                $response['status']   = REST_Controller::HTTP_OK;
		                $response['content']  = 'Borrower tidak ditemukan';
		                $this->set_response($response, REST_Controller::HTTP_OK);
		                return;

	            	}

				}else{
			    	$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Agent Not Found',
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

	public function submit_collection_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				if (!empty($uid) && trim($uid) !='') {

					$post = $this->input->post(NULL, TRUE);

					$indata['id_agent']   = trim($post['id_agent']);
					$indata['id_peminjam']   = trim($post['id_peminjam']);
					$indata['master_loan_id'] = $post['master_loan_id'];
					$indata['product_title'] = $post['product_title'];

					$indata['nama']       = trim($post['nama']);
					$indata['no_ktp']  	  = trim($post['no_ktp']);

					$indata['hutang_pokok'] = trim($post['hutang_pokok']);
					$indata['jumlah_pembayaran'] = trim($post['jumlah_pembayaran']);
					$indata['sisa_hutang_pokok'] = $indata['hutang_pokok'] - $indata['jumlah_pembayaran'];
					$indata['collection_date'] = date('Y-m-d H:i:s');
					$indata['status'] = 0;

					if($indata['hutang_pokok'] < $indata['jumlah_pembayaran']){

						$response['response'] = 'fail';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = 'Masukan Jumlah Pembayaran yang Benar';
	                    $this->set_response($response, REST_Controller::HTTP_OK);
	                    return;

	                }else{

						if($indata['id_peminjam'] == $post['borrower_code']){

							$this->Content_model->insert_mod_agent_collection($indata);
							
							$response['response'] = 'success';
		                    $response['status']   = REST_Controller::HTTP_OK;
		                    $response['content']  = 'Penagihan Berhasil Dibuat';
		                    $this->set_response($response, REST_Controller::HTTP_OK);
		                    return;
	                	
	                	}else{

	                		$response['response'] = 'fail';
		                    $response['status']   = REST_Controller::HTTP_OK;
		                    $response['content']  = 'Barcode dan Borrower Code Tidak Cocok';
		                    $this->set_response($response, REST_Controller::HTTP_OK);
		                    return;

	                	}
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

	public function submit_collection2_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				if (!empty($uid) && trim($uid) !='') {

					$post = $this->input->post(NULL, TRUE);

					$indata['user_id']   = trim($post['user_id']);
					$indata['id_mod_agent']   = trim($post['id_mod_agent']);
					$indata['master_loan_id'] = $post['master_loan_id'];
					
					$indata['jml_tagihan'] = trim($post['jml_tagihan']);
					$indata['sisa_tagihan'] = trim($post['sisa_tagihan']);
					$indata['tgl_collection'] = date('Y-m-d H:i:s');

						if($indata['user_id'] == $post['borrower_code']){

							if($indata['sisa_tagihan'] == 0){
								$cek_sisa_tagihan = $this->Content_model->get_log_transaksi_pinjam($post['master_loan_id']);
								$indata['sisa_tagihan'] = $cek_sisa_tagihan['ltp_total_pinjaman_disetujui'] - $indata['jml_tagihan'];
							}else{
								$cek_sisa_tagihan = $this->Content_model->get_mod_agent_collection_bymaster_loanid($post['master_loan_id']);
								$indata['sisa_tagihan'] = $indata['jml_tagihan'] - $cek_sisa_tagihan['sisa_tagihan'];
							}
							// print_r($indata['sisa_tagihan']);
							
							$this->Content_model->insert_mod_agent_collection2($indata);
							
							$response['response'] = 'success';
		                    $response['status']   = REST_Controller::HTTP_OK;
		                    $response['content']  = 'Penagihan Berhasil Dibuat';
		                    $this->set_response($response, REST_Controller::HTTP_OK);
		                    return;
	                	
	                	}else{

	                		$response['response'] = 'fail';
		                    $response['status']   = REST_Controller::HTTP_OK;
		                    $response['content']  = 'Barcode dan Borrower Code Tidak Cocok';
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
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			}
		

		$this->set_response($response, $http_status);
        return;
	}

	public function list_mycollection_get()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);				

				if (!empty($uid)) {

					//$id_agent = trim($post['id_agent']);
					$id_agent = $this->input->get('id_mod_agent');
					$limit_per_page = (int)$this->input->get('limit', TRUE);
			        $start_index    = (int)$this->input->get('page', TRUE);

					$data['list_mycollection'] = $this->Content_model->get_mod_agent_collection($id_agent, $start_index, $limit_per_page);

					$response['response'] = 'success';
	                $response['status']   = REST_Controller::HTTP_OK;
	                $response['content']  = $data;
	                $this->set_response($response, REST_Controller::HTTP_OK);
	                return;

				}else{
			    	$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Agent Not Found',
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

	public function details_collection_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);				

				if (!empty($uid)) {

					$post = $this->input->post(NULL, TRUE);

					$data['summary_penagihan'] = $this->Content_model->set_collection($post['Master_loan_id']);
					$data['catatan_penagihan'] = $this->Content_model->set_collection_list($post['Master_loan_id']);

					$response['response'] = 'success';
	                $response['status']   = REST_Controller::HTTP_OK;
	                $response['content']  = $data;
	                $this->set_response($response, REST_Controller::HTTP_OK);
	                return;

				}else{
			    	$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Agent Not Found',
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


}