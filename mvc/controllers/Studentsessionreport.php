<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentsessionreport extends Admin_Controller {
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
| WEBSITE:			http://inilabs.net
| -----------------------------------------------------
*/
	function __construct() {
		parent::__construct();
		$this->load->model("classes_m");
		$this->load->model('section_m');
		$this->load->model("studentrelation_m");
		$this->load->model("exam_m");
		$this->load->model("markpercentage_m");
		$this->load->model("subject_m");
		$this->load->model("setting_m");
		$this->load->model("mark_m");
		$this->load->model("grade_m");
		$this->load->model("studentgroup_m");
		$this->load->model("marksetting_m");

		$language = $this->session->userdata('lang');
		$this->lang->load('studentsessionreport', $language);
	}
	
 	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css',
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
			)
		);
		$schoolyearID            = $this->session->userdata('defaultschoolyearID');
		$this->data['students']  = $this->studentrelation_m->general_get_order_by_student(['srschoolyearID'=> $schoolyearID]);
		$this->data["subview"]   = "report/studentsession/StudentsessionReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getstudentsessionreport () {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('studentsessionreport')) {
			if($_POST) {
				$studentID  = $this->input->post('studentID');
				$rules      = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {

					$markArray    = [];
					$queryArray   = [];
					if((int)$studentID > 0) {
						$markArray['studentID']    = $studentID;
						$queryArray['srstudentID'] = $studentID;
					}

					$students               = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srschoolyearID');
					$marks                  = $this->mark_m->student_all_mark_array($markArray);
					$mandatorySubjects      = pluck_multi_array_key($this->subject_m->general_get_order_by_subject(array('type' => 1)), 'obj', 'classesID', 'subjectID');
					$optionalSubjects       = pluck_multi_array_key($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'obj', 'classesID', 'subjectID');

					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
					$percentageArr          = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
					


					$retMark = [];
					if(count($marks)) {
						foreach ($marks as $mark) {
							$retMark[$mark->schoolyearID][$mark->classesID][$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
						}
					}

					$this->data['classes']           = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections']          = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->data['groups']            = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
					$this->data['exams']             = pluck($this->exam_m->get_exam(),'exam','examID');
					$this->data['grades']            = $this->grade_m->get_grade();
					$this->data['schoolyears']       = pluck($this->schoolyear_m->get_schoolyear(),'schoolyear','schoolyearID');

					$this->data['studentID']         = $studentID;
					$this->data['retMark']           = $retMark;
					$this->data['percentageArr']     = $percentageArr;
					$this->data['mandatorySubjects'] = $mandatorySubjects;
					$this->data['optionalSubjects']  = $optionalSubjects;					
					$this->data['students']          = $students;
					$this->data['settingmarktypeID']       = $settingmarktypeID;
					$this->data['markpercentagesmainArr']  = $markpercentagesmainArr;

					$retArray['render'] = $this->load->view('report/studentsession/StudentsessionReport',$this->data,true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit();
				}
			} else {
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
			$retArray['status'] = TRUE;
			echo json_encode($retArray);
			exit;
		}
	}

	public function pdf() {
		if(permissionChecker('studentsessionreport')) {
			$studentID    = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$studentID) {

				$markArray    = [];
				$queryArray   = [];
				if((int)$studentID > 0) {
					$markArray['studentID']    = $studentID;
					$queryArray['srstudentID'] = $studentID;
				}

				$students               = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srschoolyearID');
				$marks                  = $this->mark_m->student_all_mark_array($markArray);
				$mandatorySubjects      = pluck_multi_array_key($this->subject_m->general_get_order_by_subject(array('type' => 1)), 'obj', 'classesID', 'subjectID');
				$optionalSubjects       = pluck_multi_array_key($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'obj', 'classesID', 'subjectID');

				$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
				$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
				$percentageArr          = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
				


				$retMark = [];
				if(count($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->schoolyearID][$mark->classesID][$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}

				$this->data['classes']           = pluck($this->classes_m->general_get_classes(),'classes','classesID');
				$this->data['sections']          = pluck($this->section_m->general_get_section(),'section','sectionID');
				$this->data['groups']            = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
				$this->data['exams']             = pluck($this->exam_m->get_exam(),'exam','examID');
				$this->data['grades']            = $this->grade_m->get_grade();
				$this->data['schoolyears']       = pluck($this->schoolyear_m->get_schoolyear(),'schoolyear','schoolyearID');

				$this->data['studentID']         = $studentID;
				$this->data['retMark']           = $retMark;
				$this->data['percentageArr']     = $percentageArr;
				$this->data['mandatorySubjects'] = $mandatorySubjects;
				$this->data['optionalSubjects']  = $optionalSubjects;					
				$this->data['students']          = $students;
				$this->data['settingmarktypeID']       = $settingmarktypeID;
				$this->data['markpercentagesmainArr']  = $markpercentagesmainArr;

				$this->reportPDF('studentsessionreport.css', $this->data, 'report/studentsession/StudentsessionReportPDF');

			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_pdf_to_mail() {
		$retArray['status']  = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('studentsessionreport')) {
			if($_POST) {
				$to           = $this->input->post('to');
				$subject      = $this->input->post('subject');
				$message      = $this->input->post('message');
				$studentID    = $this->input->post('studentID');

				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$markArray    = [];
					$queryArray   = [];
					if((int)$studentID > 0) {
						$markArray['studentID']    = $studentID;
						$queryArray['srstudentID'] = $studentID;
					}

					$students               = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srschoolyearID');
					$marks                  = $this->mark_m->student_all_mark_array($markArray);
					$mandatorySubjects      = pluck_multi_array_key($this->subject_m->general_get_order_by_subject(array('type' => 1)), 'obj', 'classesID', 'subjectID');
					$optionalSubjects       = pluck_multi_array_key($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'obj', 'classesID', 'subjectID');

					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
					$percentageArr          = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
					


					$retMark = [];
					if(count($marks)) {
						foreach ($marks as $mark) {
							$retMark[$mark->schoolyearID][$mark->classesID][$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
						}
					}

					$this->data['classes']           = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections']          = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->data['groups']            = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
					$this->data['exams']             = pluck($this->exam_m->get_exam(),'exam','examID');
					$this->data['grades']            = $this->grade_m->get_grade();
					$this->data['schoolyears']       = pluck($this->schoolyear_m->get_schoolyear(),'schoolyear','schoolyearID');

					$this->data['studentID']         = $studentID;
					$this->data['retMark']           = $retMark;
					$this->data['percentageArr']     = $percentageArr;
					$this->data['mandatorySubjects'] = $mandatorySubjects;
					$this->data['optionalSubjects']  = $optionalSubjects;					
					$this->data['students']          = $students;
					$this->data['settingmarktypeID']       = $settingmarktypeID;
					$this->data['markpercentagesmainArr']  = $markpercentagesmainArr;

					$this->reportSendToMail('studentsessionreport.css', $this->data, 'report/studentsession/StudentsessionReportPDF',$to, $subject,$message);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
    				exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('studentsessionreport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('studentsessionreport_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("studentsessionreport_student"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			)
		);
		return $rules;
	} 

	protected function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("studentsessionreport_student"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line("studentsessionreport_to"),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("studentsessionreport_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("studentsessionreport_message"),
				'rules' => 'trim|xss_clean'
			),
		);
		return $rules;
	}

	public function unique_data($data) {
		if($data != "") {
			if($data === "0") {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
		} 
		return TRUE;
	}
}
