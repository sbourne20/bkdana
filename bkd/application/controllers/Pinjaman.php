<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pinjaman extends CI_Controller {

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

		$logintype    = isset($_SESSION['_bkdtype_'])? htmlentities($_SESSION['_bkdtype_']) : 0; // 1.peminjam, 2.pendana

		if (isset($_SESSION['_bkdlog_']) && isset($_SESSION['_bkduser_'])) {
			// Jika sudah login maka redirect ke form only
			if ($logintype=='1') {
				redirect('formulir-pinjaman-kilat');
				exit();
			}else{
				redirect('message/restrict_pendana');
				exit();
			}
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
			$password    = trim($post['password']);
			$repassword  = trim($post['confirm_password']);
			$ktp         = trim($post['nomor_ktp']);
			//$tgl_lahir   = trim($post['tgl_lahir']);

			/*$filter = explode('.', trim($post['jumlah_pinjam']));
			$total_pinjam = str_replace(',', '', $filter[0]);*/

			//$total_pinjam = trim($post['jumlah_pinjam']);

			//$productID = trim($post['product']);
			$check = $this->Content_model->check_existing_member($email, $notelp, $ktp);
			$count_member = count($check);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL) && $email!='') {
				$ret = array('error'=> '1', 'message'=>'Invalid Email format!');

			}
			else if ( $count_member > 1){
				$ret = array('error'=> '1', 'message'=>'Email/No.Telp/NIK Anda sudah terdaftar!');

			}else if ( $password == '' OR strlen($password) < 6 ) {
				$ret = array('error'=> '1', 'message'=>'Password minimal 6 karakter.');

			}else if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-zA-Z]).*$/", $password) === 0) {
				// min 6 karakter, terdiri dari minimum 1 huruf, minimum 1 angka
				$ret = array('error'=> '1', 'message'=>'Password harus terdiri dari huruf dan angka, serta minimal 1 huruf besar.');
			
			}else if ( $password != $repassword ) {
				$ret = array('error'=> '1', 'message'=>'Password dan Konfirmasi Password tidak sama!');
				
			}else if (
				$fullname != '' 
				&& $notelp != '' 
				&& $password == $repassword && strlen($password) >=6 
				) {
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
				// $mem_data['mum_nomor_rekening'] = trim($post['nomor_rekening']);

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
					// $user['Nomor_rekening']     = trim($post['nomor_rekening']);
					// $user['nama_bank']          = trim($post['nama_bank']);					
					$user['id_mod_user_member'] = $uid;

					$userID = $this->Content_model->insert_user($user);

					// user_detail
					$u_detail['Id_pengguna']       = $userID;
					$u_detail['user_type']         = 'peminjam';
					$u_detail['Mobileno']          = $notelp;
					//$u_detail['Jumlah_permohonan_pinjaman'] = $total_pinjam;

					$this->Content_model->insert_userdetail($u_detail);
					
					// profile_geografi
					$u_geo['Agama']       = NULL;
					$u_geo['Alamat']      = NULL;
					$u_geo['Kodepos']     = NULL;
					$u_geo['Kota']        = NULL;
					$u_geo['Provinsi']    = NULL;
					$u_geo['User_id']     = $userID;

					$this->Content_model->insert_profil_geografi($u_geo);		

					/* // profil_permohonan_pinjaman
					$p_pinjam['Master_loan_id']          = $orderID;
					$p_pinjam['Tgl_permohonan_pinjaman'] = $nowdatetime;
					$p_pinjam['Jml_permohonan_pinjaman'] = $total_pinjam;
					$p_pinjam['User_id']                 = $userID;
					$p_pinjam['Product_id']              = $productID;
					$p_pinjam['Master_loan_status']      = 'review';
					$p_pinjam['pinjam_member_id']        = $uid;
					$p_pinjam['jml_permohonan_pinjaman_awal'] = $p_pinjam['Jml_permohonan_pinjaman'];
					$p_pinjam['nama_peminjam']           = $fullname;

					$pinjamID = $this->Content_model->insert_profil_pinjaman($p_pinjam);

					$produk = $this->Content_model->get_produk($productID);

							// Log Transaksi
							$inlog['ltp_Id_pengguna']              = $p_pinjam['User_id'];
							$inlog['ltp_Master_loan_id']           = $p_pinjam['Master_loan_id'];
							$inlog['ltp_total_pinjaman']           = $p_pinjam['Jml_permohonan_pinjaman'];
							$inlog['ltp_total_pinjaman_disetujui'] = 0;
							$inlog['ltp_admin_fee']                = 0;
							$inlog['ltp_bunga_pinjaman']           = 0;
							$inlog['ltp_jml_angsuran']             = 0;
							$inlog['ltp_lama_angsuran']            = 0;
							$inlog['ltp_tgl_jatuh_tempo']          = '0000-00-00';
							$inlog['ltp_platform_fee']             = 0;
							$inlog['ltp_lender_fee']               = 0;
							$inlog['ltp_product_title']            = $produk['product_title'];
							$inlog['ltp_product_id']               = $produk['Product_id'];
							$inlog['ltp_product_interest_rate']    = $produk['Interest_rate'];
							$inlog['ltp_product_loan_term']        = $produk['Loan_term'];
							$inlog['ltp_product_platform_rate']    = $produk['Platform_rate'];
							$inlog['ltp_product_loan_organizer']   = $produk['Loan_organizer'];
							$inlog['ltp_product_investor_return']  = $produk['Investor_return'];
							$inlog['ltp_product_revenue_share']    = $produk['Fee_revenue_share'];
							$inlog['ltp_product_secured_loan_fee'] = $produk['Secured_loan_fee'];
							$inlog['ltp_product_interest_rate_type'] = $produk['type_of_interest_rate'];				
							$inlog['ltp_product_pph']                = $produk['PPH'];				
							$inlog['ltp_type_of_business_id']        = $produk['type_of_business_id'];
							$inlog['ltp_loan_organizer_id']          = 1;
							$inlog['ltp_date_created']               = date('Y-m-d H:i:s');
							$this->Content_model->insert_log_transaksi_pinjam($inlog);*/

					// ranking
					$get_ranking = set_ranking_pengguna($userID, 1, 1); // (Id_pengguna, peminjam/pendana, kilat/mikro)

					$update_pengguna['peringkat_pengguna']            = $get_ranking['grade'];
					$update_pengguna['peringkat_pengguna_persentase'] = $get_ranking['ranking'];
					$this->Content_model->update_user_byid($userID, $update_pengguna);
					// End ranking

					if($email==''){
						// redirect('email-aktivasi');
						// $set_otp = $this->Member_model->set_cookies_otp($email);
						$ciphertext = urlencode($this->encryption->encrypt(trim($post['telp'])));
						$activation_url = site_url('aktivasi?t='.$ciphertext);
						$ret = array('error'=> '0', 'message'=>'Sukses daftar pinjaman kilat.','activation_url'=>$activation_url);
					
					}else{	 
						$this->send_email($email);
					
						$ret = array('error'=> '0', 'message'=>'Sukses daftar pinjaman kilat.');
						$this->session->set_userdata('message','Sukses daftar pinjaman kilat');
						$this->session->set_userdata('message_type','success');
					}
						/*$ret = array('error'=> '0', 'message'=>'Sukses daftar pinjaman kilat.');
						$this->session->set_userdata('message','Sukses daftar pinjaman kilat');
						$this->session->set_userdata('message_type','success');*/
				}
			}else{
				$ret = array('error'=> '1', 'message'=>'Isilah semua kolom!');
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
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }	

        return TRUE;
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
		$logintype    = isset($_SESSION['_bkdtype_'])? htmlentities($_SESSION['_bkdtype_']) : 0; // 1.peminjam, 2.pendana
		
		if (isset($_SESSION['_bkdlog_']) && isset($_SESSION['_bkduser_'])) {
			// Jika sudah login maka redirect ke form only
			$uid = htmlentities(strip_tags($_SESSION['_bkduser_']));	
			$getmember = $this->Content_model->get_memberdata($uid);

			if ($logintype=='1') {
				if($getmember['mum_type_peminjam']=='2'){
					redirect('formulir-pinjaman-mikro');
					exit();	
				}else{
					redirect('dashboard');
					exit();
				}
			}else{
				redirect('message/restrict_pendana');
				exit();
			}
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
		$data['bottom_js'] .= add_js("js/fileinput/plugins/piexif.min.js");
		$data['bottom_js'] .= add_js("js/fileinput/fileinput.min.js");
		$data['bottom_js'] .= add_js('js/fileinput-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/date-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/form-wizard.js');
		$data['bottom_js'] .= add_js('js/exif-js-master/exif.js');
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
			
			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');
			$fullname    = trim($post['fullname']);
			$notelp      = trim($post['telp']);
			$email       = trim($post['email']);
			$password    = trim($post['password']);
			$repassword  = trim($post['confirm_password']);

			/*$filter = explode('.', trim($post['jumlah_pinjam']));
			$total_pinjam = str_replace(',', '', $filter[0]);

			$productID = trim($post['product']);*/

			$check = $this->Content_model->check_existing_member($email, $notelp, '');
			$count_member = count($check);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL) && $email!='') {
				$ret = array('error'=> '1', 'message'=>'Invalid Email format!');
			
			}else if ( $count_member > 1 ){
				$ret = array('error'=> '1', 'message'=>'Email/No.telp Anda sudah terdaftar!');
			
			}else if ( $password == '' OR strlen($password) < 6 ) {
				$ret = array('error'=> '1', 'message'=>'Password minimal 6 karakter.');

			}else if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-zA-Z]).*$/", $password) === 0) {
				// min 6 karakter, terdiri dari minimum 1 huruf, minimum 1 angka
				$ret = array('error'=> '1', 'message'=>'Password harus terdiri dari huruf dan angka, serta minimal 1 huruf besar');

			}else if ( $password != $repassword ) {
				$ret = array('error'=> '1', 'message'=>'Password dan Konfirmasi Password tidak sama!');

			/*}else if ($total_pinjam < 100000) {
				$ret = array('error'=> '1', 'message'=>'Jumlah Pinjaman minimal Rp 100,000');*/
			
			}else if (
				$fullname != '' 
				&& $notelp != '' 
				&& $password == $repassword && strlen($password) >=6 
			) {
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
				// $mem_data['mum_nomor_rekening'] = trim($post['nomor_rekening']);

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
					// $user['Nomor_rekening']     = $post['nomor_rekening'];
					// $user['nama_bank']          = trim($post['nama_bank']);
					$user['id_mod_user_member'] = $uid;

					$userID = $this->Content_model->insert_user($user);

					// user_detail
					$u_detail['Id_pengguna']       = $userID;
					$u_detail['user_type']         = 'peminjam';
					$u_detail['Mobileno']          = $notelp;
					// $u_detail['Jumlah_permohonan_pinjaman'] = $total_pinjam;

					$this->Content_model->insert_userdetail($u_detail);
					
					// profile_geografi
					$u_geo['Agama']       = NULL;
					$u_geo['Alamat']      = NULL;
					$u_geo['Kodepos']     = NULL;
					$u_geo['Kota']        = NULL;
					$u_geo['Provinsi']    = NULL;
					$u_geo['User_id']     = $userID;

					$this->Content_model->insert_profil_geografi($u_geo);		

					/*// profil_permohonan_pinjaman
					$p_pinjam['Master_loan_id']          = $orderID;
					$p_pinjam['Tgl_permohonan_pinjaman'] = $nowdatetime;
					$p_pinjam['Jml_permohonan_pinjaman'] = $total_pinjam;
					$p_pinjam['User_id']                 = $userID;
					$p_pinjam['Product_id']              = $productID;
					$p_pinjam['Master_loan_status']      = 'review';
					$p_pinjam['pinjam_member_id']        = $uid;
					$p_pinjam['jml_permohonan_pinjaman_awal'] = $p_pinjam['Jml_permohonan_pinjaman'];
					$p_pinjam['nama_peminjam']           = $fullname;

					$pinjamID =$this->Content_model->insert_profil_pinjaman($p_pinjam);

					$produk = $this->Content_model->get_produk($productID);

							// Log Transaksi
							$inlog['ltp_Id_pengguna']              = $p_pinjam['User_id'];
							$inlog['ltp_Master_loan_id']           = $p_pinjam['Master_loan_id'];
							$inlog['ltp_total_pinjaman']           = $p_pinjam['Jml_permohonan_pinjaman'];
							$inlog['ltp_total_pinjaman_disetujui'] = 0;
							$inlog['ltp_admin_fee']                = 0;
							$inlog['ltp_bunga_pinjaman']           = 0;
							$inlog['ltp_jml_angsuran']             = 0;
							$inlog['ltp_lama_angsuran']            = 0;
							$inlog['ltp_tgl_jatuh_tempo']          = '0000-00-00';
							$inlog['ltp_platform_fee']             = 0;
							$inlog['ltp_lender_fee']               = 0;
							$inlog['ltp_product_title']            = $produk['product_title'];
							$inlog['ltp_product_id']               = $produk['Product_id'];
							$inlog['ltp_product_interest_rate']    = $produk['Interest_rate'];
							$inlog['ltp_product_loan_term']        = $produk['Loan_term'];
							$inlog['ltp_product_platform_rate']    = $produk['Platform_rate'];
							$inlog['ltp_product_loan_organizer']   = $produk['Loan_organizer'];
							$inlog['ltp_product_investor_return']  = $produk['Investor_return'];
							$inlog['ltp_product_revenue_share']    = $produk['Fee_revenue_share'];
							$inlog['ltp_product_secured_loan_fee'] = $produk['Secured_loan_fee'];
							$inlog['ltp_product_interest_rate_type'] = $produk['type_of_interest_rate'];				
							$inlog['ltp_product_pph']                = $produk['PPH'];				
							$inlog['ltp_type_of_business_id']        = $produk['type_of_business_id'];
							$inlog['ltp_loan_organizer_id']          = 1;
							$inlog['ltp_date_created']               = date('Y-m-d H:i:s');
							$this->Content_model->insert_log_transaksi_pinjam($inlog);*/

					// ranking
					$get_ranking = set_ranking_pengguna($userID, 1, 2); // (Id_pengguna, peminjam/pendana, kilat/mikro)

					$update_pengguna['peringkat_pengguna']            = $get_ranking['grade'];
					$update_pengguna['peringkat_pengguna_persentase'] = $get_ranking['ranking'];
					$this->Content_model->update_user_byid($userID, $update_pengguna);
					// End ranking
					if($email==''){
						// redirect('email-aktivasi');
						// $set_otp = $this->Member_model->set_cookies_otp($email);
						$ciphertext = urlencode($this->encryption->encrypt(trim($post['telp'])));
						$activation_url = site_url('aktivasi?t='.$ciphertext);
						$ret = array('error'=> '0', 'message'=>'Sukses daftar pinjaman mikro.','activation_url'=>$activation_url);
					
					}else{	 
						$this->send_email($email);
					
						$ret = array('error'=> '0', 'message'=>'Sukses daftar pinjaman mikro.');
						$this->session->set_userdata('message','Sukses daftar pinjaman mikro');
						$this->session->set_userdata('message_type','success');
					}
						/*$ret = array('error'=> '0', 'message'=>'Sukses daftar pinjaman kilat.');
						$this->session->set_userdata('message','Sukses daftar pinjaman kilat');
						$this->session->set_userdata('message_type','success');*/
				}
			}else{
				$ret = array('error'=> '1', 'message'=>'Isilah semua kolom!');
				$this->session->set_userdata('message','Isilah semua kolom!');
				$this->session->set_userdata('message_type','error');
			}

			echo json_encode($ret);
		}
	}

	public function daftar_agri()
	{
		// ====== Daftar pinjaman Agri belum login ====== //

		// clear browser cache
		header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		$logintype    = isset($_SESSION['_bkdtype_'])? htmlentities($_SESSION['_bkdtype_']) : 0; // 1.peminjam, 2.pendana
		
		if (isset($_SESSION['_bkdlog_']) && isset($_SESSION['_bkduser_'])) {
			// Jika sudah login maka redirect ke form only
			$uid = htmlentities(strip_tags($_SESSION['_bkduser_']));	
			$getmember = $this->Content_model->get_memberdata($uid);

			if ($logintype=='1') {
				if($getmember['mum_type_peminjam']=='3'){
					redirect('formulir-pinjaman-agri');
					exit();	
				}else{
					redirect('dashboard');
					exit();	
				}
			}else{
				redirect('message/restrict_pendana');
				exit();
			}
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
		$data['bottom_js'] .= add_js('js/pinjaman-agri.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('daftar, pinjaman agri', 'daftar pinjaman agri');

		$data['products'] = $this->Content_model->get_pinjaman(5); // type_off_business_id

		$data['pages']    = 'v_register_pinjaman_agri';
		$this->load->view('template', $data);
	}

	function submit_reg_agri()
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$post = $this->input->post(NULL, TRUE);

			/*_d($post);
			_d($_FILES);*/
			
			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');
			$fullname    = trim($post['fullname']);
			$notelp      = trim($post['telp']);
			$email       = trim($post['email']);
			$password    = trim($post['password']);
			$repassword  = trim($post['confirm_password']);

			/*$filter = explode('.', trim($post['jumlah_pinjam']));
			$total_pinjam = str_replace(',', '', $filter[0]);

			$productID = trim($post['product']);*/

			$check = $this->Content_model->check_existing_member($email, $notelp, '');
			$count_member = count($check);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL) && $email='') {
				$ret = array('error'=> '1', 'message'=>'Invalid Email format!');
			
			}else if ( $count_member > 1 ){
				$ret = array('error'=> '1', 'message'=>'Email/No.telp Anda sudah terdaftar!');
			
			}else if ( $password == '' OR strlen($password) < 6 ) {
				$ret = array('error'=> '1', 'message'=>'Password minimal 6 karakter.');

			}else if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-zA-Z]).*$/", $password) === 0) {
				// min 6 karakter, terdiri dari minimum 1 huruf, minimum 1 angka
				$ret = array('error'=> '1', 'message'=>'Password harus terdiri dari huruf dan angka, serta minimal 1 huruf besar');

			}else if ( $password != $repassword ) {
				$ret = array('error'=> '1', 'message'=>'Password dan Konfirmasi Password tidak sama!');

			/*}else if ($total_pinjam < 100000) {
				$ret = array('error'=> '1', 'message'=>'Jumlah Pinjaman minimal Rp 100,000');*/
			
			}else if (
				$fullname != '' 
				&& $notelp != '' 
				&& $password == $repassword && strlen($password) >=6 
			) {
				// mod_user_member

				$stored_p = password_hash(base64_encode(hash('sha256', (trim($post['password'])), true)), PASSWORD_DEFAULT);

				$mem_data['mum_fullname']       = trim($post['fullname']);
				$mem_data['mum_email']          = trim($post['email']);
				$mem_data['mum_telp']           = trim($post['telp']);
				$mem_data['mum_password']       = $stored_p;
				$mem_data['mum_status']         = 0;
				$mem_data['mum_create_date']    = $nowdatetime;
				$mem_data['mum_type']           = '1'; // 1.peminjam, 2.pendana
				$mem_data['mum_type_peminjam']  = '3'; // 1.Kilat, 2.mikro
				// $mem_data['mum_nomor_rekening'] = trim($post['nomor_rekening']);

				$uid = $this->Content_model->insert_mod_usermember($mem_data);

				if ($uid) {

					$prefixID    = 'PA-';
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
					// $user['Nomor_rekening']     = $post['nomor_rekening'];
					// $user['nama_bank']          = trim($post['nama_bank']);
					$user['id_mod_user_member'] = $uid;
					$user['Pekerjaan']			= 5;
					$user['bidang_pekerjaan']	= 'agrikultur';

					$userID = $this->Content_model->insert_user($user);

					// user_detail
					$u_detail['Id_pengguna']       = $userID;
					$u_detail['user_type']         = 'peminjam';
					$u_detail['Mobileno']          = $notelp;
					// $u_detail['Jumlah_permohonan_pinjaman'] = $total_pinjam;

					$this->Content_model->insert_userdetail($u_detail);
					
					// profile_geografi
					$u_geo['Agama']       = NULL;
					$u_geo['Alamat']      = NULL;
					$u_geo['Kodepos']     = NULL;
					$u_geo['Kota']        = NULL;
					$u_geo['Provinsi']    = NULL;
					$u_geo['User_id']     = $userID;

					$this->Content_model->insert_profil_geografi($u_geo);		

					/*// profil_permohonan_pinjaman
					$p_pinjam['Master_loan_id']          = $orderID;
					$p_pinjam['Tgl_permohonan_pinjaman'] = $nowdatetime;
					$p_pinjam['Jml_permohonan_pinjaman'] = $total_pinjam;
					$p_pinjam['User_id']                 = $userID;
					$p_pinjam['Product_id']              = $productID;
					$p_pinjam['Master_loan_status']      = 'review';
					$p_pinjam['pinjam_member_id']        = $uid;
					$p_pinjam['jml_permohonan_pinjaman_awal'] = $p_pinjam['Jml_permohonan_pinjaman'];
					$p_pinjam['nama_peminjam']           = $fullname;

					$pinjamID =$this->Content_model->insert_profil_pinjaman($p_pinjam);

					$produk = $this->Content_model->get_produk($productID);

							// Log Transaksi
							$inlog['ltp_Id_pengguna']              = $p_pinjam['User_id'];
							$inlog['ltp_Master_loan_id']           = $p_pinjam['Master_loan_id'];
							$inlog['ltp_total_pinjaman']           = $p_pinjam['Jml_permohonan_pinjaman'];
							$inlog['ltp_total_pinjaman_disetujui'] = 0;
							$inlog['ltp_admin_fee']                = 0;
							$inlog['ltp_bunga_pinjaman']           = 0;
							$inlog['ltp_jml_angsuran']             = 0;
							$inlog['ltp_lama_angsuran']            = 0;
							$inlog['ltp_tgl_jatuh_tempo']          = '0000-00-00';
							$inlog['ltp_platform_fee']             = 0;
							$inlog['ltp_lender_fee']               = 0;
							$inlog['ltp_product_title']            = $produk['product_title'];
							$inlog['ltp_product_id']               = $produk['Product_id'];
							$inlog['ltp_product_interest_rate']    = $produk['Interest_rate'];
							$inlog['ltp_product_loan_term']        = $produk['Loan_term'];
							$inlog['ltp_product_platform_rate']    = $produk['Platform_rate'];
							$inlog['ltp_product_loan_organizer']   = $produk['Loan_organizer'];
							$inlog['ltp_product_investor_return']  = $produk['Investor_return'];
							$inlog['ltp_product_revenue_share']    = $produk['Fee_revenue_share'];
							$inlog['ltp_product_secured_loan_fee'] = $produk['Secured_loan_fee'];
							$inlog['ltp_product_interest_rate_type'] = $produk['type_of_interest_rate'];				
							$inlog['ltp_product_pph']                = $produk['PPH'];				
							$inlog['ltp_type_of_business_id']        = $produk['type_of_business_id'];
							$inlog['ltp_loan_organizer_id']          = 1;
							$inlog['ltp_date_created']               = date('Y-m-d H:i:s');
							$this->Content_model->insert_log_transaksi_pinjam($inlog);*/

					// ranking
					$get_ranking = set_ranking_pengguna($userID, 1, 2); // (Id_pengguna, peminjam/pendana, kilat/mikro)

					$update_pengguna['peringkat_pengguna']            = $get_ranking['grade'];
					$update_pengguna['peringkat_pengguna_persentase'] = $get_ranking['ranking'];
					$this->Content_model->update_user_byid($userID, $update_pengguna);
					// End ranking
					if($email==''){
						// redirect('email-aktivasi');
						// $set_otp = $this->Member_model->set_cookies_otp($email);
						$ciphertext = urlencode($this->encryption->encrypt(trim($post['telp'])));
						$activation_url = site_url('aktivasi?t='.$ciphertext);
						$ret = array('error'=> '0', 'message'=>'Sukses daftar pinjaman agri.','activation_url'=>$activation_url);
					
					}else{	 
						$this->send_email($email);
					
						$ret = array('error'=> '0', 'message'=>'Sukses daftar pinjaman agri.');
						$this->session->set_userdata('message','Sukses daftar pinjaman agri');
						$this->session->set_userdata('message_type','success');
					}
						/*$ret = array('error'=> '0', 'message'=>'Sukses daftar pinjaman kilat.');
						$this->session->set_userdata('message','Sukses daftar pinjaman kilat');
						$this->session->set_userdata('message_type','success');*/
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

		$memberID = (int)$_SESSION['_bkduser_'];
		$logintype = isset($_SESSION['_bkdtype_'])? htmlentities($_SESSION['_bkdtype_']) : 0; // 1.peminjam, 2.pendana

		if ($logintype=='2') {	
			redirect('message/restrict_pendana');
			exit();
		}

		$pinjaman_active = $this->Content_model->check_active_pinjaman($memberID);

		if (count($pinjaman_active) > 1)
		{
			redirect('message/pinjaman_belum_selesai');
			exit();
		}

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
		//$data['bottom_js'] .= add_js('js/form-wizard.js');
		$data['bottom_js'] .= add_js('js/pinjaman-kilat2.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('daftar, pinjaman kilat', 'daftar pinjaman kilat');

		$data['harga'] = $this->Content_model->get_harga_pinjaman_kilat();
		$data['products'] = $this->Content_model->get_pinjaman(1); // type_off_business_id

		
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

				if ( $password == '' ) {
					$ret = array('error'=> '1', 'message'=>'Password harus diisi.');
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
						// tambahan baru
						$p_pinjam['Amount'] =$total_pinjam;
						$p_pinjam['Jml_permohonan_pinjaman_disetujui'] = $total_pinjam; 
						// batas tambahan baru
						$p_pinjam['User_id']                 = $user_data['Id_pengguna'];
						$p_pinjam['Product_id']              = $productID;
						$p_pinjam['Master_loan_status']      = 'review';
						$p_pinjam['pinjam_member_id']        = $uid;
						$p_pinjam['jml_permohonan_pinjaman_awal'] = $p_pinjam['Jml_permohonan_pinjaman'];
						$p_pinjam['nama_peminjam']                = $member_data['Nama_pengguna'];

						$pinjamID = $this->Content_model->insert_profil_pinjaman($p_pinjam);
						
						//tambahan baru

						$this->db->from('record_pinjaman');
						$this->db->where('User_id', $user_data['Id_pengguna']);
						$this->db->like('Flag', 'P', 'BOTH');
						$hasil=$this->db->get()->num_rows();
						// $p_pinjam2['user_id'] =$uid;
					/*	if($hasil>0){
							$hasil+=1;	
						}else{
							$hasil="1";
						}*/

						$p_pinjam2['Flag'] = 'P';
						$p_pinjam2['Master_loan_id'] = $orderID;
						$p_pinjam2['User_id'] = $user_data['Id_pengguna'];
						$p_pinjam2['Amount'] = $total_pinjam;
						$p_pinjam2['Tgl_pinjaman'] = date('Y-m-d H:i:s');
						// $this->db->insert('record_pinjaman', $p_pinjam2);
						$pinjamID2 = $this->Content_model->insert_profil_pinjaman1($p_pinjam2);

						//batas tambahan baru

						if ($pinjamID) {

							$produk = $this->Content_model->get_produk($productID);

							// Log Transaksi
							$inlog['ltp_Id_pengguna']              = $p_pinjam['User_id'];
							$inlog['ltp_Master_loan_id']           = $p_pinjam['Master_loan_id'];
							$inlog['ltp_total_pinjaman']           = $p_pinjam['Jml_permohonan_pinjaman'];
							$inlog['ltp_total_pinjaman_disetujui'] = 0;
							$inlog['ltp_admin_fee']                = 0;
							$inlog['ltp_bunga_pinjaman']           = 0;
							$inlog['ltp_jml_angsuran']             = 0;
							$inlog['ltp_lama_angsuran']            = 0;
							$inlog['ltp_tgl_jatuh_tempo']          = '0000-00-00';
							$inlog['ltp_platform_fee']             = 0;
							$inlog['ltp_lender_fee']               = 0;
							$inlog['ltp_product_title']            = $produk['product_title'];
							$inlog['ltp_product_id']               = $produk['Product_id'];
							$inlog['ltp_product_interest_rate']    = $produk['Interest_rate'];
							$inlog['ltp_product_loan_term']        = $produk['Loan_term'];
							$inlog['ltp_product_platform_rate']    = $produk['Platform_rate'];
							$inlog['ltp_product_loan_organizer']   = $produk['Loan_organizer'];
							$inlog['ltp_product_investor_return']  = $produk['Investor_return'];
							$inlog['ltp_product_revenue_share']    = $produk['Fee_revenue_share'];
							$inlog['ltp_product_secured_loan_fee'] = $produk['Secured_loan_fee'];
							$inlog['ltp_product_interest_rate_type'] = $produk['type_of_interest_rate'];				
							$inlog['ltp_product_pph']                = $produk['PPH'];				
							$inlog['ltp_type_of_business_id']        = $produk['type_of_business_id'];
							$inlog['ltp_loan_organizer_id']          = 1;
							$inlog['ltp_date_created']               = date('Y-m-d H:i:s');
							$this->Content_model->insert_log_transaksi_pinjam($inlog);
						}

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

		$memberID = (int)$_SESSION['_bkduser_'];
		$logintype = isset($_SESSION['_bkdtype_'])? htmlentities($_SESSION['_bkdtype_']) : 0; // 1.peminjam, 2.pendana

		$uid = htmlentities(strip_tags($_SESSION['_bkduser_']));	
		$getmember = $this->Content_model->get_memberdata($uid);

		if ($logintype=='1') {
			if($getmember['mum_type_peminjam'] !='2'){
				redirect('dashboard');
				exit();	
			}
		} else if ($logintype=='2') {	
			redirect('message/restrict_pendana');
			exit();
		}
		
		$pinjaman_active = $this->Content_model->check_active_pinjaman($memberID);

		if (count($pinjaman_active) > 1)
		{
			redirect('message/pinjaman_belum_selesai');
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

		$data['bottom_js'] .= add_js("js/fileinput/plugins/piexif.min.js");
		$data['bottom_js'] .= add_js("js/fileinput/fileinput.min.js");
		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/bootstrap-datepicker/js/bootstrap-datepicker.min.js');
		$data['bottom_js'] .= add_js('js/jqueryvalidation/dist/jquery.validate.min.js');
		$data['bottom_js'] .= add_js('js/alertify/alertify.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/date-init.js');
		$data['bottom_js'] .= add_js('js/pinjaman-mikro2.js');
		$data['bottom_js'] .= add_js('js/exif-js-master/exif.js');
		$data['bottom_js'] .= add_js('js/fileinput-init.js');
		$data['bottom_js'] .= add_js('js/ImageTools.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('daftar, pinjaman mikro', 'daftar pinjaman mikro');

		//$data['meta_tag'] = $this->m_settings->meta_default();
		$data['products'] = $this->Content_model->get_pinjaman(3); // type_off_business_id

		$data['memberdata'] = $this->Member_model->get_member_byid($memberID);

		$content_mikro = $this->Content_model->screening_mikro_rows($data['memberdata']['Id_pengguna']);

		if (empty($content_mikro['What_is_the_name_of_your_business']) )
			//OR empty($content_mikro['images_usaha_name'])  )
		{
			//echo 'belum lengkap';
			$data['pages']    = 'v_form_pinjaman_mikro_lengkap';
		}else{
			//echo 'sudah lengkap';
			$data['pages']    = 'v_form_pinjaman_mikro';
		}

		$this->load->view('template', $data);
	}

	function submit_p_mikro()
	{
		$uid = htmlentities(strip_tags($_SESSION['_bkduser_']));

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
				$produk = $this->Content_model->get_produk($productID);
				$min_pinjam = 2000000;
				$max_pinjam = $produk['Max_loan'];
				if ( (int)$total_pinjam < $min_pinjam ) {
					$ret = array('error'=> '1', 'message'=>'Pinjaman tidak boleh dibawah Rp '.number_format($min_pinjam));
				}
				if ( (int)$total_pinjam > $max_pinjam ) {
					$ret = array('error'=> '1', 'message'=>'Pinjaman tidak boleh diatas Rp '.number_format($max_pinjam));
					
				}else if ( $password == '' OR strlen($password) < 6 ) {
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

					if ($post['kelengkapan'] == '0'){
						// jika kelengkapan MIKRO belum lengkap
						if (trim($post['usaha'])=='' OR trim($post['lama_usaha'])=='' OR $_FILES['usaha_file']['tmp_name']  == '' ) {
							$ret = array('error'=> '1', 'message'=>'Anda harus mengisi kolom Usaha, Lama Usaha, dan Upload Foto Usaha!');
							echo json_encode($ret);
							exit();
						}else{

							//$upload_usaha = file_get_contents($_FILES['usaha_file']['tmp_name']);

							// if( isset($_FILES['usaha_file']['name']) && $_FILES['usaha_file']['name'] != ''){
							// 	// ----- Process Image Name -----
							// 	$img_info          = pathinfo($_FILES['usaha_file']['name']);
							// 	$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
							// 	$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
							// 	$fileExt           = $img_info['extension'];
							// 	$file_usaha_name   = $fileName.'.'.$fileExt;
							// 	// ----- END Process Image Name -----
								
							// 	$u_detail['images_usaha_name'] = $file_usaha_name;
							// }else{
							// 	$file_usaha_name   = '';
							// }

							$destination_usaha = $this->config->item('member_images_dir'). $member_data['Id_pengguna']."/usaha/";

							if($post['usaha_file_hidden']!=''){
								if (!is_file($destination_usaha)) {
									mkdir_r($destination_usaha);
								}	
								if($post['old_usaha']!=''){
									if (is_file($destination_usaha.$post['old_usaha'])){
										unlink($destination_usaha.$post['old_usaha']);
									}
								}
								$data = $_POST['usaha_file_hidden'];
								$splited = explode(',', substr( $data , 5 ) , 2);
								$mime=$splited[0];
							    $data=$splited[1];

							    $mime_split_without_base64=explode(';', $mime,2);
							    $mime_split=explode('/', $mime_split_without_base64[0],2);
							    if(count($mime_split)==2)
							    {
							        $extension=$mime_split[1];
							        if($extension=='jpeg')$extension='jpg';
							        //if($extension=='javascript')$extension='js';
							        //if($extension=='text')$extension='txt';
							        $output_file_with_extension=rand().'.'.$extension;
							    }

								$data = base64_decode($data);
								$file = $destination_usaha.$output_file_with_extension;
								$success = file_put_contents($file, $data);
								$u_detail['images_usaha_name']  = $output_file_with_extension;
							}


							$u_detail['What_is_the_name_of_your_business']        = trim($post['usaha']);
							$u_detail['How_many_years_have_you_been_in_business'] = trim($post['lama_usaha']);
							//$u_detail['Photo_business_location']                  = $upload_usaha;
							//$u_detail['foto_usaha']                               = $upload_usaha;
							$this->Content_model->update_userdetail($member_data['Id_pengguna'], $u_detail);
						}

						// Upload Image
						// $destination_usaha = $this->config->item('member_images_dir'). $member_data['Id_pengguna']."/usaha/";

						// if(isset($_FILES['usaha_file']['name']) && $_FILES['usaha_file']['name'] != ''){
						// 	if (!is_file($destination_usaha.$file_usaha_name)) {
						// 		mkdir_r($destination_usaha);
						// 	}
						// 	move_uploaded_file($_FILES['usaha_file']['tmp_name'], $destination_usaha.$file_usaha_name);
						// }
					}

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
						//tambahan baru
						$p_pinjam['Amount'] =$total_pinjam;
						$p_pinjam['Jml_permohonan_pinjaman_disetujui'] = $total_pinjam; 
						//batas tambahan baru
						$p_pinjam['User_id']                 = $user_data['Id_pengguna'];
						$p_pinjam['Product_id']              = $productID;
						$p_pinjam['Master_loan_status']      = 'review';
						$p_pinjam['pinjam_member_id']        = $uid;
						$p_pinjam['jml_permohonan_pinjaman_awal'] = $p_pinjam['Jml_permohonan_pinjaman'];
						$p_pinjam['nama_peminjam']           = $member_data['Nama_pengguna'];

						$pinjamID =$this->Content_model->insert_profil_pinjaman($p_pinjam);

												//tambahan baru

						$this->db->from('record_pinjaman');
						$this->db->where('User_id', $user_data['Id_pengguna']);
						$this->db->like('Flag', 'P', 'BOTH');
						$hasil=$this->db->get()->num_rows();
						// $p_pinjam2['user_id'] =$uid;
					/*	if($hasil>0){
							$hasil+=1;	
						}else{
							$hasil="1";
						}*/

						$p_pinjam3['Flag'] = 'P';
						$p_pinjam3['Master_loan_id'] = $orderID;
						$p_pinjam3['User_id'] = $user_data['Id_pengguna'];
						$p_pinjam3['Amount'] = $total_pinjam;
						$p_pinjam3['Tgl_pinjaman'] = date('Y-m-d H:i:s');
						// $this->db->insert('record_pinjaman', $p_pinjam2);
						$pinjamID3 = $this->Content_model->insert_profil_pinjaman1($p_pinjam3);

						//batas tambahan baru

						if ($pinjamID) {

							$produk = $this->Content_model->get_produk($productID);

							// Log Transaksi
							$inlog['ltp_Id_pengguna']              = $p_pinjam['User_id'];
							$inlog['ltp_Master_loan_id']           = $p_pinjam['Master_loan_id'];
							$inlog['ltp_total_pinjaman']           = $p_pinjam['Jml_permohonan_pinjaman'];
							$inlog['ltp_total_pinjaman_disetujui'] = 0;
							$inlog['ltp_admin_fee']                = 0;
							$inlog['ltp_bunga_pinjaman']           = 0;
							$inlog['ltp_jml_angsuran']             = 0;
							$inlog['ltp_lama_angsuran']            = 0;
							$inlog['ltp_tgl_jatuh_tempo']          = '0000-00-00';
							$inlog['ltp_platform_fee']             = 0;
							$inlog['ltp_lender_fee']               = 0;
							$inlog['ltp_product_title']            = $produk['product_title'];
							$inlog['ltp_product_id']               = $produk['Product_id'];
							$inlog['ltp_product_interest_rate']    = $produk['Interest_rate'];
							$inlog['ltp_product_loan_term']        = $produk['Loan_term'];
							$inlog['ltp_product_platform_rate']    = $produk['Platform_rate'];
							$inlog['ltp_product_loan_organizer']   = $produk['Loan_organizer'];
							$inlog['ltp_product_investor_return']  = $produk['Investor_return'];
							$inlog['ltp_product_revenue_share']    = $produk['Fee_revenue_share'];
							$inlog['ltp_product_secured_loan_fee'] = $produk['Secured_loan_fee'];
							$inlog['ltp_product_interest_rate_type'] = $produk['type_of_interest_rate'];				
							$inlog['ltp_product_pph']                = $produk['PPH'];				
							$inlog['ltp_type_of_business_id']        = $produk['type_of_business_id'];
							$inlog['ltp_loan_organizer_id']          = 1;
							$inlog['ltp_date_created']               = date('Y-m-d H:i:s');
							$this->Content_model->insert_log_transaksi_pinjam($inlog);
						}

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

	//================ Pinjaman AGRI ====================

	public function agri()
	{
		$this->Content_model->has_login();

		$memberID = (int)$_SESSION['_bkduser_'];
		$logintype = isset($_SESSION['_bkdtype_'])? htmlentities($_SESSION['_bkdtype_']) : 0; // 1.peminjam, 2.pendana

		$uid = htmlentities(strip_tags($_SESSION['_bkduser_']));	
		$getmember = $this->Content_model->get_memberdata($uid);

		if ($logintype=='1') {
			if($getmember['mum_type_peminjam'] !='3'){
				redirect('dashboard');
				exit();	
			}
		} else if ($logintype=='2') {	
			redirect('message/restrict_pendana');
			exit();
		}
		
		$pinjaman_active = $this->Content_model->check_active_pinjaman($memberID);

		if (count($pinjaman_active) > 1)
		{
			redirect('message/pinjaman_belum_selesai');
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

		$data['bottom_js'] .= add_js("js/fileinput/fileinput.min.js");
		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/bootstrap-datepicker/js/bootstrap-datepicker.min.js');
		$data['bottom_js'] .= add_js('js/jqueryvalidation/dist/jquery.validate.min.js');
		$data['bottom_js'] .= add_js('js/alertify/alertify.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/date-init.js');
		$data['bottom_js'] .= add_js("js/fileinput/plugins/piexif.min.js");
		$data['bottom_js'] .= add_js("js/fileinput/fileinput.min.js");
		$data['bottom_js'] .= add_js('js/pinjaman-agri2.js');
		$data['bottom_js'] .= add_js('js/fileinput-init.js');
		$data['bottom_js'] .= add_js('js/exif-js-master/exif.js');
		$data['bottom_js'] .= add_js('js/ImageTools.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('daftar, pinjaman agri', 'daftar pinjaman agri');

		//$data['meta_tag'] = $this->m_settings->meta_default();
		$data['products'] = $this->Content_model->get_pinjaman(5); // type_off_business_id

		$data['memberdata'] = $this->Member_model->get_member_byid($memberID);

		//$content_mikro = $this->Content_model->screening_mikro_rows($data['memberdata']['Id_pengguna']);
		
		//echo 'belum lengkap';
		$data['pages']    = 'v_form_pinjaman_agri';
		
		$this->load->view('template', $data);
	}

	function submit_p_agri()
	{
		$uid = htmlentities(strip_tags($_SESSION['_bkduser_']));

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

				$productID    = '5';
				$tenor = trim($post['product']);


				$member_data     = $this->Member_model->get_member_byid($uid);
				$stored_password = $member_data['mum_password'];

				$user_data = $this->Content_model->get_user($uid); // get data from table user
				$produk = $this->Content_model->get_produk($productID);
				$min_pinjam = 2000000;
				$max_pinjam = $produk['Max_loan'];
				if ( (int)$total_pinjam < $min_pinjam ) {
					$ret = array('error'=> '1', 'message'=>'Pinjaman tidak boleh dibawah Rp '.number_format($min_pinjam));
				}
				if ( (int)$total_pinjam > $max_pinjam ) {
					$ret = array('error'=> '1', 'message'=>'Pinjaman tidak boleh diatas Rp '.number_format($max_pinjam));
					
				}else if ( $password == '' OR strlen($password) < 6 ) {
					$ret = array('error'=> '1', 'message'=>'Password minimal 6 karakter.');
					$this->session->set_userdata('message','Password minimal 6 karakter.');
					$this->session->set_userdata('message_type','error');

				}else if (!password_verify(base64_encode(hash('sha256', $password, true)), $stored_password)) {
					$ret = array('error'=> '1', 'message'=>'Password yang Anda masukkan Salah!');
					$this->session->set_userdata('message','Password yang Anda masukkan Salah!');
					$this->session->set_userdata('message_type','error');

				}else if ( trim($post['jumlah_pinjam']) !=''
							&& $tenor != ''
							&& $password != ''
							&& strlen($password) >= 6 
							&& password_verify(base64_encode(hash('sha256', $password, true)), $stored_password) ) 
				{

					if ($post['kelengkapan'] == '0'){
						// jika kelengkapan Agri belum lengkap
					/*	if ($_FILES['cf_file']['tmp_name']  == '' OR $_FILES['progress_report_file']['tmp_name']  == '' ) {
							$ret = array('error'=> '1', 'message'=>'Anda harus Upload Contract Farming, Progress Report!');
							echo json_encode($ret);
							exit();
						}else{*/

							$destination_cf = $this->config->item('member_images_dir'). $member_data['Id_pengguna']."/cf/";

							if($post['cf_file_hidden']!=''){
								if (!is_file($destination_cf)) {
									mkdir_r($destination_cf);
								}	
								if($post['old_cf']!=''){
									if (is_file($destination_cf.$post['old_cf'])){
										unlink($destination_cf.$post['old_cf']);
									}
								}
								$data = $_POST['cf_file_hidden'];
								$splited = explode(',', substr( $data , 5 ) , 2);
								$mime=$splited[0];
							    $data=$splited[1];

							    $mime_split_without_base64=explode(';', $mime,2);
							    $mime_split=explode('/', $mime_split_without_base64[0],2);
							    if(count($mime_split)==2)
							    {
							        $extension=$mime_split[1];
							        if($extension=='jpeg')$extension='jpg';
							        //if($extension=='javascript')$extension='js';
							        //if($extension=='text')$extension='txt';
							        $output_file_with_extension=rand().'.'.$extension;
							    }

								$data = base64_decode($data);
								$file = $destination_cf.$output_file_with_extension;
								$success = file_put_contents($file, $data);
								$u_detail['images_cf_name']  = $output_file_with_extension;
							}


							$destination_progress_report = $this->config->item('member_images_dir'). $member_data['Id_pengguna']."/progress_report/";

							if($post['progress_report_file_hidden']!=''){
								if (!is_file($destination_progress_report)) {
									mkdir_r($destination_progress_report);
								}	
								if($post['old_progress_report']!=''){
									if (is_file($destination_progress_report.$post['old_progress_report'])){
										unlink($destination_progress_report.$post['old_progress_report']);
									}
								}
								$data = $_POST['progress_report_file_hidden'];
								$splited = explode(',', substr( $data , 5 ) , 2);
								$mime=$splited[0];
							    $data=$splited[1];

							    $mime_split_without_base64=explode(';', $mime,2);
							    $mime_split=explode('/', $mime_split_without_base64[0],2);
							    if(count($mime_split)==2)
							    {
							        $extension=$mime_split[1];
							        if($extension=='jpeg')$extension='jpg';
							   
							        $output_file_with_extension=rand().'.'.$extension;
							    }

								$data = base64_decode($data);
								$file = $destination_progress-report.$output_file_with_extension;
								$success = file_put_contents($file, $data);
								$u_detail['images_progress_report_name']  = $output_file_with_extension;
							}
							
							$this->Content_model->update_userdetail($member_data['Id_pengguna'], $u_detail);
						}

						$prefixID    = 'PA-';
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
						//tambahan baru
						$p_pinjam['Amount'] =$total_pinjam;
						$p_pinjam['Jml_permohonan_pinjaman_disetujui'] = $total_pinjam; 
						//batas tambahan baru
						$p_pinjam['User_id']                 = $user_data['Id_pengguna'];
						$p_pinjam['Product_id']              = $productID;
						$p_pinjam['Master_loan_status']      = 'review';
						$p_pinjam['pinjam_member_id']        = $uid;
						$p_pinjam['jml_permohonan_pinjaman_awal'] = $p_pinjam['Jml_permohonan_pinjaman'];
						$p_pinjam['nama_peminjam']           = $member_data['Nama_pengguna'];
						$p_pinjam['loan_term_permohonan']	 = $tenor;
						$pinjamID =$this->Content_model->insert_profil_pinjaman($p_pinjam);

												//tambahan baru

						$this->db->from('record_pinjaman');
						$this->db->where('User_id', $user_data['Id_pengguna']);
						$this->db->like('Flag', 'P', 'BOTH');
						$hasil=$this->db->get()->num_rows();
						// $p_pinjam2['user_id'] =$uid;
					/*	if($hasil>0){
							$hasil+=1;	
						}else{
							$hasil="1";
						}*/

						$p_pinjam3['Flag'] = 'P';
						$p_pinjam3['Master_loan_id'] = $orderID;
						$p_pinjam3['User_id'] = $user_data['Id_pengguna'];
						$p_pinjam3['Amount'] = $total_pinjam;
						$p_pinjam3['Tgl_pinjaman'] = date('Y-m-d H:i:s');
						// $this->db->insert('record_pinjaman', $p_pinjam2);
						$pinjamID3 = $this->Content_model->insert_profil_pinjaman1($p_pinjam3);

						//batas tambahan baru

						if ($pinjamID) {

							$produk = $this->Content_model->get_produk($productID);

							// Log Transaksi
							$inlog['ltp_Id_pengguna']              = $p_pinjam['User_id'];
							$inlog['ltp_Master_loan_id']           = $p_pinjam['Master_loan_id'];
							$inlog['ltp_total_pinjaman']           = $p_pinjam['Jml_permohonan_pinjaman'];
							$inlog['ltp_total_pinjaman_disetujui'] = 0;
							$inlog['ltp_admin_fee']                = 0;
							$inlog['ltp_bunga_pinjaman']           = 0;
							$inlog['ltp_jml_angsuran']             = 0;
							$inlog['ltp_lama_angsuran']            = 0;
							$inlog['ltp_tgl_jatuh_tempo']          = '0000-00-00';
							$inlog['ltp_platform_fee']             = 0;
							$inlog['ltp_lender_fee']               = 0;
							$inlog['ltp_product_title']            = $produk['product_title'];
							$inlog['ltp_product_id']               = $produk['Product_id'];
							$inlog['ltp_product_interest_rate']    = $produk['Interest_rate'];
							$inlog['ltp_product_loan_term']        = $produk['Loan_term'];
							$inlog['ltp_product_platform_rate']    = $produk['Platform_rate'];
							$inlog['ltp_product_loan_organizer']   = $produk['Loan_organizer'];
							$inlog['ltp_product_investor_return']  = $produk['Investor_return'];
							$inlog['ltp_product_revenue_share']    = $produk['Fee_revenue_share'];
							$inlog['ltp_product_secured_loan_fee'] = $produk['Secured_loan_fee'];
							$inlog['ltp_product_interest_rate_type'] = $produk['type_of_interest_rate'];				
							$inlog['ltp_product_pph']                = $produk['PPH'];				
							$inlog['ltp_type_of_business_id']        = $produk['type_of_business_id'];
							$inlog['ltp_loan_organizer_id']          = 1;
							$inlog['ltp_date_created']               = date('Y-m-d H:i:s');
							$this->Content_model->insert_log_transaksi_pinjam($inlog);
						}

						$ret = array('error'=> '0', 'message'=>'Sukses ajukan Pinjaman Agri.');
						$this->session->set_userdata('message','Sukses ajukan Pinjaman Agri');
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
