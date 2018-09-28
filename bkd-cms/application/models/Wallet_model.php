<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wallet_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}

	function get_wallet_user($uid)
	{
		$this->db->select('*');
		$this->db->from($this->master_wallet);
		$this->db->where('User_id', $uid);
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_saldo_bymember($uid)
	{
		$this->db->select('*');
		$this->db->from($this->master_wallet);
		$this->db->where('wallet_member_id', $uid);
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_saldo_LO($uid)
	{
		$this->db->select('*');
		$this->db->from($this->master_wallet);
		$this->db->where('loan_organizer_id', $uid);
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function update_master_wallet_saldo($uid, $saldo)
	{
		$uid   = $this->db->escape_str($uid);
		$saldo = $this->db->escape_str($saldo);

		$sql = "UPDATE {$this->master_wallet} SET Amount = Amount+{$saldo} WHERE User_id='{$uid}'";

		$kueri = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	function kurangi_saldo($uid, $saldo)
	{
		$uid   = $this->db->escape_str($uid);
		$saldo = $this->db->escape_str($saldo);

		$sql = "UPDATE {$this->master_wallet} SET Amount = Amount-{$saldo} WHERE User_id='{$uid}'";

		$kueri = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	function insert_master_wallet($data)
	{
		$this->db->insert($this->master_wallet, $data);
		return $this->db->insert_id();
	}

	function insert_detail_wallet($data)
	{
		$this->db->insert($this->detail_wallet, $data);
		return $this->db->insert_id();
	}


	function get_all_dt()
	{
		// ---- Get All data show as Json ----
		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array();
		$sql_where 		= '';
		$cols 			= array( "Id", "Nama_pengguna", "Amount", "");
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = strtoupper($this->db->escape_str(trim($this->input->get('sSearch', TRUE))));

			$_sql_where[] = "
				(
					UCASE(Nama_pengguna) LIKE '%".$search."%' OR
					UCASE(Amount) LIKE '%".$search."%' 
				)
			";
		}

		// limit
		$start 		= $this->Datatables_model->get_start();
		$rows 		= $this->Datatables_model->get_rows();
		// sort
		$sort 		= $this->Datatables_model->get_sort($cols);		
		$sort_dir 	= $this->Datatables_model->get_sort_dir();	
		        
		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);
        //running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM {$this->master_wallet} mw
					INNER JOIN user u ON(u.Id_pengguna=mw.User_id)
					$sql_where
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();


		$sql = " 	SELECT *
					FROM {$this->master_wallet} mw
					INNER JOIN user u ON(u.Id_pengguna=mw.User_id)
					$sql_where
			    ";

		if($sort!='' && $sort_dir!='') $order = " ORDER BY $sort $sort_dir ";
		
		$query 	= $this->db->query($sql. $order. " LIMIT $start,$rows ");
		$data 	= $query->result();

		if( $search!='' ){
			$iFilteredTotal = count($query->result());
		}else{
			$iFilteredTotal = $iTotal;
		}
		
        //    * Output
         
         $output = array(
             "sEcho" => $this->Datatables_model->get_echo(),
             "iTotalRecords" => $iTotal,
             "iTotalDisplayRecords" => $iFilteredTotal,
             "aaData" => $data
         );

        $query->free_result();

		return json_encode($output);
	}

	public function delete_master_wallet($id)
	{
		$id = $this->db->escape_str($id);
		return $this->db->delete($this->master_wallet, array('User_id'=>$id));
	}

	public function delete_detail_wallet($id)
	{
		$id = $this->db->escape_str($id);
		return $this->db->delete($this->detail_wallet, array('User_id'=>$id));
	}
}