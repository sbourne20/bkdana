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
        $this->load->model('Agent_model');

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

                $getdata = $this->Agent_model->do_login_byemail(htmlentities(strip_tags($email)));

                // _d($getdata);
                // exit();

                if ( count($getdata) > 0 && $getdata['agent_password'] !='' && $getdata['id_mod_agent'] !=''){
                
                    $stored_password = $getdata['agent_password'];

                    if (password_verify(base64_encode(hash('sha256', ($pass), true)), $stored_password)) {

                        /*if (!empty($device_token))
                        {
                            // echo 'update device token';
                            $updata['agent_device_token'] = antiInjection($device_token);
                            $updata['agent_device_date']  = date('Y-m-d H:i:s');
                            $this->Agent_model->update_agentdata($getdata['agent_id'], $updata);
                        }*/

                        // Generate JWT Token
                        $issuedAt   = time();
                        $notBefore  = $issuedAt;             //Adding 10 seconds
                        $expire     = $notBefore + 1209600;         // in seconds. Adding 2 weeks

                        $tokenData          = array();
                        $tokenData['id']    = $getdata['id_mod_agent'];
                        $tokenData['iss']   = $_SERVER['SERVER_NAME'];
                        $tokenData['iat']   = $issuedAt;
                        $tokenData['nbf']   = $notBefore;
                        $tokenData['exp']   = $expire;
                        
                        $response['response'] = 'success';
                        $response['status']   = REST_Controller::HTTP_OK;
                        $response['token']    = Authorization::generateToken($tokenData);
                        $response['name']     = $getdata['agent_fullname'];
                        $response['email']     = $getdata['agent_email'];
                        $response['phone']     = $getdata['agent_phone'];
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

    
}