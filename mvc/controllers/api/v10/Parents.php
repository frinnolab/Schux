<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Parents extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('parents_m');
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('document_m');
        $this->load->model('usertype_m');
        $this->load->model('studentrelation_m');
    }

    public function index_get() 
    {
        $myProfile = false;
        if($this->session->userdata('usertypeID') == 4) {
            if(!permissionChecker('parents_view')) {
                $myProfile = true;
            }
        }

        if($this->session->userdata('usertypeID') == 4 && $myProfile) {
            $parentsID = $this->session->userdata('loginuserID');
            $this->getView($parentsID);
        } else {
            $parents = $this->parents_m->get_parents();

            if(count($parents)) {
                $this->retdata['parents'] = $parents;
            } else {
                $this->retdata['parents'] = [];
            }
        }

        $retArray['status']     = true;
        $retArray['message']    = 'Success'; 
        $retArray['data']       = $this->retdata;
        $this->response($retArray, REST_Controller::HTTP_OK);
    }

    public function view_get($parentsID = 0) 
    {
        $this->getView($parentsID);
    }

    private function getView($parentsID) 
    {
        if ((int)$parentsID) {
            $parents = $this->parents_m->get_single_parents(array('parentsID' => $parentsID));
            $this->plucInfo();
            $this->basicInfo($parents);
            $this->childrenInfo($parents);
            $this->documentInfo($parents);

            if(count($parents)) {
                $this->response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $this->retdata
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status'    => false,
                    'message'   => 'Error 404',
                    'data'      => []
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Error 404',
                'data'      => []
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    private function plucInfo() 
    {
        $this->retdata['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
        $this->retdata['classes']   = pluck($this->classes_m->general_get_classes(),'classes','classesID');
        $this->retdata['sections']  = pluck($this->section_m->get_section(),'section','sectionID');
    }

    private function basicInfo($parents) 
    {
        if(count($parents)) {
            $this->retdata['profile'] = $parents;
        } else {
            $this->retdata['profile'] = [];
        }
    }

    private function childrenInfo($parents) 
    {
        $this->retdata['childrens'] = [];
        if(count($parents)) {
            $schoolyearID               = $this->session->userdata('defaultschoolyearID');
            $this->db->order_by('student.classesID', 'asc');
            $this->retdata['childrens'] = $this->studentrelation_m->general_get_order_by_student(array('parentID' => $parents->parentsID, 'srschoolyearID' => $schoolyearID));
        }
    }

    private function documentInfo($parents) 
    {
        if(count($parents)) {
            $this->retdata['documents'] = $this->document_m->get_order_by_document(array('usertypeID' => 4, 'userID' => $parents->parentsID));
        } else {
            $this->retdata['documents'] = [];
        }
    }
}
