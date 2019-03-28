<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cronjob_jatuh_tempo extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Pinjaman_model');
		$this->load->model('Wallet_model');
		$this->load->model('Member_model');
		$this->load->model('Product_model');
		$this->load->model('Log_transaksi_model');
		$this->load->model('Cronjob_model');
		include(APPPATH.'libraries/phpmailer-5.2.23/PHPMailerAutoload.php');
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
		ini_set('max_execution_time', 600);
	}
	function index(){
	}
	//tambahan baru
	function get_denda(){
		//$data3 = $this->Cronjob_model->get_user();
		//$data3 = $this->Cronjob_model->get_jatuh_tempo();
		//foreach ($data3 as $key1 ) {
		//$idus = $data3['id_pengguna'];
		//}
		
		$recordrepayments = $this->Cronjob_model->get_record_repayment();
		
		//$data1 = $this->Cronjob_model->get_my_denda1();
		//$totaldata = count($data);
		$totaldata = count($recordrepayments);
		echo 'Found '.$totaldata.'</br>';
		//echo $recordrepayments['type_of_business_id'];
		//echo 'id '.$key1['Id_pengguna'].'</br>';
		//echo 'grup '.$key1['id_user_group'];


		foreach ($recordrepayments as $recordrepayment ) {
			//$data1 = $this->Cronjob_model->get_tgl_jth_tempo($key['Master_loan_id']);
		
			//$totaldata1 = count($data1);
			//echo 'Found '.$totaldata1;
			//print_r($recordrepayment);
			$grup = $recordrepayment['id_user_group'];
			$master_loan_id =$recordrepayment['Master_loan_id'];
			//$business_id = $recordrepayment['type_of_business_id'];
			$charge_type = $recordrepayment['charge_type'];

			if($recordrepayment['type_of_business_id']=='1'){
				
			
				//tambahan baru denda keterlambatan
				if($charge_type=='1'){
				
					$hitungdenda = ($recordrepayment['charge'] * $recordrepayment['jml_kredit'])/100;
					$bayar_denda = ($hitungdenda *  $recordrepayment['diff']);
					$this->Cronjob_model->update_record_repayment($bayar_denda, $recordrepayment['Master_loan_id'], $recordrepayment['tgl_jatuh_tempo']);

					//insert log record repayment
					$nowdate     = date('Y-m-d H:i:s');

					$logrp['Master_loan_id']			= $master_loan_id;
					$logrp['jml_denda']          		= $bayar_denda;
					$logrp['notes_cicilan']         	= $recordrepayment['notes_cicilan'];
					$logrp['tgl_record_repayment_log'] 	= $nowdate;
					
					$log_record_repayment = $this->Cronjob_model->insert_log_record_repayment($logrp);

					echo '</br>=========kilat===========';
					echo '</br> No Transaksi :'.$recordrepayment['Master_loan_id'].'</br>' ;
					echo 'Selisih Hari :'.$recordrepayment['diff'].'</br>' ;
					echo 'Denda yg dibayar :'.$bayar_denda.'</br>' ;
					echo '==============================';
					//batas insert log
				
				}else if($charge_type=='2'){
										
					$hitungdenda = ($recordrepayment['charge']);

					$bayar_denda = ($hitungdenda *  $recordrepayment['diff']);
					$this->Cronjob_model->update_record_repayment($bayar_denda, $recordrepayment['Master_loan_id'], $recordrepayment['tgl_jatuh_tempo']);

					//insert log record repayment
					$nowdate     = date('Y-m-d H:i:s');

					$logrp['Master_loan_id']			= $master_loan_id;
					$logrp['jml_denda']          		= $bayardenda;
					$logrp['notes_cicilan']         	= $recordrepayment['notes_cicilan'];
					$logrp['tgl_record_repayment_log'] 	= $nowdate;
					
					$log_record_repayment = $this->Cronjob_model->insert_log_record_repayment($logrp);
					//batas insert log
					echo '</br>=========Kilat===========';
					echo '</br> No Transaksi :'.$recordrepayment['Master_loan_id'].'</br>' ;
					echo 'Selisih Hari :'.$recordrepayment['diff'].'</br>' ;
					echo 'Denda yg dibayar :'.$bayar_denda.'</br>' ;
					echo '==============================';

				}
			
				//batas tambahan baru

			}else if($recordrepayment['type_of_business_id']=='3'){


			if($grup == 0){

				$bayar_denda = ($recordrepayment['charge'] * $recordrepayment['diff']);
				
				$this->Cronjob_model->update_record_repayment($bayar_denda, $recordrepayment['Master_loan_id'], $recordrepayment['tgl_jatuh_tempo']);

				//insert log record repayment
				$nowdate     = date('Y-m-d H:i:s');

				$logrp['Master_loan_id']			= $master_loan_id;
				$logrp['jml_denda']          		= $recordrepayment['charge'];
				$logrp['notes_cicilan']         	= $recordrepayment['notes_cicilan'];
				$logrp['tgl_record_repayment_log'] 	= $nowdate;
				
				$log_record_repayment = $this->Cronjob_model->insert_log_record_repayment($logrp);
				//batas insert log
					
				//}
				echo '</br>=========tanpa grup===========';
				echo '</br> No Transaksi :'.$recordrepayment['Master_loan_id'].'</br>' ;
				echo 'Selisih Hari :'.$recordrepayment['diff'].'</br>' ;
				echo 'Denda yg dibayar :'.$bayar_denda.'</br>' ;
				echo 'Bisnis Id :'.$recordrepayment['type_of_business_id'];
				echo '==============================';


			}else{

				$bayar_denda = ($recordrepayment['charge'] * $recordrepayment['diff'] / $recordrepayment['jml_belum_bayar']);
				$this->Cronjob_model->update_record_repayment($bayar_denda, $recordrepayment['Master_loan_id'], $recordrepayment['tgl_jatuh_tempo']);

				//insert log record repayment
				$nowdate     = date('Y-m-d H:i:s');

				$logrp1['Master_loan_id']			= $master_loan_id;
				$logrp1['jml_denda']          		= $recordrepayment['charge']/$recordrepayment['jml_belum_bayar'];
				$logrp1['notes_cicilan']         	= $recordrepayment['notes_cicilan'];
				$logrp1['tgl_record_repayment_log'] = $nowdate;
				
				$log_record_repayment1 = $this->Cronjob_model->insert_log_record_repayment($logrp1);
				//batas insert log

				echo '</br> No Transaksi'.$recordrepayment['Master_loan_id'].'</br>' ;
				echo 'Selisih Hari :'.$recordrepayment['diff'].'</br>' ;
				echo 'Denda yg dibayar :'.$bayar_denda.'</br>' ;
				echo 'Id User Group : '.$grup;
				//echo '</br> Grup Found : '.$ttlmembr.'</br>';
				echo '</br>Belum bayar :'.$recordrepayment['jml_belum_bayar'];
				echo '</br>Bisnis Id :'.$recordrepayment['type_of_business_id'];
				
				}
			}

		}
	}
