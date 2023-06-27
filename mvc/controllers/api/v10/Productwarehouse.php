<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Productwarehouse extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('productwarehouse_m');
    }

    public function index_get() 
    {
        $this->retdata['productwarehouses'] = $this->productwarehouse_m->get_productwarehouse();
        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
