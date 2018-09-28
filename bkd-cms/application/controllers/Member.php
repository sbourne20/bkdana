<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Member_model');
		$this->load->model('Grade_model');
		$this->load->model('Wallet_model');
		
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

	function detail()
	{
		$id = $this->input->post('id');

		$output['data'] = $this->Member_model->get_usermember_by($id);
		$this->load->view('member/vdetail', $output);
	}

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