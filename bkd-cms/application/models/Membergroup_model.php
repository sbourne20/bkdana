<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Membergroup_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}

	/*function get_all_group()
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
		$cols 			= array( "id_mod_group", "group_name", "group_status", "");
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = strtoupper($this->db->escape_str($this->input->get('sSearch', TRUE)));

			$_sql_where[] = "
				(
					UCASE(group_name) LIKE '%".$search."%'
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
					FROM mod_group
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = " 	SELECT *
					FROM mod_group 
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

	public function insert_group($data)
	{
		$data = $this->Master_model->escape_all($data);

		$this->db->insert('mod_group', $data);
		return $this->db->insert_id();
	}

	function get_group_byid($id)
	{
		$this->db->select('*');
		$this->db->from('mod_group');
		$this->db->where('id_mod_group', $id);
		$this->db->limit(1);
		$sql = $this->db->get();

		$ret = $sql->row_array();
		$sql->free_result();

		return $ret;
	}

	public function update_group($data, $ID)
	{
		$this->db->where('id_mod_group', $ID);
		$this->db->update('mod_group', $data);
		return $this->db->affected_rows();
	}
	
	public function delete_group($id)
	{
		return $this->db->delete('mod_group', array('id_mod_group'=>$id));
	}

	function get_active_group()
	{
		$this->db->select('*');
		$this->db->from('mod_group');
		$this->db->where('group_status', '1');
		$this->db->order_by('group_name', 'asc');
		$sql = $this->db->get();
		$ret = $sql->result_array();
		$sql->free_result();
		return $ret;
	}

	function checkif_exist($name, $id='')
	{
		$name = strtoupper($name);

		$this->db->select('*');
		$this->db->from('mod_group');
		$this->db->where('UCASE(group_name) = ', $name);

		if ($id!=''){
		$this->db->where('id_mod_group !=', $id);
		}

		$this->db->limit(1);
		$sql = $this->db->get();

		$ret = $sql->row_array();
		$sql->free_result();

		return $ret;
	}*/
}