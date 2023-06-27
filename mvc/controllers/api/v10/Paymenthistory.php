<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Paymenthistory extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('payment_m');
        $this->load->model('studentrelation_m');
    }

    public function index_get() 
    {
        $usertypeID   = $this->session->userdata('usertypeID');
        $userID       = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if($usertypeID == 3) {
            $this->retdata['payments'] = $this->payment_m->get_payment_with_studentrelation_by_studentID($userID, $schoolyearID);
        } elseif($usertypeID == 4) {
            $students = $this->studentrelation_m->get_order_by_student(array('parentID' => $userID, 'schoolyearID' => $schoolyearID));
            if(count($students)) {
                $studentArray = pluck($students, 'srstudentID');
                $this->retdata['payments'] = [];
                $this->retdata['payments'] = $this->payment_m->get_payment_with_studentrelation_by_studentID($studentArray, $schoolyearID);
            } else {
                $this->retdata['payments'] = [];
            }
        } else {
            $this->retdata['payments'] = $this->payment_m->get_payment_with_studentrelation($schoolyearID);
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
