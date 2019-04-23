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
$route['default_controller'] = 'dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['koperasi']              = 'pendana_internal/index';
$route['koperasi/edit']              = 'pendana_internal/edit';
$route['koperasi/topup']              = 'pendana_internal/topup';

$route['transaksi-pinjaman-kilat-draft']              = 'transaksi_pinjaman_kilat_draft/index';
$route['transaksi-pinjaman-kilat-draft/json']         = 'transaksi_pinjaman_kilat_draft/json';
$route['transaksi-pinjaman-kilat-draft/edit/(.*)']    = 'transaksi_pinjaman_kilat_draft/edit/$1';
$route['transaksi-pinjaman-kilat-draft/approve/(.*)'] = 'transaksi_pinjaman_kilat_draft/approve/$1';
$route['transaksi-pinjaman-kilat-draft/submit_edit']  = 'transaksi_pinjaman_kilat_draft/submit_edit';
$route['transaksi-pinjaman-kilat-draft/detail_transaksi/(.*)'] = 'transaksi_pinjaman_kilat_draft/detail_transaksi/$1';

$route['transaksi-pinjaman-mikro-draft']              = 'transaksi_pinjaman_mikro_draft/index';
$route['transaksi-pinjaman-mikro-draft/json']         = 'transaksi_pinjaman_mikro_draft/json';
$route['transaksi-pinjaman-mikro-draft/edit/(.*)']    = 'transaksi_pinjaman_mikro_draft/edit/$1';
$route['transaksi-pinjaman-mikro-draft/approve/(.*)'] = 'transaksi_pinjaman_mikro_draft/approve/$1';
$route['transaksi-pinjaman-mikro-draft/submit_edit']  = 'transaksi_pinjaman_mikro_draft/submit_edit';
$route['transaksi-pinjaman-mikro-draft/detail_transaksi/(.*)'] = 'transaksi_pinjaman_mikro_draft/detail_transaksi/$1';
//$route['cronjob/find_expired_pinjaman_kilat/(.*)'] = 'cronjob/find_expired_pinjaman_kilat';

//agri
$route['transaksi-pinjaman-agri-draft']               = 'transaksi_pinjaman_agri_draft/index';
$route['transaksi-pinjaman-agri-draft/json']         = 'transaksi_pinjaman_agri_draft/json';
$route['transaksi-pinjaman-agri-draft/edit/(.*)']    = 'transaksi_pinjaman_agri_draft/edit/$1';
$route['transaksi-pinjaman-agri-draft/approve/(.*)'] = 'transaksi_pinjaman_agri_draft/approve/$1';
$route['transaksi-pinjaman-agri-draft/submit_edit']  = 'transaksi_pinjaman_agri_draft/submit_edit';
$route['transaksi-pinjaman-agri-draft/detail_transaksi/(.*)'] = 'transaksi_pinjaman_agri_draft/detail_transaksi/$1';

$route['fileload'] = 'file_loader/file';