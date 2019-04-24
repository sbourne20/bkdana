<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (is_file(__DIR__ . '/../libraries/aliyun-oss-php-sdk-master/autoload.php')) {
    require_once __DIR__ . '/../libraries/aliyun-oss-php-sdk-master/autoload.php';
}
use OSS\OssClient;
use OSS\Core\OssException;

class File_loader extends CI_Controller {

    public function __construct()
    {
        parent::  __construct();
        $this->load->model('Content_model');
        $this->load->model('Member_model');
    }

	private function get_mime_type($filename) {
		$idx = explode( '.', $filename );
		$count_explode = count($idx);
		$idx = strtolower($idx[$count_explode-1]);

		$mimet = array( 
			'txt' => 'text/plain',
			'htm' => 'text/html',
			'html' => 'text/html',
			'php' => 'text/html',
			'css' => 'text/css',
			'js' => 'application/javascript',
			'json' => 'application/json',
			'xml' => 'application/xml',
			'swf' => 'application/x-shockwave-flash',
			'flv' => 'video/x-flv',

			// images
			'png' => 'image/png',
			'jpe' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'gif' => 'image/gif',
			'bmp' => 'image/bmp',
			'ico' => 'image/vnd.microsoft.icon',
			'tiff' => 'image/tiff',
			'tif' => 'image/tiff',
			'svg' => 'image/svg+xml',
			'svgz' => 'image/svg+xml',

			// archives
			'zip' => 'application/zip',
			'rar' => 'application/x-rar-compressed',
			'exe' => 'application/x-msdownload',
			'msi' => 'application/x-msdownload',
			'cab' => 'application/vnd.ms-cab-compressed',

			// audio/video
			'mp3' => 'audio/mpeg',
			'qt' => 'video/quicktime',
			'mov' => 'video/quicktime',

			// adobe
			'pdf' => 'application/pdf',
			'psd' => 'image/vnd.adobe.photoshop',
			'ai' => 'application/postscript',
			'eps' => 'application/postscript',
			'ps' => 'application/postscript',

			// ms office
			'doc' => 'application/msword',
			'rtf' => 'application/rtf',
			'xls' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',
			'docx' => 'application/msword',
			'xlsx' => 'application/vnd.ms-excel',
			'pptx' => 'application/vnd.ms-powerpoint',


			// open office
			'odt' => 'application/vnd.oasis.opendocument.text',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		);

		if (isset( $mimet[$idx] )) {
		return $mimet[$idx];
		} else {
		return 'application/octet-stream';
		}
	}

    public function file()
    {
        $this->Content_model->has_login();
        $uid = htmlentities($_SESSION['_bkduser_']);
        $param = urldecode($_GET['p']);
        $filename = substr($param, strrpos($param,'/')+1);
        $param_array = explode('/', $param);
        $paramuid = $param_array[1]; 

        $mime_type_or_return = $this->get_mime_type($param);
        $filepath = $this->config->item('data_dir') . $param;

        $userfile = $this->Member_model->get_file_name($uid);
        $user_file_name = (strtolower($userfile['userfile']));
        $user_file_name_array = explode('|',$user_file_name);

        if(in_array(strtolower($filename), $user_file_name_array ) && $uid == $paramuid){
            $this->load->helper('file');

			// Start of OSS
			$accessKeyId = $this->config->item('oss_access_key_id');
			$accessKeySecret = $this->config->item('oss_access_key_secret');
			$endpoint = $this->config->item('oss_endpoint');
			$bucket= $this->config->item('oss_bucket_bkd_user');
			$object = $param;

			try {
				$ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
				$image_content = $ossClient->getObject($bucket, $object);
			} catch (OssException $e) {
				print $e->getMessage();
			}
			// End of OSS

            // Image was not found
            if($image_content === FALSE)
            {
                show_error('Image "'.$filepath.'" could not be found.');
                return FALSE;
            }

            // Return the image or output it?
            if($mime_type_or_return === TRUE)
            {
                return $image_content;
            }

            header('Content-Length: '.strlen($image_content)); // sends filesize header
            header('Content-Type: '.$mime_type_or_return); // send mime-type header
            header('Content-Disposition: inline; filename="'.basename($filepath).'";'); // sends filename header
            exit($image_content); // reads and outputs the file onto the output buffer
        }
        else{
            header('HTTP/1.0 403 Forbidden');
        }
       
	}
	
	private function get_file_from_external()
    {
		// DIGEST AUTH
		$realm = 'The batcave';

		// Just a random id
		$nonce = uniqid();
		
		// Get the digest from the http header
		$digest = $this->getDigest();
		
		// If there was no digest, show login
		if (is_null($digest)) $this->requireLogin($realm,$nonce);
		
		$digestParts = $this->digestParse($digest);
		
		$validUser = 'admin.bkd';
		$validPass = '@B3rk4hK3l0l4D4n42018!!';
		
		// Based on all the info we gathered we can figure out what the response should be
		$A1 = md5("{$validUser}:{$realm}:{$validPass}");
		$A2 = md5("{$_SERVER['REQUEST_METHOD']}:{$digestParts['uri']}");
		
		$validResponse = md5("{$A1}:{$digestParts['nonce']}:{$digestParts['nc']}:{$digestParts['cnonce']}:{$digestParts['qop']}:{$A2}");
		
		if ($digestParts['response']!=$validResponse){
			 $this->requireLogin($realm,$nonce);
		}
		// END OF DIGEST
		else{
			$param = urldecode(antiInjection($_GET['p']));
			$filename = substr($param, strrpos($param,'/')+1);

			$mime_type_or_return = $this->get_mime_type($param);
			$filepath = $this->config->item('data_dir') . $param;

			$this->load->helper('file');
			$image_content = read_file($filepath);
			
            // Image was not found
            if($image_content === FALSE)
            {
                show_error('Image "'.$filepath.'" could not be found.');
                return FALSE;
            }

            // Return the image or output it?
            if($mime_type_or_return === TRUE)
            {
                return $image_content;
            }

            header('Content-Length: '.strlen($image_content)); // sends filesize header
            header('Content-Type: '.$mime_type_or_return); // send mime-type header
            header('Content-Disposition: inline; filename="'.basename($filepath).'";'); // sends filename header
            exit($image_content); // reads and outputs the file onto the output buffer
        }
	}
	
	// This function returns the digest string
	private function getDigest() {

		// mod_php
		if (isset($_SERVER['PHP_AUTH_DIGEST'])) {
			$digest = $_SERVER['PHP_AUTH_DIGEST'];
		// most other servers
		} elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {

				if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']),'digest')===0)
				$digest = substr($_SERVER['HTTP_AUTHORIZATION'], 7);
		}

		return $digest;

	}

	// This function forces a login prompt
	private function requireLogin($realm,$nonce) {
		header('WWW-Authenticate: Digest realm="' . $realm . '",qop="auth",nonce="' . $nonce . '",opaque="' . md5($realm) . '"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Text to send if user hits Cancel button';
		die();
	}

	// This function extracts the separate values from the digest string
	private function digestParse($digest) {
		// protect against missing data
		$needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
		$data = array();

		preg_match_all('@(\w+)=(?:(?:")([^"]+)"|([^\s,$]+))@', $digest, $matches, PREG_SET_ORDER);

		foreach ($matches as $m) {
			$data[$m[1]] = $m[2] ? $m[2] : $m[3];
			unset($needed_parts[$m[1]]);
		}

		return $needed_parts ? false : $data;
	}

}
