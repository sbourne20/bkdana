<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

// --- Area penggunaan voucher - based on Kabupaten id ---
$config['voucher_coverage_smn'] = array();
$config['voucher_coverage_rmc'] = array('100','101','102','103','104','119','121','124','126','127','128','132');

// Kecamatan ID - area ini tidak bisa pakai voucher
// Tamansari, Sukaraja, Sukajaya, Pamijahan, Nanggung, Megamendung, Cisarua, Cijeruk, Cigombong, Ciawi
$config['voucher_rmc_block_kecamatan'] = array('106','104','102','97','96','95','83','80','78','75');
$config['voucher_smn_block_kecamatan'] = array('');