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
			$html .= '<option value="'.$prod['Product_id'].'">'.$prod['Loan_term'].' '. $prod['type_of_interest_rate_name'].' </option>';
		}

		echo $html;
	}

	function register_check()
	{
			$post = $this->input->post(NULL, TRUE);

			$fullname    = trim($post['fullname']);
			$notelp      = trim($post['telp']);
			$email       = trim($post['email']);
			$password    = trim($post['password']);
			$repassword  = trim($post['confirm_password']);

			//$total_pinjam = trim($post['jumlah_pinjam']);
			//$productID    = trim($post['product']);

			$check = $this->Content_model->check_existing_member($email, $notelp, '');
			$count_member = count((array)$check);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$ret = array('error'=> '1', 'message'=>'Invalid Email format!');

			}else if ( $count_member > 1){
				$ret = array('error'=> '1', 'message'=>'Email atau No.Telp Anda sudah terdaftar!');

			}else if ( $password == '' OR strlen($password) < 6 ) {
				$ret = array('error'=> '1', 'message'=>'Password minimal 6 karakter, kombinasi huruf dan angka, dan minimal 1 huruf besar.');

			}else if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-zA-Z]).*$/", $password) === 0) {
				// min 6 karakter, terdiri dari minimum 1 huruf, minimum 1 angka
				$ret = array('error'=> '1', 'message'=>'Password harus terdiri dari huruf kecil dan besar serta angka');
			
			}else if ( $password != $repassword ) {
				$ret = array('error'=> '1', 'message'=>'Password dan Konfirmasi Password tidak sama!');
			}else{
				// All OK
				$ret = array('error'=> '0', 'message'=>'');
			}

			echo json_encode($ret);
	}

	function register_check_pendana()
	{
			$post = $this->input->post(NULL, TRUE);

			$fullname    = trim($post['fullname']);
			$notelp      = trim($post['telp']);
			$email       = trim($post['email']);
			$password    = trim($post['password']);
			$repassword  = trim($post['confirm_password']);
			$sumberdana  = trim($post['sumberdana']);

			//$total_pinjam = trim($post['jumlah_pinjam']);
			//$productID    = trim($post['product']);

			$check = $this->Content_model->check_existing_member($email, $notelp, '');
			$count_member = count((array)$check);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$ret = array('error'=> '1', 'message'=>'Invalid Email format!');

			}else if ( $count_member > 1 && isset($check['id_mod_user_member'])){
				$ret = array('error'=> '1', 'message'=>'Email atau No.Telp Anda sudah terdaftar!');

			}else if ( $password == '' OR strlen($password) < 6 ) {
				$ret = array('error'=> '1', 'message'=>'Password minimal 6 karakter, kombinasi huruf dan angka, dan minimal 1 huruf besar.');

			}else if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-zA-Z]).*$/", $password) === 0) {
				// min 6 karakter, terdiri dari minimum 1 huruf, minimum 1 angka
				$ret = array('error'=> '1', 'message'=>'Password harus terdiri dari huruf kecil dan besar serta angka');
			
			}else if ( $password != $repassword ) {
				$ret = array('error'=> '1', 'message'=>'Password dan Konfirmasi Password tidak sama!');
			}else if ( $sumberdana == '' ) {
				$ret = array('error'=> '1', 'message'=>'Sumber Dana harus diisi!');
			}else{
				// All OK
				$ret = array('error'=> '0', 'message'=>'');
			}

			echo json_encode($ret);
	}
	
}