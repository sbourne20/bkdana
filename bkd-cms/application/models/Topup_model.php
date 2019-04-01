<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topup_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
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
		$cols 			= array( "id_top_up","");
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = strtoupper($this->db->escape_str($this->input->get('sSearch', TRUE)));

			$_sql_where[] = "
				(
					UCASE(kode_top_up) LIKE '%".$search."%'
				)
			";
		}

		// limit
		$start 		= $this->Datatables_model->get_start();
		$rows 		= $this->Datatables_model->get_rows();
		// sort
		$sort 		= $this->Datatables_model->get_sort($cols);		
		$sort_dir 	= $this->Datatables_model->get_sort_dir();	
		        
        //running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM mod_top_up
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = " 	SELECT *
					FROM mod_top_up t 
					LEFT JOIN user u ON(u.Id_pengguna=t.user_id)
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

	public function update_($data, $ID)
	{
		$this->db->where('id_top_up', $ID);
		$this->db->update($this->mod_top_up, $data);
		return $this->db->affected_rows();
	}

	function get_data_byid($id)
	{
		$this->db->select('*');
		$this->db->from($this->mod_top_up);
		$this->db->where('id_top_up', $id);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		return $ret;
	}

	function insert_top_up($data)
	{
		$this->db->insert($this->mod_top_up, $data);
		return $this->db->insert_id();
	}

	function check_topup_code($code)
	{
		$this->db->select('id_top_up');
		$this->db->from($this->mod_top_up);
		$this->db->where('kode_top_up', $code);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	public function delete_($id)
	{
		$id = $this->db->escape_str($id);
		return $this->db->delete($this->mod_top_up, array('id_top_up'=>$id));
	}

	function get_all_member()
	{
		$this->db->select('*');
		$this->db->from($this->user_ojk);
		$this->db->order_by('Nama_pengguna', 'asc');
		$sql = $this->db->get();
		
		return $sql->result_array();
	}

	function get_all_member1($code)
	{
		$this->db->select('*');
		$this->db->from($this->user_ojk);
		$this->db->where('Id_pengguna', $code);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function getmum($code)
	{
		$this->db->select('*');
		$this->db->from($this->mod_user_member);
		$this->db->where('id_mod_user_member', $code);
		$sql = $this->db->get();
		
		return $sql->result_array();
	}

	function get_all_member2()
	{
		$this->db->select('Id_pengguna','Nama_pengguna');
		$this->db->from($this->user_ojk);
		$this->db->order_by('Nama_pengguna', 'asc');
		$query 	= $this->db->get();
		$data 	= $query->row_array();
		return $data;
	}

	function insert_topup($data) 
	{
		$this->db->insert($this->mod_top_up, $data);
		return $this->db->insert_id();
	}
}