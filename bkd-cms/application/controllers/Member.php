<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Member_model');
		$this->load->model('Grade_model');
		$this->load->model('Wallet_model');
		$this->load->library('session');
		
		 //error_reporting(0);
		 //ini_set('display_errors', '1');
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Member';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/member.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mainData['mainContent']  = $this->load->view('member/vmember', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->Member_model->get_member_all();
		print_r($data);
	}

	public function json_group()
	{		
		$result=$this->Member_model->get_user_group_dt();
		print_r($result);
	}

	function detail()
	{
		$id = $this->input->post('id');

		$output['data'] = $this->Member_model->get_usermember_by($id);
		$this->load->view('member/vdetail', $output);

		$this->session->set_flashdata('id', $id);
	}


/*	function edit()
	{
		$this->Member_model->has_login();

		$mainData['add_mode']  = 1; // sbg tanda edit
		
		$mainData['EDIT']      = $this->Member_model->get_member_byid($ID);
		$mainData['membergroup'] = $this->Member_model->get_all_group();
	}*/

	function delete()
	{
		$this->User_model->has_login();

		$id = antiInjection($this->uri->segment(3));

		$getdata = $this->Member_model->get_user_ojk_bymember($id);

		//_d($getdata);

		$del = $this->Member_model->delete_member($id);
		if($id && $del){

			$this->Member_model->delete_user_ojk($id);
			$this->Member_model->delete_user_ojk_detail($getdata['Id_pengguna']);
			$this->Member_model->delete_profil_geografi($getdata['Id_pengguna']);
			$this->Wallet_model->delete_master_wallet($getdata['Id_pengguna']);
			$this->Wallet_model->delete_detail_wallet($getdata['Id_pengguna']);

			$this->session->set_userdata('message','Data has been deleted.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('member');
	}


	//-------------- tambahan terbaru -------------------

	//  ============================= USER GROUP ===============================

	function group()
	{
		// -------- Display user Group list ---------
		$this->User_model->has_login();
		
		$output['PAGE_TITLE'] = 'Member GROUP';


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

	/*function setting_role()
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
	}*/

	function edit_group()
	{
		$this->User_model->has_login();

		$mainData['add_mode'] = 2; // sbg tanda edit
		$ID                   = $this->uri->segment(3);
		$mainData['EDIT']     = $this->Member_model->get_group_by($ID);

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
				$data['user_group_name'] = $post['gname'];

				$insertg = $this->Member_model->insert_group($data);
				if ($insertg){
					$this->session->set_userdata('message','New Member Group Inserted.');
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
				$data['user_group_name'] = $post['gname'];

				$update = $this->Member_model->update_group($data, $ID);
				if ($update){
					$this->session->set_userdata('message','The Member Group has been updated.');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','No Update.');
					$this->session->set_userdata('message_type','success');
				}
			}
		}

	 	redirect('member/group');
	}

	function delete_group()
	{
		$this->User_model->has_login();

		$id = $this->uri->segment(3);
		$del = $this->Member_model->delete_user_group($id);
		if($id && $del){

			$this->session->set_userdata('message','Data has been deleted.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('member/group');
	}

	


	// -------------- batas tambahan terbaru -------------

	/*// ----- Tambahan Baru -----

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
			redirect('member_group'); 
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

	 	redirect('member_group');
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

		redirect('member_group');
	}

	// ----- Batas tambahan -----*/



	/*function edit()
	{
		$this->User_model->has_login();

		$id = $this->uri->segment(3);
		
		$output['add_mode'] = 2; // sbg tanda edit
		$output['EDIT']     = $this->Member_model->get_member_byid($id);

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{

			$mainData['top_css']  ="";
			$mainData['top_js']   ="";
			$mainData['bottom_js'] ="";
			// init data utk select2
			$mainData['bottom_js'] .= add_js('js/data/select2-data.js');
			$mainData['bottom_js'] .= add_js('js/data/global.js');
			
			$mainData['mainContent'] = $this->load->view('member/vmember_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post();
			$ID   = $post['uid'];

			$data['email']          = $post['email'];
			$data['fullname']       = $post['fullname'];
			$data['phone']           = $post['telp'];
			$data['email']          = $post['email'];
			$data['address']        = $post['address'];
			$data['is_distributor'] = $post['distributor'];

			$update = $this->Member_model->update_member($data, $ID);
			if ($update){
				$this->session->set_userdata('message','Data berhasil diupdate.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','Tidak ada yang diubah.');
				$this->session->set_userdata('message_type','success');
			}

			redirect('member'); 
		}
	}


	function validation()
	{
		error_reporting(E_ALL);
		$this->form_validation->set_rules('fullname', 'Username', 'trim|required');
		$this->form_validation->set_rules('email', 'Password', 'trim|required');

		$this->form_validation->set_message('required', '%s harus diisi.');
	}

	function ubah_status()
	{
		$this->User_model->has_login();
		
		$id     = $this->uri->segment(3);
		$status = $this->uri->segment(4);

		if ($status==1){
			$set_status = 0;
			$msg_status = 'Non Aktif';
		}else{
			$set_status = 1;
			$msg_status = 'Aktif';
		}

		$del = $this->Member_model->set_member_status($id, $set_status);
		if($id && $del){

			$this->session->set_userdata('message','Member tersebut telah ' . $msg_status);
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','No Change');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('member');
	}*/

	
}

/* End of file member.php */