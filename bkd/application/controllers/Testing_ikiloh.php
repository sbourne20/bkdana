<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testing_ikiloh extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();
		$this->load->model('Member_model');
		$this->load->model('Wallet_model');

		$this->load->library('pagination');

		include(APPPATH.'libraries/phpmailer-5.2.23/PHPMailerAutoload.php');
		require_once(APPPATH.'libraries/TCPDF/tcpdf.php');

		ini_set('max_execution_time', 600);
		
		error_reporting(E_ALL & ~E_NOTICE);
		ini_set('display_errors', '1');
	}

	function pdf_kilat()
	{
		$mid_peminjam = '64';
		$tbl_penawaran['Master_loan_id'] = 'PK-64C47B69AB96ED';
		$pinjaman_transaksiID = 'PK-64C47B69AB96ED';
		$log_tran_pinjam      = $this->Content_model->get_log_transaksi_pinjam($pinjaman_transaksiID);


		$check_wallet_peminjam = $this->Wallet_model->get_wallet_bymember($mid_peminjam);

		$memberpinjam = $this->Content_model->get_pinjaman_member($tbl_penawaran['Master_loan_id']);

		// --------- Create Tgl Jatuh Tempo -> Insert ke table Mod_Tempo ---------
		if ($log_tran_pinjam['ltp_type_of_business_id'] == '1')
		{
			// Kilat
			$loan_term       = $log_tran_pinjam['ltp_product_loan_term'];
			$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$loan_term." days"));

			/*$intempo['kode_transaksi']  = $tbl_penawaran['Master_loan_id'];
			$intempo['tgl_jatuh_tempo'] = $tgl_jatuh_tempo;
			$intempo['no_angsuran']     = 1;
			$this->Content_model->insert_table_tempo($intempo);*/

			$output['list_pendana'] = $this->Content_model->get_list_pendana($tbl_penawaran['Master_loan_id']);

			$output['member']   = $memberpinjam;
			$output['tgl']      = parseDateTimeIndex(date('Y-m-d'));
			$output['jml_hari'] = $loan_term;

			$html               = $this->load->view('email/vpinjaman-kilat', $output, TRUE);
			
			$filename = 'perjanjian-pinjaman-kilat-'.$tbl_penawaran['Master_loan_id'].'.pdf';
			$title    = 'Perjanjian Pinjaman Kilat '.$tbl_penawaran['Master_loan_id'];
			$label_transaksi = 'Pinjaman Kilat';

		}else{
			// Mikro
			for ($i=1; $i <= $log_tran_pinjam['ltp_lama_angsuran']; $i++) { 
				
				$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$i." week"));

				$intempo['kode_transaksi']  = $tbl_penawaran['Master_loan_id'];
				$intempo['tgl_jatuh_tempo'] = $tgl_jatuh_tempo;
				$intempo['no_angsuran']     = $i;
				$this->Content_model->insert_table_tempo($intempo);
			}

			$output['list_pendana'] = $this->Content_model->get_list_pendana($tbl_penawaran['Master_loan_id']);
			
			$output['member']    = $memberpinjam;
			$output['tgl']       = parseDateTimeIndex(date('Y-m-d'));
			$output['tgl_order'] = date('d/m/Y', strtotime($memberpinjam['Tgl_permohonan_pinjaman']));
			$html                = $this->load->view('email/vpinjaman-mikro', $output, TRUE);
			
			$filename = 'perjanjian-pinjaman-mikro-'.$tbl_penawaran['Master_loan_id'].'.pdf';
			$title    = 'Perjanjian Pinjaman Mikro '.$tbl_penawaran['Master_loan_id'];
			$label_transaksi = 'Pinjaman Mikro';
		}

		echo $html; exit();

		// update status pinjaman mjd approve
		$this->Content_model->approve_pinjaman_bycode($tbl_penawaran['Master_loan_id']);
		// update semua pendana status mjd approve
		$this->Content_model->approve_transaksi_pendana_bypinjaman($tbl_penawaran['Master_loan_id']);

		// --------- Generate PDF Peminjam  ----------
		$attach_file = $this->create_pdf($html, $tbl_penawaran['Master_loan_id'], $filename, $title);
		$this->send_mail_peminjam($memberpinjam, $label_transaksi, $attach_file);
		unlink($pdf_folder.$filename);
		// --------- Generate PDF Peminjam  ----------

		unset($output);
		// --------- Generate PDF Pendana ----------
		$output['member']    = $memdata;
		$output['ordercode'] = $orderID;
		$output['tgl_order'] = date('d/m/Y');
		$html = $this->load->view('email/vpendana', $output, TRUE);

		$filename = 'perjanjian-pendana-'.$orderID.'.pdf';
		$title    = 'Perjanjian Pendana '.$orderID;
		$attach_file = $this->create_pdf($html, $output['ordercode'], $filename, $title);
		// --------- End Generate PDF ----------
		
		$this->send_email($memdata['mum_email'], $orderID, $jmldana, $attach_file);
		unlink($pdf_folder.$filename);
	
	}
}