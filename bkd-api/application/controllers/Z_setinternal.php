<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;


class Z_setinternal extends REST_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('Member_model');
		$this->load->model('Content_model');
	}

	function index(){
		echo 'index';
	}

	function set_password_post() {

		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = 'iriawan.maarif@gmail.com';
				$password = 'master199';

				$stored_p = password_hash(base64_encode(hash('sha256', (trim($password)), true)), PASSWORD_DEFAULT);

				$mem_data['mum_password']      = $stored_p;

				$affected = $this->Member_model->update_member_byemail($uid, $mem_data);

				if ($affected) {
					echo 'berhasil';
				}else{
					echo 'gagal';
				}
			}
		}
	}
}