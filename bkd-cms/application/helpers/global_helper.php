<?php

/**
 * Global function for application
 *
 */

function check_role_access($array_data, $akses)
{
	if (isset($array_data[$akses]))
	{
		$ret = TRUE;
	}else{
		$ret = FALSE;
	}
		return $ret;
}

function is_have_access($access_name, $access=array())
{
	if(isset($access[$access_name]))
		return true;
	else
		return false;
}

function rupiah($s){
	return number_format($s,0,',','.');
}

function _d($str)
{
	echo "<pre>";
	print_r($str);
	echo "</pre>";
}

function get_username()
{
	$CI =& get_instance();

	$CI->load->model('user_model');
	$id   = $CI->session->userdata('current_user');
	$data = $CI->user_model->get_user_by_id($id);
	echo $data->username;
}

function time_to_unixts($time){
	$p = explode(':',$time);
	$h = isset($p[0]) ? (int) $p[0] : 0;
	$m = isset($p[1]) ? (int) $p[1] : 0;
	$s = isset($p[2]) ? (int) $p[2] : 0;
	return ($h*3600)+($m*60)+$s;
}

function id_date($date){
	$str = strtotime($date);
	$m = array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
	return date('d', $str).' '.$m[intval(date('m', $str))].' '.date('Y', $str);
}

function cdate($date,$format='d M Y'){
	$str = strtotime($date);
	return date($format,$str);
}

function tgl($date, $time = FALSE, $day = FALSE){
	$str = strtotime($date);
	$m = array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
	$aday = array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu');
	$_time = '';
	$_day = '';
	if($time) $_time = date(', H:i',$str);
	if($day) $_day = isset($aday[date('w',$str)]) ? $aday[date('w',$str)].', ' : '';
	return $_day.date('d', $str).' '.$m[intval(date('m', $str))].' '.date('Y', $str).$_time;
}

function toMonth($month){
	$month = intval($month);
	$m = array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
	return $m[$month];
}

function search_filter($str){
	return trim(rtrim(ltrim(urlencode($str))));
}

function in($text){
	$replaced = array("\n","\t","\r");
	if($text=='') return '';
	$text = preg_replace('/[^(\x20-\x7F)]*/','', $text); /* remove non-ascii */
	return htmlentities(str_replace(array_values($replaced),'',$text));
}

function article_sanitize($text)
{
	$tags = '<div><strong><b><em><ul><ol><li><a><p><br><abbr><blockquote><dfn><mark><pre><code><kbd><samp><q><small><sub><sup><figure><table><thead><tbody><tfoot><tr><th><td><h1><h2><h3><h4><h5><h6>';
	$text = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $text);
	$replacer = array('<p>&nbsp;</p>'=>'');
	$text = str_replace(array_keys($replacer),array_values($replacer),$text);
	return strip_tags($text,$tags);
}

function in_arr($arr){
	if( ! is_array($arr)) return in($arr);
	$tmp = array();
	if(count($arr)>0){
		foreach($arr as $k=>$v){
			$tmp[$k] = in($v);
		}
	}
	return $tmp;
}

function out($text){
	if($text=='') return '';
	return stripslashes(html_entity_decode($text));
}

function ext($str){
	$t = explode('.',$str);
	$c = count($t)-1;
	return $t[$c];
}

function time_span($t1,$t2){
	$t1 = (int) strtotime($t1);
	$t2 = (int) strtotime($t2);
	$t = $t2-$t1;
	if($t<0) $t=0;
	$res = secondsToTime($t);
	$ret = '';
	if( ! empty($res['h']) OR ! empty($res['m']) ){
		$ret = str_pad($res['h'], 2, "0", STR_PAD_LEFT).':'.str_pad($res['m'], 2, "0", STR_PAD_LEFT);
	}

	return $ret;
}

function secondsToTime($seconds)
{
    // extract hours
    $hours = floor($seconds / (60 * 60));
	
    // extract minutes
    $divisor_for_minutes = $seconds % (60 * 60);
    $minutes = floor($divisor_for_minutes / 60);

    // extract the remaining seconds
    $divisor_for_seconds = $divisor_for_minutes % 60;
    $seconds = ceil($divisor_for_seconds);

    // return the final array
    $obj = array(
        "h" => (int) $hours,
        "m" => (int) $minutes,
        "s" => (int) $seconds,
    );

    return $obj;
}

function get_query_string()
{
	$qs = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
	return $qs;
}

function kekata($x) {
	$x = abs($x);
	$angka = array("", "satu", "dua", "tiga", "empat", "lima",
	"enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($x <12) {
		$temp = " ". $angka[$x];
	} else if ($x <20) {
		$temp = kekata($x - 10). " belas";
	} else if ($x <100) {
		$temp = kekata($x/10)." puluh". kekata($x % 10);
	} else if ($x <200) {
		$temp = " seratus" . kekata($x - 100);
	} else if ($x <1000) {
		$temp = kekata($x/100) . " ratus" . kekata($x % 100);
	} else if ($x <2000) {
		$temp = " seribu" . kekata($x - 1000);
	} else if ($x <1000000) {
		$temp = kekata($x/1000) . " ribu" . kekata($x % 1000);
	} else if ($x <1000000000) {
		$temp = kekata($x/1000000) . " juta" . kekata($x % 1000000);
	} else if ($x <1000000000000) {
		$temp = kekata($x/1000000000) . " milyar" . kekata(fmod($x,1000000000));
	} else if ($x <1000000000000000) {
		$temp = kekata($x/1000000000000) . " trilyun" . kekata(fmod($x,1000000000000));
	}
		return $temp;
}

function terbilang($x, $style=4) {
	if($x<0) {
		$hasil = "minus ". trim(kekata($x));
	} else {
		$hasil = trim(kekata($x));
	}
	switch ($style) {
		case 1:
			$hasil = strtoupper($hasil);
			break;
		case 2:
			$hasil = strtolower($hasil);
			break;
		case 3:
			$hasil = ucwords($hasil);
			break;
		default:
			$hasil = ucfirst($hasil);
			break;
	}
	return $hasil;
}

function valid_url($str){
	if( $str == '' ) return FALSE;
	if( preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $str) ){
		return TRUE;
	}
	return FALSE;
}

function get_image($text)
{
	$matches = array();
	preg_match_all('!http://.+\.(?:jpe?g|png|gif)!Ui' , $text , $matches);
	return isset($matches[0][0]) ? basename($matches[0][0]) : '';
}

function phpthumb($path, $width, $height, $zc=1){
	$ci = & get_instance();
	if($ci->config->item('rewrite_thumb'))
		return base_url() . 'thumb/'.$width.'x'.$height.'/'.$zc.'/'.$path;
	else
		return base_url() . 'thumb/index.php?thumb='.$width.'x'.$height.'/'.$zc.'/'.$path;
}

function v(){ return rand('11111','99999'); }

function _current_url()
{
    $CI =& get_instance();
    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
}

function memory_usage() {
	$mem_usage = memory_get_usage(TRUE);   
	if ($mem_usage < 1024) return $mem_usage." bytes";
	elseif ($mem_usage < 1048576) return round($mem_usage/1024,2)." kilobytes";
	else return round($mem_usage/1048576,2)." megabytes";
}
function antiInjection($str) {
    $r = stripslashes(strip_tags(htmlspecialchars($str, ENT_QUOTES)));
    return $r;
}