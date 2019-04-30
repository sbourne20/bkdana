<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_pinjaman_agri extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Pinjaman_model');
		$this->load->model('Product_model');
		$this->load->model('Wallet_model');
		$this->load->model('Member_model');
		$this->load->model('Log_transaksi_model');

		$this->load->library('FirebaseNotification');
		
		//error_reporting(E_ALL);
		error_reporting(0);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Pinjaman Agri';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('plugins/jquery-loading-overlay/dist/loadingoverlay.min.js');
		$mainData['bottom_js'] .= add_js('js/data/transaksi_pinjaman_agri.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mainData['mainContent']  = $this->load->view('loan/vagri_list', $output, true);

		$this->load->view('vbase',$mainData);
	}

	//tambahan fungsi baru


		function edit()
	{
			
		$id = antiInjection($this->uri->segment(3));
		//$output['mode'] = 2;
		$output['data'] = $this->Pinjaman_model->get_loan_detail($id);
		//header('Content-type: image/jpeg');

		$output['top_css']   ="";
		$output['top_js']    ="";
		$output['bottom_js'] ="";
		$output['top_css']   .= add_css("plugins/bootstrap-timepicker/bootstrap-timepicker.css");
		$output['top_css'] .= add_css('plugins/validationengine/validationEngine.jquery.css');
		$output['top_css'] .= add_css('plugins/bootstrap-datepicker/css/bootstrap-datepicker.css');
		$output['top_js']    .= add_js("plugins/bootstrap-timepicker/bootstrap-timepicker.js");
		$output['bottom_js'] .= add_js('plugins/validationengine/jquery.validationEngine.js');
		$output['bottom_js'] .= add_js('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js');
		$output['bottom_js'] .= add_js('plugins/jqueryvalidation/dist/jquery.validate.min.js');
		$output['bottom_js'] .= add_js('plugins/autoNumeric/autoNumeric.min.js');

		$output['bottom_js'] .= add_js('plugins/validation-init.js');
		$output['bottom_js'] .= add_js('plugins/autoNumeric-init.js');
		$output['bottom_js'] .= add_js('plugins/date-init.js');
		$output['bottom_js'] .= add_js('plugins/autoNumeric/autoNumeric.min.js');
		$output['bottom_js'] .= add_js('plugins/autoNumeric-init.js');

		$output['tipe_hasil_tani'] = $this->Member_model->get_all_tipe_hasil_tani();
		//$a = $this->Member_model->get_all_tipe_hasil_tani();
		//echo"tipe hasil tani ".$a;
		//exit();
		
		$mainData['mainContent'] = $this->load->view('loan/vagri_list_form', $output, true);
		//$output['data'] = $this->Pinjaman_model->get_loan_detail($id);
		$this->load->view('vbase',$mainData);


		$post = $this->input->post(NULL, TRUE);

		if (trim($post['transaksi_id']) != '' && !empty($id) )
		{

			$filter = explode('.', trim($post['Jml_permohonan_pinjaman_disetujui']));
			$pinjaman_disetujui = str_replace(',', '', $filter[0]);
			$filter = explode('.', trim($post['penjualan']));
			$penjualan = str_replace(',', '', $filter[0]);
			$filter = explode('.', trim($post['harga_kg']));
			$harga_kg = str_replace(',', '', $filter[0]);

			$updata1['Jml_permohonan_pinjaman_disetujui'] = $pinjaman_disetujui;
			$updata1['Amount'] = $pinjaman_disetujui;
			//tambahan baru agri
			$updata1['no_cf'] = antiInjection(trim($post['cf_number']));
			$updata1['luas_lahan'] = antiInjection(trim($post['luas_lahan']));
			$updata1['tgl_panen'] = date('Y-m-d', strtotime(trim($post['tgl_panen'])));
			$updata1['penjualan_terakhir'] = $penjualan;
			$updata1['bulan_aktif_terakhir'] = antiInjection(trim($post['bulan_aktif']));
			$updata1['tipe_hasil_tani'] = antiInjection(trim($post['tipe_hasil_tani']));
			$updata1['volume_panen'] = antiInjection(trim($post['volume_panen']));
			$updata1['harga_kg'] = $harga_kg;
			$updata1['tenor_pinjaman_disetujui'] = antiInjection(trim($post['rekomendasi_tenor']));
			//batas tambahan baru agri
			$affected = $this->Pinjaman_model->update_profil_pinjaman($updata1, $id);
			
			
			//$user_data = $this->Pinjaman_model-> get_loan_detail($id);
			//p_pinjam['User_id'] = $user_data['Id_pengguna'];
			$userid = trim($post['user_id']);
			$masterloan = trim($post['transaksi_id']);

			$this->db->from('record_pinjaman');
			$this->db->where('User_id', $userid);
			//$this->db->where('Master_loan_id', $masterloan);
			$this->db->like('Flag', 'CA', 'BOTH');
			$hasil=$this->db->get()->num_rows();
			// $p_pinjam2['user_id'] =$uid;
			/*if($hasil>0){
				$hasil+=1;	
			}else{
				$hasil="1";
			}*/

			$p_pinjam['Flag'] = 'CA';
			$p_pinjam['Master_loan_id'] = $masterloan;
			$p_pinjam['User_id'] = $userid;
			$p_pinjam['Amount'] = $updata1['Jml_permohonan_pinjaman_disetujui'];
			$p_pinjam['Tgl_pinjaman'] = date('Y-m-d H:i:s');
			$pinjamID = $this->Pinjaman_model->insert_profil_pinjaman5($p_pinjam);
						//$this->db->insert('record_pinjaman', $p_pinjam);
						//$pinjamID = $this->Pinjaman_model->insert_profil_pinjaman($p_pinjam);
							/*echo '<script language="javascript">
						  	alert("message successfully sent");  
						  	</script>';
						*/

			if($affected){
				$this->session->set_userdata('message','Data has been updated.');
				$this->session->set_userdata('message_type','success');
				redirect('transaksi_pinjaman_agri');
					
			}else{
				$this->session->set_userdata('message','No Update.');
				$this->session->set_userdata('message_type','warning');
				redirect('transaksi_pinjaman_agri');
			}

		}

	}

	//batas fungsi baru

	function json()
	{			
		$data = $this->Pinjaman_model->get_all_agri_dt();
		print_r($data);
	}

	function detail()
	{
		$id = antiInjection(trim($this->uri->segment(3)));

		if (!empty($id)) {
		$output['data'] = $this->Pinjaman_model->get_loan_byid($id);

		header('Content-type: image/jpeg');
		$this->load->view('loan/vdetail_agri', $output);		
		}
	}

	function delete()
	{
		$this->User_model->has_login();

		$id = antiInjection(trim($this->uri->segment(3)));

		if (!empty($id))
		{
			$del = $this->Pinjaman_model->delete_($id);
			if($id && $del){
				$this->Pinjaman_model->delete_detail($id);
				$this->Pinjaman_model->delete_log_pinjaman($id);
				$this->Pinjaman_model->delete_tempo($id);
				
				$this->session->set_userdata('message','Data has been deleted.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','No Data was deleted.');
				$this->session->set_userdata('message_type','warning');
			}
		}
		redirect('transaksi_pinjaman_agri');
	}


	function approve()
	{
		$this->User_model->has_login();

		$affected = FALSE;
		$id = antiInjection(trim($this->uri->segment(3)));

		$nowdatetime = date('Y-m-d H:i:s');

		if ( !empty($id) )
		{
			// get data pinjaman
			$loan_data = $this->Pinjaman_model->get_pinjaman_byid($id);
			$token_data = $this->Member_model->get_fcm_token($loan_data['User_id']);

			if ( count($loan_data) > 0) {
				// get tipe produk: kilat, mikro ,usaha
				$message_fcm = "Pinjaman Agri anda dengan nomor traksaksi ".$loan_data['Master_loan_id']." telah ditinjau oleh Analis kami, silahkan cek Dashboard Anda untuk melanjutkan pinjaman";
				$title_fcm	= "Status Pinjaman";
				$action = "approve_pinjaman_agri";


				$produk = $this->Product_model->get_product_by($loan_data['Product_id']);
				$tipe_produk = $produk['type_of_business_id'];
				//tambahan baru repayment
				$loan_data1 = $this->Product_model->get_record_repayment($loan_data['Master_loan_id']);
				//batas tambahan baru repayment
				$pinjaman_rp = $loan_data['Amount'];

				// ---------- hitung total pinjaman disetujui ------------			
				$revenue                = ($pinjaman_rp * $produk['Fee_revenue_share'])/100;
				$admin_fee              = ($pinjaman_rp * $produk['Secured_loan_fee'])/100;
				$jml_pinjaman_disetujui = $pinjaman_rp - $admin_fee;
				// ----------- End hitung total pinjaman disetujui -------------------

			$datediff =round((strtotime($nowdatetime) - strtotime($record_repayment_data['tgl_jatuh_tempo']))/86400);

				//tambahan baru
				$type_interest_rate = $produk['type_of_interest_rate'];
				if($type_interest_rate == 1){//harian
					$totalweeks   = $loan_data['loan_term_permohonan'];
					$jml_angsuran = $pinjaman_rp;
					$pokok_cicilan = $pinjaman_rp / $totalweeks;
					$jml_repayment     = round($jml_angsuran);
					$total_angsuran_rp = $jml_repayment*$totalweeks;
					// $bunga             = $total_angsuran_rp - $pinjaman_rp;
					$bunga = ($pinjaman_rp * ($produk['Interest_rate'] * $diffdate))/100;
					$loan_term = $loan_data['loan_term_permohonan'];
					$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$loan_term." days"));
				}
				if($type_interest_rate == 2){//bulanan
					$totalweeks   = 4 * $loan_data['loan_term_permohonan'];
					$jml_angsuran = ($pinjaman_rp + ( $pinjaman_rp * ($produk['Interest_rate'] * $totalweeks))/100 );
					$pokok_cicilan = $pinjaman_rp / $totalweeks;
					$jml_repayment     = round($jml_angsuran);
					$total_angsuran_rp = $jml_repayment*$totalweeks;
					$bunga = ($pinjaman_rp * ($produk['Interest_rate'] * $diffdate))/100;
					$loan_term = $loan_data['loan_term_permohonan'];
					$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$loan_term." months"));
				}
				if($type_of_interest_rate == 3){//mingguan
					$totalweeks   = $loan_data['loan_term_permohonan'];
					$jml_angsuran = ($pinjaman_rp + ( $pinjaman_rp * ($produk['Interest_rate'] * $totalweeks))/100 );
					$pokok_cicilan = $pinjaman_rp / $totalweeks;
					$jml_repayment     = round($jml_angsuran);
					$total_angsuran_rp = $jml_repayment*$totalweeks;
					$bunga = ($pinjaman_rp * ($produk['Interest_rate'] * $diffdate))/100;
					// $bunga             = $total_angsuran_rp - $pinjaman_rp;
					$loan_term = $loan_data['loan_term_permohonan'];
					$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$loan_term." weeks"));
				}
				//batas tambahan baru



				$angsuran_platform_fee = ($pinjaman_rp * ($produk['Platform_rate'] * $produk['Loan_term'])/100);


				$angsuran_LO = ($pinjaman_rp * ($produk['Loan_organizer'] * $produk['Loan_term'])/100);

				$lender_fee  = (($pinjaman_rp*$produk['Investor_return'] * $loan_term)/100)/$totalweeks;
				// $lender_fee  = (($pinjaman_rp*$produk['Investor_return'] * $loan_term)/100);

				$bunga_cicilan = ($angsuran_platform_fee + $angsuran_LO + $lender_fee );

				// -------- hitung total fundraise date (tgl maximum pendanaan) --------
				$fundraise = $produk['Fundraising_period'];
				$date_fundraise = date('Y-m-d', strtotime('+ '.$fundraise.' days'));
				// -------- End of hitung total fundraise date (tgl maximum pendanaan) ------

					$affected = $this->Pinjaman_model->approval_pinjaman_agri($id, $jml_pinjaman_disetujui, $date_fundraise, $jml_angsuran, $produk['Fundraising_period']);


				if($affected){

					// Log Transaksi Pinjaman
					$inlog['ltp_total_pinjaman_disetujui'] = $jml_pinjaman_disetujui;
					$inlog['ltp_admin_fee']                = $admin_fee;
					$inlog['ltp_bunga_pinjaman']           = $bunga;
					$inlog['ltp_jml_angsuran']             = $jml_repayment;
					$inlog['ltp_lama_angsuran']            = $totalweeks;
					$inlog['ltp_tgl_jatuh_tempo']          = $tgl_jatuh_tempo;
					$inlog['ltp_frozen']                   = 0;
					$inlog['ltp_platform_fee']             = $angsuran_platform_fee;
					$inlog['ltp_LO_fee']                   = $angsuran_LO;
					$inlog['ltp_lender_fee']               = $lender_fee;
					$inlog['ltp_pokok_cicilan']		   	   = 0;
					$inlog['ltp_bunga_cicilan']			   = 0;

					$this->Pinjaman_model->update_log_pinjaman($inlog, $loan_data['Master_loan_id']);

					$cicilan_duedate1 = date('Y-m-d H:i:s',strtotime($loan_data1['tgl_pinjaman_disetujui']. "+".$jmlhari." day"));

					

					$repayment['Master_loan_id']		   = $loan_data['Master_loan_id'];
					$repayment['User_id']				   = $loan_data['User_id'];
					$repayment['jumlah_cicilan']		   = $pinjaman_rp;
					$repayment['jml_bayar']				   = 0;
					$repayment['jml_bunga']				   = $bunga;
					$repayment['notes_cicilan']			   = 1;
					$repayment['status_cicilan']		   = 'belum-bayar';
					$repayment['tgl_jatuh_tempo']		   = $cicilan_duedate1;
					//$repayment['tgl_pembayaran']		   = $nowdatetime;
					$repayment['tgl_record_repayment']	   = $nowdatetime;
					$this->Pinjaman_model->insert_record_repayment($repayment);


					$this->session->set_userdata('message','Data has been Approved.');
					$this->session->set_userdata('message_type','success');
				}
			}else{
				$this->session->set_userdata('message','No Data selected.');
				$this->session->set_userdata('message_type','warning');	
			}
		}
		
		redirect('transaksi_pinjaman_agri');
	}

	function send_mail($loan_data, $jml_pinjaman_disetujui, $jml_angsuran, $file)
	{
		$userdata = $this->Member_model->get_user_ojk_by($loan_data['User_id']);
		
		$html_content = '
        Hai '.$userdata['Nama_pengguna'].',<br><br>

            Pengajuan Pinjaman Anda di website BKDana telah disetujui.
            <br><br>
            
            Berikut detail pinjaman Anda:<br>

            No. Transaksi: '.$loan_data['Master_loan_id'].'
            <br>
            Jenis Transaksi: Pinjaman Agri
            <br>
            Jumlah Pinjaman: Rp '.number_format($loan_data['Amount']).'
            <br>
            Jumlah Pinjaman diterima: Rp '.number_format($jml_pinjaman_disetujui).'
            <br>
            Jumlah Angsuran per minggu : Rp '.number_format($jml_angsuran).'
            <br>
            Status: <strong>Menunggu Pendanaan</strong>
            <br><br>

            <span style="color:#858C93;">
            	Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke Email ini.
            	<br><br>

            	&copy; BKDana, '.date("Y").'. All rights reserved.
            </span>
			';

		include(APPPATH.'libraries/phpmailer-5.2.23/PHPMailerAutoload.php');
    	$mail = new phpmailer();
        $mail->IsSMTP();
		$mail->SMTPAuth    = true;
		//$mail->SMTPSecure  = 'ssl';
		$mail->Host        = 'smtp.gmail.com';
		$mail->Port        = 587;
		$mail->IsHTML(true);
		$mail->Username    = $this->config->item('mail_username');
		$mail->Password    = $this->config->item('mail_password');
		$mail->SetFrom('bkdanafinansial@gmail.com', 'BKDana');	
		$mail->AddAddress($userdata['mum_email']);
		$mail->Subject     = 'Pinjaman disetujui';
		$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($html_content);	
		$mail->SMTPDebug   = 0;

		/* Update by desta */
	    // $mail->addAttachment('/var/www/html/data-file-bkd/pinjaman-mikro.docx', 'perjanjian-pinjaman-mikro.docx');
	    $mail->addAttachment($file['output_file'], $file['filename']);

        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }	

        return TRUE;
	
	}

	function create_pdf($html, $ordercode, $data)
	{
		require_once(APPPATH.'libraries/TCPDF/tcpdf.php');
		$filename = 'perjanjian-pinjaman-agri-'.$ordercode.'.pdf';

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('BKDana');
		$pdf->SetTitle('Perjanjian Pinjaman Agri '.$ordercode);
		$pdf->SetSubject('Perjanjian Pinjaman Agri '.$ordercode);

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

		$pdf->SetMargins(12, 15, 16,true);

		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		    require_once(dirname(__FILE__).'/lang/eng.php');
		    $pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);

		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont('helvetica', '', 9);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		// set text shadow effect
		//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

		ob_start();
	    $pdf->writeHTML($html, true, false, false, false, '');
	    ob_end_clean();
		// ---------------------------------------------------------

		$output_file = $this->config->item('attach_dir') . $filename;

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		//$pdf->Output($ordercode. '.pdf', 'I');
		$pdf->Output($output_file,'F');

		$ret = array(
				'filename'    => $filename,
				'output_file' =>$output_file
				);

		return $ret;
	}

	function reject()
	{
		$this->User_model->has_login();

		$id = antiInjection(trim($this->uri->segment(3)));

		if (!empty($id))
		{
			$loan_data = $this->Pinjaman_model->get_pinjaman_byid($id);
			$token_data = $this->Member_model->get_fcm_token($loan_data['User_id']);
			
			if (count($loan_data) > 0) {
				$affected = $this->Pinjaman_model->reject_pinjaman($id);

				$message_fcm = "Pinjaman Agri anda dengan nomor traksaksi ".$loan_data['Master_loan_id']." tidak disetujui";
				$title_fcm	= "Status Pinjaman";
				$action = "reject_pinjaman_agri";
				$this->firebasenotification->notif_aprrove($token_data['fcm_token'], $message_fcm, $title_fcm, $action);
			}else{
				$affected = FALSE;
			}

			if ($affected) {

				$this->send_mail_reject($loan_data);
				$this->session->set_userdata('message','Transaksi '.$id.' was Rejected.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','No Data selected.');
				$this->session->set_userdata('message_type','warning');
			}
		}		
		redirect('transaksi_pinjaman_agri');
	}

	function send_mail_reject($loan_data)
	{
		$userdata = $this->Member_model->get_user_ojk_by($loan_data['User_id']);
		
		$html_content = '
        Hai '.$userdata['Nama_pengguna'].',<br><br>

            Pengajuan Pinjaman Anda di website BKDana <strong>tidak disetujui</strong>.
            <br><br>
            
            Berikut detail pinjaman Anda:<br>

            No. Transaksi: '.$loan_data['Master_loan_id'].'
            <br>
            Jenis Transaksi: Pinjaman Agri
            <br>
            Jumlah Pinjaman: Rp '.number_format($loan_data['Amount']).'
            <br>
            Status: <strong>Dibatalkan</strong>
            <br><br>

            <span style="color:#858C93;">
            	Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke Email ini.
            	<br><br>

            	&copy; BKDana, '.date("Y").'. All rights reserved.
            </span>
			';

		include(APPPATH.'libraries/phpmailer-5.2.23/PHPMailerAutoload.php');
    	$mail = new phpmailer();
        $mail->IsSMTP();
		$mail->SMTPAuth    = true;
		//$mail->SMTPSecure  = 'ssl';
		$mail->Host        = 'smtp.gmail.com';
		$mail->Port        = 587;
		$mail->IsHTML(true);
		$mail->Username    = $this->config->item('mail_username');
		$mail->Password    = $this->config->item('mail_password');
		$mail->SetFrom('bkdanafinansial@gmail.com', 'BKDana');	
		$mail->AddAddress($userdata['mum_email']);
		$mail->Subject     = 'Pinjaman dibatalkan';
		$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($html_content);	
		$mail->SMTPDebug   = 0;
        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }	

        return TRUE;
	
	}

	function detail_transaksi()
	{
		// display formula, jumlah potongan, jumlah pendanaan, dll
		error_reporting(0);
		$id = antiInjection(trim($this->uri->segment(3)));

		if (!empty($id)) {

			$this->User_model->has_login();

			$output['PAGE_TITLE'] = 'Pinjaman Agri';

			$mainData['top_css']   = '';
			$mainData['top_js']    = '';
			$mainData['bottom_js'] = '';
			$mainData['bottom_js'] .= add_js('js/data/transaksi_pinjaman_agri.js');
			$mainData['bottom_js'] .= add_js('js/global.js');

			$output['EDIT'] = $this->Log_transaksi_model->get_log_transaksi_pinjam($id);
			$output['pendana'] = $this->Log_transaksi_model->get_log_transaksi_pendana_byloan($id);

			//_d($output['EDIT']);

			$mainData['mainContent']  = $this->load->view('loan/vpinjaman_detail_agri', $output, true);

			$this->load->view('vbase',$mainData);
		}
	}
}