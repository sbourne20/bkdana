<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Transaksi_pendana extends REST_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');

		error_reporting(E_ALL);
        ini_set('display_errors', '1');
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

			        if (empty($start_index) OR $start_index=='1') {
			        	$start_index = 0;
			        }else{
			        	$start_index = $start_index * $limit_per_page;
			        }

			        $data['list_transaksi'] = $this->Content_model->get_my_transactions_pendana($uid, $limit_per_page, $start_index);
					$total_records          = $this->Content_model->get_total_pendana($uid);

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

					$ID = trim($this->input->get('t', TRUE));

					$transaksi                = $this->Content_model->get_transaksi_pendana_byid($ID);
					$detail_transaksi = $this->Content_model->get_transaksi_pinjam_byid($transaksi['Master_loan_id']);
					$log_pendanaan    = $this->Content_model->get_log_pendanaan_by_codedana($ID);
					$log_pinjaman             = $this->Content_model->get_log_transaksi_pinjam($detail_transaksi['Master_loan_id']);
					//$produk_pinjaman = $this->Content_model->get_produk($detail_transaksi['Product_id']);

					if ($transaksi['tgl_disetujui'] != '0000-00-00 00:00:00') {
						// hitung jatuh tempo
						$jatuh_tempo = date('d/m/Y', strtotime($log_pinjaman['ltp_tgl_jatuh_tempo']));
						
					}else{
						$jatuh_tempo = '-';
					}

					// -------- START --------//

					switch ($detail_transaksi['Master_loan_status']) {
					    case 'complete':
					        $status_pinjaman_text = 'Proses Pembayaran';
					        break;
					    
					    default:
					        $status_pinjaman_text = ucfirst($detail_transaksi['Master_loan_status']);
					        break;
					}

					$data['no_transaksi_pendanaan'] = $transaksi['Id'];
					$data['tenor']                = $transaksi['Loan_term'];
					$data['total_pendanaan']      = $transaksi['Jml_penawaran_pemberian_pinjaman'];
					$data['total_saldo_diterima'] = $log_pendanaan['total_pendapatan'];
					$data['status_pendanaan']     = $transaksi['pendanaan_status'];

					$data['no_transaksi_pinjaman'] = $log_pinjaman['ltp_Master_loan_id'];
					$data['nama_peminjam']         = $detail_transaksi['nama_peminjam'];
					$data['jatuh_tempo']           = $jatuh_tempo;
					$data['total_pinjaman']        = $detail_transaksi['Jml_permohonan_pinjaman'];
					$data['status_pinjaman']        = $status_pinjaman_text;

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
                'status'   => REST_Controller::HTTP_FORBIDDEN,
                'message'  => 'Forbidden',
            ];
            $http_status = REST_Controller::HTTP_FORBIDDEN;
		}

		$this->set_response($response, $http_status);
        return;
	}

}