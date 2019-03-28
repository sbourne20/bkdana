<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

if($_SERVER['HTTP_HOST']=='localhost')
{
    $config['doc_root'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
    $config['doc_root'] .= "://".$_SERVER['HTTP_HOST'];
    $config['doc_root'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

    $config['data_dir']    = $_SERVER['DOCUMENT_ROOT'] . '/data-bkd/';
    $config['attach_dir']  = $_SERVER['DOCUMENT_ROOT'] . '/data-file-bkd/';
    $config['img_baseurl'] = 'http://149.129.213.30/';

}else{  // LIVE
    $config['doc_root'] = "https://".$_SERVER['HTTP_HOST'];
    $config['doc_root'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

    $config['data_dir']    = '/var/www/html/data-bkd/';
    $config['attach_dir']  = '/var/www/html/data-file-bkd/';
    $config['img_baseurl'] = 'http://149.129.213.30/';
}

// upload path
$config['images_dir']         = $config['data_dir'] . 'images/';
$config['kilat_images_dir']   = $config['images_dir'] . 'pinjaman/kilat/';
$config['mikro_images_dir']   = $config['images_dir'] . 'pinjaman/mikro/';
$config['pendana_images_dir'] = $config['images_dir'] . 'pendana/';
$config['member_images_dir']  = $config['images_dir'] . 'member/';
$config['agent_images_dir']  = $config['images_dir'] . 'agent/';

$config['images_uri']   = $config['img_baseurl'] . "data-bkd/images/agent/";

$config['Firebase_API_KEY'] = '';

$config['mail_username']      = 'bkdanafinansial@gmail.com';
$config['mail_password']      = 'master177';
$config['bank_tujuan']        = 'Bank CIMB';

$config['minimum_topup']      = '100000';
$config['minimum_grade']      = '95';
$config['bkd_telp']           = '+62 21 83784354';
$config['bkd_email']          = 'cs@bkdana.id';

// Veritrans API 
$config['vMerchant_id'] = 'G077099250';
$config['vClient_key']  = 'SB-Mid-client-3L-CVOaUEZHeshIs'; // Sandbox
$config['vServer_key']  = 'SB-Mid-server-7UDjQBASCcq-WJdzM-nJBPZZ'; // sandbox
