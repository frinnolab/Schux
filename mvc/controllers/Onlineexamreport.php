<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Onlineexamreport extends Admin_Controller {
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
		$this->load->model('schoolyear_m');
		$this->load->model('classes_m');
		$this->load->model('section_m');
		$this->load->model('subject_m');
		$this->load->model('online_exam_m');
		$this->load->model('online_exam_user_status_m');
		$this->load->model('studentrelation_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('onlineexamreport', $language);
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
		$this->data['onlineexams'] 	= $this->online_exam_m->get_order_by_online_exam(array('schoolYearID' => $schoolyearID));
		$this->data['classes'] 		= $this->classes_m->general_get_classes();
		$this->data["subview"] 		= "report/onlineexam/OnlineexamReportView";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'onlineexamID',
				'label' => $this->lang->line('onlineexamreport_onlineexam'),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line('onlineexamreport_classes'),
				'rules' => 'trim|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line('onlineexamreport_section'),
				'rules' => 'trim|xss_clean|numeric'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line('onlineexamreport_student'),
				'rules' => 'trim|xss_clean|numeric'
			),
			array(
				'field' => 'statusID',
				'label' => $this->lang->line('onlineexamreport_status'),
				'rules' => 'trim|xss_clean|numeric|callback_unique_status'
			)
		);

		return $rules;
	}

	protected function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line('onlineexamreport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),array(
				'field' => 'subject',
				'label' => $this->lang->line('onlineexamreport_subject'),
				'rules' => 'trim|required|xss_clean'
			),array(
				'field' => 'message',
				'label' => $this->lang->line('onlineexamreport_message'),
				'rules' => 'trim|xss_clean'
			),array(
				'field' => 'id',
				'label' => $this->lang->line('onlineexamreport_id'),
				'rules' => 'trim|numeric|required|xss_clean'
			),
		);
		return $rules;
	}

	public function unique_data() {
		$onlineexamID = $this->input->post('onlineexamID');
		$classesID = $this->input->post('classesID');

		if($onlineexamID === "0" && $classesID === '0') {
			$this->form_validation->set_message('unique_data', 'The %s field is required.');
			return FALSE;
		}
		return TRUE;
	}

	public function unique_status() {
		$statusID = $this->input->post('statusID');

		if($statusID === "0") {
			$this->form_validation->set_message('unique_status', 'The %s field is required.');
			return FALSE;
		}
		return TRUE;
	}

	public function getSection() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			$allSection = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));

			echo "<option value='0'>", $this->lang->line("onlineexamreport_please_select"),"</option>";
			foreach ($allSection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}

		}
	}

	public function getStudent() {
		$classesID  = $this->input->post('classesID');
		$sectionID  = $this->input->post('sectionID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		$array = [];
		$array['srschoolyearID'] = $schoolyearID;
		if((int)$classesID && $classesID > 0) {
			$array['srclassesID'] = $classesID;
		}

		if((int)$sectionID && $sectionID > 0) {
			$array['srsectionID'] = $sectionID;
		}
		$students = $this->studentrelation_m->general_get_order_by_student($array);
		echo "<option value='0'>", $this->lang->line("onlineexamreport_please_select"),"</option>";
		foreach ($students as $student) {
			echo "<option value=".$student->srstudentID.">".$student->srname."</option>";
		}
	}

	public function getUserList() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run() == FALSE) {
				$retArray = $this->form_validation->error_array();
				$retArray['status'] = FALSE;
			    echo json_encode($retArray);
			    exit;
			} else {
				$onlineexamID 	= $this->input->post('onlineexamID');
				$classesID 		= $this->input->post('classesID');
				$sectionID 		= $this->input->post('sectionID');
				$studentID 		= $this->input->post('studentID');
				$statusID 		= $this->input->post('statusID');
				$schoolyearID   = $this->session->userdata('defaultschoolyearID');
				
				$queryArray = [];
				$examArray = [];
				$queryArray['srschoolyearID'] = $schoolyearID;
				if((int)$onlineexamID && $onlineexamID > 0) {
					$examArray['onlineexamID'] = $onlineexamID;
				}
				if((int)$classesID && $classesID > 0) {
					$queryArray['srclassesID'] = $classesID;
					$examArray['classesID'] = $classesID;
				}
				if((int)$sectionID && $sectionID > 0) {
					$queryArray['srsectionID'] = $sectionID;
					$examArray['sectionID'] = $sectionID;
				}
				if((int)$studentID && $studentID > 0) {
					$queryArray['srstudentID'] = $studentID;
					$examArray['userID'] = $studentID;
				}
				if((int)$statusID) {
					$examArray['statusID'] = $statusID;
				}

				$this->data['onlineexam_user_statuss'] = $this->online_exam_user_status_m->get_join_online_exam_user_status($examArray, $schoolyearID);
				$this->data['onlineexams'] = pluck($this->online_exam_m->get_order_by_online_exam(array('schoolYearID' => $schoolyearID)), 'obj', 'onlineExamID');
				$this->data['students'] = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srstudentID');
				$this->data['classs'] 	= pluck($this->classes_m->general_get_classes(), 'obj', 'classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(), 'obj', 'sectionID');
				$this->data['subjects'] = pluck($this->subject_m->general_get_subject(), 'obj', 'subjectID');
				
				$this->data['onlineexamID'] = $onlineexamID;
				$this->data['classesID']	= $classesID;
				$this->data['sectionID'] 	= $sectionID;
				$this->data['studentID']	= $studentID;
				$retArray['render'] = $this->load->view('report/onlineexam/OnlineexamReport', $this->data, true);
				$retArray['status'] = TRUE;
				echo json_encode($retArray);
			    exit;
			}
		}
	}

	public function result() {
		$onlineExamUserStatusID = htmlentities(escapeString($this->uri->segment(3)));
		if(permissionChecker('onlineexamreport')) {
			if((int)$onlineExamUserStatusID) {
				$onlineExamUserStatus = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('onlineExamUserStatus' => $onlineExamUserStatusID));
				if(count($onlineExamUserStatus)) {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$this->data['onlineExamUserStatus'] = $onlineExamUserStatus;
					$this->data['onlineexam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $onlineExamUserStatus->onlineExamID));
					if((int)$this->data['onlineexam']->subjectID) {
						$this->data['subject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $this->data['onlineexam']->subjectID));
					} else {
						$this->data['subject'] = [];
					}
					$this->data['rank'] = $this->ranking($onlineExamUserStatus->onlineExamID,$onlineExamUserStatusID, $onlineExamUserStatus->userID);
					$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $onlineExamUserStatus->userID,'srschoolyearID'=>$schoolyearID));

					$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['section'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
					$this->data["subview"] = "report/onlineexam/OnlineexamResult";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	private function ranking($onlineexamID, $onlineexamuserstatusID, $userID) {
		$onlineExamUserStatus = $this->online_exam_user_status_m->get_order_by_online_exam_user_status(array('onlineExamID' => $onlineexamID));
		$retArray = [];
		if(count($onlineExamUserStatus)) {
			foreach($onlineExamUserStatus as $result) {
				$retArray[$result->onlineExamUserStatus] = $result->totalObtainedMark;
			}
		}
		arsort($retArray);
		$i = array_search($onlineexamuserstatusID, array_keys($retArray));
		return ++$i;
	}

	public function pdf() {
		$onlineExamUserStatusID = htmlentities(escapeString($this->uri->segment(3)));
		if(permissionChecker('onlineexamreport')) {
			if((int) $onlineExamUserStatusID) {
				$onlineExamUserStatus = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('onlineExamUserStatus' => $onlineExamUserStatusID));
				if(count($onlineExamUserStatus)) {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$this->data['onlineExamUserStatus'] = $onlineExamUserStatus;
					$this->data['onlineexam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $onlineExamUserStatus->onlineExamID));
					if((int)$this->data['onlineexam']->subjectID) {
						$this->data['subject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $this->data['onlineexam']->subjectID));
					} else {
						$this->data['subject'] = [];
					}
					$this->data['rank'] = $this->ranking($onlineExamUserStatus->onlineExamID,$onlineExamUserStatusID, $onlineExamUserStatus->userID);
					$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $onlineExamUserStatus->userID,'srschoolyearID'=>$schoolyearID));
					
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['section'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');

					$this->reportPDF('onlineexamreport.css', $this->data, 'report/onlineexam/OnlineexamResultPDF');
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
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
		$retArray['message']= '';
		if(permissionChecker('onlineexamreport')) {
			if($_POST) {
				$to           			= $this->input->post('to');
				$subject      			= $this->input->post('subject');
				$message 				= $this->input->post('message');
				$onlineExamUserStatusID	= $this->input->post('id');
				
				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == FALSE) {
					$retArray[] = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					if((int) $onlineExamUserStatusID) {
						$onlineExamUserStatus = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('onlineExamUserStatus' => $onlineExamUserStatusID));
						if(count($onlineExamUserStatus)) {
							$schoolyearID = $this->session->userdata('defaultschoolyearID');
							$this->data['onlineExamUserStatus'] = $onlineExamUserStatus;
							$this->data['onlineexam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $onlineExamUserStatus->onlineExamID));
							if((int)$this->data['onlineexam']->subjectID) {
								$this->data['subject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $this->data['onlineexam']->subjectID));
							} else {
								$this->data['subject'] = [];
							}
							$this->data['rank'] = $this->ranking($onlineExamUserStatus->onlineExamID,$onlineExamUserStatusID, $onlineExamUserStatus->userID);
							$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $onlineExamUserStatus->userID,'srschoolyearID'=>$schoolyearID));

							$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
							$this->data['section'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');

							$this->reportSendToMail('onlineexamreport.css', $this->data, 'report/onlineexam/OnlineexamResultPDF', $to, $subject, $message);

							$retArray['status'] = TRUE;
							echo json_encode($retArray);
						    exit;
						} else {
							$retArray['message'] = $this->lang->line("onlineexamreport_onlineexam_found found");
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line("onlineexamreport_id_not found");
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line("onlineexamreport_permissionmethod");
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line("onlineexamreport_permission");
			echo json_encode($retArray);
			exit;
		}
	}
}
