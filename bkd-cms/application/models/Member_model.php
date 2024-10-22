<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}

	function get_member($type)
	{
		// ---- Get All data show as Json ----
		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array("mum_type={$type}", "(mum_status='1' OR mum_status='2')");
		$sql_where 		= '';
		$cols 			= array( "m.id_mod_user_member", "Nama_pengguna", "mum_email", "Mobileno", "p.user_group_name", "Tgl_record", "peringkat_pengguna", "mum_status", "");
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
					UCASE(Nama_pengguna) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(Mobileno) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(mum_email) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(user_group_name) LIKE '%".strtoupper($this->db->escape_str($search))."%'
				)
			";
		}

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);

		//running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM mod_user_member m 
					LEFT JOIN {$this->user_ojk} u ON(u.id_mod_user_member=m.id_mod_user_member)
					LEFT JOIN {$this->user_ojk_detail} d ON(d.Id_pengguna=u.Id_pengguna) 
					LEFT JOIN {$this->peminjam_group} p ON(p.id_user_group=u.id_user_group)
					{$sql_where}
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		$sql = " 	SELECT m.id_mod_user_member, mum_email, mum_type, mum_status, mum_telp, mum_nomor_rekening, mum_usaha,
					Tgl_record, Id_penyelenggara, u.Id_pengguna, p.id_user_group, Nama_pengguna, peringkat_pengguna, skoring,
					Mobileno, p.user_group_name
					FROM mod_user_member m 
					LEFT JOIN {$this->user_ojk} u ON(u.id_mod_user_member=m.id_mod_user_member)
					LEFT JOIN {$this->user_ojk_detail} d ON(d.Id_pengguna=u.Id_pengguna) 
					LEFT JOIN {$this->peminjam_group} p ON(p.id_user_group=u.id_user_group)
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

	function get_member_all()
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
		$cols 			= array( "m.id_mod_user_member", "Nama_pengguna", "mum_email", "Mobileno", "p.user_group_name", "Tgl_record", "peringkat_pengguna", "mum_status", "");
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
					UCASE(Nama_pengguna) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(Mobileno) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(mum_email) LIKE '%".strtoupper($this->db->escape_str($search))."%'
				)
			";
		}

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);

		//running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM mod_user_member m 
					LEFT JOIN {$this->user_ojk} u ON(u.id_mod_user_member=m.id_mod_user_member)
					LEFT JOIN {$this->user_ojk_detail} d ON(d.Id_pengguna=u.Id_pengguna) 
					LEFT JOIN {$this->peminjam_group} p ON(p.id_user_group=u.id_user_group)
					{$sql_where}
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		$sql = " 	SELECT m.id_mod_user_member, mum_email, mum_type, mum_status, mum_telp, mum_status, mum_nomor_rekening, mum_usaha,
					Tgl_record, Id_penyelenggara, u.Id_pengguna, p.id_user_group, Nama_pengguna, peringkat_pengguna, skoring,
					Mobileno, p.user_group_name, mum_create_date
					FROM mod_user_member m 
					LEFT JOIN {$this->user_ojk} u ON(u.id_mod_user_member=m.id_mod_user_member)
					LEFT JOIN {$this->user_ojk_detail} d ON(d.Id_pengguna=u.Id_pengguna)
					LEFT JOIN {$this->peminjam_group} p ON(p.id_user_group=u.id_user_group) 
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

	function get_member_byid($id)
	{
		$sql = " SELECT * 
					FROM {$this->mod_user_member} m 
					WHERE id_mod_user_member='{$id}' LIMIT 1 ";
		$query 	= $this->db->query($sql);
		$data 	= $query->row_array();

		return $data;
	}

	function get_usermember_by($id)
	{
		$this->db->select('*');
		$this->db->from($this->user_ojk. ' u');
		$this->db->join($this->user_ojk_detail. ' d', 'd.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->mod_user_member. ' m', 'm.id_mod_user_member=u.id_mod_user_member', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->where('m.id_mod_user_member', $id);
		$this->db->limit(1);
		$query 	= $this->db->get();
		$data 	= $query->row_array();
		return $data;
	}

	function get_usermember_less($id)
	{
		$this->db->select('u.Id_pengguna, Nama_pengguna, Id_ktp, mum_email, Mobileno, What_is_the_name_of_your_business, Alamat, Kota, Provinsi');
		$this->db->from($this->user_ojk. ' u');
		$this->db->join($this->user_ojk_detail. ' d', 'd.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->mod_user_member. ' m', 'm.id_mod_user_member=u.id_mod_user_member', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->where('m.id_mod_user_member', $id);
		$this->db->limit(1);
		$query 	= $this->db->get();
		$data 	= $query->row_array();
		return $data;
	}

	function get_user_ojk_by($id)
	{
		$this->db->select('Nama_pengguna, mum_email');
		$this->db->from($this->user_ojk. ' u');
		$this->db->join($this->mod_user_member. ' m', 'm.id_mod_user_member=u.id_mod_user_member', 'left');
		$this->db->where('u.Id_pengguna', $id);
		$this->db->limit(1);
		$query 	= $this->db->get();
		$data 	= $query->row_array();
		return $data;
	}

	function get_user_ojk_bymember($id)
	{
		$this->db->select('Id_pengguna, Nama_pengguna, mum_email');
		$this->db->from($this->user_ojk. ' u');
		$this->db->join($this->mod_user_member. ' m', 'm.id_mod_user_member=u.id_mod_user_member', 'left');
		$this->db->where('m.id_mod_user_member', $id);
		$this->db->limit(1);
		$query 	= $this->db->get();
		$data 	= $query->row_array();
		return $data;
	}
	
	public function delete_member($id)
	{
		$id = $this->db->escape_str($id);
		return $this->db->delete($this->mod_user_member, array('id_mod_user_member'=>$id));
	}

	public function delete_user_ojk($id)
	{
		$id = $this->db->escape_str($id);
		return $this->db->delete($this->user_ojk, array('id_mod_user_member'=>$id));
	}

	public function delete_user_ojk_detail($id)
	{
		$id = $this->db->escape_str($id);
		return $this->db->delete($this->user_ojk_detail, array('Id_pengguna'=>$id));
	}

	public function delete_profil_geografi($id)
	{
		$id = $this->db->escape_str($id);
		return $this->db->delete($this->profile_geografi, array('User_id'=>$id));
	}

	public function set_member_status($ID, $dostatus)
	{
		$this->db->set('mum_status', $dostatus);
		$this->db->where('id_mod_user_member', $ID);
		$this->db->update($this->mod_user_member);
		return $this->db->affected_rows();
	}

	public function update_user_ojk($data, $ID)
	{
		$this->db->where('id_mod_user_member', $ID) ;
		$this->db->update($this->user_ojk, $data);
		return $this->db->affected_rows();
	}

	public function update_user_group_ojk($ID, $membergroup)
	{
		/*$ID = $this->db->escape_str($ID);
		$data = $this->Master_model->escape_all($data);*/

		$update = array( 'id_user_group'=> $membergroup); 
	/*	$this->db->set('id_user_group', $membergroup);*/
		$this->db->where('Id_pengguna', $ID);
		$this->db->update($this->user_ojk, $update);
		return $this->db->affected_rows();
	}

/*	public function update_user_ojk1($data, $membergroup)
	{
		$this->db->where('id_pengguna', $membergroup);
		$this->db->update($this->user_ojk, $data);
		return $this->db->affected_rows();
	}*/

	public function update_user_ojkdetail($data, $ID)
	{
		$this->db->where('Id_pengguna', $ID);
		$this->db->update($this->user_ojk_detail, $data);
		return $this->db->affected_rows();
	}

	public function update_profil_geografi($data, $ID)
	{
		$this->db->where('User_id', $ID);
		$this->db->update($this->profile_geografi, $data);
		return $this->db->affected_rows();
	}

	public function update_member($data, $ID)
	{
		$this->db->where('id_mod_user_member', $ID);
		$this->db->update($this->mod_user_member, $data);
		return $this->db->affected_rows();
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

	function get_list_pendana($pinjaman_code)
	{
		$this->db->select('Nama_pengguna, Id_ktp');
		$this->db->from($this->tabel_pendana.' pd');
		$this->db->join($this->user_ojk.' u', 'u.Id_pengguna=pd.User_id', 'left');
		$this->db->where('pd.Master_loan_id', $pinjaman_code);
		$sql = $this->db->get();

		return $sql->result_array();
	}

	//tambahan baru

	// ------------------ User Privilege Group  ----------------

	function get_allgroup()
	{
		$this->db->select('*');
		$this->db->from($this->peminjam_group);
		$this->db->order_by('user_group_name', 'asc');
		$sql = $this->db->get();
		
		return $sql->result_array();
	}

	function get_user_group_dt()
	{		
		// variable initialization
		$search 		= "";
		$start 			= 0;
		$rows 			= 10;
		$iTotal 		= 0;
		$iFilteredTotal = 0;
		$_sql_where 	= array();
		$sql_where 		= '';
		$cols 			= array( "id_user_group", "user_group_name");
		$sort 			= "desc";
		
		// get search value (if any)
		if (isset($_GET['sSearch']) && trim($_GET['sSearch']) != "" ) {
			$search = strtoupper($this->db->escape_str($this->input->get('sSearch', TRUE)));
			
			$_sql_where[] = "( UCASE(user_group_name) LIKE '%".strtoupper($this->db->escape_str($search))."%' ) ";
		}

		// limit
		$start 		= $this->Datatables_model->get_start();
		$rows 		= $this->Datatables_model->get_rows();
		// sort
		$sort 		= $this->Datatables_model->get_sort($cols);		
		$sort_dir 	= $this->Datatables_model->get_sort_dir();	
		        
        //running query		
		$sql = " 	SELECT count(0) as iTotal
					FROM {$this->peminjam_group}
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		if(count($_sql_where)>0) $sql_where = " WHERE ".implode(' AND ',$_sql_where);	

		$sql = " 	SELECT * FROM {$this->peminjam_group}
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

	function insert_group($data)
	{
		$this->db->insert($this->peminjam_group, $data);
		return $this->db->affected_rows();
	}

	function update_group($data,$id)
	{
		$this->db->where('id_user_group', $id);
		$this->db->update($this->peminjam_group, $data);
		return $this->db->affected_rows();
	}

	function get_group_by($id)
	{
		$query = $this->db->query(" SELECT * FROM {$this->peminjam_group} WHERE id_user_group='$id' ");
		return $query->row_array();
	}

	function delete_user_group($id)
	{
		$this->db->where('id_user_group', $id);
		$this->db->delete($this->peminjam_group);
		return $this->db->affected_rows();
	}

	function has_login( $checkAccess = TRUE )
	{
		$log_status = $this->session->userdata('login_status');
		$user = $this->session->userdata('current_user');
		$role = $this->session->userdata('role');

		if($log_status !=1 OR empty($user) OR empty($role) )
		{
			$this->session->set_userdata('message','You must log in to this application. ');
			$this->session->set_userdata('message_type','error');
			redirect('log/in');
			die();
		}

		$this->has_access();
		//$this->insert_log();
	}


		function has_access()
	{
	    $default_controller = isset($this->router->routes['default_controller']) ? $this->router->routes['default_controller'] : 'welcome';
		$default_method = 'index';

		$controller = $this->uri->segment(1, $default_controller);
		$action = $this->uri->segment(2, $default_method);

		$default_access = array(
			$default_controller => $default_controller,
			$default_controller.'/'.$default_method => $default_controller.'/'.$default_method,
			$controller.'/json'			=> $controller.'/json',
			$controller.'/json_modal'	=> $controller.'/json_modal',
			'dashboard/index'	=> 'dashboard/index'
		);

		$CU_role_id = $this->session->userdata('role');

		if ($CU_role_id != 1) {	// 1. Super Admin
			$acc        = $this->get_user_access($CU_role_id);
			$acc        = array_merge($default_access,$acc);

			//_d($acc);exit();

			if( ! isset($acc[$controller.'/'.$action]) ){
				$this->session->set_userdata('message','You do not have permission to access / take action on the page that you are headed.');
				$this->session->set_userdata('message_type','error');
				$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();
				redirect($ref);
				die();
			}
		}
	    
	}

	// batas tambahan baru

}