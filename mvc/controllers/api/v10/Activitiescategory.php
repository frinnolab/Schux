<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Activitiescategory extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('activitiescategory_m');
    }

    public function index_get() 
    {
        $this->retdata['activitiescategorys'] = $this->activitiescategory_m->get_activitiescategory();
        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
