<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Redeem_model extends CI_Model
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
		$cols 			= array( "mod_redeem_id","redeem_kode", "Nama_pengguna", "redeem_amount", "redeem_nomor_rekening", "redeem_nama_bank", "redeem_date", "redeem_status", "");
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = strtoupper($this->db->escape_str(trim($this->input->get('sSearch', TRUE))));

			$_sql_where[] = "
				(
					UCASE(redeem_kode) LIKE '%".$search."%' OR
					UCASE(redeem_nomor_rekening) LIKE '%".$search."%' OR
					UCASE(redeem_nama_bank) LIKE '%".$search."%' OR
					UCASE(Nama_pengguna) LIKE '%".$search."%' OR
					UCASE(redeem_status) LIKE '%".$search."%'
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
					FROM mod_redeem t 
					LEFT JOIN user u ON(u.Id_pengguna=t.redeem_id_pengguna)
					$sql_where
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();


		$sql = " 	SELECT *
					FROM mod_redeem t 
					LEFT JOIN user u ON(u.Id_pengguna=t.redeem_id_pengguna)
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
		$this->db->where('mod_redeem_id', $ID);
		$this->db->update($this->mod_redeem, $data);
		return $this->db->affected_rows();
	}

	function get_data_byid($id)
	{
		$this->db->select('*');
		$this->db->from($this->mod_redeem);
		$this->db->where('mod_redeem_id', $id);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		return $ret;
	}

	public function delete_($id)
	{
		$id = $this->db->escape_str($id);
		return $this->db->delete($this->mod_redeem, array('mod_redeem_id'=>$id));
	}
}