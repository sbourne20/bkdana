<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DataTables_model extends CI_Model
{

	function get_start() {
		$start = 0;
		if (isset($_GET['iDisplayStart'])) {
			$start = intval($_GET['iDisplayStart']);

			if ($start < 0)
				$start = 0;
		}

		return $start;
	}

	function get_rows() {
		$rows = 10;
		if (isset($_GET['iDisplayLength'])) {
			$rows = intval($_GET['iDisplayLength']);
			// if ($rows < 5 || $rows > 500) {
			// 	$rows = 10;
			// }
		}

		return $rows;
	}

	function get_sort_dir() {
		$sort_dir = "ASC";
		$s='';

		if(isset($_GET['sSortDir_0'])) $s=$_GET['sSortDir_0'];

		$sdir = strip_tags($s);
		if (isset($sdir)) {
			if ($sdir != "asc" ) {
				$sort_dir = "DESC";
			}
		}

		return $sort_dir;
	}

	function get_sort($cols=array()) {
		$sCol='';	
		$col = 1;
		if(isset($_GET['iSortCol_0'])) $sCol=$_GET['iSortCol_0'];
		if (isset($sCol)) {
			$col = intval($sCol);
			// if ($col < 0 || $col > 4)
			// 	$col = 0;
		}
		$colName = $cols[$col];

		return $colName;
	}

	function get_echo() {
		$echo = 0;
		if (isset($_GET['sEcho'])) {
			$echo = intval($_GET['sEcho']);
			// if ($rows < 5 || $rows > 500) {
			// 	$rows = 10;
			// }
		}

		return $echo;
	}

}