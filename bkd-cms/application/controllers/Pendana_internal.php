<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendana_internal extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Member_model');
		$this->load->model('Grade_model');
		$this->load->model('Wallet_model');
		$this->load->model('Topup_model');
		
		// error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Member';
		$output['mode'] = 1;

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/pendana.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mid = $this->config->item('pendana_intern_memberid');
		$output['EDIT'] = $this->Member_model->get_usermember_by($mid);
		$output['saldo'] = $this->Wallet_model->get_saldo_bymember($mid);

		//_d($output['saldo']);

		$mainData['mainContent']  = $this->load->view('member/vpendana_internal', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->Member_model->get_member(2);
		print_r($data);
	}

	function detail()
	{
		$id = $this->input->post('id');

		$output['data'] = $this->Member_model->get_usermember_by($id);
		$this->load->view('member/vdetail', $output);
	}

	function activate()
	{
		$this->User_model->has_login();

		$id = antiInjection($this->uri->segment(3));

		if ($id)
		{
			$affected = $this->Member_model->set_member_status($id, 1);

			$this->session->set_userdata('message','Pendana telah Aktif.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','No Data selected.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('pendana');
	}

	public function edit()
	{
		$this->User_model->has_login();

		$id             = $this->config->item('pendana_intern_memberid');
		$output['mode'] = 2; // sbg tanda edit
		$output['EDIT'] = $this->Member_model->get_usermember_by($id);

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
			$output['bottom_js'] .= add_js('js/date-init.js');

			$output['grade'] = $this->Grade_model->get_active_grade();
			
			$mainData['mainContent'] = $this->load->view('member/vpendana_internal_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$filter2 = explode('.', trim($post['jumlah_penghasilan']));
			$jml_penghasilan = antiInjection(str_replace(',', '', $filter2[0]));

			//_d($_FILES);exit();

			if($_FILES['foto_file']['name'] == ''){
				$file_foto_name   = '';
			}else{
				$upload_foto = file_get_contents($_FILES['foto_file']['tmp_name']);
				// ----- Process Image Name -----
				$img_info          = pathinfo($_FILES['foto_file']['name']);
				$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
				$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
				$fileExt           = $img_info['extension'];
				$file_foto_name   = $fileName.'.'.$fileExt;
				// ----- END Process Image Name -----
				$u_detail['Profile_photo']    = $upload_foto;
				$u_detail['images_foto_name'] = $file_foto_name;
			
			}

			if($_FILES['ktp_file']['name'] == ''){
				$file_ktp_name   = '';
			}else{
				$upload_ktp  = file_get_contents($_FILES['ktp_file']['tmp_name']);
				// ----- Process Image Name -----
				$img_info          = pathinfo($_FILES['ktp_file']['name']);
				$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
				$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
				$fileExt           = $img_info['extension'];
				$file_ktp_name   = $fileName.'.'.$fileExt;
				// ----- END Process Image Name -----
				$u_detail['Photo_id']         = $upload_ktp;
				$u_detail['images_ktp_name']  = $file_ktp_name;
			}

			$mem_data['mum_fullname']    = trim($post['fullname']);
			$mem_data['mum_email']       = trim($post['email']);
			$mem_data['mum_telp']        = trim($post['telp']);
			//$mem_data['mum_ktp']         = antiInjection(trim($post['nomor_ktp']));
			$mem_data['mum_nomor_rekening'] = antiInjection(trim($post['nomor_rekening']));
			$this->Member_model->update_member($mem_data, $id);

			$user['Nama_pengguna']      = $mem_data['mum_fullname'];
			$user['Jenis_pengguna']     = 2; // 1.orang, 2.badan hukum
			$user['Id_ktp']             = antiInjection(trim($post['nomor_ktp']));
			$user['Tempat_lahir']       = antiInjection(trim($post['tempat_lahir']));
			$user['Tanggal_lahir']      = date('Y-m-d', strtotime(antiInjection(trim($post['tgl_lahir']))));
			$user['Jenis_kelamin']      = antiInjection($post['gender']);
			$user['Pendidikan']         = antiInjection($post['pendidikan']);
			$user['Pekerjaan']          = antiInjection($post['pekerjaan']);	// 1=PNS,2=BUMN, 3=Swasta, 4=wiraswasta, 5=lain-lain 
			$user['Nomor_rekening']     = antiInjection(trim($post['nomor_rekening']));
			$user['nama_bank']          = trim($post['nama_bank']);
			$this->Member_model->update_user_ojk($user, $id);

			$u_detail['Mobileno']          = $mem_data['mum_telp'];
			$u_detail['Occupation']        = antiInjection($post['pekerjaan']);
			$u_detail['Highest_Education'] = antiInjection($post['pendidikan']);
			$u_detail['average_monthly_salary'] = $jml_penghasilan;
			$u_detail['ID_type']           = 'KTP';
			$u_detail['ID_No']             = antiInjection(trim($post['nomor_ktp']));
			$u_detail['How_many_people_do_you_financially_support'] = antiInjection(trim($post['jml_tanggungan']));	
			$u_detail['status_nikah']                               = antiInjection(trim($post['status_kawin']));
			$this->Member_model->update_user_ojkdetail($u_detail, $output['EDIT']['Id_pengguna']);

			// profile_geografi
			//$u_geo['Agama']       = NULL;
			$u_geo['Alamat']      = antiInjection(trim($post['alamat']));
			$u_geo['Kodepos']     = antiInjection(trim($post['kodepos']));
			$u_geo['Kota']        = antiInjection(trim($post['kota']));
			$u_geo['Provinsi']    = antiInjection(trim($post['provinsi']));
			
			$this->Member_model->update_profil_geografi($u_geo, $output['EDIT']['Id_pengguna']);

			$userID = $output['EDIT']['Id_pengguna'];
			$old_fotoname = $output['EDIT']['images_foto_name'];
			$old_ktpname  = $output['EDIT']['images_ktp_name'];
			// ------- Upload Image file --------
			$destination_foto = $this->config->item('pendana_images_dir'). $userID."/foto/";
			$destination_ktp  = $this->config->item('pendana_images_dir'). $userID."/ktp/";
			
			if($_FILES['foto_file']['name'] != ''){
				if (!is_file($destination_foto.$file_foto_name)) {
					mkdir_r($destination_foto);
				}
				unlink($destination_foto.$old_fotoname);
				move_uploaded_file($_FILES['foto_file']['tmp_name'], $destination_foto.$file_foto_name);
			}

			if($_FILES['ktp_file']['name'] != ''){
				if (!is_file($destination_ktp.$file_ktp_name)) {
					mkdir_r($destination_ktp);
				}
				unlink($destination_ktp.$old_ktpname);
				move_uploaded_file($_FILES['ktp_file']['tmp_name'], $destination_ktp.$file_ktp_name);
			}
			
			$this->session->set_userdata('message','Data has been updated.');
			$this->session->set_userdata('message_type','success');
			
			/*$this->session->set_userdata('message','No Update.');
				$this->session->set_userdata('message_type','warning');*/
			
			redirect('pendana_internal');
		}
	}

	function validation()
	{
		$this->form_validation->set_rules('fullname', 'fullname', 'trim|required');
		$this->form_validation->set_rules('email', 'email', 'trim|required');
		$this->form_validation->set_rules('telp', 'telp', 'trim|required');
		$this->form_validation->set_rules('nomor_rekening', 'nomor_rekening', 'trim|required');
		$this->form_validation->set_rules('nomor_ktp', 'nomor_ktp', 'trim|required');

		$this->form_validation->set_message('required', '%s harus diisi.');
	}

	function validation_topup()
	{
		$this->form_validation->set_rules('account_bank_number', 'account_bank_number', 'trim|required');
		$this->form_validation->set_rules('my_bank_name', 'Bank_name', 'trim|required');
		$this->form_validation->set_rules('jml_topup', 'jml_topup', 'trim|required');
		$this->form_validation->set_rules('bank_destination', 'bank_destination', 'trim|required');
		$this->form_validation->set_message('required', '%s harus diisi.');
	}

	function topup()
	{
		$this->User_model->has_login();

		$id             = $this->config->item('pendana_intern_memberid');
		$output['mode'] = 2; // sbg tanda edit

		$this->validation_topup();
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
			
			$output['bottom_js'] .= add_js('js/autoNumeric-init.js');
			$output['bottom_js'] .= add_js('js/select2-data.js');
			$output['bottom_js'] .= add_js('js/global.js');
			$output['bottom_js'] .= add_js('js/date-init.js');

			$output['EDIT'] = $this->Member_model->get_usermember_by($id);
			
			$mainData['mainContent'] = $this->load->view('member/vtopup', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$redir = $this->uri->segment(1);
			$post  = $this->input->post(NULL, TRUE);

			$uid = $post['memberID'];

			if (trim($uid) == '' OR empty($uid)) {
				redirect($redir);
				exit();
			}

			$prefixID    = 'TU-';
			$orderID     = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,10));
	        $exist_order = $this->Topup_model->check_topup_code($orderID);	// Cek if order ID exist on Database
			
			// jika order ID sudah ada di Database, generate lagi tambahkan datetime
			if (is_array($exist_order) && count($exist_order) > 0 )
			{
				$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
			}

			$memberdata  = $this->Member_model->get_member_byid($uid);

			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');

			$akun_bank_name   = trim($post['account_bank_name']);
			$no_rekening      = trim($post['account_bank_number']);
			$my_bank_name     = trim($post['my_bank_name']);
			$bank_destination = trim($post['bank_destination']);
			$jml_topup        = trim($post['jml_topup']);

			if ($no_rekening != '' && strlen($no_rekening)>5 && $my_bank_name != '' && $bank_destination != '' &&  $jml_topup!= '' && strlen($jml_topup) >= 3 )
			{

				$filter = explode('.', $jml_topup);
				$total_topup = str_replace(',', '', $filter[0]);

				$indata['kode_top_up']             = $orderID;
				$indata['member_id']               = antiInjection($uid);
				$indata['user_id']                 = antiInjection($post['Id_pengguna']);
				$indata['nama_rekening_pengirim']  = antiInjection($akun_bank_name);
				$indata['nomor_rekening_pengirim'] = antiInjection($no_rekening);
				$indata['bank_pengirim']           = antiInjection($my_bank_name);
				$indata['bank_tujuan']             = antiInjection($bank_destination);
				$indata['jml_top_up']              = antiInjection($total_topup);
				$indata['tipe_top_up']             = 1;  // 1. transfer, 2.virtual account
				$indata['tgl_top_up']              = $nowdatetime;
				$indata['status_top_up']           = 'pending';

				$topupID = $this->Topup_model->insert_top_up($indata);

				if ($topupID) {
					$this->session->set_userdata('message','Sukses Top Up');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','Error on Top Up!');
					$this->session->set_userdata('message_type','error');
				}
			}else{
				$this->session->set_userdata('message','Top Up gagal. Isilah semua kolom dengan benar.');
				$this->session->set_userdata('message_type','error');
			}

			redirect($redir);
		}
	}

	
}