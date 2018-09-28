<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pinjaman extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		//error_reporting(E_ALL);
		error_reporting(0);
	}
	
	public function index()
	{}

	public function daftar_kilat()
	{
		// ====== Daftar pinjaman kilat belum login ====== //

		// clear browser cache
		header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		if (isset($_SESSION['_bkdlog_']) && isset($_SESSION['_bkduser_'])) {
			// Jika sudah login maka redirect ke form only
			redirect('formulir-pinjaman-kilat');
			exit();
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
		$data['bottom_js'] .= add_js('js/fileinput-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/form-wizard.js');
		$data['bottom_js'] .= add_js('js/pinjaman-kilat.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('daftar, pinjaman kilat', 'daftar pinjaman kilat');

		$data['harga'] = $this->Content_model->get_harga_pinjaman_kilat();

		//_d($data['harga']);

		$data['products'] = $this->Content_model->get_pinjaman(1); // type_off_business_id

		$data['pages']    = 'v_register_pinjaman_kilat';
		$this->load->view('template', $data);
	}

	function submit_reg_kilat()
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$post = $this->input->post(NULL, TRUE);

			/*_d($post);
			_d($_FILES);*/

			$allowed_types = ['image/jpg', 'image/png', 'image/jpeg', 'image/gif'];
			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');
			$fullname    = trim($post['fullname']);
			$notelp      = trim($post['telp']);
			$email       = trim($post['email']);
			$ktp         = trim($post['nomor_ktp']);
			$password    = trim($post['password']);
			$repassword  = trim($post['confirm_password']);
			$tgl_lahir   = trim($post['tgl_lahir']);

			/*$filter = explode('.', trim($post['jumlah_pinjam']));
			$total_pinjam = str_replace(',', '', $filter[0]);*/

			$total_pinjam = trim($post['jumlah_pinjam']);

			$productID = trim($post['product']);
			$check = $this->Content_model->check_existing_member($email, $notelp, $ktp);
			$count_member = count($check);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$ret = array('error'=> '1', 'message'=>'Invalid Email format!');
				//$this->session->set_userdata('message','Invalid Email format!');
				//$this->session->set_userdata('message_type','error');

			}else if ( $count_member > 1){
				$ret = array('error'=> '1', 'message'=>'Email/No.Telp/NIK Anda sudah terdaftar!');
				//$this->session->set_userdata('message','Email Anda sudah terdaftar.');
				//$this->session->set_userdata('message_type','error');

			}else if ( $password == '' OR strlen($password) < 6 ) {
				$ret = array('error'=> '1', 'message'=>'Password minimal 6 karakter.');
				//$this->session->set_userdata('message','Anda harus Upload KTP file.');
				//$this->session->set_userdata('message_type','error');

			}else if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-zA-Z]).*$/", $password) === 0) {
				// min 6 karakter, terdiri dari minimum 1 huruf, minimum 1 angka
				$ret = array('error'=> '1', 'message'=>'Password harus terdiri dari huruf dan angka');
			
			}else if ( $password != $repassword ) {
				$ret = array('error'=> '1', 'message'=>'Password dan Konfirmasi Password tidak sama!');
				//$this->session->set_userdata('message','Password dan Konfirmasi Password tidak sama!');
				//$this->session->set_userdata('message_type','error');

			}else if (hitung_umur($tgl_lahir) < 17) {
				$ret = array('error'=> '1', 'message'=>'Maaf, Umur Anda kurang dari 17 Tahun');
			
			}else if ($_FILES['foto_file']['name'] == '' OR $_FILES['foto_file']['error'] != 0 OR $_FILES['foto_file']['size'] < 1) {
				$ret = array('error'=> '1', 'message'=>'Silahkan Upload Foto File!');
				//$this->session->set_userdata('message','Silahkan Upload Foto File!');
				//$this->session->set_userdata('message_type','error');
			
			}else if ($_FILES['ktp_file']['name'] == '' OR $_FILES['ktp_file']['error'] != 0 OR $_FILES['ktp_file']['size'] < 1) {
				$ret = array('error'=> '1', 'message'=>'Anda harus Upload NIK File.');
				//$this->session->set_userdata('message','Anda harus Upload KTP file.');
				//$this->session->set_userdata('message_type','error');

			}else if ( !in_array($_FILES['foto_file']['type'], $allowed_types) OR !in_array($_FILES['ktp_file']['type'], $allowed_types) ) {
			    
				$ret = array('error'=> '1', 'message'=>'Upload lah file foto format jpg atau png.');
				//$this->session->set_userdata('message','Upload lah file foto format jpg atau png.');
				//$this->session->set_userdata('message_type','error');

			}else if($_FILES['foto_file']['size'] > 5000000 OR $_FILES['ktp_file']['size'] > 5000000 ) { // 5 MB (size is in bytes)
		        // File too big
		        $ret = array('error'=> '1', 'message'=>'File Foto dan KTP maksimum size 5 MB');
				//$this->session->set_userdata('message','File Foto dan KTP maksimum size 500 KB');
				//$this->session->set_userdata('message_type','error');

			}else if ($fullname != '' 
				&& $notelp != '' 
				&& trim($post['nomor_ktp']) != '' 
				&& trim($post['tempat_lahir']) != '' 
				&& trim($post['tgl_lahir']) != '' 
				&& trim($post['alamat']) != '' 
				&& trim($post['kodepos']) != '' 
				&& trim($post['kota']) != '' 
				&& trim($post['provinsi']) != '' 
				&& trim($post['pekerjaan']) != ''
				&& trim($post['nomor_rekening']) != '' 
				&& $total_pinjam != '' && strlen($total_pinjam) > 5
				&& $password == $repassword && strlen($password) >=6 
				&& $_FILES['foto_file']['error'] == 0
				&& $_FILES['ktp_file']['error']  == 0
				&& $_FILES['foto_file']['tmp_name'] != ''
				&& $_FILES['ktp_file']['tmp_name']  != ''
				) {

				$upload_foto = file_get_contents($_FILES['foto_file']['tmp_name']);
				$upload_ktp  = file_get_contents($_FILES['ktp_file']['tmp_name']);

				if($_FILES['foto_file']['name'] == ''){
					$file_foto_name   = '';
				}else{
					// ----- Process Image Name -----
					$img_info          = pathinfo($_FILES['foto_file']['name']);
					$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
					$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
					$fileExt           = $img_info['extension'];
					$file_foto_name   = $fileName.'.'.$fileExt;
					// ----- END Process Image Name -----
				}

				if($_FILES['ktp_file']['name'] == ''){
					$file_ktp_name   = '';
				}else{
					// ----- Process Image Name -----
					$img_info          = pathinfo($_FILES['ktp_file']['name']);
					$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
					$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
					$fileExt           = $img_info['extension'];
					$file_ktp_name   = $fileName.'.'.$fileExt;
					// ----- END Process Image Name -----
				}

				// mod_user_member

				$stored_p = password_hash(base64_encode(hash('sha256', (trim($post['password'])), true)), PASSWORD_DEFAULT);

				$mem_data['mum_fullname']      = trim($post['fullname']);
				$mem_data['mum_email']         = trim($post['email']);
				$mem_data['mum_telp']          = trim($post['telp']);
				$mem_data['mum_password']      = $stored_p;
				$mem_data['mum_status']        = 0;
				$mem_data['mum_create_date']   = $nowdatetime;
				$mem_data['mum_type']          = '1'; // 1.peminjam, 2.pendana
				$mem_data['mum_type_peminjam'] = '1'; // 1.Kilat, 2.mikro
				$mem_data['mum_ktp']           = trim($post['nomor_ktp']);
				$mem_data['mum_nomor_rekening'] = trim($post['nomor_rekening']);

				$uid = $this->Content_model->insert_mod_usermember($mem_data);

				if ($uid) {

					$prefixID    = 'PK-';
					$orderID     = $prefixID.strtoupper(substr(uniqid(sha1(time().$uid)),0,12));
			        $exist_order = $this->Content_model->check_ordercode_pinjaman($orderID);	// Cek if order ID exist on Database
					
					// jika order ID sudah ada di Database, generate lagi tambahkan datetime
					if (is_array($exist_order) && count($exist_order) > 0 )
					{
						$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
					}

					// user
					$user['Tgl_record']         = $nowdate;
					$user['Nama_pengguna']      = $fullname;
					$user['Jenis_pengguna']     = 1; // 1.orang, 2.badan hukum
					$user['Id_ktp']             = trim($post['nomor_ktp']);
					$user['Tempat_lahir']       = trim($post['tempat_lahir']);
					$user['Tanggal_lahir']      = date('Y-m-d', strtotime(trim($post['tgl_lahir'])));
					$user['Jenis_kelamin']      = $post['gender'];
					$user['Pekerjaan']          = $post['pekerjaan'];	// 1=PNS,2=BUMN, 3=Swasta, 4=wiraswasta, 5=lain-lain 
					$user['Nomor_rekening']     = trim($post['nomor_rekening']);
					$user['nama_bank']          = trim($post['nama_bank']);					
					$user['id_mod_user_member'] = $uid;

					$userID = $this->Content_model->insert_user($user);

					// user_detail
					$u_detail['Id_pengguna']       = $userID;
					$u_detail['user_type']         = 'peminjam';
					$u_detail['Mobileno']          = $notelp;
					$u_detail['Profile_photo']     = $upload_foto;
					$u_detail['Photo_id']          = $upload_ktp;
					$u_detail['Occupation']        = $post['pekerjaan'];
					$u_detail['ID_type']           = 'KTP';
					$u_detail['ID_No']             = trim($post['nomor_ktp']);					
					$u_detail['Jumlah_permohonan_pinjaman '] = $total_pinjam;
					$u_detail['images_foto_name '] = $file_foto_name;
					$u_detail['images_ktp_name ']  = $file_ktp_name;

					$this->Content_model->insert_userdetail($u_detail);
					
					// profile_geografi
					$u_geo['Agama']       = NULL;
					$u_geo['Alamat']      = $post['alamat'];
					$u_geo['Kodepos']     = $post['kodepos'];
					$u_geo['Kota']        = $post['kota'];
					$u_geo['Provinsi']    = $post['provinsi'];
					$u_geo['User_id']     = $userID;

					$this->Content_model->insert_profil_geografi($u_geo);		

					// profil_permohonan_pinjaman
					$p_pinjam['Master_loan_id']          = $orderID;
					$p_pinjam['Tgl_permohonan_pinjaman'] = $nowdatetime;
					$p_pinjam['Jml_permohonan_pinjaman'] = $total_pinjam;
					$p_pinjam['User_id']                 = $userID;
					$p_pinjam['Product_id']              = $productID;
					$p_pinjam['Master_loan_status']      = 'review';
					$p_pinjam['pinjam_member_id']        = $uid;

					$pinjamID = $this->Content_model->insert_profil_pinjaman($p_pinjam);

					// ------- Upload Image file --------
					$destination_foto = $this->config->item('kilat_images_dir'). $userID."/foto/";
					$destination_ktp  = $this->config->item('kilat_images_dir'). $userID."/ktp/";
					
					if($_FILES['foto_file']['name'] != ''){
						if (!is_file($destination_foto.$file_foto_name)) {
							mkdir_r($destination_foto);
						}
						move_uploaded_file($_FILES['foto_file']['tmp_name'], $destination_foto.$file_foto_name);		
					}

					if($_FILES['ktp_file']['name'] != ''){
						if (!is_file($destination_ktp.$file_ktp_name)) {
							mkdir_r($destination_ktp);
						}
						move_uploaded_file($_FILES['ktp_file']['tmp_name'], $destination_ktp.$file_ktp_name);		
					}

					$set_otp = $this->Member_model->set_cookies_otp($email); // set cookies for OTP login controller login/login_otp

					// ranking
					$get_ranking = set_ranking_pengguna($userID, 1, 1); // (Id_pengguna, peminjam/pendana, kilat/mikro)

					$update_pengguna['peringkat_pengguna']            = $get_ranking['grade'];
					$update_pengguna['peringkat_pengguna_persentase'] = $get_ranking['ranking'];
					$this->Content_model->update_user_byid($userID, $update_pengguna);
					// End ranking

					$ret = array('error'=> '0', 'message'=>'Sukses daftar pinjaman kilat.');
					$this->session->set_userdata('message','Sukses daftar pinjaman kilat');
					$this->session->set_userdata('message_type','success');
				}
			}else{
				$ret = array('error'=> '1', 'message'=>'Isilah semua kolom!');
				//$this->session->set_userdata('message','Isilah semua kolom!');
				//$this->session->set_userdata('message_type','error');
			}

			echo json_encode($ret);
		}
	}

	public function daftar_mikro()
	{
		// ====== Daftar pinjaman Mikro belum login ====== //

		// clear browser cache
		header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		if (isset($_SESSION['_bkdlog_']) && isset($_SESSION['_bkduser_'])) {
			// Jika sudah login maka redirect ke form only
			redirect('formulir-pinjaman-mikro');
			exit();
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
		$data['bottom_js'] .= add_js('js/fileinput-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/date-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/form-wizard.js');
		$data['bottom_js'] .= add_js('js/pinjaman-mikro.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('daftar, pinjaman mikro', 'daftar pinjaman mikro');

		$data['products'] = $this->Content_model->get_pinjaman(3); // type_off_business_id

		$data['pages']    = 'v_register_pinjaman_mikro';
		$this->load->view('template', $data);
	}

	function submit_reg_mikro()
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$post = $this->input->post(NULL, TRUE);

			/*_d($post);
			_d($_FILES);*/
			$allowed_types = ['image/jpg', 'image/png', 'image/jpeg', 'image/gif'];
			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');
			$fullname    = trim($post['fullname']);
			$notelp      = trim($post['telp']);
			$email       = trim($post['email']);
			$ktp         = trim($post['nomor_ktp']);
			$password    = trim($post['password']);
			$repassword  = trim($post['confirm_password']);
			$tgl_lahir   = trim($post['tgl_lahir']);

			$filter = explode('.', trim($post['jumlah_pinjam']));
			$total_pinjam = str_replace(',', '', $filter[0]);

			$productID = trim($post['product']);

			$check = $this->Content_model->check_existing_member($email, $notelp, $ktp);
			$count_member = count($check);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$ret = array('error'=> '1', 'message'=>'Invalid Email format!');
			
			}else if ( $count_member > 1 ){
				$ret = array('error'=> '1', 'message'=>'Email/No.telp/NIK Anda sudah terdaftar!');
			
			}else if ( $password == '' OR strlen($password) < 6 ) {
				$ret = array('error'=> '1', 'message'=>'Password minimal 6 karakter.');

			}else if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-zA-Z]).*$/", $password) === 0) {
				// min 6 karakter, terdiri dari minimum 1 huruf, minimum 1 angka
				$ret = array('error'=> '1', 'message'=>'Password harus terdiri dari huruf dan angka');

			}else if ( $password != $repassword ) {
				$ret = array('error'=> '1', 'message'=>'Password dan Konfirmasi Password tidak sama!');

			}else if (hitung_umur($tgl_lahir) < 17) {
				$ret = array('error'=> '1', 'message'=>'Maaf, Umur Anda kurang dari 17 Tahun');

			}else if ($total_pinjam < 100000) {
				$ret = array('error'=> '1', 'message'=>'Jumlah Pinjaman minimal Rp 100,000');
			
			}else if ($_FILES['foto_file']['name'] == '' OR $_FILES['foto_file']['error'] != 0 OR $_FILES['foto_file']['size'] < 1 ) {
				$ret = array('error'=> '1', 'message'=>'Silahkan Upload Foto File!');
				// $this->session->set_userdata('message','Silahkan Upload Foto File!');
				// $this->session->set_userdata('message_type','error');
			
			}else if ($_FILES['ktp_file']['name'] == '' OR $_FILES['ktp_file']['error'] != 0 OR $_FILES['ktp_file']['size'] < 1 ) {
				$ret = array('error'=> '1', 'message'=>'Anda harus Upload NIK file.');

			}else if ($_FILES['usaha_file']['name'] == '' OR $_FILES['usaha_file']['error'] != 0 OR $_FILES['usaha_file']['size'] < 1 ) {
				$ret = array('error'=> '1', 'message'=>'Anda harus Upload foto usaha.');

			}else if ( !in_array($_FILES['foto_file']['type'], $allowed_types) OR !in_array($_FILES['ktp_file']['type'], $allowed_types) OR !in_array($_FILES['usaha_file']['type'], $allowed_types) ) {
			    
				$ret = array('error'=> '1', 'message'=>'Upload lah file foto, NIK, Usaha format jpg atau png.');

			}else if($_FILES['foto_file']['size'] > 5000000 OR $_FILES['ktp_file']['size'] > 5000000 OR $_FILES['usaha_file']['size'] > 5000000 ) { // 2 MB (size is in bytes)
		        // File too big
		        $ret = array('error'=> '1', 'message'=>'File Foto dan NIK maksimum size 5 MB');

			}else if ($fullname != '' 
				&& $notelp != '' 
				&& trim($post['nomor_ktp']) != '' 
				&& trim($post['tempat_lahir']) != '' 
				&& trim($post['tgl_lahir']) != '' 
				&& trim($post['alamat']) != '' 
				&& trim($post['kodepos']) != '' 
				&& trim($post['kota']) != '' 
				&& trim($post['provinsi']) != '' 
				&& trim($post['pekerjaan']) != ''
				&& trim($post['nomor_rekening']) != '' 
				&& trim($post['jumlah_pinjam']) != '' 
				&& trim($post['lama_usaha']) != ''
				&& $password == $repassword && strlen($password) >=6 
				&& $_FILES['foto_file']['error'] == 0
				&& $_FILES['ktp_file']['error']  == 0
				&& $_FILES['usaha_file']['error']  == 0
				&& $_FILES['foto_file']['tmp_name'] != ''
				&& $_FILES['ktp_file']['tmp_name']  != '' 
				&& $_FILES['usaha_file']['tmp_name']  != '' 
			) {

				$upload_foto = file_get_contents($_FILES['foto_file']['tmp_name']);
				$upload_ktp  = file_get_contents($_FILES['ktp_file']['tmp_name']);
				$upload_usaha = file_get_contents($_FILES['usaha_file']['tmp_name']);

				if($_FILES['foto_file']['name'] == ''){
					$file_foto_name   = '';
				}else{
					// ----- Process Image Name -----
					$img_info          = pathinfo($_FILES['foto_file']['name']);
					$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
					$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
					$fileExt           = $img_info['extension'];
					$file_foto_name   = $fileName.'.'.$fileExt;
					// ----- END Process Image Name -----
				}

				if($_FILES['ktp_file']['name'] == ''){
					$file_ktp_name   = '';
				}else{
					// ----- Process Image Name -----
					$img_info          = pathinfo($_FILES['ktp_file']['name']);
					$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
					$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
					$fileExt           = $img_info['extension'];
					$file_ktp_name   = $fileName.'.'.$fileExt;
					// ----- END Process Image Name -----
				}

				if($_FILES['usaha_file']['name'] == ''){
					$file_usaha_name   = '';
				}else{
					// ----- Process Image Name -----
					$img_info          = pathinfo($_FILES['usaha_file']['name']);
					$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
					$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
					$fileExt           = $img_info['extension'];
					$file_usaha_name   = $fileName.'.'.$fileExt;
					// ----- END Process Image Name -----
				}

				// mod_user_member

				$stored_p = password_hash(base64_encode(hash('sha256', (trim($post['password'])), true)), PASSWORD_DEFAULT);

				$mem_data['mum_fullname']       = trim($post['fullname']);
				$mem_data['mum_email']          = trim($post['email']);
				$mem_data['mum_telp']           = trim($post['telp']);
				$mem_data['mum_password']       = $stored_p;
				$mem_data['mum_status']         = 0;
				$mem_data['mum_create_date']    = $nowdatetime;
				$mem_data['mum_type']           = '1'; // 1.peminjam, 2.pendana
				$mem_data['mum_type_peminjam']  = '2'; // 1.Kilat, 2.mikro
				$mem_data['mum_ktp']            = trim($post['nomor_ktp']);
				$mem_data['mum_nomor_rekening'] = trim($post['nomor_rekening']);
				$mem_data['mum_usaha']          = trim($post['usaha']);
				$mem_data['mum_lama_usaha']     = trim($post['lama_usaha']);

				$uid = $this->Content_model->insert_mod_usermember($mem_data);

				if ($uid) {

					$prefixID    = 'PM-';
					$orderID     = $prefixID.strtoupper(substr(uniqid(sha1(time().$uid)),0,12));
			        $exist_order = $this->Content_model->check_ordercode_pinjaman($orderID);	// Cek if order ID exist on Database
					
					// jika order ID sudah ada di Database, generate lagi tambahkan datetime
					if (is_array($exist_order) && count($exist_order) > 0 )
					{
						$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
					}

					// user
					$user['Tgl_record ']        = $nowdate;
					$user['Nama_pengguna']      = $fullname;
					$user['Jenis_pengguna']     = 1; // 1.orang, 2.badan hukum
					$user['Id_ktp']             = trim($post['nomor_ktp']);
					$user['Tempat_lahir']       = trim($post['tempat_lahir']);
					$user['Tanggal_lahir']      = date('Y-m-d', strtotime(trim($post['tgl_lahir'])));
					$user['Jenis_kelamin']      = $post['gender'];
					$user['Pekerjaan']          = $post['pekerjaan'];	// 1=PNS,2=BUMN, 3=Swasta, 4=wiraswasta, 5=lain-lain 
					$user['Nomor_rekening']     = $post['nomor_rekening'];
					$user['nama_bank']          = trim($post['nama_bank']);
					$user['id_mod_user_member'] = $uid;

					$userID = $this->Content_model->insert_user($user);

					// user_detail
					$u_detail['Id_pengguna']       = $userID;
					$u_detail['user_type']         = 'peminjam';
					$u_detail['Mobileno']          = $notelp;
					$u_detail['Profile_photo']     = $upload_foto;
					$u_detail['Photo_id']          = $upload_ktp;
					$u_detail['Occupation']        = $post['pekerjaan'];
					$u_detail['ID_type']           = 'KTP';
					$u_detail['ID_No']             = trim($post['nomor_ktp']);
					$u_detail['Jumlah_permohonan_pinjaman']               = $total_pinjam;
					$u_detail['What_is_the_name_of_your_business']        = trim($post['usaha']);
					$u_detail['How_many_years_have_you_been_in_business'] = trim($post['lama_usaha']);
					$u_detail['Photo_business_location']                  = $upload_usaha;
					$u_detail['foto_usaha']        = $upload_usaha;
					$u_detail['images_foto_name']  = $file_foto_name;
					$u_detail['images_ktp_name']   = $file_ktp_name;
					$u_detail['images_usaha_name'] = $file_usaha_name;

					$this->Content_model->insert_userdetail($u_detail);
					
					// profile_geografi
					$u_geo['Agama']       = NULL;
					$u_geo['Alamat']      = $post['alamat'];
					$u_geo['Kodepos']     = $post['kodepos'];
					$u_geo['Kota']        = $post['kota'];
					$u_geo['Provinsi']    = $post['provinsi'];
					$u_geo['User_id']     = $userID;

					$this->Content_model->insert_profil_geografi($u_geo);		

					// profil_permohonan_pinjaman
					$p_pinjam['Master_loan_id']          = $orderID;
					$p_pinjam['Tgl_permohonan_pinjaman'] = $nowdatetime;
					$p_pinjam['Jml_permohonan_pinjaman'] = $total_pinjam;
					$p_pinjam['User_id']                 = $userID;
					$p_pinjam['Product_id']              = $productID;
					$p_pinjam['Master_loan_status']      = 'review';
					$p_pinjam['pinjam_member_id']        = $uid;

					$pinjamID =$this->Content_model->insert_profil_pinjaman($p_pinjam);

					// ------- Upload Image file --------
					$destination_foto = $this->config->item('mikro_images_dir'). $userID."/foto/";
					$destination_ktp  = $this->config->item('mikro_images_dir'). $userID."/ktp/";
					$destination_usaha = $this->config->item('mikro_images_dir'). $userID."/usaha/";

					if($_FILES['foto_file']['name'] != ''){
						if (!is_file($destination_foto.$file_foto_name)) {
							mkdir_r($destination_foto);
						}
						move_uploaded_file($_FILES['foto_file']['tmp_name'], $destination_foto.$file_foto_name);		
					}

					if($_FILES['ktp_file']['name'] != ''){
						if (!is_file($destination_ktp.$file_ktp_name)) {
							mkdir_r($destination_ktp);
						}
						move_uploaded_file($_FILES['ktp_file']['tmp_name'], $destination_ktp.$file_ktp_name);	
					}

					if($_FILES['usaha_file']['name'] != ''){
						if (!is_file($destination_usaha.$file_usaha_name)) {
							mkdir_r($destination_usaha);
						}
						move_uploaded_file($_FILES['usaha_file']['tmp_name'], $destination_usaha.$file_usaha_name);
					}

					$set_otp = $this->Member_model->set_cookies_otp($email); // set cookies for OTP login controller login/login_otp

					// ranking
					$get_ranking = set_ranking_pengguna($userID, 1, 2); // (Id_pengguna, peminjam/pendana, kilat/mikro)

					$update_pengguna['peringkat_pengguna']            = $get_ranking['grade'];
					$update_pengguna['peringkat_pengguna_persentase'] = $get_ranking['ranking'];
					$this->Content_model->update_user_byid($userID, $update_pengguna);
					// End ranking

					$ret = array('error'=> '0', 'message'=>'Sukses daftar pinjaman kilat.');
					$this->session->set_userdata('message','Sukses daftar pinjaman kilat');
					$this->session->set_userdata('message_type','success');
				}
			}else{
				$ret = array('error'=> '1', 'message'=>'Isilah semua kolom!');
				$this->session->set_userdata('message','Isilah semua kolom!');
				$this->session->set_userdata('message_type','error');
			}

			echo json_encode($ret);
		}
	}

