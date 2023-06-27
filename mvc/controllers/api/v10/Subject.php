<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Subject extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('teacher_m');
        $this->load->model('subject_m');
        $this->load->model('classes_m');
        $this->load->model('subjectteacher_m');
    }

    public function index_get($id = null) 
    {
        if($this->session->userdata('usertypeID') == 3) {
            $id = $this->data['myclass'];
        }

        if((int)$id) {
            $this->retdata['classesID'] = $id;
            $this->retdata['teachers'] = pluck($this->teacher_m->general_get_teacher(), 'name', 'teacherID');
            $this->retdata['classes'] = $this->classes_m->get_classes();
            $fetchClass = pluck($this->retdata['classes'], 'classesID', 'classesID');
            if(isset($fetchClass[$id])) {
                $this->retdata['subjects'] = $this->subject_m->general_get_order_by_subject(array('classesID' => $id));
                $this->retdata['subjectteachers'] = pluck_multi_array($this->subjectteacher_m->get_order_by_subjectteacher(array('classesID' => $id)), 'teacherID', 'subjectID');
            } else {
                $this->retdata['classesID'] = 0;
                $this->retdata['subjects'] = [];
                $this->retdata['subjectteachers'] = [];
            }
        } else {
            $this->retdata['classesID'] = 0;
            $this->retdata['subjects'] = [];
            $this->retdata['subjectteachers'] = [];
            $this->retdata['classes'] = $this->classes_m->get_classes();
        }
        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
