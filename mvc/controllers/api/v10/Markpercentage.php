<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Markpercentage extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('markpercentage_m');
    }

    public function index_get() 
    {
        $this->retdata['markpercentage'] = $this->markpercentage_m->get_markpercentage();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
