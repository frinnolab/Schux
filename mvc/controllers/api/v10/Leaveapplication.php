<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaveapplication extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('usertype_m');
        $this->load->model('leaveassign_m');
        $this->load->model('leavecategory_m');
        $this->load->model('leaveapplication_m');
    }

    public function index_get() 
    {
        $userID        = $this->session->userdata("loginuserID");
        $usertypeID    = $this->session->userdata("usertypeID");
        $schoolyearID  = $this->session->userdata("defaultschoolyearID");
        if($usertypeID != 1) {
            $this->retdata['leaveapplications'] = $this->leaveapplication_m->get_order_by_leaveapplication_with_user(['leaveapplications.applicationto_userID' => $userID, 'leaveapplications.applicationto_usertypeID' => $usertypeID, 'leaveapplications.schoolyearID' => $schoolyearID]);
        } else {
            $this->retdata['leaveapplications'] = $this->leaveapplication_m->get_order_by_leaveapplication_with_user(array('leaveapplications.schoolyearID' => $schoolyearID));
        }
        $this->retdata['leavecategorys'] = pluck($this->leavecategory_m->get_leavecategory(), 'leavecategory', 'leavecategoryID');
        $this->retdata['allUserTypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = null) 
    {
        if(permissionChecker('leaveapplication')) {
            if ((int)$id) {
                $schoolyearID  = $this->session->userdata("defaultschoolyearID");
                $this->retdata['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                $this->retdata['leaveapplication'] = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $id, 'schoolyearID' => $schoolyearID));

                if(count($this->retdata['leaveapplication'])) {
                    if((($this->retdata['leaveapplication']->applicationto_userID == $this->session->userdata('loginuserID')) && ($this->retdata['leaveapplication']->applicationto_usertypeID == $this->session->userdata('usertypeID'))) || ($this->session->userdata('usertypeID') == 1)) {
                        $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID' => $this->retdata['leaveapplication']->leavecategoryID));
                        if(count($leavecategory)) {
                            $this->retdata['leaveapplication']->category = $leavecategory->leavecategory;    
                        } else {
                            $this->retdata['leaveapplication']->category = '';    
                        }

                        $availableleave = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($this->retdata['leaveapplication']->create_usertypeID, $this->retdata['leaveapplication']->create_userID, $schoolyearID, $this->retdata['leaveapplication']->leavecategoryID);              
                        if(isset($availableleave->days) && $availableleave->days > 0) {
                            $availableleavedays = $availableleave->days;
                        } else {
                            $availableleavedays = 0;    
                        }

                        $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('leavecategoryID' => $this->retdata['leaveapplication']->leavecategoryID, 'schoolyearID' => $schoolyearID));
                        if(count($leaveassign)) {
                            $this->retdata['leaveapplication']->leaveavabledays = ($leaveassign->leaveassignday - $availableleavedays);
                        } else {
                            $this->retdata['leaveapplication']->leaveavabledays = $this->lang->line('leaveapply_deleted');
                        }

                        $this->retdata['applicant'] = getObjectByUserTypeIDAndUserID($this->retdata['leaveapplication']->create_usertypeID, $this->retdata['leaveapplication']->create_userID, $schoolyearID);

                        $this->retdata['daysArray'] = $this->leavedaysCount($this->retdata['leaveapplication']->from_date, $this->retdata['leaveapplication']->to_date);

                        $this->response([
                            'status'    => true,
                            'message'   => 'Success',
                            'data'      => $this->retdata
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status'    => false,
                            'message'   => 'Error 404',
                            'data'      => []
                        ], REST_Controller::HTTP_OK);
                    }
                } else {
                    $this->response([
                        'status'    => false,
                        'message'   => 'Error 404',
                        'data'      => []
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    'status'    => false,
                    'message'   => 'Error 404',
                    'data'      => []
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Error 404',
                'data'      => []
            ], REST_Controller::HTTP_OK);
        }
    }

    private function leavedaysCount($fromdate, $todate) 
    {
        $allholidayArray    = $this->getHolidaysSession();
        $getweekenddayArray = $this->getWeekendDaysSession();
        $leavedays = get_day_using_two_date(strtotime($fromdate), strtotime($todate));

        $holidayCount    = 0;
        $weekenddayCount = 0;
        $leavedayCount   = 0;
        $totaldayCount   = 0;
        $retArray = [];
        if(count($leavedays)) {
            foreach($leavedays as $leaveday) {
                if(in_array($leaveday, $allholidayArray)) {
                    $holidayCount++;
                } elseif(in_array($leaveday, $getweekenddayArray)) {
                    $weekenddayCount++;
                } else {
                    $leavedayCount++;
                }
                $totaldayCount++;
            }
        }

        $retArray['fromdate']        = $fromdate;
        $retArray['todate']          = $todate;
        $retArray['holidayCount']    = $holidayCount;
        $retArray['weekenddayCount'] = $weekenddayCount;
        $retArray['leavedayCount']   = $leavedayCount;
        $retArray['totaldayCount']   = $totaldayCount;
        return $retArray;
    }
}
