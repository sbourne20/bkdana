<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aktivasi extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		$this->load->library('encryption');
		//error_reporting(E_ALL);
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

				//if ($affected) {
					$set_otp = $this->Member_model->set_cookies_otp($email); // set cookies for OTP login controller login/login_otp
					redirect('input-otp');
					exit();
				//}
			}
		}
		// mobile no
		else if($email != ''){
			$member = $this->Member_model->get_member_by($email);
			
			if ( count($member) > 0 && isset($member['id_mod_user_member']) )
			{
				if ($member['mum_type'] == 1){
					$active_status = 1; // peminjam
				}else{
					$active_status = 2;	// pendana. aktif tapi tidak bisa transaksi karena harus di approve via cms.
				}
				
				$affected = $this->Member_model->verify_member_byid($member['id_mod_user_member']);

				//if ($affected) {
					$set_otp = $this->Member_model->set_cookies_otp($email); // set cookies for OTP login controller login/login_otp
					redirect('input-otp');
					exit();
				//}
			}
		}

		redirect('login');

	}

	function resend_email()
	{
		$email = urldecode(trim($this->input->get('email', TRUE)));
		$ciphertext = urlencode($this->encryption->encrypt($email));
		$url = site_url('aktivasi?t='.$ciphertext);
		$html_content = '
        Hai '.$email.',<br><br>

            Anda telah terdaftar di BKDana.com.<br>
            Klik link berikut untuk aktivasi Akun Anda:
            <br><br>
            <a href="'.$url.'">'.$url.'</a>
            
            <br><br>

            <span style="color:#858C93;">
            	Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke Email ini.
            	<br><br>

            	&copy; BKDana.com, '.date("Y").'. All rights reserved.
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
		$mail->Subject     = 'Aktivasi Akun BKDana.com';
		$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($html_content);	
		$mail->SMTPDebug   = 0;
        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }

        redirect('message/registrasi_success');
	
	}
}