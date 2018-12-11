<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group extends CI_Controller {

	/* Member Group */

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Group_model');
		
		// error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output = '';
		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/group.js');

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

					redirect('group'); 
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
			
			$mainData['mainContent'] = $this->load->view('group/vgroup_form', $output, TRUE);

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

		redirect('group');
	}
	
}