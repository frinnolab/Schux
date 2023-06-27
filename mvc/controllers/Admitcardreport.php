<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admitcardreport extends Admin_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('exam_m');
		$this->load->model('classes_m');
		$this->load->model('section_m');
		$this->load->model('studentrelation_m');
		$this->load->model('subject_m');
		$this->load->model('schoolyear_m');
		$this->load->model('marksetting_m');
		
		$language = $this->session->userdata('lang');
		$this->lang->load('admitcardreport', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field'=>'examID',
				'label'=>$this->lang->line('admitcardreport_exam'),
				'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field'=>'classesID',
				'label'=>$this->lang->line('admitcardreport_class'),
				'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field'=>'sectionID',
				'label'=>$this->lang->line('admitcardreport_section'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'studentID',
				'label'=>$this->lang->line('admitcardreport_student'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'typeID',
				'label'=>$this->lang->line('admitcardreport_type'),
				'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field'=>'backgroundID',
				'label'=>$this->lang->line('admitcardreport_background'),
				'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
			),
		);
		return $rules;
	}

	protected function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field'=>'examID',
				'label'=>$this->lang->line('admitcardreport_exam'),
				'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field'=>'classesID',
				'label'=>$this->lang->line('admitcardreport_class'),
				'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field'=>'sectionID',
				'label'=>$this->lang->line('admitcardreport_section'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'studentID',
				'label'=>$this->lang->line('admitcardreport_student'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'typeID',
				'label'=>$this->lang->line('admitcardreport_type'),
				'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field'=>'backgroundID',
				'label'=>$this->lang->line('admitcardreport_background'),
				'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field'=>'to',
				'label'=>$this->lang->line('admitcardreport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field'=>'subject',
				'label'=>$this->lang->line('admitcardreport_subject'),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field'=>'message',
				'label'=>$this->lang->line('admitcardreport_message'),
				'rules' => 'trim|xss_clean'
			),
		);
		return $rules;
	}

	public function getSection() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			$sections = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>". $this->lang->line("admitcardreport_please_select") . "</option>";
			if(count($sections)) {
				foreach ($sections as $section) {
					echo "<option value='".$section->sectionID."'>".$section->section."</option>";
				}
			}
		}
	}

	public function getStudent() {
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$classesID && (int)$sectionID) {
			$students = $this->studentrelation_m->general_get_order_by_student(array('srclassesID'=>$classesID,'srsectionID'=>$sectionID,'srschoolyearID'=>$schoolyearID));
			echo "<option value='0'>". $this->lang->line("admitcardreport_please_select") . "</option>";
			if(count($students)) {
				foreach ($students as $student) {
					echo "<option value='".$student->srstudentID."'>".$student->srname."</option>";
				}
			}
		}
	}

	public function getExam() {
		$classesID = $this->input->post('classesID');
		echo "<option value='0'>", $this->lang->line("admitcardreport_please_select"),"</option>";
		if((int)$classesID) {
			$exams    = pluck($this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID), 'obj', 'examID');
			if(count($exams)) {
				foreach ($exams as $exam) {
					echo "<option value=".$exam->examID.">".$exam->exam."</option>";
				}
			}
		}
	}

	public function unique_data($data) {
		if($data != "") {
			if($data === "0") {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		} 
		return TRUE;
	}

	private function queryArray($posts) {
		$classesID = $posts['classesID'];
		$sectionID = $posts['sectionID'];
		$studentID = $posts['studentID'];
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$queryArray['srschoolyearID'] = $schoolyearID;
		if($classesID > 0) {
			$queryArray['srclassesID'] = $classesID;
		}

		if((int)$sectionID && $sectionID > 0) {
			$queryArray['srsectionID'] = $sectionID;
		}

		if((int)$studentID && $studentID > 0) {
			$queryArray['srstudentID'] = $studentID;	
		}
		return $queryArray;
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data["subview"] = "report/admitcard/AdmitcardReportView";
		$this->load->view('_layout_main', $this->data);
	}


	public function getAdmitcardReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('admitcardreport')) {
			$examID      = $this->input->post('examID');
			$classesID   = $this->input->post('classesID');
			$sectionID   = $this->input->post('sectionID');
			$studentID   = $this->input->post('studentID');
			$typeID      = $this->input->post('typeID');
			$backgroundID= $this->input->post('backgroundID');

			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$queryArray = $this->queryArray($this->input->post());
					$students   = $this->studentrelation_m->general_get_order_by_student($queryArray);
					$subjects   = $this->subject_m->general_get_order_by_subject(array('classesID'=>$classesID)); 
					$exambyID   = $this->exam_m->get_exam($examID);
					$classes    = $this->classes_m->general_get_classes();
					$sections   = $this->section_m->general_get_section();
					$schoolyearbyID = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID'=>$schoolyearID));
					$this->data['examID']       = $examID; 
					$this->data['classesID']    = $classesID; 
					$this->data['sectionID']    = $sectionID; 
					$this->data['studentID']    = $studentID; 
					$this->data['typeID']       = $typeID; 
					$this->data['backgroundID'] = $backgroundID; 
					$this->data['students']     = $students; 
					$this->data['subjects']     = pluck($subjects,'obj','subjectID');
					$this->data['examTitle']    = $exambyID->exam;
					$this->data['examYear']     = $schoolyearbyID->schoolyear;
					$this->data['classes'] = pluck($classes,'classes','classesID');
					$this->data['sections'] = pluck($sections,'section','sectionID');
					$retArray['render'] = $this->load->view('report/admitcard/AdmitcardReport',$this->data,true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
		    		exit;
				}
			} else {
				$retArray['status'] = FALSE;
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
		if(permissionChecker('admitcardreport')) {
			$examID       = htmlentities(escapeString($this->uri->segment(3)));
			$classesID    = htmlentities(escapeString($this->uri->segment(4)));
			$sectionID    = htmlentities(escapeString($this->uri->segment(5)));
			$studentID    = htmlentities(escapeString($this->uri->segment(6)));
			$typeID       = htmlentities(escapeString($this->uri->segment(7)));
			$backgroundID = htmlentities(escapeString($this->uri->segment(8)));
			if((int)$examID && (int)$classesID && ((int)$sectionID || $sectionID == 0) && ((int)$studentID || $studentID == 0) && (int)$typeID && (int)$backgroundID) {

				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$posts['examID']       = $examID; 
				$posts['classesID']    = $classesID; 
				$posts['sectionID']    = $sectionID; 
				$posts['studentID']    = $studentID; 
				$posts['typeID']       = $typeID; 
				$posts['backgroundID'] = $backgroundID; 

				$queryArray = $this->queryArray($posts);
				$students   = $this->studentrelation_m->general_get_order_by_student($queryArray);
				$subjects   = $this->subject_m->general_get_order_by_subject(array('classesID'=>$classesID)); 
				$exambyID   = $this->exam_m->get_exam($examID);
				$classes    = $this->classes_m->general_get_classes();
				$sections   = $this->section_m->general_get_section();
				$schoolyearbyID = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID'=>$schoolyearID));

				$this->data['examID']       = $examID; 
				$this->data['classesID']    = $classesID; 
				$this->data['sectionID']    = $sectionID; 
				$this->data['studentID']    = $studentID; 
				$this->data['typeID']       = $typeID; 
				$this->data['backgroundID'] = $backgroundID; 
				$this->data['students']     = $students; 
				$this->data['subjects']     = pluck($subjects,'obj','subjectID');
				$this->data['examTitle']    = $exambyID->exam;
				$this->data['examYear']     = $schoolyearbyID->schoolyear;
				$this->data['classes'] = pluck($classes,'classes','classesID');
				$this->data['sections'] = pluck($sections,'section','sectionID');
				$this->reportPDF('admitcardreport.css', $this->data, 'report/admitcard/AdmitcardReportPDF');
			} else {
				$this->data["subview"] = "errorpermission";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('admitcardreport')) {
			if($_POST) {
				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else{
					$examID      = $this->input->post('examID');
					$classesID   = $this->input->post('classesID');
					$sectionID   = $this->input->post('sectionID');
					$studentID   = $this->input->post('studentID');
					$typeID      = $this->input->post('typeID');
					$backgroundID= $this->input->post('backgroundID');
					$to          = $this->input->post('to');
					$subject     = $this->input->post('subject');
					$message     = $this->input->post('message');

					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$queryArray = $this->queryArray($this->input->post());
					$students   = $this->studentrelation_m->general_get_order_by_student($queryArray);
					$subjects   = $this->subject_m->general_get_order_by_subject(array('classesID'=>$classesID)); 
					$exambyID   = $this->exam_m->get_exam($examID);
					$classes    = $this->classes_m->general_get_classes();
					$sections   = $this->section_m->general_get_section();
					$schoolyearbyID = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID'=>$schoolyearID));
					$this->data['examID']       = $examID; 
					$this->data['classesID']    = $classesID; 
					$this->data['sectionID']    = $sectionID; 
					$this->data['studentID']    = $studentID; 
					$this->data['typeID']       = $typeID; 
					$this->data['backgroundID'] = $backgroundID; 
					$this->data['students']     = $students; 
					$this->data['subjects']     = pluck($subjects,'obj','subjectID');
					$this->data['examTitle']    = $exambyID->exam;
					$this->data['examYear']     = $schoolyearbyID->schoolyear;
					$this->data['classes'] = pluck($classes,'classes','classesID');
					$this->data['sections'] = pluck($sections,'section','sectionID');
					$this->reportSendToMail('admitcardreport.css', $this->data, 'report/admitcard/AdmitcardReportPDF', $to, $subject, $message);
					$retArray['message'] = "Message";
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
				    exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('admitcardreport_permission');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('admitcardreport_permissionmethod');
			echo json_encode($retArray);
			exit;
		}
	}
}

?>