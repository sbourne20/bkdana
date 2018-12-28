<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Transaksi_peminjam extends REST_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');

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
					

					$data['list_transaksi'] = $this->Content_model->all_transactions_pinjam();

					//_d($data['list_transaksi']);

					if (count($data['list_transaksi']) > 0) {

						$json_format = json_encode((array)$data);

			    		// echo '{ "response": "success", "status" : '.REST_Controller::HTTP_OK.' , "content":' . $json_format. ' }';
			    		$response['response'] = 'success';
	                    $response['status'] = REST_Controller::HTTP_OK;
	                    $response['list_transaksi']   = $data['list_transaksi'];
	                    $this->set_response($response, REST_Controller::HTTP_OK);
			    		// $this->output->set_status_header(200);
			    		// $this->output->set_content_type('application/json', 'utf-8');
					   	return;

			    	}else{

			    		$response = [
		            		'response' => 'fail',
			                'status'   => REST_Controller::HTTP_OK,
			                'content'  => 'data kosong',
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

	function detail_get()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);
				$logintype = (int)antiInjection($token->logtype);

				if (!empty($uid) && is_numeric($logintype)) {

					$ID = antiInjection($this->input->get('t', TRUE)); // transaksi id

					$log_transaksi_pinjam     = $this->Content_model->get_log_transaksi_pinjam($ID);
					$transaksi                = $this->Content_model->get_transaksi_pinjam_byid($ID); // pinjaman
					$data['detail_angsuran'] = $this->Content_model->get_detail_pinjam_byid($ID); // cicilan
					$data['transaksi']        = $transaksi;
					$data['log_pinjaman']     = $log_transaksi_pinjam;

					$total_bayar = $transaksi['Jml_permohonan_pinjaman_disetujui'];
					$data['total_bayar'] = $total_bayar;
					$data['jatuh_tempo'] = '-';
					
					if ($transaksi['type_of_business_id'] == '1')
					{
						//echo ' Pinjaman Kilat';
						
						$data['jml_angsuran'] = $log_transaksi_pinjam['ltp_jml_angsuran'];

						if ( $transaksi['Master_loan_status'] == 'complete' || $transaksi['Master_loan_status'] == 'lunas') {
							$data['jatuh_tempo'] = date('d/m/Y', strtotime($log_transaksi_pinjam['ltp_tgl_jatuh_tempo']));
						}
					}else{
						//echo 'Pinjaman Mikro';
						
						$data['jml_angsuran']   = $log_transaksi_pinjam['ltp_jml_angsuran'];
						$data['lama_angsuran'] = $log_transaksi_pinjam['ltp_lama_angsuran']; // berapa minggu

						if ($transaksi['Master_loan_status'] == 'complete' || $transaksi['Master_loan_status'] == 'lunas') {
							$data['jatuh_tempo'] = date('d/m/Y', strtotime("+3 months", strtotime($transaksi['tgl_pinjaman_disetujui'])));
						}
					}

					$json_format = json_encode($data);

			    		$response['response'] = 'success';
	                    $response['status'] = REST_Controller::HTTP_OK;
	                    $response['content']   = $data;
			    		$http_status = REST_Controller::HTTP_OK;

			    		$this->set_response($response, REST_Controller::HTTP_OK);
				        return;

				}else{
					$response = [
		        		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_FORBIDDEN,
		                'message'  => 'Forbidden',
		            ];
		            $http_status = REST_Controller::HTTP_FORBIDDEN;
				}
			}else{
				$response = [
	        		'response' => 'fail',
	                'status'   => REST_Controller::HTTP_FORBIDDEN,
	                'message'  => 'Forbidden',
	            ];
	            $http_status = REST_Controller::HTTP_FORBIDDEN;
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

	/*function list_get()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				$logintype = (int)antiInjection($token->logtype);

				if (!empty($uid) && is_numeric($logintype)) {
					if ($logintype == '1') {
						// Peminjam
						$data['list_transaksi'] = $this->Content_model->get_my_transactions_pinjam($uid);
						$total_records          = $this->Content_model->get_total_pinjam($uid);
					}else{
						// Pendana
						$data['list_transaksi'] = $this->Content_model->get_my_transactions_pendana($uid);
						$total_records          = $this->Content_model->get_total_pendana($uid);
					} 

					//_d($data['list_transaksi']);

					if (count($data['list_transaksi']) > 0) {

						$json_format = json_encode((array)$data);

			    		echo '{ "response": "success", "status" : '.REST_Controller::HTTP_OK.' , "content":' . $json_format. ' }';
			    		$this->output->set_status_header(200);
			    		return;

			    	}else{

			    		$response = [
		            		'response' => 'fail',
			                'status'   => REST_Controller::HTTP_OK,
			                'content'  => 'data kosong',
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
	}*/
}