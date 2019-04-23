<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Master_model');
		$this->Master_model->get_tables($this);
	}

	function set_cookies($email)
	{
		$this->remove_cookies();

		$getdata = $this->get_member_by($email);

		if (is_array($getdata) && $getdata['id_mod_user_member'] && $getdata['mum_fullname']) {
			$data = array();
			$data['_bkdlog_']   = 1;	// login status
			$data['_bkdmail_']  = $getdata['mum_email'];
			$data['_bkduser_']  = $getdata['id_mod_user_member'];
			$data['_bkdname_']  = $getdata['mum_fullname'];
			$data['_bkdtype_']  = $getdata['mum_type'];
			$this->session->set_userdata($data);

			return TRUE;
		}else{
			return FALSE;
		}
	}

	function set_cookies_otp($email)
	{
		$this->remove_cookies();

		if ($email) {
			$data = array();
			$data['_bkd_otp_'] = $email;
			$this->session->set_userdata($data);
			return TRUE;
		}else{
			return FALSE;
		}
	}

	function check_existing_member($email)
	{
		$this->db->select('id_mod_user_member, mum_fullname, mum_email, mum_password, mum_type');
		$this->db->from($this->mod_user_member);
		$this->db->where('mum_email', $email);		
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function do_login_byemail($u)
	{
		$u = $this->db->escape_str($u);
		$u2 = $u;
		if(substr($u2, 0, 1)=='0'){
			$u2 = '+62'.substr($u2, 1);
		}
		$this->db->select('id_mod_user_member, mum_fullname, mum_email, mum_telp, mum_password, mum_status, mum_type');
		$this->db->from($this->mod_user_member);
		$this->db->where('mum_email' , $u);
		$this->db->or_where('mum_telp' , $u);
		$this->db->or_where('mum_telp' , $u2);
		//$this->db->where('mum_status', '1');
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_file_name($id)
	{
		$this->db->select("CONCAT(images_foto_name, '|',
			images_ktp_name, '|',
			foto_pegang_ktp, '|',
			foto_slip_gaji, '|',
			foto_surat_keterangan_bekerja, '|',
			images_usaha_name, '|',
			images_usaha_name2, '|',
			images_usaha_name3, '|',
			images_usaha_name4, '|',
			images_usaha_name5) as userfile", FALSE);
		$this->db->from($this->user_detail);
		$this->db->where('Id_pengguna', $id);
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->result_array();
		return $ret;
	}

/*	function get_member_phone($id)
	{
		$this->db->select('*');
		$this->db->from($this->mod_user_member);
		$this->db->where('mum_telp', $id);
		$this->db->limit(1);
		$sql = $this->db->get();

		return $sql->row_array();
	}
*/
	function user_alldata($id) 
	{
		$this->db->select('*');
		$this->db->from($this->mod_user_member.' m');
		$this->db->join($this->user.' u', 'u.id_mod_user_member=m.id_mod_user_member', 'left');
		$this->db->join($this->user_detail.' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi.' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->where('m.id_mod_user_member', $id);
		$this->db->limit(1);
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_member_byid($id)
	{
		$this->db->select('m.id_mod_user_member, mum_fullname, mum_email, mum_telp, mum_password, mum_status, mum_create_date, mum_type, mum_type_peminjam, mum_ktp, mum_nomor_rekening, mum_nomor_rekening,
			u.Id_pengguna, Nama_pengguna, Id_ktp, Profile_photo, Nomor_rekening, nama_bank, npwp, deskripsi_usaha, omzet_usaha, modal_usaha, margin_usaha, biaya_operasional, laba_usaha, peringkat_pengguna, peringkat_pengguna_persentase, images_foto_name, Mobileno, Alamat, Kota, Provinsi, Kodepos');
		$this->db->from($this->mod_user_member.' m');
		$this->db->join($this->user.' u', 'u.id_mod_user_member=m.id_mod_user_member', 'left');
		$this->db->join($this->user_detail.' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->profile_geografi.' g', 'g.User_id=u.Id_pengguna', 'left');
		$this->db->where('m.id_mod_user_member', $id);
		$this->db->limit(1);
		$sql = $this->db->get();

		return $sql->row_array();
	}

	function get_member_byid_less($id)
	{
		// tanpa foto//
		$this->db->select('m.id_mod_user_member, mum_fullname, mum_email, mum_telp, mum_password, mum_status, mum_create_date, mum_type, mum_type_peminjam, mum_ktp, mum_nomor_rekening, mum_nomor_rekening,
			u.Id_pengguna, Nama_pengguna, Id_ktp, Nomor_rekening, peringkat_pengguna, peringkat_pengguna_persentase');
		$this->db->from($this->mod_user_member.' m');
		$this->db->join($this->user.' u', 'u.id_mod_user_member=m.id_mod_user_member', 'left');
		$this->db->join($this->user_detail.' ud', 'ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->where('m.id_mod_user_member', $id);
		$this->db->limit(1);
		$sql = $this->db->get();

		return $sql->row_array();
	}

	function get_member_by($email)
	{
		$this->db->select('id_mod_user_member, mum_fullname, mum_email, mum_telp, mum_status, mum_type');
		$this->db->from($this->mod_user_member);
		$this->db->where('mum_email', $email);
		$this->db->or_where('mum_telp', '+'.$email);		
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function update_member_byid($uid, $data)
	{
		$this->db->where('id_mod_user_member', $uid);
		$this->db->update($this->mod_user_member, $data);
		return $this->db->affected_rows();
	}

	function update_password_member($newpass, $uid)
	{
		$this->db->set('mum_password', $newpass);
		$this->db->where('id_mod_user_member', $uid);
		$this->db->update($this->mod_user_member);
		return $this->db->affected_rows();
	}

	function verify_member_byid($uid)
	{
		$this->db->set('mum_verifikasi_email ', 1);
		$this->db->where('id_mod_user_member', $uid);
		$this->db->update($this->mod_user_member);
		return $this->db->affected_rows();
	}

	function activate_member_byid($uid, $status)
	{
		$this->db->set('mum_status', $status);
		$this->db->set('mum_last_login', date('Y-m-d H:i:s'));
		$this->db->where('id_mod_user_member', $uid);
		$this->db->update($this->mod_user_member);
		return $this->db->affected_rows();
	}

	function remove_cookies()
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
	}

	


	// ======= table mod_member_resetcode ========

	function insert_resetcode($data)
	{
		$this->db->insert($this->mod_member_resetcode, $data);
		return $this->db->insert_id();
	}

	function check_resetcode($resetcode, $uid)
	{
		$this->db->select('reset_id, reset_member_id, reset_code, reset_status');
		$this->db->from($this->mod_member_resetcode);
		$this->db->where('reset_member_id', $uid);
		$this->db->where('reset_code', $resetcode);
		$this->db->where('reset_status', '0');
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function update_statusreset($resetcode, $uid)
	{
		$this->db->set('reset_status', '1');
		$this->db->where('reset_code', $resetcode);
		$this->db->where('reset_member_id', $uid);
		$this->db->update($this->mod_member_resetcode);
		return $this->db->affected_rows();
	}

	//3desember
	function get_user_alldata($id, $id2) 
	{
		$this->db->select('*');
		// $this->db->from($this->detail_wallet. ' d');
		// $this->db->join($this->mod_log_transaksi_pendana. ' ltp','ltp.Id_pendanaan=d.kode_transaksi', 'left');
		// $this->db->join($this->tabel_pinjaman. ' tp','tp.Master_loan_id=ltp.Master_loan_id', 'left');
		$this->db->from($this->user. ' u');
		$this->db->join($this->user_detail. ' ud','ud.Id_pengguna=u.Id_pengguna', 'left');
		$this->db->join($this->mod_user_member. ' mum','mum.id_mod_user_member=u.Id_pengguna','left');
		$this->db->join($this->profile_geografi. ' pg','pg.User_id=u.Id_pengguna', 'left');
		// // $this->db->join($this->master_wallet. ' m', 'd.Id=m.Id', 'left');
		$this->db->where('u.Nama_pengguna', $id);
		$this->db->where('u.Id_pengguna', $id2);
		$this->db->limit('1');
		// $this->db->where('tipe_dana', 2);
		// $this->db->where('Date_transaction',$uid);
		// $this->db->order_by('Detail_wallet_id', 'desc');
		// $sql = $this->db->get();
		// return $sql->result_array();
		$sql = $this->db->get();
		$ret = $sql->row_array();
		$sql->result_array();
		return $ret;
	}
//3desember

}