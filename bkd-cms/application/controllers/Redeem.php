<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Redeem extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Redeem_model');
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
		$mainData['bottom_js'] .= add_js('js/data/redeem.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mainData['mainContent']  = $this->load->view('redeem/vlist', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{
		$data = $this->Redeem_model->get_all_dt();
		print_r($data);
	}

	function approve()
	{
		$id = trim(antiInjection($this->uri->segment(3)));

		if ($id != '' && $id !='0')
		{
			$redeemdata = $this->Redeem_model->get_data_byid($id);

			if (count($redeemdata)>1 && !empty($redeemdata['redeem_amount']) && !empty($redeemdata['redeem_id_pengguna']) )
			{
				$nowdate     = date('Y-m-d H:i:s');
				$tambahsaldo = $redeemdata['redeem_amount'];

				// approve table mod_top_up
				$update_redeem['redeem_status']        = 'approve';
				$update_redeem['redeem_status_date']   = date('Y-m-d H:i:s');
				$affected = $this->Redeem_model->update_($update_redeem, $id);

				if($affected){

					// insert wallet
					$check_wallet = $this->Wallet_model->get_wallet_user($redeemdata['redeem_id_pengguna']);

					if (count($check_wallet) >0 && isset($check_wallet['User_id'])) {
						// update master
						$this->Wallet_model->kurangi_saldo($redeemdata['redeem_id_pengguna'], $tambahsaldo);

						$master_wallet_id = $check_wallet['Id'];

						// detail wallet
						/*$detail_w['Id']               = $master_wallet_id;
						$detail_w['Date_transaction'] = $nowdate;
						$detail_w['Amount']           = $tambahsaldo;
						$detail_w['Notes']            = 'Approve Redeem by Admin';

						$this->Wallet_model->insert_detail_wallet($detail_w);*/

						$notes          = 'Tarik Tunai';
						$tipedana       = 2;
						$id_pengguna    = $redeemdata['redeem_id_pengguna'];
						$kode_transaksi = $redeemdata['redeem_kode'];
						$balance        = $check_wallet['Amount'] - $tambahsaldo;

						insert_detail_wallet($master_wallet_id, $nowdate, $tambahsaldo, $notes, $tipedana, $id_pengguna, $kode_transaksi, $balance);

						$this->session->set_userdata('message','Data has been Approved.');
						$this->session->set_userdata('message_type','success');
					}else{
						$this->session->set_userdata('message','Error!. Anda tidak memiliki Saldo.');
						$this->session->set_userdata('message_type','warning');
					}
					
				}else{
					$this->session->set_userdata('message','No Data selected.');
					$this->session->set_userdata('message_type','warning');
				}
			}

		}

		redirect('redeem');
	}

	function reject()
	{
		$id = trim(antiInjection($this->uri->segment(3)));

		if ($id != '' && $id !='0')
		{
			$nowdate     = date('Y-m-d H:i:s');

			// approve table mod_top_up
			$update_redeem['redeem_status']        = 'reject';
			$update_redeem['redeem_status_date']   = date('Y-m-d H:i:s');
			$affected = $this->Redeem_model->update_($update_redeem, $id);

			if($affected){
				$this->session->set_userdata('message','Data has been Rejected.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','No Data selected.');
				$this->session->set_userdata('message_type','warning');
			}
		}

		redirect('redeem');
	}

	function delete()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);

		if (trim($id) != '' && !empty($id))
		{
			$del = $this->Redeem_model->delete_($id);
			if($del){
				$this->session->set_userdata('message','Data has been deleted.');
				$this->session->set_userdata('message_type','success');
			}
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('redeem');
	}
}