<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Productsupplier extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('productsupplier_m');
    }

    public function index_get() 
    {
        $this->retdata['suppliers'] = $this->productsupplier_m->get_productsupplier();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
