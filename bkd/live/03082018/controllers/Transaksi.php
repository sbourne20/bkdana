<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi extends CI_Controller {

	/* --- Transaksi Peminjam ---  */

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		$this->load->model('Wallet_model');

		$this->load->library('pagination');
		error_reporting(E_ALL);

		$this->Content_model->has_login();
	}
	
	public function index()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

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
		$data['total_pinjaman'] = $this->Content_model->get_jml_pinjam($uid);
		$data['total_invest']   = $this->Content_model->get_jml_invest($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		if ($logintype == '1') {
			// Peminjam
			$data['list_transaksi'] = $this->Content_model->get_my_transactions_pinjam($uid, $limit_per_page, $start_index);
			$total_records          = $this->Content_model->get_total_pinjam($uid);
			$data['pages']          = 'v_transaksi';
		}else{
			// Pendana
			$data['list_transaksi'] = $this->Content_model->get_my_transactions_pendana($uid, $limit_per_page, $start_index);
			$total_records          = $this->Content_model->get_total_pendana($uid);
			$data['pages']          = 'v_transaksi_pendana';
		}  
 
        if (is_array($total_records) && $total_records['itotal'] > 0) 
        {             
            $config['base_url']    = base_url() . 'transaksi/page';
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

	public function search()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');
		
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
		$data['total_pinjaman'] = $this->Content_model->get_jml_pinjam($uid);
		$data['total_invest']   = $this->Content_model->get_jml_invest($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		if ($logintype == '1') {
			$data['list_transaksi'] = $this->Content_model->get_my_transactions_pinjam($uid, $limit_per_page, $start_index);
			$total_records          = $this->Content_model->get_total_pinjam($uid);
			$data['pages']          = 'v_transaksi';
		}else{
			$data['list_transaksi'] = $this->Content_model->get_my_transactions_pendana($uid, $limit_per_page, $start_index);
			$total_records          = $this->Content_model->get_total_pendana($uid);
			$data['pages']          = 'v_transaksi_pendana';
		}  
        
 
        if (is_array($total_records) && $total_records['itotal'] > 0) 
        {             
            $config['base_url']    = base_url() . 'transaksi/page';
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
        }

		
		$this->load->view('template', $data);
	}

	function detail()
	{
		// ====== Detail Pinjaman ========

		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['top_css'] .= add_css('js/alertify/css/alertify.min.css');
		$data['top_css'] .= add_css('js/alertify/css/themes/default.min.css');

		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/alertify/alertify.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric/autoNumeric.min.js');
		$data['bottom_js'] .= add_js('js/autoNumeric-init.js');
		$data['bottom_js'] .= add_js('js/dsn.js');
		$data['bottom_js'] .= add_js('js/transaction.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$uid = htmlentities($_SESSION['_bkduser_']);
		$logintype = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$ID = antiInjection($this->input->get('tid', TRUE)); // transaksi id

		$transaksi                = $this->Content_model->get_transaksi_pinjam_byid($ID); // pinjaman
		$data['detail_transaksi'] = $this->Content_model->get_detail_pinjam_byid($ID); // cicilan
		$data['transaksi'] = $transaksi;

		//_d($data['transaksi']);

		/*$bunga       = $transaksi['Jml_permohonan_pinjaman'] * ($transaksi['Interest_rate'] + $transaksi['Platform_rate'] + $transaksi['Investor_return'] + $transaksi['Secured_loan_fee']);
		$bunga       = $bunga/100;
		$total_bayar = $transaksi['Jml_permohonan_pinjaman'] + $bunga;*/
		$total_bayar = $transaksi['Jml_permohonan_pinjaman_disetujui'];
		$data['total_bayar'] = $total_bayar;
		
		if ($transaksi['type_of_business_id']=1)
		{
			// Pinjaman Kilat
			$data['jml_cicilan'] = $total_bayar;			
		}else{
			// Pinjaman Mikro
			$data['jml_cicilan'] = ceil($total_bayar/$transaksi['Loan_term']);			
		}

		if ($transaksi['Master_loan_status'] == 'approve') {
			// hitung jatuh tempo
			$data['jatuh_tempo'] = date('d/m/Y', strtotime("+3 months", strtotime($transaksi['tgl_pinjaman_disetujui'])));
		}else{
			$data['jatuh_tempo'] = '-';
		}

		$data['pages']    = 'v_transaksi_detail';
		$this->load->view('template', $data);
	}

	function submit_cicilan()
	{
		// --- Bayar cicilan pakai Saldo --- //

		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$post = $this->input->post(NULL, TRUE);

			$uid = htmlentities($_SESSION['_bkduser_']);

			$transaksi_id       = trim($post['transaksi_id']);
			$jml_cicilan_hidden = trim($post['jml_cicilan']);	// jml cicilan asli
			$jml_cicilan        = trim($post['jml_bayar']);		// jml dari input user

			$filter = explode('.', $jml_cicilan);
			$jml_bayar = str_replace(',', '', $filter[0]);

			if (!empty($uid) && $transaksi_id != '' AND $jml_cicilan != '' AND strlen($jml_cicilan) > 4 && $jml_cicilan_hidden == $jml_bayar)
			{
				$nowdate     = date('Y-m-d');
				$nowdatetime = date('Y-m-d H:i:s');
				$get_master_wallet = $this->Wallet_model->get_wallet_bymember($uid);

				if (is_array($get_master_wallet) && isset($get_master_wallet['Id']) && $get_master_wallet['Amount'] >= $jml_bayar)
				{

					$indetail['Master_loan_id']   = antiInjection($transaksi_id);
					$indetail['Date_repaid']      = $nowdate;
					$indetail['Amount_repayment'] = antiInjection($jml_bayar);

					$detail_id = $this->Content_model->insert_cicilan($indetail);

					if ($detail_id)
					{
						// update profil pinjaman tambah total_loan_repayment, kurangi total_loan_outstanding
						$this->Content_model->update_total_loan_repayment($indetail['Master_loan_id'], $uid, $indetail['Amount_repayment']);

						// master wallet -> kurangi saldo peminjam
						$this->Wallet_model->kurangi_saldo_wallet($uid, $jml_bayar);

						// detail transaksi wallet 
						$detail_w['Id']               = $get_master_wallet['Id'];
						$detail_w['Date_transaction'] = $nowdate;
						$detail_w['Amount']           = $jml_bayar;
						$detail_w['Notes']            = 'Pembayaran cicilan pinjaman No.'. $indetail['Master_loan_id'];
						$this->Wallet_model->insert_detail_wallet($detail_w);

						$get_data_pinjam = $this->Content_model->get_transaksi_pinjam_byid($transaksi_id); // get total yg sdh diangsur

						if ($get_data_pinjam['Total_loan_repayment'] >= $get_data_pinjam['Jml_permohonan_pinjaman_disetujui'])
						{
							// date close, status lunas
							$this->Content_model->close_pinjaman($indetail['Master_loan_id']);
						}
						
						$this->session->set_userdata('message','Sukses melakukan pembayaran.');
						$this->session->set_userdata('message_type','success');
					}
				}

			}else{
				$this->session->set_userdata('message','Jumlah pembayaran cicilan tidak sesuai.');
				$this->session->set_userdata('message_type','error');
			}
			redirect('transaksi/detail/?tid=' . $transaksi_id);
		}
	}


	function detail_pendanaan()
	{
		// ======= Pendanaan =========
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');
		$data['bottom_js'] .= add_js('js/validationengine/languages/jquery.validationEngine-en.js');
		$data['bottom_js'] .= add_js('js/validationengine/jquery.validationEngine.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_noindex('bkdana.com', 'website bkdana.com');

		$uid       = htmlentities($_SESSION['_bkduser_']);
		$logintype = htmlentities($_SESSION['_bkdtype_']); // 1.peminjam, 2.pendana
		$data['logintype'] = $logintype;
		$data['memberid']  = $uid;
		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$ID = antiInjection($this->input->get('tid', TRUE)); // transaksi id

		$transaksi                = $this->Content_model->get_transaksi_pendana_byid($ID);
		$data['detail_transaksi'] = $this->Content_model->get_detail_pinjam_byid($ID);
		$data['transaksi'] = $transaksi;

		/*$bunga       = $transaksi['Jml_penawaran_pemberian_pinjaman'] * ($transaksi['Interest_rate'] + $transaksi['Platform_rate'] + $transaksi['Investor_return'] + $transaksi['Secured_loan_fee']);
		$bunga       = $bunga/100;
		$total_bayar = $transaksi['Jml_penawaran_pemberian_pinjaman'] + $bunga;

		$data['total_bayar'] = $total_bayar;
		$data['jml_cicilan'] = $total_bayar/$transaksi['Loan_term'];
		*/
		if ($transaksi['tgl_disetujui'] != '0000-00-00 00:00:00') {
			// hitung jatuh tempo
			$data['jatuh_tempo'] = date('d/m/Y', strtotime("+3 months", strtotime($transaksi['tgl_disetujui'])));
		}else{
			$data['jatuh_tempo'] = '-';
		}

		$data['pages']    = 'v_transaksi_detail_pendana';
		$this->load->view('template', $data);
	}
	
}