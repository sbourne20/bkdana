<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekening_saya extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		$this->load->model('Wallet_model');
	}
	public function index()
	{
		$this->Content_model->has_login();

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/jquery-loading-overlay/dist/loadingoverlay.min.js');
		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/transaction.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana');
		
		
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		

		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_pinjaman'] = $this->Content_model->get_jml_pinjam($uid);
		$data['total_invest']   = $this->Content_model->get_jml_invest($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$data['list_redeem']    = $this->Content_model->get_list_myredeem($uid);
		

		$data['pages']    = 'v_rekening_saya';
		$this->load->view('template', $data);
	}

	function submit()
	{
		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/jquery-loading-overlay/dist/loadingoverlay.min.js');
		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/transaction.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana');
		
		$data['memberdata']   = $this->Member_model->get_member_byid($uid);
		$data['mywallet']     = $this->Wallet_model->get_wallet_bymember($uid);

		$post = $this->input->post(NULL, TRUE);

		$code           = trim($post['no_transaksi']);
		$data['detail'] = $this->Content_model->get_redeem_bycode($code);
		
		// print_r($data['detail']);

		$data['pages']    = 'v_rekening_saya_submit';
		$this->load->view('template', $data);
	}
}