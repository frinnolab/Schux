<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('category_m');
    }

    public function index_get() 
    {
        $this->retdata['categorys'] = $this->category_m->get_join_category();
        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
