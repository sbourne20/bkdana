<?php

function metaname_facebook($meta = array(), $newline = "\n"){
    $str = '';
    foreach ($meta as $key => $val) {
        $str .= '<meta property=\'og:'.$key.'\' content=\''.$val.'\' />'.$newline;
    }
    return $str;
}

function add_js($js=''){
    $CI =& get_instance();
    if (trim($js)=="") return false;
    $string = "<script type=\"text/javascript\" src=\"".$CI->config->item('template_uri').$js."\"></script>\n";
    return $string;
}
function add_external_js($js=''){
    $CI =& get_instance();
    if (trim($js)=="") return false;
    $string = "<script type=\"text/javascript\" src=\"".$js."\"></script>\n";
    return $string;
}

function add_css($css=''){
    $CI =& get_instance();
    if (trim($css)=="") return false;
    $string = "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$CI->config->item('template_uri').$css."\" media=\"all\">\n";
    return $string;
}
function add_external_css($css=''){
    $CI =& get_instance();
    if (trim($css)=="") return false;
    $string = "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$css."\">\n";
    return $string;
}


/**
* function rev 1.0
*
* - addMobileCss
* - addTitle
*
* addMobileCss("/css/a.css","screen,projection,tv");
* addMobileCss("/css/a.css","handheld");
* adTitle('m.kabar24.com - kabar24 mobile web');
*
**/
function addMobileCss($csspath, $type=null){
    $media     = (($type != null)) ? "media=\"".$type."\"" : "";
     
    $string    = "<link rel=\"stylesheet\" ".$media." href=\"".$csspath."\" type=\"text/css\" />\n\t";
    return $string;
}
function addTitle($title) {
    $string    = "<title>".$title."</title>\n";
    return $string;
}

?>