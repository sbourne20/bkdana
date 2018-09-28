<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob extends CI_Controller
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
	}

	function index()
	{}

	function find_expired_pinjaman_mikro()
	{
		// cari pinjaman mikro dg date_fundraise sudah lewat tgl sekarang berstatus pending (pending = dlm proses mencari dana), set status mjd expired

		$data = $this->Cronjob_model->find_mikro_expired();

		//_d($data);exit();
		
		$nowdate = date('Y-m-d');
		$totaldata = count($data);

		if ( $totaldata > 0) {
			 echo $totaldata.' Found transaction.';

			foreach ($data as $key ) {

				$iduser_peminjam          = $key['User_id'];
				$idmember_peminjam        = $key['pinjam_member_id'];
				$total_kredit             = $key['jml_kredit'];
				$total_pinjaman_disetujui = $key['Jml_permohonan_pinjaman_disetujui'];

				if (!empty($total_kredit)) 
				{
					// cek apakah pembiayaan mencapai 80% atau lebih
					$kredit_percentage = ($total_kredit/$total_pinjaman_disetujui) * 100;

					/*echo '<br>'.$kredit_percentage .'<br>';
					exit();*/

					if ($kredit_percentage >= 80)
					{
						echo ' ---------------- sudah mencapai 80% ----------------';

						// Approve pinjaman dan pendana
						$approved = $this->Cronjob_model->approve_pinjaman($key['Master_loan_id']);
						if ($approved) {						

							$this->Cronjob_model->approve_pendanaan($key['Master_loan_id']);

							if ($total_kredit >= $total_pinjaman_disetujui) {
								$saldo_masuk_kepeminjam = $total_pinjaman_disetujui;
							}else{
								$saldo_masuk_kepeminjam = $total_kredit;
							}

							$check_wallet = $this->Wallet_model->get_wallet_user($iduser_peminjam);
							
							// saldo masuk ke peminjam
							if (count($check_wallet) > 1 && isset($check_wallet['User_id'])) {
								// update master
								$this->Wallet_model->update_master_wallet_saldo($iduser_peminjam, $saldo_masuk_kepeminjam);

								$master_wallet_id = $check_wallet['Id'];
							}else{
								// insert master
								$inwallet['Date_create']      = $nowdate;
								$inwallet['User_id']          = $iduser_peminjam;
								$inwallet['Amount']           = $saldo_masuk_kepeminjam;
								$inwallet['wallet_member_id'] = $idmember_peminjam;

								$master_wallet_id = $this->Wallet_model->insert_master_wallet($inwallet);
							}

							// detail wallet
							$detail_wal['Id']               = $master_wallet_id;
							$detail_wal['Date_transaction'] = $nowdate;
							$detail_wal['Amount']           = $saldo_masuk_kepeminjam;
							$detail_wal['Notes']            = 'Pemberian dana pinjaman No.' . $key['Master_loan_id'];
							$detail_wal['tipe_dana']        = 1;
							$detail_wal['User_id']          = $check_wallet['User_id'];
							$detail_wal['kode_transaksi']   = $key['Master_loan_id'];
							$detail_wal['balance']          = $check_wallet['Amount'] + $detail_wal['Amount'];

							$this->Wallet_model->insert_detail_wallet($detail_wal);

							// ---------- Create Tgl Jatuh Tempo -----------
							for ($i=1; $i <= $key['ltp_lama_angsuran']; $i++) 
							{ 
								$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$i." week"));

								$intempo['kode_transaksi']  = $key['Master_loan_id'];
								$intempo['tgl_jatuh_tempo'] = $tgl_jatuh_tempo;
								$intempo['no_angsuran']     = $i;
								$this->Cronjob_model->insert_table_tempo($intempo);
							}
							// ---------- End Create Tgl Jatuh Tempo -----------

							$this->send_mail_peminjam($key, $total_pinjaman_disetujui);

							// looping pendanaan utk kirim email ke pendana
							$investdata = $this->Cronjob_model->get_pendana_bytransaksi($key['Master_loan_id']);
							foreach ($investdata as $inv) {
								$this->send_mail_pendana($inv);							
							}
						}
					}else{
						 echo ' -------------- belum 80% maka menjadi expired ----------------';

						$investdata = $this->Cronjob_model->get_pendana_bytransaksi($key['Master_loan_id']);

						//_d($investdata);exit();

						if (count($investdata) > 0)
						{
							//echo 'ada';
							foreach ($investdata as $inv) {

								$check_wallet = $this->Wallet_model->get_wallet_user($inv['User_id']);

								// kembalikan saldo pendana
								$this->Cronjob_model->kembalikan_saldo($inv['User_id'], $inv['Jml_penawaran_pemberian_pinjaman_disetujui']);
							
								// detail wallet
								$detail_w['Id']               = $check_wallet['Id'];
								$detail_w['Date_transaction'] = $nowdate;
								$detail_w['Amount']           = $inv['Jml_penawaran_pemberian_pinjaman_disetujui'];
								$detail_w['Notes']            = 'Pengembalian Saldo Pendana dari transaksi Pinjaman No. ' . $inv['Master_loan_id'];
								$detail_w['tipe_dana']        = 1;
								$detail_w['User_id']          = $check_wallet['User_id'];
								$detail_w['kode_transaksi']   = $key['Master_loan_id'];
								$detail_w['balance']          = $check_wallet['Amount'] + $detail_w['Amount'];
								$this->Wallet_model->insert_detail_wallet($detail_w);

								// set status pendanaan mjd expired
								$this->Cronjob_model->set_pendanaan_expired($inv['Id']);
							}
						}

						// set status pinjaman expired
						$this->Cronjob_model->set_pinjaman_expired($key['Master_loan_id']);
					}
				}else{
					echo 'kredit kosong (tidak ada pendana)';

					// set status pinjaman expired
					$this->Cronjob_model->set_pinjaman_expired($key['Master_loan_id']);
				}				
			}
		}else{
			echo 'Transaction not found.';
		}
	}

	function find_expired_pinjaman_kilat()
	{
		$data = $this->Cronjob_model->find_kilat_expired();

		/*_d($data);
		exit();*/
		
		$nowdate     = date('Y-m-d');
		$nowdatetime = date('Y-m-d H:i:s');
		$totaldata   = count($data);

		if ( $totaldata > 0) {
			 echo $totaldata.' Found transaction.';

			foreach ($data as $key ) {

				$iduser_peminjam          = $key['User_id'];
				$idmember_peminjam        = $key['pinjam_member_id'];
				$total_kredit             = $key['jml_kredit'];
				$total_pinjaman           = $key['Jml_permohonan_pinjaman'];
				$total_pinjaman_disetujui = $key['Jml_permohonan_pinjaman_disetujui'];

				if (!empty($total_kredit)) 
				{
					// cek apakah pembiayaan mencapai 80% atau lebih
					$kredit_percentage = ($total_kredit/$total_pinjaman) * 100;

					/*echo '<br>'.$kredit_percentage .'<br>';
					exit();*/

					if ($kredit_percentage >= 80)
					{
						echo ' ---------------- sudah mencapai 80% ----------------';

						// Approve pinjaman
						$approved = $this->Cronjob_model->approve_pinjaman($key['Master_loan_id']);

						if ($approved) {
							// Approve pendana
							$this->Cronjob_model->approve_pendanaan($key['Master_loan_id']);

							$check_wallet = $this->Wallet_model->get_wallet_user($iduser_peminjam);
							
							if ($total_kredit >= $total_pinjaman_disetujui) {
								$saldo_masuk_kepeminjam = $total_pinjaman_disetujui;
							}else{
								$saldo_masuk_kepeminjam = $total_kredit;
							}

							// saldo masuk ke peminjam
							if (count($check_wallet) > 1 && isset($check_wallet['User_id'])) {
								// update master							
								$this->Wallet_model->update_master_wallet_saldo($iduser_peminjam, $saldo_masuk_kepeminjam);

								$master_wallet_id = $check_wallet['Id'];
							}else{
								// insert master
								$inwallet['Date_create']      = $nowdate;
								$inwallet['User_id']          = $iduser_peminjam;
								$inwallet['Amount']           = $saldo_masuk_kepeminjam;
								$inwallet['wallet_member_id'] = $idmember_peminjam;

								$master_wallet_id = $this->Wallet_model->insert_master_wallet($inwallet);
							}

							// detail wallet
							$detail_wal['Id']               = $master_wallet_id;
							$detail_wal['Date_transaction'] = $nowdate;
							$detail_wal['Amount']           = $saldo_masuk_kepeminjam;
							$detail_wal['Notes']            = 'Pemberian dana pinjaman';
							$detail_wal['tipe_dana']        = 1;
							$detail_wal['User_id']          = $check_wallet['User_id'];
							$detail_wal['kode_transaksi']   = $key['Master_loan_id'];
							$detail_wal['balance']          = $check_wallet['Amount'] + $detail_wal['Amount'];

							$this->Wallet_model->insert_detail_wallet($detail_wal);

							// ---------- Create Tgl Jatuh Tempo -----------
							$loan_term       = $key['ltp_product_loan_term'];
							$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$loan_term." days"));

							$intempo['kode_transaksi']  = $key['Master_loan_id'];
							$intempo['tgl_jatuh_tempo'] = $tgl_jatuh_tempo;
							$intempo['no_angsuran']     = 1;
							$this->Cronjob_model->insert_table_tempo($intempo);
							// ---------- End Create Tgl Jatuh Tempo -----------

							$this->send_mail_peminjam($key, $saldo_masuk_kepeminjam);

							// looping pendanaan utk kirim email ke pendana
							$investdata = $this->Cronjob_model->get_pendana_bytransaksi($key['Master_loan_id']);
							foreach ($investdata as $inv) {
								$this->send_mail_pendana($inv);							
							}
						}
					}else{
						 echo ' -------------- belum 80%  ----------------';

						$investdata = $this->Cronjob_model->get_pendana_bytransaksi($key['Master_loan_id']);

						//_d($investdata);exit();

						if (count($investdata) > 0)
						{
							//echo 'ada';
							foreach ($investdata as $inv) {

								$check_wallet = $this->Wallet_model->get_wallet_user($inv['User_id']);

								// kembalikan saldo pendana
								$this->Cronjob_model->kembalikan_saldo($inv['User_id'], $inv['Jml_penawaran_pemberian_pinjaman_disetujui']);
							
								// detail wallet
								$detail_w['Id']               = $check_wallet['Id'];
								$detail_w['Date_transaction'] = $nowdate;
								$detail_w['Amount']           = $inv['Jml_penawaran_pemberian_pinjaman_disetujui'];
								$detail_w['Notes']            = 'Pengembalian Saldo Pendana dari transaksi Pinjaman No. ' . $inv['Master_loan_id'].'. Kuota pendana tidak mencapai lebih dari 80%.';
								$detail_w['tipe_dana']        = 1;
								$detail_w['User_id']          = $inv['User_id'];
								$detail_w['kode_transaksi']   = $inv['Id'];
								$detail_w['balance']          = $check_wallet['Amount'] + $detail_w['Amount'];
								$this->Wallet_model->insert_detail_wallet($detail_w);

								// set status pendanaan mjd expired
								$this->Cronjob_model->set_pendanaan_expired($inv['Id']);

								// kurangi kredit peminjam sesuai pembiayaan dari pendana
								$this->Cronjob_model->kurangi_kredit_peminjam($key['Master_loan_id'], $inv['Jml_penawaran_pemberian_pinjaman_disetujui']);
							}
						}

						// Lakukan pendanaan internal
						$this->execute_pendanaan_internal($key, $total_pinjaman, $total_pinjaman_disetujui);
					}
				}else{
					// jml kredit kosong (tidak ada pendana)

					// Lakukan pendanaan internal
					$this->execute_pendanaan_internal($key, $total_pinjaman, $total_pinjaman_disetujui);
				}				
			}
		}else{
			echo 'Transaction not found.';
		}
	}

	function execute_pendanaan_internal($key, $total_pinjaman, $total_pinjaman_disetujui)
	{
		// ------------- Pendanaan Internal ------------- 
		$nowdate     = date('Y-m-d');
		$nowdatetime = date('Y-m-d H:i:s');
		$hitung_tax = 0;

		$pendana_internUID   = $this->config->item('pendana_intern_userid'); 
		$pendana_internMEMID = $this->config->item('pendana_intern_memberid'); 

		$log_tran_pinjam     = $this->Log_transaksi_model->get_log_transaksi_pinjam($key['Master_loan_id']);

		//_d($log_tran_pinjam);exit();

		// KILAT ONLY -> hitung laba dan angsuran ke pendana
		$laba                     = (($total_pinjaman * $log_tran_pinjam['ltp_product_investor_return'])/100) * $log_tran_pinjam['ltp_product_loan_term'];
		$angsuran_ke_pendana      = ($total_pinjaman + $laba)/$log_tran_pinjam['ltp_lama_angsuran'];
		$lender_fee               = $laba;
		$cicilan_pokok            = $total_pinjaman;
		$total_pendapatan_pendana = $angsuran_ke_pendana;

		if ($log_tran_pinjam['ltp_product_pph'] != '0')
		{
			$hitung_tax = ($total_pendapatan_pendana * $log_tran_pinjam['ltp_product_pph'])/100;
			$angsuran_ke_pendana      = $angsuran_ke_pendana - $hitung_tax;
			$total_pendapatan_pendana = $angsuran_ke_pendana;
		}

		//echo $laba; exit();

		$prefixID    = 'DD-';
		$orderID     = $prefixID.$pendana_internMEMID.strtoupper(substr(uniqid(sha1(time().$pendana_internMEMID)),0,12));
        $exist_order = $this->Cronjob_model->check_ordercode_transaksi_pendanaan($orderID);	// Cek if order ID exist on Database
		
		// jika order ID sudah ada di Database, generate lagi tambahkan datetime
		if (is_array($exist_order) && count($exist_order) > 0 )
		{
			$orderID = $prefixID.$pendana_internMEMID.strtoupper(substr(uniqid(sha1(time().$pendana_internMEMID)),0,3)).date('YmdHis');
		}

		// insert profil penawaran pemberian pinjaman
		$tbl_penawaran['Id']                                         = $orderID;
		$tbl_penawaran['Tgl_penawaran_pemberian_pinjaman']           = $nowdate;
		$tbl_penawaran['Jml_penawaran_pemberian_pinjaman']           = $total_pinjaman;
		$tbl_penawaran['Jml_penawaran_pemberian_pinjaman_disetujui'] = $total_pinjaman;
		$tbl_penawaran['Date_create']      = $nowdatetime;
		$tbl_penawaran['Master_loan_id']   = $key['Master_loan_id'];
		$tbl_penawaran['User_id']          = $pendana_internUID;
		$tbl_penawaran['dana_member_id']   = $pendana_internMEMID;
		$tbl_penawaran['Product_id']       = 4; // investasi
		$tbl_penawaran['pendanaan_status'] = 'approve';
		$tbl_penawaran['jml_laba']         = $laba;

		$insertMaster = $this->Cronjob_model->insert_profil_pembiayaan($tbl_penawaran);

		$debet_saldo_pendana = $tbl_penawaran['Jml_penawaran_pemberian_pinjaman_disetujui'];

		if ($insertMaster)
		{
			// table detail_ profile_penawaran_pemberian_pinjaman
			$tbl_detail['Date_create']  = $nowdate;
			$tbl_detail['Investor_id']  = $pendana_internUID;
			$tbl_detail['Amount']       = $debet_saldo_pendana;
			$tbl_detail['transaksi_id'] = $orderID;
			$insertDetail = $this->Cronjob_model->insert_detail_profil_pembiayaan($tbl_detail);

			// ----------  insert table log transaksi pendana ---------
					$inlogpendana['Master_loan_id']          = $tbl_penawaran['Master_loan_id'];
					$inlogpendana['Id_pendanaan']            = $orderID;
					$inlogpendana['jml_pendanaan']           = $tbl_penawaran['Jml_penawaran_pemberian_pinjaman_disetujui'];
					$inlogpendana['cicilan_pokok']           = $cicilan_pokok;
					$inlogpendana['lender_fee']              = $lender_fee;
					$inlogpendana['jml_angsuran_ke_pendana'] = $angsuran_ke_pendana;
					$inlogpendana['date_created']            = date('Y-m-d H:i:s');
					$inlogpendana['angsuran_count']          = $log_tran_pinjam['ltp_lama_angsuran'];
					$inlogpendana['total_pendapatan']        = $total_pendapatan_pendana;
					$inlogpendana['total_pajak']             = $hitung_tax;
					$this->Log_transaksi_model->insert_log_transaksi_pendana($inlogpendana);
			// ----------  End of insert table log transaksi pendana ---------

			// ------------------ Transaksi Wallet PENDANA INTERNAL-----------------
			
			// master wallet -> kurangi saldo pendana
			$this->Wallet_model->kurangi_saldo($pendana_internUID, $debet_saldo_pendana);

			$get_master_wallet = $this->Wallet_model->get_wallet_user($pendana_internUID);

			// detail transaksi wallet 
			$notes          = 'Pembiayaan pinjaman No.'.$key['Master_loan_id'];
			$tipedana       = 2;
			$id_pengguna    = $pendana_internUID;
			$kode_transaksi = $orderID;
			$amount         = $debet_saldo_pendana;
			$balance        = $get_master_wallet['Amount'] - $debet_saldo_pendana;

			insert_detail_wallet($get_master_wallet['Id'], $nowdate, $amount, $notes, $tipedana, $id_pengguna, $kode_transaksi, $balance);			
			// ------------------ End of Transaksi Wallet PENDANA INTERNAL---------------

			// tambah kredit peminjam
			$this->Cronjob_model->tambah_kredit_peminjam($key['Master_loan_id'], $debet_saldo_pendana);

			$check_wallet_peminjam = $this->Wallet_model->get_wallet_user($key['User_id']);

			if ( is_array($check_wallet_peminjam) && count($check_wallet_peminjam)>0 )
			{
				// update
				$this->Wallet_model->update_master_wallet_saldo($key['User_id'], $total_pinjaman_disetujui);

				$id_masterwallet_peminjam = $check_wallet_peminjam['Id'];
			}else{
				// insert
				$inmwallet['Date_create']      = $nowdate;
				$inmwallet['User_id']          = $key['User_id'];
				$inmwallet['Amount']           = $total_pinjaman_disetujui;
				$inmwallet['wallet_member_id'] = $key['pinjam_member_id'];

				$id_masterwallet_peminjam = $this->Wallet_model->insert_master_wallet($inmwallet);
			}

			// detail transaksi wallet peminjam
			$notes          = 'Pemberian pinjaman No.'.$key['Master_loan_id'].'. dari Pendanaan No. '.$orderID;
			$tipedana       = 1;
			$id_pengguna    = $key['User_id'];
			$kode_transaksi = $orderID;
			$amount         = $total_pinjaman_disetujui;
			$balance        = $check_wallet_peminjam['Amount'] + $total_pinjaman_disetujui;

			insert_detail_wallet($id_masterwallet_peminjam, $nowdate, $amount, $notes, $tipedana, $id_pengguna, $kode_transaksi, $balance);

			// update status pinjaman approve
			$this->Cronjob_model->approve_pinjaman($key['Master_loan_id']);

			// ---------- Create Tgl Jatuh Tempo -----------
			$loan_term       = $key['ltp_product_loan_term'];
			$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$loan_term." days"));

			$intempo['kode_transaksi']  = $key['Master_loan_id'];
			$intempo['tgl_jatuh_tempo'] = $tgl_jatuh_tempo;
			$intempo['no_angsuran']     = 1;
			$this->Cronjob_model->insert_table_tempo($intempo);
			// ---------- End Create Tgl Jatuh Tempo -----------

			return TRUE;
		}
	}

	function send_mail_peminjam($loan_data, $saldo_diterima)
	{
		$userdata = $this->Member_model->get_user_ojk_by($loan_data['User_id']);
		
		$html_content = '
        Hai '.$userdata['Nama_pengguna'].',<br><br>

            Pinjaman Anda di BKDana.com telah mendapat pendanaan.
            <br><br>

            No. Transaksi: '.$loan_data['Master_loan_id'].'
            <br>
            Jumlah permohonan pinjaman: Rp '.number_format($loan_data['Jml_permohonan_pinjaman']).'
            <br>
            Saldo diterima: Rp '.number_format($saldo_diterima).'
            <br>
            <br><br>

            <span style="color:#858C93;">
            	Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke Email ini.
            	<br><br>

            	&copy; BKDana.com, '.date("Y").'. All rights reserved.
            </span>
			';

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
		$mail->Subject     = 'Saldo Pinjaman';
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

	function send_mail_pendana($invest_data)
	{
		$userdata = $this->Member_model->get_user_ojk_by($invest_data['User_id']);
		
		$html_content = '
        Hai '.$userdata['Nama_pengguna'].',<br><br>

            Pengajuan Pendanaan Anda di BKDana.com telah disetujui.<br><br>
            Berikut detail transaksi pendanaan Anda:<br>

            No. Transaksi: '.$invest_data['Id'].'
            <br>
            Jenis Transaksi: Pendanaan
            <br>
            Jumlah Pendanaan: Rp '.number_format($invest_data['Jml_penawaran_pemberian_pinjaman']).'
            <br><br>

            Salam,<br>
            BKDana.com
			';

		
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
		$mail->Subject     = 'Penawaran Pendanaan disetujui';
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
}