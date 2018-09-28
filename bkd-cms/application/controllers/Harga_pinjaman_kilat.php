<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Harga_pinjaman_kilat extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Grade_model');
		$this->load->model('Product_model');
		$this->load->model('Harga_model');
		
		// error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output = '';
		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/global.js');
		$mainData['bottom_js'] .= add_js('js/data/harga_kilat.js');

		$mainData['mainContent']  = $this->load->view('harga/vkilat_list', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->Harga_model->get_all_dt();
		print_r($data);
	}

	function add()
	{
		$this->User_model->has_login();
		
		$output['mode'] = 1;
		$output['EDIT'] = NULL;

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{

			$mainData['top_css']  ="";
			$mainData['top_js']   ="";
			$mainData['bottom_js'] ="";
			
			$mainData['top_css']   .= add_css("plugins/fileinput/fileinput.min.css");
			$mainData['top_css']   .= add_css("plugins/jquery-tags-input/dist/bootstrap-tagsinput.css");
			$mainData['top_css']   .= add_css("plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css");
			$mainData['top_css']   .= add_css("plugins/bootstrap-timepicker/bootstrap-timepicker.css");
			$mainData['top_css']   .= add_css("plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css");

			$mainData['top_js']    .= add_js("plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js");
			$mainData['top_js']    .= add_js("plugins/bootstrap-timepicker/bootstrap-timepicker.js");
			$mainData['top_js']    .= add_js('plugins/ckeditor/ckeditor.js');
			$mainData['top_js']    .= add_js("plugins/autonumeric/autoNumeric.js");
			$mainData['top_js']    .= add_js("plugins/fileinput/fileinput.min.js");
			$mainData['top_js']    .= add_js('plugins/jquery-tags-input/dist/bootstrap-tagsinput.js');
			$mainData['top_js']    .= add_js("plugins/friendurl/jquery.friendurl.min.js");
			$mainData['top_js']    .= add_js("plugins/bootstrap-switch/js/bootstrap-switch.min.js");
			$mainData['top_js']    .= add_js("plugins/numeric/jquery.numeric.min.js");
			
			$mainData['bottom_js'] .= add_js('js/autoNumeric-init.js');
			$mainData['bottom_js'] .= add_js('js/select2-data.js');
			$mainData['bottom_js'] .= add_js('js/global.js');

			$output['products'] = $this->Product_model->get_product_kilat();
			
			$mainData['mainContent'] = $this->load->view('harga/vkilat_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$harga_input = trim($post['harga']);
			$tenor       = $post['product'];
			$totaltenor  = count($post['product']);

			$filter = explode('.', $harga_input);
			$harga  = str_replace(',', '', $filter[0]);

			if ($harga_input != '' && $totaltenor >= 1)
			{
				$indata['h_harga']   = $harga;
				$indata['h_created'] = date('Y-m-d H:i:s');
				$indata['h_status']  = trim($post['status']);

				$hargaID = $this->Harga_model->insert_harga($indata);

				if ($hargaID) {

					foreach ($tenor as $key ) {
						
						$inrelasi['hp_harga_id']   = $hargaID;
						$inrelasi['hp_product_id'] = $key;

						$this->Harga_model->insert_harga_produk($inrelasi);
					}

					$this->session->set_userdata('message','Success add Harga.');
					$this->session->set_userdata('message_type','success');
				}
			}else{
				$this->session->set_userdata('message','Error. Isilah Semua Kolom dengan benar.');
				$this->session->set_userdata('message_type','error');
			}

			redirect('harga_pinjaman_kilat');
		}
	}

	function edit()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);
		
		$output['mode'] = 2; // sbg tanda edit
		$output['EDIT'] = $this->Harga_model->get_harga_byid($id);

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{

			$mainData['top_css']  ="";
			$mainData['top_js']   ="";
			$mainData['bottom_js'] =""; 
			
			$mainData['top_css']   .= add_css("plugins/fileinput/fileinput.min.css");
			$mainData['top_css']   .= add_css("plugins/jquery-tags-input/dist/bootstrap-tagsinput.css");
			$mainData['top_css']   .= add_css("plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css");
			$mainData['top_css']   .= add_css("plugins/bootstrap-timepicker/bootstrap-timepicker.css");
			$mainData['top_css']   .= add_css("plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css");

			$mainData['top_js']    .= add_js("plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js");
			$mainData['top_js']    .= add_js("plugins/bootstrap-timepicker/bootstrap-timepicker.js");
			$mainData['top_js']    .= add_js('plugins/ckeditor/ckeditor.js');
			$mainData['top_js']    .= add_js("plugins/autonumeric/autoNumeric.js");
			$mainData['top_js']    .= add_js("plugins/fileinput/fileinput.min.js");
			$mainData['top_js']    .= add_js('plugins/jquery-tags-input/dist/bootstrap-tagsinput.js');
			$mainData['top_js']    .= add_js("plugins/friendurl/jquery.friendurl.min.js");
			$mainData['top_js']    .= add_js("plugins/bootstrap-switch/js/bootstrap-switch.min.js");
			$mainData['top_js']    .= add_js("plugins/numeric/jquery.numeric.min.js");
			
			$mainData['bottom_js'] .= add_js('js/autoNumeric-init.js');
			$mainData['bottom_js'] .= add_js('js/select2-data.js');
			$mainData['bottom_js'] .= add_js('js/global.js');

			$output['products']  = $this->Product_model->get_product_kilat();
			$output['relasi_hp'] = $this->Harga_model->get_harga_produk($id);

			//_d($output['relasi_hp']);
			
			$mainData['mainContent'] = $this->load->view('harga/vkilat_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$harga_input = trim($post['harga']);
			$tenor       = $post['product'];
			$totaltenor  = count($post['product']);

			$filter = explode('.', $harga_input);
			$harga  = str_replace(',', '', $filter[0]);

			if ($harga_input != '' && $totaltenor >= 1)
			{
				$indata['h_harga']   = $harga;
				$indata['h_status']  = trim($post['status']);

				$hargaID = $this->Harga_model->update_harga($indata, $id);

				$this->Harga_model->delete_relasi_harga($id); // delete old data di table mod_harga_produk

				foreach ($tenor as $key ) {
					
					$inrelasi['hp_harga_id']   = $id;
					$inrelasi['hp_product_id'] = $key;

					$this->Harga_model->insert_harga_produk($inrelasi);
				}

				$this->session->set_userdata('message','Success update Harga.');
				$this->session->set_userdata('message_type','success');
				
			}else{
				$this->session->set_userdata('message','Semua kolom harus diisi.');
				$this->session->set_userdata('message_type','error');
			}

			redirect('harga_pinjaman_kilat');
		}
	}

	function validation()
	{
		error_reporting(E_ALL);
		$this->form_validation->set_rules('harga', 'harga', 'trim|required');
		//$this->form_validation->set_rules('product', 'product', 'trim|required');

		$this->form_validation->set_message('required', '%s is required.');
	}

	function delete()
	{
		$this->User_model->has_login();

		$id = (int)$this->uri->segment(3);

		if (trim($id) != '' && !empty($id))
		{
			$del = $this->Harga_model->delete_harga($id);
			$del = $this->Harga_model->delete_relasi_harga($id);
			if($del){
				$this->session->set_userdata('message','Data has been deleted.');
				$this->session->set_userdata('message_type','success');
			}
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('harga_pinjaman_kilat');
	}
	
}