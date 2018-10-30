<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		//error_reporting(E_ALL);
	}
	
	public function index()
	{
	}

	public function registrasi_success()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title']    = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', '');

		$data['pages']    = 'v_registrasi_success';
		$this->load->view('template', $data);
	}

	public function pinjaman_belum_selesai()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title']    = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', '');

		$data['message_title'] = 'Informasi';
		$data['message_body']  = 'Pengajuan Pinjaman Dibatalkan. <br> Anda mempunyai transaksi pinjaman yang belum selesai.';
		$data['message_type']  = 'warning';

		$data['pages']    = 'v_message';
		$this->load->view('template', $data);
	}

	function restrict_pendana()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title']    = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', '');

		$data['message_title'] = 'Informasi';
		$data['message_body']  = 'Maaf. Anda tidak bisa melakukan peminjaman.';
		$data['message_type']  = 'warning';

		$data['pages']    = 'v_message';
		$this->load->view('template', $data);
	}
}