<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_jatuh_tempo extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Member_model');
		$this->load->model('Pinjaman_model');
		$this->load->model('Product_model');
		$this->load->model('Wallet_model');
		$this->load->model('Log_transaksi_model');
		
		//error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Loan';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/transaksi_pinjaman_kilat.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mainData['mainContent']  = $this->load->view('loan/vkilat_list', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{
		$data = $this->Pinjaman_model->get_all_kilat_dt();
		print_r($data);
	}