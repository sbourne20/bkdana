<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aktivasi extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		$this->load->library('encryption');
		error_reporting(E_ALL);
	}
	
	public function index()
	{
		/* Aktivasi via email setelah register */

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		
		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/firebase-init.js');

		$data['title']    = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('aktivasi', 'aktivasi akun');
		
		$ciphertext = $this->input->get('t', TRUE);
		$email      = trim(antiInjection(urldecode($this->encryption->decrypt($ciphertext))));

		if ($email != '' && filter_var($email, FILTER_VALIDATE_EMAIL) ) 
		{
			$member = $this->Member_model->get_member_by($email);
			
			if ( count($member) > 0 && isset($member['id_mod_user_member']) )
			{
				if ($member['mum_type'] == 1){
					$active_status = 1; // peminjam
				}else{
					$active_status = 2;	// pendana. aktif tapi tidak bisa transaksi karena harus di approve via cms.
				}
				$affected = $this->Member_model->verify_member_byid($member['id_mod_user_member']);

				if ($affected) {
					$set_otp = $this->Member_model->set_cookies_otp($email); // set cookies for OTP login controller login/login_otp
					redirect('input-otp');
					exit();
				}
			}
		}

		redirect('login');

	}
}