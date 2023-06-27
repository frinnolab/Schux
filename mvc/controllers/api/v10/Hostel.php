<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Hostel extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('hostel_m');
    }

    public function index_get() 
    {
        $this->retdata['hostels'] = $this->hostel_m->get_order_by_hostel();
        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
