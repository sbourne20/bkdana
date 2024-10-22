<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Member extends REST_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');
		$this->load->model('Member_model');

		error_reporting(E_ALL);
        ini_set('display_errors', '1');
	}

	function index(){}

	public function cek_biayai_get()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);
				$logintype = (int)antiInjection($token->logtype);

				if (!empty($uid) && is_numeric($logintype)) {

					$memberdata = $this->Member_model->get_member_byid_less($uid);

					if ($memberdata['mum_status'] =='2') {
						$response = [
		            		'response' => 'fail',
			                'status'   => REST_Controller::HTTP_OK,
			                'message'  => 'Member sebagai pendana belum aktif',
			            ];
			            $http_status = REST_Controller::HTTP_OK;
			        }else if ($memberdata['mum_status'] =='1') {
						$response = [
		            		'response' => 'success',
			                'status'   => REST_Controller::HTTP_OK,
			                'message'  => 'Member sudah aktif',
			            ];
			            $http_status = REST_Controller::HTTP_OK;
					}else{
						$response = [
		            		'response' => 'fail',
			                'status'   => REST_Controller::HTTP_OK,
			                'message'  => 'Member belum aktif',
			            ];
			            $http_status = REST_Controller::HTTP_OK;
					}

				}else{
			    	$response = [
	            		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_UNAUTHORIZED,
		                'message'  => 'Member Not Found',
		            ];
		            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
			    }

			}else{
				$response = [
	            		'response' => 'fail',
		                'status'   => 404,
		                'message'  => 'Unauthorized',
		            ];
		            $http_status = 404;
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

	public function cek_password_post()
    {
        //echo 'login';

            $email = trim($this->input->post('username', TRUE));
            $pass  = trim($this->input->post('password', TRUE));

            if ($email != '' && $pass != '') {

                $device_token = $this->input->post('token', TRUE);

                $getdata = $this->Member_model->do_login_byemail(htmlentities(strip_tags($email)));

                // _d($getdata);
                // exit();

                if ( count($getdata) > 0 && $getdata['mum_password'] !='' && $getdata['id_mod_user_member'] !=''){
                
                    $stored_password = $getdata['mum_password'];

                    if (password_verify(base64_encode(hash('sha256', ($pass), true)), $stored_password)) {

                        $response['response'] = 'success';
                        $response['status']   = REST_Controller::HTTP_OK;
                        $this->set_response($response, REST_Controller::HTTP_OK);
                        return;
                    }
                    else
                    {
                        $response = [
                            'response' => 'fail',
                            'status'   => 400,
                            'message'  => '',
                        ];
                        $this->set_response($response, 400);
                        return;                
                    }
                }else{
                    $response = [
                            'response' => 'fail',
                            'status'   => 400,
                            'message'  => 'Data Not Found',
                        ];
                        $this->set_response($response, 400);
                        return;  
                }
            }else{
                $response = [
                    'status' => 400,
                    'message' => 'Username & Password is mandatory',
                ];
                $this->set_response($response, 400);
                return;
            }
        
    }

	function mydata_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);
				$member = $this->Member_model->data_member($uid);

				if ($member['foto_file'] !=''){
					$member['foto_file'] = $this->config->item('images_uri') . 'member/'.$member['member_id'] .'/foto/' .$member['foto_file'];
				}

				if ($member['nik_file'] !=''){
					$member['nik_file'] = $this->config->item('images_uri') . 'member/'.$member['member_id'] .'/ktp/' .$member['nik_file'];
				}

				if ($member['foto_surat_ket_kerja'] !=''){
					$member['foto_surat_ket_kerja'] = $this->config->item('images_uri') . 'member/'.$member['member_id'] .'/surat_kerja/' .$member['foto_surat_ket_kerja'];
				}

				if ($member['foto_slip_gaji'] !=''){
					$member['foto_slip_gaji'] = $this->config->item('images_uri') . 'member/'.$member['member_id'] .'/slip_gaji/' .$member['foto_slip_gaji'];
				}

				if ($member['foto_pegang_idcard'] !=''){
					$member['foto_pegang_idcard'] = $this->config->item('images_uri') . 'member/'.$member['member_id'] .'/hold_idcard/' .$member['foto_pegang_idcard'];
				}

				if ($member['foto_usaha_file'] !=''){
					$member['foto_usaha_file'] = $this->config->item('images_uri') . 'member/'.$member['member_id'] .'/usaha/' .$member['foto_usaha_file'];
				}

				

				$response['response'] = 'success';
                $response['status']   = REST_Controller::HTTP_OK;
                $response['content']  = $member;
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

	function mysaldo_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);
				if ($uid)
				{

					$data    = $this->Content_model->get_total_saldo($uid);

					if (!isset($data['Amount'])) {
						$data['Amount'] = '0';
					}

					$response['response'] = 'success';
	                $response['status']   = REST_Controller::HTTP_OK;
	                $response['content']  = $data;
	                $this->set_response($response, REST_Controller::HTTP_OK);
		    		// $this->output->set_status_header(200);
		    		// $this->output->set_content_type('application/json', 'utf-8');
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
                'status'   => REST_Controller::HTTP_FORBIDDEN,
                'message'  => 'Forbidden',
            ];
            $http_status = REST_Controller::HTTP_FORBIDDEN;
		}

		$this->set_response($response, $http_status);
        return;
	}

	function check_notelp_post()
	{
		

		$telp = $this->input->post('no_telp');

		if (trim($telp) != '') {


				$data = $this->Member_model->check_notelp($telp);

				if (isset($data['Mobileno'])) {
					$resp = 'success';
					$message = 'Data ditemukan';
				
				}else{
					$resp = 'fail';
					$data = '';
					$message = 'Data tidak ditemukan';
				}

				$response['response'] = $resp;
                $response['status']   = REST_Controller::HTTP_OK;
                $response['content']  = $data;
                $response['message']  = $message;
                $this->set_response($response, REST_Controller::HTTP_OK);	    		
			   	return;
		}else{
			$response = [
            		'response' => 'fail',
	                'status'   => 401,
	                'message'  => 'No telp is required',
	            ];
	            $http_status = REST_Controller::HTTP_UNAUTHORIZED;
		}
		

		$this->set_response($response, $http_status);
        return;
	}
}