<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Productcategory extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('productcategory_m');
    }

    public function index_get() 
    {
        $this->retdata['productcategorys'] = $this->productcategory_m->get_productcategory();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
