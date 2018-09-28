<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Top_up extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();
		$this->load->model('Member_model');
		error_reporting(E_ALL);

		$this->Content_model->has_login();
		$this->Content_model->is_active_pendana();
	}
	
	public function index()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;

		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);
		$data['history_topup']  = $this->Content_model->history_topup_member($uid);

		//_d($data['history_topup']);

		$data['pages']    = 'v_top_up';
		$this->load->view('template', $data);
	}

	function submit_manual()
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$post = $this->input->post(NULL, TRUE);

			$uid = htmlentities($_SESSION['_bkduser_']);

			if (trim($uid) == '' OR empty($uid)) {
				redirect('home');
				exit();
			}

			$prefixID    = 'TU-';
			$orderID     = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,10));
	        $exist_order = $this->Content_model->check_topup_code($orderID);	// Cek if order ID exist on Database
			
			// jika order ID sudah ada di Database, generate lagi tambahkan datetime
			if (is_array($exist_order) && count($exist_order) > 0 )
			{
				$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
			}

			$memberdata  = $this->Member_model->get_member_byid($uid);

			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');

			$akun_bank_name   = trim($post['account_bank_name']);
			$no_rekening      = trim($post['account_bank_number']);
			$my_bank_name     = trim($post['my_bank_name']);
			$bank_destination = trim($post['bank_destination']);
			$jml_topup        = trim($post['jml_topup']);

			if ($no_rekening != '' && strlen($no_rekening)>5 && $my_bank_name != '' && $bank_destination != '' &&  $jml_topup!= '' && strlen($jml_topup) >= 3 )
			{

				$filter = explode('.', $jml_topup);
				$total_topup = str_replace(',', '', $filter[0]);

				$indata['kode_top_up']             = $orderID;
				$indata['member_id']               = antiInjection($uid);
				$indata['user_id']                 = antiInjection($memberdata['Id_pengguna']);
				$indata['nama_rekening_pengirim']  = antiInjection($akun_bank_name);
				$indata['nomor_rekening_pengirim'] = antiInjection($no_rekening);
				$indata['bank_pengirim']           = antiInjection($my_bank_name);
				$indata['bank_tujuan']             = antiInjection($bank_destination);
				$indata['jml_top_up']              = antiInjection($total_topup);
				$indata['tipe_top_up']             = 1;  // 1. transfer, 2.virtual account
				$indata['tgl_top_up']              = $nowdatetime;
				$indata['status_top_up']           = 'pending';

				$topupID = $this->Content_model->insert_top_up($indata);

				if ($topupID) {
					$this->session->set_userdata('message','Sukses Top Up Transfer Manual');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','Error on Top Up!');
					$this->session->set_userdata('message_type','error');
				}
			}else{
				$this->session->set_userdata('message','Top Up gagal. Isilah semua kolom dengan benar.');
				$this->session->set_userdata('message_type','error');
			}

			redirect('top-up');
		}else{
			redirect('home');
		}
	}

	function submit_auto()
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$post = $this->input->post(NULL, TRUE);

			$uid = htmlentities($_SESSION['_bkduser_']);

			if (trim($uid) == '' OR empty($uid)) {
				redirect('home');
				exit();
			}

			$prefixID    = 'TU-';
			$orderID     = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,10));
	        $exist_order = $this->Content_model->check_topup_code($orderID);	// Cek if order ID exist on Database
			
			// jika order ID sudah ada di Database, generate lagi tambahkan datetime
			if (is_array($exist_order) && count($exist_order) > 0 )
			{
				$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
			}

			$memberdata  = $this->Member_model->get_member_byid($uid);

			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');

			$akun_bank_name   = trim($post['account_bank_name']);
			$no_rekening      = trim($post['account_bank_number']);
			$my_bank_name     = trim($post['my_bank_name']);
			$bank_destination = trim($post['bank_destination']);
			$jml_topup        = trim($post['jml_topup']);

			if ($no_rekening != '' && strlen($no_rekening)>5 && $my_bank_name != '' && $bank_destination != '' &&  $jml_topup!= '' && strlen($jml_topup) >= 3 )
			{

				$filter = explode('.', $jml_topup);
				$total_topup = str_replace(',', '', $filter[0]);

				$indata['kode_top_up']             = $orderID;
				$indata['member_id']               = antiInjection($uid);
				$indata['user_id']                 = antiInjection($memberdata['Id_pengguna']);
				$indata['nama_rekening_pengirim']  = antiInjection($akun_bank_name);
				$indata['nomor_rekening_pengirim'] = antiInjection($no_rekening);
				$indata['bank_pengirim']           = antiInjection($my_bank_name);
				$indata['bank_tujuan']             = antiInjection($bank_destination);
				$indata['jml_top_up']              = antiInjection($total_topup);
				$indata['tipe_top_up']             = 2;  // 1. transfer manual, 2.virtual account
				$indata['tgl_top_up']              = $nowdatetime;
				$indata['status_top_up']           = 'pending';

				$topupID = $this->Content_model->insert_top_up($indata);

				if ($topupID) {
					$this->session->set_userdata('message','Sukses Top Up Transfer Manual');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','Error on Top Up!');
					$this->session->set_userdata('message_type','error');
				}
			}else{
				$this->session->set_userdata('message','Top Up gagal. Isilah semua kolom dengan benar.');
				$this->session->set_userdata('message_type','error');
			}

			redirect('top-up');
		}else{
			redirect('home');
		}
	}
	
}