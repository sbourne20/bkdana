<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	function test_pendana_mail()
	{
		$this->load->model('Member_model');
		$memdata = $this->Member_model->get_member_byid(58);
		$output['member']    = $memdata;
		$output['ordercode'] = 'DD-56C14BF2D8F163';
		$output['tgl_order'] = date('d/m/Y');
		$html = $this->load->view('email/vpendana', $output, TRUE);
		echo $html;
		//$attach_file = $this->create_pdf($html, $output['ordercode']);
	}
}
