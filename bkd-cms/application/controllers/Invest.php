<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invest extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Invest_model');
		
		// error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Invest';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/invest.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mainData['mainContent']  = $this->load->view('invest/vlist', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->Invest_model->get_all_dt();
		print_r($data);
	}

	function detail()
	{
		$id = $this->input->post('id');

		$output['data'] = $this->Invest_model->get_data_byid($id);
		$this->load->view('invest/vdetail', $output);
	}

	function delete()
	{
		$this->User_model->has_login();

		$id = antiInjection($this->uri->segment(3));

		$del = $this->Invest_model->delete_pendanaan($id);
		if($id && $del){

			$this->session->set_userdata('message','Data has been deleted.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('invest');
	}

	function verify()
	{
		$this->User_model->has_login();
		
		$id = antiInjection($this->uri->segment(3));

		$check = $this->Invest_model->check_pendanaan($id);

		if(is_array($check) && count($check)>0){

			$affected = $this->Invest_model->do_verify($id);

			if ($affected) {
				$this->session->set_userdata('message','Pendanaan nomor '.$id.' berhasil di verifikasi.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','No Change');
				$this->session->set_userdata('message_type','info');	
			}
		}else{
			$this->session->set_userdata('message','Tidak ada data yang dipilih');
			$this->session->set_userdata('message_type','info');
		}

		redirect('invest');
	}

	
}