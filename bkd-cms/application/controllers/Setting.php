<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends CI_Controller {

	/*  Static Page  */

	function __construct()
	{
		parent::__construct();

		$this->load->model('Setting_model');
		$this->load->model('User_model');
		
		$this->User_model->has_login();
	}

	function index()
	{
		// error_reporting(E_ALL);
		$output['PAGE_TITLE'] = 'Setting HOME PAGE';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';

		$mainData['top_css']  .= add_css("plugins/fileinput/fileinput.min.css");

		$mainData['top_js'] .= add_js("plugins/fileinput/fileinput.min.js");
		$mainData['top_js'] .= add_js('plugins/ckeditor/ckeditor.js');
		$mainData['top_js'] .= add_js("plugins/friendurl/jquery.friendurl.min.js");

		$mainData['bottom_js'] .= add_js('js/select2-data.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$output['EDIT'] = $this->Setting_model->get_setting_byid(1);

		// use this js for this page
		//$mainData['bottom_js'] .= add_js('js/data/pages.js');

		$mainData['mainContent']  = $this->load->view('setting/vhome', $output,true);
		$this->load->view('vbase',$mainData);
	}

	function edit()
	{
		// error_reporting(E_ALL);
		$output['PAGE_TITLE'] = 'Setting HOME PAGE';

		$ID = 1;

		$this->validation();
		if ($this->form_validation->run() == FALSE)
			{

			$mainData['top_css']   = '';
			$mainData['top_js']    = '';
			$mainData['bottom_js'] = '';

			$mainData['top_css']  .= add_css("plugins/fileinput/fileinput.min.css");

			$mainData['top_js'] .= add_js("plugins/fileinput/fileinput.min.js");
			$mainData['top_js'] .= add_js('plugins/ckeditor/ckeditor.js');
			$mainData['top_js'] .= add_js("plugins/friendurl/jquery.friendurl.min.js");

			$mainData['bottom_js'] .= add_js('js/select2-data.js');
			$mainData['bottom_js'] .= add_js('js/global.js');

			$output['EDIT'] = $this->Setting_model->get_setting_byid($ID);

			$mainData['mainContent']  = $this->load->view('setting/vhome_form', $output,true);
			$this->load->view('vbase',$mainData);
		}else{
			$post = $this->input->post();

			$updata['home_1'] = htmlentities(trim($post['home_1']));
			$updata['home_2'] = htmlentities(trim($post['home_2']));
			$updata['home_3'] = htmlentities(trim($post['home_3']));
			$updata['home_4'] = htmlentities(trim($post['home_4']));
			$updata['home_5'] = htmlentities(trim($post['home_5']));
			$updata['home_6'] = htmlentities(trim($post['home_6']));
			$updated = $this->Setting_model->update_($updata, $ID);

			if ($updated)
			{
				$this->session->set_userdata('message','Data berhasil di-update.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','Tidak ada yang diubah.');
				$this->session->set_userdata('message_type','success');
			}

			redirect('setting');
		}
	}

	function edit_home_middle()
	{
		// error_reporting(E_ALL);
		$output['PAGE_TITLE'] = 'Setting HOME PAGE';

		$ID = 1;

		$this->validation();
		if ($this->form_validation->run() == FALSE)
			{

			$mainData['top_css']   = '';
			$mainData['top_js']    = '';
			$mainData['bottom_js'] = '';

			$mainData['top_css']  .= add_css("plugins/fileinput/fileinput.min.css");

			$mainData['top_js'] .= add_js("plugins/fileinput/fileinput.min.js");
			$mainData['top_js'] .= add_js('plugins/ckeditor/ckeditor.js');
			$mainData['top_js'] .= add_js("plugins/friendurl/jquery.friendurl.min.js");

			$mainData['bottom_js'] .= add_js('js/select2-data.js');
			$mainData['bottom_js'] .= add_js('js/global.js');

			$output['EDIT'] = $this->Setting_model->get_setting_byid($ID);

			$mainData['mainContent']  = $this->load->view('setting/vhome_form_middle', $output,true);
			$this->load->view('vbase',$mainData);
		}else{
			$post = $this->input->post();

			$updata['home_7'] = htmlentities(trim($post['home_1']));			
			$updated = $this->Setting_model->update_($updata, $ID);

			if ($updated)
			{
				$this->session->set_userdata('message','Data berhasil di-update.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','Tidak ada yang diubah.');
				$this->session->set_userdata('message_type','success');
			}

			redirect('setting');
		}
	}

	function edit_home_transaksi()
	{
		// error_reporting(E_ALL);
		$output['PAGE_TITLE'] = 'Setting';

		$ID = 1;

		$this->validation();
		if ($this->form_validation->run() == FALSE)
			{

			$mainData['top_css']   = '';
			$mainData['top_js']    = '';
			$mainData['bottom_js'] = '';

			$mainData['top_css']  .= add_css("plugins/fileinput/fileinput.min.css");

			$mainData['top_js'] .= add_js("plugins/fileinput/fileinput.min.js");
			$mainData['top_js'] .= add_js('plugins/ckeditor/ckeditor.js');
			$mainData['top_js'] .= add_js("plugins/friendurl/jquery.friendurl.min.js");

			$mainData['bottom_js'] .= add_js('js/select2-data.js');
			$mainData['bottom_js'] .= add_js('js/global.js');

			$output['EDIT'] = $this->Setting_model->get_setting_byid($ID);

			$mainData['mainContent']  = $this->load->view('setting/vhome_form_transaksi', $output,true);
			$this->load->view('vbase',$mainData);
		}else{
			$post = $this->input->post();

			$updata['home_8'] = htmlentities(trim($post['home_1']));			
			$updata['home_9'] = htmlentities(trim($post['home_2']));			
			$updata['home_10'] = htmlentities(trim($post['home_3']));			
			$updata['home_11'] = htmlentities(trim($post['home_4']));			
			$updata['home_12'] = htmlentities(trim($post['home_5']));			
			$updata['home_13'] = htmlentities(trim($post['home_6']));			
			$updated = $this->Setting_model->update_($updata, $ID);

			if ($updated)
			{
				$this->session->set_userdata('message','Data berhasil di-update.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','Tidak ada yang diubah.');
				$this->session->set_userdata('message_type','success');
			}

			redirect('setting');
		}
	}

	function edit_home_bottom()
	{
		// error_reporting(E_ALL);
		$output['PAGE_TITLE'] = 'Setting';

		$ID = 1;

		$this->validation();
		if ($this->form_validation->run() == FALSE)
			{

			$mainData['top_css']   = '';
			$mainData['top_js']    = '';
			$mainData['bottom_js'] = '';

			$mainData['top_css']  .= add_css("plugins/fileinput/fileinput.min.css");

			$mainData['top_js'] .= add_js("plugins/fileinput/fileinput.min.js");
			$mainData['top_js'] .= add_js('plugins/ckeditor/ckeditor.js');
			$mainData['top_js'] .= add_js("plugins/friendurl/jquery.friendurl.min.js");

			$mainData['bottom_js'] .= add_js('js/select2-data.js');
			$mainData['bottom_js'] .= add_js('js/global.js');

			$output['EDIT'] = $this->Setting_model->get_setting_byid($ID);

			$mainData['mainContent']  = $this->load->view('setting/vhome_form_bottom', $output,true);
			$this->load->view('vbase',$mainData);
		}else{
			$post = $this->input->post();

			$updata['home_14'] = htmlentities(trim($post['home_1']));			
			$updata['home_15'] = htmlentities(trim($post['home_2']));	
			$updated = $this->Setting_model->update_($updata, $ID);

			if ($updated)
			{
				$this->session->set_userdata('message','Data berhasil di-update.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','Tidak ada yang diubah.');
				$this->session->set_userdata('message_type','success');
			}
			redirect('setting');
		}
	}

	function validation()
	{
		// error_reporting(E_ALL);
		$this->form_validation->set_rules('home_1', 'Title', 'trim|required');
		$this->form_validation->set_message('required', '%s harus diisi.');
	}
}