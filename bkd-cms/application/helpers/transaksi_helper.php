<?php 

function insert_detail_wallet($master_wallet_id, $nowdate, $tambahsaldo, $notes, $tipedana, $id_pengguna, $kode_transaksi, $balance)
{
	$CI =& get_instance();

	$detail_w['Id']               = $master_wallet_id;
	$detail_w['Date_transaction'] = $nowdate;
	$detail_w['Amount']           = $tambahsaldo;
	$detail_w['Notes']            = $notes;
	$detail_w['tipe_dana']        = $tipedana;
	$detail_w['User_id']          = $id_pengguna;
	$detail_w['kode_transaksi']   = $kode_transaksi;
	$detail_w['balance']          = $balance;

	$CI->Wallet_model->insert_detail_wallet($detail_w);
} 

function send_mail($loan_data, $jml_pinjaman_disetujui)
{
	$userdata = $this->Member_model->get_user_ojk_by($loan_data['User_id']);
	
	$html_content = '
    Hai '.$userdata['Nama_pengguna'].',<br><br>

        Pengajuan Pinjaman Anda di BKDana.com telah disetujui.
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

}

?>