//batas tambahan baru
// akan jatuh tempo (H-1)
	function soon()
	{
		$data = $this->Cronjob_model->get_jatuh_tempo();
		$totaldata = count($data);
		echo 'Found '.$totaldata;
			if ($totaldata > 0){
				foreach ($data as $key) {
					$tgl_jatuh_tempo = date('d/m/Y', strtotime($key['tgl_jatuh_tempo']));
					$to              = $key['mum_email'];
					$title_mail      = 'Pinjaman akan Jatuh Tempo';
					$html_content    = '
					Hai '.$key['Nama_pengguna'].',<br><br>
					Pinjaman Anda dengan nomor transaksi '.$key['Master_loan_id'].' akan jatuh tempo pada '.$tgl_jatuh_tempo.'.
					<br>
					Segera lakukan pembayaran sejumlah Rp '.number_format($key['ltp_jml_angsuran']).', agar transaksi Anda berjalan lancar.
					<br><br>
					<span style="color:#858C93;">
								Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke Email ini.
								<br><br>
								&copy; BKDana, '.date("Y").'. All rights reserved.
					</span>
								';
				$this->send_mail_peminjam($to, $title_mail, $html_content);
			}
		}
	}
	// lewat masa jatuh tempo (H-1)
	function passed()
	{
	$data = $this->Cronjob_model->get_pasca_jatuh_tempo();
	$totaldata = count($data);
	echo 'Found '.$totaldata;
		if ($totaldata > 0){
			foreach ($data as $key) {
				// update mod_tempo
				$injt['telah_jatuh_tempo'] = 1;
				$this->Cronjob_model->update_jatuh_tempo($injt, $key['tempo_id']);
				$tgl_jatuh_tempo = date('d/m/Y', strtotime($key['tgl_jatuh_tempo']));
				// send email
				$to              = $key['mum_email'];
				$title_mail      = 'Pinjaman telah melewati masa Jatuh Tempo';
				$html_content    = '
				Hai '.$key['Nama_pengguna'].',<br><br>
				Pinjaman Anda dengan nomor transaksi '.$key['Master_loan_id'].' telah jatuh tempo pada '.$tgl_jatuh_tempo.'.
				<br>
				Segera lakukan pembayaran sejumlah Rp '.number_format($key['ltp_jml_angsuran']).', agar transaksi Anda berjalan lancar.
				<br><br>
				<span style="color:#858C93;">
							Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke Email ini.
							<br><br>
							&copy; BKDana, '.date("Y").'. All rights reserved.
				</span>
					';
				$this->send_mail_peminjam($to, $title_mail, $html_content);
			}
		}
	}
	// Email Notifikasi akan jatuh tempo
	function send_mail_peminjam($to, $title, $html_content)
	{
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
		$mail->AddAddress($to);
		$mail->Subject     = $title;
		$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($html_content);
		$mail->SMTPDebug   = 0;

		if(!$mail->Send()) {
		//echo $mail->ErrorInfo;exit;
			$result = 'failed';
		}else{
		echo 'email terkirim';
					$result = 'success';
		}
		return TRUE;
	}

	function jatuh_tempo_sebulan()
	{
		$data = $this->Cronjob_model->get_pinjaman_taklancar();
		$totaldata = count($data);
		echo 'Found '.$totaldata;
		
		if ($totaldata > 0){
			foreach ($data as $key) {
				$tgl_jatuh_tempo = date('d/m/Y', strtotime($key['tgl_jatuh_tempo']));
				// send email
				$to              = $key['mum_email'];
				$title_mail      = 'Pinjaman telah Jatuh Tempo lebih dari 1 bulan';
				$html_content    = '
				Hai '.$key['Nama_pengguna'].',<br><br>
				Pinjaman Anda dengan nomor transaksi '.$key['Master_loan_id'].' telah jatuh tempo pada '.$tgl_jatuh_tempo.'.
				<br>
				Segera lakukan pembayaran agar transaksi Anda berjalan lancar.
				<br><br>
				<span style="color:#858C93;">
							Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke Email ini.
							<br><br>
							&copy; BKDana, '.date("Y").'. All rights reserved.
				</span>
							';
				$this->send_mail_peminjam($to, $title_mail, $html_content);
			}
		}
	}
}