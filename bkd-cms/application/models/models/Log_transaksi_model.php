<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_transaksi_model extends CI_Model
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

	// ============== Log Peminjam ==================
	function get_log_transaksi_pinjam($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->mod_log_transaksi_pinjaman);
		$this->db->where('ltp_Master_loan_id', $ordercode);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function update_log_transaksi_pinjaman($code, $data)
	{
		$this->db->where('ltp_Master_loan_id', $code);
		$this->db->update($this->mod_log_transaksi_pinjaman, $data);
		return $this->db->affected_rows();
	}

	

	// ============= Log Pendana ===================
	function insert_log_transaksi_pendana($data)
	{
		$this->db->insert($this->mod_log_transaksi_pendana, $data);
		return $this->db->insert_id();
	}

	function get_log_transaksi_pendana($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->mod_log_transaksi_pendana);
		$this->db->where('Master_loan_id', $ordercode);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_log_transaksi_pendana_byloan($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->mod_log_transaksi_pendana);
		$this->db->where('Master_loan_id', $ordercode);
		$sql = $this->db->get();
		$ret = $sql->result_array();
		$sql->free_result();
		return $ret;
	}

	function get_log_pendanaan_by_codedana($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->mod_log_transaksi_pendana);
		$this->db->where('Id_pendanaan', $ordercode);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function insert_log_frozen($data)
	{
		$this->db->insert($this->mod_log_frozen, $data);
		return $this->db->insert_id();
	}

	// ======== get member =======

	function get_user_pendanaan($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->tabel_pendana. ' pd');
		$this->db->join($this->user_ojk. ' u', 'u.Id_pengguna=pd.User_id', 'left');
		$this->db->where('Master_loan_id', $ordercode);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
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

	function get_platform_fee_dt()
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
		$cols 			= array( "ltp_id", "ltp_Master_loan_id", "ltp_platform_fee", "Master_loan_status");
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && trim($_GET['sSearch']) != "" ) {
			$search = strtoupper($this->db->escape_str($this->input->get('sSearch', TRUE)));

			$_sql_where[] = "
				(
					UCASE(ltp_Master_loan_id) LIKE '%".$search."%' OR
					UCASE(ltp_platform_fee) LIKE '%".$search."%'
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
					FROM {$this->mod_log_transaksi_pinjaman}
					$sql_where
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();


		$sql = " 	SELECT *
					FROM {$this->mod_log_transaksi_pinjaman} log
					LEFT JOIN {$this->tabel_pinjaman} p ON(p.Master_loan_id=log.ltp_Master_loan_id)
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
}