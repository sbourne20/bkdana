<?php
function create_dirs($cur_dir="",$dir=""){
	if (!is_dir($cur_dir)) return false;
	if ($dir=="") return false;

	if (substr($cur_dir,-1)=="/" && substr($cur_dir,1)!="/"){
		$cur_dir = substr($cur_dir,0,strlen($cur_dir)-1);
	}

	$dirs = explode("/",$dir);
	$old = umask(0);
	foreach($dirs as $a_dir){
		if (!is_dir($cur_dir."/".$a_dir)){
			mkdir($cur_dir."/".$a_dir,0755);
		}
		$cur_dir = $cur_dir."/".$a_dir;
	}
	umask($old);
	unset($dirs);
}

/*
*	mkdir_r -> membuat directory recursive
*	pls tell me if this function already exist
*/
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

?>