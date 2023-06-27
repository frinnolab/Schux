<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eattendance extends Admin_Controller {
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
		$this->load->model("student_m");
		$this->load->model("exam_m");
		$this->load->model('subject_m');
		$this->load->model("eattendance_m");
		$this->load->model("classes_m");
		$this->load->model("section_m");
		$this->load->model('studentrelation_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('eattendance', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'examID', 
				'label' => $this->lang->line("eattendance_exam"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_examID'
			), 
			array(
				'field' => 'classesID', 
				'label' => $this->lang->line("eattendance_classes"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_classesID'
			), 
			array(
				'field' => 'sectionID', 
				'label' => $this->lang->line("eattendance_section"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_sectionID'
			), 
			array(
				'field' => 'subjectID', 
				'label' => $this->lang->line("eattendance_subject"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_subjectID'
			)
		);
		return $rules;
	}

	protected function rulessearch() {
		$rules = array(
			array(
				'field' => 'examID', 
				'label' => $this->lang->line("eattendance_exam"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_examID'
			), 
			array(
				'field' => 'classesID', 
				'label' => $this->lang->line("eattendance_classes"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_classesID'
			), 
			array(
				'field' => 'subjectID', 
				'label' => $this->lang->line("eattendance_subject"), 
				'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_subjectID'
			)
		);
		return $rules;
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
		$year = date("Y");

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
			$this->data["subview"] = "eattendance/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function exam_list() {
		$examID = $this->input->post('id');
		if((int)$examID) {
			$string = base_url("eattendance/index/$examID");
			echo $string;
		} else {
			redirect(base_url("eattendance/index"));
		}
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['exams'] = $this->exam_m->get_exam();
			$this->data['classes'] = $this->classes_m->get_classes();

			$classesID = $this->input->post("classesID");
			if($classesID != 0) {
				$this->data['sections'] = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
				$this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array("classesID" => $classesID));
			} else {
				$this->data['sections'] = [];
				$this->data['subjects'] = [];
			}

			$this->data['students'] = [];
			$this->data['eattendances'] = [];
			$this->data['eattendanceinfo'] = [];
			
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) { 
					$this->data["subview"] = "eattendance/add";
					$this->load->view('_layout_main', $this->data);			
				} else {
					$examID = $this->input->post("examID");
					$classesID = $this->input->post("classesID");
					$sectionID = $this->input->post('sectionID');
					$subjectID = $this->input->post("subjectID");
					$date = date("Y-m-d");
					$year = date("Y");

					$students = $this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID, "srclassesID" => $classesID, 'srsectionID' => $sectionID));
					$eattendance = pluck($this->eattendance_m->get_order_by_eattendance(array("examID" => $examID, 'schoolyearID' => $schoolyearID, "classesID" => $classesID, 'sectionID' => $sectionID, "subjectID" => $subjectID)), 'obj', 'studentID');
					
					$eattendanceArray = [];
					if(count($students)) {
						foreach ($students as $key => $student) {
							if(!isset($eattendance[$student->studentID])) {
								$eattendanceArray[] = array(
									"examID" => $examID,
									'schoolyearID' => $schoolyearID,
									"classesID" => $classesID,
									'sectionID' => $sectionID,
									"subjectID" => $subjectID,
									"studentID" => $student->studentID,
									"s_name" => $student->name,
									"eattendance" => 'Present',
									"date" => $date,
									"year" => $year
								);
							}
						}
					}
					
					if(count($eattendanceArray)) {
						$this->eattendance_m->insert_batch_eattendance($eattendanceArray);
					}

					$this->data['students'] = $students;
					$this->data['eattendances'] = pluck($this->eattendance_m->get_order_by_eattendance(array("examID" => $examID, 'schoolyearID' => $schoolyearID, "classesID" => $classesID, 'sectionID' => $sectionID, "subjectID" => $subjectID)), 'obj', 'studentID');

					$this->data['examID'] = $examID;
					$this->data['classesID'] = $classesID;
					$this->data['sectionID'] = $sectionID;
					$this->data['subjectID'] = $subjectID;

					$this->data['eattendanceinfo']['exam'] = $this->exam_m->get_single_exam(array('examID' => $examID));
					$this->data['eattendanceinfo']['class'] = $this->classes_m->general_get_single_classes(array('classesID' => $classesID));
					$this->data['eattendanceinfo']['section'] = $this->section_m->general_get_single_section(array('sectionID' => $sectionID));
					$this->data['eattendanceinfo']['subject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $subjectID));
					$this->data["subview"] = "eattendance/add";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "eattendance/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function unique_examID() {
		$examID = $this->input->post('examID');
		if($examID === '0') {
			$this->form_validation->set_message("unique_examID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_classesID() {
		$classesID = $this->input->post('classesID');
		if($classesID === '0') {
			$this->form_validation->set_message("unique_classesID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_subjectID() {
		$subjectID = $this->input->post('subjectID');
		if($subjectID === '0') {
			$this->form_validation->set_message("unique_subjectID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_sectionID() {
		$sectionID = $this->input->post('sectionID');
		if($sectionID === '0') {
			$this->form_validation->set_message("unique_sectionID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function subjectcall() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$allclasses = $this->subject_m->general_get_order_by_subject(array("classesID" => $classID));
			echo "<option value='0'>", $this->lang->line("eattendance_select_subject"),"</option>";
			foreach ($allclasses as $value) {
				echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
			}
		} 
	}

	public function sectioncall() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$sections = $this->section_m->general_get_order_by_section(array("classesID" => $classID));
			echo "<option value='0'>", $this->lang->line("eattendance_select_section"),"</option>";
			foreach ($sections as $section) {
				echo "<option value=\"$section->sectionID\">",$section->section,"</option>";
			}
		} 
	}

	public function single_add() {
		$examID = $this->input->post('examID');
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$subjectID = $this->input->post('subjectID');
		$studentID = $this->input->post('studentID');
		$status = 0;
		$status = $this->input->post('status');
		$year = date("Y");
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		
		if($status == "checked") {
			$status = "Present";
		} elseif($status == "unchecked") {
			$status = "Absent";
		}
		if((int)$examID && (int)$classesID && (int)$subjectID) {
			$array = array("eattendance" => $status);
			$this->eattendance_m->update_eattendance_via_array($array, array("examID" => $examID, 'schoolyearID' => $schoolyearID, "classesID" => $classesID, 'sectionID' => $sectionID, "subjectID" => $subjectID, "studentID" => $studentID));
			echo $this->lang->line('menu_success');
		}
	}

	public function all_add() {
		$examID = $this->input->post('examID');
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$subjectID = $this->input->post('subjectID');
		$status = 0;
		$status = $this->input->post('status');
		$year = date("Y");
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		if($status == "checked") {
			$status = "Present";
		} elseif($status == "unchecked") {
			$status = "Absent";
		}
		if((int)$examID && (int)$classesID && (int)$subjectID) {
			$array = array("eattendance" => $status);
			$this->eattendance_m->update_eattendance_via_array($array, array("examID" => $examID, 'schoolyearID' => $schoolyearID, "classesID" => $classesID, 'sectionID' => $sectionID, "subjectID" => $subjectID));
			echo $this->lang->line('menu_success');
		}
	}
}