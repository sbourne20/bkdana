<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekening_koran extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();
		$this->load->model('Member_model');
		$this->load->model('Wallet_model');
		//error_reporting(E_ALL);

		$this->Content_model->has_login();
		$this->Content_model->is_active_pendana();
	}
	
	public function index()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;

		$data['tipe_pengguna'] = $this->Member_model->user_alldata($uid);

		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);
		
		$data['rk'] = $this->Wallet_model->all_wallet_detail($uid);
		//_d($data['rk']);

		$data['pages']    = 'v_rekening_koran';
		$this->load->view('template', $data);
	}

	public function detail()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;

		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);
		
		$ID = antiInjection($this->input->get('tid', TRUE)); // transaksi id
		
		$data['rk'] = $this->Wallet_model->all_wallet_detail($uid);


		//$rekkor1 = $this->Content_model->get_transaksi_pinjam_byid($ID);
		$rekkor  = $this->Wallet_model->all_wallet_detail2($ID);
		$data['rekkor']        = $rekkor;

/*		if ($rekeningkoran['tipe_dana'] == '1')
		{
			//echo ' Pinjaman Kilat';
			
			//$data['jml_cicilan'] = $log_transaksi_pinjam['ltp_jml_angsuran'];
			$data['pages']    = 'v_detail_rekening_koran';			
			//$data['pages']    = 'v_transaksi_detail_kilat';

			//if ( $transaksi['Master_loan_status'] == 'complete' || $transaksi['Master_loan_status'] == 'lunas') {
			//	$data['jatuh_tempo'] = date('d/m/Y', strtotime($log_transaksi_pinjam['ltp_tgl_jatuh_tempo']));
			//}
		}else{
			echo 'Pinjaman Mikro';
			
			//$data['jml_cicilan']   = $log_transaksi_pinjam['ltp_jml_angsuran'];
			//$data['lama_angsuran'] = $log_transaksi_pinjam['ltp_lama_angsuran']; // berapa minggu

			//$data['pages']         = 'v_transaksi_detail_mikro';

			//if ($transaksi['Master_loan_status'] == 'complete' || $transaksi['Master_loan_status'] == 'lunas') {
			//	$data['jatuh_tempo'] = date('d/m/Y', strtotime("+3 months", strtotime($transaksi['tgl_pinjaman_disetujui'])));
			//}
		}*/
		

		$data['pages']    = 'v_detail_rekening_koran';
		$this->load->view('template', $data);
	
	}

		function detail_kredit_kilat()
	{
		// ====== Detail Pinjaman ========

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		$ID = antiInjection($this->input->get('tid', TRUE));
		$ID2 = antiInjection($this->input->get('tid2', TRUE));

		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$data['transaksi']	= $this->Content_model->get_transaksi_pinjam_byid($ID);

		$data['walletkoran'] = $this->Wallet_model->all_wallet_detail_kredit_kilat($ID, $ID2);
		
		//$data['rk'] = $this->Wallet_model->all_wallet_detail($uid);
		//_d($data['rk']);

		$data['pages']    = 'v_detail_rekening_koran';
		$this->load->view('template', $data);
	}

	//tambahan baru detail kilat
	function detail_debet_kilat()
	{
		// ====== Detail Pinjaman ========

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		$ID = antiInjection($this->input->get('tid', TRUE));
		$ID2 = antiInjection($this->input->get('tid2', TRUE));

		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$data['transaksi']	= $this->Content_model->get_transaksi_pinjam_byid($ID);

		$data['walletkoran'] = $this->Wallet_model->all_wallet_detail_debet_kilat($ID, $ID2);
		$data['repayment']   = $this->Content_model->get_log_transaksi_pinjam($ID);
		
		//$data['rk'] = $this->Wallet_model->all_wallet_detail($uid);
		//_d($data['rk']);

		$data['pages']    = 'v_detail_rekening_koran_debet';
		$this->load->view('template', $data);
	}

	function detail_kredit_mikro()
	{
		// ====== Detail Pinjaman ========

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		$ID = antiInjection($this->input->get('tid', TRUE));
		$ID2 = antiInjection($this->input->get('tid2', TRUE));

		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$data['transaksi']	= $this->Content_model->get_transaksi_pinjam_byid($ID);

		$data['walletkoran'] = $this->Wallet_model->all_wallet_detail_kredit_mikro($ID, $ID2);
		
		//$data['rk'] = $this->Wallet_model->all_wallet_detail($uid);
		//_d($data['rk']);

		$data['pages']    = 'v_detail_rekening_koran';
		$this->load->view('template', $data);
	}

function detail_debet_mikro()
	{
		// ====== Detail Pinjaman ========

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		$ID = antiInjection($this->input->get('tid', TRUE));
		$ID2 = antiInjection($this->input->get('tid2', TRUE));

		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$data['transaksi']	= $this->Content_model->get_transaksi_pinjam_byid($ID);

		$data['walletkoran'] = $this->Wallet_model->all_wallet_detail_debet_mikro($ID, $ID2);
		$data['repayment']   = $this->Content_model->get_log_transaksi_pinjam($ID);
		
		//$data['rk'] = $this->Wallet_model->all_wallet_detail($uid);
		//_d($data['rk']);

		$data['pages']    = 'v_detail_rekening_koran_debet';
		$this->load->view('template', $data);
	}

	//tambahan baru pendana
	function detail_kredit_pendana()
	{
		// ====== Detail Pinjaman ========

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		$ID = antiInjection($this->input->get('tid', TRUE));
		$ID2 = antiInjection($this->input->get('tid2', TRUE));

		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$data['transaksi']	= $this->Content_model->get_transaksi_pinjam_byid($ID);

		$data['walletkoran'] = $this->Wallet_model->all_wallet_detail_kredit_pendana($ID, $ID2);
		$data['repayment']   = $this->Content_model->get_log_transaksi_pinjam($ID);
		
		//$data['rk'] = $this->Wallet_model->all_wallet_detail($uid);
		//_d($data['rk']);

		$data['pages']    = 'v_detail_rekening_koran_kredit_pendana';
		$this->load->view('template', $data);
	}
	
//tambahan baru
	function detail_debet_pendana()
	{
		// ====== Detail Pinjaman ========

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		$ID = antiInjection($this->input->get('tid', TRUE));
		$ID2 = antiInjection($this->input->get('tid2', TRUE));

		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$data['transaksi']	= $this->Content_model->get_transaksi_pinjam_byid($ID);

		$data['walletkoran'] = $this->Wallet_model->all_wallet_detail_debet_pendana($ID, $ID2);
		$data['repayment']   = $this->Content_model->get_log_transaksi_pinjam_pendana($ID);
		
		//$data['rk'] = $this->Wallet_model->all_wallet_detail($uid);
		//_d($data['rk']);

		$data['pages']    = 'v_detail_rekening_koran_debet_pendana';
		$this->load->view('template', $data);
	}

	//Tambahan Profil - 3 desember
	function detail_profil_peminjam(){

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/validation-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$data['nama'] = htmlentities($_SESSION['_bkdname_']);
		$uid          = htmlentities($_SESSION['_bkduser_']);
		$logintype    = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$username = antiInjection($this->input->get('uname', TRUE));
		$username2 = antiInjection($this->input->get('uname2', TRUE));

		$data['data_profil'] = $this->Member_model->get_user_alldata($username, $username2);
		
		//$data['rk'] = $this->Wallet_model->all_wallet_detail($uid);
		//_d($data['rk']);

		$data['pages']    = 'v_detail_profil_peminjam';
		$this->load->view('template', $data);	
	}
//Batas Tambahan - 3 desember	
	
	
}