//============= END DAFTAR dengan Pinjam ============

	public function kilat()
	{
		// ======= User has login: Formulir Pinjaman Kilat ======

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
		$data['bottom_js'] .= add_js('js/alertify/alertify.min.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/date-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/form-wizard.js');
		$data['bottom_js'] .= add_js('js/pinjaman-kilat2.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('daftar, pinjaman kilat', 'daftar pinjaman kilat');

		$data['harga'] = $this->Content_model->get_harga_pinjaman_kilat();
		$data['products'] = $this->Content_model->get_pinjaman(1); // type_off_business_id

		$memberID = (int)$_SESSION['_bkduser_'];
		$data['memberdata'] = $this->Member_model->get_member_byid($memberID);

		$data['pages']    = 'v_form_pinjaman_kilat';
		$this->load->view('template', $data);
	}

	function submit_p_kilat()
	{
		$uid = htmlentities($_SESSION['_bkduser_']);
		// cek if has login
		if (!empty($uid) & trim($uid) !='')
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$post = $this->input->post(NULL, TRUE);

				$nowdate     = date('Y-m-d');
				$nowdatetime = date('Y-m-d H:i:s');
				$password    = trim($post['password']);

				/*$filter = explode('.', trim($post['jumlah_pinjam']));
				$total_pinjam = str_replace(',', '', $filter[0]);*/
				$total_pinjam = trim($post['jumlah_pinjam']);
				$productID    = trim($post['product']);

				$member_data     = $this->Member_model->get_member_byid($uid);
				$stored_password = $member_data['mum_password'];

				$user_data = $this->Content_model->get_user($uid); // get data from table user

				if ( $password == '' OR strlen($password) < 6 ) {
					$ret = array('error'=> '1', 'message'=>'Password minimal 6 karakter.');
					//$this->session->set_userdata('message','Password minimal 6 karakter.');
					//$this->session->set_userdata('message_type','error');

				}else if (!password_verify(base64_encode(hash('sha256', $password, true)), $stored_password)) {
					$ret = array('error'=> '1', 'message'=>'Password yang Anda masukkan Salah!');
					//$this->session->set_userdata('message','Password yang Anda masukkan Salah!');
					//$this->session->set_userdata('message_type','error');

				}else if ( $total_pinjam != ''
							&& $productID != ''
							&& strlen($password) >= 6 
							&& (password_verify(base64_encode(hash('sha256', $password, true)), $stored_password)) ) 
				{

						$prefixID    = 'PK-';
						$orderID     = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,12));
				        $exist_order = $this->Content_model->check_ordercode_pinjaman($orderID);	// Cek if order ID exist on Database
						
						// jika order ID sudah ada di Database, generate lagi tambahkan datetime
						if (is_array($exist_order) && count($exist_order) > 0 )
						{
							$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
						}

						// profil_permohonan_pinjaman
						$p_pinjam['Master_loan_id']          = $orderID;
						$p_pinjam['Tgl_permohonan_pinjaman'] = $nowdatetime;
						$p_pinjam['Jml_permohonan_pinjaman'] = $total_pinjam;
						$p_pinjam['User_id']                 = $user_data['Id_pengguna'];
						$p_pinjam['Product_id']              = $productID;
						$p_pinjam['Master_loan_status']      = 'review';
						$p_pinjam['pinjam_member_id']        = $uid;

						$pinjamID = $this->Content_model->insert_profil_pinjaman($p_pinjam);

						$ret = array('error'=> '0', 'message'=>'Sukses ajukan pinjaman kilat.');
						$this->session->set_userdata('message','Sukses ajukan pinjaman kilat');
						$this->session->set_userdata('message_type','success');
					
				}else{
					$ret = array('error'=> '1', 'message'=>'Isilah semua kolom!');
					//$this->session->set_userdata('message','Isilah semua kolom!');
					//$this->session->set_userdata('message_type','error');
				}

				echo json_encode($ret);
			}
		}else{
			$ret = array('error'=> '0', 'message'=>'Gagal ajukan pinjaman kilat.');
			$this->session->set_userdata('message','Gagal ajukan pinjaman kilat');
			$this->session->set_userdata('message_type','error');
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
		$data['top_css'] .= add_css('js/alertify/css/alertify.min.css');
		$data['top_css'] .= add_css('js/alertify/css/themes/default.min.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/bootstrap-datepicker/js/bootstrap-datepicker.min.js');
		$data['bottom_js'] .= add_js('js/jqueryvalidation/dist/jquery.validate.min.js');
		$data['bottom_js'] .= add_js('js/alertify/alertify.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/form-wizard.js');
		$data['bottom_js'] .= add_js('js/date-init.js');
		$data['bottom_js'] .= add_js('js/pinjaman-mikro2.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('daftar, pinjaman mikro', 'daftar pinjaman mikro');

		//$data['meta_tag'] = $this->m_settings->meta_default();
		$data['products'] = $this->Content_model->get_pinjaman(3); // type_off_business_id

		$memberID = (int)$_SESSION['_bkduser_'];
		$data['memberdata'] = $this->Member_model->get_member_byid($memberID);

		$data['pages']    = 'v_form_pinjaman_mikro';
		$this->load->view('template', $data);
	}

	function submit_p_mikro()
	{
		$uid = htmlentities($_SESSION['_bkduser_']);

		// cek if has login
		if ($uid !='0' & trim($uid) !='')
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$post = $this->input->post(NULL, TRUE);

				$nowdate     = date('Y-m-d');
				$nowdatetime = date('Y-m-d H:i:s');
				$password    = trim($post['password']);

				$filter = explode('.', trim($post['jumlah_pinjam']));
				$total_pinjam = str_replace(',', '', $filter[0]);

				$productID = trim($post['product']);

				$member_data     = $this->Member_model->get_member_byid($uid);
				$stored_password = $member_data['mum_password'];

				$user_data = $this->Content_model->get_user($uid); // get data from table user

				if ( $password == '' OR strlen($password) < 6 ) {
					$ret = array('error'=> '1', 'message'=>'Password minimal 6 karakter.');
					$this->session->set_userdata('message','Password minimal 6 karakter.');
					$this->session->set_userdata('message_type','error');

				}else if (!password_verify(base64_encode(hash('sha256', $password, true)), $stored_password)) {
					$ret = array('error'=> '1', 'message'=>'Password yang Anda masukkan Salah!');
					$this->session->set_userdata('message','Password yang Anda masukkan Salah!');
					$this->session->set_userdata('message_type','error');

				}else if ( trim($post['jumlah_pinjam']) !=''
							&& $productID != ''
							&& $password != ''
							&& strlen($password) >= 6 
							&& password_verify(base64_encode(hash('sha256', $password, true)), $stored_password) ) 
				{
						$prefixID    = 'PM-';
						$orderID     = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,12));
				        $exist_order = $this->Content_model->check_ordercode_pinjaman($orderID);	// Cek if order ID exist on Database
						
						// jika order ID sudah ada di Database, generate lagi tambahkan datetime
						if (is_array($exist_order) && count($exist_order) > 0 )
						{
							$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
						}

						// profil_permohonan_pinjaman
						$p_pinjam['Master_loan_id']          = $orderID;
						$p_pinjam['Tgl_permohonan_pinjaman'] = $nowdatetime;
						$p_pinjam['Jml_permohonan_pinjaman'] = $total_pinjam;
						$p_pinjam['User_id']                 = $user_data['Id_pengguna'];
						$p_pinjam['Product_id']              = $productID;
						$p_pinjam['Master_loan_status']      = 'review';
						$p_pinjam['pinjam_member_id']        = $uid;

						$pinjamID =$this->Content_model->insert_profil_pinjaman($p_pinjam);

						$ret = array('error'=> '0', 'message'=>'Sukses ajukan Pinjaman Mikro.');
						$this->session->set_userdata('message','Sukses ajukan Pinjaman Mikro');
						$this->session->set_userdata('message_type','success');
					
				}else{
					$ret = array('error'=> '1', 'message'=>'Isilah semua kolom!');
					$this->session->set_userdata('message','Isilah semua kolom!');
					$this->session->set_userdata('message_type','error');
				}

				echo json_encode($ret);
			}
		}
	}
}