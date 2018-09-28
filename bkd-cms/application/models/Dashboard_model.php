<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}

	public function total_redeem_pending()
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->mod_redeem);
		$this->db->where('redeem_status', 'pending');
		$prod = $this->db->get();

		return $prod->row_array();
	}

	public function total_transaksi_pendana()
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->tabel_pendana. ' p');
		$this->db->where('pendanaan_status', 'pending');
		$prod = $this->db->get();

		return $prod->row_array();
	}

	public function total_pinjaman_mikro()
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->tabel_pinjaman. ' p');
		$this->db->join($this->product. ' pr', 'pr.Product_id=p.Product_id', 'left');
		$this->db->where('type_of_business_id', '3');		
		$this->db->where('Master_loan_status', 'review');
		$prod = $this->db->get();

		return $prod->row_array();
	}

	public function total_pinjaman_kilat()
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->tabel_pinjaman. ' p');
		$this->db->join($this->product. ' pr', 'pr.Product_id=p.Product_id', 'left');
		$this->db->where('type_of_business_id', '1');
		$this->db->where('Master_loan_status', 'review');
		$prod = $this->db->get();

		return $prod->row_array();
	}

	public function total_topup_pending()
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->mod_top_up);
		$this->db->where('status_top_up', 'pending');
		$this->db->where('payment_status <>', 'settlement');
		$prod = $this->db->get();

		return $prod->row_array();
	}
	public function total_topup_approve()
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->mod_top_up);
		$this->db->where('status_top_up', 'approve');
		$prod = $this->db->get();

		return $prod->row_array();
	}

	public function total_peminjam()
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->mod_user_member);
		$this->db->where('mum_type', 1);
		$this->db->where('mum_status', 1);
		$prod = $this->db->get();

		return $prod->row_array();
	}

	public function total_pendana()
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->mod_user_member);
		$this->db->where('mum_type', 2);
		$this->db->where('mum_status', 2);
		$prod = $this->db->get();

		return $prod->row_array();
	}

	public function update_page($data, $ID)
	{
		$ID = $this->db->escape_str($ID);

		$this->db->where('p_id', $ID);
		$this->db->update($this->mod_pages, $data);
		return $this->db->affected_rows();
	}

	public function delete_page($id)
	{
		$id = $this->db->escape_str($id);
		return $this->db->delete($this->mod_pages, array('p_id'=>$id));
	}

	function get_page_byid($id)
	{
		$id = $this->db->escape_str($id);

		$this->db->select('*');
		$this->db->from($this->mod_pages);
		$this->db->where('p_id', $id);
		$prod = $this->db->get();
		$ret = $prod->row_array();
		$prod->free_result();
		return $ret;
	}


	// ---- MOD_pages_type -----
	function get_page_type($id)
	{
		$id = $this->db->escape_str($id);

		$this->db->select('*');
		$this->db->from($this->mod_pages_type);
		$this->db->where('ptype_id', $id);
		$prod = $this->db->get();

		return $prod->row_array();
	}

	
	public function update_page_type($data, $ID)
	{
		$ID = $this->db->escape_str($ID);
		$data = $this->Master_model->escape_all($data);

		$this->db->where('ptype_id', $ID);
		$this->db->update($this->mod_pages_type, $data);
		return $this->db->affected_rows();
	}

}
