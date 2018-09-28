<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_pinjaman_kilat extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Member_model');
		$this->load->model('Pinjaman_model');
		$this->load->model('Product_model');
		$this->load->model('Wallet_model');
		$this->load->model('Log_transaksi_model');
		
		//error_reporting(E_ALL);
		error_reporting(0);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Loan';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('plugins/jquery-loading-overlay/dist/loadingoverlay.min.js');
		$mainData['bottom_js'] .= add_js('js/data/transaksi_pinjaman_kilat.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mainData['mainContent']  = $this->load->view('loan/vkilat_list', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{
		$data = $this->Pinjaman_model->get_all_kilat_dt();
		print_r($data);
	}

	function detail()
	{
		$id = antiInjection($this->uri->segment(3));
		$output['data'] = $this->Pinjaman_model->get_loan_byid($id);
		header('Content-type: image/jpeg');
		$this->load->view('loan/vkilat_detail', $output);
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

		redirect('transaksi_pinjaman_kilat');
	}

	/*function approve()
	{
		$this->User_model->has_login();

		$affected = FALSE;
		$id = antiInjection($this->uri->segment(3));

		// get data pinjaman
		$loan_data    = $this->Pinjaman_model->get_pinjaman_byid($id);
		$log_pinjaman = $this->Log_transaksi_model->get_log_transaksi_pinjam($id);

		$jml_pinjaman_disetujui = $log_pinjaman['ltp_total_pinjaman_disetujui'];

		if ( count($loan_data) > 0 && !empty($id)) {
			
			$affected = $this->Pinjaman_model->set_approve_pinjaman($id);

			if($id && $affected){ 

				$this->send_mail($loan_data, $jml_pinjaman_disetujui);

				$this->session->set_userdata('message','Data has been Approved.');
				$this->session->set_userdata('message_type','success');
			}
		}else{
			$this->session->set_userdata('message','No Data selected.');
			$this->session->set_userdata('message_type','warning');
		}
		redirect('transaksi_pinjaman_kilat');
	}*/

	function approve()
	{
		$this->User_model->has_login();

		$affected = FALSE;
		$id = antiInjection($this->uri->segment(3));

		// get data pinjaman
		$loan_data = $this->Pinjaman_model->get_pinjaman_byid($id);

		// _d($loan_data);exit();

		if ( count($loan_data) > 0 && !empty($id)) {
			// get tipe produk: kilat, mikro ,usaha
			$produk = $this->Product_model->get_product_by($loan_data['Product_id']);
			$tipe_produk = $produk['type_of_business_id'];
			$totalweek   = $produk['Loan_term']/7;
		
			$pinjaman_rp = $loan_data['Jml_permohonan_pinjaman'];

			//_d($produk);exit();

			// admin fee = pinjaman - (pinjaman * secure loan fee)
			$admin_fee = ($pinjaman_rp * $produk['Secured_loan_fee'])/100;
			$jml_pinjaman_disetujui = $pinjaman_rp - $admin_fee;

			// Repayment
			$bunga = ($pinjaman_rp * $produk['Loan_term'] * $produk['Investor_return'])/100;
			$jml_repayment = $pinjaman_rp + $bunga;
			
			$loan_term = $produk['Loan_term'];
			$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$loan_term." days"));

			// hitung total fundraise 15 menit
			$date_fundraise = date('Y-m-d H:i:s', strtotime('+ 15 minute'));

			$lender_fee = (($pinjaman_rp * $produk['Investor_return'])/100) * $loan_term;
			
			// set status APPROVE
			$affected = $this->Pinjaman_model->approval_pinjaman($id, $jml_pinjaman_disetujui, $date_fundraise, $jml_repayment, $produk['Fundraising_period']);

			if($id && $affected){ 

				// Update Log Transaksi Pinjaman
				$inlog['ltp_total_pinjaman_disetujui'] = $jml_pinjaman_disetujui;
				$inlog['ltp_admin_fee']                = $admin_fee;
				$inlog['ltp_bunga_pinjaman']           = $bunga;
				$inlog['ltp_jml_angsuran']             = $jml_repayment;
				$inlog['ltp_lama_angsuran']            = 1;
				$inlog['ltp_tgl_jatuh_tempo']          = $tgl_jatuh_tempo;
				$inlog['ltp_platform_fee']             = $inlog['ltp_admin_fee'];
				$inlog['ltp_lender_fee']               = $lender_fee;
				
				$this->Pinjaman_model->update_log_pinjaman($inlog, $loan_data['Master_loan_id']);

				//$this->send_mail($loan_data, $jml_pinjaman_disetujui);

				/*// --------- Create pdf perjanjian ---------
				$memberdata = $this->Member_model->get_usermember_less($loan_data['pinjam_member_id']);

				$output['member']   = $memberdata;
				$output['tgl']      = parseDateTimeIndex(date('Y-m-d'));
				$html        = $this->load->view('email/vpinjaman-kilat', $output, TRUE);
				$attach_file = $this->create_pdf($html, $id, $loan_data);
				// --------- End create pdf perjanjian ---------

				$this->send_mail($loan_data, $jml_pinjaman_disetujui, $attach_file);*/

				$this->session->set_userdata('message','Success APPROVE Transaction.');
				$this->session->set_userdata('message_type','success');
			}
		}else{
			$this->session->set_userdata('message','No Data selected.');
			$this->session->set_userdata('message_type','warning');
		}
		
		redirect('transaksi_pinjaman_kilat');
	}

	function send_mail($loan_data, $jml_pinjaman_disetujui, $file)
	{
		$userdata = $this->Member_model->get_user_ojk_by($loan_data['User_id']);
		
		$html_content = '
        Hai '.$userdata['Nama_pengguna'].',<br><br>

            Pengajuan Pinjaman Anda di website BKDana telah disetujui.
            <br><br>
            
            Berikut detail pinjaman Anda:<br>

            No. Transaksi: '.$loan_data['Master_loan_id'].'
            <br>
            Jenis Transaksi: Pinjaman Kilat
            <br>
            Jumlah Pinjaman: Rp '.number_format($loan_data['Jml_permohonan_pinjaman']).'
            <br>
            Jumlah Pinjaman diterima: Rp '.number_format($jml_pinjaman_disetujui).'
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
		// Attachments
	    // $mail->addAttachment('/var/www/html/data-file-bkd/pinjaman-kilat.docx', 'perjanjian-pinjaman-kilat.docx');
	    $mail->addAttachment($file['output_file'], $file['filename']);
	    /* end update */

        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }	

        return TRUE;
	
	}

	function reject()
	{
		$this->User_model->has_login();

		$id = antiInjection(trim($this->uri->segment(3)));

		if (!empty($id))
		{ 
			$loan_data = $this->Pinjaman_model->get_pinjaman_byid($id);
			
			if (count($loan_data)>0) {
				$affected = $this->Pinjaman_model->reject_pinjaman($id);
			}else{
				$affected = FALSE;
			}

			if ($id && $affected) {
				$this->send_mail_reject($loan_data);
				$this->session->set_userdata('message','Transaksi '.$id.' was Rejected.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','No Data selected.');
				$this->session->set_userdata('message_type','warning');
			}
		}
		
		redirect('transaksi_pinjaman_kilat');
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
            Jenis Transaksi: Pinjaman Kilat
            <br>
            Jumlah Pinjaman: Rp '.number_format($loan_data['Jml_permohonan_pinjaman']).'
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

			$output['PAGE_TITLE'] = 'Pinjaman Kilat';

			$mainData['top_css']   = '';
			$mainData['top_js']    = '';
			$mainData['bottom_js'] = '';
			$mainData['bottom_js'] .= add_js('js/data/transaksi_pinjaman_mikro.js');
			$mainData['bottom_js'] .= add_js('js/global.js');

			$output['EDIT'] = $this->Log_transaksi_model->get_log_transaksi_pinjam($id);
			$output['pendana'] = $this->Log_transaksi_model->get_log_transaksi_pendana_byloan($id);

			//_d($output['pendana']);

			$mainData['mainContent']  = $this->load->view('loan/vpinjaman_detail', $output, true);

			$this->load->view('vbase',$mainData);
		}
	}

	function create_pdf($html, $ordercode, $data)
	{
		require_once(APPPATH.'libraries/TCPDF/tcpdf.php');
		$filename = 'perjanjian-pinjaman-kilat-'.$ordercode.'.pdf';

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('BKDana');
		$pdf->SetTitle('Perjanjian Pinjaman Kilat '.$ordercode);
		$pdf->SetSubject('Perjanjian Pinjaman Kilat '.$ordercode);

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
	

	/*function approve()
	{
		// ------ langsung approve saldo masuk ke peminjam ------
		$this->User_model->has_login();

		$affected = FALSE;
		$id = antiInjection($this->uri->segment(3));

		// get data pinjaman
		$loan_data = $this->Pinjaman_model->get_pinjaman_byid($id);

		if ( count($loan_data) > 0 && !empty($id)) {
			// get tipe produk: kilat, mikro ,usaha
			$produk = $this->Product_model->get_product_by($loan_data['Product_id']);
			$tipe_produk = $produk['type_of_business_id'];
		
			$pinjaman_rp = $loan_data['Jml_permohonan_pinjaman'];

			// hitung total pinjaman + bunga
			$bunga       = $pinjaman_rp * ($produk['Interest_rate'] + $produk['Platform_rate'] + $produk['Investor_return'] + $produk['Secured_loan_fee']);
			$bunga       = $bunga/100;
			$total_pinjaman_plus_bunga = $pinjaman_rp + $bunga;

			// hitung total fundraise date (tgl maximum pendanaan)
			$fundraise = $produk['Fundraising_period'];
			$date_fundraise = date('Y-m-d', strtotime('+ '.$fundraise.' days'));
			

			if ($tipe_produk == '3' OR $tipe_produk == '4') {
				// mikro, usaha
				$affected    = $this->Pinjaman_model->pending_pinjaman($id, $total_pinjaman_plus_bunga, $date_fundraise);
			}else{
				// kilat
				$affected    = $this->Pinjaman_model->approve_pinjaman($id, $total_pinjaman_plus_bunga);
			}

			if($id && $affected){

				if ($tipe_produk == 1) {
					// -- Hanya produk kilat yang langsung masuk Saldo ke Wallet  --
					
					$check_wallet = $this->Pinjaman_model->get_wallet_user($loan_data['User_id']);

					$nowdate = date('Y-m-d H:i:s');
					$tambahsaldo = $loan_data['Jml_permohonan_pinjaman'];

					if (is_array($check_wallet) && isset($check_wallet['User_id'])) {
						// update master
						$this->Wallet_model->update_master_wallet_saldo($loan_data['User_id'], $tambahsaldo);

						$master_wallet_id = $check_wallet['Id'];
					}else{
						// insert master
						$inwallet['Date_create']      = $nowdate;
						$inwallet['User_id']          = $loan_data['User_id'];
						$inwallet['Amount']           = $tambahsaldo;
						$inwallet['wallet_member_id'] = $loan_data['pinjam_member_id'];

						$master_wallet_id = $this->Pinjaman_model->insert_master_wallet($inwallet);
					}

					// detail wallet
					$detail_w['Id']               = $master_wallet_id;
					$detail_w['Date_transaction'] = $nowdate;
					$detail_w['Amount']           = $tambahsaldo;
					$detail_w['Notes']            = NULL;

					$this->Pinjaman_model->insert_detail_wallet($detail_w);

					$this->send_mail($loan_data, $total_pinjaman_plus_bunga);
				}


				$this->session->set_userdata('message','Data has been Approved.');
				$this->session->set_userdata('message_type','success');
			}
		}else{
			$this->session->set_userdata('message','No Data selected.');
			$this->session->set_userdata('message_type','warning');
		}
		
		redirect('transaksi_pinjaman_kilat');
	}*/
}