<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Province_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->Master_model->get_tables($this);
	}

	function get_province()
	{
		$this->db->select('province_name');
		$this->db->from($this->mod_province);
		$this->db->order_by('province_name', 'asc');
		$sql = $this->db->get();
		$ret = $sql->result_array();
		$sql->free_result();
		return $ret;
	}
}