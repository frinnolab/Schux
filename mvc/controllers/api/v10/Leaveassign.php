<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaveassign extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('usertype_m');
        $this->load->model('leaveassign_m');
        $this->load->model('leavecategory_m');
    }

    public function index_get() 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->retdata['usertypes']      = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
        $this->retdata['leavecategorys'] = pluck($this->leavecategory_m->get_leavecategory(), 'leavecategory', 'leavecategoryID');
        $this->retdata['leaveassign']    = $this->leaveassign_m->get_order_by_leaveassign(array('schoolyearID' => $schoolyearID));

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
