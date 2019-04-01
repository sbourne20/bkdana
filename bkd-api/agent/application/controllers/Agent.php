<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Agent extends REST_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');
		$this->load->model('Agent_model');

		error_reporting(E_ALL);
        ini_set('display_errors', '1');
	}

	public function profile_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);
				$agent = $this->Agent_model->data_agent($uid);

				$response['response'] = 'success';
                $response['status']   = REST_Controller::HTTP_OK;
                $response['content']  = $agent;
                $this->set_response($response, REST_Controller::HTTP_OK);
	    		// $this->output->set_status_header(200);
	    		// $this->output->set_content_type('application/json', 'utf-8');
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
                'status'   => REST_Controller::HTTP_FORBIDDEN,
                'message'  => 'Forbidden',
            ];
            $http_status = REST_Controller::HTTP_FORBIDDEN;
		}

		$this->set_response($response, $http_status);
        return;
	}


}