<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;

class Home extends REST_Controller {

	/* Submit pendanaan */

	function __construct()
	{
		parent::__construct();

		$this->load->model('Content_model');
		$this->load->model('Member_model');
		$this->load->model('Wallet_model');
		$this->load->model('Pendanaan_model');

		error_reporting(E_ALL);
        ini_set('display_errors', '1');
	}

	function index(){
		// echo 'index';
		header("Location: https://bkdana.id");
		die();
	}

	function data_transaksi_get()
	{
		$headers = $this->input->request_headers();

		if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
				$uid = (int)antiInjection($token->id);
				$logintype = (int)antiInjection($token->logtype);

				if (!empty($uid) && is_numeric($logintype)) {

					if ($logintype == '1') {
						// Peminjam
						$list_transaksi = $this->Content_model->get_my_transactions_pinjam($uid);
						$nominal_all_transaksi = $this->Content_model->get_total_mypinjaman($uid);
						$tipe_user = 'Peminjam';
					}else{
						// Pendana
						$list_transaksi = $this->Content_model->get_my_transactions_pendana($uid);
						$nominal_all_transaksi = $this->Content_model->get_total_mypendanaan($uid);
						$tipe_user = 'Pendana';
					}

					$total_repayment = count($list_transaksi);
					$save_arr = array();
					
					if ($total_repayment > 0) {

						$i=0;
						foreach ($list_transaksi as $l) {
							$save_arr[$i]['no_transaksi']      = $l['transaksi_id'];
							$save_arr[$i]['nominal_transaksi'] = $l['totalrp'];


							if ($logintype == '1') {
								// Peminjam
								$save_arr[$i]['title_transaksi'] = $l['product_title'];
							}else{
								// Pendana
								$save_arr[$i]['title_transaksi'] = 'Pendanaan - ' . $l['product_title'];
							}

							if ($l['tgl_approve'] != '0000-00-00 00:00:00') {
								// hitung jatuh tempo
								$save_arr[$i]['jatuh_tempo_transaksi'] = date('d/m/Y', strtotime($l['tgl_approve']));
								
							}else{
								$save_arr[$i]['jatuh_tempo_transaksi'] = '-';
							}

							$i++;

						}
					}

					$data['list_repayment'] = $save_arr;

					// Saldo
					$mysaldo = $this->Content_model->get_total_saldo($uid);
					if (isset($mysaldo['Amount'])) {
						$data['saldo']             = ($mysaldo['Amount']);
					}else{
						$data['saldo']             = '0';						
					}

					if (isset($nominal_all_transaksi['itotal_transaksi'])) {
						$data['jml_all_transaksi'] = number_format($nominal_all_transaksi['itotal_transaksi']);
					}else{
						$data['jml_all_transaksi'] = '0';						
					}
					
					$data['tipe_user']         = $tipe_user;

					$response['response'] = 'success';
	                $response['status']   = REST_Controller::HTTP_OK;
	                $response['content']  = $data;
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
}
