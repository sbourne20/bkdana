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

	function add_topup()
	{
		$this->User_model->has_login();

		$mainData['add_mode'] = 1; // sbg tanda add new
		// $mainData['EDIT']     = NULL;
		$output['EDIT'] = $this->Topup_model->get_all_member2();
		// $output['member'] = $this->Topup_model->get_all_member();
		$mainData['member'] = $this->Topup_model->get_all_member();
		$a['top_css']   ="";
		$a['top_js']    ="";
		$a['bottom_js'] ="";
		
		$a['bottom_js'] .= add_js('js/global.js');
		$a['top_css']  .= add_css('plugins/bootstrap-datepicker/bootstrap-datepicker.css');
		$a['top_js'] .= add_js('plugins/bootstrap-datepicker/bootstrap-datepicker.js');
		$a['bottom_js'] .= add_js('js/data/topup.js');
		
		$a['mainContent'] = $this->load->view('topup/vmanual_topup', $mainData, TRUE);

		$this->load->view('vbase', $a);
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

	public function submit_topup()
	{	
		$post = $this->input->post(NULL, TRUE);

		$a['top_css']   ="";
		$a['top_js']    ="";
		$a['bottom_js'] ="";
		
		$a['bottom_js'] .= add_js('js/global.js');
		$a['top_css']  .= add_css('plugins/bootstrap-datepicker/bootstrap-datepicker.css');
		$a['top_js'] .= add_js('plugins/bootstrap-datepicker/bootstrap-datepicker.js');
		$a['bottom_js'] .= add_js('js/data/topup.js');

		$namamember  = trim($post['member']);
		$data = $this->Topup_model->get_all_member1($namamember);
		$get_master_wallet = $this->Wallet_model->get_wallet_bymember($namamember);
		//$mainData['member'] = $data['Id_pengguna'];

		//get id_grup from select in js
		$idusrgrp = $POST['Id_pengguna'];
		$secondP1 = antiInjection($this->uri->segment(3));
		if(isset($_POST['secondP1']))
		{
    		$usr = $_POST['secondP1'];

    		// Do whatever you want with the $uid
		}
		else{
			echo "tidak ada data";
		}


		$prefixID    = 'T';
		$orderID     = $prefixID.strtoupper(substr(uniqid(sha1(time().$namamember)),0,7));
        $exist_order = $this->Topup_model->check_topup_code($orderID);	// Cek if order ID exist on Database
			
		// jika order ID sudah ada di Database, generate lagi tambahkan datetime
		if (is_array($exist_order) && count($exist_order) > 0 )
		{
			$orderID = $prefixID.strtoupper(substr(uniqid(sha1(time().$namamember.date('yzGis'))),0,7));
		}

		$nowdatetime = date('Y-m-d H:i:s');
		$norekmember = trim($post['rekening']);
		$nominal     = trim($post['nominal']);
		$tgl_tu      = trim($post['from']);
		$notes       = trim($post['catatan']);

		$check_wallet_peminjam = $this->Wallet_model->get_wallet_bymember($namamember);
		

		if ( is_array($check_wallet_peminjam) && count($check_wallet_peminjam)>0 )
		{
			// update saldo peminjam
			$this->Wallet_model->update_master_wallet_saldo($namamember ,$nominal);
			$id_masterwallet_peminjam = $check_wallet_peminjam['Id'];
		}else{
			// insert saldo peminjam
			$inmwallet['Date_create']      = date('Y-m-d', strtotime($tgl_tu));
			$inmwallet['User_id']          = $namamember;
			$inmwallet['Amount']           = $nominal;
			$inmwallet['wallet_member_id'] = $namamember;

			$id_masterwallet_peminjam = $this->Wallet_model->insert_master_wallet($inmwallet);

		}

		//insert mdo_top_tup
		$insert_tu['kode_top_up']= $orderID;
		$insert_tu['member_id'] =antiInjection($namamember);
		$insert_tu['user_id'] =antiInjection($namamember);
		$insert_tu['nama_rekening_pengirim'] = $data['Nama_pengguna'];
		$insert_tu['nomor_rekening_pengirim'] =antiInjection($norekmember);
		$insert_tu['bank_pengirim'] = $data['nama_bank'];
		$insert_tu['bank_tujuan'] = "Bank CIMB";
		$insert_tu['jml_top_up'] = antiInjection($nominal);
		$insert_tu['tipe_top_up'] = 3;
		$insert_tu['tgl_top_up'] = date('Y-m-d', strtotime($tgl_tu));
		$insert_tu['tgl_perubahan_status'] = $nowdatetime;
		$insert_tu['flag_mail'] = 2;
		$insert_tu['payment_status'] ="settlement";

		$this->Topup_model->insert_topup($insert_tu);

		$detail_w['Id']               = $id_masterwallet_peminjam;
		$detail_w['Date_transaction'] = date('Y-m-d', strtotime($tgl_tu));
		$detail_w['Amount']           = $nominal;
		$detail_w['Notes']            = $notes;
		$detail_w['tipe_dana']        = 1;
		$detail_w['User_id']          = $namamember;
		$detail_w['kode_transaksi']   = $orderID;
		$detail_w['balance']          = $get_master_wallet['Amount'] + $detail_w['Amount'];
		$this->Wallet_model->insert_detail_wallet($detail_w);

			// $this->Wallet_model->update_master_wallet_saldo($namamember ,$nominal);

		redirect('top_up');
	}

	function rekening_tujuan()
	{
		if(isset($_POST['id_member']))
		{
			$usr = $_POST['id_member'];
			echo $usr;
		}else{
			echo "no data";
		}

		$member = $this->Topup_model->getmum($usr);
		print_r($member);

		$html = '';
		foreach ($member as $prod) {
			$html .= '<option value="'.$prod['id_mod_user_member'].'">'.$prod['mum_nomor_rekening'].' </option>';

		echo $html;
	}

	}
}