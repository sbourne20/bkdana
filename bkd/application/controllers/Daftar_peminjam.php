<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_peminjam extends CI_Controller {

	/* 	1. Display List Peminjam 
		2. Submit Pendanaan
	*/

	public function __construct()
	{
		parent::  __construct();
		$this->load->model('Member_model');
		$this->load->model('Wallet_model');
		//$this->load->model('Pinjaman_model');

		$this->load->library('pagination');

		$this->Content_model->has_login();
		$this->Content_model->is_active_pendana();

		include(APPPATH.'libraries/phpmailer-5.2.23/PHPMailerAutoload.php');
		require_once(APPPATH.'libraries/TCPDF/tcpdf.php');

		ini_set('max_execution_time', 600);
		
		// error_reporting(E_ALL & ~E_NOTICE);
		// ini_set('display_errors', '1');
	}
	
	public function index()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com, daftar peminjam', 'daftar peminjam');

		$uid       = htmlentities($_SESSION['_bkduser_']);
		$logintype = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana

		$limit_per_page = 10;
        $page           = (int)antiInjection($this->uri->segment(3));

        if (empty($page)) {
	        $start_index    = 0;
	    }else{
	        $start_index    = ($page*$limit_per_page)-$limit_per_page;
	    }

		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);
	
		$data['list_transaksi'] = $this->Content_model->all_list_transactions_pinjaman($limit_per_page, $start_index);
		$total_records          = $this->Content_model->total_all_pinjaman();
		$data['total_peminjam'] = $total_records['itotal'];
		$data['pages']          = 'v_daftar_peminjam';

		//_d($data['list_transaksi']);
 
        if (is_array($total_records) && $total_records['itotal'] > 0) 
        {             
            $config['base_url']    = base_url() . 'daftar-peminjam/page';
            $config['total_rows']  = $total_records['itotal'];
            $config['per_page']    = $limit_per_page;
            $config["uri_segment"] = 3;
            // custom paging configuration
            $config['num_links']   = 2;
            $config['use_page_numbers']   = TRUE;
            $config['reuse_query_string'] = TRUE;
             
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
             
            $config['first_link'] = 'First';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
             
            $config['last_link'] = 'Last';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
             
            $config['next_link'] = '&raquo;';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
 
            $config['prev_link'] = '&laquo;';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
 
            $config['cur_tag_open'] = '<li><a style="background-color:#f0f8ff;">';
            $config['cur_tag_close'] = '</a></li>';
 
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
             
            $this->pagination->initialize($config);
             
            // build paging links
            $data["pagination"] = $this->pagination->create_links();
        }else{
        	$data["pagination"] = '';
        }
		
		$this->load->view('template', $data);
	}

	function detail()
	{
		// Detail Pinjaman

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/alertify/css/alertify.min.css');
		$data['top_css'] .= add_css('js/alertify/css/themes/default.min.css');

		$data['bottom_js'] .= add_js('js/jquery-loading-overlay/dist/loadingoverlay.min.js');
		$data['bottom_js'] .= add_js('js/alertify/alertify.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/transaction.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'daftar peminjam');

		$uid = htmlentities($_SESSION['_bkduser_']);
		$logintype = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		$data['memberdata']     = $this->Member_model->get_member_byid($uid);		
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$ID = antiInjection($this->input->get('tid', TRUE)); // transaksi id
		
		$transaksi         = $this->Content_model->get_transaksi_pinjam_byid($ID);
		$log_tran_pinjam   = $this->Content_model->get_log_transaksi_pinjam($ID);
		$data['transaksi'] = $transaksi;

		$data['total_bayar']        = $transaksi['Jml_permohonan_pinjaman'];
		$data['pinjaman_disetujui'] = $transaksi['Jml_permohonan_pinjaman_disetujui'];
		$data['Product_id'] = $transaksi['Product_id'];
		$data['Amount'] =$transaksi['Amount'];

		$get_total_pendana = $this->Content_model->get_kuota_pinjaman($transaksi['Master_loan_id']);
		$total_dana_masuk  = $get_total_pendana['jml_pendanaan'];
		$total_pinjaman    = $transaksi['Jml_permohonan_pinjaman'];
		$total_kredit      = $transaksi['jml_kredit'];

		//_d($transaksi);

		/*if ($transaksi['type_of_business_id'] == '1'){
		}else if ($transaksi['type_of_business_id'] == '3') {
		}else{
		}*/

		$data['kuota_dana'] = round(($total_kredit/$data['Amount']) * 100);

		$data['pages']    = 'v_daftar_peminjam_detail';
		$this->load->view('template', $data);
	}

	function submit_pendanaan()
	{
		$post = $this->input->post(NULL, TRUE);
		$pdf_folder = $this->config->item('attach_dir');

		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$uid = htmlentities($_SESSION['_bkduser_']);
			$memdata = $this->Member_model->get_member_byid($uid);

			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');

			$filter                = explode('.', trim($post['jml_pendanaan']));
			$jmldana               = str_replace(',', '', $filter[0]);
			$jmlpinjaman           = antiInjection(trim($post['jml_pinjaman']));
			$jmlpinjaman_disetujui = antiInjection(trim($post['jml_pinj_disetujui']));

			$jml_kredit_pinjaman = antiInjection(trim($post['jml_kredit']));
			$jml_kurang_pinjaman = antiInjection(trim($post['jml_kekurangan']));

			$ID_peminjam  = antiInjection(trim($post['id_peminjam']));
			$mid_peminjam = antiInjection(trim($post['id_peminjam_member']));
			$Product_id = antiInjection(trim($post['Product_id']));

			$get_master_wallet = $this->Wallet_model->get_wallet_bymember($uid);

			if ( empty($uid) OR empty($ID_peminjam) OR empty($mid_peminjam))
			{
				$this->session->set_userdata('message','Proses Pembiayaan gagal. Error data peminjam.');
				$this->session->set_userdata('message_type','error');
				redirect('daftar-peminjam-detail/?tid='. $post['transaksi_id']);
				exit();
			}

			if ($get_master_wallet['Amount'] < $jmldana)
			{
				$this->session->set_userdata('message','Proses Pembiayaan gagal. Total pembiayaan tidak boleh melebihi total Saldo Anda!');
				$this->session->set_userdata('message_type','error');
				redirect('daftar-peminjam-detail/?tid='. $post['transaksi_id']);
				exit();
			}else if ($jml_kredit_pinjaman != 0 && $jmldana > $jml_kurang_pinjaman) 
			{
				$this->session->set_userdata('message','Proses Pembiayaan gagal. Total pembiayaan tidak boleh melebihi total tagihan!');
				$this->session->set_userdata('message_type','error');
				redirect('daftar-peminjam-detail/?tid='. $post['transaksi_id']);
				exit();
			}else if ($jmldana < 100000) {
				$this->session->set_userdata('message','Proses Pembiayaan gagal. Jumlah Pembiayaan minimal Rp 100,000.');
				$this->session->set_userdata('message_type','error');
				redirect('daftar-peminjam-detail/?tid='. $post['transaksi_id']);
				exit();
			
			}else if ($jmldana > $jmlpinjaman) {
				$this->session->set_userdata('message','Proses Pembiayaan gagal. Jumlah Pembiayaan tidak boleh melebihi jumlah tagihan.');
				$this->session->set_userdata('message_type','error');
				redirect('daftar-peminjam-detail/?tid='. $post['transaksi_id']);

			}else if ($jmldana != '' && strlen($jmldana) > 5 && $jmldana >= 100000 && trim($post['transaksi_id'])!='' ) {
				
				$jmldana              = antiInjection($jmldana);
				$pinjaman_transaksiID = antiInjection(trim($post['transaksi_id']));
				$log_tran_pinjam      = $this->Content_model->get_log_transaksi_pinjam($pinjaman_transaksiID);

				//_d($log_tran_pinjam);

				// ------------ hitung admin fee, bunga ----------------
				$hitung_tax = 0; 

				if ($log_tran_pinjam['ltp_type_of_business_id'] == '1')
				{
					// KILAT
					$laba                = (($jmldana * $log_tran_pinjam['ltp_product_investor_return'])/100) * $log_tran_pinjam['ltp_product_loan_term'];
					$angsuran_ke_pendana = ($jmldana + $laba)/$log_tran_pinjam['ltp_lama_angsuran'];
					$lender_fee          = $laba;
					$cicilan_pokok       = $jmldana;
					$lender_fee_after_tax      = $lender_fee;
					$total_pendapatan_pendana = $angsuran_ke_pendana;
					$pendapatan_bersih = $angsuran_ke_pendana;
					// hitung TAX -> PPH
					if ($log_tran_pinjam['ltp_product_pph'] != '0')
					{
						$hitung_tax = ($laba * $log_tran_pinjam['ltp_product_pph'])/100;
						$angsuran_ke_pendana      = $jmldana+$laba-$hitung_tax;
											
					}
					//$pendapatan_bersih = $angsuran_ke_pendana;	
					$total_pendapatan_bersih = $pendapatan_bersih;

				}else if ($log_tran_pinjam['ltp_type_of_business_id'] == '3') {
					// MIKRO
					// angsuran ke pendana per minggu
					$cicilan_pokok = $jmldana/$log_tran_pinjam['ltp_lama_angsuran'];
					// lender fee per minggu
					$lender_fee    = (($jmldana*$log_tran_pinjam['ltp_product_investor_return'] * $log_tran_pinjam['ltp_product_loan_term'])/100) /$log_tran_pinjam['ltp_lama_angsuran'];
					$lender_fee_tax = ((($jmldana*$log_tran_pinjam['ltp_product_investor_return'] * $log_tran_pinjam['ltp_product_loan_term'])/100) /$log_tran_pinjam['ltp_lama_angsuran'])* $log_tran_pinjam['ltp_product_pph']/100;
					$lender_fee_after_tax = $lender_fee - $lender_fee_tax;
					$platform_fee  = (($jmldana * $log_tran_pinjam['ltp_product_platform_rate'] * $log_tran_pinjam['ltp_product_loan_term'])/100)/$log_tran_pinjam['ltp_lama_angsuran'];
					$LO_fee        = (($jmldana * $log_tran_pinjam['ltp_product_loan_organizer'] * $log_tran_pinjam['ltp_product_loan_term'])/100)/$log_tran_pinjam['ltp_lama_angsuran'];
					
					$angsuran_ke_pendana = round($cicilan_pokok) + $lender_fee;
					$laba                = ($angsuran_ke_pendana * $log_tran_pinjam['ltp_lama_angsuran']) - $jmldana;
					$total_pendapatan_pendana = $jmldana + ($lender_fee*$log_tran_pinjam['ltp_lama_angsuran']);

					// hitung TAX -> PPH
					if ($log_tran_pinjam['ltp_product_pph'] != '0')
					{
						$hitung_tax = ($laba * $log_tran_pinjam['ltp_product_pph'])/100;
						$angsuran_ke_pendana      = $angsuran_ke_pendana - ($hitung_tax/$log_tran_pinjam['ltp_lama_angsuran']);
						$pendapatan_bersih = $laba - $hitung_tax;
					}
					$total_pendapatan_bersih = $jmldana + $pendapatan_bersih;
				}
				else if ($log_tran_pinjam['ltp_type_of_business_id'] == '5') {
					// AGRI
					// angsuran ke pendana per minggu
					$cicilan_pokok = $jmldana/$log_tran_pinjam['ltp_lama_angsuran'];
					// lender fee per minggu
					$lender_fee    = (($jmldana*$log_tran_pinjam['ltp_product_investor_return'] * $log_tran_pinjam['ltp_product_loan_term'])/100) /$log_tran_pinjam['ltp_lama_angsuran'];
					$lender_fee_tax = ((($jmldana*$log_tran_pinjam['ltp_product_investor_return'] * $log_tran_pinjam['ltp_product_loan_term'])/100) /$log_tran_pinjam['ltp_lama_angsuran'])* $log_tran_pinjam['ltp_product_pph']/100;
					$lender_fee_after_tax = $lender_fee - $lender_fee_tax;
					$platform_fee  = (($jmldana * $log_tran_pinjam['ltp_product_platform_rate'] * $log_tran_pinjam['ltp_product_loan_term'])/100)/$log_tran_pinjam['ltp_lama_angsuran'];
					$LO_fee        = (($jmldana * $log_tran_pinjam['ltp_product_loan_organizer'] * $log_tran_pinjam['ltp_product_loan_term'])/100)/$log_tran_pinjam['ltp_lama_angsuran'];
					
					$angsuran_ke_pendana = round($cicilan_pokok) + $lender_fee;
					$laba                = ($angsuran_ke_pendana * $log_tran_pinjam['ltp_lama_angsuran']) - $jmldana;
					$total_pendapatan_pendana = $jmldana + ($lender_fee*$log_tran_pinjam['ltp_lama_angsuran']);

					// hitung TAX -> PPH
					if ($log_tran_pinjam['ltp_product_pph'] != '0')
					{
						$hitung_tax = ($laba * $log_tran_pinjam['ltp_product_pph'])/100;
						$angsuran_ke_pendana      = $angsuran_ke_pendana - ($hitung_tax/$log_tran_pinjam['ltp_lama_angsuran']);
						$pendapatan_bersih = $laba - $hitung_tax;
					}
					$total_pendapatan_bersih = $jmldana + $pendapatan_bersih;
				}

					/*echo ($angsuran_ke_pendana);
					echo '<br>';
					echo ($total_pendapatan_pendana);
					echo '<br>';
					echo $hitung_tax;
					echo '<br>';
				exit();*/

				$prefixID    = 'DD-';
				$orderID     = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,12));
		        $exist_order = $this->Content_model->check_ordercode_transaksi_pendanaan($orderID);	// Cek if order ID exist on Database
				
				// jika order ID sudah ada di Database, generate lagi tambahkan datetime
				if (is_array($exist_order) && count($exist_order) > 0 )
				{
					$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
				}

				$status_pendanaan = ($jmldana == $jmlpinjaman)? 'approve' : 'pending';

				// insert profil penawaran pemberian pinjaman
				//tambahan baru
				//$Product_id = $this->Content_model->get_produk($productID);
				//batas tambahan baru

				$tbl_penawaran['Id']                                         = $orderID;
				$tbl_penawaran['Tgl_penawaran_pemberian_pinjaman']           = $nowdate;
				$tbl_penawaran['Jml_penawaran_pemberian_pinjaman']           = $jmldana;
				$tbl_penawaran['Jml_penawaran_pemberian_pinjaman_disetujui'] = $jmldana;
				$tbl_penawaran['Date_create']      = $nowdatetime;
				$tbl_penawaran['Master_loan_id']   = $pinjaman_transaksiID;
				$tbl_penawaran['User_id']          = $memdata['Id_pengguna'];
				$tbl_penawaran['dana_member_id']   = $uid;
				$tbl_penawaran['Product_id']       = $Product_id;
				$tbl_penawaran['pendanaan_status'] = $status_pendanaan;
				$tbl_penawaran['jml_laba']         = $laba;
				$tbl_penawaran['nama_pendana']     = $memdata['Nama_pengguna'];

				$insertMaster = $this->Content_model->insert_profil_pembiayaan($tbl_penawaran);

				if ($insertMaster)
				{
					// table detail_ profile_penawaran_pemberian_pinjaman
					$tbl_detail['Date_create']  = $nowdate;
					$tbl_detail['Investor_id']  = $memdata['Id_pengguna'];
					$tbl_detail['Amount']       = $jmldana;
					$tbl_detail['transaksi_id'] = $orderID;
					$insertDetail = $this->Content_model->insert_detail_profil_pembiayaan($tbl_detail);

					// --------- kurangi saldo pendana => master wallet --------------
					$this->Wallet_model->kurangi_saldo_wallet($uid, $jmldana);

					// detail transaksi wallet pendana
					$detail_w['Id']               = $get_master_wallet['Id'];
					$detail_w['Date_transaction'] = $nowdate;
					$detail_w['Amount']           = $jmldana;
					$detail_w['Notes']            = 'Pembiayaan pinjaman No.'.$tbl_penawaran['Master_loan_id'];
					$detail_w['tipe_dana']        = 2;
					$detail_w['User_id']          = $tbl_penawaran['User_id'];
					$detail_w['kode_transaksi']   = $orderID;
					$detail_w['balance']          = $get_master_wallet['Amount'] - $detail_w['Amount'];
					$this->Wallet_model->insert_detail_wallet($detail_w);
					// ---------- End kurangi saldo pendana ------------------

					// tambah kredit peminjam
					$this->Content_model->tambah_kredit_peminjam($tbl_penawaran['Master_loan_id'], $jmldana);

					// ----------  insert table log transaksi pendana ---------
					$inlogpendana['Master_loan_id']          = $tbl_penawaran['Master_loan_id'];
					$inlogpendana['Id_pendanaan']            = $orderID;
					$inlogpendana['nama_pendana']            = $memdata['Nama_pengguna'];
					$inlogpendana['jml_pendanaan']           = $jmldana;
					$inlogpendana['cicilan_pokok']           = $cicilan_pokok;
					$inlogpendana['lender_fee']              = $lender_fee;
					$inlogpendana['lender_fee_tax']          = $lender_fee_after_tax;
					$inlogpendana['jml_angsuran_ke_pendana'] = $angsuran_ke_pendana;
					$inlogpendana['date_created']            = date('Y-m-d H:i:s');
					$inlogpendana['angsuran_count']          = $log_tran_pinjam['ltp_lama_angsuran'];
					$inlogpendana['total_pendapatan']        = $total_pendapatan_pendana;
					$inlogpendana['total_pajak']             = $hitung_tax;
					 $inlogpendana['pendapatan_bersih']   = $total_pendapatan_bersih;
					$this->Content_model->insert_log_transaksi_pendana($inlogpendana);
					// ----------  End of insert table log transaksi pendana ---------

					// cek jika jml pendanaan = jml pinjaman, maka masuk saldo peminjam
					// ----- Pendanaan sudah terpenuhi 100 % -----
					if ($jmldana == $jmlpinjaman OR $jml_kurang_pinjaman == $jmldana)
					{
						$check_wallet_peminjam = $this->Wallet_model->get_wallet_bymember($mid_peminjam);

						// if ( is_array($check_wallet_peminjam) && count($check_wallet_peminjam)>0 )
						// {
						// 	// update saldo peminjam
						// 	$this->Wallet_model->update_master_wallet_saldo($ID_peminjam, $jmlpinjaman_disetujui);
						// 	$id_masterwallet_peminjam = $check_wallet_peminjam['Id'];
						// }else{
						// 	// insert saldo peminjam
						// 	$inmwallet['Date_create']      = $nowdate;
						// 	$inmwallet['User_id']          = $ID_peminjam;
						// 	$inmwallet['Amount']           = $jmlpinjaman_disetujui;
						// 	$inmwallet['wallet_member_id'] = $mid_peminjam;

						// 	$id_masterwallet_peminjam = $this->Wallet_model->insert_master_wallet($inmwallet);
						// }
						//tambahan uang administrasi

						// Insert Detail wallet peminjam
						// $dwp['Id']               = $id_masterwallet_peminjam;
						// $dwp['Date_transaction'] = $nowdatetime;
						// $dwp['Amount']           = $jmlpinjaman_disetujui;
						// $dwp['Notes']            = 'Pemberian dana pinjaman No.'.$tbl_penawaran['Master_loan_id'];
						// $dwp['tipe_dana']        = 1;
						// $dwp['User_id']          = $ID_peminjam;
						// $dwp['kode_transaksi']   = $tbl_penawaran['Master_loan_id'];
						// $dwp['balance']          = $check_wallet_peminjam['Amount'] + $dwp['Amount'];
						// $this->Wallet_model->insert_detail_wallet($dwp);

						// $dwp2['Id']               = $id_masterwallet_peminjam;
						// $dwp2['Date_transaction'] = $nowdatetime;
						// $dwp2['Amount']           = $log_tran_pinjam['ltp_admin_fee'];
						// $dwp2['Notes']            = 'Pembayaran dana administrasi transaksi No.'.$tbl_penawaran['Master_loan_id'];
						// $dwp2['tipe_dana']        = 2;
						// $dwp2['User_id']          = $ID_peminjam;
						// $dwp2['kode_transaksi']   = $tbl_penawaran['Master_loan_id'];
						// $dwp2['balance']          = $check_wallet_peminjam['Amount'] + $dwp['Amount'];
						// $this->Wallet_model->insert_detail_wallet($dwp2);

						// $check_wallet_bkd = $this->Wallet_model->get_wallet_bymember(269);

						// $dwbkd['Id']               = 69;
						// $dwbkd['Date_transaction'] = $nowdatetime;
						// $dwbkd['Amount']           = $log_tran_pinjam['ltp_admin_fee'];
						// $dwbkd['Notes']            = 'Penerimaan dana administrasi transaksi No.'.$tbl_penawaran['Master_loan_id'];
						// $dwbkd['tipe_dana']        = 1;
						// $dwbkd['User_id']          = 269;
						// $dwbkd['kode_transaksi']   = $tbl_penawaran['Master_loan_id'];
						// $dwbkd['balance']          = $check_wallet_bkd['Amount'] + $log_tran_pinjam['ltp_admin_fee'];
						// $this->Wallet_model->insert_detail_wallet($dwbkd);

						// //tambahan 23 Januari 2019
						// $check_wallet_koperasi = $this->Wallet_model->get_wallet_bymember(5);
						// if($log_tran_pinjam['ltp_type_of_business_id'] == '3'){
						// $dwpkop['Id']               = 4;
						// $dwpkop['Date_transaction'] = $nowdatetime;
						// $dwpkop['Amount']           = $log_tran_pinjam['ltp_frozen'];
						// $dwpkop['Notes']            = 'Penerimaan dana frozen transaksi No.'.$tbl_penawaran['Master_loan_id'];
						// $dwpkop['tipe_dana']        = 1;
						// $dwpkop['User_id']          = 5;
						// $dwpkop['kode_transaksi']   = $tbl_penawaran['Master_loan_id'];
						// $dwpkop['balance']          = $check_wallet_koperasi['Amount'] + $log_tran_pinjam['ltp_frozen'];
						// $this->Wallet_model->insert_detail_wallet($dwpkop);

						// //update 24 Januari 2019
						// //$walletkop = $this->Wallet_model->get_wallet_bkd(5);

						// $upwalkop = $log_tran_pinjam['ltp_frozen'];
						// $this->Wallet_model->update_master_wallet_saldo(5,$upwalkop);	
						// }
						//end of tambahan 23 Januari 2019
						
						$memberpinjam = $this->Content_model->get_pinjaman_member($tbl_penawaran['Master_loan_id']);

						//udpate 24 januari 2019
						//update saldo bkd
						//$walletbkd = $this->Wallet_model->get_wallet_bkd(269);

						$upwalbkd = $log_tran_pinjam['ltp_admin_fee'];
						$this->Wallet_model->update_master_wallet_saldo(269, $upwalbkd);

						//end of update saldo bkd
						//end of tambahan uang administrasi

						/*// Insert Detail wallet peminjam
						$dwp['Id']               = $id_masterwallet_peminjam;
						$dwp['Date_transaction'] = $nowdatetime;
						$dwp['Amount']           = $jmlpinjaman_disetujui;
						$dwp['Notes']            = 'Pemberian dana pinjaman No.'.$tbl_penawaran['Master_loan_id'];
						$dwp['tipe_dana']        = 1;
						$dwp['User_id']          = $ID_peminjam;
						$dwp['kode_transaksi']   = $tbl_penawaran['Master_loan_id'];
						$dwp['balance']          = $check_wallet_peminjam['Amount'] + $dwp['Amount'];
						$this->Wallet_model->insert_detail_wallet($dwp);

						$memberpinjam = $this->Content_model->get_pinjaman_member($tbl_penawaran['Master_loan_id']);*/

						// --------- Create Tgl Jatuh Tempo -> Insert ke table Mod_Tempo ---------
						if ($log_tran_pinjam['ltp_type_of_business_id'] == '1')
						{
							// Kilat
							$loan_term       = $log_tran_pinjam['ltp_product_loan_term'];
							$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$loan_term." days"));

							$intempo['kode_transaksi']  = $tbl_penawaran['Master_loan_id'];
							$intempo['tgl_jatuh_tempo'] = $tgl_jatuh_tempo;
							$intempo['no_angsuran']     = 1;
							$this->Content_model->insert_table_tempo($intempo);

							$output['list_pendana'] = $this->Content_model->get_list_pendana($tbl_penawaran['Master_loan_id']);

							$output['member']   = $memberpinjam;
							$output['tgl']      = parseDateTimeIndex(date('Y-m-d'));
							$output['jml_hari'] = $loan_term;

							$html               = $this->load->view('email/vpinjaman-kilat', $output, TRUE);
							
							$filename = 'perjanjian-pinjaman-kilat-'.$tbl_penawaran['Master_loan_id'].'.pdf';
							$title    = 'Perjanjian Pinjaman Kilat '.$tbl_penawaran['Master_loan_id'];
							$label_transaksi = 'Pinjaman Kilat';

						}else{
							// Mikro
							for ($i=1; $i <= $log_tran_pinjam['ltp_lama_angsuran']; $i++) { 
								
								$loan_term       = $log_tran_pinjam['ltp_product_loan_term'];
								$tgl_jatuh_tempo = date('Y-m-d', strtotime("+".$i." week"));

								$intempo['kode_transaksi']  = $tbl_penawaran['Master_loan_id'];
								$intempo['tgl_jatuh_tempo'] = $tgl_jatuh_tempo;
								$intempo['no_angsuran']     = $i;
								$this->Content_model->insert_table_tempo($intempo);
							}

							$output['list_pendana'] = $this->Content_model->get_list_pendana($tbl_penawaran['Master_loan_id']);
							
							$output['member']    = $memberpinjam;
							$output['tgl']       = parseDateTimeIndex(date('Y-m-d'));
							$output['tgl_order'] = date('d/m/Y', strtotime($memberpinjam['Tgl_permohonan_pinjaman']));
							$output['jml_hari'] = $loan_term*30;

							$html                = $this->load->view('email/vpinjaman-mikro', $output, TRUE);
							
							$filename = 'perjanjian-pinjaman-mikro-'.$tbl_penawaran['Master_loan_id'].'.pdf';
							$title    = 'Perjanjian Pinjaman Mikro '.$tbl_penawaran['Master_loan_id'];
							$label_transaksi = 'Pinjaman Mikro';
						}
						
						// $token_data = $this->Member_model->get_fcm_token($loan_data['User_id']);
						// $message_fcm = "Pinjaman Agri anda dengan nomor traksaksi ".$loan_data['Master_loan_id']." telah ditinjau, silahkan cek Dashboard Anda untuk melanjutkan pinjaman";
						// $title_fcm	= "Status Pinjaman";
						// $action = "akad_pinjaman";
						// update status pinjaman mjd approve
						$this->Content_model->approve_pinjaman_bycode($tbl_penawaran['Master_loan_id']);
						// update semua pendana status mjd approve
						$this->Content_model->approve_transaksi_pendana_bypinjaman($tbl_penawaran['Master_loan_id']);

						// --------- Generate PDF Peminjam  ----------
						$attach_file = $this->create_pdf($html, $tbl_penawaran['Master_loan_id'], $filename, $title);
						$this->send_mail_peminjam($memberpinjam, $label_transaksi, $attach_file);
						unlink($pdf_folder.$filename);
						// --------- Generate PDF Peminjam  ----------

						unset($output);
						// --------- Generate PDF Pendana ----------
						$output['member']    = $memdata;
						$output['ordercode'] = $orderID;
						$output['tgl_order'] = date('d/m/Y');
						$html = $this->load->view('email/vpendana', $output, TRUE);

						$filename = 'perjanjian-pendana-'.$orderID.'.pdf';
						$title    = 'Perjanjian Pendana '.$orderID;
						$attach_file = $this->create_pdf($html, $output['ordercode'], $filename, $title);
						// --------- End Generate PDF ----------
						
						$this->send_email($memdata['mum_email'], $orderID, $jmldana, $attach_file);
						unlink($pdf_folder.$filename);
					}
					// ------ End Pendanaan 100% -------

						//tambahan baru insert ke record_pinjaman
					/*	$updata1['Jml_permohonan_pinjaman_disetujui'] = antiInjection(trim($post['jml_pinj_disetujui']));
						$updata1['Amount'] = antiInjection(trim($post['jml_pinj_disetujui']));
						$affected = $this->Wallet_model->update_profil_pinjaman($updata1, $pinjaman_transaksiID);*/

						$userid = trim($post['id_peminjam']);
						$masterloan = trim($post['transaksi_id']);
						//$updata1 = explode('.', trim($post['jml_pendanaan']));
						//$jmldana               = str_replace(',', '', $filter[0]);


						$this->db->from('record_pinjaman');
						$this->db->where('User_id', $userid);
						//$this->db->where('Master_loan_id', $masterloan);
						$this->db->like('Flag', 'CF', 'BOTH');
						$hasil=$this->db->get()->num_rows();
						// $p_pinjam2['user_id'] =$uid;
						/*if($hasil>0){
							$hasil+=1;	
						}else{
							$hasil="1";
						}*/

						$p_pinjam['Flag'] = 'CF';
						$p_pinjam['Master_loan_id'] = $masterloan; //transaksi_id
						$p_pinjam['User_id'] = $userid; //id_peminjam
						$p_pinjam['Amount'] = $jmldana;
						$p_pinjam['Tgl_pinjaman'] = date('Y-m-d H:i:s');
						$pinjamID = $this->Content_model->insert_profil_pinjaman5($p_pinjam);

						//batas tambahan baru


					$this->session->set_userdata('message','Berhasil melakukan proses pembiayaan untuk pinjaman no.transaksi '.$post['transaksi_id']);
					$this->session->set_userdata('message_type','success');
				}else{
					$this->session->set_userdata('message','Error proses pembiayaan!');
					$this->session->set_userdata('message_type','error');
				}

				redirect('daftar-peminjam-detail/?tid='. $post['transaksi_id']);
			}else{
				$this->session->set_userdata('message','Proses Pembiayaan gagal!');
				$this->session->set_userdata('message_type','error');
				redirect('daftar-peminjam-detail/?tid='. $post['transaksi_id']);
				exit();
			}
		}
	}

	function send_email($email, $code, $jml, $file)
	{
		$html_content = '
        Hai '.$email.',<br><br>

            Penawaran Pendanaan Anda telah disetujui.<br><br>
            No.Pendanaan : '.$code.' <br>
            Nominal : Rp '.number_format($jml).'
            
            <br><br>

            <span style="color:#858C93;">
            	Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke Email ini.
            	<br><br>

            	&copy; BKDana.com, '.date("Y").'. All rights reserved.
            </span>
			';
		
    	$mail = new phpmailer();
        $mail->IsSMTP();
		$mail->SMTPAuth    = true;
		//$mail->SMTPSecure  = 'ssl';
		$mail->Host        = 'smtp.gmail.com';
		$mail->Port        = 587;
		$mail->IsHTML(true);
		$mail->Username    = $this->config->item('mail_username');
		$mail->Password    = $this->config->item('mail_password');
		$mail->SetFrom('bkdanafinansial@gmail.com', 'BKDana');	
		$mail->AddAddress($email);
		$mail->Subject     = 'Penawaran Pendanaan pinjaman disetujui';
		$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($html_content);	
		$mail->SMTPDebug   = 0;
		/*$mail->addAttachment($file['output_file'], $file['filename']);*/
        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }	

        return TRUE;
	}

	function send_mail_peminjam($userdata, $type, $file)
	{
		$subject_title = 'Pinjaman '.$userdata['Master_loan_id'].' disetujui dan mendapat pendanaan';

		$html_content = '
        Hai '.$userdata['Nama_pengguna'].',<br><br>

            Pengajuan Pinjaman Anda di BKDana.com telah disetujui dan sudah mendapat Pendanaan.
            <br><br>
            
            Berikut detail pinjaman Anda:<br><br>

            No. Transaksi: '.$userdata['Master_loan_id'].'
            <br>
            Jenis Transaksi: '.$type.'
            <br>
            Jumlah Pinjaman: Rp '.number_format($userdata['Amount']).'
            <br>
            Jumlah Pinjaman diterima: Rp '.number_format($userdata['Jml_permohonan_pinjaman_disetujui']).'
            <br>
            Status: <strong>Sudah mendapat pendanaan</strong>
            <br><br>

            <span style="color:#858C93;">
            	Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke Email ini.
            	<br><br>

            	&copy; BKDana.com, '.date("Y").'. All rights reserved.
            </span>
			';

    	$mail = new phpmailer();
        $mail->IsSMTP();
		$mail->SMTPAuth    = true;
		//$mail->SMTPSecure  = 'ssl';
		$mail->Host        = 'smtp.gmail.com';
		$mail->Port        = 587;
		$mail->IsHTML(true);
		$mail->Username    = $this->config->item('mail_username');
		$mail->Password    = $this->config->item('mail_password');
		$mail->SetFrom('bkdanafinansial@gmail.com', 'BKDana');	
		$mail->AddAddress($userdata['mum_email']);
		$mail->Subject     = $subject_title;
		$mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
		$mail->MsgHTML($html_content);	
		$mail->SMTPDebug   = 0;

		/* Update by desta */
		// Attachments
	    $mail->addAttachment($file['output_file'], $file['filename']);

        if(!$mail->Send()) {
            //echo $mail->ErrorInfo;exit;
        	$result = 'failed';		

        }else{
            $result = 'success';		                		               	
        }	

        return TRUE;
	
	}

	function create_pdf($html, $ordercode, $filename, $title)
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('BKDana');
		$pdf->SetTitle($title);
		$pdf->SetSubject($title);

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

		$pdf->SetMargins(12, 15, 16,true);

		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		    require_once(dirname(__FILE__).'/lang/eng.php');
		    $pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);

		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont('helvetica', '', 9);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		// set text shadow effect
		//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

		ob_start();
	    $pdf->writeHTML($html, true, false, false, false, '');
	    ob_end_clean();
		// ---------------------------------------------------------

		$output_file = $this->config->item('attach_dir') . $filename;

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		//$pdf->Output($ordercode. '.pdf', 'I');
		$pdf->Output($output_file,'F');

		$ret = array(
				'filename'    => $filename,
				'output_file' =>$output_file
				);

		return $ret;
	}
}
