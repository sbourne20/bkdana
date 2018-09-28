<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Top_up extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Topup_model');
		$this->load->model('Wallet_model');
		
		//error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Loan';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/topup.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mainData['mainContent']  = $this->load->view('topup/vlist', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{
		$data = $this->Topup_model->get_all_dt();
		print_r($data);
	}

	function approve()
	{
		$id = trim(antiInjection($this->uri->segment(3)));

		if ($id != '' && $id !='0')
		{
			$topdata = $this->Topup_model->get_data_byid($id);

			if ($topdata['status_top_up'] == 'pending')
			{
				$nowdate     = date('Y-m-d H:i:s');
				$tambahsaldo = $topdata['jml_top_up'];

				// approve table mod_top_up
				$update_topup['status_top_up']        = 'approve';
				$update_topup['tgl_perubahan_status'] = date('Y-m-d H:i:s');
				$affected = $this->Topup_model->update_($update_topup, $id);

				if($affected){

					$check_wallet = $this->Wallet_model->get_wallet_user($topdata['user_id']);

					//_d($check_wallet);

					if (count($check_wallet)>1 && isset($check_wallet['User_id'])) {
						//echo 'update master';
						$this->Wallet_model->update_master_wallet_saldo($topdata['user_id'], $tambahsaldo);

						$master_wallet_id = $check_wallet['Id'];
					}else{
						//echo  'insert master';
						$inwallet['Date_create']      = $nowdate;
						$inwallet['User_id']          = $topdata['user_id'];
						$inwallet['Amount']           = $tambahsaldo;
						$inwallet['wallet_member_id'] = $topdata['member_id'];

						$master_wallet_id = $this->Wallet_model->insert_master_wallet($inwallet);
					}

					// detail wallet
					/*$detail_w['Id']               = $master_wallet_id;
					$detail_w['Date_transaction'] = $nowdate;
					$detail_w['Amount']           = $tambahsaldo;
					$detail_w['Notes']            = 'Top Up Saldo';
					$detail_w['tipe_dana']        = 1;
					$detail_w['User_id']          = $topdata['user_id'];
					$detail_w['kode_transaksi']   = $topdata['kode_top_up'];

					$this->Wallet_model->insert_detail_wallet($detail_w);*/

					$notes          = 'Top Up Saldo';
					$tipedana       = 1;
					$id_pengguna    = $topdata['user_id'];
					$kode_transaksi = $topdata['kode_top_up'];
					$balance        = isset($check_wallet['Amount'])? $check_wallet['Amount']+$tambahsaldo : 0;

					insert_detail_wallet($master_wallet_id, $nowdate, $tambahsaldo, $notes, $tipedana, $id_pengguna, $kode_transaksi, $balance);

					$this->session->set_userdata('message','Data has been Approved.');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','No Data selected.');
					$this->session->set_userdata('message_type','warning');
				}
			}else{
				$this->session->set_userdata('message','Transaksi sudah pernah di Approve.');
				$this->session->set_userdata('message_type','warning');
			}

		}

		redirect('top_up');
	}

	function delete()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);

		if (trim($id) != '' && !empty($id))
		{
			$del = $this->Topup_model->delete_($id);
			if($del){
				$this->session->set_userdata('message','Data has been deleted.');
				$this->session->set_userdata('message_type','success');
			}
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('top_up');
	}
}