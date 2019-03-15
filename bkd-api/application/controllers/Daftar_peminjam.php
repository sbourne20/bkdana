<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Daftar_peminjam extends REST_Controller {

	/* List daftar peminjam di halaman pendana */

	function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');
		$this->load->model('Member_model');

		error_reporting(E_ALL);
        ini_set('display_errors', '1');
	}

	function index_get()
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

					if (empty($start_index)) {
			        	$start_index = 0;
			        }else{
			        	$start_index = $start_index * $limit_per_page;
			        }
				
					$data['list_peminjam'] = $this->Content_model->all_list_transactions_pinjaman($limit_per_page, $start_index);

					if (count($data['list_peminjam']) > 0) {

						$response['response'] = 'success';
		                $response['status']   = REST_Controller::HTTP_OK;
		                $response['content']  = $data;
		                $this->set_response($response, REST_Controller::HTTP_OK);
		                return;
		            }else{
		            	$response = [
			            		'response' => 'fail',
				                'status'   => 400,
				                'content'  => $data,
				                'message'  => 'data kosong',
				            ];
				    		$http_status = REST_Controller::HTTP_OK;
				    		$this->set_response($response, REST_Controller::HTTP_OK);
		                return;
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
					
					$transaksi         = $this->Content_model->get_transaksi_pinjam_byid($ID);
					$log_tran_pinjam   = $this->Content_model->get_log_transaksi_pinjam($ID);
					$data['transaksi'] = $transaksi;

					$data['total_bayar']        = $transaksi['Jml_permohonan_pinjaman'];
					$data['pinjaman_disetujui'] = $transaksi['Jml_permohonan_pinjaman_disetujui'];

					$get_total_pendana = $this->Content_model->get_kuota_pinjaman($transaksi['Master_loan_id']);
					$total_dana_masuk  = $get_total_pendana['jml_pendanaan'];
					$total_pinjaman    = $transaksi['Jml_permohonan_pinjaman'];

					$data['kuota_dana'] = round(($total_dana_masuk/$total_pinjaman) * 100);

					//-----------------------------------------------//
					switch ($transaksi['Pekerjaan']) {
					    case '1':
					        $label_pekerjaan = 'PNS';
					        break;
					    case '2':
					        $label_pekerjaan = 'BUMN';
					        break;
					    case '3':
					        $label_pekerjaan = 'Swasta';
					        break;
					    case '4':
					        $label_pekerjaan = 'wiraswasta';
					        break;
					    
					    default:
					        $label_pekerjaan = 'lain-lain';
					        break;
					}

					switch ($transaksi['How_many_years_have_you_been_in_business']) {
					    case '0':
					        $label_lama_usaha = 'Kurang dari setahun';
					        break;
					    case '11':
					        $label_lama_usaha = 'Lebih dari 10 tahun';
					        break;
					    
					    default:
					        $label_lama_usaha = $transaksi['How_many_years_have_you_been_in_business'] . ' tahun';
					        break;
					}
					$output['total_pinjaman']  = $transaksi['Jml_permohonan_pinjaman'];
					$output['total_pendanaan'] = $transaksi['jml_kredit'];
					$output['total_lender']    = $transaksi['total_lender'];
					$output['kuota_dana']      = round(($output['total_pendanaan']/$output['total_pinjaman']) * 100) .'%';
					$output['nama_peminjam']    = $transaksi['Nama_pengguna'];
					$output['no_transaksi_pinjaman']    = $transaksi['Master_loan_id'];
					$output['tenor']     = $transaksi['Loan_term'];
					$output['grade_peminjam']    = $transaksi['peringkat_pengguna'];
					$output['pekerjaan']  = $label_pekerjaan;
					$output['lama_usaha'] = $label_lama_usaha;
					$output['alamat']    = $transaksi['Alamat'];
					$output['kota']    = $transaksi['Kota'];
					$output['foto_profil'] = '';
					$output['foto_usaha']  = '';

					if ($transaksi['images_foto_name'] != ''){ 
                        $output['foto_profil'] = $this->config->item('images_uri') . 'member/'.$transaksi['id_mod_user_member']. '/foto/'. $transaksi['images_foto_name'];
                    }

                    if ($transaksi['images_usaha_name'] != ''){ 
                        $output['foto_usaha'] = $this->config->item('images_uri') . 'member/'.$transaksi['id_mod_user_member']. '/usaha/'. $transaksi['images_usaha_name'];
                    }

                    $response['response'] = 'success';
	                $response['status']   = REST_Controller::HTTP_OK;
	                $response['content']  = $output;
	                $this->set_response($response, REST_Controller::HTTP_OK);
	                return;

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