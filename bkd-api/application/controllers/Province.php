<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Province extends REST_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('Province_model');

		error_reporting(E_ALL);
        ini_set('display_errors', '1');
	}

	function list_get()
	{
		$data = $this->Province_model->get_province();

		$response['response'] = 'success';
        $response['status']   = REST_Controller::HTTP_OK;
        $response['content']  = $data;
        $this->set_response($response, REST_Controller::HTTP_OK);
        return;
	}
}
