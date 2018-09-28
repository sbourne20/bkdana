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

		if($login_status !=1 OR empty($uid) OR trim($uid)=='' )
		{
			redirect('login');
			die();
		}
	}

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

	function check_existing_member($email, $telp, $ktp)
	{
		$this->db->select('m.id_mod_user_member, mum_fullname, mum_email, mum_password, Id_ktp, Mobileno, ID_No');
		$this->db->from('mod_user_member m');
		$this->db->join('user u', 'u.id_mod_user_member=m.id_mod_user_member', 'left');
		$this->db->join('user_detail ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->where('mum_email', $email);
		$this->db->or_where('Mobileno', $telp);
		$this->db->or_where('ID_No', $ktp);
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

	function get_my_transactions_pinjam($uid, $limit, $start)
	{
		$sql = "SELECT 
			Master_loan_id as transaksi_id, 
			tgl_permohonan_pinjaman as tgl_transaksi, 
			Jml_permohonan_pinjaman as totalrp, 
			Jml_permohonan_pinjaman_disetujui as total_approve, 
			Master_loan_status as transaksi_status, 
			date_close, 
			tgl_pinjaman_disetujui as tgl_approve,
			product_title, Loan_term, id_mod_type_business, type_business_name
			FROM {$this->profil_permohonan_pinjaman} p
			LEFT JOIN {$this->product} prod ON(prod.Product_id=p.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			WHERE (tb.id_mod_type_business='1' OR tb.id_mod_type_business='3')
			AND p.pinjam_member_id='{$uid}'
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
			Jml_permohonan_pinjaman_disetujui as total_approve, 
			Master_loan_status as transaksi_status, 
			date_close, 
			tgl_pinjaman_disetujui as tgl_approve,
			product_title, Loan_term, id_mod_type_business, type_business_name
			FROM {$this->profil_permohonan_pinjaman} p
			LEFT JOIN {$this->product} prod ON(prod.Product_id=p.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			WHERE (tb.id_mod_type_business='1' OR tb.id_mod_type_business='3')
			AND p.pinjam_member_id='{$uid}'
			AND p.Master_loan_status = 'approve'
			ORDER BY tgl_permohonan_pinjaman DESC
			LIMIT {$start}, {$limit}
		";

		$queries = $this->db->query($sql);
		$ret = $queries->result_array();
		$queries->free_result();
		return $ret;
	}

	function get_my_transactions_pendana($uid, $limit, $start)
	{
		$sql = "SELECT Id as transaksi_id, 
			Tgl_penawaran_pemberian_pinjaman as tgl_transaksi, 
			Jml_penawaran_pemberian_pinjaman as totalrp, 
			Jml_penawaran_pemberian_pinjaman_disetujui as total_approve, 
			tgl_disetujui as tgl_approve,
			pendanaan_status as transaksi_status,
			product_title, Loan_term, id_mod_type_business, type_business_name
			FROM {$this->profile_pendanaan} pd 
			LEFT JOIN {$this->product} prod ON(prod.Product_id=pd.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			WHERE pd.dana_member_id={$uid}
			ORDER BY Tgl_penawaran_pemberian_pinjaman DESC
			LIMIT {$start}, {$limit}
		";

		$queries = $this->db->query($sql);
		$ret = $queries->result_array();
		$queries->free_result();
		return $ret;
	}

	/*function get_my_transactions_pendana($uid, $limit, $start)
	{
		$sql = "SELECT Id as transaksi_id, 
			Tgl_penawaran_pemberian_pinjaman as tgl_transaksi, 
			Jml_penawaran_pemberian_pinjaman as totalrp, 
			Jml_penawaran_pemberian_pinjaman_disetujui as total_approve, 
			tgl_disetujui as tgl_approve,
			pendanaan_status as transaksi_status, 
			product_title, Loan_term, id_mod_type_business, type_business_name
			FROM {$this->profile_pendanaan} pd
			LEFT JOIN {$this->product} prod ON(prod.Product_id=pd.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			WHERE tb.id_mod_type_business=2
			AND pd.dana_member_id={$uid}
			ORDER BY Tgl_penawaran_pemberian_pinjaman DESC
			LIMIT {$start}, {$limit}
		";

		$queries = $this->db->query($sql);
		$ret = $queries->result_array();
		$queries->free_result();
		return $ret;
	}*/

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
			WHERE (tb.id_mod_type_business='1' OR tb.id_mod_type_business='3' OR tb.id_mod_type_business='4')
			AND (p.Master_loan_status='approve' OR p.Master_loan_status='pending')
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

	/*function get_my_transactions($uid)
	{
		$sql = "SELECT Master_loan_id as transaksi_id, tgl_permohonan_pinjaman as tgl_transaksi, Jml_permohonan_pinjaman as totalrp, Jml_permohonan_pinjaman_disetujui as total_approve, Master_loan_status as transaksi_status,
			product_title, Loan_term, id_mod_type_business, type_business_name
			FROM {$this->profil_permohonan_pinjaman} p
			LEFT JOIN {$this->product} prod ON(prod.Product_id=p.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			WHERE tb.id_mod_type_business=1
			AND p.pinjam_member_id={$uid}
			UNION 
			SELECT Id as transaksi_id, Tgl_penawaran_pemberian_pinjaman as tgl_transaksi, Jml_penawaran_pemberian_pinjaman as totalrp, Jml_penawaran_pemberian_pinjaman_disetujui as total_approve, NULL,
			product_title, Loan_term, id_mod_type_business, type_business_name
			FROM {$this->profile_pendanaan} pd
			LEFT JOIN {$this->product} prod ON(prod.Product_id=pd.Product_id)
			LEFT JOIN {$this->mod_type_business} tb ON(tb.id_mod_type_business=prod.type_of_business_id)
			WHERE tb.id_mod_type_business=2
			AND pd.dana_member_id={$uid}
		";

		$queries = $this->db->query($sql);
		$ret = $queries->result_array();
		$queries->free_result();
		return $ret;
	}*/

	function get_transaksi_pinjam_byid($id)
	{
		$this->db->select('u.Id_pengguna, Nama_pengguna, peringkat_pengguna, u.id_mod_user_member,
			What_is_the_name_of_your_business, How_many_years_have_you_been_in_business, Kota, 
			Master_loan_id, Master_loan_id as transaksi_id, Loan_term, Jml_permohonan_pinjaman, Informasi_kredit, Jml_permohonan_pinjaman_disetujui, tgl_pinjaman_disetujui, Master_loan_status, date_fundraise, date_close, p.fundraising_period, Total_loan_repayment, Total_loan_outstanding, jml_kredit,
			type_business_name, type_of_business_id,
			(select count(*) as itotal from '.$this->profile_pendanaan.' where Master_loan_id=transaksi_id) as total_lender ');
		$this->db->from($this->profil_permohonan_pinjaman. ' p');
		$this->db->join($this->product. ' prod', 'prod.Product_id=p.Product_id', 'left');
		$this->db->join($this->mod_type_business. ' tb', 'tb.id_mod_type_business=prod.type_of_business_id', 'left');
		$this->db->join($this->user. ' u', 'u.Id_pengguna=p.User_id', 'left');
		$this->db->join($this->user_detail. ' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi. ' g', 'g.User_id=u.Id_pengguna', 'left');
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

		$this->db->set('Master_loan_status', 'approve');
		$this->db->set('tgl_pinjaman_disetujui', $datetime);
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

	// ======== RANKING ============
	function get_data_peminjam_kilat_rows($uid)
	{
		$this->db->select('
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
			Alamat,
			Kodepos,
			Kota,
			Provinsi,
			g.User_id
			');
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
			');
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

}