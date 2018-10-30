<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekening_koran extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();
		$this->load->model('Member_model');
		$this->load->model('Wallet_model');
		//error_reporting(E_ALL);

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
		
		$data['rk'] = $this->Wallet_model->all_wallet_detail($uid);
		//_d($data['rk']);

		$data['pages']    = 'v_rekening_koran';
		$this->load->view('template', $data);
	}
	
}