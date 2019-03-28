<?php
function images_uri(){
    $CI =& get_instance();
    return $CI->config->item('images_uri');
}
function template_uri(){
    $CI =& get_instance();
    return $CI->config->item('template_uri');
}
function asset_uri(){
    $CI =& get_instance();
    return $CI->config->item('asset_uri');
}
function add_css($css=''){
    $CI =& get_instance();
    if (trim($css)=="") return false;
    $string = "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$CI->config->item('template_uri').$css."\" media=\"all\">\n";
    return $string;
}
function add_js($js=''){
    $CI =& get_instance();
    if (trim($js)=="") return false;
    $string = "<script type=\"text/javascript\" src=\"".$CI->config->item('template_uri').$js."\"></script>\n";
    return $string;
}
function in($text){
    $replaced = array("\n","\t","\r");
    if($text=='') return '';
    $text = preg_replace('/[^(\x20-\x7F)]*/','', $text); /* remove non-ascii */
    return htmlentities(str_replace(array_values($replaced),'',$text));
}
function _d($str)
{
    echo "<pre>";
    print_r($str);
    echo "</pre>";
}
function mkdir_r($dirName, $rights=0755){
    $dirs = explode('/', $dirName);
    $dir='';
    foreach ($dirs as $part) {
        //echo $part;
        $dir.=$part.'/';
        if (!is_dir($dir) && strlen($dir)>0)
            mkdir($dir, $rights);
    }
}
function create_thumb_relative($fullpath, $setwidth, $setheight)
{
    // ------ Create Thumbnail -------
    $CI =& get_instance();
    $CI->load->library('image_lib');
    $CI->image_lib->clear();

    // create image thumbnail
    list($width, $height, $type, $attr) = getimagesize($fullpath);
    
    // landscape
    $config['image_library']  = 'gd2';
    $config['source_image']   = $fullpath;
    $config['create_thumb']   = true;
    $config['maintain_ratio'] = true;
    //$config['new_image']      = $destinationpath;

    // we set fix image width & height
    $config['width']  = $setwidth;
    $config['height'] = $setheight;
    $CI->image_lib->initialize($config);
    
    if (!$CI->image_lib->resize()) {
        echo $CI->image_lib->display_errors();
    }
}
// Cek -> jika akses role ada dlm sebuah array multidimensi (from mysql result)
// maka return TRUE
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
function tanggal_indo($tanggal)
{
	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split = explode('-', $tanggal);
	return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
}
function RandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
function antiInjection($str) {
    $r = stripslashes(strip_tags(htmlspecialchars($str, ENT_QUOTES)));
    return $r;
}
function grade_percentage($id)
{
    $CI =& get_instance();
    $data = $CI->Content_model->get_user_grade($id);
    return $data['completeness_profile'];
}

function set_ranking_pengguna($uid, $logintype, $tipe_peminjam=0)
{
    $CI =& get_instance();

    // tentukan tipe user
    if ($logintype == 1){

        // tentukan tipe peminjam: kilat atau mikro (saat register)
        if ($tipe_peminjam == 1){
            $result = $CI->Content_model->get_data_peminjam_kilat_rows($uid);
        }else if($tipe_peminjam == 2){
            $result = $CI->Content_model->get_data_peminjam_mikro_rows($uid);
        }
        else if($tipe_peminjam == 3){
            $result = $CI->Content_model->get_data_peminjam_agri_rows($uid);
        }
        
    }else{
        $result = $CI->Content_model->get_data_pendana_rows($uid);
    }

    $totalloop   = count($result);
    $empty_count = 0;
    $exist_count = 0;
        
        foreach ($result as $key => $value) {

            if( $value=='' || $value === NULL)
            {
                // echo $key . '----------'.$value;
                // echo '<br>';
                $empty_count = $empty_count+1;
            }else{
                // echo $key.'='.$value;   
                // echo '<br>';
                $exist_count = $exist_count+1;
            }
        }

        //$ret =  ((int)(100*(1-$empty_count/($totalloop-1)))).'% complete';
        $ranking =  (int)(($exist_count/$totalloop)*100);

        if ($ranking >= 100)
        {
            $grade_user = 'A';
        }else if ($ranking >= 80 AND $ranking < 100) {
            $grade_user = 'B';
        }else if ($ranking >= 60 AND $ranking < 80) {
            $grade_user = 'C';
        }else if ($ranking >= 40 AND $ranking < 60) {
            $grade_user = 'D';
        }else{
            $grade_user = 'E';
        }

        $ret['ranking'] = $ranking;
        $ret['grade']   = $grade_user;
    
    return $ret;
}

function hitung_umur($tgl_lahir)
{
    //date in mm/dd/yyyy format
    $birthDate = date('m/d/Y', strtotime($tgl_lahir));
    //explode the date to get month, day and year
    $birthDate = explode("/", $birthDate);
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
    ? ((date("Y") - $birthDate[2]) - 1)
    : (date("Y") - $birthDate[2]));
    return $age;
}
function set_cookies_login($memberdata)
{
    $CI =& get_instance();
    if (isset($memberdata['mum_email']) && isset($memberdata['id_mod_user_member'])) {
        $data = array();
        $data['_bkdlog_']   = 1;    // login status
        $data['_bkdmail_']  = $memberdata['mum_email'];
        $data['_bkduser_']  = $memberdata['id_mod_user_member'];
        $data['_bkdname_']  = $memberdata['mum_fullname'];
        $data['_bkdtype_']  = $memberdata['mum_type'];
        $CI->session->set_userdata($data);
    }
    return TRUE;
}
function parseDateTimeIndex($date){
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

function create_pdf($html, $ordercode, $filename, $title)
{
    $CI =& get_instance();
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('BKDana');
    $pdf->SetTitle($title);
    $pdf->SetSubject($title);

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

    $pdf->SetMargins(12, 15, 16,true);

    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(0);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set default font subsetting mode
    $pdf->setFontSubsetting(true);

    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('helvetica', '', 9);

    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();

    // set text shadow effect
    //$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

    ob_start();
    $pdf->writeHTML($html, true, false, false, false, '');
    ob_end_clean();
    // ---------------------------------------------------------

    $output_file = $CI->config->item('attach_dir') . $filename;

    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    //$pdf->Output($ordercode. '.pdf', 'I');
    $pdf->Output($output_file,'F');

    $ret = array(
            'filename'    => $filename,
            'output_file' =>$output_file
            );

    return $ret;
}
?>
