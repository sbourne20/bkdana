<?php
function template_uri(){
    $CI =& get_instance();
    return $CI->config->item('template_uri');
}
function images_uri(){
    $CI =& get_instance();
    return $CI->config->item('images_uri');
}

function asset_uri(){
    $CI =& get_instance();
    return $CI->config->item('asset_uri');
}

function cache_uri(){
    $CI =& get_instance();
    return $CI->config->item('app_cache');
}

?>