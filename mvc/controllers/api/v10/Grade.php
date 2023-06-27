<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Grade extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('grade_m');
    }

    public function index_get() 
    {
        $this->retdata['grades'] = $this->grade_m->get_order_by_grade();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
