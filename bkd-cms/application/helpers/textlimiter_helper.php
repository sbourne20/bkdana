<?php
function textlimit($string_text, $number_limit){
	$tmp    = ((strlen($string_text) > $number_limit)) ? (substr($string_text,0,$number_limit-4)." ...") : $string_text;
	return $tmp;
}
function cleanHTML($htmltext){
		$patt = array('@<script[^>]*?>.*?</script>@si','@<[\\/\\!]*?[^<>]*?>@si','@<style[^>]*?>.*?</style>@siU','@<![\\s\\S]*?--[ \\t\\n\\r]*>@');
		$text = preg_replace($patt, '', $htmltext);
		return $text;
}
function climiter($str, $n = 500, $end_char = '&#8230;'){
	    if (strlen($str) < $n){ 
            return $str;
        }
		$str = preg_replace("/\s+/", ' ', preg_replace("/(\r\n|\r|\n)/", " ", $str));
	    if (strlen($str) <= $n)	{ 
            return $str;
        }						
	    $out = "";
	    foreach (explode(' ', trim($str)) as $val){
		    $out .= $val.' ';			
		    if (strlen($out) >= $n){
			    return trim($out).$end_char;
		    }		
	    }
    }
?>