<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Input_otp extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Member_model');
		$this->load->model('Payment_model');

		//error_reporting(E_ALL);
	}

	function index()
	{
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		//_d($_SESSION);

		$data['referal_url'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'dashboard';

		$email = (isset($_SESSION['_bkd_otp_'])) ? antiInjection($_SESSION['_bkd_otp_']) : '';

		if (trim($email) != '') {
			$member = $this->Member_model->get_member_by($email);

			if ($member['mum_status'] != '0') {
				redirect('login');
				exit();
			}

			$data['top_css']   = '';
			$data['top_js']    = '';
			$data['bottom_js'] = '';

			$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

			$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
			$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
			//$data['bottom_js'] .= add_js('js/firebase-init.js');

			$data['title'] = $this->M_settings->title;
			$data['meta_tag'] = $this->M_settings->meta_tag_noindex('input', 'input otp');

			$post = $this->input->post(NULL, TRUE);
			$data['pages']    = 'v_input_otp';
			$data['postdata'] = $post;

			$redirect_uri = '';
			$data['redirect_uri'] = $redirect_uri;
			$data['member'] = $member;

			if (isset($member['mum_telp'])) {
				$data['kode_negara'] = substr($member['mum_telp'], 0, 3);
				$data['notelp']      = substr($member['mum_telp'], 3);
			}

			$this->load->view('template', $data);
		} else {
			$this->session->set_userdata('message', 'Member was not found!');
			$this->session->set_userdata('message_type', 'error');
			redirect('home');
		}
	}

	// Method to send Get request to url
	private function doCurl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = json_decode(curl_exec($ch), true);
		curl_close($ch);
		return $data;
	}

	function otp_login_success()
	{
		//set no Virtual Account
		$email = (isset($_SESSION['_bkd_otp_'])) ? antiInjection($_SESSION['_bkd_otp_']) : '';

		if (trim($email) != '') {
			$member = $this->Member_model->get_member_by($email);

			if ($member['mum_status'] != '0') {
				redirect('login');
				exit();
			}

			if (isset($_POST['code'])) {
				// Initialize variables
				$app_id = $this->config->item('fb_app_id');
				$secret = $this->config->item('fb_kit_secret');
				$version = 'v1.1';

				// Exchange authorization code for access token
				$token_exchange_url = 'https://graph.accountkit.com/' . $version . '/access_token?' .
					'grant_type=authorization_code' .
					'&code=' . $_POST['code'] .
					"&access_token=AA|$app_id|$secret";
				$data = $this->doCurl($token_exchange_url);
				$user_id = $data['id'];
				$user_access_token = $data['access_token'];
				$refresh_interval = $data['token_refresh_interval_sec'];

				$appsecret_proof = hash_hmac('sha256', $user_access_token, $secret);

				// Get Account Kit information
				$me_endpoint_url = 'https://graph.accountkit.com/' . $version . '/me?' .
					'access_token=' . $user_access_token . '&appsecret_proof=' . $appsecret_proof;
				$data = $this->doCurl($me_endpoint_url);
				$phone = isset($data['phone']) ? $data['phone']['number'] : '';
				$email = isset($data['email']) ? $data['email']['address'] : '';

				// no telp terverifikasi sama dengan yang sudah didaftarkan
				if (strcmp($member['mum_telp'], $phone) == 0) {
					$this->Member_model->activate_member_byid($member['id_mod_user_member'], 99); // update status ke 99 apabila nomor telepon sudah terverifikasi

					//set no Virtual Account
					$banks = $this->Payment_model->get_va_banks();
					foreach($banks as $bank){
						$phone_no = $member['mum_telp'];
						if(substr($phone_no, 0, 3)=='+62'){
							$phone_no = '0'.substr($phone_no, 3);
						}
						if(substr($phone_no, 0, 2)=='62'){
							$phone_no = '0'.substr($phone_no, 2);
						}
						$current_va = $this->Member_model->get_virtual_account($member['id_mod_user_member'],$bank['bank_id']);
						if(count($current_va) == 0){
							$this->Member_model->insert_virtual_account_no($member['id_mod_user_member'],$phone_no,$bank['bank_id'],'BKDana ' . $member['mum_fullname']);
						}

					}
	
					redirect('otp-login');
				}
				// kalu no telp terverifikasi tidak sama
				else {
					$this->session->set_userdata('message_login', 'Nomor handphone yang terverifikasi tidak sama');
					redirect('input-otp');
					exit();
				}
			}
		}
	}
}
