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
		$cols 			= array( "m.id_mod_user_member", "Nama_pengguna", "mum_email", "Mobileno", "Tgl_record", "peringkat_pengguna", "mum_status", "");
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
					{$sql_where}
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		$sql = " 	SELECT m.id_mod_user_member, mum_email, mum_type, mum_status, mum_telp, mum_nomor_rekening, mum_usaha,
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
		$cols 			= array( "m.id_mod_user_member", "Nama_pengguna", "mum_email", "Mobileno", "Tgl_record", "peringkat_pengguna", "mum_status", "");
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
					{$sql_where}
				";

		$q = $this->db->query($sql);
		$iTotal = $q->row('iTotal');

		$q->free_result();

		$sql = " 	SELECT m.id_mod_user_member, mum_email, mum_type, mum_status, mum_telp, mum_status, mum_nomor_rekening, mum_usaha,
					Tgl_record, Id_penyelenggara, u.Id_pengguna, Nama_pengguna, peringkat_pengguna,
					Mobileno, mum_create_date
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
		$this->db->where('id_mod_user_member', $ID);
		$this->db->update($this->user_ojk, $data);

		// $this->db->where('id_pengguna', $ID);
		// $this->db->update('user', $data2);
		return $this->db->affected_rows();
	}
/*
		public function update_user_ojk1($data, $ID)
	{
		$this->db->where('id_mod_user_member', $ID);
		$this->db->update($this->user_ojk, $data);
		// return $this->db->affected_rows();
		return $data;
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
}