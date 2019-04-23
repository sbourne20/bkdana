<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->Master_model->get_tables($this);
	}

	function has_login( $checkAccess = TRUE )
	{
		$login_status = isset($_SESSION['_bkdlog_'])? (int)(strip_tags($_SESSION['_bkdlog_'])) : '0';
        $uid          = isset($_SESSION['_bkduser_'])? strip_tags($_SESSION['_bkduser_']) : '0';
        $type         = isset($_SESSION['_bkdtype_'])? strip_tags($_SESSION['_bkdtype_']) : '0';

        $unique_session = isset($_COOKIE['_bkdsesi_'])? strip_tags($_COOKIE['_bkdsesi_']) : '';
        $this_machine   = md5($_SERVER['HTTP_USER_AGENT']);

        if ($unique_session != '')
        {
	        $data = $this->cek_session($uid);
	        //_d($data);
	        if ($data['mum_session'] != $this_machine) 
	        {
	        	$this->logoff();
	        	redirect('login');
				die();
	        }
        }

		if($login_status !=1 OR empty($uid) OR trim($uid)=='' )
		{
			redirect('login');
			die();
		}

		// --- check grade - peminjam only ---
		if ($type == '1') {
			$grade = $this->check_grade($uid);
			if ($grade['peringkat_pengguna_persentase'] < $this->config->item('minimum_grade') && $this->uri->segment(1) !='lengkapi-profil' )
			{
				$this->session->set_userdata('message','Lengkapi Profil Anda agar dapat melakukan pinjaman.');
				$this->session->set_userdata('message_type','error');
				redirect('lengkapi-profil');
			}
		}
	}

	function check_grade($id)
	{
		$this->db->select('peringkat_pengguna, peringkat_pengguna_persentase');
		$this->db->from('user');
		$this->db->where('id_mod_user_member', $id);
		$this->db->limit(1);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function cek_session($id)
	{
		$this->db->select('mum_session, mum_email');
		$this->db->from('mod_user_member');
		$this->db->where('id_mod_user_member', $id);
		$this->db->limit(1);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function logoff()
	{
		// delete a cookie safely
		setcookie ("_bkdlog_", "", 1);
		setcookie ("_bkdlog_", false);
		unset($_COOKIE["_bkdlog_"]);
		unset($_SESSION["_bkdlog_"]);

		setcookie ("_bkdmail_", "", 1);
		setcookie ("_bkdmail_", false);
		unset($_COOKIE["_bkdmail_"]);
		unset($_SESSION["_bkdmail_"]);

		setcookie ("_bkduser_", "", 1);
		setcookie ("_bkduser_", false);
		unset($_COOKIE["_bkduser_"]);
		unset($_SESSION["_bkduser_"]);

		setcookie ("_bkdname_", "", 1);
		setcookie ("_bkdname_", false);
		unset($_COOKIE["_bkdname_"]);
		unset($_SESSION["_bkdname_"]);

		setcookie ("_bkdtype_", "", 1);
		setcookie ("_bkdtype_", false);
		unset($_COOKIE["_bkdtype_"]);
		unset($_SESSION["_bkdtype_"]);

		setcookie ("_bkdsesi_", "", 1);
		setcookie ("_bkdsesi_", false);
		unset($_COOKIE["_bkdsesi_"]);

		$this->session->sess_destroy();
	}

	/*function has_login( $checkAccess = TRUE )
	{
		$login_status = isset($_SESSION['_bkdlog_'])? (int)(strip_tags($_SESSION['_bkdlog_'])) : '0';
        $uid          = isset($_SESSION['_bkduser_'])? strip_tags($_SESSION['_bkduser_']) : '0';

		if($login_status !=1 OR empty($uid) OR trim($uid)=='' )
		{
			redirect('login');
			die();
		}
	}*/

	function is_active_pendana()
	{
		$uid = isset($_SESSION['_bkduser_'])? strip_tags($_SESSION['_bkduser_']) : '0';
		$type = isset($_SESSION['_bkdtype_'])? strip_tags($_SESSION['_bkdtype_']) : '0';

		if (!empty($uid) && $type==2)
		{
			$member = $this->get_memberdata($uid);
			if ($member['mum_status'] == 2)
			{
				$this->session->set_userdata('message','Akun Pendana Anda sedang dalam review oleh Tim BKDana.');
				$this->session->set_userdata('message_type','warning');
				redirect('dashboard');
			}
		}
	}

	function insert_mod_usermember($data)
	{
		$this->db->insert($this->mod_user_member, $data);
		return $this->db->insert_id();
	}

	function insert_peminjam($data)
	{
		$this->db->insert($this->user, $data);
		return $this->db->insert_id();
	}

	function insert_cicilan($data)
	{
		$this->db->insert($this->detail_pinjaman, $data);
		return $this->db->insert_id();
	}

	function update_user($uid, $data)
	{
		$this->db->where('id_mod_user_member', $uid);
		$this->db->update($this->user, $data);
		return $this->db->affected_rows();
	}

	function update_user_byid($uid, $data)
	{
		$this->db->where('Id_pengguna', $uid);
		$this->db->update($this->user, $data);
		return $this->db->affected_rows();
	}

	function update_userdetail($uid, $data)
	{
		$this->db->where('Id_pengguna', $uid);
		$this->db->update($this->user_detail, $data);
		return $this->db->affected_rows();
	}

	function update_profil_geografi($uid, $data)
	{
		$this->db->where('User_id', $uid);
		$this->db->update($this->profile_geografi, $data);
		return $this->db->affected_rows();
	}

	function check_existing_member($email, $telp, $ktp="")
	{
		$this->db->select('m.id_mod_user_member, mum_fullname, mum_email, mum_password, Id_ktp, Mobileno, ID_No');
		$this->db->from('mod_user_member m');
		$this->db->join('user u', 'u.id_mod_user_member=m.id_mod_user_member', 'left');
		$this->db->join('user_detail ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->where('Mobileno', $telp);
		if ($email !=''){
			$this->db->or_where('mum_email', $email);
		}
		if ($ktp !=''){
			$this->db->or_where('ID_No', $ktp);
		}
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_memberdata($uid)
	{
		$this->db->select('*');
		$this->db->from($this->mod_user_member);
		$this->db->where('id_mod_user_member', $uid);		
		$this->db->limit('1');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_page($id)
	{
		$this->db->select('*');
		$this->db->from($this->mod_pages);
		$this->db->where('p_id', $id);		
		$this->db->limit('1');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_pinjaman($id)
	{
		$this->db->select('*');
		$this->db->from($this->product);
		$this->db->where('type_of_business_id', $id);
		$this->db->where('product_status', '1');
		$sql = $this->db->get();
		$ret = $sql->result_array();
		$sql->free_result();
		return $ret;
	}

	function get_produk($id)
	{
		$this->db->select('*');
		$this->db->from($this->product);
		$this->db->where('Product_id', $id);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_user($uid)
	{
		$this->db->select('*');
		$this->db->from($this->user);
		$this->db->where('id_mod_user_member', $uid);
		$this->db->limit('1');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function insert_user($data)
	{
		$this->db->insert($this->user, $data);
		return $this->db->insert_id();
	}

	function insert_userdetail($data)
	{
		$this->db->insert($this->user_detail, $data);
		return $this->db->insert_id();
	}

	function insert_profil_geografi($data)
	{
		$this->db->insert($this->profile_geografi, $data);
		return $this->db->insert_id();
	}

	function insert_profil_pinjaman($data)
	{
		$this->db->insert($this->profil_permohonan_pinjaman, $data);
		/*$this->db->insert($this->record_pinjaman, $data);*/
		/*return $this->db->insert_id();*/
		/*$insert_id = $this->db->insert_id();*/
	//	$this->db->and_insert($this->record_pinjaman, $data);
		return $this->db->insert_id();
		//$this->db->insert_id($insert_data2);
	}

		function insert_profil_pinjaman1($data)
	{
		$this->db->insert('record_pinjaman', $data);
		return $this->db->insert_id();
	}

	function insert_profil_pendanaan($data)
	{
		$this->db->insert($this->profile_pendanaan, $data);
		return $this->db->insert_id();
	}

	function insert_top_up($data)
	{
		$this->db->insert($this->mod_top_up, $data);
		return $this->db->insert_id();
	}

	function check_ordercode_pinjaman($code)
	{
		$this->db->select('Master_loan_id');
		$this->db->from($this->profil_permohonan_pinjaman);
		$this->db->where('Master_loan_id', $code);		
		$this->db->limit('1');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function check_ordercode_transaksi_pendanaan($code)
	{
		$this->db->select('Id');
		$this->db->from($this->profile_pendanaan);
		$this->db->where('Id', $code);		
		$this->db->limit('1');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function check_ordercode_pendana($code)
	{
		$this->db->select('Id');
		$this->db->from($this->profile_pendanaan);
		$this->db->where('Id', $code);		
		$this->db->limit('1');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function insert_profil_pembiayaan($data)
	{
		$this->db->insert($this->profile_pendanaan, $data);
		return $this->db->insert_id();
	}

	function insert_detail_profil_pembiayaan($data)
	{
		$this->db->insert($this->detail_profile_pendanaan, $data);
		return $this->db->insert_id();
	}

	function get_jml_pinjam($uid)
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->profil_permohonan_pinjaman);
		$this->db->where('pinjam_member_id', $uid);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_jml_invest($uid)
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->profile_pendanaan);
		$this->db->where('dana_member_id', $uid);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_total_saldo($uid)
	{
		$this->db->select('Amount');
		$this->db->from($this->master_wallet);
		$this->db->where('wallet_member_id', $uid);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_my_loan($uid)
	{
		$this->db->select('*');
		$this->db->from($this->profil_permohonan_pinjaman. ' p');
		$this->db->join($this->product. ' prod', 'prod.Product_id=p.Product_id', 'left');
		$this->db->join($this->mod_type_business. ' tb', 'tb.id_mod_type_business=prod.type_of_business_id', 'left');
		$this->db->where('tb.id_mod_type_business', '1');
		$this->db->where('p.pinjam_member_id', $uid);
		$sql = $this->db->get();
		$ret = $sql->result_array();
		$sql->free_result();

		//echo $this->db->last_query();
		return $ret;
	}

	function get_my_invest($uid)
	{
		$this->db->select('*');
		$this->db->from($this->profile_pendanaan. ' pd');
		$this->db->join($this->product. ' prod', 'prod.Product_id=pd.Product_id', 'left');
		$this->db->join($this->mod_type_business. ' tb', 'tb.id_mod_type_business=prod.type_of_business_id', 'left');
		$this->db->where('tb.id_mod_type_business', '2'); // pendanaan
		$this->db->where('pd.dana_member_id', $uid);
		$sql = $this->db->get();
		$ret = $sql->result_array();
		$sql->free_result();

		//echo $this->db->last_query();
		return $ret;
	}

	function get_my_transactions_pinjam($uid, $limit, $start, $search=NULL)
	{
		$search_query = '';
		if ($search != NULL && $search !='')
		{
			$search_query = " AND Master_loan_id like '%{$search}%' ";
		}

		$sql = "SELECT 
			Master_loan_id as transaksi_id, 
			tgl_permohonan_pinjaman as tgl_transaksi, 
			Jml_permohonan_pinjaman as totalrp,
			Amount as Amount, 
			Jml_permohonan_pinjaman_disetujui as total_approve, 
			Master_loan_status as transaksi_status, 
			date_close, 
			tgl_pinjaman_disetujui as tgl_approve,
			product_title, Loan_term, id_mod_type_business, type_business_name
			FROM {$this->profil_permohonan_pinjaman} p
			LEFT JOIN {$this->product} prod ON(prod.Product_id=p.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			WHERE (tb.id_mod_type_business='1' OR tb.id_mod_type_business='3' OR tb.id_mod_type_business='5')
			AND p.pinjam_member_id='{$uid}' 
			{$search_query}
			ORDER BY tgl_permohonan_pinjaman DESC
			LIMIT {$start}, {$limit}
		";

		$queries = $this->db->query($sql);
		$ret = $queries->result_array();
		$queries->free_result();
		return $ret;
	}

	function get_my_transactions_pinjam_approve($uid, $limit, $start)
	{
		$sql = "SELECT 
			Master_loan_id as transaksi_id, 
			tgl_permohonan_pinjaman as tgl_transaksi, 
			Jml_permohonan_pinjaman as totalrp,
			Amount as Amount, 
			Jml_permohonan_pinjaman_disetujui as total_approve, 
			Master_loan_status as transaksi_status, 
			date_close, 
			tgl_pinjaman_disetujui as tgl_approve,
			product_title, Loan_term, id_mod_type_business, type_business_name, prod.type_of_interest_rate
			FROM {$this->profil_permohonan_pinjaman} p
			LEFT JOIN {$this->product} prod ON(prod.Product_id=p.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			WHERE (tb.id_mod_type_business='1' OR tb.id_mod_type_business='3' OR tb.id_mod_type_business='5')
			AND p.pinjam_member_id='{$uid}'
			AND (p.Master_loan_status = 'approve' OR p.Master_loan_status = 'complete' OR p.Master_loan_status = 'akad')
			ORDER BY tgl_permohonan_pinjaman DESC
			LIMIT {$start}, {$limit}
		";

		$queries = $this->db->query($sql);
		$ret = $queries->result_array();
		$queries->free_result();
		return $ret;
	}

	function get_my_transactions_analyst_approved($uid, $limit, $start)
	{
		$sql = "SELECT 
			Master_loan_id as transaksi_id, 
			tgl_permohonan_pinjaman as tgl_transaksi, 
			Jml_permohonan_pinjaman as totalrp,
			Amount as Amount, 
			Jml_permohonan_pinjaman_disetujui as total_approve, 
			Master_loan_status as transaksi_status, 
			date_close, 
			tgl_pinjaman_disetujui as tgl_approve,
			product_title, Loan_term, id_mod_type_business, type_business_name, prod.type_of_interest_rate
			FROM {$this->profil_permohonan_pinjaman} p
			LEFT JOIN {$this->product} prod ON(prod.Product_id=p.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			WHERE (tb.id_mod_type_business='5')
			AND p.pinjam_member_id='{$uid}'
			AND (p.Master_loan_status = 'user')
			ORDER BY tgl_permohonan_pinjaman DESC
			LIMIT {$start}, {$limit}
		";

		$queries = $this->db->query($sql);
		$ret = $queries->result_array();
		$queries->free_result();
		return $ret;
	}

	function get_my_transactions_pendana($uid, $limit, $start, $search=NULL)
	{
		$search_query = '';
		if ($search != NULL && $search !='')
		{
			$search_query = " AND Id like '%{$search}%' ";
		}

		$sql = "SELECT Id as transaksi_id, 
			Tgl_penawaran_pemberian_pinjaman as tgl_transaksi, 
			Jml_penawaran_pemberian_pinjaman as totalrp, 
			Jml_penawaran_pemberian_pinjaman_disetujui as total_approve, 
			tgl_disetujui as tgl_approve,
			pendanaan_status as transaksi_status,
			product_title, Loan_term, id_mod_type_business, type_business_name
			FROM {$this->profile_pendanaan} pd 
			LEFT JOIN {$this->profil_permohonan_pinjaman} pp ON(pp.Master_loan_id=pd.Master_loan_id)
			LEFT JOIN {$this->product} prod ON(prod.Product_id=pp.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			WHERE pd.dana_member_id={$uid} 
			{$search_query}
			ORDER BY pendanaan_id DESC
			LIMIT {$start}, {$limit}
		";

		$queries = $this->db->query($sql);
		$ret = $queries->result_array();
		$queries->free_result();
		return $ret;
	}

	function get_total_pinjam($uid)
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->profil_permohonan_pinjaman);
		$this->db->where('pinjam_member_id', $uid);
		$queries = $this->db->get();
		$ret = $queries->row_array();
		$queries->free_result();
		return $ret;
	}

	function get_total_pendana($uid)
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->profile_pendanaan);
		$this->db->where('dana_member_id', $uid);
		$queries = $this->db->get();
		$ret = $queries->row_array();
		$queries->free_result();
		return $ret;
	}

	// ------ list all transaksi pinjaman ------ 
	function all_list_transactions_pinjaman($limit, $start)
	{
		$sql = "SELECT 
			Master_loan_id as transaksi_id, 
			tgl_permohonan_pinjaman as tgl_transaksi, 
			Jml_permohonan_pinjaman as total_pinjam,
			Amount as Amount, 
			Jml_permohonan_pinjaman_disetujui as total_approve, 
			jml_kredit as jml_kredit,
			Master_loan_status as transaksi_status, 
			date_close, 
			tgl_pinjaman_disetujui as tgl_approve,
			Nama_pengguna as nama_peminjam,
			peringkat_pengguna,
			product_title, Loan_term, id_mod_type_business, type_business_name, type_of_interest_rate,
			(select count(*) as itotal from {$this->profile_pendanaan} where Master_loan_id=transaksi_id) as total_lender
			FROM {$this->profil_permohonan_pinjaman} p
			LEFT JOIN {$this->product} prod ON(prod.Product_id=p.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			LEFT JOIN {$this->user} u ON(u.Id_pengguna=p.User_id)
			WHERE  (tb.id_mod_type_business='1' OR tb.id_mod_type_business='3' OR tb.id_mod_type_business='4' OR tb.id_mod_type_business='5')
			AND (p.Master_loan_status='approve')
			ORDER BY tgl_permohonan_pinjaman DESC
			LIMIT {$start}, {$limit}
		";

		$queries = $this->db->query($sql);
		$ret = $queries->result_array();
		$queries->free_result();
		return $ret;
	}

	function get_list_transactions_pinjam_mikrousaha($limit, $start)
	{
		$sql = "SELECT 
			Master_loan_id as transaksi_id, 
			tgl_permohonan_pinjaman as tgl_transaksi, 
			Jml_permohonan_pinjaman as totalrp, 
			Amount as Amount,
			Jml_permohonan_pinjaman_disetujui as total_approve, 
			jml_kredit as jml_kredit,
			Master_loan_status as transaksi_status, 
			date_close, 
			tgl_pinjaman_disetujui as tgl_approve,
			Nama_pengguna as nama_peminjam,
			peringkat_pengguna,
			product_title, Loan_term, id_mod_type_business, type_business_name,
			(select count(*) as itotal from {$this->profile_pendanaan} where Master_loan_id=transaksi_id) as total_lender
			FROM {$this->profil_permohonan_pinjaman} p
			LEFT JOIN {$this->product} prod ON(prod.Product_id=p.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			LEFT JOIN {$this->user} u ON(u.Id_pengguna=p.User_id)
			WHERE (tb.id_mod_type_business='3' OR tb.id_mod_type_business='4')
			AND (p.Master_loan_status='approve' OR p.Master_loan_status='pending')
			ORDER BY tgl_permohonan_pinjaman DESC
			LIMIT {$start}, {$limit}
		";

		$queries = $this->db->query($sql);
		$ret = $queries->result_array();
		$queries->free_result();
		return $ret;
	}

	/*function get_list_transactions_pinjam($limit, $start)
	{
		$sql = "SELECT 
			Master_loan_id as transaksi_id, 
			tgl_permohonan_pinjaman as tgl_transaksi, 
			Jml_permohonan_pinjaman as totalrp, 
			Jml_permohonan_pinjaman_disetujui as total_approve, 
			Master_loan_status as transaksi_status, 
			date_close, 
			tgl_pinjaman_disetujui as tgl_approve,
			Nama_pengguna as nama_peminjam,
			product_title, Loan_term, id_mod_type_business, type_business_name
			FROM {$this->profil_permohonan_pinjaman} p
			LEFT JOIN {$this->product} prod ON(prod.Product_id=p.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			LEFT JOIN {$this->user} u ON(u.Id_pengguna=p.User_id)
			WHERE (tb.id_mod_type_business='1' OR tb.id_mod_type_business='3')
			AND p.Master_loan_status='approve'
			ORDER BY tgl_permohonan_pinjaman DESC
			LIMIT {$start}, {$limit}
		";

		$queries = $this->db->query($sql);
		$ret = $queries->result_array();
		$queries->free_result();
		return $ret;
	}*/

	function get_list_transactions_pinjam_total()
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->profil_permohonan_pinjaman);
		$this->db->where('Master_loan_status', 'approve');
		$queries = $this->db->get();
		$ret = $queries->row_array();
		$queries->free_result();
		return $ret;
	}

	function total_all_pinjaman()
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->profil_permohonan_pinjaman. ' p');
		$this->db->join($this->product. ' prod', 'prod.Product_id=p.Product_id', 'left');
		
		$this->db->group_start();
			$this->db->where('type_of_business_id', '1'); // kilat
			$this->db->or_where('type_of_business_id', '3'); // mikro
			$this->db->or_where('type_of_business_id', '4'); // usaha
			$this->db->or_where('type_of_business_id', '5'); // agri
		$this->db->group_end();
		
		$this->db->group_start();
		$this->db->where('Master_loan_status', 'approve');
		$this->db->or_where('Master_loan_status', 'pending');
		$this->db->group_end();

		$queries = $this->db->get();
		$ret = $queries->row_array();

		// echo $this->db->last_query();
		$queries->free_result();
		return $ret;
	}

	function get_total_peminjam_mikrousaha()
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->profil_permohonan_pinjaman. ' p');
		$this->db->join($this->product. ' prod', 'prod.Product_id=p.Product_id', 'left');
		
		$this->db->group_start();
			$this->db->where('type_of_business_id', '3'); // mikro
			$this->db->or_where('type_of_business_id', '4'); // usaha
		$this->db->group_end();
		
		$this->db->group_start();
		$this->db->where('Master_loan_status', 'approve');
		$this->db->or_where('Master_loan_status', 'pending');
		$this->db->group_end();

		$queries = $this->db->get();
		$ret = $queries->row_array();

		// echo $this->db->last_query();
		$queries->free_result();
		return $ret;
	}

	function get_transaksi_pinjam_byid($id)
	{
		$this->db->select('u.Id_pengguna, 
			Nama_pengguna, 
			Pekerjaan,
			peringkat_pengguna, 
			u.id_mod_user_member,
			What_is_the_name_of_your_business, 
			How_many_years_have_you_been_in_business, 
			Kota, 
			Id_ktp,
			p.Master_loan_id, 
			p.Master_loan_id as transaksi_id, 
			Jml_permohonan_pinjaman, 
			Informasi_kredit, 
			Jml_permohonan_pinjaman_disetujui,
			Amount, 
			tgl_pinjaman_disetujui, 
			Master_loan_status, 
			date_fundraise, 
			date_close, 
			p.fundraising_period, 
			Total_loan_repayment, 
			Total_loan_outstanding, 
			jml_kredit,
			prod.Product_id, 
			Loan_term, 
			Investor_return,
			type_business_name, 
			type_of_business_id,
			LENGTH(Profile_photo) as size_foto_profil,
			LENGTH(foto_usaha) as size_foto_usaha,
			Profile_photo,
			foto_usaha,
			nama_bank,
			Mobileno,
			images_foto_name,
			images_usaha_name,
			images_usaha_name2,
			images_usaha_name3,
			images_usaha_name4,
			images_usaha_name5,
			nama_peminjam,
			Alamat, Kota, Provinsi, prod.type_of_interest_rate,
			rr.jml_denda,
			(select count(*) as itotal from '.$this->profile_pendanaan.' where Master_loan_id=transaksi_id) as total_lender ');
		$this->db->from($this->profil_permohonan_pinjaman. ' p');
		$this->db->join($this->product. ' prod', 'prod.Product_id=p.Product_id', 'left');
		$this->db->join($this->mod_type_business. ' tb', 'tb.id_mod_type_business=prod.type_of_business_id', 'left');
		$this->db->join($this->user. ' u', 'u.Id_pengguna=p.User_id', 'left');
		$this->db->join($this->user_detail. ' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->join($this->record_repayment. ' rr', 'rr.Master_loan_id=p.Master_loan_id', 'left');
		$this->db->where('p.Master_loan_id', $id);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();

		//echo $this->db->last_query();
		return $ret;
	}

	function get_detail_pinjam_byid($id)
	{
		$this->db->select('*');
		$this->db->from($this->detail_pinjaman. ' dp');
		$this->db->where('dp.Master_loan_id', $id);
		$sql = $this->db->get();
		$ret = $sql->result_array();
		$sql->free_result();

		//echo $this->db->last_query();
		return $ret;
	}

	// ----------- Pendanaan ---------

	function get_transaksi_pendana_byid($id)
	{
		$this->db->select('*');
		$this->db->from($this->profile_pendanaan. ' p');
		$this->db->join($this->product. ' prod', 'prod.Product_id=p.Product_id', 'left');
		$this->db->join($this->mod_type_business. ' tb', 'tb.id_mod_type_business=prod.type_of_business_id', 'left');
		$this->db->join($this->user. ' u', 'u.Id_pengguna=p.User_id', 'left');
		$this->db->where('p.Id', $id);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();

		//echo $this->db->last_query();
		return $ret;
	}

	// ---- TOP UP ------

	function update_topup($id, $data)
	{
		$this->db->where('member_id', $id);
		$this->db->update($this->mod_top_up, $data);
		return $this->db->affected_rows();
	}

	function history_topup_member($id)
	{
		$this->db->select('*');
		$this->db->from($this->mod_top_up);
		$this->db->where('member_id', $id);
		$this->db->order_by('id_top_up', 'DESC');
		$sql = $this->db->get();
		$ret = $sql->result_array();
		$sql->free_result();
		return $ret;
	}

	function count_topup($id)
	{
		$this->db->select('id_top_up, flag_mail');
		$this->db->from($this->mod_top_up);
		$this->db->where('member_id', $id);
		$this->db->where('flag_mail', '1');
		$this->db->limit('1');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function check_topup_code($code)
	{
		$this->db->select('id_top_up');
		$this->db->from($this->mod_top_up);
		$this->db->where('kode_top_up', $code);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_kuota_pinjaman($code)
	{
		$this->db->select('SUM(Jml_penawaran_pemberian_pinjaman) as jml_pendanaan');
		$this->db->from($this->profile_pendanaan. ' p');
		$this->db->where('p.Master_loan_id', $code);
		$this->db->where('p.pendanaan_status !=', 'expired');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();

		//echo $this->db->last_query();
		return $ret;
	}

	function approve_pinjaman_bycode($code)
	{
		$datetime = date('Y-m-d H:i:s');

		$this->db->set('Master_loan_status', 'akad');
		$this->db->set('tgl_pinjaman_disetujui', $datetime);
		$this->db->set('tgl_disburse', $datetime);
		$this->db->where('Master_loan_id', $code);
		$this->db->update($this->profil_permohonan_pinjaman);
		return $this->db->affected_rows();
	}

	function approve_transaksi_pendana_bypinjaman($pinj_code)
	{
		// status awal pending: artinya total kuota pendanaan blm 100%
		// approve = kuota sudah 100% 
		
		$datetime = date('Y-m-d H:i:s');

		$this->db->set('pendanaan_status', 'approve');
		$this->db->set('tgl_disetujui', $datetime);
		$this->db->where('Master_loan_id', $pinj_code);
		$this->db->update($this->profile_pendanaan);
		return $this->db->affected_rows();
	}

	function tambah_kredit_peminjam($code, $kredit)
	{
		$code   = $this->db->escape_str($code);
		$kredit = $this->db->escape_str($kredit);

		$datetime = date('Y-m-d H:i:s');

		$sql = "UPDATE {$this->profil_permohonan_pinjaman} 
				SET jml_kredit = jml_kredit + {$kredit} ,
				tgl_perubahan_kredit = '{$datetime}'
				WHERE Master_loan_id = '{$code}' ";
		$run = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	function get_user_grade($uid)
	{
		$this->db->select('peringkat_pengguna, peringkat_pengguna_persentase as completeness_profile');
		$this->db->from($this->user.' u');
		$this->db->where('id_mod_user_member', $uid);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function update_total_loan_repayment($trans_id, $uid, $jml)
	{
		$trans_id = $this->db->escape_str($trans_id);
		$uid      = $this->db->escape_str($uid);
		$jml      = $this->db->escape_str($jml);
		
		$sql = "UPDATE {$this->profil_permohonan_pinjaman} 
				SET Total_loan_repayment = Total_loan_repayment + {$jml} ,
				Total_loan_outstanding = Total_loan_outstanding - {$jml}
				WHERE Master_loan_id = '{$trans_id}' AND pinjam_member_id  = '{$uid}' ";
		$run = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	function close_pinjaman($trans_id)
	{
		$datetime = date('Y-m-d H:i:s');

		$this->db->set('date_close', $datetime);
		$this->db->set('Master_loan_status', 'lunas');
		$this->db->set('Payment_status', 'lunas');
		$this->db->where('Master_loan_id', $trans_id);
		$this->db->update($this->profil_permohonan_pinjaman);
		return $this->db->affected_rows();
	}

	// ====== REDEEEM =======//
	function get_list_myredeem($uid)
	{
		$this->db->select('*');
		$this->db->from($this->mod_redeem);
		$this->db->where('redeem_member_id', $uid);
		$sql = $this->db->get();
		$ret = $sql->result_array();
		$sql->free_result();
		return $ret;
	}

	function insert_redeem($data)
	{
		$this->db->insert($this->mod_redeem, $data);
		return $this->db->insert_id();
	}

	function check_pending_redeem($uid)
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->mod_redeem);
		$this->db->where('redeem_status', 'pending');
		$this->db->where('redeem_member_id', $uid);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_redeem_bycode($code)
	{
		$this->db->select('*');
		$this->db->from($this->mod_redeem);
		$this->db->where('redeem_kode', $code);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}	

	// ======== RANKING ============
	function get_data_peminjam_kilat_rows($uid)
	{
		$this->db->select('
			Nama_pengguna,
			Id_ktp,
			Tempat_lahir,
			Tanggal_lahir,
			Jenis_kelamin,
			Pekerjaan,
			Nomor_rekening,
			nama_bank,
			Mobileno,
			images_foto_name,
			images_ktp_name,
			Alamat,
			Kota,
			Provinsi,
			Kodepos
			');
		/*$this->db->select('
			u.Id_pengguna as Id_pengguna_user,
			Tgl_record,
			Nama_pengguna,
			Jenis_pengguna,
			Id_ktp,
			Tempat_lahir,
			Tanggal_lahir,
			Jenis_kelamin,
			Pekerjaan,
			Nomor_rekening,
			nama_bank,
			id_mod_user_member,
			ud.Id_pengguna,
			user_type,
			Mobileno,
			Profile_photo,
			Photo_id,
			Occupation,
			ID_type,
			ID_No,
			Alamat,
			Kota,
			Provinsi,
			Kodepos,			
			g.User_id
			');*/
		$this->db->from($this->user. ' u');
		$this->db->join($this->user_detail. ' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->where('u.Id_pengguna', $uid);
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_data_peminjam_mikro_rows($uid)
	{
		$this->db->select('
			Nama_pengguna,
			Id_ktp,
			Tempat_lahir,
			Tanggal_lahir,
			Jenis_kelamin,
			Pekerjaan,
			Nomor_rekening,
			nama_bank,
			Mobileno,
			images_foto_name,
			images_ktp_name,
			images_usaha_name,
			images_usaha_name2,
			images_usaha_name3,
			images_usaha_name4,
			images_usaha_name5,
			deskripsi_usaha,
			omzet_usaha,
			modal_usaha,
			margin_usaha,
			biaya_operasional,
			laba_usaha,
			Alamat,
			Kota,
			Provinsi,
			Kodepos,
			What_is_the_name_of_your_business,
			How_many_years_have_you_been_in_business			
			');
		/*$this->db->select('
			u.Id_pengguna as Id_pengguna_user,
			Tgl_record,
			Nama_pengguna,
			Jenis_pengguna,
			Id_ktp,
			Tempat_lahir,
			Tanggal_lahir,
			Jenis_kelamin,
			Pekerjaan,
			Nomor_rekening,
			id_mod_user_member,
			ud.Id_pengguna,
			user_type,
			Mobileno,
			Profile_photo,
			Photo_id,
			Occupation,
			ID_type,
			ID_No,
			What_is_the_name_of_your_business,
			How_many_years_have_you_been_in_business,
			Photo_business_location,
			foto_usaha,
			Alamat,
			Kodepos,
			Kota,
			Provinsi,
			g.User_id
			');*/
		$this->db->from($this->user. ' u');
		$this->db->join($this->user_detail. ' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->where('u.Id_pengguna', $uid);
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_data_peminjam_agri_rows($uid)
	{
		$this->db->select('
			Nama_pengguna,
			Id_ktp,
			Tempat_lahir,
			Tanggal_lahir,
			Jenis_kelamin,
			Pekerjaan,
			Nomor_rekening,
			nama_bank,
			Mobileno,
			images_foto_name,
			images_ktp_name,
			foto_pegang_ktp,
			Pendidikan,
			g.Agama,
			ud.bidang_pekerjaan,
			status_nikah,
			How_many_people_do_you_financially_support,
			status_tempat_tinggal,
			g.Alamat,
			g.Kota,
			g.Provinsi,
			g.Kodepos				
			');
		/*$this->db->select('
			u.Id_pengguna as Id_pengguna_user,
			Tgl_record,
			Nama_pengguna,
			Jenis_pengguna,
			Id_ktp,
			Tempat_lahir,
			Tanggal_lahir,
			Jenis_kelamin,
			Pekerjaan,
			Nomor_rekening,
			id_mod_user_member,
			ud.Id_pengguna,
			user_type,
			Mobileno,
			Profile_photo,
			Photo_id,
			Occupation,
			ID_type,
			ID_No,
			What_is_the_name_of_your_business,
			How_many_years_have_you_been_in_business,
			Photo_business_location,
			foto_usaha,
			Alamat,
			Kodepos,
			Kota,
			Provinsi,
			g.User_id
			');*/
		$this->db->from($this->user. ' u');
		$this->db->join($this->user_detail. ' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->where('u.Id_pengguna', $uid);
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_data_pendana_rows($uid)
	{
		$this->db->select('
			Nama_pengguna,
			Id_ktp,
			Tempat_lahir,
			Tanggal_lahir,
			Jenis_kelamin,
			Pendidikan,
			Pekerjaan,
			Nomor_rekening,
			Mobileno,
			average_monthly_salary,
			How_many_people_do_you_financially_support,
			images_foto_name,
			images_ktp_name,
			status_nikah,
			Alamat,
			Kodepos,
			Kota,
			Provinsi
			');
		/*$this->db->select('
			u.Id_pengguna as Id_pengguna_user,
			Tgl_record,
			Nama_pengguna,
			Jenis_pengguna,
			Id_ktp,
			Tempat_lahir,
			Tanggal_lahir,
			Jenis_kelamin,
			Pendidikan,
			Pekerjaan,
			Nomor_rekening,
			id_mod_user_member,
			ud.Id_pengguna,
			user_type,
			Mobileno,
			Profile_photo,
			Photo_id,
			Highest_Education,
			average_monthly_salary,
			Occupation,
			ID_type,
			ID_No,
			How_many_people_do_you_financially_support,
			Alamat,
			Kodepos,
			Kota,
			Provinsi,
			g.User_id
			');*/
		$this->db->from($this->user. ' u');
		$this->db->join($this->user_detail. ' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->where('u.Id_pengguna', $uid);
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function screening_mikro_rows($uid)
	{
		$this->db->select('
			What_is_the_name_of_your_business,
			How_many_years_have_you_been_in_business,
			images_usaha_name,
			');
		$this->db->from($this->user. ' u');
		$this->db->join($this->user_detail. ' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->where('u.Id_pengguna', $uid);
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_harga_pinjaman_kilat()
	{
		$this->db->select('*');
		$this->db->from($this->mod_harga. ' h');
		$this->db->where('h_status', '1');
		$this->db->order_by('h_harga', 'asc');
		$sql = $this->db->get();
		return $sql->result_array();
	}

	function product_by_harga($hid)
	{
		$this->db->select('*');
		$this->db->from($this->mod_harga_produk. ' h');
		$this->db->join($this->product. ' p', 'p.Product_id=h.hp_product_id', 'left');
		$this->db->where('hp_harga_id', $hid);
		$sql = $this->db->get();
		return $sql->result_array();
	}

	function check_active_pinjaman($uid)
	{
		$this->db->select('Master_loan_id, Jml_permohonan_pinjaman, Master_loan_status, Id_pengguna, Nama_pengguna');
		$this->db->from($this->profil_permohonan_pinjaman. ' p');
		$this->db->join($this->user. ' u', 'u.Id_pengguna=p.User_id', 'left');
		$this->db->where('u.Id_pengguna', $uid);
		$this->db->where('Master_loan_status !=', 'lunas');
		$this->db->where('Master_loan_status !=', 'expired');
		$this->db->where('Master_loan_status !=', 'reject');
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_pendanaan_byloan($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->profile_pendanaan. ' pd');
		$this->db->join($this->mod_log_transaksi_pendana. ' log', 'log.Id_pendanaan=pd.Id', 'left');
		$this->db->join($this->mod_user_member. ' m', 'm.id_mod_user_member=pd.dana_member_id', 'left');
		$this->db->where('pd.Master_loan_id', $ordercode);
		$sql = $this->db->get();
		return $sql->result_array();
	}

	function get_nomor_angsuran($ordercode)
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->detail_pinjaman);
		$this->db->where('Master_loan_id ', $ordercode);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	// ============== Log Peminjam ==================

	function insert_log_transaksi_pinjam($data)
	{
		$this->db->insert($this->mod_log_transaksi_pinjaman, $data);
		return $this->db->insert_id();
	}

	function get_log_transaksi_pinjam($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->mod_log_transaksi_pinjaman. ' mltj');
		$this->db->join($this->mod_log_transaksi_pendana. ' mltp','mltp.Master_loan_id=mltj.ltp_Master_loan_id', 'LEFT');
		$this->db->join($this->tabel_pinjaman. ' tp', 'tp.Master_loan_id=mltj.ltp_Master_loan_id', 'LEFT');
		$this->db->where('ltp_Master_loan_id', $ordercode);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	//tambahan baru - pendana
	function get_log_transaksi_pinjam_pendana($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->detail_wallet. ' d');
		$this->db->join($this->mod_log_transaksi_pendana. ' mltp', 'mltp.Id_pendanaan=d.kode_transaksi', 'left');
		$this->db->join($this->mod_log_transaksi_pinjaman. ' mltj', 'mltj.ltp_Master_loan_id=mltp.Master_loan_id', 'left');
		//$this->db->join($this->tabel_pinjaman. ' tp', 'tp.Master_loan_id=mltp.Master_loan_id', 'left');
		//$this->db->from($this->mod_log_transaksi_pinjaman);
		$this->db->where('d.kode_transaksi', $ordercode);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}
	//batas tambahan baru - pendana

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

	function update_status_pendana($idpinjaman, $status)
	{
		$datetime = date('Y-m-d H:i:s');
		
		$this->db->set('pendanaan_status', $status);
		$this->db->set('received_date', $datetime);
		$this->db->where('Master_loan_id', $idpinjaman);
		$this->db->update($this->profile_pendanaan);
		return $this->db->affected_rows();
	}

	// ============ mod_tempo ==============
	
	function insert_table_tempo($data)
	{
		$this->db->insert($this->mod_tempo, $data);
		return $this->db->insert_id();
	}

	function update_table_tempo($code, $nomor_cicilan, $data)
	{
		$this->db->where('kode_transaksi', $code);
		$this->db->where('no_angsuran', $nomor_cicilan);
		$this->db->update($this->mod_tempo, $data);
		return $this->db->affected_rows();
	}

	function get_pinjaman_member($ordercode)
	{
		$this->db->select('Master_loan_id, Tgl_permohonan_pinjaman, Jml_permohonan_pinjaman, Jml_permohonan_pinjaman_disetujui, Amount,
			mum_email, 
			u.Id_pengguna, Nama_pengguna, Id_ktp, 
			Mobileno, What_is_the_name_of_your_business, 
			Alamat, Kota, Provinsi, Kodepos');
		$this->db->from($this->profil_permohonan_pinjaman. ' p');
		$this->db->join($this->user. ' u', 'u.Id_pengguna=p.User_id', 'left');
		$this->db->join($this->user_detail. ' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->join($this->mod_user_member. ' m', 'm.id_mod_user_member=u.id_mod_user_member', 'left');
		$this->db->where('Master_loan_id', $ordercode);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_list_pendana($ordercode)
	{
		$this->db->select('
			mum_email, 
			u.Id_pengguna, Nama_pengguna, Id_ktp, 
			Mobileno');
		$this->db->from($this->profile_pendanaan. ' p');
		$this->db->join($this->user. ' u', 'u.Id_pengguna=p.User_id', 'left');
		$this->db->join($this->user_detail. ' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->join($this->mod_user_member. ' m', 'm.id_mod_user_member=u.id_mod_user_member', 'left');
		$this->db->where('p.Master_loan_id', $ordercode);
		$sql = $this->db->get();
		$ret = $sql->result_array();
		$sql->free_result();
		return $ret;
	}

	function insert_profil_pinjaman5($data)
	{
		$this->db->insert('record_pinjaman', $data);
		//$this->db->insert($this->record_pinjaman, $data);
		return $this->db->insert_id();
	}

	//tambahan baru denda
		function get_my_denda($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->mod_log_transaksi_pinjaman. ' m');
		$this->db->join($this->product. ' prod', 'prod.Product_id=m.ltp_product_id', 'left');
		//$this->db->join($this->record_repayment. ' rec_rep', 'rec_rep.Master_loan_id=m.ltp_Master_loan_id', 'left');
		$this->db->where('ltp_Master_loan_id', $ordercode);
		//$this->db->order_by('record_repayment_id', 'asc');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;

		//$this->db->select('*');
		//$this->db->from($this->profil_permohonan_pinjaman. ' p');
		//$this->db->join($this->product. ' prod', 'prod.Product_id=p.Product_id', 'left');
		//$this->db->from($this->product. ' prod');
		//$this->db->join($this->profil_permohonan_pinjaman. ' p', 'p.Product_id=prod.Product_id', 'left');
		//$this->db->join($this->mod_type_business. ' tb', 'tb.id_mod_type_business=prod.type_of_business_id', 'left');
		//$this->db->where('tb.id_mod_type_business', '1');
		//$this->db->where('Product_id', $id);
		//$this->db->where('p.Master_loan_id', $id);
		//$this->db->where('prod.product_status', '1');
		//$sql = $this->db->get();
		//$ret = $sql->result_array();
		//$sql->free_result();

		//echo $this->db->last_query();
		//return $ret;
	}

	function get_jml_kredit($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->tabel_pinjaman);
		$this->db->where('Master_loan_id', $ordercode);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function get_record_repayment($ordercode)
	{
		$nowdate = date('Y-m-d');

		$this->db->select('*');
		$this->db->from($this->record_repayment);
		$this->db->where('Master_loan_id', $ordercode);
		//$this->db->where('status_cicilan','belum-bayar');
		//$this->db->where('tgl_jatuh_tempo', $tgl);
		//$this->db->where('tgl_jatuh_tempo <= now()', null);
		$this->db->order_by('tgl_jatuh_tempo', 'asc');
		//$this->db->order_by('record_repayment_id', 'asc');
		
		//$this->db->limit(1);
		//$this->db->order_by('tgl_jatuh_tempo', 'asc');
		$sql = $this->db->get();
		return $sql->result_array();

/*		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;*/
	}

	function get_record_repayment1($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->record_repayment);
		$this->db->where('Master_loan_id', $ordercode);
		//$this->db->order_by('record_repayment_id', 'desc');
		$this->db->order_by('tgl_jatuh_tempo', 'asc');
		$this->db->limit(1);
		//$this->db->order_by('tgl_jatuh_tempo', 'asc');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;

	}

	/*function get_nomor_angsuran1($ordercode)
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->record_repayment);
		$this->db->where('Master_loan_id ', $ordercode);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}
*/
/*	function get_record_repayment1($ordercode, $k)
	{
		$this->db->select('*');
		$this->db->from($this->record_repayment);
		$this->db->where('Master_loan_id', $ordercode);
		$this->db->where('notes_cicilan', $k);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}*/
		function get_record_repayment_tempo($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->record_repayment);
		$this->db->where('Master_loan_id', $ordercode);
		//$this->db->order_by('record_repayment_id', 'desc');
		$this->db->order_by('tgl_jatuh_tempo', 'asc');
		//$this->db->limit(1);
		//$this->db->order_by('tgl_jatuh_tempo', 'asc');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}


		function get_nomor_angsuran1($ordercode)
	{
		$this->db->select('count(*) as itotal');
		$this->db->from($this->record_repayment);
		$this->db->where('Master_loan_id ', $ordercode);
		$this->db->where('status_cicilan ', 'belum-bayar');
		$this->db->order_by('tgl_jatuh_tempo ', 'asc');

		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}
	/*	function get_record_repayment2($ordercode)
	{
		$this->db->select('*');
		$this->db->from($this->record_repayment);
		$this->db->where('Master_loan_id', $ordercode);
		$this->db->where('status_cicilan', 'belum-bayar');
		//$this->db->order_by('record_repayment_id', 'desc');
		$this->db->order_by('tgl_jatuh_tempo', 'asc');
		//$this->db->limit(1);
		//$this->db->order_by('tgl_jatuh_tempo', 'asc');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;

	}*/
	function get_record_repaymentdenda($ordercode)
	{
		$nowdate = date('Y-m-d');

		$this->db->select('*');
		$this->db->from($this->record_repayment);
		$this->db->where('Master_loan_id', $ordercode);
		$this->db->where('status_cicilan','belum-bayar');
		//$this->db->where('tgl_jatuh_tempo', $tgl);
		//$this->db->where('tgl_jatuh_tempo <= now()', null);
		$this->db->order_by('tgl_jatuh_tempo', 'asc');
		//$this->db->order_by('record_repayment_id', 'asc');
		
		$this->db->limit(1);
		//$this->db->order_by('tgl_jatuh_tempo', 'asc');
		$sql = $this->db->get();
		return $sql->result_array();

/*		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;*/
	}


	function update_record_repayment($id, $tempo)
	{
		$nowdatetime = date('Y-m-d H:i:s');

		$this->db->set('status_cicilan', 'lunas');
		$this->db->set('tgl_pembayaran', $nowdatetime);
		$this->db->where('Master_loan_id', $id);
		$this->db->where('tgl_jatuh_tempo', $tempo);
		$this->db->update($this->record_repayment);
		return $this->db->affected_rows();
	}

	function update_approval_agri($status,$id)
	{
		$this->db->set('Master_loan_status', $status);
		$this->db->where('Master_loan_id', $id);
		$this->db->update($this->tabel_pinjaman);
		return $this->db->affected_rows();	
	}

		function get_record_repayment_log($ordercode)
	{
		$nowdate = date('Y-m-d');

		$this->db->select('*');
		$this->db->from($this->record_repayment_log);
		$this->db->where('Master_loan_id', $ordercode);
		//$this->db->where('notes_cicilan', $cicilan);
		//$this->db->where('tgl_record_repayment_log', $ordercode);
		$this->db->order_by('tgl_record_repayment_log', 'asc');
		$sql = $this->db->get();
		return $sql->result_array();

/*		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;*/
	}
}