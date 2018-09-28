<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agent extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Agent_model');
		
		// error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Agent';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/agent.js');

		$mainData['mainContent']  = $this->load->view('agent/vlist', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->Agent_model->get_all_dt();
		print_r($data);
	}

	function detail()
	{
		$id = $this->input->post('id');

		$output['data'] = $this->Agent_model->get_member_byid($id);
		$this->load->view('member/vdetail', $output);
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

			
			$mainData['mainContent'] = $this->load->view('agent/vadd_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$oname    = trim($post['oname']);
			$oaddress = trim($post['oaddress']);

			if ( $oname=='' OR $oaddress=='') {
				$this->session->set_userdata('message','Name and Address is mandatory.');
				$this->session->set_userdata('message_type','error');
				redirect('agent/add');
				exit();
			}else if ( !empty($oname) && !empty($oaddress) ) {


				//$stored = password_hash(base64_encode(hash('sha256', ($password), true)), PASSWORD_DEFAULT);

				$data['organizer_name']    = trim($post['oname']);
				$data['organizer_address'] = trim($post['oaddress']);
				$data['organizer_story']   = trim($post['ostory']);
				$data['organizer_mission'] = trim($post['omission']);
				$data['organizer_note']    = trim($post['onote']);

				$update = $this->Agent_model->insert_($data);
				if ($update){
					$this->session->set_userdata('message','Success add agent.');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','Error add agent. Please try again.');
					$this->session->set_userdata('message_type','error');
				}

				redirect('agent'); 
			}else{
				$this->session->set_userdata('message','please fill in all mandatory field.');
				$this->session->set_userdata('message_type','error');
				redirect('agent/add');
				exit();
			}			
		}
	}

	function edit()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);
		
		$output['mode'] = 2; // sbg tanda edit
		$output['EDIT']     = $this->Agent_model->get_agent_byid($id);

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{

			$mainData['top_css']  ="";
			$mainData['top_js']   ="";
			$mainData['bottom_js'] ="";

			$mainData['bottom_js'] .= add_js('js/global.js');

			
			$mainData['mainContent'] = $this->load->view('agent/vadd_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$fullname  = trim($post['oname']);			

			if ( !empty($fullname) ) {
				
				$data['organizer_name']    = trim($post['oname']);
				$data['organizer_address'] = trim($post['oaddress']);
				$data['organizer_story']   = trim($post['ostory']);
				$data['organizer_mission'] = trim($post['omission']);
				$data['organizer_note']    = trim($post['onote']);

				$update = $this->Agent_model->update_($data, $id);
				if ($update){
					$this->session->set_userdata('message','Update success.');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','No change.');
					$this->session->set_userdata('message_type','info');
				}
				redirect('agent'); 
			}else{
				$this->session->set_userdata('message','please fill in all mandatory field.');
				$this->session->set_userdata('message_type','error');
				redirect('agent/edit/'.$id);
				exit();
			}

		}
	}


	function validation()
	{
		error_reporting(E_ALL);
		$this->form_validation->set_rules('oname', 'Name', 'trim|required');
		$this->form_validation->set_rules('oaddress', 'Address', 'trim|required');

		$this->form_validation->set_message('required', '%s harus diisi.');
	}

	function delete()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);

		if (trim($id) != '' && !empty($id))
		{
			$del = $this->Agent_model->delete_($id);
			if($del){
				$this->session->set_userdata('message','Data has been deleted.');
				$this->session->set_userdata('message_type','success');
			}
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('agent');
	}
}