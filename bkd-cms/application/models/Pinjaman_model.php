<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pinjaman_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}

	function get_all_kilat_dt()
	{
		// ---- Get All data show as Json ----
		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array( "prod.type_of_business_id = '1'" );
		$sql_where 		= '';
		$cols 			= array( "pinjam_primary_id", "Master_loan_id", "Tgl_permohonan_pinjaman", "Nama_pengguna", "product_title", "Jml_permohonan_pinjaman", "Jml_permohonan_pinjaman_disetujui", "Master_loan_status", "");
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

		// Kolom Pencarian
		if( $search!='' ){
			$_sql_where[] = "
				(
					UCASE(Master_loan_id) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(Nama_pengguna) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(product_title) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(Jml_permohonan_pinjaman) LIKE '%".strtoupper($this->db->escape_str($search))."%'
				)
			";
		}

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);
		        
        //running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN user u ON (u.Id_pengguna=p.User_id)
					LEFT JOIN product prod ON (prod.Product_id=p.Product_id)
					LEFT JOIN mod_user_member mem ON (mem.id_mod_user_member=u.id_mod_user_member)
					{$sql_where}
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();	

		$sql = " 	SELECT * 
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN user u ON (u.Id_pengguna=p.User_id)
					LEFT JOIN product prod ON (prod.Product_id=p.Product_id)
					LEFT JOIN mod_user_member mem ON (mem.id_mod_user_member=u.id_mod_user_member)
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

	function get_all_kilat_draft()
	{
		// ---- Get All data show as Json ----
		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array( "prod.type_of_business_id = '1'" );
		$sql_where 		= '';
		$cols 			= array( "pinjam_primary_id", "Master_loan_id", "Tgl_permohonan_pinjaman", "Nama_pengguna", "product_title", "Jml_permohonan_pinjaman", "Jml_permohonan_pinjaman_disetujui", "Master_loan_status", "");
		$sort 			= "desc";

		$_sql_where[] = "(Master_loan_status = 'review' OR Master_loan_status = 'draft') ";
		
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
					FROM profil_permohonan_pinjaman 
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		// Kolom Pencarian
		if( $search!='' ){
			$_sql_where[] = "
				(
					UCASE(Master_loan_id) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(Nama_pengguna) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(product_title) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(Jml_permohonan_pinjaman) LIKE '%".strtoupper($this->db->escape_str($search))."%'
				)
			";
		}

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = " 	SELECT * 
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN user u ON (u.Id_pengguna=p.User_id)
					LEFT JOIN product prod ON (prod.Product_id=p.Product_id)
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

	function get_all_mikro_dt()
	{
		// ---- Get All data show as Json ----
		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array( "prod.type_of_business_id = '3'" );
		$sql_where 		= '';
		$cols 			= array( "pinjam_primary_id", "Master_loan_id", "Tgl_permohonan_pinjaman", "Nama_pengguna", "product_title", "Jml_permohonan_pinjaman", "Jml_permohonan_pinjaman_disetujui", "Master_loan_status", "");
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

		// Kolom Pencarian
		if( $search!='' ){
			$_sql_where[] = "
				(
					UCASE(Master_loan_id) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(Nama_pengguna) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(product_title) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(Jml_permohonan_pinjaman) LIKE '%".strtoupper($this->db->escape_str($search))."%'
				)
			";
		}

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	
		        
        //running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN user u ON (u.Id_pengguna=p.User_id)
					LEFT JOIN product prod ON (prod.Product_id=p.Product_id)
					$sql_where
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		$sql = " 	SELECT * 
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN user u ON (u.Id_pengguna=p.User_id)
					LEFT JOIN product prod ON (prod.Product_id=p.Product_id)
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

	function get_all_mikro_draft()
	{
		// ---- Get All data show as Json ----
		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array( "prod.type_of_business_id = '3'" );
		$sql_where 		= '';
		$cols 			= array( "pinjam_primary_id", "Master_loan_id", "Tgl_permohonan_pinjaman", "Nama_pengguna", "product_title", "Jml_permohonan_pinjaman", "Jml_permohonan_pinjaman_disetujui", "Master_loan_status", "");
		$sort 			= "desc";

		$_sql_where[] = "(Master_loan_status = 'review' OR Master_loan_status = 'draft') ";
		
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
					FROM profil_permohonan_pinjaman 
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		// Kolom Pencarian
		if( $search!='' ){
			$_sql_where[] = "
				(
					UCASE(Master_loan_id) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(Nama_pengguna) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(product_title) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(Jml_permohonan_pinjaman) LIKE '%".strtoupper($this->db->escape_str($search))."%'
				)
			";
		}

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = " 	SELECT * 
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN user u ON (u.Id_pengguna=p.User_id)
					LEFT JOIN product prod ON (prod.Product_id=p.Product_id)
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
		$cols 			= array( "pinjam_primary_id", "Master_loan_id", "Tgl_permohonan_pinjaman", "Nama_pengguna", "product_title", "Jml_permohonan_pinjaman", "Jml_permohonan_pinjaman_disetujui", "Master_loan_status", "");
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
					FROM profil_permohonan_pinjaman 
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		// Kolom Pencarian
		if( $search!='' ){
			$_sql_where[] = "
				(
					UCASE(Nama_pengguna) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(product_title) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(Jml_permohonan_pinjaman) LIKE '%".strtoupper($this->db->escape_str($search))."%'
				)
			";
		}

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = " 	SELECT * 
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN user u ON (u.Id_pengguna=p.User_id)
					LEFT JOIN product prod ON (prod.Product_id=p.Product_id)
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

	function get_pinjaman_byid($id)
	{
		$sql = " SELECT * 
					FROM {$this->tabel_pinjaman} p
					WHERE Master_loan_id='{$id}' LIMIT 1 ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		return $data;
	}
	
	public function delete_($id)
	{
		return $this->db->delete($this->tabel_pinjaman, array('Master_loan_id'=>$id));
	}

	public function delete_detail($id)
	{
		return $this->db->delete($this->detail_profil_permohonan_pinjaman, array('Master_loan_id'=>$id));
	}

	public function delete_log_pinjaman($id)
	{
		return $this->db->delete($this->mod_log_transaksi_pinjaman, array('ltp_Master_loan_id'=>$id));
	}

	public function delete_tempo($id)
	{
		return $this->db->delete($this->mod_tempo, array('kode_transaksi'=>$id));
	}

	// ===== WALLET ======== //
	function get_wallet_user($userid)
	{
		$this->db->where('User_id', $userid);
		$this->db->from($this->master_wallet);
		$sql = $this->db->get();

		return $sql->row_array();
	}

	function insert_master_wallet($data)
	{
		$this->db->insert($this->master_wallet, $data);
		return $this->db->insert_id();
	}

	function insert_detail_wallet($data)
	{
		$this->db->insert($this->detail_wallet, $data);
		return $this->db->insert_id();
	}
	// ================ Mod_products_member ==================

	function get_member_id_byproduct($ID)
	{
		$ID = $this->db->escape_str($ID);

		$this->db->where('product_id', $ID);
		$this->db->from($this->mod_products_member);
		$sql = $this->db->get();

		return $sql->row_array();
	}

	function approve_pinjaman($ID, $money)
	{
		$ID = $this->db->escape_str($ID);

		$data = array(
			'Jml_permohonan_pinjaman_disetujui' => $money,
			'Master_loan_status'                => 'approve',
			'Total_loan_outstanding'            => $money,
			'tgl_pinjaman_disetujui'            => date('Y-m-d H:i:s')
		);
		$this->db->where('Master_loan_id', $ID);
		$this->db->update($this->tabel_pinjaman, $data);
		return $this->db->affected_rows();
	}

	function draft_pinjaman($ID, $money, $date_fundraise, $pinjaman, $period)
	{
		$ID = $this->db->escape_str($ID);

		$data = array(
			'Jml_permohonan_pinjaman_disetujui' => $money,
			'Master_loan_status'                => 'draft',
			'date_fundraise'					=> $date_fundraise,
			'fundraising_period'                => $period,
			'Total_loan_outstanding'            => $pinjaman,
			'tgl_pinjaman_disetujui'            => date('Y-m-d H:i:s')
		);
		$this->db->where('Master_loan_id', $ID);
		$this->db->update($this->tabel_pinjaman, $data);
		return $this->db->affected_rows();
	}

	function approval_pinjaman($ID, $money, $date_fundraise, $pinjaman, $period)
	{
		$ID = $this->db->escape_str($ID);

		$data = array(
			'Jml_permohonan_pinjaman_disetujui' => $money,
			'Master_loan_status'                => 'approve',
			'date_fundraise'					=> $date_fundraise,
			'fundraising_period'                => $period,
			'Total_loan_outstanding'            => $pinjaman,
			'tgl_pinjaman_disetujui'            => date('Y-m-d H:i:s')
		);
		$this->db->where('Master_loan_id', $ID);
		$this->db->update($this->tabel_pinjaman, $data);
		return $this->db->affected_rows();
	}

	function pending_pinjaman($ID, $money, $date_fundraise, $pinjaman, $period)
	{
		$ID = $this->db->escape_str($ID);

		$data = array(
			'Jml_permohonan_pinjaman_disetujui' => $money,
			'Master_loan_status'                => 'pending',
			'date_fundraise'					=> $date_fundraise,
			'fundraising_period'                => $period,
			'Total_loan_outstanding'            => $pinjaman,
			'tgl_pinjaman_disetujui'            => date('Y-m-d H:i:s')
		);
		$this->db->where('Master_loan_id', $ID);
		$this->db->update($this->tabel_pinjaman, $data);
		return $this->db->affected_rows();
	}

	function set_approve_pinjaman($ID)
	{
		$data = array(
			'Master_loan_status'     => 'approve',
			'tgl_pinjaman_disetujui' => date('Y-m-d H:i:s')
		);
		$this->db->where('Master_loan_id', $ID);
		$this->db->update($this->tabel_pinjaman, $data);
		return $this->db->affected_rows();
	}

	function reject_pinjaman($ID)
	{
		$ID = $this->db->escape_str($ID);

		$data = array(
			'Master_loan_status' => 'reject'
		);
		$this->db->where('Master_loan_id', $ID);
		$this->db->update($this->tabel_pinjaman, $data);
		return $this->db->affected_rows();
	}

	function get_loan_byid($ID)
	{
		$this->db->select('*');
		$this->db->from($this->tabel_pinjaman. ' p');
		$this->db->join($this->user_ojk. ' u', 'u.Id_pengguna=p.User_id', 'left');
		$this->db->join($this->user_ojk_detail. ' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->join($this->product. ' prod', 'prod.Product_id=p.Product_id', 'left');
		$this->db->join($this->mod_type_business. ' t', 't.id_mod_type_business=prod.type_of_business_id', 'left');
		$this->db->where('Master_loan_id', $ID);
		$sql = $this->db->get();

		return $sql->row_array();
	}

	//TAMBAHAN BARU
		function get_loan_detail($ID)
	{
		$this->db->select('*');
		$this->db->from($this->tabel_pinjaman. ' p');
		//$this->db->join($this->user_ojk. ' u', 'u.Id_pengguna=p.User_id', 'left');
		//$this->db->join($this->user_ojk_detail. ' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		//$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->join($this->product. ' prod', 'prod.Product_id=p.Product_id', 'left');
		$this->db->join($this->mod_type_business. ' t', 't.id_mod_type_business=prod.type_of_business_id', 'left');
		$this->db->where('Master_loan_id', $ID);
		$sql = $this->db->get();

		return $sql->row_array();
	}
	//batas tambahan baru

	function find_expired_pinjaman()
	{
		// pinjaman mikro

		$sql = " SELECT * 
					FROM {$this->tabel_pinjaman} p
					WHERE Master_loan_status = 'pending'  
					AND ( date_fundraise != '0000-00-00' AND DATE(date_fundraise) < DATE(NOW()) )";

		$query 	= $this->db->query($sql);
		$data 	= $query->result_array();
		return $data;
	}

	function insert_log_transaksi($data)
	{
		$this->db->insert($this->mod_log_transaksi_pinjaman, $data);
		return $this->db->insert_id();
	}

	public function update_profil_pinjaman($data, $id)
	{
		$this->db->where('Master_loan_id', $id);
		$this->db->update($this->tabel_pinjaman, $data);
		return $this->db->affected_rows();
	}

	public function update_log_pinjaman($data, $id)
	{
		$this->db->where('ltp_Master_loan_id', $id);
		$this->db->update($this->mod_log_transaksi_pinjaman, $data);
		return $this->db->affected_rows();
	}
	function insert_profil_pinjaman5($data)
	{
		$this->db->insert('record_pinjaman', $data);
		//$this->db->insert($this->record_pinjaman, $data);
		return $this->db->insert_id();
	}
}