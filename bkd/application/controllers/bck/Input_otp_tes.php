<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Input_otp_tes extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		
		error_reporting(E_ALL);
	}

	function index()
	{
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Last-Modified: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Cache-Control: post-check=0, pre-check=0", false); 
		header("Pragma: no-cache"); 
		//_d($_SESSION);

		
			$data['referal_url'] = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER'] : 'dashboard';
			
			//$email = (isset($_SESSION['_bkd_otp_']))? antiInjection($_SESSION['_bkd_otp_']) : '';
			$email = 'iriawan.maarif@gmail.com';

			if (trim($email) != '')
			{
				$member = $this->Member_model->get_member_by($email);

				$data['top_css']   = '';
				$data['top_js']    = '';
				$data['bottom_js'] = '';


				$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
				
				$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
				$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
				//$data['bottom_js'] .= add_js('js/firebase-init-tes.js');

				$data['title'] = $this->M_settings->title;
				$data['meta_tag'] = $this->M_settings->meta_tag_noindex('input', 'input otp');

				$post = $this->input->post(NULL, TRUE);
				$data['pages']    = 'v_input_otp';
				$data['postdata'] = $post;

				$redirect_uri = '';
				$data['redirect_uri'] = $redirect_uri;
				$data['member'] = $member;

				if (isset($member['mum_telp']))
                {
                    $data['kode_negara'] = substr($member['mum_telp'], 0, 3);
                    $data['notelp']        = substr($member['mum_telp'], 3);
                    
                }
				$this->load->view('template', $data);
			}else{
				$this->session->set_userdata('message','Member was not found!');
				$this->session->set_userdata('message_type','error');
				redirect('home');
			}
	}
}