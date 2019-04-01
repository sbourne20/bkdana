<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wallet_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->Master_model->get_tables($this);
	}

	function get_wallet_bymember($uid)
	{
		$this->db->select('*');
		$this->db->from($this->master_wallet);
		$this->db->where('wallet_member_id', $uid);
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_wallet_byuser($uid)
	{
		$this->db->select('*');
		$this->db->from($this->master_wallet);
		$this->db->where('User_id', $uid);
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_wallet_bylo($uid)
	{
		$this->db->select('*');
		$this->db->from($this->master_wallet);
		$this->db->where('loan_organizer_id', $uid);
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function kurangi_saldo_wallet($uid, $jml)
	{
		$uid = $this->db->escape_str($uid);
		$jml = $this->db->escape_str($jml);
		
		$sql = "UPDATE {$this->master_wallet} SET Amount = Amount - {$jml} WHERE wallet_member_id = '{$uid}' ";
		$run = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	function insert_detail_wallet($data)
	{
		$this->db->insert($this->detail_wallet, $data);
		return $this->db->insert_id();
	}

	function insert_master_wallet($data)
	{
		$this->db->insert($this->master_wallet, $data);
		return $this->db->insert_id();
	}

	function update_master_wallet_saldo($uid, $saldo)
	{
		$uid   = $this->db->escape_str($uid);
		$saldo = $this->db->escape_str($saldo);

		$sql = "UPDATE {$this->master_wallet} SET Amount = Amount+{$saldo} WHERE User_id='{$uid}'";

		$kueri = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	function update_wallet_bylo($uid, $saldo)
	{
		$uid   = $this->db->escape_str($uid);
		$saldo = $this->db->escape_str($saldo);

		$sql = "UPDATE {$this->master_wallet} SET Amount = Amount+{$saldo} WHERE loan_organizer_id='{$uid}'";

		$kueri = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	function all_wallet_detail($uid, $start, $limit)
	{
		$this->db->select('d.Id, Detail_wallet_id, Date_transaction, d.Amount as amount_detail, Notes, tipe_dana, d.User_id, kode_transaksi, wallet_member_id');
		$this->db->from($this->detail_wallet. ' d');
		$this->db->join($this->master_wallet. ' m', 'd.Id=m.Id', 'left');
		$this->db->where('wallet_member_id', $uid);
		$this->db->order_by('Detail_wallet_id', 'desc');
		$this->db->limit($limit, $start);
		$sql = $this->db->get();
		return $sql->result_array();
	}
}