<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();
		error_reporting(E_ALL);
	}
	
	public function index()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag;

		$data['setting_home'] = $this->M_settings->get_setting_home();

		//_d($data['setting_home']);

		$data['pages']    = 'v_home';
		$this->load->view('template', $data);
	}
	
}