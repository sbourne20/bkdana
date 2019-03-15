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

function share_socmed()
{
    $html = '<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-589839ccb521630f"></script>
        <div class="addthis_inline_share_toolbox"></div>';
    return $html;
}

function RandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
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

function antiInjection($str) {
    $r = stripslashes(strip_tags(htmlspecialchars($str, ENT_QUOTES)));
    return $r;
}

function kirim_email($from, $from_title, $to, $subject, $content)
{
    include(APPPATH.'libraries/phpmailer/5.2.23/PHPMailerAutoload.php');
    $mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->Host        = '10.10.156.29';
    $mail->Port        = 25;
    $mail->IsHTML(true);
    $mail->SetFrom($from, $from_title);
    $mail->AddAddress($to);
    $mail->Subject     = $subject;
    $mail->AltBody     = 'To view the message, please use an HTML compatible email viewer!';
    $mail->MsgHTML($content);
    $mail->SMTPDebug   = 0;
    $mail->Send();
}
function status_unauthorized()
{
    $data['response'] = [
        'response' => 'fail',
        'status'   => 401,
        'message'  => 'Unauthorized',
    ];
    $data['http_status'] = 401;
    return $data;
}
function status_forbidden()
{
    $response = [
        'response' => 'fail',
        'status'   => 403,
        'message'  => 'Forbidden',
    ];
    $http_status = 403;
}

function set_ranking_pengguna($uid, $logintype, $tipe_peminjam=0)
{
    $CI =& get_instance();

    // tentukan tipe user
    if ($logintype == 1){

        // tentukan tipe peminjam: kilat atau mikro (saat register)
        if ($tipe_peminjam == 1){
            $result = $CI->Content_model->get_data_peminjam_kilat_rows($uid);
        }else{
            $result = $CI->Content_model->get_data_peminjam_mikro_rows($uid);
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
