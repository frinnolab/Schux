<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Librarycardreport extends Admin_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://iNilabs.net
| -----------------------------------------------------
*/
	function __construct() {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('teacher_m');
        $this->load->model('schoolyear_m');
        $this->load->model('studentrelation_m');
        $this->load->model('lmember_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('librarycardreport', $language);
	}

    protected function rules($usertypeID) {
        $rules = array(
            array(
                'field' => 'classesID', 
                'label' => $this->lang->line('librarycardreport_class'),
                'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
            ), array(
                'field' => 'sectionID',
                'label' => $this->lang->line('librarycardreport_section'),
                'rules' => 'trim|xss_clean|greater_than_equal_to[0]'
            ), array(
                'field' => 'studentID',
                'label' => $this->lang->line('librarycardreport_student'),
                'rules' => 'trim|xss_clean|greater_than_equal_to[0]'
            ), array(
                'field' => 'type',
                'label' => $this->lang->line('librarycardreport_type'),
                'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
            ), array(
                'field' => 'background',
                'label' => $this->lang->line('librarycardreport_background'),
                'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
            )
        );
        return $rules;
    }

    protected function send_pdf_to_mail_rules($usertypeID) {
        $rules = array(
            array(
                'field' => 'classesID', 
                'label' => $this->lang->line('librarycardreport_class'),
                'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
            ), array(
                'field' => 'sectionID',
                'label' => $this->lang->line('librarycardreport_section'),
                'rules' => 'trim|xss_clean|greater_than_equal_to[0]'
            ), array(
                'field' => 'studentID',
                'label' => $this->lang->line('librarycardreport_student'),
                'rules' => 'trim|xss_clean|greater_than_equal_to[0]'
            ), array(
                'field' => 'type',
                'label' => $this->lang->line('librarycardreport_type'),
                'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
            ), array(
                'field' => 'background',
                'label' => $this->lang->line('librarycardreport_background'),
                'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
            ), array(
                'field' => 'to',
                'label' => $this->lang->line('librarycardreport_to'),
                'rules' => 'trim|required|xss_clean|valid_email'
            ), array(
                'field' => 'subject',
                'label' => $this->lang->line('librarycardreport_subject'),
                'rules' => 'trim|required|xss_clean'
            ), array(
                'field' => 'message',
                'label' => $this->lang->line('librarycardreport_message'),
                'rules' => 'trim|xss_clean'
            )
        );
        return $rules;
    }

    public function unique_data($data) {
        if($data == 0) {
            $this->form_validation->set_message('unique_data','The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }

    public function getSection() {
        $classesID = $this->input->post('classesID');
        if((int)$classesID) {
            $sections = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
            echo "<option value='0'>".$this->lang->line("librarycardreport_please_select")."</option>";
            if(count($sections)) {
                foreach ($sections as $section) {
                    echo "<option value='".$section->sectionID."'>".$section->section."</option>";
                }
            }
        }
    }

    public function getStudent() {
        $classesID  = $this->input->post('classesID');
        $sectionID  = $this->input->post('sectionID');
            
        $queryArray['srschoolyearID'] = $this->session->userdata('defaultschoolyearID');
        if((int)$classesID && $classesID > 0) {
            $queryArray['srclassesID'] = $classesID;
        }

        if((int)$sectionID && $sectionID > 0) {
            $queryArray['srsectionID'] = $sectionID;
        }

        echo "<option value='0'>".$this->lang->line("librarycardreport_please_select")."</option>";
        if(count($queryArray)) {
            $students = $this->studentrelation_m->general_get_order_by_student($queryArray);
            if(count($students)) {
                foreach ($students as $student) {
                    echo "<option value='".$student->srstudentID."'>".$student->srname."</option>";
                }
            }  
        }
    }

    private function queryArray($posts) {
        $classesID  = $posts['classesID'];
        $sectionID  = $posts['sectionID'];
        $studentID  = $posts['studentID'];

        $queryArray = [];
        $queryArray['srschoolyearID'] = $this->session->userdata('defaultschoolyearID');
        $queryArray['srclassesID'] = $classesID;
        if((int)$sectionID > 0) {
            $queryArray['srsectionID'] = $sectionID;
        }
        if((int)$studentID > 0) {
            $queryArray['srstudentID'] = $studentID;
        }
        return $this->lmember_m->get_join_lmember_student_studentrelation($queryArray);
    }

    public function index() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
            )
        );
        $this->data['classes'] = $this->classes_m->general_get_classes();
        $this->data["subview"] = "report/librarycard/LibrarycardReportView";
        $this->load->view('_layout_main', $this->data);
    }

    public function getLibrarycardReport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('librarycardreport')) {
            if($_POST) {
                $usertypeID = $this->input->post('usertypeID');
                $rules = $this->rules($usertypeID);
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');
                    $this->data['classesID']   = $this->input->post('classesID');
                    $this->data['sectionID']   = $this->input->post('sectionID');
                    $this->data['studentID']      = $this->input->post('studentID');
                    $this->data['type']        = $this->input->post('type');
                    $this->data['background']  = $this->input->post('background');
                    $this->data['schoolyear']  = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID'=>$schoolyearID));
                    $this->data['classes']     = pluck($this->classes_m->general_get_classes(),'classes','classesID');
                    $this->data['sections']    = pluck($this->section_m->general_get_section(),'section','sectionID');
                    $this->data['librarycards']= $this->queryArray($this->input->post());
                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/librarycard/LibrarycardReport', $this->data,true);
                    echo json_encode($retArray);
                    exit;
                }
            } else {
                $retArray['status'] = TRUE;
                $retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['status'] = TRUE;
            $retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
            echo json_encode($retArray);
            exit;
        }
    }

    public function pdf() {
        if(permissionChecker('librarycardreport')) {
            $classesID    = htmlentities(escapeString($this->uri->segment(3)));
            $sectionID    = htmlentities(escapeString($this->uri->segment(4)));
            $studentID    = htmlentities(escapeString($this->uri->segment(5)));
            $type         = htmlentities(escapeString($this->uri->segment(6)));
            $background   = htmlentities(escapeString($this->uri->segment(7)));
            $schoolyearID = $this->session->userdata('defaultschoolyearID');

            if((int)$classesID && ((int)$sectionID || $sectionID ==0) && ((int)$studentID || $studentID ==0) && (int)$type && (int)$background) {
                $this->data['classesID']   = $classesID;
                $this->data['sectionID']   = $sectionID;
                $this->data['studentID']   = $studentID;
                $this->data['type']        = $type;
                $this->data['background']  = $background;
                $this->data['schoolyear']  = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID'=>$schoolyearID));
                $this->data['classes']     = pluck($this->classes_m->general_get_classes(),'classes','classesID');
                $this->data['sections']    = pluck($this->section_m->general_get_section(),'section','sectionID');
                $array['classesID'] = $classesID;
                $array['sectionID'] = $sectionID;
                $array['studentID'] = $studentID;
                $this->data['librarycards']     = $this->queryArray($array);
                $this->reportPDF('librarycardreport.css', $this->data, 'report/librarycard/LibrarycardReportPDF','view','a4');
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';

        if(permissionChecker('librarycardreport')) {
            if($_POST) {
                $to          = $this->input->post('to');
                $subject     = $this->input->post('subject');
                $message     = $this->input->post('message');
                $classesID   = $this->input->post('classesID');
                $sectionID   = $this->input->post('sectionID');
                $studentID   = $this->input->post('studentID');
                $type        = $this->input->post('type');
                $background  = $this->input->post('background');
                $schoolyearID= $this->session->userdata('defaultschoolyearID');

                $usertypeID = $this->input->post('usertypeID');
                $rules = $this->send_pdf_to_mail_rules($usertypeID);
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    if((int)$classesID && ((int)$sectionID || $sectionID ==0) && ((int)$studentID || $studentID ==0) && (int)$type && (int)$background) {
                        $this->data['classesID']   = $classesID;
                        $this->data['sectionID']   = $sectionID;
                        $this->data['studentID']   = $studentID;
                        $this->data['type']        = $type;
                        $this->data['background']  = $background;
                        $this->data['schoolyear']  = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID'=>$schoolyearID));
                        $this->data['classes']     = pluck($this->classes_m->general_get_classes(),'classes','classesID');
                        $this->data['sections']    = pluck($this->section_m->general_get_section(),'section','sectionID');
                        $array['classesID'] = $classesID;
                        $array['sectionID'] = $sectionID;
                        $array['studentID'] = $studentID;
                        $this->data['librarycards']     = $this->queryArray($array);
                        $this->reportSendToMail('librarycardreport.css', $this->data, 'report/librarycard/LibrarycardReportPDF', $to, $subject, $message);
                        $retArray['message'] = "Message";
                        $retArray['status'] = TRUE;
                        echo json_encode($retArray);
                        exit;
                    } else {
                        $retArray['message'] = $this->lang->line('librarycardreport_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('librarycardreport_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('librarycardreport_permission');
            echo json_encode($retArray);
            exit;
        }
    }

	

}

/* End of file activities.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/activities.php */