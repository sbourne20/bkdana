<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Rekening_koran extends REST_Controller {

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

					$data['list_rekening_koran'] = $this->Wallet_model->all_wallet_detail($uid, $start_index, $limit_per_page);

					if (count($data['list_rekening_koran']) > 0) {

			    		$response['response'] = 'success';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = $data['list_rekening_koran'];
	                    $this->set_response($response, REST_Controller::HTTP_OK);
					   	return;

			    	}else{

			    		// $response = [
		       //      		'response' => 'fail',
			      //           'status'   => REST_Controller::HTTP_OK,
			      //           'content'  => '',
			      //           'message'  => 'Tidak ada data',
			      //       ];
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

}