<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Type_business extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Business_model');
		
		// error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Type Business';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/type_business.js');

		$mainData['mainContent']  = $this->load->view('type_business/vlist', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->Business_model->get_all_dt();
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
			
			$mainData['top_js']    .= add_js("plugins/friendurl/jquery.friendurl.min.js");
			$mainData['bottom_js'] .= add_js('js/global.js');

			
			$mainData['mainContent'] = $this->load->view('type_business/vform', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$business_name  = trim($post['business_name']);
			
			if ( !empty($business_name) ) {

				$check_existing = $this->Business_model->checkif_exist($business_name);

				if (is_array($check_existing) && !empty($check_existing['type_business_name'])) {
					$this->session->set_userdata('message', '<strong>'. $business_name. '</strong> was exist. choose another name.');
					$this->session->set_userdata('message_type','error');
					redirect('type_business/add');
				}else{
				
					$data['type_business_name']    = $business_name;
					$data['type_business_slug']    = trim($post['slug']);
					$data['type_business_category']= trim($post['category']);
					$data['type_business_status']  = $post['status'];

					$update = $this->Business_model->insert_($data);
					if ($update){
						$this->session->set_userdata('message','Success add new Type of Business.');
						$this->session->set_userdata('message_type','success');
					}else{
						$this->session->set_userdata('message','Error add agent. Please try again.');
						$this->session->set_userdata('message_type','error');
					}

					redirect('type_business'); 
				}
			}else{
				$this->session->set_userdata('message','please fill in all mandatory field.');
				$this->session->set_userdata('message_type','error');
				redirect('type_business/add');
				exit();
			}			
		}
	}

	function edit()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);
		
		$output['mode'] = 2; // sbg tanda edit
		$output['EDIT']     = $this->Business_model->get_data_byid($id);

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{

			$mainData['top_css']  ="";
			$mainData['top_js']   ="";
			$mainData['bottom_js'] ="";

			$mainData['top_js']    .= add_js("plugins/friendurl/jquery.friendurl.min.js");
			$mainData['bottom_js'] .= add_js('js/global.js');

			
			$mainData['mainContent'] = $this->load->view('type_business/vform', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$business_name  = trim($post['business_name']);
			
			if ( !empty($business_name) ) {

				$check_existing = $this->Business_model->checkif_exist($business_name, $id);

				if (is_array($check_existing) && !empty($check_existing['type_business_name'])) {
					$this->session->set_userdata('message', '<strong>'. $business_name. '</strong> was exist. choose another name.');
					$this->session->set_userdata('message_type','error');
					redirect('type_business/edit/'.$id);
				}else{
				
					$data['type_business_name']    = $business_name;
					$data['type_business_slug']    = trim($post['slug']);
					$data['type_business_category']= trim($post['category']);
					$data['type_business_status']  = $post['status'];

					$update = $this->Business_model->update_($data, $id);
					if ($update){
						$this->session->set_userdata('message','Update success.');
						$this->session->set_userdata('message_type','success');
					}else{
						$this->session->set_userdata('message','No change.');
						$this->session->set_userdata('message_type','info');
					}
					redirect('type_business'); 
				}
			}else{
				$this->session->set_userdata('message','please fill in all mandatory field.');
				$this->session->set_userdata('message_type','error');
				redirect('type_business/edit/'.$id);
				exit();
			}

		}
	}


	function validation()
	{
		error_reporting(E_ALL);
		$this->form_validation->set_rules('business_name', 'Type of Business Name', 'trim|required');
		$this->form_validation->set_message('required', '%s harus diisi.');
	}

	function delete()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);

		if (trim($id) != '' && !empty($id))
		{
			$del = $this->Business_model->delete_($id);
			if($del){
				$this->session->set_userdata('message','Data has been deleted.');
				$this->session->set_userdata('message_type','success');
			}
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('type_business');
	}

	
}