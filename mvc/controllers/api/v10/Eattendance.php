<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Eattendance extends Api_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('exam_m');
        $this->load->model('section_m');
        $this->load->model('classes_m');
        $this->load->model('subject_m');
        $this->load->model('eattendance_m');
        $this->load->model('studentrelation_m');

        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function index_get() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->data['exams'] = $this->exam_m->get_exam();
        $this->data['classes'] = $this->classes_m->get_classes();

        $classesID = $this->input->post("classesID");
        if($classesID > 0) {
            $this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array("classesID" => $classesID));
        } else {
            $this->data['subjects'] = [];
        }
        $this->data['subjectID'] = 0;
        $this->data['students'] = [];

        if($_POST) {
            $rules = $this->rulessearch();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) { 
                $this->data["subview"] = "eattendance/index";
                $this->load->view('_layout_main', $this->data);         
            } else {
                $examID = $this->input->post("examID");
                $classesID = $this->input->post("classesID");
                $subjectID = $this->input->post("subjectID");
                $date = date("Y-m-d");

                $this->data['eattendances'] = pluck($this->eattendance_m->get_order_by_eattendance(array("examID" => $examID, 'schoolyearID' => $schoolyearID, "classesID" => $classesID, "subjectID" => $subjectID)), 'obj', 'studentID');

                $this->data['students'] = $this->studentrelation_m->get_order_by_student(array("srclassesID" => $classesID, 'srschoolyearID' => $schoolyearID));
                
                if(count($this->data['students'])) {
                    $sections = $this->section_m->general_get_order_by_section(array("classesID" => $classesID));
                    $this->data['sections'] = $sections;
                    foreach ($sections as $key => $section) {
                        $this->data['allsection'][$section->section] = $this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID, 'srclassesID' => $classesID, "srsectionID" => $section->sectionID));
                    }
                }

                $this->data["subview"] = "eattendance/index";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->response($this->data, REST_Controller::HTTP_OK);
        }
    }






}
