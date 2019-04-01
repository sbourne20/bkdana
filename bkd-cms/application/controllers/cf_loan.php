<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cf_loan extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Reporting_model');
		$this->load->model('Cf_loan_model');
		
		 error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Report';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		
		$mainData['top_css'] .= add_css('plugins/bootstrap-datepicker/bootstrap-datepicker.css');
		$mainData['top_js']  .= add_js('plugins/bootstrap-datepicker/bootstrap-datepicker.js');

		
		$mainData['bottom_js'] .= add_js('js/global.js');

		$output['data'] = array();

		$mainData['mainContent']  = $this->load->view('cf_loan/v_front', $output, true);

		$this->load->view('vbase',$mainData);
	}


	function submit_report()
	{
		$post = $this->input->post(NULL, TRUE);
		$from = trim($post['from']);
		$to = trim($post['to']);

		$fromtgl = trim($post['from']);
		$totgl = trim($post['to']);
		//$to   = trim($post['to']);

		$pembagi_avg = 30;
		$output['data'] = array('2');

		$output['is_submit'] = 1;
		$output['is_from']   = $from;
		$output['is_to'] = $to;

		if ($from == '' || $to == '') {

			$this->session->set_userdata('message','Periode tidak boleh kosong.');
			$this->session->set_userdata('message_type','warning');
			redirect($this->uri->segment(1));

		}else{

			// $from = '01-'. $from;
			// $to   = '01-'. $to; // 01 dummy saja karena akan di convert dng "t" : akhir tanggal


			// $pass_date = strtotime($fromtgl)-strtotime($totgl);
			// $output['tgl'] = $pass_date/(60*60*24);
			// $output['tgl'] = cal_days_in_month(CAL_GREGORIAN, date('m', $fromtgl), date('Y', $totgl));

			// echo $total_days;

			$output['tgl'] = ceil(abs(strtotime($fromtgl)-strtotime($totgl))/86400);


			$start = date('Y-m-d', strtotime($from)) . ' 00:00:00';
			$end   = date('Y-m-d', strtotime($to)) . ' 23:59:59';

			$output['bulan'] = date('F Y d', strtotime($fromtgl));
			$output['bulan2'] = date('F Y d', strtotime($totgl));

			$output['total_disburse'] = $this->Cf_loan_model->sum_uang_disburse($start, $end);
			$output['total_principal'] = $this->Cf_loan_model->sum_principal($start, $end);

			// $output['jml_orang']    = $this->Reporting_model->jml_orang($start, $end);
			// $output['jml_pinjaman'] = $this->Reporting_model->jml_uang_pinjaman($start, $end);			
			// $bunga           = $this->Reporting_model->jml_bunga($start, $end);
			// $output['bunga'] = round($bunga['itotal_bunga']/$pembagi_avg, 2)*100;
			// $output['tenor'] = round($bunga['itotal_tenor']/$pembagi_avg, 2)*100;


			// $output['jml_disetujui']   = $this->Reporting_model->jml_orang_disetujui($start, $end);
			// $output['bunga_disetujui'] = round($output['jml_disetujui']['itotal_bunga']/$pembagi_avg, 2)*100;
			// $output['tenor_disetujui'] = round($output['jml_disetujui']['itotal_tenor']/$pembagi_avg, 2)*100;

		


			// ---------------------------//

			$output['PAGE_TITLE'] = 'CF LOAN';

			$mainData['top_css']   = '';
			$mainData['top_js']    = '';
			$mainData['bottom_js'] = '';
			
			$mainData['top_css'] .= add_css('plugins/bootstrap-datepicker/bootstrap-datepicker.css');
			$mainData['top_js']  .= add_js('plugins/bootstrap-datepicker/bootstrap-datepicker.js');

			$mainData['bottom_js'] .= add_js('js/global.js');

			$mainData['mainContent']  = $this->load->view('cf_loan/v_front', $output, true);

			$this->load->view('vbase',$mainData);
		}
	}
}