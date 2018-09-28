<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');

		// error_reporting(E_ALL);
	}

	function index()
	{}

	// ================= User Management ===============
	function management()
	{
		$this->User_model->has_login();
		
		$output['PAGE_TITLE'] = 'USER CMS';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		// use this js for this page
		$mainData['bottom_js'] .= add_js('js/data/user.js');

		$mainData['mainContent'] = $this->load->view('user/vmanagement', $output, TRUE);

		$this->load->view('vbase', $mainData);
	}

	public function json_management()
	{		
		$result=$this->User_model->get_cms_user();
		print_r($result);
	}

	public function json_group()
	{		
		$result=$this->User_model->get_user_group_dt();
		print_r($result);
	}

	public function json_log()
	{		
		$result=$this->User_model->get_user_log();
		print_r($result);
	}

	public function add()
	{
		$this->User_model->has_login();

		$a['CU'] = $this->User_model->current_user();
		$mainData['CU']       = $a['CU'];
		$mainData['add_mode'] = 1; // sbg tanda add new
		$mainData['EDIT']     = NULL;

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{
			$a['top_css']   ="";
			$a['top_js']    ="";
			$a['bottom_js'] ="";
			
			// init data utk select2
			//$a['bottom_js'] .= add_js('js/select2-data.js');
			$a['bottom_js'] .= add_js('js/global.js');

			$mainData['usergroup'] = $this->User_model->get_allgroup();
			
			$a['mainContent'] = $this->load->view('user/vuser_form', $mainData, TRUE);

			$this->load->view('vbase', $a);
		}else{
			$post = $this->input->post(NULL, TRUE);

			//_d($post);exit();

			if ( trim($post['username']) == '' OR strlen($post['username']) < 4 ){
				$this->session->set_userdata('message','Username Minimum 4 characters.');
				$this->session->set_userdata('message_type','error');
				redirect('user/add');
				exit;
			}

			if ( trim($post['password']) == '' OR (strlen($post['password']) < 6) ){
				$this->session->set_userdata('message','Fill in Password Minimum 6 characters.');
				$this->session->set_userdata('message_type','error');
				redirect('user/add');
				exit;
			}

			if ($this->input->post('password') != $this->input->post('password2'))
			{
				$this->session->set_userdata('message','Password and Re-type Password didn\'t match.');
				$this->session->set_userdata('message_type','error');
				redirect('user/add');
				exit;
			}

			if (!empty($post['fullname']) && !empty($post['username']) && !empty($post['privilege']) && (strlen($post['password'])>=6) && $post['password'] == $post['password2']) {

				$is_exist = $this->User_model->get_username(trim($post['username']));

				if ( count($is_exist)>0 && !empty($is_exist['id_system_user']) ) {
					$this->session->set_userdata('message','The <strong>'.$post['username'].'</strong> is already used. Choose another username.');
					$this->session->set_userdata('message_type','error');
					redirect('user/add');
					exit();
				}

				$stored = password_hash(base64_encode(hash('sha256', (trim($post['password'])), true)), PASSWORD_DEFAULT);

				$indata['id_group'] = $post['privilege'];
				$indata['username'] = trim($post['username']);
				$indata['password'] = $stored;
				$indata['fullname'] = trim($post['fullname']);
				$indata['email']    = trim($post['email']);
				$indata['phone']    = trim($post['telpon']);
				$indata['active']   = trim($post['status']);
				$indata['created '] = date('Y-m-d H:i:s');

				$insertID = $this->User_model->insert_new_user($indata);

				if ($insertID) {
					$this->session->set_userdata('message','Successfully add new user.');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','Failed to insert.');
					$this->session->set_userdata('message_type','error');
				}

				redirect('user/management');
			}else{
				$this->session->set_userdata('message','Please fill in all column');
				$this->session->set_userdata('message_type','error');
				redirect('user/add');
			}
		}
	}

	function delete()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);

		if (trim($id) != '' && !empty($id))
		{
			$del = $this->User_model->delete_cms_user($id);
			if($del){
				$this->session->set_userdata('message','Data has been deleted.');
				$this->session->set_userdata('message_type','success');
			}
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('user/management');
	}

	function edit()
	{
		$this->User_model->has_login();

		$ID = $this->uri->segment(3);
		$a['CU'] = $this->User_model->current_user();
		$mainData['add_mode']  = 2; // sbg tanda edit
		$mainData['CU']        = $a['CU'];
		$mainData['EDIT']      = $this->User_model->get_cmsuser_byid($ID);
		$mainData['usergroup'] = $this->User_model->get_allgroup();

		$this->validation2();
		if ($this->form_validation->run() == FALSE)
		{
			$a['top_css']  ="";
			$a['top_js']   ="";
			$a['bottom_js'] ="";
			
			// init data utk select2
			$a['bottom_js'] .= add_js('js/data/select2-data.js');
			$a['bottom_js'] .= add_js('js/data/global.js');
			
			$a['mainContent'] = $this->load->view('user/vuser_form_edit', $mainData, TRUE);

			$this->load->view('vbase', $a);
		}else{
			$post = $this->input->post(null, true);

			$ID = $post['uid'];

			$data['id_group'] = $post['privilege'];
			$data['username'] = $post['username'];
			$data['fullname'] = $post['fullname'];
			$data['email']    = $post['email'];
			$data['phone']    = $post['telpon'];
			$data['active']   = $post['status'];

			$update = $this->User_model->update_cms_user($data, $ID);
			if ($update){
				$this->session->set_userdata('message','Data updated.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','No Update.');
				$this->session->set_userdata('message_type','success');
			}

		 	redirect('user/management');
		}
	}


	function validation()
	{
		//error_reporting(E_ALL);
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('fullname', 'fullname', 'trim|required');

		$this->form_validation->set_message('required', '%s harus diisi.');
	}

	function validation2()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('fullname', 'fullname', 'trim|required');
		$this->form_validation->set_rules('privilege', 'Privilege', 'trim|required');

		$this->form_validation->set_message('required', '%s harus diisi.');
	}

	//  ============================= USER GROUP ===============================

	function group()
	{
		// -------- Display user Group list ---------
		$this->User_model->has_login();
		
		$output['PAGE_TITLE'] = 'USER GROUP';


		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';

		// use this js for this page
		$mainData['bottom_js'] .= add_js('js/data/user_group.js');

		$mainData['mainContent'] = $this->load->view('user/vgroup', $output, TRUE);

		$this->load->view('vbase', $mainData);
	}

	function add_group()
	{
		$this->User_model->has_login();

		$mainData['add_mode'] = 1; // sbg tanda add new
		$mainData['EDIT']     = NULL;

		$a['top_css']   ="";
		$a['top_js']    ="";
		$a['bottom_js'] ="";
		
		$a['bottom_js'] .= add_js('js/global.js');
		
		$a['mainContent'] = $this->load->view('user/vgroup_form', $mainData, TRUE);

		$this->load->view('vbase', $a);
	}

	function setting_role()
	{
		$this->User_model->has_login();

		if($_SERVER['REQUEST_METHOD']!='POST'){
			$ID                   = $this->uri->segment(3);
			$mainData['EDIT']     = $this->User_model->get_role_access($ID);
			$mainData['group']    = $this->User_model->get_group_by($ID);

			$a['top_css']   ="";
			$a['top_js']    ="";
			$a['bottom_js'] ="";
			
			$a['bottom_js'] .= add_js('js/data/global.js');
			
			$a['mainContent'] = $this->load->view('user/vsetting_role_group', $mainData, TRUE);

			$this->load->view('vbase', $a);
		}else{
			// ----- SUBMIT role access ------
			$post   = $this->input->post(null, true);
			$ID     = $post['idgroup'];
			$access = $post['access'];

			// Delete semua role dg id=$ID
			// lalu insert baru
			$this->User_model->delete_role_access($ID);
			
			if($access AND is_array($access)){
				foreach($access as $ac){
					$data = array();
					$data['priv_id_group'] = $ID;
					$data['access']        = $ac;
					$this->User_model->insert_access_roles($data);
				}
			}
			
			$this->session->set_userdata('message','Data has been Saved.');
			$this->session->set_userdata('message_type','success');
			redirect('user/group'); 
		}
	}

	function edit_group()
	{
		$this->User_model->has_login();

		$mainData['add_mode'] = 2; // sbg tanda edit
		$ID                   = $this->uri->segment(3);
		$mainData['EDIT']     = $this->User_model->get_group_by($ID);

		$a['top_css']   ="";
		$a['top_js']    ="";
		$a['bottom_js'] ="";
		
		$a['bottom_js'] .= add_js('js/data/global.js');
		
		$a['mainContent'] = $this->load->view('user/vgroup_form', $mainData, TRUE);

		$this->load->view('vbase', $a);
	}

	function submit_group()
	{
		$this->User_model->has_login();
		$post = $this->input->post(null, true);

		if ($post['add_mode'] == 1)
		{
			if (trim($post['gname']) != '') {
				// --- INSERT NEW GROUP ---
				$data['group_name'] = $post['gname'];

				$insertg = $this->User_model->insert_group($data);
				if ($insertg){
					$this->session->set_userdata('message','New Group Inserted.');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','No Insert.');
					$this->session->set_userdata('message_type','error');
				}
			}

		}elseif ($post['add_mode'] == 2){
			// --- UPDATE GROUP ---

			if (trim($post['gname']) != '') {

				$ID = $post['idgroup'];
				$data['group_name'] = $post['gname'];

				$update = $this->User_model->update_group($data, $ID);
				if ($update){
					$this->session->set_userdata('message','The Group has been updated.');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','No Update.');
					$this->session->set_userdata('message_type','success');
				}
			}
		}

	 	redirect('user/group');
	}

	function delete_group()
	{
		$this->User_model->has_login();

		$id = $this->uri->segment(3);
		$del = $this->User_model->delete_user_group($id);
		if($id && $del){

			$this->session->set_userdata('message','Data has been deleted.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('user/group');
	}

	//  ============================= USER LOG ===============================

	function log()
	{
		$this->User_model->has_login();
		
		$output['PAGE_TITLE'] = 'USER LOG';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';

		// use this js for this page
		$mainData['bottom_js'] .= add_js('js/data/user_log.js');

		$mainData['mainContent'] = $this->load->view('user/vlog', $output, TRUE);

		$this->load->view('vbase', $mainData);
	}

	function delete_log()
	{
		$this->User_model->has_login();
		
		$del = $this->User_model->delete_logs();
		if($del){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	// ==================================================

	// show username on nav bar
	function get_username()
	{
		$id = $this->session->userdata('current_user');
		$data = $this->User_model->get_user_by_id($id);
		echo $data->username;
	}

	// =============== Change Password via nav bar ============
	function change_password()
	{
		$this->User_model->has_login();

		$mainData['CU'] = $this->User_model->current_user();

		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('password2', 'Password', 'trim|required');
		$this->form_validation->set_message('required', '%s harus diisi.');

		if ($this->form_validation->run() == FALSE)
		{
			$a['top_css']  ="";
			$a['top_js']   ="";
			$a['bottom_js'] ="";
			
			$a['bottom_js'] .= add_js('js/global.js');
			
			$a['mainContent'] = $this->load->view('user/vchange_password_form', $mainData, TRUE);

			$this->load->view('vbase', $a);
		}else{
			$post = $this->input->post(null, true);
			$ID = $post['uid'];

			if (trim($post['password']) == '' OR strlen($post['password']) < 6 )
			{
				$this->session->set_userdata('message','Password minimum 6 characters.');
				$this->session->set_userdata('message_type','error');
				redirect('user/change_password/'.$ID);
				exit;
			}elseif ($post['password'] != $post['password2'])
			{
				$this->session->set_userdata('message','Password dan Re-type Password didn\'t match.');
				$this->session->set_userdata('message_type','error');
				redirect('user/change_password/'.$ID);
				exit;
			}else{
				$stored = password_hash(base64_encode(hash('sha256', (trim($post['password'])), true)), PASSWORD_DEFAULT);

				$data['password'] = $stored;

				$update = $this->User_model->update_cms_user($data, $ID);
				if ($update){
					$this->session->set_userdata('message','Password Changes successfully.');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','No Update.');
					$this->session->set_userdata('message_type','success');
				}
			}
			
			redirect('dashboard');
		}
	}

	function change_password_user()
	{
		$this->User_model->has_login();

		$mainData['CU'] = $this->User_model->current_user();
		$ID = $this->uri->segment(3);

		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('password2', 'Password', 'trim|required');
		$this->form_validation->set_message('required', '%s harus diisi.');

		if ($this->form_validation->run() == FALSE)
		{
			$a['top_css']  ="";
			$a['top_js']   ="";
			$a['bottom_js'] ="";
			
			$a['bottom_js'] .= add_js('js/global.js');

			$mainData['EDIT'] = $this->User_model->get_cmsuser_byid($ID);
			
			$a['mainContent'] = $this->load->view('user/vchange_password_form2', $mainData, TRUE);

			$this->load->view('vbase', $a);
		}else{
			$post = $this->input->post(null, true);
			$ID = $post['uid'];

			if (trim($post['password']) == '' OR strlen($post['password']) < 6 )
			{
				$this->session->set_userdata('message','Password minimum 6 characters.');
				$this->session->set_userdata('message_type','error');
				redirect('user/change_password/'.$ID);
				exit;
			}elseif ($post['password'] != $post['password2'])
			{
				$this->session->set_userdata('message','Password dan Re-type Password didn\'t match.');
				$this->session->set_userdata('message_type','error');
				redirect('user/change_password/'.$ID);
				exit;
			}else{
				$stored = password_hash(base64_encode(hash('sha256', (trim($post['password'])), true)), PASSWORD_DEFAULT);

				$data['password'] = $stored;

				$update = $this->User_model->update_cms_user($data, $ID);
				if ($update){
					$this->session->set_userdata('message','Password Changes successfully.');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','No Update.');
					$this->session->set_userdata('message_type','success');
				}
			}
			
			redirect('user/management');
		}
	}

}