<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaksi_pendana extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		$this->load->library('pagination');
		error_reporting(E_ALL);
	}
	
	public function index()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

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

		/*if ($logintype == '1') {
			$data['list_transaksi'] = $this->Content_model->get_my_transactions_pinjam($uid, $limit_per_page, $start_index);
			$total_records          = $this->Content_model->get_total_pinjam($uid);
		}else{
			$data['list_transaksi'] = $this->Content_model->get_my_transactions_pendana($uid, $limit_per_page, $start_index);
			$total_records          = $this->Content_model->get_total_pendana($uid);
		}  */

		$data['list_transaksi'] = $this->Content_model->get_my_transactions_pendana($uid, $limit_per_page, $start_index);
		$total_records          = $this->Content_model->get_total_pendana($uid);
        
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

		$data['pages']    = 'v_transaksi';
		$this->load->view('template', $data);
	}

	function detail()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['top_css'] .= add_css('js/validationengine/validationEngine.jquery.css');

		$data['bottom_js'] .= add_js('js/jquery-loading-overlay/dist/loadingoverlay.min.js');
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
		$data['detail_transaksi'] = $this->Content_model->get_transaksi_pinjam_byid($transaksi['Master_loan_id']);
		$data['log_pendanaan']    = $this->Content_model->get_log_pendanaan_by_codedana($ID);
		$log_pinjaman             = $this->Content_model->get_log_transaksi_pinjam($data['detail_transaksi']['Master_loan_id']);
		$data['produk_pinjaman'] = $this->Content_model->get_produk($data['detail_transaksi']['Product_id']);
		$data['transaksi'] = $transaksi;

		//_d($data['detail_transaksi']);

		if ($transaksi['tgl_disetujui'] != '0000-00-00 00:00:00') {
			// hitung jatuh tempo
			$data['jatuh_tempo'] = date('d/m/Y', strtotime($log_pinjaman['ltp_tgl_jatuh_tempo']));
			
		}else{
			$data['jatuh_tempo'] = '-';
		}

		$data['pages']    = 'v_transaksi_detail_pendana';
		$this->load->view('template', $data);
	}
	public function search()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/jquery-loading-overlay/dist/loadingoverlay.min.js');
		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com', 'website bkdana.com');
		
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
		}else{
			$data['list_transaksi'] = $this->Content_model->get_my_transactions_pendana($uid, $limit_per_page, $start_index);
			$total_records          = $this->Content_model->get_total_pendana($uid);
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

		$data['pages']    = 'v_transaksi';
		$this->load->view('template', $data);
	}

	/*function detail()
	{
		$data['top_css']   = '';
		$data['top_js']    = '';
		$data['bottom_js'] = '';

		$data['bottom_js'] .= add_js('js/dsn.js');

		$data['title'] = $this->M_settings->title;
		$data['meta_tag'] = $this->M_settings->meta_tag_dynamics('bkdana.com', 'website bkdana.com');

		$uid = htmlentities($_SESSION['_bkduser_']);
		$data['memberid']  = $uid;
		$data['memberdata']     = $this->Member_model->get_member_byid($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$ID = antiInjection($this->input->get('tid', TRUE)); // transaksi id

		$transaksi                = $this->Content_model->get_transaksi_pendana_byid($ID);
		$data['detail_transaksi'] = $this->Content_model->get_detail_pinjam_byid($ID);
		$data['transaksi'] = $transaksi;

		$bunga       = $transaksi['Jml_penawaran_pemberian_pinjaman'] * ($transaksi['Interest_rate'] + $transaksi['Platform_rate'] + $transaksi['Investor_return'] + $transaksi['Secured_loan_fee']);
		$bunga       = $bunga/100;
		$total_bayar = $transaksi['Jml_penawaran_pemberian_pinjaman'] + $bunga;
		$data['total_bayar'] = $total_bayar;
		$data['jml_cicilan'] = $total_bayar/$transaksi['Loan_term'];

		if ($transaksi['tgl_disetujui'] != '0000-00-00 00:00:00') {
			// hitung jatuh tempo
			$data['jatuh_tempo'] = date('d/m/Y', strtotime("+3 months", strtotime($transaksi['tgl_disetujui'])));
		}else{
			$data['jatuh_tempo'] = '-';
		}

		$data['pages']    = 'v_transaksi_detail_pendana';
		$this->load->view('template', $data);
	}*/
	
}