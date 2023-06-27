<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Leavecategory extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('leavecategory_m');
    }

    public function index_get() 
    {
        $this->retdata['leavecategorys'] = $this->leavecategory_m->get_leavecategory();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
