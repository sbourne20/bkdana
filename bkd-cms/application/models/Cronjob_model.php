<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('Datatables_model');

		// table
		$this->Master_model->get_tables($this);
	}

	function find_mikro_expired()
	{
		$today= date('Y-m-d').' 00:00:00';

		$sql = " SELECT * 
					FROM {$this->tabel_pinjaman} p 
					LEFT JOIN {$this->product} prod ON(prod.Product_id=p.Product_id)
					LEFT JOIN {$this->mod_log_transaksi_pinjaman} lp ON(lp.ltp_Master_loan_id=p.Master_loan_id)
					WHERE Master_loan_status = 'approve'  
					AND type_of_business_id = '3'
					AND ( date_fundraise != '0000-00-00 00:00:00' AND date_fundraise < '{$today}' )";

		$query 	= $this->db->query($sql);
		$data 	= $query->result_array();

		/*echo $this->db->last_query();*/
		return $data;
	}

	function find_kilat_expired()
	{
		$today= date('Y-m-d H:i:s');

		$sql = " SELECT * 
					FROM {$this->tabel_pinjaman} p 
					LEFT JOIN {$this->product} prod ON(prod.Product_id=p.Product_id)
					LEFT JOIN {$this->mod_log_transaksi_pinjaman} lp ON(lp.ltp_Master_loan_id=p.Master_loan_id)
					WHERE (Master_loan_status = 'approve' OR Master_loan_status = 'draft')
					AND type_of_business_id = '1'
					AND ( date_fundraise != '0000-00-00 00:00:00' AND date_fundraise < '{$today}' )
					GROUP BY Master_loan_id
					";

		$query 	= $this->db->query($sql);
		$data 	= $query->result_array();

		/*echo $this->db->last_query();*/
		return $data;
	}

	function get_pendana_bytransaksi($code)
	{
		$this->db->select('*');
		$this->db->from($this->tabel_pendana. ' p');
		$this->db->where('Master_loan_id', $code);
		$sql = $this->db->get();

		return $sql->result_array();
	}

	function check_ordercode_transaksi_pendanaan($code)
	{
		$this->db->select('Id');
		$this->db->from($this->tabel_pendana);
		$this->db->where('Id', $code);
		$this->db->limit('1');
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->free_result();
		return $ret;
	}

	function insert_profil_pembiayaan($data)
	{
		$this->db->insert($this->tabel_pendana, $data);
		return $this->db->insert_id();
	}

	function insert_detail_profil_pembiayaan($data)
	{
		$this->db->insert($this->tabel_detail_pendanaan, $data);
		return $this->db->insert_id();
	}

	function tambah_kredit_peminjam($code, $kredit)
	{
		$code   = $this->db->escape_str($code);
		$kredit = $this->db->escape_str($kredit);

		$datetime = date('Y-m-d H:i:s');

		$sql = "UPDATE {$this->tabel_pinjaman} 
				SET jml_kredit = jml_kredit + {$kredit} ,
				tgl_perubahan_kredit = '{$datetime}'
				WHERE Master_loan_id = '{$code}' ";
		$run = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	function kurangi_kredit_peminjam($code, $kredit)
	{
		$code   = $this->db->escape_str($code);
		$kredit = $this->db->escape_str($kredit);

		$datetime = date('Y-m-d H:i:s');

		$sql = "UPDATE {$this->tabel_pinjaman} 
				SET jml_kredit = jml_kredit - {$kredit} ,
				tgl_perubahan_kredit = '{$datetime}'
				WHERE Master_loan_id = '{$code}' ";
		$run = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	function set_pinjaman_expired($code)
	{

		$data = array(
			'Master_loan_status' => 'expired'
		);
		$this->db->where('Master_loan_id', $code);
		$this->db->update($this->tabel_pinjaman, $data);
		return $this->db->affected_rows();
	}

	function set_pendanaan_expired($code)
	{
		$data = array(
			'pendanaan_status' => 'expired'
		);
		$this->db->where('Id', $code);
		$this->db->update($this->tabel_pendana, $data);
		return $this->db->affected_rows();
	}

	function approve_pinjaman($code)
	{
		$data = array(
			'Master_loan_status'     => 'complete',
			'tgl_pinjaman_disetujui' => date('Y-m-d H:i:s')
		);
		$this->db->where('Master_loan_id', $code);
		$this->db->update($this->tabel_pinjaman, $data);
		return $this->db->affected_rows();
	}

	function approve_pendanaan($pinj_code)
	{
		$data = array(
			'pendanaan_status' => 'approve',
			'tgl_disetujui'    => date('Y-m-d H:i:s')
		);
		$this->db->where('Master_loan_id', $pinj_code);
		$this->db->update($this->tabel_pendana, $data);
		return $this->db->affected_rows();
	}

	function kembalikan_saldo($id_pengguna, $saldo)
	{
		$id_pengguna = $this->db->escape_str($id_pengguna);
		$saldo       = $this->db->escape_str($saldo);

		$sql = "UPDATE {$this->master_wallet} SET Amount = Amount+{$saldo} WHERE User_id='{$id_pengguna}'";

		$kueri = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	// ========== JATUH TEMPO ============

	function insert_table_tempo($data)
	{
		$this->db->insert($this->mod_tempo, $data);
		return $this->db->insert_id();
	}


	// Akan jatuh tempo
	function get_jatuh_tempo()
	{
		$today= date('Y-m-d H:i:s');
		$due_date = date('Y-m-d', strtotime('+1 days'));

		//echo $due_date;exit();

		$sql = " SELECT * 
					FROM {$this->mod_tempo} t 
					LEFT JOIN {$this->tabel_pinjaman} p ON(p.Master_loan_id=t.kode_transaksi)
					LEFT JOIN {$this->user_ojk} u ON(u.Id_pengguna=p.User_id)
					LEFT JOIN {$this->mod_user_member} m ON(m.id_mod_user_member=u.id_mod_user_member)
					LEFT JOIN {$this->mod_log_transaksi_pinjaman} lp ON(lp.ltp_Master_loan_id=p.Master_loan_id)
					WHERE (Master_loan_status = 'complete')
					AND date_close = '0000-00-00 00:00:00' 
					AND tgl_jatuh_tempo = '{$due_date}' AND is_paid ='0'
					";

		$query 	= $this->db->query($sql);
		$data 	= $query->result_array();

		/*echo $this->db->last_query();*/
		return $data;
	}

	// lewat jatuh tempo
	function get_pasca_jatuh_tempo()
	{
		$due_date = date('Y-m-d', strtotime('-1 days'));

		//echo $due_date;exit();

		$sql = " SELECT * 
					FROM {$this->mod_tempo} t 
					LEFT JOIN {$this->tabel_pinjaman} p ON(p.Master_loan_id=t.kode_transaksi)
					LEFT JOIN {$this->user_ojk} u ON(u.Id_pengguna=p.User_id)
					LEFT JOIN {$this->mod_user_member} m ON(m.id_mod_user_member=u.id_mod_user_member)
					LEFT JOIN {$this->mod_log_transaksi_pinjaman} lp ON(lp.ltp_Master_loan_id=p.Master_loan_id)
					WHERE (Master_loan_status = 'complete')
					AND date_close = '0000-00-00 00:00:00' 
					AND tgl_jatuh_tempo = '{$due_date}' AND is_paid ='0'
					";

		$query 	= $this->db->query($sql);
		$data 	= $query->result_array();

		/*echo $this->db->last_query();*/
		return $data;
	}

	function update_jatuh_tempo($data, $id)
	{
		$this->db->where('tempo_id', $id);
		$this->db->update($this->mod_tempo, $data);
		return $this->db->affected_rows();
	}

	function get_pinjaman_taklancar()
	{
		$sql = " 	SELECT Master_loan_id, tgl_jatuh_tempo, mum_email, Nama_pengguna 
					FROM profil_permohonan_pinjaman p 
					LEFT JOIN mod_tempo tp ON(tp.kode_transaksi=p.Master_loan_id)
					LEFT JOIN product prod ON(prod.Product_id=p.Product_id) 
					LEFT JOIN user u ON(u.Id_pengguna=p.User_id)
					LEFT JOIN mod_user_member m ON(m.id_mod_user_member=u.id_mod_user_member)
					WHERE Master_loan_status = 'complete'
					AND type_of_business_id !='2'
					AND is_paid = '0'
					AND telah_jatuh_tempo = '1'
					AND tgl_jatuh_tempo <= NOW() - INTERVAL 1 MONTH
					GROUP BY p.Master_loan_id
			    ";
		$query 	= $this->db->query($sql);
		$data 	= $query->result_array();

		$query->free_result();

		return $data;
	}

	
}