<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

if($_SERVER['HTTP_HOST']=='localhost' or $_SERVER['HTTP_HOST']=='192.168.1.86')
{
    $config['doc_root'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
    $config['doc_root'] .= "://".$_SERVER['HTTP_HOST'];
    $config['doc_root'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

    $config['data_dir']    = $_SERVER['DOCUMENT_ROOT'] . '/data-bkd/';
    $config['attach_dir']  = $_SERVER['DOCUMENT_ROOT'] . '/data-file-bkd/';
    $config['img_baseurl'] = "http://192.168.1.86/";
    $config['images_uri']   = $config['img_baseurl'] . "data-bkd/images/";

}else if($_SERVER['HTTP_HOST']=='149.129.213.30')
{
	$config['doc_root'] = "http://".$_SERVER['HTTP_HOST'];
    $config['doc_root'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

    $config['data_dir']    = '/var/www/html/data-bkd/';
    $config['attach_dir']  = '/var/www/html/data-file-bkd/';
    $config['img_baseurl'] = 'http://149.129.213.30/';
    $config['images_uri']   = $config['img_baseurl'] . "data-bkd/images/";

}else{  // LIVE
    $config['doc_root'] = "https://".$_SERVER['HTTP_HOST'];
    $config['doc_root'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

    $config['data_dir']    = '/var/www/html/data-bkd/';
    $config['attach_dir']  = '/var/www/html/data-file-bkd/';
    $config['img_baseurl'] = 'https://bkdana.id/';
    $config['images_uri']   = $config['img_baseurl'] . "images-data/";
}

// upload path
$config['images_dir']         = $config['data_dir'] . 'images/';
$config['kilat_images_dir']   = $config['images_dir'] . 'pinjaman/kilat/';
$config['mikro_images_dir']   = $config['images_dir'] . 'pinjaman/mikro/';
$config['pendana_images_dir'] = $config['images_dir'] . 'pendana/';
$config['member_images_dir']  = $config['images_dir'] . 'member/';



$config['Firebase_API_KEY'] = '';

$config['mail_username']      = 'bkdanafinansial@gmail.com';
$config['mail_password']      = 'master177';
$config['bank_tujuan']        = 'Bank CIMB';

$config['minimum_mikro']      = '1000000';
$config['minimum_topup']      = '100000';
$config['minimum_grade']      = '95';
$config['bkd_telp']           = '+62 21 83784354';
$config['bkd_email']          = 'cs@bkdana.id';

// Veritrans API 
$config['vMerchant_id'] = 'G077099250';
$config['vClient_key']  = 'SB-Mid-client-3L-CVOaUEZHeshIs'; // Sandbox
$config['vServer_key']  = 'SB-Mid-server-7UDjQBASCcq-WJdzM-nJBPZZ'; // sandbox
