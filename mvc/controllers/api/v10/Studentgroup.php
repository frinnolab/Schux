<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Studentgroup extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('studentgroup_m');
    }

    public function index_get() 
    {
        $this->retdata['studentgroups'] = $this->studentgroup_m->get_order_by_studentgroup();
      
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
