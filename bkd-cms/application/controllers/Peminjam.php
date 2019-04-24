<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Peminjam extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Member_model');
		$this->load->model('Grade_model');
		$this->load->model('Wallet_model');
		
		 //error_reporting(E_ALL);
		 //ini_set('display_errors', '1');
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Member';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/peminjam.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mainData['mainContent']  = $this->load->view('member/vpeminjam', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->Member_model->get_member(1);
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

			$this->session->set_userdata('message','Peminjam telah Aktif.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','No Data selected.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('peminjam');
	}

	function deactivate()
	{
		$this->User_model->has_login();

		$id = antiInjection($this->uri->segment(3));

		if ($id)
		{
			$affected = $this->Member_model->set_member_status($id, 0);

			$this->session->set_userdata('message','Peminjam telah Aktif.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','No Data selected.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('peminjam');
	}

	function delete()
	{
		$this->User_model->has_login();

		$id = antiInjection($this->uri->segment(3));

		$getdata = $this->Member_model->get_user_ojk_bymember($id);

		//_d($getdata);

		$del = $this->Member_model->delete_member($id);
		if($id && $del){

			$this->Member_model->delete_user_ojk($id);
			$this->Member_model->delete_user_ojk_detail($getdata['Id_pengguna']);
			$this->Member_model->delete_profil_geografi($getdata['Id_pengguna']);
			$this->Wallet_model->delete_master_wallet($getdata['Id_pengguna']);
			$this->Wallet_model->delete_detail_wallet($getdata['Id_pengguna']);

			$this->session->set_userdata('message','Data has been deleted.');
			$this->session->set_userdata('message_type','success');
		}else{
			$this->session->set_userdata('message','No Data was deleted.');
			$this->session->set_userdata('message_type','warning');
		}

		redirect('peminjam');
	}

	public function getgroupcount()
	{
		if(isset($_POST['memberid']))
		{
    		$usr = antiInjection($_POST['memberid']);
    		// Do whatever you want with the $uid
		}
		else{
			echo "tidak ada data";
		}

		//$output['id_grup'] = $usr;
		//batas
		//$idusergroup = antiInjection(trim($usergroup1['id_user_group']));
		//$usergroup2 = $usergroup1['id_user_group'];
		//$usergroup1 = $usergroup['id_user_group'];
		//$updata2['id_user_group'] = antiInjection(trim($post['membergroup']));
		//$updata1['id_user_group'] = antiInjection(trim($post['membergroup']));
		$countgroup = $this->Member_model->get_count_group($usr);
		//print_r($countgroup);
		//$output['countgroup1'] = $countgroup['itotal'];
		echo $countgroup['itotal'];
	}

	public function edit()
	{
		$this->User_model->has_login();

		$id             = antiInjection($this->uri->segment(3));
		$output['mode'] = 2; // sbg tanda edit
		$output['EDIT'] = $this->Member_model->get_usermember_by($id);
		$output['membergroup'] = $this->Member_model->get_allgroup();
		$output['usergroup'] = $this->Member_model->get_usergroup();
		$usergroup = $this->Member_model->get_usermember_by($id);
		
		
		$countgroup = $this->Member_model->get_count_group($usergroup['id_user_group']);
		$output['countgroup'] = $countgroup['itotal']; 
		

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

			$output['grade'] = $this->Grade_model->get_active_grade();
			$mainData['membergroup'] = $this->Member_model->get_allgroup();

			$mainData['mainContent'] = $this->load->view('member/vpeminjam_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
			$post = $this->input->post(NULL, TRUE);

			$countgroup = $this->Member_model->get_count_group(antiInjection(trim($post['membergroup'])));
			//if(trim($post['count']<='1'){
				//echo 'id'.$countgroup['itotal'];
				//return false;

				if (trim($post['grade']) != '' && !empty($id) )
				{
					$updata1['peringkat_pengguna'] = antiInjection(trim($post['grade']));
					$updata1['id_user_group'] = antiInjection(trim($post['membergroup']));
					//$updata2['id_user_group'] = antiInjection(trim($post['membergroup']));

					if(trim($countgroup['itotal'])>=10){
						//echo 'id'.$countgroup;
						//alert('Jumlah Member telah penuh');
						//echo 'id'.$countgroup['itotal'];
					
						$this->session->set_userdata('message','Max Member.');
						$this->session->set_userdata('message_type','warning');
						redirect('peminjam/edit/'.$id);
					}else{
						//echo('id else'.$countgroup['itotal']);
						

					$affected = $this->Member_model->update_user_ojk($updata1, $id);
					//$affected = $this->Member_model->update_mod_ltpinjaman($updata2, $id);

					$this->session->set_userdata('message','Data has been updated.');
					$this->session->set_userdata('message_type','success');
					redirect('peminjam');
					}
					
					
					/*if($affected){

						$this->session->set_userdata('message','Data has been updated.');
						$this->session->set_userdata('message_type','success');
					}else{
						$this->session->set_userdata('message','No Update.');
						$this->session->set_userdata('message_type','warning');
					}*/
				}

			//}else{
			//	alert('Jumlah Member telah penuh');
			//}
			

			// else {

				//$post = $this->input->post(null, true);

				// $ID = $post['Id_pengguna'];

				// //$data['peringkat_pengguna'] = $post['grade'];
				// $data['id_user_group'] = $post['membergroup'];
				
				// $update = $this->Member_model->update_user_group_ojk($data, $id);
				// echo "<script>console.log(".$update.");</script>";
				// if ($update){
					
				// 	$this->session->set_userdata('message','Data has been updated.');
				// 	$this->session->set_userdata('message_type','success');
				// }else{
				// 	$this->session->set_userdata('message',$update);
				// 	$this->session->set_userdata('message_type','warning');
				// }
			// }
			/*if (trim($post['membergroup']) != '' && !empty($id) )
			{
				$updata['id_user_group'] = antiInjection(trim($post['membergroup']));
				$affected = $this->Member_model->update_user_group_ojk($updata, $id);
				if($affected){

					$this->session->set_userdata('message','Data has been updated 123.');
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','No Update 345.');
					$this->session->set_userdata('message_type','warning');
				}
			}*/
			
			
			
		}
	}

	public function editprofil()
	{
		$this->User_model->has_login();

		$id             = antiInjection($this->uri->segment(3));
		$output['mode'] = 2; // sbg tanda edit
		$output['EDIT'] = $this->Member_model->get_usermember_by($id);
		$output['memberdata'] = $this->Member_model->get_usermember_by($id);
		$output['membergroup'] = $this->Member_model->get_allgroup();
		$output['usergroup'] = $this->Member_model->get_usergroup();
		$usergroup = $this->Member_model->get_usermember_by($id);
		
		// echo "UID ".$id;
		// exit();
		
		//$countgroup = $this->Member_model->get_count_group($usergroup['id_user_group']);
		//$output['countgroup'] = $countgroup['itotal']; 
		
		$this->validationprofil();
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

			//tambahan js dari bkd
			$output['top_css'] .= add_css('plugins/jsbkd/validationengine/validationEngine.jquery.css');
			$output['top_css'] .= add_css('plugins/jsbkd/bootstrap-datepicker/css/bootstrap-datepicker.css');
			$output['top_css'] .= add_css('plugins/jsbkd/alertify/css/alertify.min.css');
			$output['top_css'] .= add_css('plugins/jsbkd/alertify/css/themes/default.min.css');
			$output['top_css'] .= add_css("plugins/jsbkd/fileinput/fileinput.min.css");

			$output['bottom_js'] .= add_js('plugins/jsbkd/validationengine/languages/jquery.validationEngine-en.js');
			$output['bottom_js'] .= add_js('plugins/jsbkd/validationengine/jquery.validationEngine.js');
			$output['bottom_js'] .= add_js('plugins/jsbkd/bootstrap-datepicker/js/bootstrap-datepicker.min.js');
			$output['bottom_js'] .= add_js('plugins/jsbkd/jqueryvalidation/dist/jquery.validate.min.js');
			$output['bottom_js'] .= add_js('plugins/jsbkd/autoNumeric/autoNumeric.min.js');
			$output['bottom_js'] .= add_js('plugins/jsbkd/alertify/alertify.min.js');
			$output['bottom_js'] .= add_js("plugins/jsbkd/fileinput/plugins/piexif.min.js");
			$output['bottom_js'] .= add_js("plugins/jsbkd/fileinput/fileinput.min.js");
			$output['bottom_js'] .= add_js('plugins/jsbkd/validation-init.js');
			$output['bottom_js'] .= add_js('plugins/jsbkd/autoNumeric-init.js');
			$output['bottom_js'] .= add_js('plugins/jsbkd/date-init.js');
			$output['bottom_js'] .= add_js('plugins/jsbkd/fileinput-edit.js');
			$output['bottom_js'] .= add_js('plugins/jsbkd/dsn.js');
			$output['bottom_js'] .= add_js('plugins/jsbkd/exif-js-master/exif.js');
			$output['bottom_js'] .= add_js('plugins/jsbkd/ImageTools.js');
			//batas tambahan

			$output['grade'] = $this->Grade_model->get_active_grade();
			$mainData['membergroup'] = $this->Member_model->get_allgroup();

			$mainData['mainContent'] = $this->load->view('member/vprofilpeminjam_form', $output, TRUE);

			$this->load->view('vbase', $mainData);
		}else{
				$post = $this->input->post(NULL, TRUE);


				$memberdata = $this->Member_model->get_usermember_by($id);
				$logintype   = htmlentities($_SESSION['_bkdtype_']); 

				$tgl_lahir = date('Y-m-d', strtotime(trim($post['tgl_lahir'])));
				 // echo"fullname ".$post['fullname'];
				 // echo"telepon ".$post['telp'];
				 // exit();
				//echo "id ".$id;
				//exit();

				if (trim($post['fullname']) != '' && !empty($id) )
				{
					$userID = $memberdata['Id_pengguna'];
						
						$mem_data['mum_fullname']       = antiInjection(trim($post['fullname']));
						$mem_data['mum_email']      	= antiInjection(trim($post['email']));
						$mem_data['mum_telp']           = antiInjection(trim($post['telp']));
						$mem_data['mum_ktp']            = antiInjection(trim($post['nomor_ktp']));
						$mem_data['mum_nomor_rekening'] = antiInjection(trim($post['nomor_rekening']));

						$this->Member_model->update_member_byid($id, $mem_data);

						// user
						$user['Nama_pengguna']      = antiInjection(trim($post['fullname']));
						$user['Id_ktp']             = antiInjection(trim($post['nomor_ktp']));
						$user['Tempat_lahir']       = antiInjection(trim($post['tempat_lahir']));
						$user['Tanggal_lahir']      = $tgl_lahir;
						$user['Jenis_kelamin']      = antiInjection($post['gender']);
						$user['Pekerjaan']          = antiInjection($post['pekerjaan']);	// 1=PNS,2=BUMN, 3=Swasta, 4=wiraswasta, 5=lain-lain 
						$user['Nomor_rekening']     = antiInjection(trim($post['nomor_rekening']));
						$user['nama_bank']          = antiInjection(trim($post['nama_bank']));

						if (isset($post['pendidikan'])) {
							//return $post['pendidikan'];
							$user['Pendidikan']     = antiInjection(trim($post['pendidikan']));
						}

						$this->Member_model->update_user($id, $user);

						// user_detail
						$u_detail['Mobileno']          = antiInjection(trim($post['telp']));
						$u_detail['Occupation']        = antiInjection(trim($post['pekerjaan']));
						$u_detail['ID_type']           = 'KTP';
						$u_detail['ID_No']             = antiInjection(trim($post['nomor_ktp']));

						if (isset($post['usaha'])) {
							$u_detail['What_is_the_name_of_your_business']        = antiInjection(trim($post['usaha']));
						}

						if (isset($post['lama_usaha'])) {
							$u_detail['How_many_years_have_you_been_in_business'] = antiInjection(trim($post['lama_usaha']));
						}

						/*if (isset($post['pendidikan'])) {
							$u_detail['pendidikan'] = antiInjection(trim($post['pendidikan']));
						}*/
						if (isset($post['jumlah_penghasilan'])) {
							$filterh         = explode('.', trim($post['jumlah_penghasilan']));
							$jml_penghasilan = antiInjection(str_replace(',', '', $filterh[0]));
							$u_detail['average_monthly_salary'] = $jml_penghasilan;
						}
						if (isset($post['jml_tanggungan'])) {
							$u_detail['How_many_people_do_you_financially_support'] = antiInjection(trim($post['jml_tanggungan']));
						}					
						if (isset($post['status_kawin'])) {
							$u_detail['status_nikah']  = antiInjection(trim($post['status_kawin']));
						}
						//start of user detail update
						if (isset($post['nama_perusahaan'])) {
							$u_detail['nama_perusahaan']  = antiInjection(trim($post['nama_perusahaan']));
						}
						if (isset($post['telepon_perusahaan'])) {
							$u_detail['telepon_tempat_bekerja']  = antiInjection(trim($post['telepon_perusahaan']));
						}
						if (isset($post['status_karyawan'])) {
							$u_detail['status_karyawan']  = antiInjection(trim($post['status_karyawan']));
						}
						if (isset($post['lama_bekerja'])) {
							$u_detail['lama_bekerja']  = antiInjection(trim($post['lama_bekerja']));
						}
						if (isset($post['nama_atasan_langsung'])) {
							$u_detail['nama_atasan_langsung']  = antiInjection(trim($post['nama_atasan_langsung']));
						}
						if (isset($post['telp_atasan_langsung'])) {
							$u_detail['telp_atasan_langsung']  = antiInjection(trim($post['telp_atasan_langsung']));
						}
						if (isset($post['referensi_teman_1'])) {
							$u_detail['referensi_teman_1']  = antiInjection(trim($post['referensi_teman_1']));
						}
						if (isset($post['telp_teman_1'])) {
							$u_detail['telp_referensi_teman_1']  = antiInjection(trim($post['telp_teman_1']));
						}
						if (isset($post['referensi_teman_2'])) {
							$u_detail['referensi_teman_2']  = antiInjection(trim($post['referensi_teman_2']));
						}
						if (isset($post['telp_teman_2'])) {
							$u_detail['telp_referensi_teman_2']  = antiInjection(trim($post['telp_teman_2']));
						}
						if (isset($post['gaji_bulanan'])) {
							$u_detail['gaji_bulanan']  = antiInjection(trim($post['gaji_bulanan']));
						}
						if (isset($post['usia'])) {
							$u_detail['usia']  = antiInjection(trim($post['usia']));
						}
						if (isset($post['npwp'])) {
							$u_detail['npwp']  = antiInjection(trim($post['npwp']));
						}
						if (isset($post['deskripsi_usaha'])) {
							$u_detail['deskripsi_usaha']  = antiInjection(trim($post['deskripsi_usaha']));
						}
						if (isset($post['omzet_usaha'])) {
							$u_detail['omzet_usaha']  = antiInjection(trim($post['omzet_usaha']));
						}
						if (isset($post['modal_usaha'])) {
							$u_detail['modal_usaha']  = antiInjection(trim($post['modal_usaha']));
						}
						if (isset($post['margin_usaha'])) {
							$u_detail['margin_usaha']  = antiInjection(trim($post['margin_usaha']));
						}						
						if (isset($post['biaya_operasional'])) {
							$u_detail['biaya_operasional']  = antiInjection(trim($post['biaya_operasional']));
						}
						if (isset($post['laba_usaha'])) {
							$u_detail['laba_usaha']  = antiInjection(trim($post['laba_usaha']));
						}
						if (isset($post['bidang_pekerjaan'])) {
							$u_detail['bidang_pekerjaan']  = antiInjection(trim($post['bidang_pekerjaan']));
						}
						if (isset($post['jumlah_tanggungan'])) {
							$u_detail['How_many_people_do_you_financially_support']  = antiInjection(trim($post['jumlah_tanggungan']));
						}
						if (isset($post['status_tempat_tinggal'])) {
							$u_detail['status_tempat_tinggal']  = antiInjection(trim($post['status_tempat_tinggal']));
						}

						//end of user detail update

						$this->Member_model->update_userdetail($userID, $u_detail);
						
						// profile_geografi
						if($logintype=='2'){

							$u_geo['Alamat']      = antiInjection(trim($post['alamat']));
							$u_geo['Kodepos']     = antiInjection(trim($post['kodepos']));
							$u_geo['Kota']        = antiInjection(trim($post['kota']));
							$u_geo['Provinsi']    = antiInjection(trim($post['provinsi']));
						}else{

							if ($memberdata['mum_type_peminjam']=='3'){

							$u_geo['Alamat']      = antiInjection(trim($post['alamat']));
							$u_geo['Kodepos']     = antiInjection(trim($post['kodepos']));
							$u_geo['Kota']        = antiInjection(trim($post['kota']));
							$u_geo['Provinsi']    = antiInjection(trim($post['provinsi']));
							$u_geo['Agama']       = antiInjection(trim($post['agama']));
							$u_geo['Check_Domisili']	   = antiInjection(trim($post['checkdomisili']));
							}else{

							$u_geo['Alamat']      = antiInjection(trim($post['alamat']));
							$u_geo['Kodepos']     = antiInjection(trim($post['kodepos']));
							$u_geo['Kota']        = antiInjection(trim($post['kota']));
							$u_geo['Provinsi']    = antiInjection(trim($post['provinsi']));
							}
						}
							if ($memberdata['mum_type_peminjam']=='3'){
							if (antiInjection(trim($post['checkdomisili']))=='1'){
								$u_geo['Alamat_Domisili']      = antiInjection(trim($post['alamat']));
								$u_geo['Kodepos_Domisili']     = antiInjection(trim($post['kodepos']));
								$u_geo['Kota_Domisili']        = antiInjection(trim($post['kota']));
								$u_geo['Provinsi_Domisili']    = antiInjection(trim($post['provinsi']));
							}else{
								$u_geo['Alamat_Domisili']      = antiInjection(trim($post['alamatdomisili']));
								$u_geo['Kodepos_Domisili']     = antiInjection(trim($post['kodeposdomisili']));
								$u_geo['Kota_Domisili']        = antiInjection(trim($post['kotadomisili']));
								$u_geo['Provinsi_Domisili']    = antiInjection(trim($post['provinsidomisili']));
							}
							}			

						$this->Member_model->update_profil_geografi($userID, $u_geo);

					// if(trim($countgroup['itotal'])>=10){
					// 	//echo 'id'.$countgroup;
					// 	//alert('Jumlah Member telah penuh');
					// 	//echo 'id'.$countgroup['itotal'];
					
					// 	$this->session->set_userdata('message','Max Member.');
					// 	$this->session->set_userdata('message_type','warning');
					// 	redirect('peminjam/edit/'.$id);
					// }else{
						//echo('id else'.$countgroup['itotal']);
						

					//$affected = $this->Member_model->update_user_ojk($updata1, $id);
					//$affected = $this->Member_model->update_mod_ltpinjaman($updata2, $id);
					if ($memberdata['mum_type']=='1') {

					$this->session->set_userdata('message','Data has been updated.');
					$this->session->set_userdata('message_type','success');
					redirect('peminjam');
					}	
					else{
					$this->session->set_userdata('message','Data has been updated.');
					$this->session->set_userdata('message_type','success');
					redirect('pendana');
					}

					// else{

					// 	if ($memberdata['mum_type']=='1') {
					// 		$this->session->set_userdata('message','Data Gagal Update');
					// 		$this->session->set_userdata('message_type','error');
					// 		redirect('peminjam');
					// 	}else{
					// 		$this->session->set_userdata('message','Data Gagal Update');
					// 		$this->session->set_userdata('message_type','error');
					// 		redirect('pendana');
					// 	}
					// }
					//}
				}



			}			
		}

	function validation()
	{
		$this->form_validation->set_rules('grade', 'Grade', 'trim|required');

		$this->form_validation->set_message('required', '%s harus diisi.');
	}

	function validationprofil()
	{
		$this->form_validation->set_rules('fullname', 'Fullname', 'trim|required');

		$this->form_validation->set_message('required', '%s harus diisi.');
	}

	

}