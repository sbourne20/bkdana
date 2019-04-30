<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Profile extends REST_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');
		$this->load->model('Member_model');

		//error_reporting(E_ALL);
        //ini_set('display_errors', '1');
	}

	public function index_get()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);
				$member = $this->Member_model->data_member($uid);

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

	public function edit_profile_get()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);
				$member = $this->Member_model->get_member_byid($uid);

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

	public function update_informasiakun_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				if (!empty($uid)) {
					
					$post = $this->input->post(NULL, TRUE);

					$fullname        = trim($post['fullname']);
					$email           = trim($post['email']);
					$telp            = trim($post['telp']);					
					$nomor_rekening  = trim($post['nomor_rekening']);
					$nama_bank       = trim($post['nama_bank']);
					$nik             = trim($post['nik']);
					$gender          = trim($post['jenis_kelamin']);
					$tgl_lahir       = ($post['tgl_lahir'])? date('Y-m-d', strtotime(trim($post['tgl_lahir']))) : '';
					$pendidikan         = trim($post['pendidikan']);
					$pekerjaan          = trim($post['pekerjaan']);

					$memberdata = $this->Member_model->get_member_byid_less($uid);
					$id_pengguna = $memberdata['Id_pengguna'];

					$check = $this->Content_model->check_existing_member($email, $telp, '');
					$check2 = $this->Member_model->check_existing_member($email);
					$count_member = count($check);

					if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

						$response['response']  = 'fail';
		                $response['status']    = REST_Controller::HTTP_OK;
		                $response['message']   = 'Invalid Email format';
		                $response['content']   = '';
						$this->set_response($response, REST_Controller::HTTP_OK);
		                return;

					// }else if ( $count_member > 1 && isset($check['id_mod_user_member'])){

					// 	$response['response']  = 'fail';
		   //              $response['status']    = REST_Controller::HTTP_OK;
		   //              $response['message']   = 'Email/No.Telp Anda sudah terdaftar';
		   //              $response['content']   = '';
					// 	$this->set_response($response, REST_Controller::HTTP_OK);
		   //              return;
					
					}else if (strlen($telp) < 10) {

						$response['response']  = 'fail';
		                $response['status']    = REST_Controller::HTTP_OK;
		                $response['message']   = 'Isilah Nomor Telepon dengan benar. Nomor Telepon Minimum 10 digit';
		                $response['content']   = '';
						$this->set_response($response, REST_Controller::HTTP_OK);
		                return;

		            }else if (
						$fullname != '' 
						&& $email != ''
						&& $telp != '' 
						&& strlen($telp) > 5 
						&& $nomor_rekening != ''
						&& $nama_bank != ''
					){
						$mem_data['mum_fullname'] = $fullname;
					    $mem_data['mum_email']    = $email;
					    $mem_data['mum_telp']     = $telp;
		            	// print_r($mem_data);
						$mod_user_member = $this->Member_model->update_member_byid($check2['id_mod_user_member'], $mem_data);

						// update user
						$indata_user['Nama_pengguna']  = $fullname;
						$indata_user['Nomor_rekening'] = $nomor_rekening;
						$indata_user['nama_bank']      = $nama_bank;
						$indata_user['Jenis_kelamin']  = $gender;
						$indata_user['Tanggal_lahir']  = $tgl_lahir;
						$indata_user['Id_ktp']         = $nik;
						$indata_user['Pendidikan']     = $pendidikan;
						$indata_user['Pekerjaan']      = $pekerjaan;
						$this->Content_model->update_user($uid, $indata_user);

						$u_detail['Mobileno'] = $telp;
						$this->Content_model->update_userdetail($id_pengguna, $u_detail);

						// --- Set Ranking pengguna ---
						$get_ranking = set_ranking_pengguna($id_pengguna, $memberdata['mum_type'], $memberdata['mum_type_peminjam']); // (Id_pengguna, peminjam/pendana, kilat/mikro)

						$update_pengguna['peringkat_pengguna']            = $get_ranking['grade'];
						$update_pengguna['peringkat_pengguna_persentase'] = $get_ranking['ranking'];
						$this->Content_model->update_user_byid($id_pengguna, $update_pengguna);
						// --- End of Set Ranking pengguna ---

						$response['response']  = 'success';
		                $response['status']    = REST_Controller::HTTP_OK;
		                $response['message']   = 'Sukses update informasi akun';
		                $response['content']   = '';
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
                'status'   => REST_Controller::HTTP_FORBIDDEN,
                'message'  => 'Forbidden',
            ];
            $http_status = REST_Controller::HTTP_FORBIDDEN;
		}

		$this->set_response($response, $http_status);
        return;
	}

	public function update_informasialamat_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				if (!empty($uid)) {

					$memberdata = $this->Member_model->get_member_byid_less($uid);
					$id_pengguna = $memberdata['Id_pengguna'];
					
					$post = $this->input->post(NULL, TRUE);

					//$member_id = trim($post['member_id']);
					$alamat    = trim($post['alamat']);
					$kota      = trim($post['kota']);
					$provinsi  = trim($post['provinsi']);
					$kodepos   = trim($post['kodepos']);
					
					$mem_data['Alamat']   = $alamat;
				    $mem_data['Kota']     = $kota;
				    $mem_data['Provinsi'] = $provinsi;
				    $mem_data['Kodepos']  = $kodepos;

	            	// print_r($mem_data);
					$profile_geografi = $this->Content_model->update_profil_geografi($id_pengguna, $mem_data);

					// --- Set Ranking pengguna ---
					$get_ranking = set_ranking_pengguna($id_pengguna, $memberdata['mum_type'], $memberdata['mum_type_peminjam']); // (Id_pengguna, peminjam/pendana, kilat/mikro)

					$update_pengguna['peringkat_pengguna']            = $get_ranking['grade'];
					$update_pengguna['peringkat_pengguna_persentase'] = $get_ranking['ranking'];
					$this->Content_model->update_user_byid($id_pengguna, $update_pengguna);
					// --- End of Set Ranking pengguna ---

					$response['response']  = 'success';
	                $response['status']    = REST_Controller::HTTP_OK;
	                $response['message']   = 'Sukses update informasi alamat';
	                $response['content']   = '';
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
                'status'   => REST_Controller::HTTP_FORBIDDEN,
                'message'  => 'Forbidden',
            ];
            $http_status = REST_Controller::HTTP_FORBIDDEN;
		}

		$this->set_response($response, $http_status);
        return;
	}

	public function update_password_post()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);

				if (!empty($uid)) {
					
					$post = $this->input->post(NULL, TRUE);

					//$member_id 		= trim($post['member_id']);
					$member_id 		= trim($uid);
					$old_password   = antiInjection(trim($post['old_password']));
					$password   	= antiInjection(trim($post['password']));
					$conf_password  = antiInjection(trim($post['conf_password']));

					if ( $password == '' OR strlen($password) < 6 ) { 
						$response['response']  = 'fail';
		                $response['status']    = REST_Controller::HTTP_OK;
		                $response['message']   = 'Password minimal 6 karakter';
		                $response['content']   = '';
						$this->set_response($response, REST_Controller::HTTP_OK);
		                return;

					}else if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-zA-Z]).*$/", $password) === 0) {
						// min 6 karakter, terdiri dari minimum 1 huruf, minimum 1 angka
						$response['response']  = 'fail';
		                $response['status']    = REST_Controller::HTTP_OK;
		                $response['message']   = 'Password harus terdiri dari huruf dan angka, serta minimal 1 huruf besar';
		                $response['content']   = '';
						$this->set_response($response, REST_Controller::HTTP_OK);
		                return;
					
					}else if ( $password != $conf_password ) {

						$response['response']  = 'fail';
		                $response['status']    = REST_Controller::HTTP_OK;
		                $response['message']   = 'Password dan Konfirmasi Password tidak sama';
		                $response['content']   = '';
						$this->set_response($response, REST_Controller::HTTP_OK);
		                return;

		            }else if (
						$old_password != '' 
						&& $password != '' 
						&& $conf_password != ''
					) {

		            	$old_p = password_hash(base64_encode(hash('sha256', (trim($old_password)), true)), PASSWORD_DEFAULT);

						$check = $this->Member_model->check_password_member($old_p, $member_id);
						
						$last_chk = password_verify(base64_encode(hash('sha256', ($old_password), true)), $check['mum_password']);
						if($last_chk){
						
							$p = password_hash(base64_encode(hash('sha256', ($password), true)), PASSWORD_DEFAULT);

			    			$mod_user_member = $this->Member_model->update_password_member($p, $member_id);

							$response['response']  = 'success';
			                $response['status']    = REST_Controller::HTTP_OK;
			                $response['message']   = 'Sukses update password';
			                $response['content']   = '';
							$this->set_response($response, REST_Controller::HTTP_OK);
		                return;

		           		}else{
		           			$response['response'] = 'fail';
		                    $response['status']   = REST_Controller::HTTP_OK;
		                    $response['content']  = '';
		                    $response['message']  = 'Old Password tidak disesuai';
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
                'status'   => REST_Controller::HTTP_FORBIDDEN,
                'message'  => 'Forbidden',
            ];
            $http_status = REST_Controller::HTTP_FORBIDDEN;
		}

		$this->set_response($response, $http_status);
        return;
	}
	
}