<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_peminjam extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();
		error_reporting(E_ALL);
	}
	
	public function index()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		//$data['top_js'] .= add_js('js/firebase-init.js');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title']    = 'BKD';
		//$data['meta_tag'] = $this->m_settings->meta_default();

		$data['referal'] = $this->uri->segment(1);

		$data['pages']    = 'v_daftar_peminjam';
		$this->load->view('template', $data);
	}

	function submit_daftar()
	{
		// === Daftar Only === //

		$post    = $this->input->post(NULL, TRUE);
		$referal = trim($post['referal']);
		$email   = filter_var(trim($post['email']), FILTER_SANITIZE_EMAIL);

		if (trim($post['fullname']) == '' OR trim($post['email']) == '' OR trim($post['handphone']) == '' OR trim($post['password']) == '' OR trim($post['confirm_password']) == '' )
		{
			$this->session->set_userdata('message','Isilah semua kolom formulir pendaftaran.');
			$this->session->set_userdata('message_type','error');
			redirect($referal);
			exit();
		}

		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) 
		{
			$this->session->set_userdata('message','isilah Email dengan format yang benar.');
			$this->session->set_userdata('message_type','error');
			redirect($referal);
			exit();
		}else{
			$check = $this->Content_model->check_existing_member($email);

			if (is_array($check) && $check['mum_email'] != ''){
				$this->session->set_userdata('message','Email Anda sudah terdaftar.');
				$this->session->set_userdata('message_type','error');
				redirect($referal);
				exit();
			}
		}

		if (strlen(trim($post['password'])) < 6)
		{
			$this->session->set_userdata('message','Password minimal 6 karakter.');
			$this->session->set_userdata('message_type','error');
			redirect($referal);
			exit();
		}else if (strlen(trim($post['password'])) >= 6 && $post['password'] == $post['confirm_password'] ) {

			$stored_p = password_hash(base64_encode(hash('sha256', (trim($post['password'])), true)), PASSWORD_DEFAULT);

			$nowdatetime = date('Y-m-d H:i:s');
			$nowdate = date('Y-m-d');

			$pinjamdata['Tgl_record']     = $nowdate;
			$pinjamdata['Nama_pengguna']  = trim($post['fullname']);
			$pinjamdata['Jenis_pengguna'] = 1;

			$ID_pengguna = $this->Content_model->insert_peminjam($pinjamdata);

			$indata['mum_fullname'] = trim($post['fullname']);
			$indata['mum_email']    = trim($post['email']);
			$indata['mum_telp']     = trim($post['handphone']);
			$indata['mum_password'] = $stored_p;
			// $indata['mum_ktp']      = $ktp;
			// $indata['mum_npwp']     = $npwp;
			$indata['mum_status']   = 1;
			$indata['mum_create_date'] = $nowdatetime;
			$indata['mum_id_pengguna'] = $ID_pengguna;

			$this->Content_model->insert_mod_usermember($indata);			

			header('HTTP/1.1 307 Temporary Redirect');
			header('Location: '.site_url('input-otp'));

		}else{
			$this->session->set_userdata('message','Password dan Konfirmasi Password tidak sama.');
			$this->session->set_userdata('message_type','error');
			redirect($referal);
			exit();
		}
	}

	function input_otp()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_js'] .= add_js('js/firebase-init.js');

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/form-wizard.js');

		$data['title']    = 'BKD';
		//$data['meta_tag'] = $this->m_settings->meta_default();

		$post = $this->input->post(NULL, TRUE);
		$data['pages']    = 'v_input_otp';
		$data['postdata'] = $post;

		$referal = empty($post['referal'])? 'kilat' : trim($post['referal']);

		// daftar-pinjaman-kilat atau daftar-pinjaman-mikro atau daftar-pinjaman-usaha
		
		if (strpos($referal, 'kilat') !== false) {
			$redirect_uri = 'formulir-pinjaman-kilat';
		}else if (strpos($referal, 'mikro') !== false) {
			$redirect_uri = 'formulir-pinjaman-mikro';
		}else if (strpos($referal, 'usaha') !== false) {
			$redirect_uri = 'formulir-pinjaman-usaha';
		}else{
		}

		$data['redirect_uri'] = $redirect_uri;

		$this->load->view('template', $data);
	}
}