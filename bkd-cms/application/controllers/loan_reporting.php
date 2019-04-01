<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class loan_reporting extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Member_model');
		$this->load->model('loan_reporting_model');
		$this->load->model('Rekening_koran_model');

		
		// error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Loan Reporting';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/global.js');
		$mainData['top_css']  .= add_css('plugins/bootstrap-datepicker/bootstrap-datepicker.css');
		$mainData['top_css']  .= add_external_css('https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css');
		$mainData['top_js'] .= add_js('plugins/bootstrap-datepicker/bootstrap-datepicker.js');
		$mainData['bottom_js'] .= add_js('plugins/DataTables1.10.19/dataTables.min.js');
		$mainData['bottom_js'] .= add_js('plugins/Buttons-1.5.6/js/dataTables.buttons.min.js');
		$mainData['bottom_js'] .= add_js('plugins/Buttons-1.5.6/js/buttons.flash.min.js');
		$mainData['bottom_js'] .= add_js('plugins/JSZip-2.5.0/jszip.min.js');
		$mainData['bottom_js'] .= add_js('plugins/pdfmake-0.1.36/pdfmake.min.js');
		$mainData['bottom_js'] .= add_js('plugins/pdfmake-0.1.36/vfs_fonts.js');
		$mainData['bottom_js'] .= add_js('plugins/Buttons-1.5.6/js/buttons.html5.min.js');
		$mainData['bottom_js'] .= add_js('plugins/Buttons-1.5.6/js//buttons.print.min.js');
		$mainData['bottom_js'] .= add_js('js/data/loan_reporting.js');

		$mainData['mainContent']  = $this->load->view('loan_reporting/vmember', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->loan_reporting_model->get_all_dt();
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