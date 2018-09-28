<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('Dashboard_model');
		$this->load->model('User_model');

		$this->load->helper('text');

		$this->User_model->has_login();
		// error_reporting(E_ALL);
	}

	function index()
	{
		//$this->load->model('order_model');
		$output = '';
		$output['checkout'] = '';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';

		$mainData['top_css']   .= add_css('plugins/morris-chart/morris.css');

		$output['total_peminjam'] = $this->Dashboard_model->total_peminjam();
		$output['total_pendana']  = $this->Dashboard_model->total_pendana();
		$output['kilat']  = $this->Dashboard_model->total_pinjaman_kilat();
		$output['mikro']  = $this->Dashboard_model->total_pinjaman_mikro();
		$output['pendanaan']  = $this->Dashboard_model->total_transaksi_pendana();

		$output['total_topup_pending']    = $this->Dashboard_model->total_topup_pending();
		$output['total_redeem']   = $this->Dashboard_model->total_redeem_pending();
		
		$mainData['mainContent'] = $this->load->view('dashboard/vdashboard', $output, true);

		$this->load->view('vbase', $mainData);
	}

	function lock()
	{
		$mainData['CU'] = $this->User_model->current_user();
		$this->load->view('vlockscreen',$mainData);
	}
}