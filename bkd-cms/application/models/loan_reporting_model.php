<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loan_reporting_model extends CI_Model
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
	$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array("Master_loan_status IN ('complete','lunas')");
		$sql_where 		= '';
		$cols 			= array("tp.Master_loan_id", 
								"u.Nama_pengguna", 
								"p.Type_of_business", 
								"Agent_Code",
								"tp.tgl_disburse", 
								"mltpin.ltp_tgl_jatuh_tempo", 
								"mltpin.ltp_product_loan_term", 
								"mltpin.ltp_total_pinjaman",
								"tp.Total_loan_outstanding",
								"Interest_rate_type",
								"mltpin.ltp_bunga_pinjaman",
								"x.Max_tgl",
								"daysbetween");
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
					FROM profil_permohonan_pinjaman
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	
		// $codeagent = "-";

		

		$sql = "SELECT tp.Master_loan_id, u.Nama_pengguna, p.Type_of_business, tp.tgl_disburse, mltpin.ltp_tgl_jatuh_tempo, mltpin.ltp_product_loan_term, mltpin.ltp_total_pinjaman, tp.Total_loan_outstanding,mltpin.ltp_bunga_pinjaman,x.Max_tgl, '-' as Agent_Code, '-' as Interest_rate_type, p.type_of_interest_rate_name, DATEDIFF(NOW(), mltpin.ltp_tgl_jatuh_tempo) as daysbetween
			FROM profil_permohonan_pinjaman tp 
			JOIN user u ON (u.Id_pengguna=tp.User_id)
			JOIN mod_log_transaksi_pinjaman mltpin ON (mltpin.ltp_master_loan_id=tp.Master_loan_id)
			JOIN product p ON (p.Product_id=mltpin.ltp_product_id)
		    JOIN (select Master_loan_id, MAX(tgl_pembayaran) Max_tgl
	        FROM record_repayment rr  
	        Group By Master_loan_id 
	        ) x
	        ON (x.Master_loan_id=tp.Master_loan_id) 	        
			{$sql_where}
			    ";

		if($sort!='' && $sort_dir!='') $order = " ORDER BY tgl_disburse DESC ";
		
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