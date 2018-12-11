<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		//error_reporting(E_ALL);
	}
	
	public function ubah_profil()
	{
		$this->Content_model->has_login();

		if ($this->uri->segment(1) == 'ubah-profil') {
			$data['page_title'] = 'Ubah Profil';
		}else{
			$data['page_title'] = 'Lengkapi Data Profil';			
		}

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['top_css'] .= add_css('js/bootstrap-datepicker/css/bootstrap-datepicker.css');
		$data['top_css'] .= add_css('js/alertify/css/alertify.min.css');
		$data['top_css'] .= add_css('js/alertify/css/themes/default.min.css');
		$data['top_css'] .= add_css("js/fileinput/fileinput.min.css");

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/bootstrap-datepicker/js/bootstrap-datepicker.min.js');
		$data['bottom_js'] .= add_js('js/jqueryvalidation/dist/jquery.validate.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/alertify/alertify.min.js');
		$data['bottom_js'] .= add_js("js/fileinput/fileinput.min.js");
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/date-init.js');
		$data['bottom_js'] .= add_js('js/fileinput-edit.js');
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
		$score = 0;
		//$this->Content_model->has_login();
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$post = $this->input->post(NULL, TRUE);

			if (trim($post['fullname']) != '' && trim($post['telp']) != '' )
			{
				$uid         = antiInjection(htmlentities(trim($_SESSION['_bkduser_'])));
				$logintype   = htmlentities($_SESSION['_bkdtype_']); 
				$notelp      = trim($post['telp']);
				$kota        = trim($post['kota']);
				$provinsi    = trim($post['provinsi']);

				if ($uid && $uid != ''
					&& trim($post['fullname']) != ''
					&& trim($post['nomor_ktp']) != ''
					&& trim($post['nomor_rekening']) != ''
					&& trim($post['nama_bank']) != ''
					//&& trim($post['tempat_lahir']) != ''
					//&& trim($post['tgl_lahir']) != ''
					&& trim($post['gender']) != ''
					&& trim($post['pekerjaan']) != ''
					&& trim($post['alamat']) != ''
					//&& trim($post['kodepos']) != ''
					&& trim($post['kota']) != ''
					&& trim($post['provinsi']) != ''
				) {
					$memberdata = $this->Member_model->get_member_byid($uid);

				//_d($_FILES);exit();

				//_d($memberdata);exit();

					if (is_array($memberdata) && count($memberdata)>0 && isset($memberdata['Id_pengguna'])) {

						// hitung usia
						$tgl_lahir = date('Y-m-d', strtotime(trim($post['tgl_lahir'])));
						$biday = new DateTime($tgl_lahir);
						$today = new DateTime();
						$diff = $today->diff($biday);
						$usia = $diff->y;
						
						if ($usia >= 20 && $usia <= 30 ) {
							$score = $score + 30;
						}else if ($usia >= 31 && $usia <= 40 ) {
							$score = $score + 50;
						}else if ($usia >= 41 && $usia <= 50 ) {
							$score = $score + 40;
						}else if ($usia >= 51 && $usia <= 60 ) {
							$score = $score + 20;
						}else if ($usia >= 61 ) {
							$score = $score + 10;
						}

						//  jakarta nilai 80, Botabek 60, non jabotabek 40
						$kota    = strtolower($kota);
						//$botabek = 'bogor tangerang bekasi';

						if ( $provinsi == 'DKI Jakarta')
						{
							$score = $score + 80;
						}else {
							if ( strpos($kota, 'bogor') !== false OR strpos($kota, 'tangerang') !== false OR strpos($kota, 'bekasi') !== false  ) {
								$score = $score + 60;
							}else{
								$score = $score + 40;
							}
						}

						if($_FILES['foto_file']['name'] == ''){
							$file_foto_name   = '';
						}else{
							//$upload_foto = file_get_contents($_FILES['foto_file']['tmp_name']);
							// ----- Process Image Name -----
							$img_info          = pathinfo($_FILES['foto_file']['name']);
							$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
							$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
							$fileExt           = $img_info['extension'];
							$file_foto_name   = $fileName.'.'.$fileExt;
							// ----- END Process Image Name -----
							//$u_detail['Profile_photo']    = $upload_foto;
							$u_detail['images_foto_name'] = $file_foto_name;
						
						}

						if($_FILES['ktp_file']['name'] == ''){
							$file_ktp_name   = '';
						}else{
							//$upload_ktp  = file_get_contents($_FILES['ktp_file']['tmp_name']);
							// ----- Process Image Name -----
							$img_info          = pathinfo($_FILES['ktp_file']['name']);
							$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
							$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
							$fileExt           = $img_info['extension'];
							$file_ktp_name   = $fileName.'.'.$fileExt;
							// ----- END Process Image Name -----
							//$u_detail['Photo_id']         = $upload_ktp;
							$u_detail['images_ktp_name']  = $file_ktp_name;
						}

						if ($memberdata['mum_type_peminjam']=='2'){//tambahan baru pengkondisian

							if( isset($_FILES['usaha_file']['name']) && $_FILES['usaha_file']['name'] == ''){
								$file_usaha_name   = '';
							}else{
								//$upload_usaha = file_get_contents($_FILES['usaha_file']['tmp_name']);
								// ----- Process Image Name -----
								$img_info          = pathinfo($_FILES['usaha_file']['name']);
								$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
								$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
								$fileExt           = $img_info['extension'];
								$file_usaha_name   = $fileName.'.'.$fileExt;
								// ----- END Process Image Name -----
								//$u_detail['Photo_business_location'] = $upload_usaha;
								//$u_detail['foto_usaha']              = $upload_usaha;
								$u_detail['images_usaha_name']       = $file_usaha_name;
							}
								if( isset($_FILES['usaha_file2']['name']) && $_FILES['usaha_file2']['name'] == ''){
								$file_usaha_name2   = '';
							}else{
								//$upload_usaha = file_get_contents($_FILES['usaha_file']['tmp_name']);
								// ----- Process Image Name -----
								$img_info          = pathinfo($_FILES['usaha_file2']['name']);
								$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
								$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
								$fileExt           = $img_info['extension'];
								$file_usaha_name2   = $fileName.'.'.$fileExt;
								// ----- END Process Image Name -----
								//$u_detail['Photo_business_location'] = $upload_usaha;
								//$u_detail['foto_usaha']              = $upload_usaha;
								$u_detail['images_usaha_name2']       = $file_usaha_name2;
							}
								if( isset($_FILES['usaha_file3']['name']) && $_FILES['usaha_file3']['name'] == ''){
								$file_usaha_name3  = '';
							}else{
								//$upload_usaha = file_get_contents($_FILES['usaha_file']['tmp_name']);
								// ----- Process Image Name -----
								$img_info          = pathinfo($_FILES['usaha_file3']['name']);
								$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
								$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
								$fileExt           = $img_info['extension'];
								$file_usaha_name3   = $fileName.'.'.$fileExt;
								// ----- END Process Image Name -----
								//$u_detail['Photo_business_location'] = $upload_usaha;
								//$u_detail['foto_usaha']              = $upload_usaha;
								$u_detail['images_usaha_name3']       = $file_usaha_name3;
							}
								if( isset($_FILES['usaha_file4']['name']) && $_FILES['usaha_file4']['name'] == ''){
								$file_usaha_name4   = '';
							}else{
								//$upload_usaha = file_get_contents($_FILES['usaha_file']['tmp_name']);
								// ----- Process Image Name -----
								$img_info          = pathinfo($_FILES['usaha_file4']['name']);
								$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
								$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
								$fileExt           = $img_info['extension'];
								$file_usaha_name4   = $fileName.'.'.$fileExt;
								// ----- END Process Image Name -----
								//$u_detail['Photo_business_location'] = $upload_usaha;
								//$u_detail['foto_usaha']              = $upload_usaha;
								$u_detail['images_usaha_name4']       = $file_usaha_name4;
							}
								if( isset($_FILES['usaha_file5']['name']) && $_FILES['usaha_file5']['name'] == ''){
								$file_usaha_name5   = '';
							}else{
								//$upload_usaha = file_get_contents($_FILES['usaha_file']['tmp_name']);
								// ----- Process Image Name -----
								$img_info          = pathinfo($_FILES['usaha_file5']['name']);
								$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
								$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
								$fileExt           = $img_info['extension'];
								$file_usaha_name5  = $fileName.'.'.$fileExt;
								// ----- END Process Image Name -----
								//$u_detail['Photo_business_location'] = $upload_usaha;
								//$u_detail['foto_usaha']              = $upload_usaha;
								$u_detail['images_usaha_name5']       = $file_usaha_name5;
							}
						}

						// -----Tambahan Baru-----
						if ($memberdata['mum_type_peminjam']=='1'){

							if($_FILES['surat_keterangan_bekerja_file']['name'] == ''){
								$file_surat_keterangan_bekerja_name   = '';
							}else{
								//$upload_surat_keterangan_bekerja_file  = file_get_contents($_FILES['surat_keterangan_bekerja_file']['tmp_name']);
								// ----- Process Image Name -----
								$img_info          = pathinfo($_FILES['surat_keterangan_bekerja_file']['name']);
								$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
								$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
								$fileExt           = $img_info['extension'];
								$file_surat_keterangan_bekerja_name  = $fileName.'.'.$fileExt;
								$u_detail['	foto_surat_keterangan_bekerja']  = $file_surat_keterangan_bekerja_name;
								// ----- END Process Image Name -----
							}

							if($_FILES['slip_gaji_file']['name'] == ''){
								$file_slip_gaji_name   = '';
							}else{
								//$upload_slip_gaji  = file_get_contents($_FILES['slip_gaji_file']['tmp_name']);
								// ----- Process Image Name -----
								$img_info          = pathinfo($_FILES['slip_gaji_file']['name']);
								$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
								$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
								$fileExt           = $img_info['extension'];
								$file_slip_gaji_name   = $fileName.'.'.$fileExt;
								$u_detail['foto_slip_gaji']  = $file_slip_gaji_name;
								// ----- END Process Image Name -----
							}

							if($_FILES['pegang_ktp_file']['name'] == ''){
								$file_pegang_ktp_name   = '';
							}else{
								//$pegang_ktp_file  = file_get_contents($_FILES['pegang_ktp_file']['tmp_name']);
								// ----- Process Image Name -----
								$img_info          = pathinfo($_FILES['pegang_ktp_file']['name']);
								$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
								$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
								$fileExt           = $img_info['extension'];
								$file_pegang_ktp_name  = $fileName.'.'.$fileExt;
								$u_detail['foto_pegang_ktp']  = $file_pegang_ktp_name;
								// ----- END Process Image Name -----
							}
						}

						// -----batas tambahan-----

						$userID = $memberdata['Id_pengguna'];
						
						$mem_data['mum_fullname']       = antiInjection(trim($post['fullname']));
						$mem_data['mum_telp']           = $notelp;
						$mem_data['mum_ktp']            = antiInjection(trim($post['nomor_ktp']));
						$mem_data['mum_nomor_rekening'] = antiInjection(trim($post['nomor_rekening']));

						$this->Member_model->update_member_byid($uid, $mem_data);

						// user
						$user['Nama_pengguna']      = antiInjection(trim($post['fullname']));
						$user['Id_ktp']             = antiInjection(trim($post['nomor_ktp']));
						$user['Tempat_lahir']       = antiInjection(trim($post['tempat_lahir']));
						$user['Tanggal_lahir']      = $tgl_lahir;
						$user['Jenis_kelamin']      = antiInjection($post['gender']);
						$user['Pekerjaan']          = antiInjection($post['pekerjaan']);	// 1=PNS,2=BUMN, 3=Swasta, 4=wiraswasta, 5=lain-lain 
						$user['Nomor_rekening']     = antiInjection(trim($post['nomor_rekening']));
						$user['nama_bank']          = antiInjection(trim($post['nama_bank']));

						if (isset($post['pendidikan'])) {
							$user['Pendidikan']     = antiInjection(trim($post['pendidikan']));
						}

						$this->Content_model->update_user($uid, $user);

						// user_detail
						$u_detail['Mobileno']          = $notelp;
						$u_detail['Occupation']        = antiInjection(trim($post['pekerjaan']));
						$u_detail['ID_type']           = 'KTP';
						$u_detail['ID_No']             = antiInjection(trim($post['nomor_ktp']));

						if (isset($post['usaha'])) {
							$u_detail['What_is_the_name_of_your_business']        = antiInjection(trim($post['usaha']));
						}

						if (isset($post['lama_usaha'])) {
							$u_detail['How_many_years_have_you_been_in_business'] = antiInjection(trim($post['lama_usaha']));
						}

						if (isset($post['pendidikan'])) {
							$u_detail['Highest_Education'] = antiInjection(trim($post['pendidikan']));
						}
						if (isset($post['jumlah_penghasilan'])) {
							$filterh         = explode('.', trim($post['jumlah_penghasilan']));
							$jml_penghasilan = antiInjection(str_replace(',', '', $filterh[0]));
							$u_detail['average_monthly_salary'] = $jml_penghasilan;
						}
						if (isset($post['jml_tanggungan'])) {
							$u_detail['How_many_people_do_you_financially_support'] = antiInjection(trim($post['jml_tanggungan']));
						}					
						if (isset($post['status_kawin'])) {
							$u_detail['status_nikah']  = antiInjection(trim($post['status_kawin']));
						}
						//start of user detail update
						if (isset($post['nama_perusahaan'])) {
							$u_detail['nama_perusahaan']  = antiInjection(trim($post['nama_perusahaan']));
						}
						if (isset($post['telepon_perusahaan'])) {
							$u_detail['telepon_tempat_bekerja']  = antiInjection(trim($post['telepon_perusahaan']));
						}
						if (isset($post['status_karyawan'])) {
							$u_detail['status_karyawan']  = antiInjection(trim($post['status_karyawan']));
						}
						if (isset($post['lama_bekerja'])) {
							$u_detail['lama_bekerja']  = antiInjection(trim($post['lama_bekerja']));
						}
						if (isset($post['nama_atasan_langsung'])) {
							$u_detail['nama_atasan_langsung']  = antiInjection(trim($post['nama_atasan_langsung']));
						}
						if (isset($post['telp_atasan_langsung'])) {
							$u_detail['telp_atasan_langsung']  = antiInjection(trim($post['telp_atasan_langsung']));
						}
						if (isset($post['referensi_teman_1'])) {
							$u_detail['referensi_teman_1']  = antiInjection(trim($post['referensi_teman_1']));
						}
						if (isset($post['telp_teman_1'])) {
							$u_detail['telp_referensi_teman_1']  = antiInjection(trim($post['telp_teman_1']));
						}
						if (isset($post['referensi_teman_2'])) {
							$u_detail['referensi_teman_2']  = antiInjection(trim($post['referensi_teman_2']));
						}
						if (isset($post['telp_teman_2'])) {
							$u_detail['telp_referensi_teman_2']  = antiInjection(trim($post['telp_teman_2']));
						}
						if (isset($post['gaji_bulanan'])) {
							$u_detail['gaji_bulanan']  = antiInjection(trim($post['gaji_bulanan']));
						}
						if (isset($post['usia'])) {
							$u_detail['usia']  = antiInjection(trim($post['usia']));
						}
						if (isset($post['npwp'])) {
							$u_detail['npwp']  = antiInjection(trim($post['npwp']));
						}
						if (isset($post['deskripsi_usaha'])) {
							$u_detail['deskripsi_usaha']  = antiInjection(trim($post['deskripsi_usaha']));
						}
						if (isset($post['omzet_usaha'])) {
							$u_detail['omzet_usaha']  = antiInjection(trim($post['omzet_usaha']));
						}
						if (isset($post['modal_usaha'])) {
							$u_detail['modal_usaha']  = antiInjection(trim($post['modal_usaha']));
						}
						if (isset($post['margin_usaha'])) {
							$u_detail['margin_usaha']  = antiInjection(trim($post['margin_usaha']));
						}						
						if (isset($post['biaya_operasional'])) {
							$u_detail['biaya_operasional']  = antiInjection(trim($post['biaya_operasional']));
						}
						if (isset($post['laba_usaha'])) {
							$u_detail['laba_usaha']  = antiInjection(trim($post['laba_usaha']));
						}
						//end of user detail update





						$this->Content_model->update_userdetail($userID, $u_detail);
						
						// profile_geografi
						$u_geo['Alamat']      = antiInjection(trim($post['alamat']));
						$u_geo['Kodepos']     = antiInjection(trim($post['kodepos']));
						$u_geo['Kota']        = antiInjection(trim($post['kota']));
						$u_geo['Provinsi']    = antiInjection(trim($post['provinsi']));

						$this->Content_model->update_profil_geografi($userID, $u_geo);

						// ---- Scoring -----
						if ($user['Jenis_kelamin']=='pria')
						{
							$score = $score + 30;
						}else{
							$score = $score + 50;							
						}

						// ranking
						$get_ranking = set_ranking_pengguna($userID, $logintype, $memberdata['mum_type_peminjam']);

						$update_pengguna['peringkat_pengguna']            = $get_ranking['grade'];
						$update_pengguna['peringkat_pengguna_persentase'] = $get_ranking['ranking'];
						$update_pengguna['skoring']                       = $score;
						$this->Content_model->update_user_byid($userID, $update_pengguna);

						$destination_foto = $this->config->item('member_images_dir'). $userID."/foto/";
						$destination_ktp  = $this->config->item('member_images_dir'). $userID."/ktp/";
						$destination_usaha = $this->config->item('member_images_dir'). $userID."/usaha/";
						$destination_usaha2 = $this->config->item('member_images_dir'). $userID."/usaha2/";
						$destination_usaha3 = $this->config->item('member_images_dir'). $userID."/usaha3/";
						$destination_usaha4 = $this->config->item('member_images_dir'). $userID."/usaha4/";
						$destination_usaha5 = $this->config->item('member_images_dir'). $userID."/usaha5/";
						$destination_surat_keterangan_bekerja = $this->config->item('member_images_dir'). $userID."/surat_keterangan_bekerja/";
						$destination_slip_gaji = $this->config->item('member_images_dir'). $userID."/slip_gaji/";
						$destination_pegang_ktp = $this->config->item('member_images_dir'). $userID."/pegang_ktp/";

						if($_FILES['foto_file']['name'] != ''){
							if (!is_file($destination_foto.$file_foto_name)) {
								mkdir_r($destination_foto);
							}

							unlink($destination_foto.$post['old_foto']);
							move_uploaded_file($_FILES['foto_file']['tmp_name'], $destination_foto.$file_foto_name);		
						}

						if($_FILES['ktp_file']['name'] != ''){
							if (!is_file($destination_ktp.$file_ktp_name)) {
								mkdir_r($destination_ktp);
							}
							unlink($destination_ktp.$post['old_ktp']);
							move_uploaded_file($_FILES['ktp_file']['tmp_name'], $destination_ktp.$file_ktp_name);		
						}

						if(isset($_FILES['usaha_file']['name']) && $_FILES['usaha_file']['name'] != ''){
							if (!is_file($destination_usaha.$file_usaha_name)) {
								mkdir_r($destination_usaha);
							}
							unlink($destination_usaha.$post['old_usaha']);
							move_uploaded_file($_FILES['usaha_file']['tmp_name'], $destination_usaha.$file_usaha_name);
						}
						if(isset($_FILES['usaha_file2']['name']) && $_FILES['usaha_file2']['name'] != ''){
							if (!is_file($destination_usaha2.$file_usaha_name2)) {
								mkdir_r($destination_usaha2);
							}
							unlink($destination_usaha2.$post['old_usaha2']);
							move_uploaded_file($_FILES['usaha_file2']['tmp_name'], $destination_usaha2.$file_usaha_name2);
						}
						if(isset($_FILES['usaha_file3']['name']) && $_FILES['usaha_file3']['name'] != ''){
							if (!is_file($destination_usaha3.$file_usaha_name3)) {
								mkdir_r($destination_usaha3);
							}
							unlink($destination_usaha3.$post['old_usaha3']);
							move_uploaded_file($_FILES['usaha_file3']['tmp_name'], $destination_usaha3.$file_usaha_name3);
						}
						if(isset($_FILES['usaha_file4']['name']) && $_FILES['usaha_file4']['name'] != ''){
							if (!is_file($destination_usaha4.$file_usaha_name4)) {
								mkdir_r($destination_usaha4);
							}
							unlink($destination_usaha4.$post['old_usaha4']);
							move_uploaded_file($_FILES['usaha_file4']['tmp_name'], $destination_usaha4.$file_usaha_name4);
						}
						if(isset($_FILES['usaha_file5']['name']) && $_FILES['usaha_file5']['name'] != ''){
							if (!is_file($destination_usaha5.$file_usaha_name5)) {
								mkdir_r($destination_usaha5);
							}
							unlink($destination_usaha5.$post['old_usaha5']);
							move_uploaded_file($_FILES['usaha_file5']['tmp_name'], $destination_usaha5.$file_usaha_name5);
						}





						// ----- tambahan baru -----

						if($_FILES['surat_keterangan_bekerja_file']['name'] != ''){
							if (!is_file($destination_surat_keterangan_bekerja.$file_surat_keterangan_bekerja_name)) {
								mkdir_r($destination_surat_keterangan_bekerja);
							}
							unlink($destination_surat_keterangan_bekerja.$post['old_surat_keterangan_bekerja']);
							move_uploaded_file($_FILES['surat_keterangan_bekerja_file']['tmp_name'], $destination_surat_keterangan_bekerja.$file_surat_keterangan_bekerja_name);
						}

						if($_FILES['slip_gaji_file']['name'] != '') {
							if (!is_file($destination_slip_gaji.$file_slip_gaji_name)) {
								mkdir_r($destination_slip_gaji);
							}
							unlink($destination_slip_gaji.$post['old_slip_gaji']);
							move_uploaded_file($_FILES['slip_gaji_file']['tmp_name'], $destination_slip_gaji.$file_slip_gaji_name);
						}

						if($_FILES['pegang_ktp_file']['name'] != ''){
							if (!is_file($destination_pegang_ktp.$file_pegang_ktp_name)) {
								mkdir_r($destination_pegang_ktp);
							}
							unlink($destination_pegang_ktp.$post['old_pegang_ktp']);
							move_uploaded_file($_FILES['pegang_ktp_file']['tmp_name'], $destination_pegang_ktp.$file_pegang_ktp_name);
						}
						
						// -----batas tambahan baru -----

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

				redirect('ubah-profil');
				exit();
			}else{
				$this->session->set_userdata('message','Isilah semua kolom dengan benar.');
				$this->session->set_userdata('message_type','error');
				redirect('ubah-profil');
				exit();
			}
		}

	}
	
}