<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendancereport extends Admin_Controller {
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
	
	public $attendancedata = array();

	function __construct() {
		parent::__construct();
		$this->load->model("subject_m");
		$this->load->model('section_m');
		$this->load->model("classes_m");
		$this->load->model("studentrelation_m");
		$this->load->model("sattendance_m");
		$this->load->model("subjectattendance_m");
		$this->load->model("leaveapplication_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('attendancereport', $language);
	}

	public function index() {
		$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data['get_all_holidays'] = $this->getHolidaysSession();
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
				'assets/select2/select2.js'
			)
		);
		
		$this->data["subview"] = "report/attendance/AttendanceReportView";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules($subjectWise = 'subject') {
		$rules = array(
			array(
				'field' => 'attendancetype',
				'label' => $this->lang->line("attendancereport_type"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("attendancereport_classid"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("attendancereport_sectionid"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'date',
				'label' => $this->lang->line("attendancereport_date"),
				'rules' => 'trim|required|xss_clean|callback_valid_date|callback_unique_date|callback_check_holiday|callback_check_weekendday|callback_valid_future_date'
			),
		);
		if($subjectWise == 'subject'){
			$rules[] = array(
				'field' => 'subjectID',
				'label' => $this->lang->line("attendancereport_subjectid"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			);
		}
		return $rules;
	}

	public function send_pdf_to_mail_rules($subjectWise = 'subject') {
		$rules = array(
			array(
				'field' => 'attendancetype',
				'label' => $this->lang->line("attendancereport_type"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("attendancereport_classid"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("attendancereport_sectionid"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'date',
				'label' => $this->lang->line("attendancereport_date"),
				'rules' => 'trim|required|xss_clean|callback_valid_date'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line("attendancereport_to"),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("attendancereport_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("attendancereport_message"),
				'rules' => 'trim|xss_clean'
			),
		);
		if($subjectWise == 'subject'){
			$rules[] = array(
				'field' => 'subjectID',
				'label' => $this->lang->line("attendancereport_subjectid"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			);
		}
		return $rules;
	}

	public function unique_data($data) {
		if($data != "") {
			if($data == "0") {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		} 
		return TRUE;
	}

	public function getAttendacneReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('attendancereport')) {
			if($_POST) {
				$classesID 		= $this->input->post('classesID');
				$sectionID 		= $this->input->post('sectionID');
				$attendancetype	= $this->input->post('attendancetype');
				$date		    = explode('-',$this->input->post('date'));
				$subjectID	    = $this->input->post('subjectID');
				$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
				$rules = $this->rules($this->data['siteinfos']->attendance);
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else{
					$attendances = '';
					$classes 		= $this->classes_m->general_get_single_classes_attendance(array('classesID'=>$classesID));
					$day = 'a'.(int)$date[0];
					$monthyear = $date[1].'-'.$date[2];
					
					if($sectionID == 0) {
						$students = pluck($this->studentrelation_m->general_get_order_by_student_attendance(array('srclassesID' => $classesID , 'srschoolyearID' => $schoolyearID)), 'obj', 'srstudentID');
						$sections = pluck($this->section_m->general_get_order_by_section_attendance(array('classesID' => $classesID)), 'obj', 'sectionID');
						
						if($subjectID) {
							$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classesID, 'subjectID' => $subjectID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));

						} else {
							$attendances = $this->sattendance_m->get_order_by_attendance(array('classesID' => $classesID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));
							
						}
					} else {
						$students = pluck($this->studentrelation_m->general_get_order_by_student_attendance(array('srclassesID' => $classesID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID)), 'obj', 'srstudentID');
						$sections = pluck($this->section_m->general_get_order_by_section_attendance(array( 'classesID' => $classesID, 'sectionID' => $sectionID)), 'obj', 'sectionID');

						if($subjectID) {
							$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classesID, 'sectionID' => $sectionID, 'subjectID' => $subjectID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));
						} else {
							$attendances = $this->sattendance_m->get_order_by_attendance(array('classesID' => $classesID, 'sectionID' => $sectionID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));
						}
					}

					$attendances = pluck($attendances, 'obj', 'studentID');

					$this->data['attendances'] = $attendances;
					$this->data['students']    = $students;
					$this->data['class']       = $classes;
					$this->data['typeSortForm']= $attendancetype;
					$this->data['day']         = $day;
					$this->data['date']        = $this->input->post('date');
					$this->data['classesID']   = $classesID;
					$this->data['subjectID']   = $subjectID;
					$this->data['sections'] = $sections;
					$this->data['sectionID'] = $sectionID;

					if(isset($sections[$sectionID])) {
						$this->data['sectionName'] = $this->lang->line("attendancereport_section")." ".$sections[$sectionID]->section;
					} elseif ($sectionID == 0) {
						$this->data['sectionName'] = $this->lang->line("attendancereport_select_all_section");
					}

					if($attendancetype == 'A') {
						$this->data['attendancetype'] = $this->lang->line("attendancereport_absent");
					} elseif($attendancetype == 'P') {
						$this->data['attendancetype'] = $this->lang->line("attendancereport_present");
					} elseif($attendancetype == 'LE') {
						$this->data['attendancetype'] = $this->lang->line("attendancereport_late_present_with_excuse");
					} elseif($attendancetype == 'L') {
						$this->data['attendancetype'] = $this->lang->line("attendancereport_late_present");
					} else {
						$this->data['attendancetype'] = $this->lang->line("attendancereport_leave");
					}

					$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(1,$schoolyearID);
					$retArray['render'] = $this->load->view('report/attendance/AttendanceReport', $this->data,TRUE);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
				    exit;
				}
			} else {
		    	echo json_encode($retArray);
		    	exit;
			}
		} else {
			echo json_encode($retArray);
			exit;
		}
	}

	public function pdf() {
		if(permissionChecker('attendancereport')) {		
			$attendancetype = htmlentities(escapeString($this->uri->segment(3)));
			$classesID      = htmlentities(escapeString($this->uri->segment(4)));
			$sectionID      = htmlentities(escapeString($this->uri->segment(5)));
			
			$flag = TRUE;
			$dateFlag = TRUE;
			$subjectID = 0;
			if($this->data["siteinfos"]->attendance == 'subject') {
				$subjectID  = htmlentities(escapeString($this->uri->segment(6)));
				$dateString = date('d-m-Y',htmlentities(escapeString($this->uri->segment(7))));
				$date  = explode('-', $dateString);
				$flag = FALSE;
			} else{
				$dateString = date('d-m-Y',htmlentities(escapeString($this->uri->segment(6))));
				$date  = explode('-', $dateString);
				if(!checkdate($date[1], $date[0], $date[2])) {
					$dateFlag = FALSE;
				}
			}

			$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
			if($dateFlag) {
				$classes = $this->classes_m->general_get_single_classes_attendance(array('classesID'=>$classesID));
				$section = $this->section_m->general_get_single_section(array('sectionID'=>$sectionID));
				
				if(count($classes)) {
					if(count($section) || $sectionID == 0) {
						if($attendancetype && ($classesID == 0 || $classesID > 0) && ($sectionID == 0 || $sectionID > 0) && ($flag || $subjectID)) {
							$attendances = '';
							$day = 'a'.(int)$date[0];
							
							$monthyear = $date[1].'-'.$date[2];
							
							if($sectionID == 0) {
								$students = pluck($this->studentrelation_m->general_get_order_by_student_attendance(array('srclassesID' => $classesID , 'srschoolyearID' => $schoolyearID)), 'obj', 'srstudentID');
								$sections = pluck($this->section_m->general_get_order_by_section_attendance(array('classesID' => $classesID)), 'obj', 'sectionID');
								
								if($subjectID) {
									$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classesID, 'subjectID' => $subjectID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));

								} else {
									$attendances = $this->sattendance_m->get_order_by_attendance(array('classesID' => $classesID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));
								}
							} else {
								$students = pluck($this->studentrelation_m->general_get_order_by_student_attendance(array('srclassesID' => $classesID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID)), 'obj', 'srstudentID');
								$sections 	= pluck($this->section_m->general_get_order_by_section_attendance(array( 'classesID' => $classesID, 'sectionID' => $sectionID)), 'obj', 'sectionID');

								if($subjectID) {
									$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classesID, 'sectionID' => $sectionID, 'subjectID' => $subjectID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));
								} else {
									$attendances = $this->sattendance_m->get_order_by_attendance(array('classesID' => $classesID, 'sectionID' => $sectionID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));
								}
							}

							$attendances = pluck($attendances, 'obj', 'studentID');
							

							$this->data['attendances'] = $attendances;
							$this->data['students'] = $students;
							$this->data['class'] = $classes;
							$this->data['typeSortForm'] = $attendancetype;
							$this->data['day'] = $day;
							$this->data['date'] = implode('-', $date);
							$this->data['sections'] = $sections;
							$this->data['sectionID'] = $sectionID;
							$this->data['classesID'] = $classesID;

							if(isset($sections[$sectionID])) {
								$this->data['sectionName'] = $this->lang->line("attendancereport_section")." ".$sections[$sectionID]->section;
							} elseif ($sectionID == 0) {
								$this->data['sectionName'] = $this->lang->line("attendancereport_select_all_section");
							}

							if($attendancetype == 'A') {
								$this->data['attendancetype'] = $this->lang->line("attendancereport_absent");
							} elseif($attendancetype == 'P') {
								$this->data['attendancetype'] = $this->lang->line("attendancereport_present");
							} elseif($attendancetype == 'LE') {
								$this->data['attendancetype'] = $this->lang->line("attendancereport_late_present_with_excuse");
							} elseif($attendancetype == 'L') {
								$this->data['attendancetype'] = $this->lang->line("attendancereport_late_present");
							} else {
								$this->data['attendancetype'] = $this->lang->line("attendancereport_leave");
							}

							$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(1,$schoolyearID);

							$this->reportPDF('attendancereport.css', $this->data, 'report/attendance/AttendanceReportPDF');

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
		if(permissionChecker('attendancereport')) {
			if($_POST) {
				$rules = $this->send_pdf_to_mail_rules($this->data['siteinfos']->attendance);
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray[] = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else{
					$attendancetype = $this->input->post('attendancetype');
					$classesID      = $this->input->post('classesID');
					$sectionID      = $this->input->post('sectionID');
					$to             = $this->input->post('to');
					$subject      = $this->input->post('subject');
					$message      = $this->input->post('message');

					$flag = TRUE;
					$dateFlag = TRUE;
					$subjectID = 0;
					if($this->data["siteinfos"]->attendance == 'subject') {
						$subjectID  = $this->input->post('subjectID');
						$date  = explode('-', $this->input->post('date'));
						$flag = FALSE;
					} else{
						$date  = explode('-', $this->input->post('date'));
						if(!checkdate($date[1], $date[0], $date[2])) {
							$dateFlag = FALSE;
						}
					}

					$schoolyearID 	= $this->session->userdata('defaultschoolyearID');

					if($dateFlag) {
						$classes = $this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
						$section = $this->section_m->general_get_single_section(array('sectionID'=>$sectionID));

						if(count($classes) && $classesID > 0) {
							if(count($section) || $sectionID == 0) {
								if((string) $attendancetype && ((int)$classesID || $classesID > 0) && ((int)$sectionID || $sectionID >= 0) && ($flag || (int)$subjectID)) {
									$attendances = '';
									$day = 'a'.(int)$date[0];
									
									$monthyear = $date[1].'-'.$date[2];
									
									if($sectionID == 0) {
										$students = pluck($this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID , 'srschoolyearID' => $schoolyearID)), 'obj', 'srstudentID');
										$sections = pluck($this->section_m->general_get_order_by_section(array('classesID' => $classesID)), 'obj', 'sectionID');

										if($subjectID) {
											$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classesID, 'subjectID' => $subjectID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));

										} else {
											$attendances = $this->sattendance_m->get_order_by_attendance(array('classesID' => $classesID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));
										}
									} else {
										$students = pluck($this->studentrelation_m->general_get_order_by_student(array( 'srclassesID' => $classesID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID)), 'obj', 'srstudentID');
										$sections 	= pluck($this->section_m->general_get_order_by_section(array( 'classesID' => $classesID, 'sectionID' => $sectionID)), 'obj', 'sectionID');

										if($subjectID) {
											$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classesID, 'sectionID' => $sectionID, 'subjectID' => $subjectID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));
										} else {
											$attendances = $this->sattendance_m->get_order_by_attendance(array('classesID' => $classesID, 'sectionID' => $sectionID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));
										}
									}

									$attendances = pluck($attendances, 'obj', 'studentID');

									$this->data['attendances'] = $attendances;
									$this->data['students'] = $students;
									$this->data['class'] = $classes;
									$this->data['typeSortForm'] = $attendancetype;
									$this->data['day'] = $day;
									$this->data['date'] = implode('-', $date);

									$this->data['sections'] = $sections;
									$this->data['sectionID'] = $sectionID;

									if(isset($sections[$sectionID])) {
										$this->data['sectionName'] = $this->lang->line("attendancereport_section")." ".$sections[$sectionID]->section;
									} elseif ($sectionID == 0) {
										$this->data['sectionName'] = $this->lang->line("attendancereport_select_all_section");
									}

									if($attendancetype == 'A') {
										$this->data['attendancetype'] = $this->lang->line("attendancereport_absent");
									} elseif($attendancetype == 'P') {
										$this->data['attendancetype'] = $this->lang->line("attendancereport_present");
									} elseif($attendancetype == 'LE') {
										$this->data['attendancetype'] = $this->lang->line("attendancereport_late_present_with_excuse");
									} elseif($attendancetype == 'L') {
										$this->data['attendancetype'] = $this->lang->line("attendancereport_late_present");
									}

									$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(1,$schoolyearID);
									$this->reportSendToMail('attendancereport.css', $this->data, 'report/attendance/AttendanceReportPDF', $to, $subject, $message);
									$retArray['status'] = TRUE;
									echo json_encode($retArray);
				    				exit;

								} else {
									$retArray['message'] = $this->lang->line("attendancereport_data_not_found");
									echo json_encode($retArray);
				    				exit;
								}
							} else {
								$retArray['message'] = $this->lang->line("attendancereport_section_not_found");
								echo json_encode($retArray);
			    				exit;
							}
						} else {
							$retArray['message'] = $this->lang->line("attendancereport_class_not_found");
							echo json_encode($retArray);
		    				exit;
						}
					}
				}
			} else {
				$retArray['message'] = $this->lang->line("attendancereport_permissionmethod");
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line("attendancereport_permission");
			echo json_encode($retArray);
			exit;
		}
	}
	
	public function getSection() {
		$id = $this->input->post('id');
		if((int)$id) {
			$sections = $this->section_m->general_get_order_by_section(array('classesID' => $id));
			echo "<option value='0'>", $this->lang->line("attendancereport_please_select"),"</option>";
			foreach ($sections as $section) {
				echo "<option value=\"$section->sectionID\">",$section->section,"</option>";
			}

		}
	}

	public function getSubject() {
		$classID = $this->input->post('classID');
		if((int)$classID) {
			$allSubject = $this->subject_m->general_get_order_by_subject(array('classesID' => $classID));
			echo "<option value=''>", $this->lang->line("attendancereport_please_select"),"</option>";
			foreach ($allSubject as $value) {
				echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
			}

		}
	}


	public function valid_date($date) {
		if(!empty($date)) {
			if(strlen($date) == 10) {
				$expDate = explode('-', $date);
				if(checkdate($expDate[1], $expDate[0], $expDate[2])) {
					return TRUE;
				} else {
					$this->form_validation->set_message('valid_date', 'The %s is dd-mm-yyyy');
					return FALSE;
				}
			} else {
				$this->form_validation->set_message('valid_date', 'The %s is dd-mm-yyyy');
				return FALSE;
			}
		} 
		return TRUE;
	}

	public function xlsx() {
		if(permissionChecker('attendancereport')) {

			$this->load->library('phpspreadsheet');
			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$sheet->getDefaultColumnDimension()->setWidth(30);
			$sheet->getDefaultRowDimension()->setRowHeight(50);

			$sheet->getPageSetup()->setFitToWidth(1);
			$sheet->getPageSetup()->setFitToHeight(0);

			$sheet->getPageMargins()->setTop(1);
			$sheet->getPageMargins()->setRight(0.75);
			$sheet->getPageMargins()->setLeft(0.75);
			$sheet->getPageMargins()->setBottom(1);

			$data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="attendancereport.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0

			$this->phpspreadsheet->output($this->phpspreadsheet->spreadsheet);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		} 

	}

	private function xmlData() {

		$attendancetype = htmlentities(escapeString($this->uri->segment(3)));
		$classesID      = htmlentities(escapeString($this->uri->segment(4)));
		$sectionID      = htmlentities(escapeString($this->uri->segment(5)));

		$subjectID = 0;
		$flag = TRUE;
		$dateFlag = TRUE;
		if($this->data["siteinfos"]->attendance == 'subject') {
			$subjectID  = htmlentities(escapeString($this->uri->segment(6)));
			$dateString = date('d-m-Y',htmlentities(escapeString($this->uri->segment(7))));
			$date  = explode('-', $dateString);
			$flag = FALSE;
		} else{
			$dateString = date('d-m-Y',htmlentities(escapeString($this->uri->segment(6))));
			$date  = explode('-', $dateString);
			if(!checkdate($date[1], $date[0], $date[2])) {
				$dateFlag = FALSE;
			}
		}

		$schoolyearID 	= $this->session->userdata('defaultschoolyearID');

		if($dateFlag) {

			$classes = $this->classes_m->general_get_single_classes(array('classesID'=> $classesID));
			$section = $this->section_m->general_get_single_section(array('sectionID'=> $sectionID));

			if(count($classes) && $classesID > 0) {
				if(count($section) || $sectionID == 0) {
					if((string) $attendancetype && ((int)$classesID || $classesID > 0) && ((int)$sectionID || $sectionID >= 0) && ($flag || (int)$subjectID)) {

						$attendances = '';
						$day = 'a'.(int)$date[0];
						
						$monthyear = $date[1].'-'.$date[2];
						
						if($sectionID == 0) {
							$students = pluck($this->studentrelation_m->general_get_order_by_student(array( 'srclassesID' => $classesID , 'srschoolyearID' => $schoolyearID)), 'obj', 'srstudentID');
							$sections = pluck($this->section_m->general_get_order_by_section(array( 'classesID' => $classesID)), 'obj', 'sectionID');

							if($subjectID) {
								$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classesID, 'subjectID' => $subjectID, 'monthyear' => $monthyear,'schoolyearID' => $schoolyearID));
							} else {
								$attendances = $this->sattendance_m->get_order_by_attendance(array('classesID' => $classesID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));
							}
						} else {
							$students = pluck($this->studentrelation_m->general_get_order_by_student(array( 'srclassesID' => $classesID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID)), 'obj', 'srstudentID');
							$sections 	= pluck($this->section_m->general_get_order_by_section(array( 'classesID' => $classesID, 'sectionID' => $sectionID)), 'obj', 'sectionID');

							if($subjectID) {
								$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classesID, 'sectionID' => $sectionID, 'subjectID' => $subjectID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));
							} else {
								$attendances = $this->sattendance_m->get_order_by_attendance(array('classesID' => $classesID, 'sectionID' => $sectionID, 'monthyear' => $monthyear, 'schoolyearID' => $schoolyearID));
							}
						}

						$attendances = pluck($attendances, 'obj', 'studentID');

						$this->data['attendances'] = $attendances;
						$this->data['students'] = $students;
						$this->data['class'] = $classes;
						$this->data['typeSortForm'] = $attendancetype;
						$this->data['day'] = $day;
						$this->data['date'] = implode('-', $date);

						$this->data['sections'] = $sections;
						$this->data['sectionID'] = $sectionID;

						if(isset($sections[$sectionID])) {
							$this->data['sectionName'] = $this->lang->line("attendancereport_section")." ".$sections[$sectionID]->section;
						} elseif ($sectionID == 0) {
							$this->data['sectionName'] = $this->lang->line("attendancereport_select_all_section");
						}

						if($attendancetype == 'A') {
							$this->data['attendancetype'] = $this->lang->line("attendancereport_absent");
						} elseif($attendancetype == 'P') {
							$this->data['attendancetype'] = $this->lang->line("attendancereport_present");
						} elseif($attendancetype == 'LE') {
							$this->data['attendancetype'] = $this->lang->line("attendancereport_late_present_with_excuse");
						} elseif($attendancetype == 'L') {
							$this->data['attendancetype'] = $this->lang->line("attendancereport_late_present");
						}

						$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(1,$schoolyearID);


						return $this->generateXML($this->data);

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
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	
	private function generateXML($data) {

		extract($data);

		if(count($students)) {
			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			if($sectionID == 0) {
				$countColumn = 8;
			} else {
				$countColumn = 7;
			}

			$headerColumn = 'A';

			$row =1;

			for($j = 1; $j < $countColumn ; $j++) {
				$headerColumn++;
			}

			$classLang = $this->lang->line('attendancereport_class');
			$className = isset($class) ? $class->classes : '';
			$sectionLang = $this->lang->line('attendancereport_section');

			if($sectionID > 0) {
				$sectionName = isset($sectionID) ? $sections[$sectionID]->section : '';
			} else {
				$sectionName = $this->lang->line('attendancereport_select_all_section');
			}

			$sheet->setCellValue('A'.$row, $classLang." : ".$className);
			$sheet->setCellValue($headerColumn.$row, $sectionLang." : ".$sectionName);
			
			$column = "A";
			$row    = "2";
			$headers = array();
		    $headers['slno'] = $this->lang->line('attendancereport_slno');
		    $headers['photo'] = $this->lang->line('attendancereport_photo');
		    $headers['name'] = $this->lang->line('attendancereport_name');
		    $headers['registerNo'] = $this->lang->line('attendancereport_registerNo');
		    if($sectionID == 0) {
		    	$headers['section'] = $this->lang->line('attendancereport_section');
		    }
		    $headers['roll'] = $this->lang->line('attendancereport_roll');
		    $headers['email'] = $this->lang->line('attendancereport_email');
		    $headers['phone'] = $this->lang->line('attendancereport_phone');
		    $sheet->getColumnDimension('A')->setWidth(15);
		    foreach($headers as $headerKey => $header) {
		    	if($headerKey == 'photo') {
					$sheet->getColumnDimension('B')->setWidth(10);
		    	}
		    	$sheet->setCellValue($column.$row,$header);
		    	$column++;
		    }

		    $i=1;
		    $j=1;
		    $studentsAttendancesData = array();
		    $attendancedate = date('d-m-Y',strtotime($date));
			foreach($students as $studentKey => $student) {
				$userleaveapplications = isset($leaveapplications[$student->srstudentID]) ? $leaveapplications[$student->srstudentID] : [];

                if((in_array($attendancedate, $userleaveapplications) && ($typeSortForm != 'LA'))) {
                    continue;
                } elseif(($typeSortForm == 'LA') && (!in_array($attendancedate, $userleaveapplications))) {
                    continue;
                } elseif(isset($attendances[$student->srstudentID])) {
                    $attendanceDay = $attendances[$student->srstudentID]->$day;
                    if($typeSortForm == 'P' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='LE' || $attendanceDay =='L' )) {
                        continue;
                    } elseif($typeSortForm == 'LE' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='P' || $attendanceDay =='L' )) {
                        continue;
                    } elseif($typeSortForm == 'L' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='LE' || $attendanceDay =='P' )) {
                        continue;
                    } elseif($typeSortForm == 'A' && ($attendanceDay == 'P' || $attendanceDay =='LE' || $attendanceDay =='L' )) {
                        continue;
                    } 
                } elseif($typeSortForm == 'P' || $typeSortForm == 'LE' || $typeSortForm == 'L') {
                    continue;
                }

		        $studentsAttendancesData[$i]['slno'] = $j;
		        $studentsAttendancesData[$i]['photo'] = $student->photo;
		        $studentsAttendancesData[$i]['name'] = $student->srname;
		        $studentsAttendancesData[$i]['registerNo'] = $student->srregisterNO;
		        if($sectionID == 0) {
			        $studentsAttendancesData[$i]['sectionID'] = $sections[$student->srsectionID]->section;
		        }
		        $studentsAttendancesData[$i]['roll'] = $student->srroll;
		        $studentsAttendancesData[$i]['email'] = $student->email;
		        $studentsAttendancesData[$i]['phone'] = $student->phone;
		        $i++;
		        $j++;
		    }

		    $row = 3;
		    foreach($studentsAttendancesData as $single_studentsAttendancesData) {
		    	$column = 'A';
		    	foreach($single_studentsAttendancesData as $single_AttendancesDataKey => $attendanceValue) {
		    		if($single_AttendancesDataKey == 'photo') {
			    		if (file_exists(FCPATH.'uploads/images/'.$attendanceValue)) {
						    $this->phpspreadsheet->draw_images(FCPATH.'uploads/images/'.$attendanceValue, $column.$row,$sheet, 40);
						} else {
							$sheet->setCellValue($column.$row, $attendanceValue);
						}
					} else {
		    			$sheet->setCellValue($column.$row, $attendanceValue);
					}

		    		$column++;
		    	}
		    	$row++;
		    }

		    //Style Excel Sheet
		   	$styleArray = [
			    'font' => [
			        'bold' => FALSE,
			        'name' => 'Roboto Mono'
			    ],
			    'alignment' =>[
			    	'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			    	'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			    'borders' => [
		            'allBorders' => [
		                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		            ]
		        ]
			];

			$row = $row-1;
			$sheet->getStyle('A1:'.$headerColumn.$row)->applyFromArray($styleArray);

			$styleArray = [
			    'font' => [
			        'bold' => true,
			        'name' => 'Roboto Mono'
			    ]
			];
			$sheet->getStyle('A1:'.$headerColumn.'2')->applyFromArray($styleArray);

			$headerColumn = chr(ord($headerColumn) - 1);  //Decreament Header Section Column

			$mergeCellsColumn = $headerColumn.'1';
			$sheet->mergeCells("B1:$mergeCellsColumn");

		} else {
			redirect('attendancereport');
		}
	}

	public function unique_date() {
		$date   = strtotime($this->input->post('date'));
		$startingdate = strtotime($this->data['schoolyearsessionobj']->startingdate);
		$endingdate = strtotime($this->data['schoolyearsessionobj']->endingdate);

		if($date != '') {
			if(($date < $startingdate) || ($date > $endingdate)) {
				$this->form_validation->set_message("unique_date", "The to date is invalid .");
			    return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	public function check_holiday($date) {
		$getHolidays = $this->getHolidaysSession();
		$getHolidaysArray = explode('","', $getHolidays);

		if(count($getHolidaysArray)) {
			if(in_array($date, $getHolidaysArray)) {
				$this->form_validation->set_message('check_holiday','The %s field given holiday.');
				return FALSE;
			} else {
				return TRUE;
			}
		}
		return TRUE;
	}

	public function check_weekendday($date) {
		$getWeekendDays = $this->getWeekendDaysSession();
		if(count($getWeekendDays)) {
			if(in_array($date, $getWeekendDays)) {
				$this->form_validation->set_message('check_holiday','The %s field given weekenday.');
				return FALSE;
			} else {
				return TRUE;
			}
		}
		return TRUE;
	}

	public function valid_future_date($date) {
		$presentdate = date('Y-m-d');
		$date = date("Y-m-d", strtotime($date));
		if($date > $presentdate) {
			$this->form_validation->set_message('valid_future_date','The %s field does not given future date.');
			return FALSE;
		}
		return TRUE;
	}

	private function leave_applications_date_list_by_user_and_schoolyear($usertype, $schoolyearID) {
		$queryArray = [];
		$queryArray['usercheck'] = FALSE;
		if($usertype == 1) {
			$queryArray['create_usertypeID'] = 3;
		} elseif($usertype == 2) {
			$queryArray['create_usertypeID'] = 2;

		} elseif ($usertype == 3) {
			$queryArray['usercheck'] = TRUE;
		}
		$queryArray['status'] = 1;
		$queryArray['schoolyearID'] = $schoolyearID;

		$leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication_where_in($queryArray);

		$retArray = [];
		if(count($leaveapplications)) {
			$oneday    = 60*60*24;
			foreach($leaveapplications as $leaveapplication) {
			    for($i=strtotime($leaveapplication->from_date); $i<= strtotime($leaveapplication->to_date); $i= $i+$oneday) {
			        $retArray[$leaveapplication->create_userID][] = date('d-m-Y', $i);
			    }
			}
		}
		return $retArray;
	}

}
