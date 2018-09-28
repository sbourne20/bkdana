<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');

		//error_reporting(E_ALL);
	}

	public function index()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com', 'login');

		$data['pages']    = 'v_login';
		$this->load->view('template', $data);
	}

	function submit_login()
	{
		if ($_SERVER["REQUEST_METHOD"] == "POST" && $this->input->server('HTTP_REFERER') == site_url('login') ) {

			$post = $this->input->post(NULL, TRUE);

			$email    = trim($post['email']);
			$password = trim($post['password']);

			if ($email == '' OR $password == '') {
				$this->session->set_userdata('message_login','Isilah Email dan Password login Anda');
				redirect('login'); 
				exit();
			}

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->session->set_userdata('message_login','Invalid Email format!');
				redirect('login'); 
				exit();
			}

			if (!empty($email) && !empty($post['password']) && (strlen($post['password'])>=6) ) {

				$getdata = $this->Member_model->do_login_byemail(htmlentities(strip_tags($email)));

				if (is_array($getdata) && count($getdata) > 0){
					$stored_password = $getdata['mum_password'];

					if (password_verify(base64_encode(hash('sha256', $password, true)), $stored_password)) {

						// check sudah aktif atau belum
						if ($getdata['mum_status'] == 0)
						{
							// belum aktif, redirect ke login OTP
							$_SESSION['_bkd_otp_'] = $email;
							redirect('input-otp');
							exit();
						}else{

							if ($getdata['id_mod_user_member'] && $getdata['mum_fullname']) {
								$data = array();
								$data['_bkdlog_']   = 1;	// login status
								$data['_bkdmail_']  = $getdata['mum_email'];
								$data['_bkduser_']  = $getdata['id_mod_user_member'];
								$data['_bkdname_']  = $getdata['mum_fullname'];
								$data['_bkdtype_']  = $getdata['mum_type'];
								$this->session->set_userdata($data);

								// update session cookies utk single sign on in one device only
								$cookie_value = md5($_SERVER['HTTP_USER_AGENT']);
								setcookie("_bkdsckie_", $cookie_value, time()+3600*24*365); // 1 year

								$upsession['mum_session']    = $cookie_value;
								$upsession['mum_last_login'] = date('Y-m-d H:i:s');
								$this->Member_model->update_member_byid($getdata['id_mod_user_member'], $upsession);
							}
							
							$redir_array = array(site_url('cart'), site_url('member/order_detail'));	// allowed referer url
							if (in_array($post['referer'], $redir_array) OR strpos($redir_array, $post['referer']))
							{
								redirect($post['referer']);
							}else{
								redirect('dashboard');
							}
						}

					} else {
					    //echo 'Failure :(';
					    $this->session->set_userdata('message_login','Username dan atau Password Anda salah!');
						redirect('login');
					}
				
				}else{
					// data not exist in DB
					$this->session->set_userdata('message_login','Username dan atau Password Anda salah!.');
					redirect('login'); 
				}

			}else{
				// input form empty
				$this->session->set_userdata('message_login','Username dan Password harus diisi.');
				redirect('login'); 
			}
		}else{
			redirect('home');
		}
	}

	function logoff()
	{
		// delete a cookie safely
		setcookie ("_bkdlog_", "", 1);
		setcookie ("_bkdlog_", false);
		unset($_COOKIE["_bkdlog_"]);
		unset($_SESSION["_bkdlog_"]);

		setcookie ("_bkdmail_", "", 1);
		setcookie ("_bkdmail_", false);
		unset($_COOKIE["_bkdmail_"]);
		unset($_SESSION["_bkdmail_"]);

		setcookie ("_bkduser_", "", 1);
		setcookie ("_bkduser_", false);
		unset($_COOKIE["_bkduser_"]);
		unset($_SESSION["_bkduser_"]);

		setcookie ("_bkdname_", "", 1);
		setcookie ("_bkdname_", false);
		unset($_COOKIE["_bkdname_"]);
		unset($_SESSION["_bkdname_"]);

		setcookie ("_bkdtype_", "", 1);
		setcookie ("_bkdtype_", false);
		unset($_COOKIE["_bkdtype_"]);
		unset($_SESSION["_bkdtype_"]);

		$this->session->sess_destroy();
		redirect('home');
	}

	function login_otp()
	{
		// ---- Redirect dari halaman OTP -----
		$email = (isset($_SESSION['_bkd_otp_']))? antiInjection(trim($_SESSION['_bkd_otp_'])) : '';

		if ($email !='' && !empty($email))
		{
			$getdata = $this->Member_model->get_member_by($email);

			if (is_array($getdata) && count($getdata) > 0 && $getdata['id_mod_user_member'] && $getdata['mum_fullname']) {
				
				// activate member

				if ($getdata['mum_type'] == 1){
					$active_status = 1; // peminjam
				}else{
					$active_status = 2;	// pendana. aktif tapi tidak bisa transaksi karena harus di approve via cms.
				}
				$this->Member_model->activate_member_byid($getdata['id_mod_user_member'], $active_status);

				$data = array();
				$data['_bkdlog_']   = 1;	// login status
				$data['_bkdmail_']  = $getdata['mum_email'];
				$data['_bkduser_']  = $getdata['id_mod_user_member'];
				$data['_bkdname_']  = $getdata['mum_fullname'];
				$data['_bkdtype_']  = $getdata['mum_type'];
				$this->session->set_userdata($data);

				unset($_SESSION["_bkd_otp_"]);

				$ret=TRUE;
			}else{
				$ret=FALSE;
			}
		}else{
			$ret=FALSE;
		}

		if ($ret)
		{
			//echo 'ke dashboard';
			redirect('dashboard');
		}else{
			//echo 'ke home';
			redirect('home');
		}
	}
}