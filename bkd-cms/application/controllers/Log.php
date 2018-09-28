<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		
		// error_reporting(E_ALL);
	}

	function in()
	{		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|callback_login_check');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_message('required','%s tidak boleh kosong.');
		if ($this->form_validation->run() == FALSE)
		{
			// echo validation_errors();
			$this->session->set_flashdata('errors', validation_errors());
			$this->load->view('vlogin');
		}
		else
		{
			redirect('dashboard');
		}
	}

	function out()
	{
		$this->User_model->logout();
	}

	function login_check()
	{
		if ( ! $this->User_model->do_login($this->input->post('username', true),$this->input->post('password', true)))
		{
			$this->form_validation->set_message('login_check', 'Username or password incorrect.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	/*function forgotten()
	{
		$email = htmlentities(strip_tags(trim($this->input->post('email', true))));

		if (!empty($email))
		{
			$get = $this->User_model->get_email_u($email);  // return 1.exist, 0.not exist

			if ($get == 1)
			{
				$a = mt_rand(100000,999999);
				$data['password']  = md5($a);

				$update = $this->User_model->update_cms_user_byemail($data, $email);

				if ($update){
					$this->load->library('email');

					$this->email->from('Admin CMS', 'Admin CMS');
					$this->email->to($email);

					// $this->email->cc('another@another-example.com');
					// $this->email->bcc('them@their-example.com');

					$this->email->subject('Password Reset Login CMS');
					$this->email->message('Dear '.$email. ', \n\n Password Anda telah di Reset menjadi = '.$a. '\n\n Silahkan lakukan penggantian password di CMS.');

					$this->email->send();
				}
			}else{

			}			

			echo $get;
		}
	}*/
}