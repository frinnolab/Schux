<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Classes extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->model('teacher_m');
    }

    public function index_get() 
    {
        $this->retdata['teachers'] = pluck($this->teacher_m->get_teacher(), 'name', 'teacherID');
        $this->retdata['classes']  = $this->classes_m->get_classes();
        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
