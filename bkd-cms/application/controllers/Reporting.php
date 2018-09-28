<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reporting extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Reporting_model');
		
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

		$mainData['mainContent']  = $this->load->view('reporting/vpinjaman-list', $output, true);

		$this->load->view('vbase',$mainData);
	}


	function submit_pinjaman()
	{
		$post = $this->input->post(NULL, TRUE);
		$from = trim($post['from']);
		//$to   = trim($post['to']);

		$pembagi_avg = 30;
		$output['data'] = array('2');

		$output['is_submit'] = 1;
		$output['is_from']   = $from;

		if ($from == '') {

			$this->session->set_userdata('message','Periode tidak boleh kosong.');
			$this->session->set_userdata('message_type','warning');
			redirect($this->uri->segment(1));

		}else{

			$from = '01-'. $from;
			//$to   = '01-'. $to; // 01 dummy saja karena akan di convert dng "t" : akhir tanggal

			$start = date('Y-m-d', strtotime($from)) . ' 00:00:00';
			$end   = date('Y-m-t', strtotime($from)) . ' 23:59:59';

			$output['bulan'] = date('F Y', strtotime($from));

			$output['jml_orang']    = $this->Reporting_model->jml_orang($start, $end);
			$output['jml_pinjaman'] = $this->Reporting_model->jml_uang_pinjaman($start, $end);			
			$bunga           = $this->Reporting_model->jml_bunga($start, $end);
			$output['bunga'] = round($bunga['itotal_bunga']/$pembagi_avg, 2)*100;
			$output['tenor'] = round($bunga['itotal_tenor']/$pembagi_avg, 2)*100;

			$output['jml_disetujui']   = $this->Reporting_model->jml_orang_disetujui($start, $end);
			$output['bunga_disetujui'] = round($output['jml_disetujui']['itotal_bunga']/$pembagi_avg, 2)*100;
			$output['tenor_disetujui'] = round($output['jml_disetujui']['itotal_tenor']/$pembagi_avg, 2)*100;

			$lancar    = $this->Reporting_model->jml_pinjaman_lancar($start, $end);
			$notlancar = $this->Reporting_model->jml_pinjaman_notlancar($start, $end);
			$macet     = $this->Reporting_model->jml_pinjaman_macet($start, $end);

			$output['lancar']    = round(($lancar['itotal']*100)/$pembagi_avg);
			$output['notlancar'] = round(($notlancar['itotal']*100)/$pembagi_avg);
			$output['macet']     = round(($macet['itotal']*100)/$pembagi_avg);

			// ------- Akumulasi (Transaksi yang sedang berjalan) ------- 
			$output['jml_orang_ak']    = $this->Reporting_model->jml_akumulasi_orang($start, $end);
			$output['jml_pinjaman_ak'] = $this->Reporting_model->jml_akumulasi_uang_pinjaman($start, $end);

			$bunga_ak           = $this->Reporting_model->jml_akumulasi_bunga($start, $end);
			$output['bunga_ak'] = round($bunga_ak['itotal_bunga']/$pembagi_avg, 2)*100;
			$output['tenor_ak'] = round($bunga_ak['itotal_tenor']/$pembagi_avg, 2)*100;

			$lancar_ak    = $this->Reporting_model->jml_akumulasi_pinjaman_lancar($start, $end);
			$notlancar_ak = $this->Reporting_model->jml_akumulasi_pinjaman_notlancar($start, $end);
			$macet_ak     = $this->Reporting_model->jml_akumulasi_pinjaman_macet($start, $end);

			$output['lancar_ak']    = round(($lancar_ak['itotal']*100)/$pembagi_avg);
			$output['notlancar_ak'] = round(($notlancar_ak['itotal']*100)/$pembagi_avg);
			$output['macet_ak']     = round(($macet_ak['itotal']*100)/$pembagi_avg);

			// ---------------------------//

			$output['PAGE_TITLE'] = 'Report';

			$mainData['top_css']   = '';
			$mainData['top_js']    = '';
			$mainData['bottom_js'] = '';
			
			$mainData['top_css'] .= add_css('plugins/bootstrap-datepicker/bootstrap-datepicker.css');
			$mainData['top_js']  .= add_js('plugins/bootstrap-datepicker/bootstrap-datepicker.js');

			$mainData['bottom_js'] .= add_js('js/global.js');

			$mainData['mainContent']  = $this->load->view('reporting/vpinjaman-list', $output, true);

			$this->load->view('vbase',$mainData);
		}
	}
}