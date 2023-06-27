<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Feetypes extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('feetypes_m');
    }

    public function index_get() 
    {
        $this->retdata['feetypes'] = $this->feetypes_m->get_order_by_feetypes();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

}
