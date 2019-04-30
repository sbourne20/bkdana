<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		//error_reporting(0);
		//error_reporting(E_ALL);
		//ini_set('display_errors', 1);
		$this->load->model('Product_model');

		$this->load->model('User_model');
	}

	function index()
	{
		$this->User_model->has_login();
		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';

		$mainData['bottom_js'] .= add_js('js/data/product.js');

		$mainData['mainContent'] = $this->load->view('product/vproduct_list', NULL, TRUE);

		$this->load->view('vbase', $mainData);
	}

	function json()
	{
		$data = $this->Product_model->get_product_dt();
		print_r($data);
	}

	public function add()
	{
		$this->User_model->has_login();

		$output['mode'] = 1; // sbg tanda add new
		$output['EDIT'] = NULL;

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{
			$output['top_css']   ="";
			$output['top_js']    ="";
			$output['bottom_js'] ="";

			$output['top_css']   .= add_css("plugins/fileinput/fileinput.min.css");
			$output['top_css']   .= add_css("plugins/jquery-tags-input/dist/bootstrap-tagsinput.css");
			$output['top_css']   .= add_css("plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css");
			$output['top_css']   .= add_css("plugins/bootstrap-timepicker/bootstrap-timepicker.css");
			$output['top_css']   .= add_css("plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css");

			$output['top_js']    .= add_js("plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js");
			$output['top_js']    .= add_js("plugins/bootstrap-timepicker/bootstrap-timepicker.js");
			$output['top_js']    .= add_js('plugins/ckeditor/ckeditor.js');
			$output['top_js']    .= add_js("plugins/autonumeric/autoNumeric.js");
			$output['top_js']    .= add_js("plugins/fileinput/fileinput.min.js");
			$output['top_js']    .= add_js('plugins/jquery-tags-input/dist/bootstrap-tagsinput.js');
			$output['top_js']    .= add_js("plugins/friendurl/jquery.friendurl.min.js");
			$output['top_js']    .= add_js("plugins/bootstrap-switch/js/bootstrap-switch.min.js");
			$output['top_js']    .= add_js("plugins/numeric/jquery.numeric.min.js");
			
			$output['bottom_js'] .= add_js('js/select2-data.js');
			$output['bottom_js'] .= add_js('js/global.js');

			$output['business'] = $this->Product_model->get_typeofbusiness();
			
			$mainData['mainContent'] = $this->load->view('product/vproduct_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$split_bt = explode('==', trim($post['type_bisnis']));

			//tambahan baru
	/*		$split_bt1 = explode('==', trim($post['type_interest_rate']));

			$type_business_id   = $split_bt[0];
			$type_interest_rate = $split_bt[1];*/

			//batas tammbahan baru

			$type_business_id   = $split_bt[0];
			$type_business_slug = $split_bt[1];

			$data['Type_of_business']   = $type_business_slug;
			$data['Fundraising_period'] = trim($post['fundraising_period']);
			$data['Product_sector']     = trim($post['product_sector']);
			$data['Interest_rate']      = trim($post['interest_rate']);
			$data['Loan_term']          = trim($post['loan_term']);
			$data['Max_loan']           = trim($post['max_loan']);
			$data['Platform_rate']      = trim($post['platform_rate']);
			$data['Loan_organizer']     = trim($post['loan_organizer']);
			$data['Investor_return']    = trim($post['investor_return']);
			$data['Fee_revenue_share']  = trim($post['fee_revenue_share']);
			$data['Secured_loan_fee']   = trim($post['secured_loan_fee']);			
			$data['product_title']      = trim($post['title']);
			$data['product_status']     = trim($post['prod_status']);
			$data['type_of_business_id']   = $type_business_id;
			$data['type_of_interest_rate'] = trim($post['type_interest_rate']);
			$data['PPH']                   = trim($post['pajak']);
			$data['charge']                = trim($post['charge']);
			$data['additional_charge']	   = trim($post['additional_charge']);
			$data['charge_type']           = trim($post['charge_type']);
			$data['additional_charge_type']           = trim($post['additional_charge_type']);
			//agri
			$data['Min_loan']			= trim($post['min_loan']);
			$data['Min_tenor']			= trim($post['min_tenor']);
			$data['Max_tenor']			= trim($post['max_tenor']);
			//agri
			//$data['type_of_interest_rate_name'] = trim($post['type_interest_rate']);
			
			//if(isset($_POST['submit'])){
			$radioVal = trim($post["type_interest_rate"]);
			switch($radioVal){
			case '1':
			$data['type_of_interest_rate_name'] = 'hari';  
			break;
			case '2':
			$data['type_of_interest_rate_name'] = 'bulan'; 
			break; 
			case '3':
			$data['type_of_interest_rate_name'] = 'minggu'; 
			break;  
			}
			//}
			
			$insertID = $this->Product_model->insert_($data);

			if ($insertID){

				$this->session->set_userdata('message','New product has been added.');
				$this->session->set_userdata('message_type','success');
				redirect('product');
			}
		}
	}

	function validation()
	{
		$this->form_validation->set_rules('type_bisnis', 'type of business', 'trim|required');
		$this->form_validation->set_rules('fundraising_period', 'fundraising_period', 'trim|required');

		$this->form_validation->set_message('required', '%s harus diisi.');
	}

	public function edit()
	{
		$this->User_model->has_login();

		$id             = $this->uri->segment(3);
		$output['mode'] = 2; // sbg tanda edit
		$output['EDIT'] = $this->Product_model->get_product_by($id);

		$this->validation();
		if ($this->form_validation->run() == FALSE)
		{
			$output['top_css']   ="";
			$output['top_js']    ="";
			$output['bottom_js'] ="";

			$output['top_css']   .= add_css("plugins/fileinput/fileinput.min.css");
			$output['top_css']   .= add_css("plugins/jquery-tags-input/dist/bootstrap-tagsinput.css");
			$output['top_css']   .= add_css("plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css");
			$output['top_css']   .= add_css("plugins/bootstrap-timepicker/bootstrap-timepicker.css");
			$output['top_css']   .= add_css("plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css");
			
			$output['top_js']    .= add_js("plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js");
			$output['top_js']    .= add_js("plugins/bootstrap-timepicker/bootstrap-timepicker.js");
			$output['top_js']    .= add_js('plugins/ckeditor/ckeditor.js');
			$output['top_js']    .= add_js("plugins/autonumeric/autoNumeric.js");
			$output['top_js']    .= add_js("plugins/fileinput/fileinput.min.js");
			$output['top_js']    .= add_js('plugins/jquery-tags-input/dist/bootstrap-tagsinput.js');
			$output['top_js']    .= add_js("plugins/friendurl/jquery.friendurl.min.js");
			$output['top_js']    .= add_js("plugins/bootstrap-switch/js/bootstrap-switch.min.js");
			$output['top_js']    .= add_js("plugins/numeric/jquery.numeric.min.js");
			
			$output['bottom_js'] .= add_js('js/select2-data.js');
			$output['bottom_js'] .= add_js('js/global.js');

			$output['business'] = $this->Product_model->get_typeofbusiness();
			
			$mainData['mainContent'] = $this->load->view('product/vproduct_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post();

			$split_bt = explode('==', trim($post['type_bisnis']));
			$type_business_id   = $split_bt[0];
			$type_business_slug = $split_bt[1];

			$updata['Type_of_business']   = $type_business_slug;
			$updata['Fundraising_period'] = trim($post['fundraising_period']);
			$updata['Product_sector']     = trim($post['product_sector']);
			$updata['Interest_rate']      = trim($post['interest_rate']);
			$updata['Loan_term']          = trim($post['loan_term']);
			$updata['Max_loan']           = trim($post['max_loan']);
			$updata['Platform_rate']      = trim($post['platform_rate']);
			$updata['Loan_organizer']     = trim($post['loan_organizer']);
			$updata['Investor_return']    = trim($post['investor_return']);
			$updata['Fee_revenue_share']  = trim($post['fee_revenue_share']);
			$updata['Secured_loan_fee']   = trim($post['secured_loan_fee']);			
			$updata['product_title']      = trim($post['title']);
			$updata['product_status']     = trim($post['prod_status']);
			$updata['type_of_business_id']   = $type_business_id;
			$updata['type_of_interest_rate'] = trim($post['type_interest_rate']);
			$updata['PPH']                   = trim($post['pajak']);
			$updata['charge']                = trim($post['charge']);
			$updata['additional_charge']	   = trim($post['additional_charge']);
			$updata['charge_type']           = trim($post['charge_type']);
			$updata['additional_charge_type']= trim($post['additional_charge_type']);
			//agri
			$updata['Min_loan']				= trim($post['min_loan']);
			$updata['Min_tenor']			= trim($post['min_tenor']);
			$updata['Max_tenor']			= trim($post['max_tenor']);
			//agri
			
			$radioVal = trim($post["type_interest_rate"]);
			switch($radioVal){
			case '1':
			$updata['type_of_interest_rate_name'] = 'hari';  
			break;
			case '2':
			$updata['type_of_interest_rate_name'] = 'bulan'; 
			break; 
			case '3':
			$updata['type_of_interest_rate_name'] = 'minggu'; 
			break;  
			}

			$affected = $this->Product_model->update_product($updata, $id);
			
			$this->session->set_userdata('message','The Data has been Updated.');
			$this->session->set_userdata('message_type','success');
			redirect('product');
			
		}
	}

	function delete()
	{
		$this->User_model->has_login();
		
		$id = $this->uri->segment(3);

		$updata['product_status'] = 2; // trash
		$this->Product_model->update_product($updata, $id);

		$this->session->set_userdata('message','The Data has been moved to trash.');
		$this->session->set_userdata('message_type','success');
		redirect('product');
	}

	function detail()
	{
		$id = $this->uri->segment(3);
		$output['EDIT'] = $this->Product_model->get_product_by($id);

		$this->load->view('product/vdetail', $output);
	}

	/*function publish()
	{
		$this->User_model->has_login();
		
		$id = $this->uri->segment(3);
		$this->Product_model->publish($id);
		$this->session->set_userdata('message','The Collection published successfully.');
		$this->session->set_userdata('message_type','success');
		redirect('collection');
	}

	function export()
	{
		require_once APPPATH.'libraries/PHPExcel-1.8/Classes/PHPExcel.php';
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		$products = $this->Product_model->get_all_product();

		// Set Bold for first row
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

		// First row fill with TITLE
		$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A1', 'COLLECTION');
		$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B1', 'SIZE');
		$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C1', 'PRICE');
		$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D1', 'CATEGORY');
		$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('E1', 'WEIGHT');
		$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F1', 'STOCK');
		
		// Other Rows
		$i = 2;
		foreach ($products as $row) {
				$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$i, $row['title']);
				$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B'.$i, $row['size_name']);
				$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.$i, $row['price_idr']);
				$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D'.$i, $row['category_name']);
				$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('E'.$i, $row['weight']);
				$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F'.$i, $row['size_stock']);
		 	$i++;
		}

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("HAPPA CMS")
									 ->setLastModifiedBy("HAPPA CMS")
									 ->setTitle("Download Collection")
									 ->setSubject("Download Collection");
		

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Download Collection');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Download-Collection.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}*/

}

/* End of file collection.php */