<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config['doc_root'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$config['http']     = $config['doc_root'];
$config['doc_root'] .= "://".$_SERVER['HTTP_HOST'];
$config['doc_root'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

$server_name = $_SERVER['SERVER_ADDR'];
switch ($server_name) {
     case  "10.10.112.8": // Live
        $config['data_dir'] = 'C:/xampp/htdocs/data-bkd/';
        $production         = '1';
        $err_reporting      = "0";
        break;

    default: // Development
        $config['data_dir'] = '/mainData/data-bkd/';
        $production         = '0';
        $err_reporting      = "E_ALL ^ E_NOTICE";
        break;
}

$config['production']        = $production;
$config['err_reporting']     = $err_reporting;

$config['template_uri']      = $config['doc_root'] . 'assets/';
$config['images_uri']        = $config['doc_root'] . 'assets/images/';

$config['images_dir']         = $config['data_dir'] . 'images/';
$config['member_dir']         = $config['images_dir'] . 'member/';

// URL
$config['images_data_uri']        = $config['doc_root'] . "images-data/";

$config['fb_id']                  = '';
$config['fb_secret']              = '';
$config['fbcallback']             = $config['doc_root']. 'fbcallback';

$config['cache']                  = $config['data_dir']. 'cache';
$config['secret_server_key']      = 's143rt487y3ss';
$config['secret_pswd']            = 'Ag4r012p3l4ng1';

$config['Firebase_API_KEY'] = '';

$config['mail_username']      = 'bkdanafinansial@gmail.com';
$config['mail_password']      = 'master177';
