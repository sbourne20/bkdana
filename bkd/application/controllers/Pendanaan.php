<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendanaan extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		$this->load->library('encryption');

		//error_reporting(E_ALL);
		error_reporting(0);

		ini_set('max_execution_time', 600);
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
			redirect('dashboard');
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
			
			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');
			$fullname    = antiInjection(trim($post['fullname']));
			$notelp      = antiInjection(trim($post['telp']));
			$email       = antiInjection(trim($post['email']));
			$password    = antiInjection(trim($post['password']));
			$repassword  = antiInjection(trim($post['confirm_password']));
			$sumberdana  = antiInjection(trim($post['sumberdana']));

			$productID = 4;

			$check = $this->Content_model->check_existing_member($email, $notelp, '');
			$count_member = count($check);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$ret = array('error'=> '1', 'message'=>'Invalid Email format!');

			}else if ( $count_member > 1 && isset($check['id_mod_user_member'])){
				$ret = array('error'=> '1', 'message'=>'Email/No.Telp Anda sudah terdaftar!');
			
			}else if (strlen($notelp) < 10) {
				$ret = array('error'=> '1', 'message'=>'Nomor Telepon Minimum 10 digit!');

			}else if ( $password == '' OR strlen($password) < 6 ) {
				$ret = array('error'=> '1', 'message'=>'Password minimal 6 karakter.');

			}else if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-zA-Z]).*$/", $password) === 0) {
				// min 6 karakter, terdiri dari minimum 1 huruf, minimum 1 angka
				$ret = array('error'=> '1', 'message'=>'Password harus terdiri dari huruf dan angka, serta minimal 1 huruf besar');
			
			}else if ( $password != $repassword ) {
				$ret = array('error'=> '1', 'message'=>'Password dan Konfirmasi Password tidak sama!');

			}else if (
				$fullname != '' 
				&& $notelp != '' 
				&& $sumberdana != '' 
				&& $password == $repassword 
				&& strlen($password) >= 6 
				&& strlen($notelp) > 5 
			) {
				// mod_user_member

				$stored_p = password_hash(base64_encode(hash('sha256', ($password), true)), PASSWORD_DEFAULT);

				$mem_data['mum_fullname']    = $fullname;
				$mem_data['mum_email']       = $email;
				$mem_data['mum_telp']        = $notelp;
				$mem_data['mum_password']    = $stored_p;
				$mem_data['mum_status']      = 0;	// 1. active, 2. tidak bisa melakukan pendanaan
				$mem_data['mum_create_date'] = $nowdatetime;
				$mem_data['mum_type']        = '2'; // 1.peminjam, 2.pendana
				// $mem_data['mum_nomor_rekening'] = antiInjection(trim($post['nomor_rekening']));

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
					$user['Tgl_record']        = $nowdate;
					$user['Nama_pengguna']      = $fullname;
					$user['Jenis_pengguna']     = 1; // 1.orang, 2.badan hukum
					// $user['Nomor_rekening']     = antiInjection(trim($post['nomor_rekening']));
					// $user['nama_bank']          = trim($post['nama_bank']);
					$user['id_mod_user_member'] = $uid;

					$userID = $this->Content_model->insert_user($user);

					// user_detail
					$u_detail['Id_pengguna']       = $userID;
					$u_detail['user_type ']        = 'pendana';
					$u_detail['Mobileno']          = $notelp;
					$u_detail['Source_income']     = $sumberdana;

					$this->Content_model->insert_userdetail($u_detail);
					
					// profile_geografi
					$u_geo['Agama']       = NULL;
					$u_geo['Alamat']      = NULL;
					$u_geo['Kodepos']     = NULL;
					$u_geo['Kota']        = NULL;
					$u_geo['Provinsi']    = NULL;
					$u_geo['User_id']     = $userID;

					$this->Content_model->insert_profil_geografi($u_geo);

					// ranking
					$get_ranking = set_ranking_pengguna($userID, 2, 0); // (Id_pengguna, peminjam/pendana, kilat/mikro)

					$update_pengguna['peringkat_pengguna']            = $get_ranking['grade'];
					$update_pengguna['peringkat_pengguna_persentase'] = $get_ranking['ranking'];
					$this->Content_model->update_user_byid($userID, $update_pengguna);
					// End ranking

					$sent = $this->send_email($email);

					if ($sent == 'success') {

						$ret = array('error'=> '0', 'message'=>'Sukses daftar sebagai Pendana.');
						$this->session->set_userdata('message','Sukses daftar sebagai Pendana.');
						$this->session->set_userdata('message_type','success');
					}else{
						$ret = array('error'=> '2', 'message'=> $sent);
					}
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

            Anda telah terdaftar di Website BKDana.<br>
            Klik link berikut untuk aktivasi Akun Anda:
            <br><br>
            <a href="'.$url.'">'.$url.'</a>
            
            <br><br>

            <span style="color:#858C93;">
            	Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke Email ini.
            	<br><br>

            	&copy; BKDana, '.date("Y").'. All rights reserved.
            </span>
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
		$mail->Subject     = 'Aktivasi Akun BKDana.id';
		$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($html_content);	
		$mail->SMTPDebug   = 0;
        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = $mail->ErrorInfo;	

        }else{
            $result = 'success';		                		               	
        }	

        return $result;
	}
}
