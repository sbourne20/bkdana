<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_model extends CI_Model
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
		$cols 			= array( "Id", "");
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = $_GET['sSearch'];
		}

		// limit
		$start 		= $this->Datatables_model->get_start();
		$rows 		= $this->Datatables_model->get_rows();
		// sort
		$sort 		= $this->Datatables_model->get_sort($cols);		
		$sort_dir 	= $this->Datatables_model->get_sort_dir();	
		        
        //running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM profile_penawaran_pemberian_pinjaman 
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		// Kolom Pencarian
		if( $search!='' ){
			$_sql_where[] = "
				(
					UCASE(mum_fullname) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(mum_email) LIKE '%".strtoupper($this->db->escape_str($search))."%'
				)
			";
		}

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = " 	SELECT * 
					FROM profile_penawaran_pemberian_pinjaman p 
					LEFT JOIN user u ON (u.Id_pengguna=p.User_id)
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

	function get_member_byid($id)
	{
		$sql = " SELECT * 
					FROM {$this->mod_user_member} m 
					WHERE id_mod_user_member='{$id}' LIMIT 1 ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		return $data;
	}
	
	public function delete_member($id)
	{
		$id = $this->db->escape_str($id);
		return $this->db->delete($this->mod_user_member, array('id_mod_user_member'=>$id));
	}

	/*public function update_member($data, $ID)
	{
		$ID = $this->db->escape_str($ID);
		$data = $this->Master_model->escape_all($data);

		$this->db->where('id_mod_member', $ID);
		$this->db->update($this->mod_member, $data);
		return $this->db->affected_rows();
	}

	public function set_member_status($ID, $dostatus)
	{
		$this->db->set('status', $dostatus);
		$this->db->where('id_mod_member', $ID);
		$this->db->update($this->mod_member);
		return $this->db->affected_rows();
	}*/

	// ================ Mod_products_member ==================

	function get_member_id_byproduct($ID)
	{
		$ID = $this->db->escape_str($ID);

		$this->db->where('product_id', $ID);
		$this->db->from($this->mod_products_member);
		$sql = $this->db->get();

		return $sql->row_array();
	}
}