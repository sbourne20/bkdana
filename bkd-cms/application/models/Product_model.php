<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}

	function get_product_dt()
	{
		// ---- Get All data show as Json ----
		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array('product_status !=2');
		$sql_where 		= '';
		$cols 			= array( "Product_id", "Type_of_business", "Fundraising_period", "Product_sector", "Interest_rate", "Loan_term", "");
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && trim($_GET['sSearch']) != "" ) {
			$search = strtoupper($this->db->escape_str($this->input->get('sSearch', TRUE)));

			$_sql_where[] = "
				(
					UCASE(Type_of_business) LIKE '%".$search."%'
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
					FROM product
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = " 	SELECT *
					FROM product 
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

	public function insert_($data)
	{
		$this->db->insert('product', $data);
		return $this->db->insert_id();
	}

	public function get_typeofbusiness()
	{
		$this->db->select('*');
		$this->db->from('mod_type_business');
		$this->db->where('type_business_status', '1');
		$sql = $this->db->get();
		$ret = $sql->result_array();

		$sql->free_result();
		return $ret;
	}

	public function get_product_by($id)
	{
		$this->db->select('*');
		$this->db->from('product');
		$this->db->where('Product_id', $id);
		$sql = $this->db->get();
		$ret = $sql->row_array();

		$sql->free_result();
		return $ret;
	}

	public function update_product($data, $ID)
	{
		$this->db->where('Product_id', $ID);
		$this->db->update('product', $data);
		return $this->db->affected_rows();
	}

	public function get_all_products()
	{
		$this->db->select('*');
		$this->db->from('product');
		$this->db->where('product_status', '1');
		$this->db->order_by('product_title', 'ASC');
		$sql = $this->db->get();
		$ret = $sql->result_array();

		$sql->free_result();
		return $ret;
	}

	public function get_product_kilat()
	{
		$this->db->select('*');
		$this->db->from('product');
		$this->db->where('product_status', '1');
		$this->db->where('type_of_business_id', '1');
		$sql = $this->db->get();
		$ret = $sql->result_array();

		$sql->free_result();
		return $ret;
	}

	public function get_record_repayment($id)
	{
		$this->db->select('*');
		$this->db->from('record_repayment');
		$this->db->where('Master_loan_id', $id);
		$sql = $this->db->get();
		$ret = $sql->row_array();

		$sql->free_result();
		return $ret;
	}
}