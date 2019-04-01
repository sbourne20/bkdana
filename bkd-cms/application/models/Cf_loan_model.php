<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cf_loan_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}

	function sum_uang_disburse($startdate, $enddate)
	{
		$sql = " 	SELECT SUM(Jml_permohonan_pinjaman_disetujui) as sum_disburse 
					FROM profil_permohonan_pinjaman
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
					AND Master_loan_status='complete'
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	function sum_principal($startdate, $enddate)
	{
		$sql = " SELECT SUM(Amount) as sum_amount, SUM(ltp_lender_fee) as sum_lender, SUM(ltp_admin_fee) as sum_admin, SUM(ltp_LO_fee) as sum_lo, SUM(ltp_frozen) as sum_frozen, x.sum_penalty
					FROM profil_permohonan_pinjaman pp
					JOIN mod_log_transaksi_pinjaman mltpin ON (mltpin.ltp_Master_loan_id=pp.Master_loan_id)
					JOIN (SELECT Master_loan_id, SUM(denda) sum_penalty
					FROM record_repayment
					GROUP BY Master_loan_id
					)x
					ON (x.Master_loan_id=mltpin.ltp_Master_loan_id)
					WHERE (Tgl_permohonan_pinjaman >='{$startdate}' AND Tgl_permohonan_pinjaman <= '{$enddate}' )
					AND Master_loan_status IN ('complete','lunas')

			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		$query->free_result();

		return $data;
	}

	
}