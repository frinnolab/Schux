<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Schoolyear extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('schoolyear_m');
    }

    public function index_get() 
    {
        $this->retdata['schoolyearID']  = $this->data['siteinfos']->school_year;
        $this->retdata['schoolyears']   = $this->schoolyear_m->get_order_by_schoolyear();
        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
