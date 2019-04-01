<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendanaan_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->Master_model->get_tables($this);
	}

	function get_pinjaman_detail($code)
	{
		$this->db->select('Master_loan_id, Master_loan_status, Tgl_permohonan_pinjaman, Jml_permohonan_pinjaman, Jml_permohonan_pinjaman_disetujui, User_id, pinjam_member_id, jml_kredit');
		$this->db->from($this->profil_permohonan_pinjaman. ' p');
		$this->db->where('Master_loan_id', $code);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

}