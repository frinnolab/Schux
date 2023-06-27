<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api_Controller extends REST_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://iNilabs.net
| -----------------------------------------------------
*/
	
	public $data       = [];
	protected $retdata = []; 
	protected $_REST_Controller;

	public function __construct () 
	{
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
		parent::__construct();
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->model('setting_m');
		$this->load->model("site_m");
		$this->load->model('site_m');
		$this->load->model('holiday_m');
		$this->load->model('schoolyear_m');
		$this->load->model('usertype_m');
		$this->load->model('permission_m');


		if(is_array($this->tokeChecking()) && !isset($this->tokeChecking()['userdata'])) {
			$this->response([
                'status' => false,
                'message' => 'Invalid token'
            ], self::HTTP_UNAUTHORIZED);
		}

		$this->data["siteinfos"] = $this->site_m->get_site();
		$schoolyearID = $this->data['siteinfos']->school_year;
		$this->data['schoolyearobj'] = $this->schoolyear_m->get_obj_schoolyear($schoolyearID);
		$this->data['schoolyearsessionobj'] = $this->schoolyear_m->get_obj_schoolyear($this->session->userdata('defaultschoolyearID'));
		
		if($this->session->userdata('usertypeID') == 3) {
			$this->load->model('studentrelation_m');
			$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $this->session->userdata('loginuserID'), 'srschoolyearID' => $this->session->userdata('defaultschoolyearID')));
			if(count($student)) {
				$this->data['myclass'] = $student->srclassesID;
			} else {
				$this->data['myclass'] = 0;
			}
		} else {
			$this->data['myclass'] = 0;
		}

		$this->data['permission'] = $this->session->userdata('master_permission_set');
		$this->data["language"] = $this->data["siteinfos"]->language;

		$this->permissionControl();
	}

	protected function tokeChecking()
	{
		$token 			= $this->jwt_token();
		$tokenDecode 	= $this->jwt_decode($token);
		if(isset($tokenDecode['userdata'])) {
			$userInfoArray = ['username' => $tokenDecode['userdata']->username, 'password' => $tokenDecode['userdata']->password];
			if($this->session->userdata('master_permission_set') == null) {
				$this->userInfo($userInfoArray);
			}
		}
		return $tokenDecode;
	}

	private function userInfo($array)
    {
    	$username = $array['username'];
    	$password = $array['password'];
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
                'username'           	=> $userFoundInfo->username,
                'password'           	=> $password,
                'photo'               	=> $userFoundInfo->photo,
                'lang'               	=> $setting->language,
                'defaultschoolyearID' 	=> $setting->school_year,
                "loggedin"            	=> true,
                "varifyvaliduser"       => true,
            ];

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
        }
    }

    private function permissionControl()
    {
    	if(!empty($this->uri->segment(3))) {
			$feature = $this->uri->segment(3);
			$mode = $this->uri->segment(4);
			
			if($mode == '') {
				$mode = $feature;
			} else {
				if($mode == 'index') {
					$mode = $feature;
				} else {
					$mode = $feature.'_'.$mode;
				}
			}

			if(!empty($mode)) {
				$permissionSet = $this->session->userdata('master_permission_set');
				if(isset($permissionSet[$mode]) && ($permissionSet[$mode] == 'no')) {
					$this->response([
		                'status' => false,
		                'message' => 'Permission Deny'
		            ], self::HTTP_UNAUTHORIZED);
				}
			} else {
				$this->response([
	                'status' => false,
	                'message' => 'Feature Option Not Found'
	            ], self::HTTP_UNAUTHORIZED);
			}
		} else {
			$this->response([
                'status' => false,
                'message' => 'Feature Not Found'
            ], self::HTTP_UNAUTHORIZED);
		}
    }

	public function getHolidays() 
	{
    	$schoolyearID = $this->data['siteinfos']->school_year;
		$holidays = $this->holiday_m->get_order_by_holiday(array('schoolyearID' => $schoolyearID));
		$allHolidayList = array();
		if(count($holidays)) {
			foreach ($holidays as $holiday) {
				$from_date = strtotime($holiday->fdate);
				$to_date   = strtotime($holiday->tdate);
				$oneday    = 60*60*24;
				for($i= $from_date; $i<= $to_date; $i= $i+$oneday) {
				   	$allHolidayList[] = date('d-m-Y', $i);
				}
			}
		}

		$uniqueHolidays =  array_unique($allHolidayList);
	    return $uniqueHolidays;
	}

	public function getHolidaysSession($key = true) 
	{
    	$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$holidays = $this->holiday_m->get_order_by_holiday(array('schoolyearID' => $schoolyearID));
		$allHolidayList = array();
		if(count($holidays)) {
			foreach ($holidays as $holiday) {
				$from_date = strtotime($holiday->fdate);
				$to_date   = strtotime($holiday->tdate);
				$oneday    = 60*60*24;
				$j = 0;
				for($i= $from_date; $i<= $to_date; $i= $i+$oneday) {
					if($key) {
						$allHolidayList[] = date('d-m-Y', $i);
					} else {
						$allHolidayList[$j] = date('m-d-Y', $i);
						$j++;
					}
				   	
				}
			}
		}

		$uniqueHolidays =  array_unique($allHolidayList);
	    return $uniqueHolidays;
	}

	public function getWeekendDays() 
	{
		$date_from = strtotime($this->data['schoolyearobj']->startingdate);
		$date_to = strtotime($this->data['schoolyearobj']->endingdate);
		$oneDay = 60*60*24;

		$allDays = array(
            '0' => 'Sunday',
            '1' => 'Monday',
            '2' => 'Tuesday',
            '3' => 'Wednesday',
            '4' => 'Thursday',
            '5' => 'Friday',
            '6' => 'Saturday'
        );

       	$weekendDay = $this->data['siteinfos']->weekends;
		$weekendArrays = explode(',', $weekendDay);

		$weekendDateArrays = array();
		
		for($i= $date_from; $i<= $date_to; $i= $i+$oneDay) {
		    if($weekendDay != "") {
		    	foreach($weekendArrays as $weekendValue) {
		            if($weekendValue >= 0 && $weekendValue <= 6) {
		                if(date('l',$i) == $allDays[$weekendValue]) {
		                    $weekendDateArrays[] = date('d-m-Y', $i);
		                }
		            }
		        }
		    }
		}
		return $weekendDateArrays;
	}

	public function getWeekendDaysSession() 
	{
		$date_from = strtotime($this->data['schoolyearsessionobj']->startingdate);
		$date_to = strtotime($this->data['schoolyearsessionobj']->endingdate);
		$oneDay = 60*60*24;

		$allDays = array(
            '0' => 'Sunday',
            '1' => 'Monday',
            '2' => 'Tuesday',
            '3' => 'Wednesday',
            '4' => 'Thursday',
            '5' => 'Friday',
            '6' => 'Saturday'
        );

       	$weekendDay = $this->data['siteinfos']->weekends;
		$weekendArrays = explode(',', $weekendDay);

		$weekendDateArrays = array();
		
		for($i= $date_from; $i<= $date_to; $i= $i+$oneDay) {
		    if($weekendDay != "") {
		    	foreach($weekendArrays as $weekendValue) {
		            if($weekendValue >= 0 && $weekendValue <= 6) {
		                if(date('l',$i) == $allDays[$weekendValue]) {
		                    $weekendDateArrays[] = date('d-m-Y', $i);
		                }
		            }
		        }
		    }
		}
		return $weekendDateArrays;
	}
}

