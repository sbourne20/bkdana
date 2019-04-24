<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;
class File_loader extends REST_Controller {

    public function __construct()
    {
        parent::  __construct();
        // 
        // $this->load->model('Content_model');
        $this->load->model('Member_model');
        // $this->load->model('User_model');
        $this->load->library('session');
    }

    function get_mime_type($filename) {
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

    public function file_get()
    {
        
        $headers = $this->input->request_headers();
        

        if (Authorization::tokenIsExist($headers)) {
            $token = Authorization::validateToken($headers['Authorization']);
            if ($token != false) {
                
                $uid = (int)antiInjection($token->id);

                if (!empty($uid)) {

                    $memberID  = $uid;
                     $param = antiInjection($_GET['p']);
                    $mime_type_or_return = $this->get_mime_type($param);
                    $filepath = $this->config->item('bkd_server') . '?p=' . $param;

                    $url = $filepath;
                    $username = "admin.bkd";
                    $password = "@B3rk4hK3l0l4D4n42018!!";
                    $post_data = array(
                            'p' => $param,
                    );

                    $options = array(
                            CURLOPT_URL            => $url,
                            CURLOPT_HEADER         => false,    
                            CURLOPT_VERBOSE        => true,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_SSL_VERIFYPEER => false,    // for https
                            CURLOPT_USERPWD        => $username . ":" . $password,
                            CURLOPT_HTTPAUTH       => CURLAUTH_DIGEST,
                            CURLOPT_POST           => true,
                            CURLOPT_POSTFIELDS     => http_build_query($post_data) 
                    );

                    $ch = curl_init();

                    curl_setopt_array( $ch, $options );

                    try {
                        $raw_response  = curl_exec( $ch );

                        // validate CURL status
                        if(curl_errno($ch))
                            throw new Exception(curl_error($ch), 500);

                        // validate HTTP status code (user/password credential issues)
                        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        if ($status_code != 200)
                            throw new Exception("Response with Status Code [" . $status_code . "].", 500);
                    } catch(Exception $ex) {
                        if ($ch != null) curl_close($ch);
                        // throw new Exception($ex);
                    //     $response = [
                    //     // 'response' => $ex,
                    //     // 'status'   => REST_Controller::HTTP_UNAUTHORIZED,
                    //     // 'message'  => 'Unauthorized',
                    // ];
                    // $http_status = REST_Controller::HTTP_UNAUTHORIZED;

                    $this->set_response($ex->getMessage(), $http_status);

                    return;
                    }

                    if ($ch != null) curl_close($ch);

                   // echo "raw response: " . $raw_response; 
                    header('Content-Length: '.strlen($raw_response)); // sends filesize header
                    header('Content-Type: '.$mime_type_or_return); // send mime-type header
                    header('Content-Disposition: inline; filename="'.basename($filepath).'";'); // sends filename header
                    exit($raw_response); // reads and outputs the file onto the output buffer
                   

                }else{
                    $response = [
                        'response' => 'fail',
                        'status'   => REST_Controller::HTTP_UNAUTHORIZED,
                        'message'  => 'Unauthorized',
                    ];
                    $http_status = REST_Controller::HTTP_UNAUTHORIZED;
                }

            }else{
                $response = [
                        'response' => 'fail',
                        'status'   => REST_Controller::HTTP_UNAUTHORIZED,
                        'message'  => 'Unauthorized',
                    ];
                    $http_status = REST_Controller::HTTP_UNAUTHORIZED;
            }
        }else{
            $response = [
                'response' => 'fail',
                'status'   => REST_Controller::HTTP_FORBIDDEN,
                'message'  => 'Forbidden',
            ];
            $http_status = REST_Controller::HTTP_FORBIDDEN;
        }

        $this->set_response($response, $http_status);
        return;

       

    }
}