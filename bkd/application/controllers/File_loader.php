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
        $param = urldecode(antiInjection($_GET['p']));
        $filename = substr($param, strrpos($param,'/')+1);
        $param_array = explode('/', $param);
        $paramuid = $param_array[1]; 

        $mime_type_or_return = $this->get_mime_type($param);
        $filepath = $this->config->item('data_dir') . $param;

        $userfile = $this->Member_model->get_file_name($uid);
        $user_file_name = (strtolower($userfile['userfile']));
        $user_file_name_array = explode('|',$user_file_name);

        if(in_array(strtolower($filename), $user_file_name_array ) && $uid == $paramuid){
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
}
