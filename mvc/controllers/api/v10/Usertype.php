<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class usertype extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('usertype_m');
    }

    public function index_get() 
    {
        $this->retdata['usertypes'] = $this->usertype_m->get_order_by_usertype();
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
