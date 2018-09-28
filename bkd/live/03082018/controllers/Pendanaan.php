<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendanaan extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		//error_reporting(E_ALL);
		error_reporting(0);
	}
	
	public function index()
	{
		// ========= Register Pendanaan, jika belum login ========

		// clear browser cache
		header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		if (isset($_SESSION['_bkdlog_']) && isset($_SESSION['_bkduser_'])) {
			// Jika sudah login maka redirect ke form only
			redirect('formulir-pendanaan');
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
		$data['bottom_js'] .= add_js('js/pendanaan.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('daftar, pendanaan', 'daftar sebagai pendana');

		$data['pages']    = 'v_register_pendanaan';
		$this->load->view('template', $data);
	}

	function submit_register()
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$post = $this->input->post(NULL, TRUE);

			/*_d($post);
			_d($_FILES);*/
			$allowed_types = ['image/jpg', 'image/png', 'image/jpeg', 'image/gif'];
			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');
			$fullname    = antiInjection(trim($post['fullname']));
			$notelp      = antiInjection(trim($post['telp']));
			$email       = antiInjection(trim($post['email']));
			$ktp         = antiInjection(trim($post['nomor_ktp']));
			$password    = antiInjection(trim($post['password']));
			$repassword  = antiInjection(trim($post['confirm_password']));
			$tgl_lahir   = trim($post['tgl_lahir']);

			$filter2 = explode('.', trim($post['jumlah_penghasilan']));
			$jml_penghasilan = antiInjection(str_replace(',', '', $filter2[0]));

			$productID = 4;

			$check = $this->Content_model->check_existing_member($email, $notelp, $ktp);
			$count_member = count($check);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$ret = array('error'=> '1', 'message'=>'Invalid Email format!');

				//$this->session->set_userdata('message','Invalid Email format!');
				//$this->session->set_userdata('message_type','error');
			}else if ( $count_member > 1 ){
				$ret = array('error'=> '1', 'message'=>'Email/No.Telp/NIK Anda sudah terdaftar!');
			
			}else if (strlen($notelp) < 10) {
				$ret = array('error'=> '1', 'message'=>'Nomor Telepon Minimum 10 digit!');
				// $this->session->set_userdata('message','Nomor Telepon Minimum 10 digit!');
				// $this->session->set_userdata('message_type','error');

			}else if ( $password == '' OR strlen($password) < 6 ) {
				$ret = array('error'=> '1', 'message'=>'Password minimal 6 karakter.');
				// $this->session->set_userdata('message','Password minimal 6 karakter.');
				// $this->session->set_userdata('message_type','error');

			}else if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-zA-Z]).*$/", $password) === 0) {
				// min 6 karakter, terdiri dari minimum 1 huruf, minimum 1 angka
				$ret = array('error'=> '1', 'message'=>'Password harus terdiri dari huruf dan angka');
			
			}else if ( $password != $repassword ) {
				$ret = array('error'=> '1', 'message'=>'Password dan Konfirmasi Password tidak sama!');
				// $this->session->set_userdata('message','Password dan Konfirmasi Password tidak sama!');
				// $this->session->set_userdata('message_type','error');

			}else if (hitung_umur($tgl_lahir) < 17) {
				$ret = array('error'=> '1', 'message'=>'Maaf, Umur Anda kurang dari 17 Tahun');

			}else if ($_FILES['foto_file']['name'] == '') {
				$ret = array('error'=> '1', 'message'=>'Silahkan Upload Foto File!');
				// $this->session->set_userdata('message','Silahkan Upload Foto File!');
				// $this->session->set_userdata('message_type','error');
			
			}else if ($_FILES['ktp_file']['name'] == '') {
				$ret = array('error'=> '1', 'message'=>'Anda harus Upload NIK file.');
				// $this->session->set_userdata('message','Anda harus Upload NIK file.');
				// $this->session->set_userdata('message_type','error');

			}else if ( !in_array($_FILES['foto_file']['type'], $allowed_types) OR !in_array($_FILES['ktp_file']['type'], $allowed_types) ) {
			    
				$ret = array('error'=> '1', 'message'=>'Upload lah file foto, NIK format jpg atau png.');

			}else if($_FILES['foto_file']['size'] > 5000000 OR $_FILES['ktp_file']['size'] > 5000000 ) { // 500 KB (size is in bytes)
		        // File too big
		        $ret = array('error'=> '1', 'message'=>'File Foto dan NIK maksimum size 5 MB');
				// $this->session->set_userdata('message','File Foto dan NIK maksimum size 5 MB');
				// $this->session->set_userdata('message_type','error');

			}else if ($fullname != '' 
				&& $notelp != '' 
				&& trim($post['nomor_ktp']) != '' 
				&& trim($post['tempat_lahir']) != '' 
				&& trim($post['tgl_lahir']) != '' 
				&& trim($post['gender']) != '' 
				&& trim($post['alamat']) != '' 
				&& trim($post['kodepos']) != '' 
				&& trim($post['kota']) != '' 
				&& trim($post['provinsi']) != '' 
				&& trim($post['pekerjaan']) != ''
				&& trim($post['pendidikan']) != ''
				&& trim($post['jml_tanggungan']) != ''
				&& trim($post['status_kawin']) != ''
				&& trim($post['nomor_rekening']) != '' 
				&& trim($post['jumlah_penghasilan']) != '' 
				&& $password == $repassword 
				&& strlen($password) >= 6 
				&& strlen($notelp) > 5 
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

				$stored_p = password_hash(base64_encode(hash('sha256', ($password), true)), PASSWORD_DEFAULT);

				$mem_data['mum_fullname']    = $fullname;
				$mem_data['mum_email']       = $email;
				$mem_data['mum_telp']        = $notelp;
				$mem_data['mum_password']    = $stored_p;
				$mem_data['mum_status']      = 0;	// 1. active, 2. tidak bisa melakukan pendanaan
				$mem_data['mum_create_date'] = $nowdatetime;
				$mem_data['mum_type']        = '2'; // 1.peminjam, 2.pendana
				$mem_data['mum_ktp']         = antiInjection(trim($post['nomor_ktp']));
				$mem_data['mum_nomor_rekening'] = antiInjection(trim($post['nomor_rekening']));

				$uid = $this->Content_model->insert_mod_usermember($mem_data);

				if ($uid) {

					$prefixID    = 'PD-';
					$orderID     = $prefixID.strtoupper(substr(uniqid(sha1(time().$uid)),0,12));
			        $exist_order = $this->Content_model->check_ordercode_pendana($orderID);	// Cek if order ID exist on Database
					
					// jika order ID sudah ada di Database, generate lagi tambahkan datetime
					if (is_array($exist_order) && count($exist_order) > 0 )
					{
						$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
					}

					// user
					$user['Tgl_record ']        = $nowdate;
					$user['Nama_pengguna']      = $fullname;
					$user['Jenis_pengguna']     = 1; // 1.orang, 2.badan hukum
					$user['Id_ktp']             = antiInjection(trim($post['nomor_ktp']));
					$user['Tempat_lahir']       = antiInjection(trim($post['tempat_lahir']));
					$user['Tanggal_lahir']      = date('Y-m-d', strtotime(antiInjection(trim($post['tgl_lahir']))));
					$user['Jenis_kelamin']      = antiInjection($post['gender']);
					$user['Pendidikan']         = antiInjection($post['pendidikan']);
					$user['Pekerjaan']          = antiInjection($post['pekerjaan']);	// 1=PNS,2=BUMN, 3=Swasta, 4=wiraswasta, 5=lain-lain 
					$user['Nomor_rekening']     = antiInjection(trim($post['nomor_rekening']));
					$user['nama_bank']          = trim($post['nama_bank']);
					$user['id_mod_user_member'] = $uid;

					$userID = $this->Content_model->insert_user($user);

					// user_detail
					$u_detail['Id_pengguna']       = $userID;
					$u_detail['user_type ']        = 'pendana';
					$u_detail['Mobileno']          = $notelp;
					$u_detail['Profile_photo']     = $upload_foto;
					$u_detail['Photo_id']          = $upload_ktp;
					$u_detail['Occupation']        = antiInjection($post['pekerjaan']);
					$u_detail['Highest_Education'] = antiInjection($post['pendidikan']);
					$u_detail['average_monthly_salary'] = $jml_penghasilan;
					$u_detail['ID_type']           = 'KTP';
					$u_detail['ID_No']             = antiInjection(trim($post['nomor_ktp']));
					$u_detail['How_many_people_do_you_financially_support'] = antiInjection(trim($post['jml_tanggungan']));
					$u_detail['images_foto_name ']          = $file_foto_name;
					$u_detail['images_ktp_name ']           = $file_ktp_name;
					$u_detail['status_nikah']               = antiInjection(trim($post['status_kawin']));

					$this->Content_model->insert_userdetail($u_detail);
					
					// profile_geografi
					$u_geo['Agama']       = NULL;
					$u_geo['Alamat']      = antiInjection(trim($post['alamat']));
					$u_geo['Kodepos']     = antiInjection(trim($post['kodepos']));
					$u_geo['Kota']        = antiInjection(trim($post['kota']));
					$u_geo['Provinsi']    = antiInjection(trim($post['provinsi']));
					$u_geo['User_id']     = $userID;

					$this->Content_model->insert_profil_geografi($u_geo);

					// ------- Upload Image file --------
					$destination_foto = $this->config->item('pendana_images_dir'). $userID."/foto/";
					$destination_ktp  = $this->config->item('pendana_images_dir'). $userID."/ktp/";
					
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

					//$set_otp = $this->Member_model->set_cookies_otp($email); // set cookies for OTP login controller login/login_otp

					// ranking
					$get_ranking = set_ranking_pengguna($userID, 2, 0); // (Id_pengguna, peminjam/pendana, kilat/mikro)

					$update_pengguna['peringkat_pengguna']            = $get_ranking['grade'];
					$update_pengguna['peringkat_pengguna_persentase'] = $get_ranking['ranking'];
					$this->Content_model->update_user_byid($userID, $update_pengguna);
					// End ranking

					$this->send_email($email);

					$ret = array('error'=> '0', 'message'=>'Sukses daftar sebagai Pendana.');
					$this->session->set_userdata('message','Sukses daftar sebagai Pendana.');
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

	function send_email($email)
	{
		$ciphertext = urlencode($this->encryption->encrypt($email));
		$url = site_url('aktivasi?t='.$ciphertext);
		$html_content = '
        Hai '.$email.',<br><br>

            Anda telah terdaftar di BKDana.com.<br>
            Klik link berikut untuk aktivasi Akun Anda:
            <br><br>
            <a href="'.$url.'">'.$url.'</a>
            
            <br><br>

            Salam,<br>
            BKDana.com
			';

		include(APPPATH.'libraries/phpmailer-5.2.23/PHPMailerAutoload.php');
    	$mail = new phpmailer();
        $mail->IsSMTP();
		$mail->SMTPAuth    = true;
		//$mail->SMTPSecure  = 'ssl';
		$mail->Host        = 'smtp.gmail.com';
		$mail->Port        = 587;
		$mail->IsHTML(true);
		$mail->Username    = $this->config->item('mail_username');
		$mail->Password    = $this->config->item('mail_password');
		$mail->SetFrom('bkdanafinansial@gmail.com', 'BKDana');	
		$mail->AddAddress($email);
		$mail->Subject     = 'Aktivasi Akun';
		$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($html_content);	
		$mail->SMTPDebug   = 0;
        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }	

        return TRUE;
	}
}