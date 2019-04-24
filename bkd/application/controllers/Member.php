<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		//error_reporting(E_ALL);
	}

	function base64_to_jpeg($base64_string, $output_file) {
	    // open the output file for writing
	    $ifp = fopen( $output_file, 'wb' ); 

	    // split the string on commas
	    // $data[ 0 ] == "data:image/png;base64"
	    // $data[ 1 ] == <actual base64 string>
	    $data = explode( ',', $base64_string );

	    // we could add validation here with ensuring count( $data ) > 1
	    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

	    // clean up the file resource
	    fclose( $ifp ); 
	    //print_r($data);
	    //exit();
	    return base64_decode( $output_file ); 
	}
	
		public function ubah_password()
	{
		//error_reporting(1);
		$this->Content_model->has_login();

		if ($this->uri->segment(1) == 'ubah-password') {
			$data['page_title'] = 'Ubah Password';
		}else{
			$data['page_title'] = 'Halaman Ubah Password';			
		}

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['top_css'] .= add_css('js/bootstrap-datepicker/css/bootstrap-datepicker.css');
		$data['top_css'] .= add_css('js/alertify/css/alertify.min.css');
		$data['top_css'] .= add_css('js/alertify/css/themes/default.min.css');
		$data['top_css'] .= add_css("js/fileinput/fileinput.min.css");

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/bootstrap-datepicker/js/bootstrap-datepicker.min.js');
		$data['bottom_js'] .= add_js('js/jqueryvalidation/dist/jquery.validate.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/alertify/alertify.min.js');
		$data['bottom_js'] .= add_js("js/fileinput/plugins/piexif.min.js");
		$data['bottom_js'] .= add_js("js/fileinput/fileinput.min.js");
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/date-init.js');
		$data['bottom_js'] .= add_js('js/fileinput-edit.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/exif-js-master/exif.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		//$data['bottom_js'] .= add_js('js/ImageTools.js');


		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		
		$uid = htmlentities($_SESSION['_bkduser_']);
		$data['memberid']  = $uid;

		$data['memberdata'] 	= $this->Member_model->user_alldata($uid);
		$data['total_pinjaman'] = $this->Content_model->get_jml_pinjam($uid);
		$data['total_invest']   = $this->Content_model->get_jml_invest($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		//_d($data['memberdata']);
		$data['pages']    = 'v_ganti_password';
		$this->load->view('template', $data);
	}

		function submit_ubah_password()
	{

		if($_SERVER["REQUEST_METHOD"] == "POST")
		{	
			$this->load->library('encryption');

			$post = $this->input->post(NULL, TRUE);

			$uid        		= antiInjection(htmlentities(trim($_SESSION['_bkduser_'])));
			$logintype  		= htmlentities($_SESSION['_bkdtype_']); 
			$oldpassword        = trim($post['oldpassword']);
			$newpassword        = trim($post['newpassword']);
			$confirmpassword    = trim($post['confirmpassword']);
			


			$getoldpassword 	= $this->Member_model->user_alldata($uid);

			$verify = password_verify(base64_encode(hash('sha256', $oldpassword, true)), $getoldpassword['mum_password']);

			if($verify==1){
				if (antiInjection($newpassword) == antiInjection($confirmpassword) && strlen($newpassword) >= 6 ) 
				{
			
					$userID    = antiInjection(htmlentities(trim($_SESSION['_bkduser_'])));

					if (!empty($userID))
					{

							$newpass = password_hash(base64_encode(hash('sha256', ($newpassword), true)), PASSWORD_DEFAULT);
				            $updated = $this->Member_model->update_password_member($newpass, $userID);

				            if ($updated) {
				            	$this->session->set_userdata('message','Sukses ubah password');
								$this->session->set_userdata('message_type','success');
				            	redirect('dashboard');
					            exit();
				            }else{
				            	$this->session->set_userdata('message','Gagal ubah password');
								$this->session->set_userdata('message_type','error');
				            	redirect('ubah-password');
					            exit();
				            }
		       		//}

					}else{
						
						$this->session->set_userdata('message','Gagal ubah password');
						$this->session->set_userdata('message_type','error');
						redirect('ubah-password');
			            exit();
					}
				}
			}else{

				$this->session->set_userdata('message','Pasword lama tidak cocok.');
			 	$this->session->set_userdata('message_type','error');
			 	redirect('ubah-password');
				exit();
			}

			if ($newpassword == '' OR $confirmpassword == '' OR $newpassword != $confirmpassword)
			{
				$this->session->set_userdata('message','New Password dan Re-type New Password tidak sama.');
				$this->session->set_userdata('message_type','error');
				redirect('ubah-password');
				exit();

			}else if (strlen($newpassword) < 6)
			{
				$this->session->set_userdata('message', 'isi Password minimal 6 karakter.');
				redirect('ubah-password');
				exit();
			}
			else if(preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $newpassword) === 0) 
			{
				$this->session->set_userdata('message','Password harus terdiri dari huruf dan angka, serta minimal 1 huruf besar');
				redirect('ubah-password');
			}		
		}	
	}

	public function ubah_profil()
	{
		//error_reporting(1);
		$this->Content_model->has_login();

		if ($this->uri->segment(1) == 'ubah-profil') {
			$data['page_title'] = 'Ubah Profil';
		}else{
			$data['page_title'] = 'Lengkapi Data Profil';			
		}

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['top_css'] .= add_css('js/bootstrap-datepicker/css/bootstrap-datepicker.css');
		$data['top_css'] .= add_css('js/alertify/css/alertify.min.css');
		$data['top_css'] .= add_css('js/alertify/css/themes/default.min.css');
		$data['top_css'] .= add_css("js/fileinput/fileinput.min.css");

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/bootstrap-datepicker/js/bootstrap-datepicker.min.js');
		$data['bottom_js'] .= add_js('js/jqueryvalidation/dist/jquery.validate.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/alertify/alertify.min.js');
		$data['bottom_js'] .= add_js("js/fileinput/plugins/piexif.min.js");
		$data['bottom_js'] .= add_js("js/fileinput/fileinput.min.js");
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/date-init.js');
		$data['bottom_js'] .= add_js('js/fileinput-edit.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/exif-js-master/exif.js');
		$data['bottom_js'] .= add_js('js/ImageTools.js');
		$data['bottom_js'] .= add_js('js/profile_edit.js');


		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		
		$uid = htmlentities($_SESSION['_bkduser_']);
		$data['memberid']  = $uid;

		$data['memberdata'] = $this->Member_model->user_alldata($uid);
		$data['total_pinjaman'] = $this->Content_model->get_jml_pinjam($uid);
		$data['total_invest']   = $this->Content_model->get_jml_invest($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);
		$data['provinsi'] = $this->Content_model->get_all_province();
		$data['kota'] = $this->Content_model->get_all_cities();
		$data['pendidikan'] = $this->Content_model->get_all_pendidikan();
		$data['agama'] = $this->Content_model->get_all_agama();
		$data['pekerjaan'] = $this->Content_model->get_all_pekerjaan();
		$data['nama_bank'] = $this->Content_model->get_all_bank();
		$data['status_tempat_tinggal'] = $this->Content_model->get_all_status_tempat();
		$data['gender'] = $this->Content_model->get_all_gender();

		//_d($data['memberdata']);
		$data['pages']    = 'v_profil_edit';
		$this->load->view('template', $data);
	}

	function kota_provinsi()
	{
		if(isset($_POST['Option_key']))
		{
			$usr = $_POST['Option_key'];
			// echo $usr;
		}else{
			echo "no data";
			exit();
		}

		$kota = $this->Content_model->getkota($usr);

		$html = '';
		foreach ($kota as $prod) {
			$html .= '<option value="'.$prod['Option_value'].'">'.$prod['Option_label'].' </option>';
	}
	echo $html;
	}

	function submit_ubah_profil()
	{
		$score = 0;
		//$this->Content_model->has_login();
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$post = $this->input->post(NULL, TRUE);

			if (trim($post['fullname']) != '' && trim($post['telp']) != '' )
			{
				$uid         = antiInjection(htmlentities(trim($_SESSION['_bkduser_'])));
				$logintype   = htmlentities($_SESSION['_bkdtype_']); 
				$notelp      = trim($post['telp']);
				$kota        = trim($post['kota']);
				$provinsi    = trim($post['provinsi']);

				if ($uid && $uid != ''
					&& trim($post['fullname']) != ''
					&& trim($post['nomor_ktp']) != ''
					&& trim($post['nomor_rekening']) != ''
					&& trim($post['nama_bank']) != ''
					//&& trim($post['tempat_lahir']) != ''
					//&& trim($post['tgl_lahir']) != ''
					&& trim($post['gender']) != ''
					&& trim($post['pekerjaan']) != ''
					&& trim($post['alamat']) != ''
					//&& trim($post['kodepos']) != ''
					&& trim($post['kota']) != ''
					&& trim($post['provinsi']) != ''

				) {
					$memberdata = $this->Member_model->get_member_byid($uid);

					if (is_array($memberdata) && count($memberdata)>0 && isset($memberdata['Id_pengguna'])) {

						// hitung usia
						$tgl_lahir = date('Y-m-d', strtotime(trim($post['tgl_lahir'])));
						$biday = new DateTime($tgl_lahir);
						$today = new DateTime();
						$diff = $today->diff($biday);
						$usia = $diff->y;
						
						if ($usia >= 20 && $usia <= 30 ) {
							$score = $score + 30;
						}else if ($usia >= 31 && $usia <= 40 ) {
							$score = $score + 50;
						}else if ($usia >= 41 && $usia <= 50 ) {
							$score = $score + 40;
						}else if ($usia >= 51 && $usia <= 60 ) {
							$score = $score + 20;
						}else if ($usia >= 61 ) {
							$score = $score + 10;
						}

						//  jakarta nilai 80, Botabek 60, non jabotabek 40
						$kota    = strtolower($kota);
						//$botabek = 'bogor tangerang bekasi';

						if ( $provinsi == 'DKI Jakarta')
						{
							$score = $score + 80;
						}else {
							if ( strpos($kota, 'bogor') !== false OR strpos($kota, 'tangerang') !== false OR strpos($kota, 'bekasi') !== false  ) {
								$score = $score + 60;
							}else{
								$score = $score + 40;
							}
						}

						$userID = $memberdata['Id_pengguna'];

						$destination_foto = $this->config->item('member_images_dir'). $userID."/foto/";
						$destination_ktp  = $this->config->item('member_images_dir'). $userID."/ktp/";
						$destination_usaha = $this->config->item('member_images_dir'). $userID."/usaha/";
						$destination_usaha2 = $this->config->item('member_images_dir'). $userID."/usaha2/";
						$destination_usaha3 = $this->config->item('member_images_dir'). $userID."/usaha3/";
						$destination_usaha4 = $this->config->item('member_images_dir'). $userID."/usaha4/";
						$destination_usaha5 = $this->config->item('member_images_dir'). $userID."/usaha5/";
						$destination_surat_keterangan_bekerja = $this->config->item('member_images_dir'). $userID."/surat_keterangan_bekerja/";
						$destination_slip_gaji = $this->config->item('member_images_dir'). $userID."/slip_gaji/";
						$destination_pegang_ktp = $this->config->item('member_images_dir'). $userID."/pegang_ktp/";

						if($post['foto_file_hidden']!=''){	
							if (!is_file($destination_foto)) {
								mkdir_r($destination_foto);
							}
							if($post['old_foto']!=''){
								if (is_file($destination_foto.$post['old_foto'])){
									unlink($destination_foto.$post['old_foto']);
								}
							}
							$data = $_POST['foto_file_hidden'];
							$splited = explode(',', substr( $data , 5 ) , 2);
							$mime=$splited[0];
							$data=$splited[1];

							$mime_split_without_base64=explode(';', $mime,2);
							$mime_split=explode('/', $mime_split_without_base64[0],2);
							if(count($mime_split)==2)
							{
								$extension=$mime_split[1];
								if($extension=='jpeg')$extension='jpg';
								//if($extension=='javascript')$extension='js';
								//if($extension=='text')$extension='txt';
								$output_file_with_extension=rand().'.'.$extension;
							}

							$data = base64_decode($data);
							$file = $destination_foto.$output_file_with_extension;
							$success = file_put_contents($file, $data);
							$u_detail['images_foto_name']  = $output_file_with_extension;
						}
						
						/*if($_FILES['foto_file']['name'] == ''){
							$file_foto_name   = '';
						}else{
							//$upload_foto = file_get_contents($_FILES['foto_file']['tmp_name']);
							// ----- Process Image Name -----
							$img_info          = pathinfo($_FILES['foto_file']['name']);
							$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
							$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
							$fileExt           = $img_info['extension'];
							$file_foto_name   = $fileName.'.'.$fileExt;
							// ----- END Process Image Name -----
							//$u_detail['Profile_photo']    = $upload_foto;
							$u_detail['images_foto_name'] = $file_foto_name;
						
						}*/

						if($post['ktp_file_hidden']!=''){
							if (!is_file($destination_ktp)) {
								mkdir_r($destination_ktp);
							}	
							if($post['old_ktp']!=''){
								if (is_file($destination_ktp.$post['old_ktp'])){
									unlink($destination_ktp.$post['old_ktp']);
								}
							}
							$data = $_POST['ktp_file_hidden'];
							$splited = explode(',', substr( $data , 5 ) , 2);
							$mime=$splited[0];
							$data=$splited[1];

							$mime_split_without_base64=explode(';', $mime,2);
							$mime_split=explode('/', $mime_split_without_base64[0],2);
							if(count($mime_split)==2)
							{
								$extension=$mime_split[1];
								if($extension=='jpeg')$extension='jpg';
								//if($extension=='javascript')$extension='js';
								//if($extension=='text')$extension='txt';
								$output_file_with_extension=rand().'.'.$extension;
							}

							$data = base64_decode($data);
							$file = $destination_ktp.$output_file_with_extension;
							$success = file_put_contents($file, $data);
							$u_detail['images_ktp_name']  = $output_file_with_extension;
						}


						if ($memberdata['mum_type_peminjam']=='2'){//tambahan baru pengkondisian

							if($post['usaha_file_hidden']!=''){	
								if (!is_file($destination_usaha)) {
									mkdir_r($destination_usaha);
								}	
								if($post['old_usaha']!=''){
									if (is_file($destination_usaha.$post['old_usaha'])){
										unlink($destination_usaha.$post['old_usaha']);
									}
								}
								$data = $_POST['usaha_file_hidden'];
								$splited = explode(',', substr( $data , 5 ) , 2);
								$mime=$splited[0];
							    $data=$splited[1];

							    $mime_split_without_base64=explode(';', $mime,2);
							    $mime_split=explode('/', $mime_split_without_base64[0],2);
							    if(count($mime_split)==2)
							    {
							        $extension=$mime_split[1];
							        if($extension=='jpeg')$extension='jpg';
							        //if($extension=='javascript')$extension='js';
							        //if($extension=='text')$extension='txt';
							        $output_file_with_extension=rand().'.'.$extension;
							    }

								$data = base64_decode($data);
								$file = $destination_usaha.$output_file_with_extension;
								$success = file_put_contents($file, $data);
								$u_detail['images_usaha_name']  = $output_file_with_extension;
							}

							/*if( isset($_FILES['usaha_file']['name']) && $_FILES['usaha_file']['name'] == ''){
								$file_usaha_name   = '';
							}else{
								//$upload_usaha = file_get_contents($_FILES['usaha_file']['tmp_name']);
								// ----- Process Image Name -----
								$img_info          = pathinfo($_FILES['usaha_file']['name']);
								$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
								$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
								$fileExt           = $img_info['extension'];
								$file_usaha_name   = $fileName.'.'.$fileExt;
								// ----- END Process Image Name -----
								//$u_detail['Photo_business_location'] = $upload_usaha;
								//$u_detail['foto_usaha']              = $upload_usaha;
								$u_detail['images_usaha_name']       = $file_usaha_name;
							}*/


							if($post['usaha_file2_hidden']!=''){
								if (!is_file($destination_usaha2)) {
									mkdir_r($destination_usaha2);
								}	
								if($post['old_usaha2']!=''){
									if (is_file($destination_usaha2.$post['old_usaha2'])){
										unlink($destination_usaha2.$post['old_usaha2']);
									}
								}
								$data = $_POST['usaha_file2_hidden'];
								$splited = explode(',', substr( $data , 5 ) , 2);
								$mime=$splited[0];
							    $data=$splited[1];

							    $mime_split_without_base64=explode(';', $mime,2);
							    $mime_split=explode('/', $mime_split_without_base64[0],2);
							    if(count($mime_split)==2)
							    {
							        $extension=$mime_split[1];
							        if($extension=='jpeg')$extension='jpg';
							        //if($extension=='javascript')$extension='js';
							        //if($extension=='text')$extension='txt';
							        $output_file_with_extension=rand().'.'.$extension;
							    }

								$data = base64_decode($data);
								$file = $destination_usaha2.$output_file_with_extension;
								$success = file_put_contents($file, $data);
								$u_detail['images_usaha_name2']  = $output_file_with_extension;
							}


							/* if( isset($_FILES['usaha_file2']['name']) && 	$_FILES['usaha_file2']['name'] == ''){
								$file_usaha_name2   = '';
							}else{
								//$upload_usaha = file_get_contents($_FILES['usaha_file']['tmp_name']);
								// ----- Process Image Name -----
								$img_info          = pathinfo($_FILES['usaha_file2']['name']);
								$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
								$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
								$fileExt           = $img_info['extension'];
								$file_usaha_name2   = $fileName.'.'.$fileExt;
								// ----- END Process Image Name -----
								//$u_detail['Photo_business_location'] = $upload_usaha;
								//$u_detail['foto_usaha']              = $upload_usaha;
								$u_detail['images_usaha_name2']       = $file_usaha_name2;
							}*/

							if($post['usaha_file3_hidden']!=''){	
								if (!is_file($destination_usaha3)) {
									mkdir_r($destination_usaha3);
								}
								if($post['old_usaha3']!=''){
									if (is_file($destination_usaha3.$post['old_usaha3'])){
										unlink($destination_usaha3.$post['old_usaha3']);
									}
								}
								$data = $_POST['usaha_file3_hidden'];
								$splited = explode(',', substr( $data , 5 ) , 2);
								$mime=$splited[0];
							    $data=$splited[1];

							    $mime_split_without_base64=explode(';', $mime,2);
							    $mime_split=explode('/', $mime_split_without_base64[0],2);
							    if(count($mime_split)==2)
							    {
							        $extension=$mime_split[1];
							        if($extension=='jpeg')$extension='jpg';
							        //if($extension=='javascript')$extension='js';
							        //if($extension=='text')$extension='txt';
							        $output_file_with_extension=rand().'.'.$extension;
							    }

								$data = base64_decode($data);
								$file = $destination_usaha3.$output_file_with_extension;
								$success = file_put_contents($file, $data);
								$u_detail['images_usaha_name3']  = $output_file_with_extension;
							}


							if($post['usaha_file4_hidden']!=''){
								if (!is_file($destination_usah4a)) {
									mkdir_r($destination_usaha4);
								}	
								if($post['old_usaha4']!=''){
									if (is_file($destination_usaha4.$post['old_usaha4'])){
										unlink($destination_usaha4.$post['old_usaha4']);
									}
								}
								$data = $_POST['usaha_file4_hidden'];
								$splited = explode(',', substr( $data , 5 ) , 2);
								$mime=$splited[0];
							    $data=$splited[1];

							    $mime_split_without_base64=explode(';', $mime,2);
							    $mime_split=explode('/', $mime_split_without_base64[0],2);
							    if(count($mime_split)==2)
							    {
							        $extension=$mime_split[1];
							        if($extension=='jpeg')$extension='jpg';
							        //if($extension=='javascript')$extension='js';
							        //if($extension=='text')$extension='txt';
							        $output_file_with_extension=rand().'.'.$extension;
							    }

								$data = base64_decode($data);
								$file = $destination_usaha4.$output_file_with_extension;
								$success = file_put_contents($file, $data);
								$u_detail['images_usaha_name4']  = $output_file_with_extension;
							}

						

							if($post['usaha_file5_hidden']!=''){	
								if (!is_file($destination_usaha5)) {
									mkdir_r($destination_usaha5);
								}
								if($post['old_usaha5']!=''){
									if (is_file($destination_usaha5.$post['old_usaha5'])){
										unlink($destination_usaha5.$post['old_usaha5']);
									}
								}
								$data = $_POST['usaha_file5_hidden'];
								$splited = explode(',', substr( $data , 5 ) , 2);
								$mime=$splited[0];
							    $data=$splited[1];

							    $mime_split_without_base64=explode(';', $mime,2);
							    $mime_split=explode('/', $mime_split_without_base64[0],2);
							    if(count($mime_split)==2)
							    {
							        $extension=$mime_split[1];
							        if($extension=='jpeg')$extension='jpg';
							        //if($extension=='javascript')$extension='js';
							        //if($extension=='text')$extension='txt';
							        $output_file_with_extension=rand().'.'.$extension;
							    }

								$data = base64_decode($data);
								$file = $destination_usaha5.$output_file_with_extension;
								$success = file_put_contents($file, $data);
								$u_detail['images_usaha_name5']  = $output_file_with_extension;
							}

					
						}

						// -----Tambahan Baru-----
						if ($memberdata['mum_type_peminjam']=='1'){

							if($post['surat_keterangan_bekerja_file_hidden']!=''){
								if (!is_file($destination_surat_keterangan_bekerja)) {
									mkdir_r($destination_surat_keterangan_bekerja);
								}	
								if($post['old_surat_keterangan_bekerja']!=''){
									if (is_file($destination_surat_keterangan_bekerja.$post['old_surat_keterangan_bekerja'])){
										unlink($destination_surat_keterangan_bekerja.$post['old_surat_keterangan_bekerja']);
									}
								}
								$data = $_POST['surat_keterangan_bekerja_file_hidden'];
								$splited = explode(',', substr( $data , 5 ) , 2);
								$mime=$splited[0];
							    $data=$splited[1];

							    $mime_split_without_base64=explode(';', $mime,2);
							    $mime_split=explode('/', $mime_split_without_base64[0],2);
							    if(count($mime_split)==2)
							    {
							        $extension=$mime_split[1];
							        if($extension=='jpeg')$extension='jpg';
							        //if($extension=='javascript')$extension='js';
							        //if($extension=='text')$extension='txt';
							        $output_file_with_extension=rand().'.'.$extension;
							    }

								$data = base64_decode($data);
								$file = $destination_surat_keterangan_bekerja.$output_file_with_extension;
								$success = file_put_contents($file, $data);
								$u_detail['foto_surat_keterangan_bekerja']  = $output_file_with_extension;
							}



							if($post['slip_gaji_file_hidden']!=''){	
								if (!is_file($destination_slip_gaji)) {
									mkdir_r($destination_slip_gaji);
								}
								if($post['old_slip_gaji']!=''){
									if (is_file($destination_slip_gaji.$post['old_slip_gaji'])){
										unlink($destination_slip_gaji.$post['old_slip_gaji']);
									}
								}
								$data = $_POST['slip_gaji_file_hidden'];
								$splited = explode(',', substr( $data , 5 ) , 2);
								$mime=$splited[0];
							    $data=$splited[1];

							    $mime_split_without_base64=explode(';', $mime,2);
							    $mime_split=explode('/', $mime_split_without_base64[0],2);
							    if(count($mime_split)==2)
							    {
							        $extension=$mime_split[1];
							        if($extension=='jpeg')$extension='jpg';
							        //if($extension=='javascript')$extension='js';
							        //if($extension=='text')$extension='txt';
							        $output_file_with_extension=rand().'.'.$extension;
							    }

								$data = base64_decode($data);
								$file = $destination_slip_gaji.$output_file_with_extension;
								$success = file_put_contents($file, $data);
								$u_detail['foto_slip_gaji']  = $output_file_with_extension;
							}

						


							if($post['pegang_ktp_file_hidden']!=''){
								if (!is_file($destination_pegang_ktp)) {
									mkdir_r($destination_pegang_ktp);
								}	
								if($post['old_pegang_ktp']!=''){
									if (is_file($destination_pegang_ktp.$post['old_pegang_ktp'])){
										unlink($destination_pegang_ktp.$post['old_pegang_ktp']);
									}
								}
								$data = $_POST['pegang_ktp_file_hidden'];
								$splited = explode(',', substr( $data , 5 ) , 2);
								$mime=$splited[0];
							    $data=$splited[1];

							    $mime_split_without_base64=explode(';', $mime,2);
							    $mime_split=explode('/', $mime_split_without_base64[0],2);
							    if(count($mime_split)==2)
							    {
							        $extension=$mime_split[1];
							        if($extension=='jpeg')$extension='jpg';
							        //if($extension=='javascript')$extension='js';
							        //if($extension=='text')$extension='txt';
							        $output_file_with_extension=rand().'.'.$extension;
							    }

								$data = base64_decode($data);
								$file = $destination_pegang_ktp.$output_file_with_extension;
								$success = file_put_contents($file, $data);
								$u_detail['foto_pegang_ktp']  = $output_file_with_extension;
							}

							/*if($_FILES['pegang_ktp_file']['name'] == ''){
								$file_pegang_ktp_name   = '';
							}else{
								//$pegang_ktp_file  = file_get_contents($_FILES['pegang_ktp_file']['tmp_name']);
								// ----- Process Image Name -----
								$img_info          = pathinfo($_FILES['pegang_ktp_file']['name']);
								$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
								$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
								$fileExt           = $img_info['extension'];
								$file_pegang_ktp_name  = $fileName.'.'.$fileExt;
								$u_detail['foto_pegang_ktp']  = $file_pegang_ktp_name;
								// ----- END Process Image Name -----
							}*/
						}

						// -----batas tambahan-----

						if ($memberdata['mum_type_peminjam']=='3'){

							// if($_FILES['pegang_ktp_file']['name'] == ''){
							// 	$file_pegang_ktp_name   = '';
							// }else{
							// 	//$pegang_ktp_file  = file_get_contents($_FILES['pegang_ktp_file']['tmp_name']);
							// 	// ----- Process Image Name -----
							// 	$img_info          = pathinfo($_FILES['pegang_ktp_file']['name']);
							// 	$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
							// 	$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
							// 	$fileExt           = $img_info['extension'];
							// 	//$fileWidth		   = 500;
							// 	//$fileHeight		   = 500;
							// 	$file_pegang_ktp_name  = $fileName.'.'.$fileExt;
							// 	$u_detail['foto_pegang_ktp']  = $file_pegang_ktp_name;
							// 	// ----- END Process Image Name -----
							// }

							if($post['pegang_ktp_file_hidden']!=''){
								if (!is_file($destination_pegang_ktp)) {
									mkdir_r($destination_pegang_ktp);
								}	
								if($post['old_pegang_ktp']!=''){
									if (is_file($destination_pegang_ktp.$post['old_pegang_ktp'])){
										unlink($destination_pegang_ktp.$post['old_pegang_ktp']);
									}
								}
								$data = $_POST['pegang_ktp_file_hidden'];
								$splited = explode(',', substr( $data , 5 ) , 2);
								$mime=$splited[0];
							    $data=$splited[1];

							    $mime_split_without_base64=explode(';', $mime,2);
							    $mime_split=explode('/', $mime_split_without_base64[0],2);
							    if(count($mime_split)==2)
							    {
							        $extension=$mime_split[1];
							        if($extension=='jpeg')$extension='jpg';
							        //if($extension=='javascript')$extension='js';
							        //if($extension=='text')$extension='txt';
							        $output_file_with_extension=rand().'.'.$extension;
							    }

								$data = base64_decode($data);
								$file = $destination_pegang_ktp.$output_file_with_extension;
								$success = file_put_contents($file, $data);
								$u_detail['foto_pegang_ktp']  = $output_file_with_extension;
							}
						}

						// -----Tambahan Baru-----
						// if ($memberdata['mum_type_peminjam']=='1'){

						// 	if($_FILES['surat_keterangan_bekerja_file']['name'] == ''){
						// 		$file_surat_keterangan_bekerja_name   = '';
						// 	}else{
						// 		//$upload_surat_keterangan_bekerja_file  = file_get_contents($_FILES['surat_keterangan_bekerja_file']['tmp_name']);
						// 		// ----- Process Image Name -----
						// 		$img_info          = pathinfo($_FILES['surat_keterangan_bekerja_file']['name']);
						// 		$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
						// 		$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
						// 		$fileExt           = $img_info['extension'];
						// 		$file_surat_keterangan_bekerja_name  = $fileName.'.'.$fileExt;
						// 		$u_detail['	foto_surat_keterangan_bekerja']  = $file_surat_keterangan_bekerja_name;
						// 		// ----- END Process Image Name -----
						// 	}

						// 	if($_FILES['slip_gaji_file']['name'] == ''){
						// 		$file_slip_gaji_name   = '';
						// 	}else{
						// 		//$upload_slip_gaji  = file_get_contents($_FILES['slip_gaji_file']['tmp_name']);
						// 		// ----- Process Image Name -----
						// 		$img_info          = pathinfo($_FILES['slip_gaji_file']['name']);
						// 		$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
						// 		$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
						// 		$fileExt           = $img_info['extension'];
						// 		$file_slip_gaji_name   = $fileName.'.'.$fileExt;
						// 		$u_detail['foto_slip_gaji']  = $file_slip_gaji_name;
						// 		// ----- END Process Image Name -----
						// 	}

						// 	//$imageProcess = 0;
						// 	if($_FILES['pegang_ktp_file']['name'] == ''){
						// 		$file_pegang_ktp_name   = '';
						// 	}else{
						// 		//$pegang_ktp_file  = file_get_contents($_FILES['pegang_ktp_file']['tmp_name']);
						// 		// ----- Process Image Name -----

						// 		//resize image
						// 		//$new_width = 500;
						//         //$new_height = 500;



						// 		//batas tambahan resize
						// 		$img_info          = pathinfo($_FILES['pegang_ktp_file']['name']);
						// 		$fileName          = strtolower(str_replace(' ', '-', $img_info['filename']));
						// 		$fileName          = preg_replace('#[^a-z.0-9_-]#i', '', $fileName);
						// 		//tambahan baru resize image
						// 		/*$sourceProperties = getimagesize($fileName);
						//         $resizeFileName = time();
      //   						$uploadPath = "./uploads/"
      //   						$uploadImageType = $sourceProperties[2];
						//         $sourceImageWidth = $sourceProperties[0];
						//         $sourceImageHeight = $sourceProperties[1];
						//         switch ($uploadImageType) {
						//             case IMAGETYPE_JPEG:
						//                 $resourceType = imagecreatefromjpeg($fileName); 
						//                 $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
						//                 imagejpeg($imageLayer,$uploadPath."thump_".$resizeFileName.'.'. $fileExt);
						//                 break;

						//             case IMAGETYPE_GIF:
						//                 $resourceType = imagecreatefromgif($fileName); 
						//                 $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
						//                 imagegif($imageLayer,$uploadPath."thump_".$resizeFileName.'.'. $fileExt);
						//                 break;

						//             case IMAGETYPE_PNG:
						//                 $resourceType = imagecreatefrompng($fileName); 
						//                 $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
						//                 imagepng($imageLayer,$uploadPath."thump_".$resizeFileName.'.'. $fileExt);
						//                 break;

						//             default:
						//                 $imageProcess = 0;
						//                 break;
						//         }*/

						// 		//batas tambahan baru

						// 		$fileExt           = $img_info['extension'];
						// 		$file_pegang_ktp_name  = $fileName.'.'.$fileExt;
						// 		$u_detail['foto_pegang_ktp']  = $file_pegang_ktp_name;
						// 		// ----- END Process Image Name -----
						// 	}
						// }

						// -----batas tambahan-----

						$userID = $memberdata['Id_pengguna'];
						
						$mem_data['mum_fullname']       = antiInjection(trim($post['fullname']));
						$mem_data['mum_telp']           = $notelp;
						$mem_data['mum_ktp']            = antiInjection(trim($post['nomor_ktp']));
						$mem_data['mum_nomor_rekening'] = antiInjection(trim($post['nomor_rekening']));

						$this->Member_model->update_member_byid($uid, $mem_data);

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

						$this->Content_model->update_user($uid, $user);

						// user_detail
						$u_detail['Mobileno']          = $notelp;
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





						$this->Content_model->update_userdetail($userID, $u_detail);
						
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

						$this->Content_model->update_profil_geografi($userID, $u_geo);

						// ---- Scoring -----
						if ($user['Jenis_kelamin']=='pria')
						{
							$score = $score + 30;
						}else{
							$score = $score + 50;							
						}

						// ranking
						$get_ranking = set_ranking_pengguna($userID, $logintype, $memberdata['mum_type_peminjam']);

						$update_pengguna['peringkat_pengguna']            = $get_ranking['grade'];
						$update_pengguna['peringkat_pengguna_persentase'] = $get_ranking['ranking'];
						$update_pengguna['skoring']                       = $score;
						$this->Content_model->update_user_byid($userID, $update_pengguna);

						

/*						if($_FILES['foto_file']['name'] != ''){
							if (!is_file($destination_foto.$file_foto_name)) {
								mkdir_r($destination_foto);
							}
							if($post['old_foto']!=''){
							unlink($destination_foto.$post['old_foto']);
							}
							move_uploaded_file($_FILES['foto_file']['tmp_name'], $destination_foto.$file_foto_name);		
						}

						if($_FILES['ktp_file']['name'] != ''){
							if (!is_file($destination_ktp.$file_ktp_name)) {
								mkdir_r($destination_ktp);
							}
							if($post['old_ktp']!=''){
							unlink($destination_ktp.$post['old_ktp']);
							}
							move_uploaded_file($_FILES['ktp_file']['tmp_name'], $destination_ktp.$file_ktp_name);		
						}

						if(isset($_FILES['usaha_file']['name']) && $_FILES['usaha_file']['name'] != ''){
							if (!is_file($destination_usaha.$file_usaha_name)) {
								mkdir_r($destination_usaha);
							}
							if($post['old_usaha']!=''){
							unlink($destination_usaha.$post['old_usaha']);
							}
							move_uploaded_file($_FILES['usaha_file']['tmp_name'], $destination_usaha.$file_usaha_name);
						}
						if(isset($_FILES['usaha_file2']['name']) && $_FILES['usaha_file2']['name'] != ''){
							if (!is_file($destination_usaha2.$file_usaha_name2)) {
								mkdir_r($destination_usaha2);
							}
							if($post['old_usaha2']!=''){
							unlink($destination_usaha2.$post['old_usaha2']);
							}
							move_uploaded_file($_FILES['usaha_file2']['tmp_name'], $destination_usaha2.$file_usaha_name2);
						}
						if(isset($_FILES['usaha_file3']['name']) && $_FILES['usaha_file3']['name'] != ''){
							if (!is_file($destination_usaha3.$file_usaha_name3)) {
								mkdir_r($destination_usaha3);
							}
							if($post['old_usaha3']!=''){
							unlink($destination_usaha3.$post['old_usaha3']);
							}
							move_uploaded_file($_FILES['usaha_file3']['tmp_name'], $destination_usaha3.$file_usaha_name3);
						}
						if(isset($_FILES['usaha_file4']['name']) && $_FILES['usaha_file4']['name'] != ''){
							if (!is_file($destination_usaha4.$file_usaha_name4)) {
								mkdir_r($destination_usaha4);
							}
							if($post['old_usaha4']!=''){
							unlink($destination_usaha4.$post['old_usaha4']);
							}
							move_uploaded_file($_FILES['usaha_file4']['tmp_name'], $destination_usaha4.$file_usaha_name4);
						}
						if(isset($_FILES['usaha_file5']['name']) && $_FILES['usaha_file5']['name'] != ''){
							if (!is_file($destination_usaha5.$file_usaha_name5)) {
								mkdir_r($destination_usaha5);
							}
							if($post['old_usaha5']!=''){
							unlink($destination_usaha5.$post['old_usaha5']);
							}
							move_uploaded_file($_FILES['usaha_file5']['tmp_name'], $destination_usaha5.$file_usaha_name5);
						}*/





						// ----- tambahan baru -----
/*					if ($memberdata['mum_type_peminjam']=='1'){

						if($_FILES['surat_keterangan_bekerja_file']['name'] != ''){
							if (!is_file($destination_surat_keterangan_bekerja.$file_surat_keterangan_bekerja_name)) {
								mkdir_r($destination_surat_keterangan_bekerja);
							}
							if($post['old_surat_keterangan_bekerja']!=''){
							unlink($destination_surat_keterangan_bekerja.$post['old_surat_keterangan_bekerja']);
							}
							move_uploaded_file($_FILES['surat_keterangan_bekerja_file']['tmp_name'], $destination_surat_keterangan_bekerja.$file_surat_keterangan_bekerja_name);
						}

						if($_FILES['slip_gaji_file']['name'] != '') {
							if (!is_file($destination_slip_gaji.$file_slip_gaji_name)) {
								mkdir_r($destination_slip_gaji);
							}
							if($post['old_slip_gaji']!=''){
							unlink($destination_slip_gaji.$post['old_slip_gaji']);
							}
							move_uploaded_file($_FILES['slip_gaji_file']['tmp_name'], $destination_slip_gaji.$file_slip_gaji_name);
						}

						if($_FILES['pegang_ktp_file']['name'] != ''){
							if (!is_file($destination_pegang_ktp.$file_pegang_ktp_name)) {
								mkdir_r($destination_pegang_ktp);
							}
							if($post['old_pegang_ktp']!=''){
							unlink($destination_pegang_ktp.$post['old_pegang_ktp']);
							}
							move_uploaded_file($_FILES['pegang_ktp_file']['tmp_name'], $destination_pegang_ktp.$file_pegang_ktp_name);
						}
					}*/
						
						// -----batas tambahan baru -----
						
						$this->session->set_userdata('message','Sukses ubah profil');
						$this->session->set_userdata('message_type','success');
					}else{
						$this->session->set_userdata('message','Data tidak ditemukan');
						$this->session->set_userdata('message_type','error');
					}
				}else{
					$this->session->set_userdata('message','Isilah semua kolom dengan benar.');
					$this->session->set_userdata('message_type','error');
					redirect('ubah-profil');
					exit();
				}

				redirect('ubah-profil');
				exit();
			}else{
				$this->session->set_userdata('message','Isilah semua kolom dengan benar.');
				$this->session->set_userdata('message_type','error');
				redirect('ubah-profil');
				exit();
			}
		}

	}
	
}
