<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekening_koran extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Member_model');
		$this->load->model('Rekening_koran_model');
		
		// error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Rekening koran';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/rekening_koran.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mainData['mainContent']  = $this->load->view('rekening_koran/vmember', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->Rekening_koran_model->get_member_dt();
		print_r($data);
	}

	function json_detail()
	{
		$uid = strip_tags($this->uri->segment(3));
		$data = $this->Rekening_koran_model->get_rekening_koran_dt($uid);
		print_r($data);
	}

	function detail()
	{
		$this->User_model->has_login();
		$output['member_id'] = strip_tags($this->uri->segment(3));
		$output['datamember'] = $this->Member_model->get_user_ojk_bymember($output['member_id']);

		$output['PAGE_TITLE'] = 'Rekening koran';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		
		$mainData['top_css'] .= add_css('plugins/bootstrap-datepicker/bootstrap-datepicker.css');
		$mainData['top_js'] .= add_js('plugins/bootstrap-datepicker/bootstrap-datepicker.js');

		$mainData['bottom_js'] .= add_js('js/data/rekening_koran_detail.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mainData['mainContent']  = $this->load->view('rekening_koran/vdetail', $output, true);

		$this->load->view('vbase',$mainData);
	}
}