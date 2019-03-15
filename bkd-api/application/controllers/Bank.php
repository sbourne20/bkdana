<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Bank extends REST_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Content_model');
	}

	function list_get(){
		$data = $this->Content_model->get_list_bank();

		$response['response']      = 'success';
        $response['status']        = REST_Controller::HTTP_OK;
        $response['content']       = $data;
        $this->set_response($response, REST_Controller::HTTP_OK);
	   	return;
	}
}