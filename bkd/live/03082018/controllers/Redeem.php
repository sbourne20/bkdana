<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Redeem extends CI_Controller {

	public function __construct()
	{
		parent::  __construct();

		$this->load->model('Member_model');
		$this->load->model('Wallet_model');

		$this->Content_model->has_login();
		$this->Content_model->is_active_pendana();
		error_reporting(E_ALL);
	}
	
	public function index()
	{
		$this->Content_model->has_login();

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
		$data['total_pinjaman'] = $this->Content_model->get_jml_pinjam($uid);
		$data['total_invest']   = $this->Content_model->get_jml_invest($uid);
		$data['total_saldo']    = $this->Content_model->get_total_saldo($uid);

		$data['list_redeem']    = $this->Content_model->get_list_myredeem($uid);
		

		$data['pages']    = 'v_redeem';
		$this->load->view('template', $data);
	}

	function submit_()
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$uid = htmlentities($_SESSION['_bkduser_']);

			if (!empty($uid) && trim($uid) !='')
			{
				$post = $this->input->post(NULL, TRUE);
				
				$nowdate     = date('Y-m-d');
				$nowdatetime = date('Y-m-d H:i:s');
				$no_rekening = trim($post['nomor_rekening']);
				$bank_name   = trim($post['bank_name']);
				$jml_redeem  = trim($post['jml_redeem']);

				$walletdata = $this->Wallet_model->get_wallet_bymember($uid);
				$memberdata  = $this->Member_model->get_member_byid_less($uid);

				if ( $no_rekening != '' && $bank_name != '' && $jml_redeem != '' && strlen($jml_redeem)> 5)
				{
					$filter = explode('.', $jml_redeem);
					$total_redeem = str_replace(',', '', $filter[0]);

					// cek saldo
					if ( is_array($memberdata) && is_array($walletdata) && isset($walletdata['Amount']) > 0 && $walletdata['Amount'] > $total_redeem)
					{
						$prefixID    = 'RDM-';
						$orderID     = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,10));
				        $exist_order = $this->Content_model->check_topup_code($orderID);	// Cek if order ID exist on Database
						
						// jika order ID sudah ada di Database, generate lagi tambahkan datetime
						if (is_array($exist_order) && count($exist_order) > 0 )
						{
							$orderID = $prefixID.$uid.strtoupper(substr(uniqid(sha1(time().$uid)),0,3)).date('YmdHis');
						}

						$inred['redeem_kode']           = $orderID;
						$inred['redeem_amount']         = antiInjection($total_redeem);
						$inred['redeem_nomor_rekening'] = antiInjection($no_rekening);
						$inred['redeem_nama_bank']      = antiInjection($bank_name);
						$inred['redeem_date']           = $nowdatetime;
						$inred['redeem_status']         = 'pending';
						$inred['redeem_member_id']      = $uid;
						$inred['redeem_id_pengguna']    = $memberdata['Id_pengguna'];

						$inserted = $this->Content_model->insert_redeem($inred);

						if ($inserted) {
							$this->session->set_userdata('message','Pengajuan Redeem sukses.');
							$this->session->set_userdata('message_type','success');
						}else{
							$this->session->set_userdata('message','Error Redeem!');
							$this->session->set_userdata('message_type','error');
						}
					}else{
						$this->session->set_userdata('message','Pengajuan Redeem Gagal. Jumlah redeem tidak boleh melebihi batas Saldo.');
						$this->session->set_userdata('message_type','error');
					}
				}else{
					$this->session->set_userdata('message','Pengajuan Redeem Gagal. Isilah formulir redeem dengan benar!');
					$this->session->set_userdata('message_type','error');
				}
				
			}

			redirect('redeem');
		}
	}
	
}