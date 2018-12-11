<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_group extends CI_Controller {

	/* Member Group */

	/*function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Membergroup_model');
		
		// error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output = '';
		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/member_group.js');

		$mainData['mainContent']  = $this->load->view('member/vmembergroup', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->Group_model->get_all_group();
		print_r($data);
	}

	function add()
	{
		$this->User_model->has_login();
		
		$output['mode'] = 1;
		$output['EDIT'] = NULL;

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{

			$mainData['top_css']  ="";
			$mainData['top_js']   ="";
			$mainData['bottom_js'] ="";
			
			$mainData['bottom_js'] .= add_js('js/global.js');
			
			$mainData['mainContent'] = $this->load->view('member/vmembergroup_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$groupname = trim($post['groupname']);
			if ( !empty($groupname) ) {

				$check_existing = $this->Group_model->checkif_exist($groupname);

				if (is_array($check_existing) && !empty($check_existing['group_name'])) {
					$this->session->set_userdata('message','Group <strong>'.$groupname.'</strong> was exist. Please choose another name.');
					$this->session->set_userdata('message_type','error');
					redirect('group/add');
				}else{
					$data['group_name']       = $groupname;
					$data['group_status']     = $post['status'];

					$update = $this->Group_model->insert_group($data);
					if ($update){
						$this->session->set_userdata('message','Success add group.');
						$this->session->set_userdata('message_type','success');
					}else{
						$this->session->set_userdata('message','Error add group. Please try again.');
						$this->session->set_userdata('message_type','error');
					}

					redirect('member'); 
				}

				
			}else{
				$this->session->set_userdata('message','please fill in all mandatory field.');
				$this->session->set_userdata('message_type','error');
				redirect('group/add');
				exit();
			}			
		}
	}

	function edit()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);
		
		$output['mode'] = 2; // sbg tanda edit
		$output['EDIT']     = $this->Group_model->get_group_byid($id);

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{

			$mainData['top_css']  ="";
			$mainData['top_js']   ="";
			$mainData['bottom_js'] =""; 
			$mainData['bottom_js'] .= add_js('js/global.js');
			
			$mainData['mainContent'] = $this->load->view('member/vmembergroup_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$groupname = trim($post['groupname']);
			if ( !empty($groupname) ) {

				$check_existing = $this->Group_model->checkif_exist($groupname, $id);

				if (is_array($check_existing) && !empty($check_existing['group_name'])) {
					$this->session->set_userdata('message','Group <strong>'.$groupname.'</strong> was exist. Please choose another name.');
					$this->session->set_userdata('message_type','error');
					redirect('group/edit/'.$id);
				}else{
					$data['group_name']       = $groupname;
					$data['group_status']     = $post['status'];

					$update = $this->Group_model->update_group($data, $id);
					if ($update){
						$this->session->set_userdata('message','Update success.');
						$this->session->set_userdata('message_type','success');
					}else{
						$this->session->set_userdata('message','No change.');
						$this->session->set_userdata('message_type','info');
					}
					redirect('group');
				}
				 
			}else{
				$this->session->set_userdata('message','please fill in all mandatory field.');
				$this->session->set_userdata('message_type','error');
				redirect('group/edit/'.$id);
				exit();
			}

		}
	}

	function validation()
	{
		error_reporting(E_ALL);
		$this->form_validation->set_rules('groupname', 'group name', 'trim|required');

		$this->form_validation->set_message('required', '%s is required.');
	}

	function delete()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);

		if (trim($id) != '' && !empty($id))
		{
			$del = $this->Group_model->delete_group($id);
			if($del){
				$this->session->set_userdata('message','Data has been deleted.');
				$this->session->set_userdata('message_type','success');
			}
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('member');
	}

	// ----- Tambahan Baru -----

		//  ============================= Member GROUP ===============================

	function group()
	{
		// -------- Display Member Group list ---------
		$this->User_model->has_login();
		
		$output['PAGE_TITLE'] = 'MEMBER GROUP';


		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';

		// use this js for this page
		$mainData['bottom_js'] .= add_js('js/data/member_group.js');

		$mainData['mainContent'] = $this->load->view('member/vmembergroup', $output, TRUE);

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
		
		$a['mainContent'] = $this->load->view('member/vmembergroup_form', $mainData, TRUE);

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
			
			$a['mainContent'] = $this->load->view('member/vsetting_role_group', $mainData, TRUE);

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
			redirect('membergroup'); 
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
		
		$a['mainContent'] = $this->load->view('member/vmembergroup_form', $mainData, TRUE);

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

	 	redirect('membergroup');
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

		redirect('membergroup');
	}
*/
	// ----- Batas tambahan -----

	
}

