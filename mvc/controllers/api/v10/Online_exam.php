<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Online_exam extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('online_exam_m');
        $this->load->model('studentrelation_m');
    }

    public function index_get() 
    {
        $usertypeID  = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if($usertypeID == '3') {
            $this->retdata['student']  = $this->studentrelation_m->get_single_student(array('srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID));
        }
        $this->retdata['usertypeID']   = $usertypeID;
        $this->retdata['online_exams'] = $this->online_exam_m->get_order_by_online_exam(array('schoolYearID' => $schoolyearID));

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
