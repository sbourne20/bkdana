<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->Master_model->get_tables($this);
	}

	function insert_payment_log($data)
	{
		$this->db->insert('payment_log', $data);
		return $this->db->insert_id();
	}

	function update_top_up($code, $data)
	{
		$this->db->where('kode_top_up', $code);
		$this->db->update($this->mod_top_up, $data);
		return $this->db->affected_rows();
	}

	function get_topup_by($code)
	{
		$this->db->select('kode_top_up, member_id, user_id');
		$this->db->from($this->mod_top_up);
		$this->db->where('kode_top_up', $code);
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}


}