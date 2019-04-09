<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller']    = 'home';
$route['404_override']          = '';
$route['translate_uri_dashes']  = FALSE;

$route['pendanaan']    		   = 'page/index';
$route['pinjaman']     		   = 'page/pinjaman';
$route['tentang-kami'] 		   = 'page/tentang';
$route['bantuan'] 	   		   = 'page/bantuan';
$route['syarat-ketentuan'] 	   = 'page/syarat_ketentuan';
$route['kebijakan-privasi']    = 'page/kebijakan_privasi';

/*$route['daftar-pinjaman-kilat']      = 'daftar_peminjam/index';
$route['submit-daftar-pinjam-kilat'] = 'daftar_peminjam/submit_daftar';
$route['daftar-pinjaman-mikro']      = 'daftar_peminjam/index';
$route['submit-daftar-pinjam-mikro'] = 'daftar_peminjam/submit_daftar';
$route['daftar-pinjaman-usaha']      = 'daftar_peminjam/index';
$route['submit-daftar-pinjam-usaha'] = 'daftar_peminjam/submit_daftar';*/

$route['input-otp']    = 'input_otp/index';
$route['submit-login'] = 'login/submit_login';
$route['logoff']       = 'login/logoff';
$route['otp-login']    = 'login/login_otp';

$route['register-pinjaman-kilat']        = 'pinjaman/daftar_kilat';
$route['register-pinjaman-mikro']        = 'pinjaman/daftar_mikro';
//register agri
$route['register-pinjaman-agri']         = 'pinjaman/daftar_agri';
//batas register agri
$route['submit-register-pinjaman-kilat'] = 'pinjaman/submit_reg_kilat';
$route['submit-register-pinjaman-mikro'] = 'pinjaman/submit_reg_mikro';
//register agri
$route['submit-register-pinjaman-agri'] = 'pinjaman/submit_reg_agri';
//batas register agri
$route['submit-register'] = 'register/submit_';

$route['formulir-pinjaman-kilat'] = 'pinjaman/kilat';
$route['formulir-pinjaman-mikro'] = 'pinjaman/mikro';
$route['formulir-pinjaman-usaha'] = 'pinjaman/usaha';
$route['formulir-pinjaman-agri']  = 'pinjaman/agri';


$route['submit-pinjaman-kilat']   = 'pinjaman/submit_p_kilat';
$route['submit-pinjaman-mikro']   = 'pinjaman/submit_p_mikro';
$route['submit-pinjaman-agri']    = 'pinjaman/submit_p_agri';
$route['submit-pinjaman-usaha']   = 'pinjaman/submit_p_usaha';

$route['register-pendana']        = 'pendanaan/index';
$route['formulir-pendana']        = 'pendanaan/show_form_pendanaan';
$route['submit-register-pendana'] = 'pendanaan/submit_register';
$route['submit-formulir-pendana'] = 'pendanaan/submit_form_pendana';

$route['transaksi']                  = 'transaksi/index';
$route['transaksi/page']             = 'transaksi/index';
$route['transaksi/page/(.*)']        = 'transaksi/index/$1';
$route['transaksi/detail-pendana']   = 'transaksi_pendana/detail';
$route['transaksi-pendana']          = 'transaksi/pendanaan_index';
$route['submit-bayar-cicilan-kilat'] = 'transaksi/submit_cicilan_kilat';
$route['submit-bayar-cicilan-mikro'] = 'transaksi/submit_cicilan_mikro';
$route['submit-bayar-cicilan-agri'] = 'transaksi/submit_cicilan_agri';
//$route['rekening-koran/detail-rekening-koran']   = 'rekening_koran/detail';

//rekening koran
$route['detail-rekening-koran-kredit-kilat']  = 'rekening_koran/detail_kredit_kilat';
$route['detail-rekening-koran-debet-kilat'] = 'rekening_koran/detail_debet_kilat';

$route['detail-rekening-koran-kredit-mikro'] = 'rekening_koran/detail_kredit_mikro';
$route['detail-rekening-koran-debet-mikro'] = 'rekening_koran/detail_debet_mikro';

$route['detail-rekening-koran-kredit-pendana'] = 'rekening_koran/detail_kredit_pendana';
$route['detail-rekening-koran-debet-pendana'] = 'rekening_koran/detail_debet_pendana';
//batas rekening koran*/

$route['ubah-profil']        = 'member/ubah_profil';
$route['lengkapi-profil']    = 'member/ubah_profil';
$route['submit-ubah-profil'] = 'member/submit_ubah_profil';

$route['top-up']             = 'top_up/index';

$route['daftar-peminjam']            = 'daftar_peminjam/index';
$route['daftar-peminjam/page/(.*)']  = 'daftar_peminjam/index/$1';
$route['daftar-peminjam-detail']     = 'daftar_peminjam/detail';
$route['submit-pembiayaan-pinjaman'] = 'daftar_peminjam/submit_pendanaan';

$route['submit-reset-password']  = 'password_reset/send_link_reset';
$route['send-password-message']  = 'password_reset/send_password_message';
$route['page-reset-password']    = 'password_reset/view_reset_pass';
$route['reset-password-failed']  = 'password_reset/final_message';
$route['reset-password-expired'] = 'password_reset/final_message';
$route['reset-password-success'] = 'password_reset/final_message';
$route['submit-new-password']    = 'password_reset/submit_new_password';

$route['resend-email-aktivasi']    = 'aktivasi/resend_email';
//tambahan baru aktivasi
$route['email-aktivasi']    = 'aktivasi/index';
//batas aktivasi

$route['detail-profil-peminjam'] = 'rekening_koran/detail_profil_peminjam'; 

$route['fileload'] = 'file_loader/file';

$route['approval-agri'] = 'transaksi/approve_agri';