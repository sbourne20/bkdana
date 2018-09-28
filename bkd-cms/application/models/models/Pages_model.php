<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}
	

	function get_pages()
	{
		// ---- Get All Quotes show as Json ----
		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array();
		$sql_where 		= '';
		$cols 			= array( "p_id", "p_title", "p_images", "p_status" );
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && trim($_GET['sSearch']) != "" ) {
			$search = $this->input->get('sSearch', TRUE);
		}

		// limit
		$start    = $this->Datatables_model->get_start();
		$rows     = $this->Datatables_model->get_rows();
		// sort
		$sort     = $this->Datatables_model->get_sort($cols);
		$sort_dir = $this->Datatables_model->get_sort_dir();
		        
        // Running Query Total Row
		$sql = " SELECT count(0) as iTotal FROM {$this->mod_pages} ";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		// Kolom Pencarian
		if( $search!='' ){
			$_sql_where[] = "
				(
					UCASE(p_title) LIKE '%".strtoupper($this->db->escape_str($search))."%'
				)
			";
		}

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = "SELECT * FROM {$this->mod_pages} $sql_where ";

		if($sort!='' && $sort_dir!='') $order = " ORDER BY $sort $sort_dir ";
		
		$query = $this->db->query($sql. $order. " LIMIT $start,$rows ");
		$data  = $query->result();

		if( $search!='' ){
			$iFilteredTotal = count($query->result());
		}else{
			$iFilteredTotal = $iTotal;
		}
		
        // * Output         
        $output = array(
             "sEcho" => $this->Datatables_model->get_echo(),
             "iTotalRecords" => $iTotal,
             "iTotalDisplayRecords" => $iFilteredTotal,
             "aaData" => $data
        );

        $query->free_result();

        //echo $this->db->last_query();
		return json_encode($output);
	}

	public function insert_new_page($data)
	{
		$this->db->insert($this->mod_pages, $data);
		return $this->db->insert_id();
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

		return $prod->row_array();
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
