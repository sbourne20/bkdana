<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Settings Fronteend Config
|--------------------------------------------------------------------------
*/
$config['doc_root'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$config['doc_root'] .= "://".$_SERVER['HTTP_HOST'];
$config['doc_root'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

//$config['data_dir']    = $_SERVER['DOCUMENT_ROOT'] . '/data-bkd/';
if($_SERVER['HTTP_HOST']=='localhost')
{
	$config['data_dir']    = $_SERVER['DOCUMENT_ROOT'] . '/data-bkd/';

}else{	// LIVE
	$config['data_dir']    = '/var/www/html/data-bkd/';
}

// upload path
$config['images_dir']         = $config['data_dir'] . 'images/';
$config['kilat_images_dir']   = $config['images_dir'] . 'pinjaman/kilat/';
$config['mikro_images_dir']   = $config['images_dir'] . 'pinjaman/mikro/';
$config['pendana_images_dir'] = $config['images_dir'] . 'pendana/';
$config['member_images_dir']  = $config['images_dir'] . 'member/';

$config['img_baseurl']  = $config['doc_root'];
$config['images_uri']   = $config['img_baseurl'] . "images-data/";
$config['page_img_uri'] = $config['images_uri'] . 'pages/';

$config['template_frontend'] = 'version1';
$config['id_order']          = date("ymdHis");
$config['template_uri']      = $config['doc_root'] . 'assets/';
// Email setting
$config['mail_username']      = 'bkdanafinansial@gmail.com';
$config['mail_password']      = 'master177';