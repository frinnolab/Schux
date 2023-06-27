<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_assignment extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('user_m');
        $this->load->model('teacher_m');
        $this->load->model('student_m');
        $this->load->model('parents_m');
        $this->load->model('usertype_m');
        $this->load->model('systemadmin_m');
        $this->load->model('asset_assignment_m');
    }

    public function index_get() 
    {
        $this->retdata['asset_assignments'] = $this->asset_assignment_m->get_asset_assignment_with_userypeID();
        if(count($this->retdata['asset_assignments'])) {
            foreach ($this->retdata['asset_assignments'] as $key => $assignment) {
                $getName = $this->userTableCall($assignment->usertypeID, $assignment->check_out_to);
                if(!empty($getName)) {
                    $this->retdata['asset_assignments'][$key] = (object) array_merge( (array)$assignment, array( 'assigned_to' => $getName));
                }
            }
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = null) 
    {
        if((int)$id) {
            $this->retdata['asset_assignment'] = $this->asset_assignment_m->get_single_asset_assignment_with_usertypeID(array('asset_assignmentID' => $id));
            $this->retdata['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');

            if(count($this->retdata['asset_assignment'])) {
                $usertypeID = $this->retdata['asset_assignment']->usertypeID;

                if($usertypeID == 3) {
                    $student = $this->student_m->get_single_student(array('studentID' => $this->retdata['asset_assignment']->check_out_to));

                    if(count($student)) {
                        $this->retdata['user'] = $this->allUsersArrayObject($usertypeID, $student->studentID, $student->classesID);
                    } else {
                        $this->retdata['user'] = [];
                    }
                } else {
                    $this->retdata['user'] = $this->allUsersArrayObject($usertypeID, $this->retdata['asset_assignment']->check_out_to);
                }

                $this->response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $this->retdata
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status'    => false,
                    'message'   => 'Error 404',
                    'data'      => [],
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Error 404',
                'data'      => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    private function userTableCall($usertypeID, $userID) 
    {
        $this->load->model('systemadmin_m');
        $this->load->model('teacher_m');
        $this->load->model('student_m');
        $this->load->model('parents_m');
        $this->load->model('user_m');

        $findUserName = '';
        if($usertypeID == 1) {
            $user = $this->db->get_where('systemadmin', array("usertypeID" => $usertypeID, 'systemadminID' => $userID));
            $alluserdata = $user->row();
            if(count($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } elseif($usertypeID == 2) {
            $user = $this->db->get_where('teacher', array("usertypeID" => $usertypeID, 'teacherID' => $userID));
            $alluserdata = $user->row();
            if(count($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } elseif($usertypeID == 3) {
            $user = $this->db->get_where('student', array("usertypeID" => $usertypeID, 'studentID' => $userID));
            $alluserdata = $user->row();
            if(count($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } elseif($usertypeID == 4) {
            $user = $this->db->get_where('parents', array("usertypeID" => $usertypeID, 'parentsID' => $userID));
            $alluserdata = $user->row();
            if(count($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } else {
            $user = $this->db->get_where('user', array("usertypeID" => $usertypeID, 'userID' => $userID));
            $alluserdata = $user->row();
            if(count($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        }
    }

    Private function allUsersArrayObject($usertypeID, $userID, $classesID = 0) 
    { 
        $returnArray = [];
        if($usertypeID == 1) {
            $returnArray = $this->systemadmin_m->get_single_systemadmin(array('systemID' => $userID));
        } elseif($usertypeID == 2) {
            $returnArray = $this->teacher_m->general_get_single_teacher(array('teacherID' => $userID));
        } elseif($usertypeID == 3) {
            $returnArray = $this->student_m->general_get_single_student(array('studentID' => $userID, 'classesID' => $classesID, 'schoolyearID' => $this->data['siteinfos']->school_year));
        } elseif($usertypeID == 4) {
            $returnArray = $this->parents_m->get_single_parents(array('parentsID' => $userID));
        } else {
            $returnArray = $this->user_m->get_single_user(array('usertypeID' => $usertypeID, 'userID' => $userID));
        }
        return $returnArray;
    }
}
