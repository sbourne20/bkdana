<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reporting_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}

	function jml_orang($startdate, $enddate)
	{
		$sql = " 	SELECT count(User_id) as itotal 
					FROM profil_permohonan_pinjaman
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	function jml_uang_pinjaman($startdate, $enddate)
	{
		$sql = " 	SELECT SUM(Jml_permohonan_pinjaman) as sum_pinjaman 
					FROM profil_permohonan_pinjaman
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	function jml_bunga($startdate, $enddate)
	{
		$sql = " 	SELECT SUM(ltp_product_interest_rate) as itotal_bunga, SUM(ltp_product_loan_term) as itotal_tenor  
					FROM profil_permohonan_pinjaman p
					LEFT JOIN mod_log_transaksi_pinjaman lop ON(lop.ltp_Master_loan_id=p.Master_loan_id)
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	// --------- disetujui ---------

	function jml_orang_disetujui($startdate, $enddate)
	{
		$sql = " 	SELECT count(User_id) as itotal_user, SUM(Jml_permohonan_pinjaman) as itotal_pinjaman, 
					SUM(ltp_product_interest_rate) as itotal_bunga, SUM(ltp_product_loan_term) as itotal_tenor 
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN mod_log_transaksi_pinjaman lop ON(lop.ltp_Master_loan_id=p.Master_loan_id)
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
					AND (Master_loan_status = 'approve' OR Master_loan_status = 'complete' OR Master_loan_status = 'lunas')
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	// ------- status pinjaman --------

	function jml_pinjaman_lancar($startdate, $enddate)
	{
		$sql = " 	SELECT count(*) as itotal 
					FROM profil_permohonan_pinjaman
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
					AND Master_loan_status = 'lunas'
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	function jml_pinjaman_notlancar($startdate, $enddate)
	{
		$sql = " 	SELECT count(*) as itotal 
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN mod_tempo tp ON(tp.kode_transaksi=p.Master_loan_id)
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
					AND telah_jatuh_tempo = '1'
					AND tgl_jatuh_tempo <= NOW() - INTERVAL 1 MONTH
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	function jml_pinjaman_macet($startdate, $enddate)
	{
		$sql = " 	SELECT count(*) as itotal 
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN mod_tempo tp ON(tp.kode_transaksi=p.Master_loan_id)
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
					AND is_paid = '0'
					AND telah_jatuh_tempo = '1'
					AND tgl_jatuh_tempo <= NOW() - INTERVAL 3 MONTH
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	// ------ AKUMULASi --------
	// Daftar Transaksi yang sedang berjalan 

	function jml_akumulasi_orang($startdate, $enddate)
	{
		$sql = " 	SELECT count(User_id) as itotal 
					FROM profil_permohonan_pinjaman
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
					AND Master_loan_status='complete'
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	function jml_akumulasi_uang_pinjaman($startdate, $enddate)
	{
		$sql = " 	SELECT SUM(Jml_permohonan_pinjaman) as sum_pinjaman 
					FROM profil_permohonan_pinjaman
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
					AND Master_loan_status='complete'
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	function jml_akumulasi_bunga($startdate, $enddate)
	{
		$sql = " 	SELECT SUM(ltp_product_interest_rate) as itotal_bunga, SUM(ltp_product_loan_term) as itotal_tenor  
					FROM profil_permohonan_pinjaman p
					LEFT JOIN mod_log_transaksi_pinjaman lop ON(lop.ltp_Master_loan_id=p.Master_loan_id)
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
					AND Master_loan_status='complete'
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	function jml_akumulasi_pinjaman_lancar($startdate, $enddate)
	{
		$sql = " 	SELECT count(*) as itotal 
					FROM profil_permohonan_pinjaman p
					LEFT JOIN mod_tempo tp ON(tp.kode_transaksi=p.Master_loan_id)
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
					AND Master_loan_status = 'complete'
					AND telah_jatuh_tempo = '0'
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	function jml_akumulasi_pinjaman_notlancar($startdate, $enddate)
	{
		$sql = " 	SELECT count(*) as itotal 
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN mod_tempo tp ON(tp.kode_transaksi=p.Master_loan_id)
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
					AND Master_loan_status = 'complete'
					AND is_paid = '0'
					AND telah_jatuh_tempo = '1'
					AND tgl_jatuh_tempo <= NOW() - INTERVAL 1 MONTH
					GROUP BY p.Master_loan_id
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	function jml_akumulasi_pinjaman_macet($startdate, $enddate)
	{
		$sql = " 	SELECT count(*) as itotal 
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN mod_tempo tp ON(tp.kode_transaksi=p.Master_loan_id)
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
					AND Master_loan_status = 'complete'
					AND is_paid = '0'
					AND telah_jatuh_tempo = '1'
					AND tgl_jatuh_tempo <= NOW() - INTERVAL 3 MONTH
					GROUP BY p.Master_loan_id
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}
}