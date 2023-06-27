<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Income extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('income_m');
    }

    public function index_get() 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->retdata['incomes'] = $this->income_m->get_income_with_user(array('income.schoolyearID' => $schoolyearID));

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
