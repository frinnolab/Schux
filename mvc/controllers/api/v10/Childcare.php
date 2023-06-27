<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Childcare extends Api_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model('childcare_m');
    }

    public function index_get() 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->retdata['childcares'] = $this->childcare_m->get_join_childcare_all($schoolyearID);

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
