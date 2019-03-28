<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agent_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->Master_model->get_tables($this);
	}

	public function check_existing_agent($email)
	{
		$this->db->select('id_mod_agent, agent_fullname, agent_email, agent_password');
		$this->db->from($this->mod_agent);
		$this->db->where('agent_email', $email);		
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	public function do_login_byemail($u)
	{
		$u = $this->db->escape_str($u);

		$this->db->select('id_mod_agent, agent_fullname, agent_email, agent_phone, agent_password, agent_status');
		$this->db->from($this->mod_agent);
		$this->db->where('agent_email', $u);
		//$this->db->where('mum_status', '1');
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	public function data_agent($id) 
	{
		$this->db->select('*');
		$this->db->from($this->mod_agent);
		$this->db->where('id_mod_agent', $id);
		$this->db->limit(1);
		$sql = $this->db->get();
		return $sql->row_array();
	}

}