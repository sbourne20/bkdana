<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_pinjaman_agri_draft extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('User_model');
		$this->load->model('Pinjaman_model');
		$this->load->model('Product_model');
		$this->load->model('Wallet_model');
		$this->load->model('Member_model');
		$this->load->model('Log_transaksi_model');
		
		//error_reporting(E_ALL);
	}

	function index()
	{
		$this->User_model->has_login();

		$output['PAGE_TITLE'] = 'Pinjaman Agri';

		$mainData['top_css']   = '';
		$mainData['top_js']    = '';
		$mainData['bottom_js'] = '';
		$mainData['bottom_js'] .= add_js('js/data/transaksi_pinjaman_agri_draft.js');
		$mainData['bottom_js'] .= add_js('js/global.js');

		$mainData['mainContent']  = $this->load->view('loan/vagri_list', $output, true);

		$this->load->view('vbase',$mainData);
	}

	function json()
	{			
		$data = $this->Pinjaman_model->get_all_agri_draft();
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

	function edit()
	{
		$this->User_model->has_login();

		$ID = antiInjection(trim($this->uri->segment(3)));

		if ($ID != '' && strlen($ID) > 3) 
		{
			$output['PAGE_TITLE'] = 'EDIT Pinjaman';
			$mainData['top_css']   ="";
			$mainData['top_js']    ="";
			$mainData['bottom_js'] ="";
			
			$mainData['top_css']  .= add_css("plugins/fileinput/fileinput.min.css");

			$mainData['top_js'] .= add_js("plugins/fileinput/fileinput.min.js");
			$mainData['top_js'] .= add_js('plugins/ckeditor/ckeditor.js');
			$mainData['top_js'] .= add_js("plugins/friendurl/jquery.friendurl.min.js");
			$mainData['top_js'] .= add_js("plugins/autonumeric/autoNumeric.js");

			$mainData['bottom_js'] .= add_js('js/select2-data.js');
			$mainData['bottom_js'] .= add_js('js/global.js');
			$mainData['bottom_js'] .= add_js('js/autoNumeric-init.js');

			$output['EDIT']       = $this->Pinjaman_model->get_pinjaman_byid($ID);

			//_d($output['EDIT']);
			
			$mainData['mainContent'] = $this->load->view('loan/vpinjaman_agri_form', $output, TRUE);
			$this->load->view('vbase',$mainData);
		}
	}

	function submit_edit()
	{
		if($_SERVER["REQUEST_METHOD"] == "POST") {
			$post = $this->input->post(NULL, TRUE);
			
			$transaksi_id       = trim($post['transaksi_id']);
			$pinjaman_pokok     = trim($post['pinjaman_pokok']);
			$pinjaman_disetujui = trim($post['pinjaman_disetujui']);

			$filter_pinj        = explode('.', $pinjaman_disetujui);
			$pinjaman_disetujui = str_replace(',', '', $filter_pinj[0]);

			if ($transaksi_id != '' && $pinjaman_pokok !='' && $pinjaman_disetujui != '')
			{
				$admin_fee = $pinjaman_pokok - $pinjaman_disetujui;

				// update profil pinjaman
				$uppj['Jml_permohonan_pinjaman_disetujui'] = $pinjaman_disetujui;
				$this->Pinjaman_model->update_profil_pinjaman($uppj, $transaksi_id);
				
				// update log pinjaman
				$ulp['ltp_total_pinjaman_disetujui'] = $pinjaman_disetujui;
				$ulp['ltp_admin_fee']                = $admin_fee;
				$this->Pinjaman_model->update_log_pinjaman($ulp, $transaksi_id);

				$this->session->set_userdata('message','Data has been updated.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','Error. Can not update.');
				$this->session->set_userdata('message_type','error');
			}

			redirect('transaksi-pinjaman-agri-draft');
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

		if ( !empty($id) )
		{
			// get data pinjaman
			$loan_data = $this->Pinjaman_model->get_pinjaman_byid($id);

			if ( count($loan_data) > 0) {
				// get tipe produk: kilat, mikro ,usaha
				$produk = $this->Product_model->get_product_by($loan_data['Product_id']);
				$tipe_produk = $produk['type_of_business_id'];
			
				$pinjaman_rp = $loan_data['Jml_permohonan_pinjaman'];

				// ---------- hitung total pinjaman disetujui ------------			
				$revenue                = ($pinjaman_rp * $produk['Fee_revenue_share'])/100;
				$jml_pinjaman_disetujui = $pinjaman_rp - ($revenue + $revenue);
				$admin_fee              = ($pinjaman_rp * $produk['Secured_loan_fee'])/100;
				// ----------- End hitung total pinjaman disetujui -------------------

				// ------- Hitung jumlah angsuran per minggu --------
				$totalweeks   = 4 * $produk['Loan_term'];
				$jml_angsuran = ($pinjaman_rp + ( $pinjaman_rp * ($produk['Interest_rate'] * $produk['Loan_term']))/100 )/$totalweeks;
				//$jml_angsuran = ceil($jml_angsuran*100)/100; // 908333.33333 => 908333.34
				$jml_repayment     = round($jml_angsuran);
				$total_angsuran_rp = $jml_repayment*$totalweeks;
				$bunga             = $total_angsuran_rp - $pinjaman_rp;
				// ------- End of Hitung jumlah angsuran per minggu --------

				$loan_term = $produk['Loan_term'];
				$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$loan_term." months"));

				// Frozen FEE
				$frozen_fee = ($pinjaman_rp * $produk['Fee_revenue_share'])/100;

				// Platform  fee = P*D*C/Jumlah Minggu
				$angsuran_platform_fee = ($pinjaman_rp * ($produk['Platform_rate'] * $produk['Loan_term'])/100) / $totalweeks;

				// LO = (P*E*C)/Jumlah Minggu
				$angsuran_LO = ($pinjaman_rp * ($produk['Loan_organizer'] * $produk['Loan_term'])/100) / $totalweeks;

				$lender_fee  = (($pinjaman_rp*$produk['Investor_return'] * $loan_term)/100)/$totalweeks;
				/*echo 'platform fee: '.$angsuran_platform_fee;
				echo '<br>';
				echo 'LO: '.$angsuran_LO;
				echo '<br>';
				echo $admin_fee;
				echo '<br>';
				echo $jml_pinjaman_disetujui;
				echo '<br>';
				echo $bunga;
				echo '<br>';
				echo 'jml minggu:'. $totalweeks;
				echo '<br>';
				echo 'jml angsuran:'. ($jml_repayment);
				echo '<br>';
				echo $total_angsuran_rp;
				echo '<br>';
				echo $tgl_jatuh_tempo;
				exit();*/

				// -------- hitung total fundraise date (tgl maximum pendanaan) --------
				$fundraise = $produk['Fundraising_period'];
				$date_fundraise = date('Y-m-d', strtotime('+ '.$fundraise.' days'));
				// -------- End of hitung total fundraise date (tgl maximum pendanaan) ------

				if ($tipe_produk == '3' OR $tipe_produk == '4' OR $tipe_produk == '5') {
					// pinjaman mikro
					$affected = $this->Pinjaman_model->draft_pinjaman($id, $jml_pinjaman_disetujui, $date_fundraise, $total_angsuran_rp, $produk['Fundraising_period']);
				}

				if($affected){

					// Log Transaksi Pinjaman
					$inlog['ltp_Id_pengguna']              = $loan_data['User_id'];
					$inlog['ltp_Master_loan_id']           = $loan_data['Master_loan_id'];
					$inlog['ltp_total_pinjaman']           = $pinjaman_rp;
					$inlog['ltp_total_pinjaman_disetujui'] = $jml_pinjaman_disetujui;
					$inlog['ltp_admin_fee']                = $admin_fee;
					$inlog['ltp_bunga_pinjaman']           = $bunga;
					$inlog['ltp_jml_angsuran']             = $jml_repayment;
					$inlog['ltp_lama_angsuran']            = $totalweeks;
					$inlog['ltp_tgl_jatuh_tempo']          = $tgl_jatuh_tempo;
					$inlog['ltp_frozen']                   = $frozen_fee;
					$inlog['ltp_platform_fee']             = $angsuran_platform_fee;
					$inlog['ltp_LO_fee']                   = $angsuran_LO;
					$inlog['ltp_lender_fee']               = $lender_fee;
					$inlog['ltp_product_title']            = $produk['product_title'];
					$inlog['ltp_product_id']               = $produk['Product_id'];
					$inlog['ltp_product_interest_rate']    = $produk['Interest_rate'];
					$inlog['ltp_product_loan_term']        = $produk['Loan_term'];
					$inlog['ltp_product_platform_rate']    = $produk['Platform_rate'];
					$inlog['ltp_product_loan_organizer']   = $produk['Loan_organizer'];
					$inlog['ltp_product_investor_return']  = $produk['Investor_return'];
					$inlog['ltp_product_revenue_share']    = $produk['Fee_revenue_share'];
					$inlog['ltp_product_secured_loan_fee'] = $produk['Secured_loan_fee'];
					$inlog['ltp_product_interest_rate_type'] = $produk['type_of_interest_rate'];
					$inlog['ltp_product_pph']                = $produk['PPH'];
					$inlog['ltp_type_of_business_id']      = $produk['type_of_business_id'];
					$inlog['ltp_loan_organizer_id']        = 1;
					$inlog['ltp_date_created']             = date('Y-m-d H:i:s');
					$this->Pinjaman_model->insert_log_transaksi($inlog);

					$this->session->set_userdata('message','Data has been Approved.');
					$this->session->set_userdata('message_type','success');
				}
			}else{
				$this->session->set_userdata('message','No Data selected.');
				$this->session->set_userdata('message_type','warning');	
			}
		}
		
		redirect('transaksi-pinjaman-agri-draft');
	}

	function detail_transaksi()
	{
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

			//_d($output['EDIT']);

			$mainData['mainContent']  = $this->load->view('loan/vpinjaman_detail_agri', $output, true);

			$this->load->view('vbase',$mainData);
		}
	}

	/*function send_mail($loan_data, $jml_pinjaman_disetujui, $jml_angsuran)
	{
		$userdata = $this->Member_model->get_user_ojk_by($loan_data['User_id']);
		
		$html_content = '
        Hai '.$userdata['Nama_pengguna'].',<br><br>

            Pengajuan Pinjaman Anda di BKDana.com telah disetujui.
            <br><br>
            
            Berikut detail pinjaman Anda:<br>

            No. Transaksi: '.$loan_data['Master_loan_id'].'
            <br>
            Jenis Transaksi: Pinjaman Mikro
            <br>
            Jumlah Pinjaman: Rp '.number_format($loan_data['Jml_permohonan_pinjaman']).'
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

            	&copy; BKDana.com, '.date("Y").'. All rights reserved.
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
        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }	

        return TRUE;
	
	}*/

	function reject()
	{
		$this->User_model->has_login();

		$id = antiInjection(trim($this->uri->segment(3)));

		if (!empty($id))
		{
			$loan_data = $this->Pinjaman_model->get_pinjaman_byid($id);
			
			if (count($loan_data) > 0) {
				$affected = $this->Pinjaman_model->reject_pinjaman($id);
			}else{
				$affected = FALSE;
			}

			if ($affected) {
				$this->session->set_userdata('message','Transaksi '.$id.' was Rejected.');
				$this->session->set_userdata('message_type','success');
			}else{
				$this->session->set_userdata('message','No Data selected.');
				$this->session->set_userdata('message_type','warning');
			}
		}		
		redirect('transaksi_pinjaman_agri');
	}
}