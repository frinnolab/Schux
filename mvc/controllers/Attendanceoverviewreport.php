<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendanceoverviewreport extends Admin_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model("subject_m");
		$this->load->model('section_m');
		$this->load->model("classes_m");
		$this->load->model("teacher_m");
		$this->load->model("user_m");
		$this->load->model("sattendance_m");
		$this->load->model("subjectattendance_m");
		$this->load->model("studentrelation_m");
		$this->load->model("leaveapplication_m");
		$this->load->model("tattendance_m");
		$this->load->model("uattendance_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('attendanceoverviewreport', $language);
	}

	public function rules($usertype) {
		$rules = array(
			array(
				'field' => 'usertype',
				'label' => $this->lang->line('attendanceoverviewreport_reportfor'),
				'rules' =>'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'monthID',
				'label' => $this->lang->line('attendanceoverviewreport_month'),
				'rules' =>'trim|required|xss_clean|callback_valid_date'
			),
			array(
				'field' => 'userID',
				'label' => $this->lang->line('attendanceoverviewreport_user'),
				'rules' =>'trim|xss_clean'
			),
		);

		if($usertype == 1) {
			$rules[] = array(
				'field' => 'classesID',
				'label' => $this->lang->line('attendanceoverviewreport_class'),
				'rules' =>'trim|required|xss_clean|callback_unique_data'
			);
			$rules[] = array(
				'field' => 'sectionID',
				'label' => $this->lang->line('attendanceoverviewreport_section'),
				'rules' =>'trim|xss_clean'
			);
			if($this->data["siteinfos"]->attendance == 'subject') {
				$rules[] = array(
					'field' => 'subjectID',
					'label' => $this->lang->line('attendanceoverviewreport_subject'),
					'rules' =>'trim|required|xss_clean|callback_unique_data'
				);
			}
		}
		return $rules;
	}

	public function send_pdf_to_mail_rules($usertype) {
		$rules = array(
			array(
				'field' => 'usertype',
				'label' => $this->lang->line('attendanceoverviewreport_reportfor'),
				'rules' =>'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'monthID',
				'label' => $this->lang->line('attendanceoverviewreport_month'),
				'rules' =>'trim|required|xss_clean|callback_valid_date'
			),
			array(
				'field' => 'userID',
				'label' => $this->lang->line('attendanceoverviewreport_user'),
				'rules' =>'trim|xss_clean'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line('attendanceoverviewreport_to'),
				'rules' =>'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line('attendanceoverviewreport_subject'),
				'rules' =>'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line('attendanceoverviewreport_message'),
				'rules' =>'trim|xss_clean'
			),
		);

		if($usertype == 1) {
			$rules[] = array(
				'field' => 'classesID',
				'label' => $this->lang->line('attendanceoverviewreport_class'),
				'rules' =>'trim|required|xss_clean|callback_unique_data'
			);
			$rules[] = array(
				'field' => 'sectionID',
				'label' => $this->lang->line('attendanceoverviewreport_section'),
				'rules' =>'trim|xss_clean'
			);
			if($this->data["siteinfos"]->attendance == 'subject') {
				$rules[] = array(
					'field' => 'subjectID',
					'label' => $this->lang->line('attendanceoverviewreport_subject'),
					'rules' =>'trim|required|xss_clean|callback_unique_data'
				);
			}
		}
		return $rules;
	}

	public function index() {
		$this->data['classes'] = $this->classes_m->general_get_classes();
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
		$this->data["subview"] = "report/attendanceoverview/AttendanceOverviewReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getSection() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			$sections = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("attendanceoverviewreport_please_select"),"</option>";
			foreach ($sections as $section) {
				echo "<option value=\"$section->sectionID\">".$section->section."</option>";
			}

		}
	}

	public function getSubject() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			$subjects = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("attendanceoverviewreport_please_select"),"</option>";
			foreach ($subjects as $subject) {
				echo "<option value=\"$subject->subjectID\">".$subject->subject."</option>";
			}
		}
	}

	public function getStudent() {
		$usertype  = $this->input->post('usertype');
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$usertype && (int)$classesID && (int)$sectionID) {
			echo "<option value='0'>".$this->lang->line("attendanceoverviewreport_please_select")."</option>";
			if($usertype == 1) {
				$students = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID,'srsectionID'=>$sectionID,'srschoolyearID' => $schoolyearID));
				foreach ($students as $student) {
					echo "<option value=\"$student->srstudentID\">".$student->srname."</option>";
				}
			}
		}
	}

	public function getUser() {
		$usertype  = $this->input->post('usertype');
		if((int)$usertype) {
			echo "<option value='0'>".$this->lang->line("attendanceoverviewreport_please_select")."</option>";
			if($usertype == 2) {
				$teachers = $this->teacher_m->general_get_teacher();
				foreach ($teachers as $teacher) {
					echo "<option value=\"$teacher->teacherID\">".$teacher->name."</option>";
				}
			} elseif($usertype ==3 ) {
				$users = $this->user_m->get_user();
				foreach ($users as $user) {
					echo "<option value=\"$user->userID\">".$user->name."</option>";
				}	
			}
		}
	}

	public function getAttendacneOverviewReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('attendancereport')) {
			if($_POST) {
				$usertype = $this->input->post('usertype');
				$classesID = $this->input->post('classesID');
				$sectionID = $this->input->post('sectionID');
				$subjectID = $this->input->post('subjectID');
				$userID = $this->input->post('userID');
				$monthID = $this->input->post('monthID');

				$rules = $this->rules($usertype);
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->data['usertype'] = $usertype;
					$this->data['classesID'] = $classesID;
					$this->data['sectionID'] = $sectionID;
					$this->data['subjectID'] = $subjectID;
					$this->data['userID']    = $userID;
					$this->data['monthID']   = $monthID;
					$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
					if($usertype == 1) {
						$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_student');
					} elseif($usertype == 2) {
						$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_teacher');
					} else {
						$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_user');
					}
					$queryArray = $this->queryArray($this->input->post());
					$userQueryArray = $this->userQueryArray($this->input->post());

					if($usertype == '1') {
						if($this->data["siteinfos"]->attendance == 'subject') {
							$attendances =  pluck($this->subjectattendance_m->get_order_by_sub_attendance($queryArray),'obj','studentID');
							$this->data['subjects'] = pluck($this->subject_m->general_get_order_by_subject(array('classesID' => $classesID)),'subject','subjectID');
						} else {
							$attendances =  pluck($this->sattendance_m->get_order_by_attendance($queryArray),'obj','studentID');
						}
						$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(1,$schoolyearID);
						$this->data['users'] = $this->studentrelation_m->general_get_order_by_student($userQueryArray);
					} elseif($usertype == '2') {
						$attendances =  pluck($this->tattendance_m->get_order_by_tattendance($queryArray),'obj','teacherID');
						$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(2,$schoolyearID);
						$this->data['users'] = $this->teacher_m->general_get_order_by_teacher($userQueryArray);
					} elseif($usertype == '3') {
						$attendances = pluck($this->uattendance_m->get_order_by_uattendance($queryArray),'obj','userID');
						$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(3,$schoolyearID);
						$this->data['users'] = $this->user_m->get_order_by_user($userQueryArray);
					}
					$this->data['attendances'] = $attendances;
					$this->data['getHolidays'] = explode('","', $this->getHolidaysSession());
					$this->data['getWeekendDays'] = $this->getWeekendDaysSession();
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
					$retArray['render'] = $this->load->view('report/attendanceoverview/AttendanceOverviewReport',$this->data,true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
				    exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('attendanceoverviewreport_permissionmethod');;
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('attendanceoverviewreport_permission');;
			echo json_encode($retArray);
			exit;
		}
	}

	private function userQueryArray($posts) {
		$userQueryArray = [];
		if($posts['usertype'] == '1') {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$userQueryArray['srschoolyearID'] = $schoolyearID; 				
			$userQueryArray['srclassesID']     = $posts['classesID'];
			if($posts['sectionID'] > 0) {
				$userQueryArray['srsectionID'] = $posts['sectionID'];
			}
			if($posts['userID'] > 0) {
				$userQueryArray['srstudentID'] = $posts['userID'];
			}
		} elseif($posts['usertype'] == '2') {
			if($posts['userID'] > 0) {
				$userQueryArray['teacherID'] = $posts['userID'];
			}
		} elseif($posts['usertype'] == '3') {
			if($posts['userID'] > 0) {
				$userQueryArray['userID'] = $posts['userID'];
			}
		}
		return $userQueryArray;
	}

	private function queryArray($posts) {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		
		$queryArray['schoolyearID'] = $schoolyearID; 				
		if($posts['monthID'] !='') {
			$queryArray['monthyear'] = $posts['monthID'];
		}
		if($posts['usertype'] == '1') {
			$queryArray['classesID']     = $posts['classesID'];
			if($posts['sectionID'] > 0) {
				$queryArray['sectionID'] = $posts['sectionID'];
			}
			if($posts['userID'] > 0) {
				$queryArray['studentID'] = $posts['userID'];
			}
			if($this->data["siteinfos"]->attendance == 'subject') {
				$queryArray['subjectID'] = $posts['subjectID'];
			}
		} elseif($posts['usertype'] == '2') {
			if($posts['userID'] > 0) {
				$queryArray['teacherID'] = $posts['userID'];
			}
		} elseif($posts['usertype'] == '3') {
			if($posts['userID'] > 0) {
				$queryArray['userID'] = $posts['userID'];
			}
		}
		return $queryArray;
	}

	public function pdf() {
		if(permissionChecker('attendanceoverviewreport')) {		
			$usertype   = htmlentities(escapeString($this->uri->segment(3)));
			$classesID  = htmlentities(escapeString($this->uri->segment(4)));
			$sectionID  = htmlentities(escapeString($this->uri->segment(5)));

			$flag = TRUE;
			$subjectID = 0;
			if($this->data["siteinfos"]->attendance == 'subject') {
				$subjectID  = htmlentities(escapeString($this->uri->segment(6)));
				$userID     = htmlentities(escapeString($this->uri->segment(7)));
				$monthID    = date('d-m-Y',(int)htmlentities(escapeString($this->uri->segment(8))));
				if($usertype == 1) {
					$flag = FALSE;
				}
			} else{
				$userID     = htmlentities(escapeString($this->uri->segment(6)));
				$monthID    = date('d-m-Y',(int)htmlentities(escapeString($this->uri->segment(7))));
			}

			$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
			$monthyears     = explode('-', $monthID);
			$monthyear      = $monthyears[1].'-'.$monthyears[2];

			if((int)$usertype && ((int)$classesID || $classesID >= 0) && ((int)$sectionID || $sectionID >= 0) && ($flag || (int)$subjectID) && ((int)$userID || $userID >= 0) && (int)strtotime($monthID) ) {
				$this->data['usertype']  = $usertype;
				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;
				if($this->data["siteinfos"]->attendance == 'subject') {
					$this->data['subjectID'] = $subjectID;
				}
				$this->data['userID']    = $userID;
				$this->data['monthID']   = $monthyear;
				
				if($usertype == 1) {
					$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_student');
				} elseif($usertype == 2) {
					$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_teacher');
				} else {
					$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_user');
				}

				$postsArray['usertype']  = $usertype;
				$postsArray['classesID'] = $classesID;
				$postsArray['sectionID'] = $sectionID;
				if($this->data["siteinfos"]->attendance == 'subject') {
					$postsArray['subjectID'] = $subjectID;
				}
				$postsArray['userID']    = $userID;
				$postsArray['monthID']   = $monthyear;

				$queryArray = $this->queryArray($postsArray);
				$userQueryArray = $this->userQueryArray($postsArray);

				if($usertype == '1') {
					if($this->data["siteinfos"]->attendance == 'subject') {
						$attendances =  pluck($this->subjectattendance_m->get_order_by_sub_attendance($queryArray),'obj','studentID');
						$this->data['subjects'] = pluck($this->subject_m->general_get_order_by_subject(array('classesID' => $classesID)),'subject','subjectID');
					} else {
						$attendances =  pluck($this->sattendance_m->get_order_by_attendance($queryArray),'obj','studentID');
					}
					$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(1,$schoolyearID);
					$this->data['users'] = $this->studentrelation_m->general_get_order_by_student($userQueryArray);
				} elseif($usertype == '2') {
					$attendances =  pluck($this->tattendance_m->get_order_by_tattendance($queryArray),'obj','teacherID');
					$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(2,$schoolyearID);
					$this->data['users'] = $this->teacher_m->general_get_order_by_teacher($userQueryArray);
				} elseif($usertype == '3') {
					$attendances = pluck($this->uattendance_m->get_order_by_uattendance($queryArray),'obj','userID');
					$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(3,$schoolyearID);
					$this->data['users'] = $this->user_m->get_order_by_user($userQueryArray);
				}
				$this->data['attendances'] = $attendances;
				$this->data['getHolidays'] = explode('","', $this->getHolidaysSession());
				$this->data['getWeekendDays'] = $this->getWeekendDaysSession();
				$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
				$this->reportPDF('attendanceoverviewreport.css', $this->data, 'report/attendanceoverview/AttendanceOverviewReportPDF','view','a4','l');
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
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

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('attendanceoverviewreport')) {
			if($_POST) {
				$usertype  = $this->input->post('usertype');
				$classesID = $this->input->post('classesID');
				$sectionID = $this->input->post('sectionID');
				$subjectID = $this->input->post('subjectID');
				$userID    = $this->input->post('userID');
				$monthID   = $this->input->post('monthID');
				$to        = $this->input->post('to');
				$subject   = $this->input->post('subject');
				$message   = $this->input->post('message');
				$rules = $this->send_pdf_to_mail_rules($usertype);
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				} else {
					$this->data['usertype']  = $usertype;
					$this->data['classesID'] = $classesID;
					$this->data['sectionID'] = $sectionID;
					$this->data['subjectID'] = $subjectID;
					$this->data['userID']    = $userID;
					$this->data['monthID']   = $monthID;
					$schoolyearID   = $this->session->userdata('schoolyearID');

					if($usertype == 1) {
						$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_student');
					} elseif($usertype == 2) {
						$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_teacher');
					} else {
						$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_user');
					}
					$queryArray = $this->queryArray($this->input->post());
					$userQueryArray = $this->userQueryArray($this->input->post());

					if($usertype == '1') {
						if($this->data["siteinfos"]->attendance == 'subject') {
							$attendances =  pluck($this->subjectattendance_m->get_order_by_sub_attendance($queryArray),'obj','studentID'
								);
							$this->data['subjects'] = pluck($this->subject_m->get_order_by_subject(array('classesID' => $classesID)),'subject','subjectID');
						} else {
							$attendances =  pluck($this->sattendance_m->get_order_by_attendance($queryArray),'obj','studentID');
						}
						$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(1,$schoolyearID);
						$this->data['users'] = $this->studentrelation_m->general_get_order_by_student($userQueryArray);
					} elseif($usertype == '2') {
						$attendances =  pluck($this->tattendance_m->get_order_by_tattendance($queryArray),'obj','teacherID');
						$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(2,$schoolyearID);
						$this->data['users'] = $this->teacher_m->general_get_order_by_teacher($userQueryArray);
					} elseif($usertype == '3') {
						$attendances = pluck($this->uattendance_m->get_order_by_uattendance($queryArray),'obj','userID');
						$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(3,$schoolyearID);
						$this->data['users'] = $this->user_m->get_order_by_user($userQueryArray);
					}
					$this->data['attendances'] = $attendances;
					$this->data['getHolidays'] = explode('","', $this->getHolidaysSession());
					$this->data['getWeekendDays'] = $this->getWeekendDaysSession();
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->reportSendToMail('attendanceoverviewreport.css', $this->data, 'report/attendanceoverview/AttendanceOverviewReportPDF', $to, $subject, $message,'a4','l');
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
    				exit;
				}
			}  else {
				$retArray['message'] = $this->lang->line('attendanceoverviewreport_permissionmethod');;
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('attendanceoverviewreport_permission');;
			echo json_encode($retArray);
			exit;
		}
	}

	public function valid_date() {
		$date = $this->input->post('monthID');
		$date = '01-'.$date;
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
		if(permissionChecker('attendanceoverviewreport')) {
			$this->load->library('phpspreadsheet');
			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$sheet->getDefaultColumnDimension()->setWidth(5);
			$sheet->getDefaultRowDimension()->setRowHeight(25);

			$sheet->getPageSetup()->setFitToWidth(1);
			$sheet->getPageSetup()->setFitToHeight(0);

			$sheet->getPageMargins()->setTop(1);
			$sheet->getPageMargins()->setRight(0.75);
			$sheet->getPageMargins()->setLeft(0.75);
			$sheet->getPageMargins()->setBottom(1);

			$data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="attendanceoverviewreport.xlsx"');
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
		$usertype   = htmlentities(escapeString($this->uri->segment(3)));
		$classesID  = htmlentities(escapeString($this->uri->segment(4)));
		$sectionID  = htmlentities(escapeString($this->uri->segment(5)));
		$flag = TRUE;
		$subjectID = 0;
		if($this->data["siteinfos"]->attendance == 'subject') {
			$subjectID  = htmlentities(escapeString($this->uri->segment(6)));
			$userID     = htmlentities(escapeString($this->uri->segment(7)));
			$monthID    = date('d-m-Y',(int)htmlentities(escapeString($this->uri->segment(8))));
			if($usertype == 1) {
				$flag = FALSE;
			}
		} else{
			$userID     = htmlentities(escapeString($this->uri->segment(6)));
			$monthID    = date('d-m-Y',(int)htmlentities(escapeString($this->uri->segment(7))));
		}

		$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
		$monthyears     = explode('-', $monthID);
		$monthyear      = $monthyears[1].'-'.$monthyears[2];

		if((int)$usertype && ((int)$classesID || $classesID >= 0) && ((int)$sectionID || $sectionID >= 0) && ($flag || (int)$subjectID) && ((int)$userID || $userID >= 0) && (int)strtotime($monthID) ) {


			$this->data['usertype']  = $usertype;
			$this->data['classesID'] = $classesID;
			$this->data['sectionID'] = $sectionID;
			if($this->data["siteinfos"]->attendance == 'subject') {
				$this->data['subjectID'] = $subjectID;
			}
			$this->data['userID']    = $userID;
			$this->data['monthID']   = $monthyear;

			if($usertype == 1) {
				$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_student');
			} elseif($usertype == 2) {
				$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_teacher');
			} else {
				$this->data['attendanceoverviewreport_reportfor'] = $this->lang->line('attendanceoverviewreport_user');
			}

			$postsArray['usertype']  = $usertype;
			$postsArray['classesID'] = $classesID;
			$postsArray['sectionID'] = $sectionID;
			if($this->data["siteinfos"]->attendance == 'subject') {
				$postsArray['subjectID'] = $subjectID;
			}
			$postsArray['userID']    = $userID;
			$postsArray['monthID']   = $monthyear;

			$queryArray = $this->queryArray($postsArray);
			$userQueryArray = $this->userQueryArray($postsArray);

			if($usertype == '1') {
				if($this->data["siteinfos"]->attendance == 'subject') {
					$attendances =  pluck($this->subjectattendance_m->get_order_by_sub_attendance($queryArray),'obj','studentID');
				} else {
					$attendances =  pluck($this->sattendance_m->get_order_by_attendance($queryArray),'obj','studentID');
				}
				$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(1,$schoolyearID);
				$this->data['users'] = $this->studentrelation_m->general_get_order_by_student($userQueryArray);
			} elseif($usertype == '2') {
				$attendances =  pluck($this->tattendance_m->get_order_by_tattendance($queryArray),'obj','teacherID');
				$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(2,$schoolyearID);
				$this->data['users'] = $this->teacher_m->general_get_order_by_teacher($userQueryArray);
			} elseif($usertype == '3') {
				$attendances = pluck($this->uattendance_m->get_order_by_uattendance($queryArray),'obj','userID');
				$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear(3,$schoolyearID);
				$this->data['users'] = $this->user_m->get_order_by_user($userQueryArray);
			}
			$this->data['attendances'] = $attendances;
			$this->data['getHolidays'] = explode('","', $this->getHolidaysSession());
			$this->data['getWeekendDays'] = $this->getWeekendDaysSession();
			$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
			$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
			return $this->generateXML($this->data);
		} else {
			redirect('attendanceoverviewreport');
		}
	}
	
	private function generateXML($data) {
		extract($data);
		if(count($users)) {
			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$monthday = explode('-', $monthID);
			$getDayOfMonth = date('t', mktime(0, 0, 0, $monthday[0], 1, $monthday[1])); 
			$headerColumn = 'A';

			if($usertype == 1) {
				$col = 10;
			} else {
				$col = 9;
			}

			$countColumn  = $getDayOfMonth + $col;			
			for($j = 1;$j < $countColumn; $j++) {
				$headerColumn++;
			}

			$sheet->getColumnDimension('A')->setWidth(15);
			$sheet->getColumnDimension('B')->setWidth(25);

			if($usertype == 1) {
				$classLang = $this->lang->line('attendanceoverviewreport_class');
				$className = isset($classes[$classesID]) ? $classes[$classesID] : '';
				$sectionLang = $this->lang->line('attendanceoverviewreport_section');
				if($sectionID > 0) {
					$sectionName = isset($sections[$sectionID]) ? $sections[$sectionID] : '';
				} else {
					$sectionName = $this->lang->line('attendanceoverviewreport_select_all_section');
				}
				$row = 1;
				$sheet->setCellValue('A'.$row, $classLang." : ".$className);
				$sheet->setCellValue($headerColumn.$row, $sectionLang." : ".$sectionName);
			} else {
				$sheet->getColumnDimension('A')->setWidth(5);
				$sheet->getColumnDimension('B')->setWidth(25);
				$sheet->getRowDimension('1')->setVisible(false);
			}

			$headerUserName = $this->lang->line('attendanceoverviewreport_name');
			if($usertype =='1') {
                $headerUserName = $this->lang->line('attendanceoverviewreport_student');
            } elseif ($usertype == '2') {
                $headerUserName = $this->lang->line('attendanceoverviewreport_teacher');
            } elseif($usertype == '3') {
                $headerUserName = $this->lang->line('attendanceoverviewreport_user');
            }

			$column = "A";
			$row    = "2";
			$headers = array();
		    $headers[] = $this->lang->line('attendanceoverviewreport_slno');
		    $headers[] = $headerUserName.' / '.$this->lang->line('attendanceoverviewreport_date');
		    if($usertype == 1) {
		    	$headers[] = $this->lang->line('attendanceoverviewreport_roll');
		    }
		    if($getDayOfMonth > 0) {
		    	for($k= 1; $k <= $getDayOfMonth; $k++) {
		    		$headers[] = $this->lang->line('attendanceoverviewreport_'.$k);
		    	}
		    }
		    $headers[] = $this->lang->line('attendanceoverviewreport_h');
            $headers[] = $this->lang->line('attendanceoverviewreport_w');
            $headers[] = $this->lang->line('attendanceoverviewreport_la');
            $headers[] = $this->lang->line('attendanceoverviewreport_p');
            $headers[] = $this->lang->line('attendanceoverviewreport_le');
            $headers[] = $this->lang->line('attendanceoverviewreport_l');
            $headers[] = $this->lang->line('attendanceoverviewreport_a');

		    if(count($headers)) {
			    foreach($headers as $header) {
			    	$sheet->setCellValue($column.$row,$header);
			    	$column++;
			    }
			}

			$bodys = array();
			if(count($users)) {
				$i = 0;
				foreach($users as $user) { 
					$i++;
					$holidayCount = 0;
                    $weekendayCount = 0;
                    $leavedayCount = 0;
                    $presentCount = 0;
                    $lateexcuseCount = 0;
                    $lateCount = 0;
                    $absentCount = 0;
					$bodys[$i][] = $i;
					$bodys[$i][] = ($usertype == 1) ? $user->srname : $user->name;
					if($usertype == 1) {
						$bodys[$i][] = $user->srroll;
					}
					if($usertype == 1) {
						$userleaveapplications = isset($leaveapplications[$user->srstudentID]) ? $leaveapplications[$user->srstudentID] : [];
						if($siteinfos->attendance == 'subject') {
	                        if(isset($attendances[$user->srstudentID])) {
	                            for($k=1; $k <= $getDayOfMonth; $k++) { 
	                            	$atten = "a".$k; 
	                                $currentDate = sprintf("%02d", $k).'-'.$monthID;
	                                if(in_array($currentDate, $getHolidays)) {
	                                    $bodys[$i][] = "H";
	                                    $holidayCount++;
	                                } else {
	                                    if(in_array($currentDate, $getWeekendDays)) {
	                                        $bodys[$i][] = "W";
	                                        $weekendayCount++;
	                                    } elseif(in_array($currentDate, $userleaveapplications)) {
                                            $bodys[$i][] = 'LA';
                                            $leavedayCount++;
                                        } else {
	                                        if($attendances[$user->srstudentID]->$atten == NULL) { 
	                                            $bodys[$i][] = "N/A";
	                                        } else {
	                                        	if($attendances[$user->srstudentID]->$atten == NULL) {
	                                            	$bodys[$i][] = $attendances[$user->srstudentID]->$atten;
	                                            } elseif($attendances[$user->srstudentID]->$atten =='P') {
	                                            	$bodys[$i][] = $attendances[$user->srstudentID]->$atten;
	                                            	$presentCount++;
	                                            } elseif($attendances[$user->srstudentID]->$atten =='LE') {
	                                            	$bodys[$i][] = $attendances[$user->srstudentID]->$atten;
	                                            	$lateexcuseCount++;
	                                            } elseif($attendances[$user->srstudentID]->$atten =='L') {
	                                            	$bodys[$i][] = $attendances[$user->srstudentID]->$atten;
	                                            	$lateCount++;
	                                            } elseif($attendances[$user->srstudentID]->$atten =='A') {
	                                            	$bodys[$i][] = $attendances[$user->srstudentID]->$atten;
	                                            	$absentCount++;
	                                            } 
	                                        }
	                                    }
	                                }
	                            }
	                        } else {
	                            for($k=1; $k <= $getDayOfMonth; $k++) { 
	                            	$atten = "a".$k; 
	                                $currentDate = sprintf("%02d", $k).'-'.$monthID;
	                                if(in_array($currentDate, $getHolidays)) {
	                                    $bodys[$i][] = "H";
	                                    $holidayCount++;
	                                } else {
	                                    if(in_array($currentDate, $getWeekendDays)) {
	                                        $bodys[$i][] = "W";
	                                        $weekendayCount++;
	                                    } elseif(in_array($currentDate, $userleaveapplications)) {
                                            $bodys[$i][] = 'LA';
                                            $leavedayCount++;
                                        } else {
	                                        $bodys[$i][] = "N/A";
	                                    }
	                                }
	                            }
	                        }
	                    } else {
	                        if(isset($attendances[$user->srstudentID])) {
	                            for($k=1; $k <= $getDayOfMonth; $k++) { 
	                            	$atten = "a".$k; 
	                                $currentDate = sprintf("%02d", $k).'-'.$monthID;
	                                if(in_array($currentDate, $getHolidays)) {
	                                    $bodys[$i][] = "H";
	                                    $holidayCount++;
	                                } else {
	                                    if(in_array($currentDate, $getWeekendDays)) {
	                                        $bodys[$i][] = "W";
	                                        $weekendayCount++;
	                                    } elseif(in_array($currentDate, $userleaveapplications)) {
                                            $bodys[$i][] = 'LA';
                                            $leavedayCount++;
                                        } else {
	                                        if($attendances[$user->srstudentID]->$atten == NULL) {
                                            	$bodys[$i][] = "N/A";
                                            } elseif($attendances[$user->srstudentID]->$atten =='P') {
                                            	$bodys[$i][] = $attendances[$user->srstudentID]->$atten;
                                            	$presentCount++;
                                            } elseif($attendances[$user->srstudentID]->$atten =='LE') {
                                            	$bodys[$i][] = $attendances[$user->srstudentID]->$atten;
                                            	$lateexcuseCount++;
                                            } elseif($attendances[$user->srstudentID]->$atten =='L') {
                                            	$bodys[$i][] = $attendances[$user->srstudentID]->$atten;
                                            	$lateCount++;
                                            } elseif($attendances[$user->srstudentID]->$atten =='A') {
                                            	$bodys[$i][] = $attendances[$user->srstudentID]->$atten;
                                            	$absentCount++;
                                            } 
	                                    }
	                                }
	                            }
	                        } else {
	                            for($k=1; $k <= $getDayOfMonth; $k++) {
	                            	$atten = "a".$k; 
	                                $currentDate = sprintf("%02d", $k).'-'.$monthID;
	                                if(in_array($currentDate, $getHolidays)) {
	                                    $bodys[$i][] = "H";
	                                    $holidayCount++;
	                                } else {
	                                    if(in_array($currentDate, $getWeekendDays)) {
	                                        $bodys[$i][] = "W";
	                                        $weekendayCount++;
	                                    } elseif(in_array($currentDate, $userleaveapplications)) {
                                            $bodys[$i][] = 'LA';
                                            $leavedayCount++;
                                        } else {
	                                        $bodys[$i][] = "N/A";
	                                    }
	                                }
	                            }
	                        }
	                    }
	                } elseif($usertype == 2) {
	                	$userleaveapplications = isset($leaveapplications[$user->teacherID]) ? $leaveapplications[$user->teacherID] : [];
	                	if(isset($attendances[$user->teacherID])) {
                            for($k=1; $k <= $getDayOfMonth; $k++) { 
                            	$atten = "a".$k; 
                                $currentDate = sprintf("%02d", $k).'-'.$monthID;
                                if(in_array($currentDate, $getHolidays)) {
                                    $bodys[$i][] = "H";
                                    $holidayCount++;
                                } else {
                                    if(in_array($currentDate, $getWeekendDays)) {
                                        $bodys[$i][] = "W";
                                        $weekendayCount++;
                                    } elseif(in_array($currentDate, $userleaveapplications)) {
                                        $bodys[$i][] = 'LA';
                                        $leavedayCount++;
                                    } else {
                                        if($attendances[$user->teacherID]->$atten == NULL) {
                                        	$bodys[$i][] = "N/A";
                                        } elseif($attendances[$user->teacherID]->$atten =='P') {
                                        	$bodys[$i][] = $attendances[$user->teacherID]->$atten;
                                        	$presentCount++;
                                        } elseif($attendances[$user->teacherID]->$atten =='LE') {
                                        	$bodys[$i][] = $attendances[$user->teacherID]->$atten;
                                        	$lateexcuseCount++;
                                        } elseif($attendances[$user->teacherID]->$atten =='L') {
                                        	$bodys[$i][] = $attendances[$user->teacherID]->$atten;
                                        	$lateCount++;
                                        } elseif($attendances[$user->teacherID]->$atten =='A') {
                                        	$bodys[$i][] = $attendances[$user->teacherID]->$atten;
                                        	$absentCount++;
                                        } 
                                    }
                                }
                            }
                        } else {
                            for($k=1; $k <= $getDayOfMonth; $k++) {
                            	$atten = "a".$k; 
                                $currentDate = sprintf("%02d", $k).'-'.$monthID;
                                if(in_array($currentDate, $getHolidays)) {
                                    $bodys[$i][] = "H";
                                    $holidayCount++;
                                } else {
                                    if(in_array($currentDate, $getWeekendDays)) {
                                        $bodys[$i][] = "W";
                                        $weekendayCount++;
                                    } elseif(in_array($currentDate, $userleaveapplications)) {
                                        $bodys[$i][] = 'LA';
                                        $leavedayCount++;
                                    }  else {
                                        $bodys[$i][] = "N/A";
                                    }
                                }
                            }
                        }
	                } elseif ($usertype == 3) {
	                 	$userleaveapplications = isset($leaveapplications[$user->userID]) ? $leaveapplications[$user->userID] : [];
	                	if(isset($attendances[$user->userID])) {
                            for($k=1; $k <= $getDayOfMonth; $k++) { 
                            	$atten = "a".$k; 
                                $currentDate = sprintf("%02d", $k).'-'.$monthID;
                                if(in_array($currentDate, $getHolidays)) {
                                    $bodys[$i][] = "H";
                                    $holidayCount++;
                                } else {
                                    if(in_array($currentDate, $getWeekendDays)) {
                                        $bodys[$i][] = "W";
                                        $weekendayCount++;
                                    } elseif(in_array($currentDate, $userleaveapplications)) {
                                        $bodys[$i][] = 'LA';
                                        $leavedayCount++;
                                    } else {
                                        if($attendances[$user->userID]->$atten == NULL) {
                                        	$bodys[$i][] = 'N/A';
                                        } elseif($attendances[$user->userID]->$atten =='P') {
                                        	$bodys[$i][] = $attendances[$user->userID]->$atten;
                                        	$presentCount++;
                                        } elseif($attendances[$user->userID]->$atten =='LE') {
                                        	$bodys[$i][] = $attendances[$user->userID]->$atten;
                                        	$lateexcuseCount++;
                                        } elseif($attendances[$user->userID]->$atten =='L') {
                                        	$bodys[$i][] = $attendances[$user->userID]->$atten;
                                        	$lateCount++;
                                        } elseif($attendances[$user->userID]->$atten =='A') {
                                        	$bodys[$i][] = $attendances[$user->userID]->$atten;
                                        	$absentCount++;
                                        } 
                                    }
                                }
                            }
                        } else {
                            for($k=1; $k <= $getDayOfMonth; $k++) {
                            	$atten = "a".$k; 
                                $currentDate = sprintf("%02d", $k).'-'.$monthID;
                                if(in_array($currentDate, $getHolidays)) {
                                    $bodys[$i][] = "H";
                                    $holidayCount++;
                                } else {
                                    if(in_array($currentDate, $getWeekendDays)) {
                                        $bodys[$i][] = "W";
                                        $weekendayCount++;
                                    } elseif(in_array($currentDate, $userleaveapplications)) {
                                        $bodys[$i][] = 'LA';
                                        $leavedayCount++;
                                    } else {
                                        $bodys[$i][] = "N/A";
                                    }
                                }
                            }
                        }
	                } 
	                $bodys[$i][] = $holidayCount;
	                $bodys[$i][] = $weekendayCount;
	                $bodys[$i][] = $leavedayCount;
	                $bodys[$i][] = $presentCount;
	                $bodys[$i][] = $lateexcuseCount;
	                $bodys[$i][] = $lateCount;
	                $bodys[$i][] = $absentCount;
				}
			}

			if(count($bodys)) {
				$row = 3;
				foreach ($bodys as $rows) {
					$column = 'A';
					foreach($rows as $value) {
						$sheet->setCellValue($column.$row,$value);
			    		$column++;
					}
					$row++;
				}
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
			
			if($usertype == 1) {
				$lastColumn = $headerColumn.'1';
                $headerColumnLastLetter = substr($headerColumn, -1);
                $headerColumn = chr(ord($headerColumnLastLetter) - 6);  //Decreament Header Section Column
                $mergeCellsColumn = 'A'.$headerColumn.'1';
                $sheet->mergeCells("B1:$mergeCellsColumn");
                $sectionColStart = 'A'.++$headerColumn.'1';
                $sheet->mergeCells("$sectionColStart:$lastColumn");                
                $sheet->setCellValue($sectionColStart,$sectionName);

			}
		} else {
			redirect('attendanceoverviewreport');
		}
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