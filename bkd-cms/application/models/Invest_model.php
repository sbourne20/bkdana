<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invest_model extends CI_Model
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
		$cols 			= array( "pendanaan_id", "Id", "Tgl_penawaran_pemberian_pinjaman", "Nama_pengguna", "Jml_penawaran_pemberian_pinjaman", "Permintaan_jaminan", "Jml_penawaran_pemberian_pinjaman_disetujui", "");
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
					UCASE(Nama_pengguna) LIKE '%".strtoupper($this->db->escape_str($search))."%'
					OR UCASE(Jml_penawaran_pemberian_pinjaman) LIKE '%".strtoupper($this->db->escape_str($search))."%'
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

	function get_data_byid($id)
	{
		$this->db->select('*');
		$this->db->from($this->tabel_pendana. ' p');
		$this->db->join($this->user_ojk. ' u', 'u.Id_pengguna=p.User_id', 'left');
		$this->db->join($this->user_ojk_detail. ' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->join($this->product. ' prod', 'prod.Product_id=p.Product_id', 'left');
		$this->db->join('(SELECT Option_value, Option_label as Nama_Kota
						 from master_option) z','z.Option_value=g.Kota', 'left');
		$this->db->join('(SELECT Option_value, Option_label as Nama_Provinsi
						 from master_option) a','a.Option_value=g.Provinsi', 'left');
		$this->db->where('Id', $id);
		$sql = $this->db->get();

		return $sql->row_array();
	}

	function check_pendanaan($id)
	{
		$this->db->select('Id, User_id');
		$this->db->from($this->tabel_pendana);
		$this->db->where('Id', $id);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}
	
	public function delete_pendanaan($id)
	{
		$this->db->delete($this->tabel_pendana, array('Id'=>$id));
		return $this->db->delete($this->tabel_detail_pendanaan, array('transaksi_id'=>$id));
	}

	function do_verify($id)
	{
		$data = array(
			'pendanaan_status' => 'verified',
			'tgl_disetujui'    => date('Y-m-d H:i:s')
		);
		$this->db->where('Id', $id);
		$this->db->update($this->tabel_pendana, $data);
		return $this->db->affected_rows();
	}
}