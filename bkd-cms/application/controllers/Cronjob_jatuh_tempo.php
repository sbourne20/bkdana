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

	function index(){}

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