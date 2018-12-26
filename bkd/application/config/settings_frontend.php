<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Settings Fronteend Config
|--------------------------------------------------------------------------
*/

/*$config['doc_root'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$config['doc_root'] .= "://".$_SERVER['HTTP_HOST'];
$config['doc_root'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
*/

if($_SERVER['HTTP_HOST']=='localhost')
{
	$config['doc_root'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
	$config['doc_root'] .= "://".$_SERVER['HTTP_HOST'];
	$config['doc_root'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

	//foto start
	$config['showphoto']    = 'http://'.$_SERVER['HTTP_HOST']. '/data-bkd/';
	//foto end

	$config['data_dir']    = $_SERVER['DOCUMENT_ROOT'] . '/data-bkd/';
	$config['attach_dir']  = $_SERVER['DOCUMENT_ROOT'] . '/data-file-bkd/';

}else{	// LIVE
	$config['doc_root'] = "https://".$_SERVER['HTTP_HOST'];
	$config['doc_root'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

	//foto start
	$config['showphoto']    = 'https://'.$_SERVER['HTTP_HOST']. '/data-bkd/';
	//foto end

	$config['data_dir']    = $_SERVER['DOCUMENT_ROOT'] . '/data-bkd/';
	$config['attach_dir']  = $_SERVER['DOCUMENT_ROOT'] . '/data-file-bkd/';
}

// upload path
$config['images_dir']         = $config['data_dir'] . 'images/';
$config['kilat_images_dir']   = $config['images_dir'] . 'pinjaman/kilat/';
$config['mikro_images_dir']   = $config['images_dir'] . 'pinjaman/mikro/';
$config['pendana_images_dir'] = $config['images_dir'] . 'pendana/';
$config['member_images_dir']  = $config['images_dir'] . 'member/';

$config['img_baseurl']  = $config['doc_root'];
$config['images_uri']   = $config['showphoto'] . "images";
$config['page_img_uri'] = $config['images_uri'] . 'pages/';

$config['template_frontend'] = 'version1';
$config['id_order']          = date("ymdHis");
$config['template_uri']      = $config['doc_root'] . 'assets/';
// Email setting
$config['mail_username']      = 'bkdanafinansial@gmail.com';
$config['mail_password']      = 'master177';

$config['fb_app_id']          = '1733360370088297';
$config['fb_kit_secret']      = 'd827d9e9c9802d140f27a96751fb9811';
$config['fb_kit_token']       = '0fa9339955b3ac6efbb5f3bb7738dd02';

$config['minimum_topup']      = '100000';
$config['minimum_grade']      = '95';
$config['bkd_telp']           = '+62 21 83784354';
$config['bkd_email']          = 'cs@bkdana.id';

// Veritrans API 
$config['vMerchant_id'] = 'G077099250';
$config['vClient_key']  = 'SB-Mid-client-3L-CVOaUEZHeshIs'; // Sandbox
$config['vServer_key']  = 'SB-Mid-server-7UDjQBASCcq-WJdzM-nJBPZZ'; // sandbox