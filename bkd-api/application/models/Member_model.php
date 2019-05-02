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

		if(substr($u2, 0, 1) == '0'){
			$u2 = '+62' . substr($u, 1);
		}

		$this->db->select('id_mod_user_member, mum_fullname, mum_email, mum_telp, mum_password, mum_status, mum_type, mum_type_peminjam');
		$this->db->from($this->mod_user_member);
		$this->db->where('mum_telp', $u);
		$this->db->or_where('mum_email', $u);
		$this->db->or_where('mum_telp', $u2);
		//$this->db->where('mum_status', '1');
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_member_phone($id)
	{
		$this->db->select('*');
		$this->db->from($this->mod_user_member);
		$this->db->where('mum_telp', $id);
		$this->db->limit(1);
		$sql = $this->db->get();

		return $sql->row_array();
	}

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

	function data_member($id) 
	{
		$this->db->select('m.id_mod_user_member as member_id,
			mum_email as email,
			Nama_pengguna, 
			IFNULL(Id_ktp, "") as Nomor_nik, 
			IFNULL(Tempat_lahir, "") as Tempat_lahir, 
			IFNULL(Tanggal_lahir, "") as Tanggal_lahir, 
			IFNULL(Jenis_kelamin, "") as Jenis_kelamin, 
			IFNULL(Pendidikan, "") as Pendidikan, 
			IFNULL(Pekerjaan, "") as Pekerjaan, 
			Mobileno,
			IFNULL(Nomor_rekening, "") as Nomor_rekening, 
			IFNULL(company, "") as nama_perusahaan, 
			IFNULL(Business_phone_no, "") as no_telp_perusahaan,
			IFNULL(How_many_years_have_you_been_in_business, "") as lama_bekerja,
			IFNULL(What_is_the_name_of_your_business, "") as usaha,
			IFNULL(deskripsi_usaha, "") as deskripsi_usaha,
			IFNULL(omzet_usaha, "") as omzet_usaha,
			IFNULL(margin_usaha, "") as margin_usaha,
			IFNULL(biaya_operasional, "") as biaya_operasional_usaha,
			IFNULL(laba_usaha, "") as laba_usaha,
			IFNULL(How_many_years_have_you_been_in_business, "") as lama_usaha,
			IFNULL(status_karyawan, "") as status_karyawan,
			IFNULL(nama_atasan_langsung, "") as nama_atasan,
			IFNULL(telp_referensi_teman_1, "") as referensi_1,
			IFNULL(telp_referensi_teman_2, "") as referensi_2,
			IFNULL(referensi_teman_1, "") as referensi_nama_1,
			IFNULL(referensi_teman_2, "") as referensi_nama_2,
			IFNULL(average_monthly_salary, "") as gaji,
			IFNULL(nama_bank, "") as nama_bank,
			IFNULL(Alamat, "") as Alamat, 
			IFNULL(Kota, "") as Kota, 
			IFNULL(Provinsi, "") as Provinsi, 
			IFNULL(Kodepos, "") as Kodepos,
			peringkat_pengguna,
			peringkat_pengguna_persentase,
			skoring,
			IFNULL(images_foto_name, "") as foto_file,
			IFNULL(images_ktp_name, "") as nik_file,
			IFNULL(foto_surat_keterangan_bekerja, "") as foto_surat_ket_kerja,
			IFNULL(foto_slip_gaji, "") as foto_slip_gaji,
			IFNULL(foto_pegang_ktp, "") as foto_pegang_idcard,
			IFNULL(images_usaha_name, "") as foto_usaha_file,
			IFNULL(images_usaha_name2, "") as foto_usaha_file2,
			IFNULL(images_usaha_name3, "") as foto_usaha_file3,
			IFNULL(images_usaha_name4, "") as foto_usaha_file4,
			IFNULL(images_usaha_name5, "") as foto_usaha_file5,
			');
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
		$this->db->select("m.id_mod_user_member, mum_fullname, mum_email, mum_telp, mum_password, mum_status, mum_create_date, mum_type, mum_type_peminjam, mum_ktp, mum_nomor_rekening, mum_usaha,mum_lama_usaha, mum_nomor_rekening,
			u.Id_pengguna, Nama_pengguna, Id_ktp, Profile_photo, Nomor_rekening, nama_bank, peringkat_pengguna, peringkat_pengguna_persentase, Pendidikan, Pekerjaan, images_foto_name, Mobileno, Alamat, Kota, Provinsi, Kodepos, Id_ktp as nik, DATE_FORMAT(Tanggal_lahir, '%d-%m-%Y') as tgl_lahir, Jenis_kelamin");
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
		$this->db->select('m.id_mod_user_member, mum_fullname, mum_email, mum_telp, mum_password, mum_status, mum_create_date, mum_type, mum_type_peminjam, mum_ktp, mum_nomor_rekening, mum_usaha,mum_lama_usaha, mum_nomor_rekening,
			u.Id_pengguna, Nama_pengguna, Id_ktp, Nomor_rekening, peringkat_pengguna, peringkat_pengguna_persentase,
			images_foto_name, images_ktp_name, foto_surat_keterangan_bekerja, foto_slip_gaji, foto_pegang_ktp,
			images_usaha_name
			');
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

	function update_member_bank_byid($uid, $data)
	{
		$this->db->where('Id_pengguna', $uid);
		$this->db->update($this->user, $data);
		return $this->db->affected_rows();
	}

	function update_member_byemail($uid, $data)
	{
		$this->db->where('mum_email', $uid);
		$this->db->update($this->mod_user_member, $data);
		return $this->db->affected_rows();
	}

	function check_password_member($oldpass, $uid)
	{
		$this->db->select('mum_password');
		$this->db->from($this->mod_user_member);
		$this->db->where('id_mod_user_member', $uid);
		$sql = $this->db->get();
		return $sql->row_array();
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

	function check_notelp($telp)
	{
		$this->db->select('Mobileno');
		$this->db->from('user_detail');
		$this->db->where('Mobileno', $telp);
		$this->db->limit('1');
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function get_fcmtoken($uid)
	{
		$this->db->select('fcm_token');
		$this->db->from($this->mod_user_member);
		$this->db->where('fcm_token', $uid);
		$this->db->or_where('id_mod_user_member', $uid);
		$this->db->limit(1);
		$sql = $this->db->get();
		return $sql->row_array();
	}

	function update_memberNotification($uid, $data)
	{
		$this->db->set('fcm_token', $data);
		$this->db->where('id_mod_user_member', $uid);
		$this->db->or_where('mum_email', $uid);
		$this->db->or_where('mum_telp', $uid);
		$this->db->update($this->mod_user_member);
		return $this->db->affected_rows();
	}

}