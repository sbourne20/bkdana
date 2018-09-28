<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grade_user extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Grade_model');
		
		// error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output = '';
		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/grade.js');

		$mainData['mainContent']  = $this->load->view('grade/vgrade_list', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->Grade_model->get_all_dt();
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
			
			$mainData['mainContent'] = $this->load->view('grade/vgrade_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$grade_name = trim($post['grade']);
			if ( !empty($grade_name) ) {

				$check_existing = $this->Grade_model->checkif_exist($grade_name);

				if (is_array($check_existing) && !empty($check_existing['grade_name'])) {
					$this->session->set_userdata('message','Grade <strong>'.$grade_name.'</strong> was exist. Please choose another name.');
					$this->session->set_userdata('message_type','error');
					redirect('grade_user/add');
				}else{
					$data['grade_name']           = $grade_name;
					$data['completeness_profile'] = $post['completeness'];

					$update = $this->Grade_model->insert_($data);
					if ($update){
						$this->session->set_userdata('message','Success add grade.');
						$this->session->set_userdata('message_type','success');
					}else{
						$this->session->set_userdata('message','Error add grade. Please try again.');
						$this->session->set_userdata('message_type','error');
					}

					redirect('grade_user'); 
				}
				
			}else{
				$this->session->set_userdata('message','please fill in all mandatory field.');
				$this->session->set_userdata('message_type','error');
				redirect('grade_user/add');
				exit();
			}			
		}
	}

	function edit()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);
		
		$output['mode'] = 2; // sbg tanda edit
		$output['EDIT']     = $this->Grade_model->get_grade_byid($id);

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{

			$mainData['top_css']  ="";
			$mainData['top_js']   ="";
			$mainData['bottom_js'] =""; 
			$mainData['bottom_js'] .= add_js('js/global.js');
			
			$mainData['mainContent'] = $this->load->view('grade/vgrade_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$grade_name = trim($post['grade']);
			if ( !empty($grade_name) ) {

				$check_existing = $this->Grade_model->checkif_exist($grade_name, $id);

				if (is_array($check_existing) && !empty($check_existing['grade_name'])) {
					$this->session->set_userdata('message','Grade <strong>'.$grade_name.'</strong> was exist. Please choose another name.');
					$this->session->set_userdata('message_type','error');
					redirect('grade_user/edit/'.$id);
				}else{
					$data['grade_name']           = $grade_name;
					$data['completeness_profile'] = $post['completeness'];

					$update = $this->Grade_model->update_($data, $id);
					if ($update){
						$this->session->set_userdata('message','Update success.');
						$this->session->set_userdata('message_type','success');
					}else{
						$this->session->set_userdata('message','No change.');
						$this->session->set_userdata('message_type','info');
					}
					redirect('grade_user');
				}
				 
			}else{
				$this->session->set_userdata('message','please fill in all mandatory field.');
				$this->session->set_userdata('message_type','error');
				redirect('grade_user/edit/'.$id);
				exit();
			}

		}
	}

	function validation()
	{
		error_reporting(E_ALL);
		$this->form_validation->set_rules('grade', 'grade name', 'trim|required');
		$this->form_validation->set_rules('completeness', 'completeness', 'trim|required');

		$this->form_validation->set_message('required', '%s is required.');
	}

	function delete()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);

		if (trim($id) != '' && !empty($id))
		{
			$del = $this->Grade_model->delete_($id);
			if($del){
				$this->session->set_userdata('message','Data has been deleted.');
				$this->session->set_userdata('message_type','success');
			}
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('grade_user');
	}
	
}