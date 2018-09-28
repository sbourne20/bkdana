<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		error_reporting(E_ALL);
	}
	
	public function index()
	{
	}

	function set_cookies()
	{
		$telp = trim($this->input->post('phoneNumber', TRUE));

		if ($telp != '' & strlen($telp) > 5) 
		{
			$memberdata = $this->Member_model->get_member_phone($telp);

			if ($memberdata['id_mod_user_member'] && $memberdata['mum_fullname']) {
				$data = array();
				$data['_bkdlog_']   = 1;	// login status
				$data['_bkdmail_']  = $memberdata['mum_email'];
				$data['_bkduser_']  = $memberdata['id_mod_user_member'];
				$data['_bkdname_']  = $memberdata['mum_fullname'];
				$this->session->set_userdata($data);
				$ret = 1;
			}else{
				$ret = 'Nomor Handphone Anda tidak terdaftar';
			}
		}else{
			$ret = 'Nomor Handphone Anda tidak terdaftar';
		}

		echo $ret;
	}

	function tenor_pinjaman_kilat()
	{
		$id_harga = $this->input->post('id_harga', TRUE);

		$data = $this->Content_model->product_by_harga($id_harga);

		$html = '';
		foreach ($data as $prod) {
			$html .= '<option value="'.$prod['Product_id'].'">'.$prod['Loan_term'].' hari</option>';
		}

		echo $html;
	}
	
}