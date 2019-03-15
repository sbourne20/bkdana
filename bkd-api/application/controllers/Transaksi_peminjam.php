<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Transaksi_peminjam extends REST_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');

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

			        if (empty($start_index) OR $start_index=='1') {
			        	$start_index = 0;
			        }else{
			        	$start_index = $start_index * $limit_per_page;
			        }
					

					//$data['list_transaksi'] = $this->Content_model->all_transactions_pinjam();
					$data['list_transaksi'] = $this->Content_model->get_my_transactions_pinjam_mod2($uid, $limit_per_page, $start_index);

					//_d($data['list_transaksi']);

					if (count($data['list_transaksi']) > 0) {

						$json_format = json_encode((array)$data);

			    		// echo '{ "response": "success", "status" : '.REST_Controller::HTTP_OK.' , "content":' . $json_format. ' }';
			    		$response['response'] = 'success';
	                    $response['status'] = REST_Controller::HTTP_OK;
	                    $response['list_transaksi']   = $data['list_transaksi'];
	                    $this->set_response($response, REST_Controller::HTTP_OK);
			    		// $this->output->set_status_header(200);
			    		// $this->output->set_content_type('application/json', 'utf-8');
					   	return;

			    	}else{

			    		$response = [
		            		'response' => 'fail',
			                'status'   => REST_Controller::HTTP_OK,
			                'content'  => '',
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
					$now = date('Y-m-d H:i:s');

					$log_transaksi_pinjam     = $this->Content_model->get_log_transaksi_pinjam($ID);
					$transaksi                = $this->Content_model->get_transaksi_pinjam_byid($ID); // pinjaman
					$data['detail_angsuran'] = $this->Content_model->get_detail_pinjam_byid($ID); // cicilan
					$data['transaksi']        = $transaksi;
					$data['log_pinjaman']     = $log_transaksi_pinjam;

					$total_bayar = $transaksi['Jml_permohonan_pinjaman_disetujui'];
					$data['total_bayar'] = $total_bayar;
					$data['jatuh_tempo'] = '-';

					// Set status transaksi
					if ( $transaksi['Master_loan_status'] == 'complete' && ($transaksi['date_close'] == '0000-00-00' OR $transaksi['date_close'] == '0000-00-00 00:00:00' OR $transaksi['date_close'] == '') ) {
                        $status_bayar = 'Menunggu Pembayaran';
                    }else if ($transaksi['Master_loan_status'] == 'review') {
                        $status_bayar = 'Proses Review';
                    }else if ($transaksi['Master_loan_status'] == 'approve') {
                        $status_bayar = 'Menunggu Pendanaan';
                    }else{
                        $status_bayar = $transaksi['Master_loan_status'];
                    }
					$data['status_transaksi'] = $status_bayar;
					
					if ($transaksi['type_of_business_id'] == '1')
					{
						//echo ' Pinjaman Kilat';
						$data['transaksi']['Loan_term'] = $transaksi['Loan_term'].' Hari';
						$data['tenor_label']            = $transaksi['Loan_term'].' Hari';
						
						//$data['nominal_jml_angsuran'] = $log_transaksi_pinjam['ltp_jml_angsuran'];
						$data['jml_angsuran'] = $log_transaksi_pinjam['ltp_lama_angsuran']; // berapa minggu

						if ( $transaksi['Master_loan_status'] == 'complete' || $transaksi['Master_loan_status'] == 'lunas') {
							$data['jatuh_tempo'] = date('d/m/Y', strtotime($log_transaksi_pinjam['ltp_tgl_jatuh_tempo']));
						}
					}else{
						//echo 'Pinjaman Mikro';
						$data['transaksi']['Loan_term'] = $transaksi['Loan_term'].' Bulan';
						$data['tenor_label']            = $transaksi['Loan_term'].' Hari';
						
						//$data['nominal_jml_angsuran']   = $log_transaksi_pinjam['ltp_jml_angsuran'];
						$data['jml_angsuran'] = $log_transaksi_pinjam['ltp_lama_angsuran']; // berapa minggu

						if ($transaksi['Master_loan_status'] == 'complete' || $transaksi['Master_loan_status'] == 'lunas') {
							$tenor = $transaksi['Loan_term'];
							$data['jatuh_tempo'] = date('d/m/Y', strtotime("+".$tenor." months", strtotime($transaksi['tgl_pinjaman_disetujui'])));
						}
					}

					// Repayment List
					$cicilan = array();
					$submit_nominal_angsur = array();

					if ($transaksi['Master_loan_status'] == 'complete' OR $transaksi['Master_loan_status'] == 'lunas') 
					{
						$data_tempo = $this->Content_model->get_tempo_pinjaman_bycode($ID);
						$loop_tempo = count($data_tempo);

						
						for ($i=0; $i < $loop_tempo; $i++) {  

							// cek jika ada denda telat bayar
							if ( ($data_tempo[$i]['is_paid'] == '0' OR $data_tempo[$i]['is_paid'] == '1') && $data_tempo[$i]['telah_jatuh_tempo'] == '1' && $data_tempo[$i]['tgl_jatuh_tempo'] < $now )
							{
								$jmlangsuran = (int)$log_transaksi_pinjam['ltp_jml_angsuran'] + (int)$data_tempo[$i]['nominal_telat_bayar'];
							}else{
								$jmlangsuran = $log_transaksi_pinjam['ltp_jml_angsuran'];
							}

							if ( $data_tempo[$i]['is_paid'] == '0') {
								$submit_nominal_angsur[] = $jmlangsuran;
							}

							$cicilan[$i]['jatuh_tempo']     = date('d/m/Y', strtotime($data_tempo[$i]['tgl_jatuh_tempo']));
	                        $cicilan[$i]['nominal_cicilan'] = $jmlangsuran;
	                        $cicilan[$i]['status']          = ($data_tempo[$i]['is_paid'] == '1')? 'Lunas' : '';
						}

						//_d($data_tempo);
					}

					/*if ($transaksi['Master_loan_status'] == 'complete' OR $transaksi['Master_loan_status'] == 'lunas') 
					{

						// looping repayment list
						$lama_angsuran = $data['jml_angsuran'];
						$k = 1;

	                    for ($i=0; $i < $lama_angsuran; $i++) {  

	                        $jmlhari = 7 * $k;

	                        if (isset($data['detail_angsuran'][$i]['Date_repaid'])) {
	                            $class = 'done';
	                            $icon = '<i class="fas fa-check"></i>';
	                            $status_cicilan = 'Lunas';
	                        }else{
	                            $class = '';
	                            $icon = '<i class="far fa-clipboard"></i>';
	                            $status_cicilan = '';
	                        }

	                        if ($transaksi['Master_loan_status'] == 'complete' OR $transaksi['Master_loan_status'] == 'lunas') {
	                            $cicilan_duedate = date('d/m/Y', strtotime("+".$jmlhari." day", strtotime($transaksi['tgl_pinjaman_disetujui'])));
	                        }else{
	                            $cicilan_duedate = '';
	                        }

	                        $cicilan[$i]['jatuh_tempo'] = $cicilan_duedate;
	                        $cicilan[$i]['nominal_cicilan'] = $data['nominal_jml_angsuran'];
	                        $cicilan[$i]['status'] = $status_cicilan;


	                        $k=$k+1;
	                    }
	                }*/

	               if ( empty($submit_nominal_angsur)) {
	               		$submit_nominal_angsur[0] = '0';
	               }

                    $data['nominal_jml_angsuran'] = $submit_nominal_angsur[0];
                    $data['repayment_list']       = $cicilan;

		    		$response['response'] = 'success';
                    $response['status'] = REST_Controller::HTTP_OK;
                    $response['content']   = $data;
		    		$http_status = REST_Controller::HTTP_OK;

		    		$this->set_response($response, REST_Controller::HTTP_OK);
			        return;

				}else{
					$response = [
		        		'response' => 'fail',
		                'status'   => REST_Controller::HTTP_FORBIDDEN,
		                'message'  => 'Forbidden',
		            ];
		            $http_status = REST_Controller::HTTP_FORBIDDEN;
				}
			}else{
				$response = [
	        		'response' => 'fail',
	                'status'   => REST_Controller::HTTP_FORBIDDEN,
	                'message'  => 'Forbidden',
	            ];
	            $http_status = REST_Controller::HTTP_FORBIDDEN;
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