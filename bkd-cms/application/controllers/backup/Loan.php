<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loan extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Pinjaman_model');
		$this->load->model('Product_model');
		$this->load->model('Wallet_model');
		
		error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Loan';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/loan.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mainData['mainContent']  = $this->load->view('loan/vlist', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->Pinjaman_model->get_all_dt();
		print_r($data);
	}

	function detail()
	{
		$id = $this->uri->segment(3);

		$output['data'] = $this->Pinjaman_model->get_loan_byid($id);

	//	print_r($output['data']);
		header('Content-type: image/jpeg');

		if ($output['data']['id_mod_type_business']=='1') {
			// Kilat
			$this->load->view('loan/vdetail', $output);
		}else if ($output['data']['id_mod_type_business']=='3') {
			// Mikro
			$this->load->view('loan/vdetail_mikro', $output);
		}else{
			$this->load->view('loan/vdetail', $output);			
		}
	}

	function delete()
	{
		$this->User_model->has_login();

		$id = $this->uri->segment(3);

		$del = $this->Pinjaman_model->delete_($id);
		if($id && $del){

			$this->session->set_userdata('message','Data has been deleted.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('loan');
	}

	function approve()
	{
		$this->User_model->has_login();

		$id = antiInjection($this->uri->segment(3));

		// get data pinjaman
		$loan_data = $this->Pinjaman_model->get_pinjaman_byid($id);

		// get tipe produk: kilat, mikro ,usaha
		$produk = $this->Product_model->get_product_by($loan_data['Product_id']);
		$tipe_produk = $produk['type_of_business_id'];
		
		if (is_array($loan_data)) {
			$pinjaman_rp = $loan_data['Jml_permohonan_pinjaman'];

			// hitung total pinjaman + bunga
			$bunga       = $pinjaman_rp * ($produk['Interest_rate'] + $produk['Platform_rate'] + $produk['Investor_return'] + $produk['Secured_loan_fee']);
			$bunga       = $bunga/100;
			$total_pinjaman_plus_bunga = $pinjaman_rp + $bunga;

			// hitung total fundraise date (tgl maximum pendanaan)
			$fundraise = $produk['Fundraising_period'];
			$date_fundraise = date('Y-m-d', strtotime('+ '.$fundraise.' days'));
			

			if ($tipe_produk == '3' OR $tipe_produk == '4') {
				$affected    = $this->Pinjaman_model->pending_pinjaman($id, $total_pinjaman_plus_bunga, $date_fundraise);
			}else{
				$affected    = $this->Pinjaman_model->approve_pinjaman($id, $total_pinjaman_plus_bunga);
			}
		}else{
			$affected = FALSE;
		}
		
		if($id && $affected){

			if ($tipe_produk == 1) {
				// -- Hanya produk kilat yang langsung masuk Saldo ke Wallet  --
				
				$check_wallet = $this->Pinjaman_model->get_wallet_user($loan_data['User_id']);

				$nowdate = date('Y-m-d H:i:s');
				$tambahsaldo = $loan_data['Jml_permohonan_pinjaman'];

				if (is_array($check_wallet) && isset($check_wallet['User_id'])) {
					// update master
					$this->Wallet_model->update_master_wallet_saldo($loan_data['User_id'], $tambahsaldo);

					$master_wallet_id = $check_wallet['Id'];
				}else{
					// insert master
					$inwallet['Date_create']      = $nowdate;
					$inwallet['User_id']          = $loan_data['User_id'];
					$inwallet['Amount']           = $tambahsaldo;
					$inwallet['wallet_member_id'] = $loan_data['pinjam_member_id'];

					$master_wallet_id = $this->Pinjaman_model->insert_master_wallet($inwallet);
				}

				// detail wallet
				$detail_w['Id']               = $master_wallet_id;
				$detail_w['Date_transaction'] = $nowdate;
				$detail_w['Amount']           = $tambahsaldo;
				$detail_w['Notes']            = NULL;

				$this->Pinjaman_model->insert_detail_wallet($detail_w);
			}

			$this->session->set_userdata('message','Data has been Approved.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','No Data selected.');
			$this->session->set_userdata('message_type','warning');
		}
		
		redirect('loan');
	}

	function reject()
	{
		$this->User_model->has_login();

		$id = antiInjection($this->uri->segment(3));

		$loan_data = $this->Pinjaman_model->get_pinjaman_byid($id);
		
		if (is_array($loan_data)) {
			$affected = $this->Pinjaman_model->reject_pinjaman($id);
		}else{
			$affected = FALSE;
		}

		if ($id && $affected) {
			$this->session->set_userdata('message','Transaksi '.$id.' was Rejected.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','No Data selected.');
			$this->session->set_userdata('message_type','warning');
		}
		
		redirect('loan');
	}
}