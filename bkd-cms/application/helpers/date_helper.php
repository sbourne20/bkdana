<?php
function parseDateTime($date){
/*
param : yyyy-mm-dd hh:ii:ss
*/

    $int = preg_match("/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/",$date,$match);
    if (!$int) return false;
    $data['year']   = $match[1];
    $data['month']  = $match[2];
    $data['day']    = $match[3];
    $data['hour']   = $match[4];
    $data['minute'] = $match[5];
    $data['second'] = $match[6];
    $data['day_of_week'] = date("N",mktime(0,0,0,intval($data['month']),intval($data['day']),intval($data['year'])));
    $data['month_ind_name'] = getIndMonth(intval($data['month']));
    $data['day_ind_name'] = getIndDay($data['day_of_week']);
    $data['month_eng_name'] = getEngMonth(intval($data['month']));
    $data['day_eng_name'] = getEngDay($data['day_of_week']);
    return $data;
}
function parseDateTimeIndex($date){
/*
param : yyyy-mm-dd hh:ii:ss
*/

    $int = preg_match("/(\d{4})-(\d{2})-(\d{2})/",$date,$match);
    if (!$int) return false;
    $data['year']   = $match[1];
    $data['month']  = $match[2];
    $data['day']    = $match[3];
    $data['day_of_week'] = date("N",mktime(0,0,0,intval($data['month']),intval($data['day']),intval($data['year'])));
    $data['month_ind_name'] = getIndMonth(intval($data['month']));
    $data['day_ind_name'] = getIndDay($data['day_of_week']);
    return $data;
}
function getIndDay($int="1"){
    switch($int){
        case "7":
            $strDay = "Minggu";
        break;
        case "6":
            $strDay = "Sabtu";
        break;
        case "5":
            $strDay = "Jum'at";
        break;
        case "4":
            $strDay = "Kamis";
        break;
        case "3":
            $strDay = "Rabu";
        break;
        case "2":
            $strDay = "Selasa";
        break;
        case "1":
        default:
            $strDay = "Senin";
        break;
    }
    return $strDay;
}
function getEngDay($int="1"){
    switch($int){
        case "7":
            $strDay = "Sunday";
        break;
        case "6":
            $strDay = "Saturday";
        break;
        case "5":
            $strDay = "Friday";
        break;
        case "4":
            $strDay = "Thursday";
        break;
        case "3":
            $strDay = "Wednesday";
        break;
        case "2":
            $strDay = "Tuesday";
        break;
        case "1":
        default:
            $strDay = "Monday";
        break;
    }
    return $strDay;
}
function getIndMonth($int=1){
    $data[1] = "Januari";
    $data[2] = "Februari";
    $data[3] = "Maret";
    $data[4] = "April";
    $data[5] = "Mei";
    $data[6] = "Juni";
    $data[7] = "Juli";
    $data[8] = "Agustus";
    $data[9] = "September";
    $data[10] = "Oktober";
    $data[11] = "November";
    $data[12] = "Desember";
    $intint = intval($int);
    if ($intint <= 12 && $intint >= 1 )
        return $data[$intint];
    else
        return false;
}

function getEngMonth($int=1){
    $data[1] = "January";
    $data[2] = "February";
    $data[3] = "March";
    $data[4] = "April";
    $data[5] = "May";
    $data[6] = "June";
    $data[7] = "July";
    $data[8] = "August";
    $data[9] = "September";
    $data[10] = "October";
    $data[11] = "November";
    $data[12] = "December";
    $intint = intval($int);
    if ($intint <= 12 && $intint >= 1 )
        return $data[$intint];
    else
        return false;
}


function getNowTime() {
    $waktu    =  date( 'Y-m-d H:i:s', time());
    return   $waktu; 
}

function formatingNowTime(){
    $waktu    = getNowTime();
    return parseDateTime($waktu);
}
function getSimpleIndonesianDate($date=null) {
    
    $waktu    = (($date == null )) ? formatingNowTime() : parseDateTime($date); 
    
    return $waktu['day_ind_name'].", ". $waktu['day']."/".$waktu['month']."/".$waktu['year'];      
}
function getNowYear() {
    $waktu    =  date( 'Y', time());
    return   $waktu; 
}

//2009-07-10T11:53:50Z

function convertGMTdate($string_date, $plus_hour=7) {

    $tmp1    = explode("T", $string_date);
    $ymd    =  $tmp1[0];
    $tmp2    = explode("Z",$tmp1[1]);
    $hms     = $tmp2[0];
    //$string_date2    =$ymd." ".$hms; 
    
    $ymd2    = explode("-",$ymd);
    $y       = $ymd2[0];
    $Mo       = $ymd2[1];
    $d       = $ymd2[2];
    
    $hms2    = explode(":",$hms);
    $h       = $hms2[0];
    $m       = $hms2[1];
    $s       = $hms2[2];       
    
    //echo "string_date :".$string_date;
    
    $dateID     = mktime($h+$plus_hour,$m,$s,$Mo,$d,$y);
    
    $string_date2    = date('Y-m-d H:i:s',$dateID);
    $result    = parseDateTime($string_date2);  
    //echo "<pre>";
    //print_r($result);
    //echo "</pre>";    
    return $result;
}

function convert_date_to_path($string_date,$delimiter='') {
    //echo "string date".$string_date; exit;
    $dateB      = explode(" ",trim($string_date));
    $ymd        = explode("-",$dateB[0]);
    $y          = $ymd[0];
    $m          = $ymd[1];
    $d          = $ymd[2];
    return $y.$delimiter.$m.$delimiter.$d;  
}

function get_list_month($ln = 'id')
{
    switch ($ln) {
        case 'id':
            $m['1'] = 'Januari';
            $m['2'] = 'Februari';
            $m['3'] = 'Maret';
            $m['4'] = 'April';
            $m['5'] = 'Mei';
            $m['6'] = 'Juni';
            $m['7'] = 'Juli';
            $m['8'] = 'Agustus';
            $m['9'] = 'September';
            $m['10'] = 'Oktober';
            $m['11'] = 'November';
            $m['12'] = 'Desember';
            break;
        
        case 'en':
            $m['1'] = 'January';
            $m['2'] = 'February';
            $m['3'] = 'March';
            $m['4'] = 'April';
            $m['5'] = 'May';
            $m['6'] = 'June';
            $m['7'] = 'July';
            $m['8'] = 'August';
            $m['9'] = 'September';
            $m['10'] = 'October';
            $m['11'] = 'November';
            $m['12'] = 'December';
            break;
    }
    return $m;
}

function get_time_ago($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}
function get_day_bahasa($day){
    switch($day){
        case "Sunday":
            $strDay = "Minggu";
        break;
        case "Saturday":
            $strDay = "Sabtu";
        break;
        case "Friday":
            $strDay = "Jum'at";
        break;
        case "Thursday":
            $strDay = "Kamis";
        break;
        case "Wednesday":
            $strDay = "Rabu";
        break;
        case "Tuesday":
            $strDay = "Selasa";
        break;
        case "Monday":
        default:
            $strDay = "Senin";
        break;
    }
    return $strDay;
}
?>