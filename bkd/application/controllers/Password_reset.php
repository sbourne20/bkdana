<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Password_reset extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		//error_reporting(E_ALL);
	}
	
	public function index()
	{
		//echo (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."\n";
		
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['top_css'] .= add_css('js/bootstrap-datepicker/css/bootstrap-datepicker.css');
		$data['top_css'] .= add_css('js/alertify/css/alertify.min.css');
		$data['top_css'] .= add_css('js/alertify/css/themes/default.min.css');
		//$data['top_js'] .= add_js('js/firebase-init.js');

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
		$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com', 'website bkdana.com');

		$data['pages']    = 'v_password_reset';
		$this->load->view('template', $data);
	}

	function send_link_reset()
	{

		if ($this->input->server('REQUEST_METHOD') == 'POST') 
		{
			$this->load->library('encryption');

			$email = antiInjection(trim($this->input->post('email', TRUE)));
			$email = filter_var($email, FILTER_SANITIZE_EMAIL);
			
			if (empty($email) OR $email == ''){
				$this->session->set_userdata('message_reset', 'isilah dengan Email Anda yang terdaftar.');
				redirect('password_reset');
				exit();

			}else if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {

			    $getuser = $this->Member_model->do_login_byemail($email);
			    $count = count($getuser);

			    if (is_array($getuser) && $count > 0 && $getuser['id_mod_user_member']!='' ){
			    	// SEND EMAIL RESET

				    $uid = $getuser['id_mod_user_member'];

			    	$Randomkey = RandomString(10);
                	$datareset = array(
								'reset_member_id' => $uid,
								'reset_code'      => $Randomkey,
								'reset_status'    => 0,
								'reset_created'   => date('Y-m-d H:i:s')
                                );
                	$resetcode = $this->Member_model->insert_resetcode($datareset); 

                	// --- Encrypt the data
                	$plain_text = 'key='.$Randomkey.'&id='.$uid;
					$encrypted  = $this->encryption->encrypt($plain_text);
					
                	$html_content = '
                        Hai '.$email.',<br><br>

                            Seseorang telah meminta kami untuk mereset password Anda di website BKDana.com.<br> 
                            Jika Bukan Anda, maka abaikan Email ini.<br><br> 
                            Untuk melakukan <em>reset password</em> Anda, silakan mengunjungi alamat dibawah ini:<br><br> 
                                                            
                            <a href="'.site_url().'page-reset-password/?link='.urlencode($encrypted).'" target="_blank">'.site_url().'page-reset-password/?link='.urlencode($encrypted).'</a><br><br>
                                                            
                            Jika link di atas tidak bisa diklik, <em>Copy</em> lalu <em>Paste</em> link tersebut ke browser Anda.<br><br>
                                                            

                            Salam,<br>
                            BKDana.com
		        			';

					if ($resetcode){
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
						$mail->Subject     = 'Reset Password';
						$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
						$mail->MsgHTML($html_content);	
						$mail->SMTPDebug   = 0;
                        if(!$mail->Send()) {
	                        //echo $mail->ErrorInfo;exit;
		                	$result = 'failed';		

                        }else{
			                $result = 'success';		                		               	
                        }	

                        redirect('send-password-message?show='.$result);
                        exit();
					}
				
				}else{
					
					$this->session->set_userdata('message_reset', 'Email Anda tidak terdaftar.');
					redirect('password_reset');
					exit();
				}
			}else{
				$this->session->set_userdata('message_reset', 'Isilah dengan format Email yang benar.');
				redirect('password_reset');
				exit();
			}

		}
	}

	function send_password_message()
	{
		$data['msg_type'] = $this->input->get('show', true);

		$data['pagetitle'] = '';
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com', 'website bkdana.com');

		$data['pages']    = 'v_password_message_sent';
		$this->load->view('template', $data);
	}

	function view_reset_pass()
	{
		// do reset pasword

		$this->load->library('encryption');

		$query_string = isset($_SERVER['QUERY_STRING'])? $this->input->server('QUERY_STRING', true) : '' ;

	    if($query_string != '' && strlen($query_string) > 2){

	     	$encrypted_data = $this->input->get('link', true);

	     	if (isset($encrypted_data)) {
			
				$decypted_data = $this->encryption->decrypt($encrypted_data);

				//echo $decypted_data;
				parse_str($decypted_data, $query_array);

				$resetcode = strip_tags($query_array['key']);
				$uid       = strip_tags($query_array['id']);

				$check_code = $this->Member_model->check_resetcode($resetcode, $uid);

				if (count($check_code) > 0 && $check_code['reset_code'] == $resetcode)  {

					$get_member = $this->Member_model->get_member_byid_less($uid);
					
					if (count($get_member) > 0 ) {
						// View page to insert new password

						$data['pagetitle'] = '';
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

						$data['title'] = $this->M_settings->title;
						$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com', 'website bkdana.com');

						$data['ref'] = $encrypted_data;
						$data['pages']    = 'v_new_password';
						$this->load->view('template', $data);

					}else{
						//echo 'member tidak ditemukan'; exit;
						redirect('reset-password-failed');
			            exit();
					}
				}else{
					// echo 'reset code tidak ditemukan atau sudah pernah direset';exit();
					redirect('reset-password-expired');
		            exit();
				}

			}else{
				//echo 'tidak ada parameter GET'; exit;
				redirect('reset-password-failed');
	            exit();
			}
	        
	    }else{
	    	//echo 'tidak ada Query String'; exit;
	    	redirect('reset-password-failed');
            exit();
	    }
	}

	function submit_new_password()
	{
		// ------- Execute reset password --------
		// -- set status resetcode = 1 -> table member_resetcode
		// -- update password member -> table member

		if ($this->input->server('HTTP_REFERER', TRUE)) {

			$this->load->library('encryption');
			
			$post = $this->input->post(NULL, TRUE);

			$encrypted_data  = trim($post['ref']);
			$newpassword     = trim($post['password']);
			$confirmpassword = trim($post['confirm_password']);

			if ($newpassword == '' OR $confirmpassword == '' OR $newpassword != $confirmpassword)
			{
				$this->session->set_userdata('message_reset', 'New Password dan Re-type New Password tidak sama.');
				redirect('page-reset-password/?link='.$encrypted_data);
				exit();
			}else if (strlen($newpassword) < 6)
			{
				$this->session->set_userdata('message_reset', 'isi Password minimal 6 karakter.');
				redirect('page-reset-password/?link='.$encrypted_data);
				exit();
			}

			if (antiInjection($newpassword) == antiInjection($confirmpassword) && strlen($newpassword) >= 6 && $encrypted_data !='' ) {
				
					$decypted_data = $this->encryption->decrypt($encrypted_data);
					parse_str($decypted_data, $query_array);

					$resetcode = antiInjection($query_array['key']);
					$userID    = antiInjection($query_array['id']);

					if (!empty($userID))
					{
						$update_codestatus = $this->Member_model->update_statusreset($resetcode, $userID);

						if ($update_codestatus) {

							$newpass = password_hash(base64_encode(hash('sha256', ($newpassword), true)), PASSWORD_DEFAULT);
				            $updated = $this->Member_model->update_password_member($newpass, $userID);

				            if ($updated) {
				            	redirect('reset-password-success');
					            exit();
				            }else{
				            	redirect('reset-password-failed');
					            exit();
				            }
			        }

					}else{
						// expired atau sudah pernah reset
						redirect('reset-password-expired');
			            exit();
					}
				

			}else{
				redirect('reset-password-failed');
	            exit();
			}
		}
	}

	function final_message($msg='')
	{
		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com', 'website bkdana.com');

		$data['pagetitle'] = '';
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$page = antiInjection($this->uri->segment(1));

		switch ($page) {
			case 'reset-password-failed':
				$msg_type   = 'error';
				$msg_return = 'Email Anda tidak terdaftar di sistem kami.';
				break;
			case 'reset-password-expired':
				$msg_type   = 'error';
				$msg_return = 'Sesi reset password Anda sudah <em>expired</em>.';
				break;
			case 'reset-password-success':
				$msg_type   = 'success';
				$msg_return = 'Reset Password berhasil. Silahkan login.';
				break;
			
			default:
				$msg_type   = 'error';
				$msg_return = 'Terjadi kesalahan saat reset password.';
				break;
		}
		$data['message']   = $msg_return;
		$data['msg_type'] = $msg_type;

		$data['pages']    = 'v_password_reset_result';
		$this->load->view('template', $data);
	}
}