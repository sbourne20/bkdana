<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Harga_model extends CI_Model
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
		$cols 			= array( "h_id", "h_harga", "tenor", "h_status", "");
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = trim(strtoupper($this->db->escape_str($this->input->get('sSearch', TRUE))));

			$_sql_where[] = "
				(
					UCASE(h_harga) LIKE '%".$search."%' OR
					UCASE(Loan_term) LIKE '%".$search."%' 
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
					FROM mod_harga h
					LEFT JOIN mod_harga_produk hp ON (hp.hp_harga_id=h.h_id)
					LEFT JOIN product p ON (p.Product_id=hp.hp_product_id)
					$sql_where
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();


		$sql = " 	SELECT h_id, h_harga, h_status, hp_harga_id, hp_product_id, p.type_of_interest_rate_name, Loan_term,
					GROUP_CONCAT(Loan_term, ' ' ,p.type_of_interest_rate_name SEPARATOR ', ')  as tenor
					FROM mod_harga h
					LEFT JOIN mod_harga_produk hp ON (hp.hp_harga_id=h.h_id)
					LEFT JOIN product p ON (p.Product_id=hp.hp_product_id)
					$sql_where
					GROUP BY h_id ASC
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
		//$this->result_id->fetchArray(SQLITE3_ASSOC)

		//while($row = $query->fetchArray()){
		//$tag_array = explode(',' , $row['tenor']);
				
		//}foreach($tag_array as $tag) {
			//	list($Loan_term,$type_of_interest_rate SEPARATOR) = explode('',$tag,2);
		//}
		
		return json_encode($output);
		
		
	}

	public function insert_harga($data)
	{
		$data = $this->Master_model->escape_all($data);

		$this->db->insert($this->mod_harga, $data);
		return $this->db->insert_id();
	}

	public function insert_harga_produk($data)
	{
		$data = $this->Master_model->escape_all($data);

		$this->db->insert($this->mod_harga_produk, $data);
		return $this->db->insert_id();
	}	

	function get_harga_byid($id)
	{
		$this->db->select('*');
		$this->db->from($this->mod_harga);
		$this->db->where('h_id', $id);
		$this->db->limit(1);
		$sql = $this->db->get();

		$ret = $sql->row_array();
		$sql->free_result();

		return $ret;
	}

	function get_harga_produk($id)
	{
		$this->db->select('*');
		$this->db->from($this->mod_harga_produk);
		$this->db->where('hp_harga_id', $id);
		$sql = $this->db->get();

		$ret = $sql->result_array();
		$sql->free_result();

		return $ret;
	}

	public function update_harga($data, $ID)
	{
		$this->db->where('h_id', $ID);
		$this->db->update($this->mod_harga, $data);
		return $this->db->affected_rows();
	}
	
	public function delete_harga($id)
	{
		return $this->db->delete($this->mod_harga, array('h_id'=>$id));
	}

	public function delete_relasi_harga($id)
	{
		return $this->db->delete($this->mod_harga_produk, array('hp_harga_id'=>$id));
	}
}