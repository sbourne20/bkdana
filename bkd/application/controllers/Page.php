<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		//error_reporting(E_ALL);
	}
	
	public function index()
	{
		// pendanaan
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['datapage'] = $this->Content_model->get_page(8);

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com, pinjaman, pendanaan, pembiayaan', htmlentities($data['datapage']['p_subtitle']));

		$data['pages']    = 'v_page';
		$this->load->view('template', $data);
	}

	public function pinjaman()
	{
		// pinjaman
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['datapage'] = $this->Content_model->get_page(9);

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com, pinjaman, pendanaan, pembiayaan', htmlentities($data['datapage']['p_subtitle']));

		$data['pages']    = 'v_page';
		$this->load->view('template', $data);
	}

	public function tentang()
	{
		// tentang kami
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['datapage'] = $this->Content_model->get_page(10);

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com, pinjaman, pendanaan, pembiayaan', htmlentities($data['datapage']['p_subtitle']));

		$data['pages']    = 'v_page';
		$this->load->view('template', $data);
	}

	public function bantuan()
	{
		// bantuan (faq)
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['datapage'] = $this->Content_model->get_page(11);

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com, pinjaman, pendanaan, pembiayaan', htmlentities($data['datapage']['p_subtitle']));

		$data['pages']    = 'v_page';
		$this->load->view('template', $data);
	}

	public function syarat_ketentuan()
	{
		// syarat & ketentuan
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['datapage'] = $this->Content_model->get_page(12);

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com, pinjaman, pendanaan, pembiayaan', htmlentities($data['datapage']['p_subtitle']));

		$data['pages']    = 'v_page';
		$this->load->view('template', $data);
	}

	public function kebijakan_privasi()
	{
		// kebijakan privasi
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['datapage'] = $this->Content_model->get_page(13);

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com, pinjaman, pendanaan, pembiayaan', htmlentities($data['datapage']['p_subtitle']));

		$data['pages']    = 'v_page';
		$this->load->view('template', $data);
	}

}