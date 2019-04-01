<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pinjaman extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();
		error_reporting(E_ALL);
	}
	
	public function index()
	{}

	public function kilat()
	{
		$this->Content_model->has_login();

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['top_css'] .= add_css('js/bootstrap-datepicker/css/bootstrap-datepicker.css');
		//$data['top_js'] .= add_js('js/firebase-init.js');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/bootstrap-datepicker/js/bootstrap-datepicker.min.js');
		$data['bottom_js'] .= add_js('js/jqueryvalidation/dist/jquery.validate.min.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/form-wizard-kilat.js');
		$data['bottom_js'] .= add_js('js/date-init.js');

		$data['title']    = 'BKD';
		//$data['meta_tag'] = $this->m_settings->meta_default();

		$uid = antiInjection($_SESSION['_bkduser_']);
		$data['member'] = $this->Content_model->get_memberdata($uid);

		$data['pages']    = 'v_form_pinjaman_kilat';
		$this->load->view('template', $data);
	}

	function submit_p_kilat()
	{
		$post = $this->input->post(NULL, TRUE);

		$nowdate   = date('Y-m-d');
		$fullname  = trim($post['fullname']);
		$notelp    = trim($post['telp']);
		$productID = 1;

		if ($fullname != '' && $notelp != '' && trim($post['nomor_ktp'])!='' && trim($post['tempat_lahir'])!='' && trim($post['nomor_rekening'])!='' && trim($post['jumlah_pinjam'])!='' && trim($post['alamat'])!='' && trim($post['kodepos'])!='' && trim($post['kota'])!='' && trim($post['provinsi'])!='' && trim($post['jumlah_pinjam'])!='' ) {

			$upload_foto = file_get_contents($_FILES['foto_file']['tmp_name']);
			$upload_ktp  = file_get_contents($_FILES['ktp_file']['tmp_name']);

			// user
			$user['Tgl_record ']    = $nowdate;
			$user['Nama_pengguna']  = $fullname;
			$user['Jenis_pengguna'] = 1; // 1.orang, 2.badan hukum
			$user['Id_ktp']         = $post['nomor_ktp'];
			$user['Tempat_lahir']   = trim($post['tempat_lahir']);
			$user['Tanggal_lahir']  = date('Y-m-d', strtotime(trim($post['tgl_lahir'])));
			$user['Jenis_kelamin']  = $post['gender'];
			$user['Pekerjaan']      = $post['pekerjaan'];	// 1=PNS,2=BUMN, 3=Swasta, 4=wiraswasta, 5=lain-lain 
			$user['Nomor_rekening'] = $post['nomor_rekening'];

			$userID = $this->Content_model->insert_user($user);

			// user_detail
			$u_detail['Id_pengguna']   = $userID;
			$u_detail['Mobileno']      = $notelp;
			$u_detail['Profile_photo'] = $upload_foto;
			$u_detail['Photo_id']      = $upload_ktp;
			$u_detail['Jumlah_permohonan_pinjaman '] = trim($post['jumlah_pinjam']);

			$this->Content_model->insert_userdetail($u_detail);
			
			// profile_geografi
			$u_geo['Agama']       = $post['agama'];
			$u_geo['Alamat']      = $post['alamat'];
			$u_geo['Kodepos']     = $post['kodepos'];
			$u_geo['Kota']        = $post['kota'];
			$u_geo['Provinsi']    = $post['provinsi'];
			$u_geo['User_id']     = $userID;

			$this->Content_model->insert_profil_geografi($u_geo);		

			// profil_permohonan_pinjaman
			$p_pinjam['Tgl_permohonan_pinjaman '] = $nowdate;
			$p_pinjam['Jml_permohonan_pinjaman '] = trim($post['jumlah_pinjam']);
			$p_pinjam['User_id ']                 = $userID;
			$p_pinjam['Product_id ']              = $productID;

			$this->Content_model->insert_profil_pinjaman($p_pinjam);

			redirect('dashboard');
		}else{
			$this->session->set_userdata('message','Isilah semua kolom.');
			$this->session->set_userdata('message_type','error');
			redirect('formulir-pinjaman-kilat');
			exit();
		}
	}

	public function mikro()
	{
		$this->Content_model->has_login();

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['top_css'] .= add_css('js/bootstrap-datepicker/css/bootstrap-datepicker.css');
		//$data['top_js'] .= add_js('js/firebase-init.js');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/bootstrap-datepicker/js/bootstrap-datepicker.min.js');
		$data['bottom_js'] .= add_js('js/jqueryvalidation/dist/jquery.validate.min.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/form-wizard.js');
		$data['bottom_js'] .= add_js('js/date-init.js');

		$data['title']    = 'BKD';
		//$data['meta_tag'] = $this->m_settings->meta_default();

		$uid = antiInjection($_SESSION['_bkduser_']);
		$data['member'] = $this->Content_model->get_memberdata($uid);

		$data['pages']    = 'v_form_pinjaman_mikro';
		$this->load->view('template', $data);
	}

	function submit_p_mikro()
	{
		$post = $this->input->post(NULL, TRUE);

		$nowdate   = date('Y-m-d');
		$fullname  = trim($post['fullname']);
		$notelp    = trim($post['telp']);
		$productID = 2;

		if ($fullname != '' && $notelp != '' && trim($post['nomor_ktp'])!='' && trim($post['tempat_lahir'])!='' && trim($post['nomor_rekening'])!='' && trim($post['jumlah_pinjam'])!='' && trim($post['alamat'])!='' && trim($post['kodepos'])!='' && trim($post['kota'])!='' && trim($post['provinsi'])!='' && trim($post['jumlah_pinjam'])!='' ) {

			$upload_foto       = file_get_contents($_FILES['foto_file']['tmp_name']);
			$upload_ktp        = file_get_contents($_FILES['ktp_file']['tmp_name']);
			$upload_foto_usaha = file_get_contents($_FILES['usaha_file']['tmp_name']);

			// user
			$user['Tgl_record ']    = $nowdate;
			$user['Nama_pengguna']  = $fullname;
			$user['Jenis_pengguna'] = 2; // 1.orang, 2.badan hukum
			$user['Nama_badan_hukum'] = trim($post['usaha']);
			$user['Id_ktp']           = trim($post['nomor_ktp']);
			$user['Tempat_lahir']     = trim($post['tempat_lahir']);
			$user['Tanggal_lahir']    = date('Y-m-d', strtotime(trim($post['tgl_lahir'])));
			$user['Jenis_kelamin']    = trim($post['gender']);
			$user['Pekerjaan']        = trim($post['pekerjaan']);	// 1=PNS,2=BUMN, 3=Swasta, 4=wiraswasta, 5=lain-lain 
			$user['Nomor_rekening']   = trim($post['nomor_rekening']);

			$userID = $this->Content_model->insert_user($user);

			// user_detail
			$u_detail['Id_pengguna']   = $userID;
			$u_detail['Mobileno']      = $notelp;
			$u_detail['Profile_photo'] = $upload_foto;
			$u_detail['Photo_id']      = $upload_ktp;
			$u_detail['Jumlah_permohonan_pinjaman '] = trim($post['jumlah_pinjam']);
			$u_detail['What_is_the_name_of_your_business'] = trim($post['usaha']);
			$u_detail['How_many_years_have_you_been_in_business'] = trim($post['lama_usaha']);
			$u_detail['foto_usaha']    = $upload_foto_usaha;

			$this->Content_model->insert_userdetail($u_detail);
			
			// profile_geografi
			$u_geo['Agama']       = $post['agama'];
			$u_geo['Alamat']      = $post['alamat'];
			$u_geo['Kodepos']     = $post['kodepos'];
			$u_geo['Kota']        = $post['kota'];
			$u_geo['Provinsi']    = $post['provinsi'];
			$u_geo['User_id']     = $userID;

			$this->Content_model->insert_profil_geografi($u_geo);		

			// profil_permohonan_pinjaman
			$p_pinjam['Tgl_permohonan_pinjaman '] = $nowdate;
			$p_pinjam['Jml_permohonan_pinjaman '] = trim($post['jumlah_pinjam']);
			$p_pinjam['User_id ']                 = $userID;
			$p_pinjam['Product_id ']              = $productID;

			$this->Content_model->insert_profil_pinjaman($p_pinjam);

			redirect('dashboard');
		}else{
			$this->session->set_userdata('message','Isilah semua kolom.');
			$this->session->set_userdata('message_type','error');
			redirect('formulir-pinjaman-kilat');
			exit();
		}
	}
}