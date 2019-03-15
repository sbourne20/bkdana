<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Survey extends REST_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');
		$this->load->model('Agent_model');

		error_reporting(E_ALL);
        ini_set('display_errors', '1');
	}

	public function listsurvey_get()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				if (!empty($uid)) {

					$limit_per_page = (int)$this->input->get('limit', TRUE);
			        $start_index    = (int)$this->input->get('page', TRUE);

					//$data['agentdata']     = $this->Agent_model->data_agent($uid);					
					//$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);
				
					$data['list_peminjam'] = $this->Content_model->all_list_transactions_pinjaman($limit_per_page, $start_index);

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

	public function submit_formsurvey1_post()
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
					$indata['alamat'] 	  = trim($post['alamat']);
					$indata['no_ktp']  	  = trim($post['no_ktp']);

					$indata['survey_date'] = date('Y-m-d H:i:s');
					$indata['status'] = 0;

					$check_pinjaman_id = $this->Content_model->get_profil_pinjam_byid($post['master_loan_id']);
					
					if($check_pinjaman_id < 1){

						$last_id = $this->Content_model->insert_mod_agent_survey($indata);
						$data = array('message' => 'Input Survey 1 Berhasil', 'last_id' => $last_id);

						$response['response'] = 'success';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = $data;
	                    $this->set_response($response, REST_Controller::HTTP_OK);
	                    return;
                	
                	}else{

                		$response['response'] = 'fail';
	                    $response['status']   = REST_Controller::HTTP_OK;
	                    $response['content']  = 'ID Transaksi Sudah Pernah di Survey';
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

	public function submit_formsurvey2_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				if (!empty($uid) && trim($uid) !='') {

					$post = $this->input->post(NULL, TRUE);

					$indata['id_mod_agent_survey'] = $post['id_mod_agent_survey'];

					$indata['alamat_usaha'] = trim($post['alamat_usaha']);
					$indata['jenis_usaha'] = trim($post['jenis_usaha']);
					
					/* Photo */
					if( isset($_FILES['info_usaha_file']['name']) && $_FILES['info_usaha_file']['name'] != ''){
						// ----- Process Image Name -----
						$img_info          = pathinfo($_FILES['info_usaha_file']['name']);
						$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
						$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
						$fileExt           = $img_info['extension'];
						$file_usaha_name   = $fileName.'.'.$fileExt;
						// ----- END Process Image Name -----
						$indata['images_usaha_name'] = $file_usaha_name;
					}else{
						$file_usaha_name   = '';
					}
					// ----- Destination Foto -----
					$destination_usaha = $this->config->item('agent_images_dir'). $uid."/usaha/";
					if(isset($_FILES['info_usaha_file']['name']) && $_FILES['info_usaha_file']['name'] != ''){
						if (!is_file($destination_usaha.$file_usaha_name)) {
							mkdir_r($destination_usaha);
						}
						// unlink($destination_usaha.$memberdata['images_usaha_name']);
						move_uploaded_file($_FILES['info_usaha_file']['tmp_name'], $destination_usaha.$file_usaha_name);
					}
					/* End Photo */
					
					$last_id = $this->Content_model->update_mod_agent_survey($post['id_mod_agent_survey'], $indata);
					$data = array('message' => 'Input Survey 2 Berhasil', 'last_id' => $last_id);

					$response['response'] = 'success';
                    $response['status']   = REST_Controller::HTTP_OK;
                    $response['content']  = $data;
                    $this->set_response($response, REST_Controller::HTTP_OK);
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

	public function submit_formsurvey3_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				if (!empty($uid) && trim($uid) !='') {

					$post = $this->input->post(NULL, TRUE);

					$indata['id_mod_agent_survey'] = $post['id_mod_agent_survey'];

					$indata['omset'] = trim($post['omset']);
					$indata['biaya'] = trim($post['biaya']);
					$indata['laba'] = trim($post['laba']);

					$indata['status'] = 1;
					
					$this->Content_model->update_mod_agent_survey($post['id_mod_agent_survey'], $indata);
					$data = array('message' => 'Input Survey 3 Berhasil', 'proses' => 'selesai');

					$response['response'] = 'success';
                    $response['status']   = REST_Controller::HTTP_OK;
                    $response['content']  = $data;
                    $this->set_response($response, REST_Controller::HTTP_OK);
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

	public function list_mysurvey_get()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);				

				if (!empty($uid)) {

					//$id_agent = trim($post['id_agent']);
					$id_agent = $this->input->get('id_agent');
					$limit_per_page = (int)$this->input->get('limit', TRUE);
			        $start_index    = (int)$this->input->get('page', TRUE);

					$data['list_mysurvey'] = $this->Content_model->get_mod_agent_survey($id_agent, $start_index, $limit_per_page);

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

	public function details_survey_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				if (!empty($uid)) {

					$post = $this->input->post(NULL, TRUE);

					$data['data_survey'] = $this->Content_model->set_mod_agent_survey_details($post['id_mod_agent_survey']);
					$path = $this->config->item('images_uri'). $uid."/usaha/";
					foreach($data as $val) {
						array_push($data['data_survey'][0], $path.$data['data_survey'][0]['images_usaha_name']);
					}
					// print_r($data);
					
					if(!empty($data)){

						$response['response'] = 'success';
		                $response['status']   = REST_Controller::HTTP_OK;
		                $response['content']  = $data;
		                $this->set_response($response, REST_Controller::HTTP_OK);
		                return;

					}else{

						$response['response'] = 'fail';
		                $response['status']   = REST_Controller::HTTP_OK;
		                $response['content']  = 'Data survey tidak ditemukan';
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


}