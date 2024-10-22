<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		error_reporting(E_ALL);
	}
	
	public function ubah_profil()
	{
		$this->Content_model->has_login();

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['top_css'] .= add_css('js/bootstrap-datepicker/css/bootstrap-datepicker.css');
		$data['top_css'] .= add_css('js/alertify/css/alertify.min.css');
		$data['top_css'] .= add_css('js/alertify/css/themes/default.min.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/bootstrap-datepicker/js/bootstrap-datepicker.min.js');
		$data['bottom_js'] .= add_js('js/jqueryvalidation/dist/jquery.validate.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/alertify/alertify.min.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/date-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		
		$uid = htmlentities($_SESSION['_bkduser_']);
		$data['memberid']  = $uid;

		$data['memberdata'] = $this->Member_model->user_alldata($uid);
		$data['total_pinjaman'] = $this->Content_model->get_jml_pinjam($uid);
		$data['total_invest']   = $this->Content_model->get_jml_invest($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

//_d($data['memberdata']);
		$data['pages']    = 'v_profil_edit';
		$this->load->view('template', $data);
	}

	function submit_ubah_profil()
	{
		$this->Content_model->has_login();
		$post = $this->input->post(NULL, TRUE);

		if (trim($post['fullname']) != '' && trim($post['telp']) != '' )
		{
			$uid         = antiInjection(htmlentities(trim($_SESSION['_bkduser_'])));
			$logintype   = htmlentities($_SESSION['_bkdtype_']); 
			$notelp      = trim($post['telp']);

			if ($uid && $uid != ''
				&& trim($post['fullname']) != ''
				&& trim($post['nomor_ktp']) != ''
				&& trim($post['nomor_rekening']) != ''
				&& trim($post['tempat_lahir']) != ''
				&& trim($post['tgl_lahir']) != ''
				&& trim($post['gender']) != ''
				&& trim($post['pekerjaan']) != ''
				&& trim($post['alamat']) != ''
				//&& trim($post['kodepos']) != ''
				&& trim($post['kota']) != ''
				&& trim($post['provinsi']) != ''
			) {
				$memberdata = $this->Member_model->get_member_byid($uid);

			//_d($memberdata);exit();

				if (is_array($memberdata) && count($memberdata)>0 && isset($memberdata['Id_pengguna'])) {

					$userID = $memberdata['Id_pengguna'];
					
					$mem_data['mum_fullname']    = trim($post['fullname']);
					$mem_data['mum_telp']        = $notelp;
					$mem_data['mum_ktp']         = trim($post['nomor_ktp']);
					$mem_data['mum_nomor_rekening'] = trim($post['nomor_rekening']);

					$this->Member_model->update_member_byid($uid, $mem_data);

					// user
					$user['Nama_pengguna']      = trim($post['fullname']);
					$user['Id_ktp']             = trim($post['nomor_ktp']);
					$user['Tempat_lahir']       = trim($post['tempat_lahir']);
					$user['Tanggal_lahir']      = date('Y-m-d', strtotime(trim($post['tgl_lahir'])));
					$user['Jenis_kelamin']      = $post['gender'];
					$user['Pekerjaan']          = $post['pekerjaan'];	// 1=PNS,2=BUMN, 3=Swasta, 4=wiraswasta, 5=lain-lain 
					$user['Nomor_rekening']     = trim($post['nomor_rekening']);

					$this->Content_model->update_user($uid, $user);

					// user_detail
					$u_detail['Mobileno']          = $notelp;
					//$u_detail['Profile_photo']     = $upload_foto;
					//$u_detail['Photo_id']          = $upload_ktp;
					$u_detail['Occupation']        = trim($post['pekerjaan']);
					$u_detail['ID_No']             = trim($post['nomor_ktp']);
					//$u_detail['images_foto_name '] = $file_foto_name;
					//$u_detail['images_ktp_name ']  = $file_ktp_name;

					$this->Content_model->update_userdetail($userID, $u_detail);
					
					// profile_geografi
					$u_geo['Alamat']      = trim($post['alamat']);
					$u_geo['Kodepos']     = trim($post['kodepos']);
					$u_geo['Kota']        = trim($post['kota']);
					$u_geo['Provinsi']    = trim($post['provinsi']);

					$this->Content_model->update_profil_geografi($userID, $u_geo);

					// ranking
					$get_ranking = set_ranking_pengguna($userID, $logintype, $memberdata['mum_type_peminjam']);

					$update_pengguna['peringkat_pengguna']            = $get_ranking['grade'];
					$update_pengguna['peringkat_pengguna_persentase'] = $get_ranking['ranking'];
					$this->Content_model->update_user_byid($userID, $update_pengguna);

					$this->session->set_userdata('message','Sukses ubah profil');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','Data tidak ditemukan');
					$this->session->set_userdata('message_type','error');
				}
			}else{
				$this->session->set_userdata('message','Isilah semua kolom dengan benar.');
				$this->session->set_userdata('message_type','error');
				redirect('ubah-profil');
				exit();
			}

			redirect('dashboard');
			exit();
		}else{
			$this->session->set_userdata('message','Isilah semua kolom dengan benar.');
			$this->session->set_userdata('message_type','error');
			redirect('ubah-profil');
			exit();
		}

	}
	
}