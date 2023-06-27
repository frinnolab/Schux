<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Assignment extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('section_m');
        $this->load->model('classes_m');
        $this->load->model('assignment_m');
        $this->load->model('assignmentanswer_m');
    }

    public function index_get($id = null) 
    {
        if($this->session->userdata('usertypeID') == 3) {
            $id = $this->data['myclass'];
        }

        $this->retdata['classes'] = $this->classes_m->get_classes();
        if((int)$id) {
            $fetchClasses = pluck($this->retdata['classes'], 'classesID', 'classesID');
            if(isset($fetchClasses[$id])) {
                $this->retdata['classesID'] = $id;
                $this->retdata['sections'] = pluck($this->section_m->general_get_order_by_section(array('classesID' => $id)), 'section', 'sectionID');
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->retdata['assignments'] = $this->assignment_m->join_get_assignment($id, $schoolyearID);
            } else {
                $this->retdata['classesID'] = 0;
                $this->retdata['assignments'] = [];
            }
        } else {
            $this->retdata['classesID'] = 0;
            $this->retdata['assignments'] = []; 
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = 0, $url = 0)
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if((int)$id && (int)($url)) {
            $this->retdata['classesID'] = $url;
            $fetchClasses = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
            if(isset($fetchClasses[$url])) {
                $assignment = $this->assignment_m->get_single_assignment(array('assignmentID' => $id, 'classesID' => $url, 'schoolyearID' => $schoolyearID));
                if(count($assignment)) {
                    $this->retdata['assignmentanswers'] = $this->assignmentanswer_m->join_get_assignmentanswer($id, $schoolyearID);
                } else {
                    $this->retdata['assignmentanswers'] = [];
                }
            } else {
                $this->retdata['assignmentanswers'] = [];
            }
        } else {
            $this->retdata['classesID'] = $url;
            $this->retdata['assignmentanswers'] = [];
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
