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

	//tambahan baru
	function get_denda(){
		$data = $this->Cronjob_model->get_denda2();
		
		//$data1 = $this->Cronjob_model->get_my_denda1();
		//$totaldata = count($data);
		$totaldata = count($data);

		echo 'Found '.$totaldata;

		foreach ($data as $key ) {
			$data1 = $this->Cronjob_model->get_denda1($key['Master_loan_id']);
		
		//$totaldata1 = count($data1);
		//echo 'Found '.$totaldata1;
		
		}
		$data2 = $this->Cronjob_model->get_my_denda1($key['Master_loan_id']);
		
		$denda 			= $data2['charge'];
		$nowdate 		= date('Y-m-d');

		if (count($data1) > 0) {
			foreach ($data1 as $rep) {
		
			$info = getdate();
		    if ($info['hours'] >= 23 && $info['minutes'] >= 59) {
		
		$tgl_jth_tempo1 	= $rep['tgl_jatuh_tempo'];
		$tgl_jth_tempo = date_format(date_create_from_format('Y-m-d H:i:s', $tgl_jth_tempo1), 'Y-m-d');

		$date1=date_create("$tgl_jth_tempo");
        $date2=date_create("$nowdate");
        $diff=date_diff($date1,$date2);
        $diff1 = $diff->format("%R%a");

        echo '</br>';
        echo $diff1;
        echo '</br>';
        echo 'tgl jth tempo : </br>';
        echo $tgl_jth_tempo;
        echo '</br>';

        $array[] = array($diff1);

        if(number_format($diff1 > 0)){
                                                   
        $bayar_denda = ($denda * $diff1);

        $repayment['jml_denda']      = $bayar_denda;

        echo $repayment['jml_denda'];
        echo '  ';
        echo $key['Master_loan_id'];
        echo '</br> jatuhh tempo '.$rep['tgl_jatuh_tempo'];

       /* date_default_timezone_set('Asia/Bangkok');
		$hours = date('h:i:s A', time());
		if ($hours == 00:00:00 AM){*/

		$this->Cronjob_model->update_record_repayment($repayment['jml_denda'], $key['Master_loan_id'], $rep['tgl_jatuh_tempo']);
		//}
        
	    }else if(number_format($diff1 < 0)){

                                                    
        $bayar_denda =  0;
        //$bayar_denda = ($denda * $diff1);

        $repayment['jml_denda']      = $bayar_denda;


        echo $repayment['jml_denda'];
        echo '  ';
        echo $key['Master_loan_id'];
        echo '</br> tgl sekarang '.$nowdate;

       $this->Cronjob_model->update_record_repayment($repayment['jml_denda'], $key['Master_loan_id'], $rep['tgl_jatuh_tempo']);

    	}
	    }else {
	    	echo "belum waktunya update";
	    }


         }
         }                                         
        //$denda1 = str_replace('/', '-', $cicilan_duedate);
        //$denda2 = date('Y-m-d', strtotime($denda1));
		echo '</br> batas';
		echo '</br> tgl sekarang </br>';
		echo $nowdate;
		echo '</br>';
		//echo $tgl_jth_tempo;
        //baru


		
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