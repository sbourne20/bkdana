<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		
		//error_reporting(E_ALL);
	}
	
	public function index()
	{
		$this->Content_model->has_login();

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'dashboard');

		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		

		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_pinjaman'] = $this->Content_model->get_jml_pinjam($uid);
		$data['total_invest']   = $this->Content_model->get_jml_invest($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		if ($logintype == '1') {
			$data['list_transaksi']  = $this->Content_model->get_my_transactions_pinjam_approve($uid, 5, 0);
			$data['analyst_approved']  = $this->Content_model->get_my_transactions_analyst_approved($uid, 5, 0);

			$data['pinjaman_active'] = $this->Content_model->check_active_pinjaman($uid);
			$data['pages']           = 'v_dashboard';
		}else{
			$data['list_transaksi'] = $this->Content_model->get_my_transactions_pendana($uid, 5, 0);
			$data['pages']          = 'v_dashboard_pendana';
		}		

		//_d($data['list_transaksi']);

		$this->load->view('template', $data);
	}
	
}