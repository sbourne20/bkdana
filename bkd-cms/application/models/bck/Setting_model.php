<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}

	public function insert_($data)
	{
		$data = $this->Master_model->escape_all($data);

		$this->db->insert('mod_setting_home', $data);
		return $this->db->insert_id();
	}

	function get_setting_byid($id)
	{
		$this->db->select('*');
		$this->db->from('mod_setting_home');
		$this->db->where('setting_home_id', $id);
		$this->db->limit(1);
		$sql = $this->db->get();

		$ret = $sql->row_array();
		$sql->free_result();

		return $ret;
	}

	public function update_($data, $ID)
	{
		$this->db->where('setting_home_id', $ID);
		$this->db->update('mod_setting_home', $data);
		return $this->db->affected_rows();
	}
}