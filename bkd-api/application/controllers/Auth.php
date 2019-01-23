<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/ExpiredException.php';
require_once APPPATH . 'libraries/BeforeValidException.php';
require_once APPPATH . 'libraries/SignatureInvalidException.php';

use Restserver\Libraries\REST_Controller;


/* ======= LOGIN =======  */

class Auth extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Member_model');

        error_reporting(E_ALL);
        ini_set('display_errors', '1');
    }

    public function login_post()
    {
        //echo 'login';

            $email = trim($this->input->post('username', TRUE));
            $pass  = trim($this->input->post('password', TRUE));

            if ($email != '' && $pass != '') {

                $device_token = $this->input->post('token', TRUE);

                $getdata = $this->Member_model->do_login_byemail(htmlentities(strip_tags($email)));

                // _d($getdata);
                // exit();

                if ( count($getdata) > 0 && $getdata['mum_password'] !='' && $getdata['id_mod_user_member'] !=''){
                
                    $stored_password = $getdata['mum_password'];

                    if (password_verify(base64_encode(hash('sha256', ($pass), true)), $stored_password)) {

                        /*if (!empty($device_token))
                        {
                            // echo 'update device token';
                            $updata['member_device_token'] = antiInjection($device_token);
                            $updata['member_device_date']  = date('Y-m-d H:i:s');
                            $this->Member_model->update_memberdata($getdata['member_id'], $updata);
                        }*/

                        // Generate JWT Token
                        $issuedAt   = time();
                        $notBefore  = $issuedAt;             //Adding 10 seconds
                        $expire     = $notBefore + 1209600;         // in seconds. Adding 2 weeks

                        $tokenData          = array();
                        $tokenData['id']    = $getdata['id_mod_user_member'];
                        $tokenData['iss']   = $_SERVER['SERVER_NAME'];
                        $tokenData['iat']   = $issuedAt;
                        $tokenData['nbf']   = $notBefore;
                        $tokenData['exp']   = $expire;
                        $tokenData['logtype'] = $getdata['mum_type'];

                        $response['response'] = 'success';
                        $response['status']   = REST_Controller::HTTP_OK;
                        $response['token']    = Authorization::generateToken($tokenData);
                        $response['name']     = $getdata['mum_fullname'];
                        $response['logtype']  = $getdata['mum_type'];
                        $this->set_response($response, REST_Controller::HTTP_OK);
                        return;
                    }
                    else
                    {
                        $response = [
                            'response' => 'fail',
                            'status'   => REST_Controller::HTTP_UNAUTHORIZED,
                            'message'  => 'Unauthorized',
                        ];
                        $this->set_response($response, REST_Controller::HTTP_UNAUTHORIZED);
                        return;                
                    }
                }else{
                    $response = [
                        'status' => REST_Controller::HTTP_UNAUTHORIZED,
                        'message' => 'Data Not Found',
                    ];
                    $this->set_response($response, REST_Controller::HTTP_UNAUTHORIZED);
                    return;
                }
            }else{
                $response = [
                    'status' => REST_Controller::HTTP_UNAUTHORIZED,
                    'message' => 'Username & Password is mandatory',
                ];
                $this->set_response($response, REST_Controller::HTTP_UNAUTHORIZED);
                return;
            }
        
    }

    public function callback_google_post()
    {
        $email = trim(antiInjection($this->input->post('email', TRUE)));

        if (!empty($email))
        {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $response = [
                    'response' => 'fail',
                    'status'   => REST_Controller::HTTP_BAD_REQUEST,
                    'message'  => 'Invalid Email format!',
                ];
                $http_status = REST_Controller::HTTP_BAD_REQUEST;
                $this->set_response($response, $http_status);
                return;
            }else{

                $check_user = $this->Member_model->check_existing_member('', $email, '1');

                if (count($check_user) < 1 OR (! $check_user['member_id']) )
                {
                    $response = [
                        'response' => 'fail',
                        'status'   => REST_Controller::HTTP_BAD_REQUEST,
                        'message'  => 'User not exist',
                    ];
                    $http_status = REST_Controller::HTTP_BAD_REQUEST;
                    $this->set_response($response, $http_status);
                    return;
                }else{
                    // Email exist

                    $device_token = $this->input->post('token', TRUE);
                    if (!empty($device_token))
                    {
                        // echo 'update device token';
                        $updata['member_device_token'] = antiInjection($device_token);
                        $updata['member_device_date']  = date('Y-m-d H:i:s');
                        $this->Member_model->update_memberdata($check_user['member_id'], $updata);
                    }

                    // Generate JWT Token
                    $issuedAt   = time();
                    $notBefore  = $issuedAt;             //Adding 10 seconds
                    $expire     = $notBefore + 1209600;         // in seconds. Adding 2 weeks

                    $tokenData          = array();
                    $tokenData['id']    = $check_user['member_id'];
                    $tokenData['iss']   = $_SERVER['SERVER_NAME'];
                    $tokenData['iat']   = $issuedAt;
                    $tokenData['nbf']   = $notBefore;
                    $tokenData['exp']   = $expire;

                    $response['response'] = 'success';
                    $response['status'] = REST_Controller::HTTP_OK;
                    $response['token']  = Authorization::generateToken($tokenData);
                    $response['name']   = $check_user['member_name'];
                    $this->set_response($response, REST_Controller::HTTP_OK);
                    return;
                }
            }
        }else{

            $response = [
                'response' => 'fail',
                'status'   => REST_Controller::HTTP_BAD_REQUEST,
                'message'  => 'Email is required',
            ];
            $http_status = REST_Controller::HTTP_BAD_REQUEST;
            $this->set_response($response, $http_status);
            return;
        }
    }

    public function callback_facebook_post()
    {
        $email = trim(antiInjection($this->input->post('email', TRUE)));

        if (!empty($email))
        {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $response = [
                    'response' => 'fail',
                    'status'   => REST_Controller::HTTP_BAD_REQUEST,
                    'message'  => 'Invalid Email format!',
                ];
                $http_status = REST_Controller::HTTP_BAD_REQUEST;
                $this->set_response($response, $http_status);
                return;
            }else{

                $check_user = $this->Member_model->check_existing_member('', $email, '1');

                if (count($check_user) < 1 OR (! $check_user['member_id']) )
                {
                    $response = [
                        'response' => 'fail',
                        'status'   => REST_Controller::HTTP_BAD_REQUEST,
                        'message'  => 'User not exist',
                    ];
                    $http_status = REST_Controller::HTTP_BAD_REQUEST;
                    $this->set_response($response, $http_status);
                    return;
                }else{
                    // Email exist

                    $device_token = $this->input->post('token', TRUE);
                    if (!empty($device_token))
                    {
                        // echo 'update device token';
                        $updata['member_device_token'] = antiInjection($device_token);
                        $updata['member_device_date']  = date('Y-m-d H:i:s');
                        $this->Member_model->update_memberdata($check_user['member_id'], $updata);
                    }

                    // Generate JWT Token
                    $issuedAt   = time();
                    $notBefore  = $issuedAt;             //Adding 10 seconds
                    $expire     = $notBefore + 1209600;         // in seconds. Adding 2 weeks

                    $tokenData          = array();
                    $tokenData['id']    = $check_user['member_id'];
                    $tokenData['iss']   = $_SERVER['SERVER_NAME'];
                    $tokenData['iat']   = $issuedAt;
                    $tokenData['nbf']   = $notBefore;
                    $tokenData['exp']   = $expire;

                    $response['response'] = 'success';
                    $response['status'] = REST_Controller::HTTP_OK;
                    $response['token']  = Authorization::generateToken($tokenData);
                    $response['name']   = $check_user['member_name'];
                    $this->set_response($response, REST_Controller::HTTP_OK);
                    return;
                }
            }
        }else{

            $response = [
                'response' => 'fail',
                'status'   => REST_Controller::HTTP_BAD_REQUEST,
                'message'  => 'Email is required',
            ];
            $http_status = REST_Controller::HTTP_BAD_REQUEST;
            $this->set_response($response, $http_status);
            return;
        }
    }
}
