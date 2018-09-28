<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_peminjam extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();
		$this->load->model('Member_model');
		$this->load->model('Wallet_model');

		$this->load->library('pagination');

		$this->Content_model->has_login();
		$this->Content_model->is_active_pendana();
		
		error_reporting(E_ALL);
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
	        $start_index    = 0;;
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

		//$data['detail_transaksi'] = $this->Content_model->get_detail_pinjam_byid($ID);
		$transaksi         = $this->Content_model->get_transaksi_pinjam_byid($ID);
		$data['transaksi'] = $transaksi;

		//_d($transaksi);

		/*$bunga       = $transaksi['Jml_permohonan_pinjaman'] * ($transaksi['Interest_rate'] + $transaksi['Platform_rate'] + $transaksi['Investor_return'] + $transaksi['Secured_loan_fee']);
		$bunga       = $bunga/100;
		$total_bayar = $transaksi['Jml_permohonan_pinjaman'] + $bunga;
		$data['total_bayar'] = $total_bayar;*/

		$data['total_bayar'] = $transaksi['Jml_permohonan_pinjaman'];

		$get_total_pendana = $this->Content_model->get_kuota_pinjaman($transaksi['Master_loan_id']);
		$total_dana_masuk  = $get_total_pendana['jml_pendanaan'];
		$total_pinjaman    = $transaksi['Jml_permohonan_pinjaman'];

		$data['kuota_dana'] = round(($total_dana_masuk/$total_pinjaman) * 100);

		$data['pages']    = 'v_daftar_peminjam_detail';
		$this->load->view('template', $data);
	}

	function submit_pendanaan()
	{
		$post = $this->input->post(NULL, TRUE);

		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$uid = htmlentities($_SESSION['_bkduser_']);
			$memdata = $this->Member_model->get_member_byid($uid);

			$nowdate     = date('Y-m-d');
			$nowdatetime = date('Y-m-d H:i:s');

			$filter              = explode('.', trim($post['jml_pendanaan']));
			$jmldana             = str_replace(',', '', $filter[0]);
			$jmlpinjaman         = antiInjection(trim($post['jml_pinjaman']));
			$jml_kredit_pinjaman = antiInjection(trim($post['jml_kredit']));
			$jml_kurang_pinjaman = antiInjection(trim($post['jml_kekurangan']));

			$ID_peminjam  = antiInjection(trim($post['id_peminjam']));
			$mid_peminjam = antiInjection(trim($post['id_peminjam_member']));

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

			}else if ($jmldana != '' && strlen($jmldana) > 5 && $jmldana >= 100000) {
				$jmldana = antiInjection($jmldana);

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
				$tbl_penawaran['Id']                                         = $orderID;
				$tbl_penawaran['Tgl_penawaran_pemberian_pinjaman']           = $nowdate;
				$tbl_penawaran['Jml_penawaran_pemberian_pinjaman']           = $jmldana;
				$tbl_penawaran['Jml_penawaran_pemberian_pinjaman_disetujui'] = $jmldana;
				$tbl_penawaran['Date_create']      = $nowdatetime;
				$tbl_penawaran['Master_loan_id']   = antiInjection(trim($post['transaksi_id']));
				$tbl_penawaran['User_id']          = $memdata['Id_pengguna'];
				$tbl_penawaran['dana_member_id']   = $uid;
				$tbl_penawaran['Product_id']       = 4;
				$tbl_penawaran['pendanaan_status'] = $status_pendanaan;

				$insertMaster = $this->Content_model->insert_profil_pembiayaan($tbl_penawaran);

				if ($insertMaster)
				{
					// table detail_ profile_penawaran_pemberian_pinjaman
					$tbl_detail['Date_create']  = $nowdate;
					$tbl_detail['Investor_id']  = $memdata['Id_pengguna'];
					$tbl_detail['Amount']       = $jmldana;
					$tbl_detail['transaksi_id'] = $orderID;
					$insertDetail = $this->Content_model->insert_detail_profil_pembiayaan($tbl_detail);

					// master wallet -> kurangi saldo pendana
					$this->Wallet_model->kurangi_saldo_wallet($uid, $jmldana);

					

					// detail transaksi wallet 
					$detail_w['Id']               = $get_master_wallet['Id'];
					$detail_w['Date_transaction'] = $nowdate;
					$detail_w['Amount']           = $jmldana;
					$detail_w['Notes']            = 'Pembiayaan transaksi pinjaman No.'.$tbl_penawaran['Master_loan_id'];
					$this->Wallet_model->insert_detail_wallet($detail_w);

					// tambah kredit peminjam
					$this->Content_model->tambah_kredit_peminjam($tbl_penawaran['Master_loan_id'], $jmldana);

					// cek jika jml pendanaa = jml pinjaman, maka masuk saldo peminjam
					if ($jmldana == $jmlpinjaman OR $jml_kurang_pinjaman == $jmldana)
					{
						$check_wallet_peminjam = $this->Wallet_model->get_wallet_bymember($mid_peminjam);

						if ( is_array($check_wallet_peminjam) && count($check_wallet_peminjam)>0 )
						{
							// update
							$this->Wallet_model->update_master_wallet_saldo($ID_peminjam, $jmlpinjaman);

							$id_masterwallet_peminjam = $check_wallet_peminjam['Id'];
						}else{
							// insert

							$inmwallet['Date_create']      = $nowdate;
							$inmwallet['User_id']          = $ID_peminjam;
							$inmwallet['Amount']           = $jmlpinjaman;
							$inmwallet['wallet_member_id'] = $mid_peminjam;

							$id_masterwallet_peminjam = $this->Wallet_model->insert_master_wallet($inmwallet);
						}
						// detail transaksi wallet peminjam
						$dwp['Id']               = $id_masterwallet_peminjam;
						$dwp['Date_transaction'] = $nowdate;
						$dwp['Amount']           = $jmlpinjaman;
						$dwp['Notes']            = 'Pembiayaan transaksi pinjaman No.'.$tbl_penawaran['Master_loan_id'].'. dari Pendanaan No. '.$orderID;
						$this->Wallet_model->insert_detail_wallet($dwp);

						// update status pinjaman mjd approve
						$this->Content_model->approve_pinjaman_bycode($tbl_penawaran['Master_loan_id']);
						// update semua pendana status mjd approve
						$this->Content_model->approve_transaksi_pendana_bypinjaman($tbl_penawaran['Master_loan_id']);
					}

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
}