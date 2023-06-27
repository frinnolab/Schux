<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Signin extends REST_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('user_m');
        $this->load->model('setting_m');
        $this->load->model('usertype_m');
        $this->load->model('permission_m');
    }

    public function index_post()
    {
    	$username 	= inputCall('username');
    	$password 	= inputCall('password');
    	if ($username && $password) {
    		$userInfo = $this->userInfo(inputCall());
    		if(is_array($userInfo)) {
                $tokenArray['iat']   	= time();
                $tokenArray['userdata']	= (array) $userInfo;
                $token                  = $this->jwt_encode($tokenArray);

                $this->retdata['token'] = $token;
                $this->retdata['profile'] = (array) $userInfo;
                $this->response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $this->retdata
                ], REST_Controller::HTTP_OK);
            } else {
    			$this->response([
                	'status' 	=> false,
	                'message' 	=> 'Invalid username or password'
	            ], REST_Controller::HTTP_UNAUTHORIZED);	
    		}
    	} else {
    		$this->response([
                'status' 	=> false,
                'message' 	=> 'Invalid username or password'
            ], REST_Controller::HTTP_UNAUTHORIZED);
    	}
    }

    private function userInfo($array)
    {
    	$username = $array['username'];
    	$password = $this->user_m->hash($array['password']);
    	$tables   = [
            'student'     => 'student',
            'parents'     => 'parents',
            'teacher'     => 'teacher',
            'user'        => 'user',
            'systemadmin' => 'systemadmin',
        ];

        $setting 		= $this->setting_m->get_setting();
       	$userFoundInfo 	= [];
       	$tableID 		= 0;

       	foreach ($tables as $table) {
            $user 				= $this->db->get_where($table, ["username" => $username, "password" => $password, 'active' => 1]);
            $userInfo 			= $user->row();
            if(count($userInfo)) {
            	$tableID 		= $table . 'ID';
            	$userFoundInfo 	= $userInfo; 
            }
        }

        if(count($userFoundInfo)) {
        	$usertype 		= $this->usertype_m->get_single_usertype(array('usertypeID' => $userFoundInfo->usertypeID));
        	$sessionArray 	= [
                'loginuserID'         	=> $userFoundInfo->$tableID,
                'name'                	=> $userFoundInfo->name,
                'email'               	=> $userFoundInfo->email,
                'usertypeID'          	=> $userFoundInfo->usertypeID,
                'usertype'            	=> $usertype->usertype,
                'username'              => $userFoundInfo->username,
                'password'           	=> $password,
                'photo'               	=> $userFoundInfo->photo,
                'lang'               	=> $setting->language,
                'defaultschoolyearID' 	=> $setting->school_year,
                "loggedin"            	=> true,
                "varifyvaliduser"       => true,
            ];

            $this->session->unset_userdata('master_permission_set');
            $this->session->set_userdata($sessionArray);
            
            $permissionSet  = [];
            $session        = $this->session->userdata;
            if($this->session->userdata('usertypeID') == 1 && $this->session->userdata('loginuserID') == 1) {
                if(isset($session['loginuserID'])) {
                    $features   = $this->permission_m->get_permission();
                    if(count($features)) {
                        foreach ($features as $featureKey => $feature) {
                            $permissionSet['master_permission_set'][trim($feature->name)] = $feature->active;
                        }
                        $permissionSet['master_permission_set']['take_exam'] = 'yes';
                        $this->session->set_userdata($permissionSet);
                    }
                }
            } else {
                if(isset($session['loginuserID'])) {
                    $features   = $this->permission_m->get_modules_with_permission($session['usertypeID']);
                    foreach ($features as $feature) {
                        $permissionSet['master_permission_set'][$feature->name] = $feature->active;
                    }

                    if($session['usertypeID'] == 3) {
                        $permissionSet['master_permission_set']['take_exam'] = 'yes';
                    }
                    $this->session->set_userdata($permissionSet);
                }
            }

            return $sessionArray;
        } else {
        	return false;
        }
    }
}