<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Book extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('book_m');
    }

    public function index_get() 
    {
        $this->retdata['books'] = $this->book_m->get_order_by_book();
        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
