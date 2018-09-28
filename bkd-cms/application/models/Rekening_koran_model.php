<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekening_koran_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}

	function get_member_dt()
	{
		// ---- Get All data show as Json ----
		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array( "mum_status='1'");
		$sql_where 		= '';
		$cols 			= array( "m.id_mod_user_member", "Nama_pengguna", "mum_email", "Mobileno", "Tgl_record", "peringkat_pengguna", "mum_status", "");
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = strtoupper($this->db->escape_str(trim($this->input->get('sSearch', TRUE))));
		}

		// ------------- Custom Search ----------
		// Type Member
		$filter_1 = $this->db->escape_str($this->input->get('sSearch_3', TRUE));
		if(isset($filter_1) and !empty($filter_1)){
            $_sql_where[] = "mum_type=".$filter_1;
        }

		// limit
		$start 		= $this->Datatables_model->get_start();
		$rows 		= $this->Datatables_model->get_rows();
		// sort
		$sort 		= $this->Datatables_model->get_sort($cols);		
		$sort_dir 	= $this->Datatables_model->get_sort_dir();	
		        
        //running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM mod_user_member
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		// Kolom Pencarian
		if( $search!='' ){
			$_sql_where[] = "
				(
					UCASE(Nama_pengguna) LIKE '%".$search."%'
					OR UCASE(mum_email) LIKE '%".$search."%'
				)
			";
		}

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = " 	SELECT m.id_mod_user_member, mum_email, mum_type, mum_status, mum_telp, mum_type, mum_nomor_rekening, mum_usaha,
					Tgl_record, Id_penyelenggara, u.Id_pengguna, Nama_pengguna, peringkat_pengguna,
					Mobileno
					FROM mod_user_member m 
					LEFT JOIN {$this->user_ojk} u ON(u.id_mod_user_member=m.id_mod_user_member)
					LEFT JOIN {$this->user_ojk_detail} d ON(d.Id_pengguna=u.Id_pengguna) 
					{$sql_where}
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

	function get_rekening_koran_dt($uid)
	{
		// ---- Get All data show as Json ----
		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array( "wallet_member_id='{$uid}'");
		$sql_where 		= '';
		$cols 			= array( "Detail_wallet_id", "Date_transaction", "kode_transaksi", "Notes", "tipe_dana", "amount_detail", "balance");
		$sort 			= "desc";

		$filter_1 = $this->db->escape_str($this->input->get('sSearch_1', TRUE));
        $filter_2 = $this->db->escape_str($this->input->get('sSearch_6', TRUE));

        $startdate = date('Y-m-d', strtotime($filter_1)) . ' 00:00:00';
        $enddate   = date('Y-m-d', strtotime($filter_2)) . ' 23:59:59';
		if(isset($filter_1) && !empty($filter_1) && isset($filter_2) and !empty($filter_2)){
            $_sql_where[] = "(Date_transaction >='{$startdate}' AND Date_transaction <= '{$enddate}' ) ";
        }
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ) {
			$search = trim(strtoupper($this->db->escape_str($this->input->get('sSearch', TRUE))));
		}

		// limit
		$start 		= $this->Datatables_model->get_start();
		$rows 		= $this->Datatables_model->get_rows();
		// sort
		$sort 		= $this->Datatables_model->get_sort($cols);		
		$sort_dir 	= $this->Datatables_model->get_sort_dir();	

		// Kolom Pencarian
		if( $search!='' ){
			$_sql_where[] = "
				(
					UCASE(Date_transaction) LIKE '%".$search."%'
					OR UCASE(kode_transaksi) LIKE '%".$search."%'
					OR UCASE(Notes) LIKE '%".$search."%'
					OR UCASE(d.Amount) LIKE '%".$search."%'
					OR UCASE(balance) LIKE '%".$search."%'
				)
			";
		}

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);

		//running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM detail_wallet d
					LEFT JOIN {$this->master_wallet} m ON(d.Id=m.Id)
					{$sql_where}
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		$sql = " 	SELECT d.Id, Detail_wallet_id, Date_transaction, d.Amount as amount_detail, Notes, tipe_dana, d.User_id, kode_transaksi, balance, wallet_member_id
					FROM detail_wallet d 
					LEFT JOIN {$this->master_wallet} m ON(d.Id=m.Id)
					{$sql_where}
			    ";

		if($sort!='' && $sort_dir!='') $order = " ORDER BY $sort $sort_dir ";
		
		$query 	= $this->db->query($sql. $order. " LIMIT $start,$rows ");
		$data 	= $query->result();

		if( $search!='' ){
			$iFilteredTotal = count($query->result());
		}else{
			$iFilteredTotal = $iTotal;
		}
		//echo $sql;
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