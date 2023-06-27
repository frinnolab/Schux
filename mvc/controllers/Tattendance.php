<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tattendance extends Admin_Controller {
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
		$this->load->model("teacher_m");
		$this->load->model("tattendance_m");
		$this->load->model("leaveapplication_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('tattendance', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'date',
				'label' => $this->lang->line("tattendance_date"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid|callback_valid_future_date|callback_check_holiday|callback_check_weekendday|callback_check_session_year_date'
			)
		);
		return $rules;
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'id',
				'label' => $this->lang->line("id"),
				'rules' => 'trim|required|numeric|xss_clean'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line("to"),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("message"),
				'rules' => 'trim|xss_clean'
			),
		);
		return $rules;
	}

	protected function attendance_rules() {
		$rules = array(
			array(
				'field' => 'day',
				'label' => $this->lang->line("tattendance_day"),
				'rules' => 'trim|required|numeric|xss_clean|max_length[11]'
			),
			array(
				'field' => 'monthyear',
				'label' => $this->lang->line("tattendance_monthyear"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'attendance[]',
				'label' => $this->lang->line("tattendance_attendance"),
				'rules' => 'trim|required|xss_clean'
			)
		);
		return $rules;
	}

	public function index() {
		$myProfile = false;
		if($this->session->userdata('usertypeID') == 2) {
			if(!permissionChecker('tattendance_view')) {
				$myProfile = true;
			}
		}

		if($this->session->userdata('usertypeID') == 2 && $myProfile) {
			$id = $this->session->userdata('loginuserID');
			$this->view($id);
		} else {
			$this->data['teachers'] = $this->teacher_m->general_get_teacher();
			$this->data["subview"] = "tattendance/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
					'assets/datepicker/datepicker.css'
				),
				'js' => array(
					'assets/select2/select2.js',
					'assets/datepicker/datepicker.js'
				)
			);
			$this->data['date'] = date("d-m-Y");
			$this->data['get_all_holidays'] = $this->getHolidaysSession();

			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['teachers'] = array();
			$this->data['dateinfo'] = array();
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "tattendance/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$date = $this->input->post("date");
					$this->data['date'] = $date;
					$explode_date = explode("-", $date);
					$monthyear = $explode_date[1]."-".$explode_date[2];

					$teachers = $this->teacher_m->get_teacher();
					$this->data['teachers'] = $teachers;
					if(count($teachers)) {
						$attendance_monthyear = pluck($this->tattendance_m->get_order_by_tattendance(array("monthyear" => $monthyear, 'schoolyearID' => $schoolyearID)), 'obj', 'teacherID');

						$insertArray = [];
						foreach ($teachers as $key => $teacher) {
							if(!isset($attendance_monthyear[$teacher->teacherID])) {
								$insertArray[] = array(
									'schoolyearID' => $schoolyearID,
									"teacherID" => $teacher->teacherID,
									"usertypeID" => $teacher->usertypeID,
									"monthyear" => $monthyear
								);
							}
						}

						if(count($insertArray)) {
							$this->tattendance_m->insert_batch_tattendance($insertArray);
						}

						$this->data['dateinfo']['day'] = date('l', strtotime($date));
						$this->data['dateinfo']['date'] = date('jS F Y', strtotime($date));
						$this->data['tattendances'] = pluck($this->tattendance_m->get_order_by_tattendance(array("monthyear" => $monthyear, 'schoolyearID' => $schoolyearID)), 'obj', 'teacherID');
						$this->data['monthyear'] = $monthyear;
						$this->data['day'] = $explode_date[0];
					}
					$this->data["subview"] = "tattendance/add";
					$this->load->view('_layout_main', $this->data);
					
				}
			} else {
				$this->data["subview"] = "tattendance/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function save_attendace() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('tattendance')) {
			if($_POST) {
				$day = $this->input->post('day');
				$monthyear = $this->input->post('monthyear');
				$attendance = $this->input->post('attendance');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');

				$rules = $this->attendance_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {

					$updateArray = [];
					if(is_array($attendance) && count($attendance)) {
						foreach($attendance as $key => $singleAttendance) {
							$id = str_replace("attendance", "", $key);
							$updateArray[] = array(
								'tattendanceID' => $id,
								'a'.abs($day) => $singleAttendance
							); 
						}
					}

					if(count($updateArray)) {
						$this->tattendance_m->update_batch_tattendance($updateArray, 'tattendanceID');
						$retArray['message'] = "Success";
						$retArray['status'] = TRUE;
						echo json_encode($retArray);
					    exit;
					} else {
						$retArray['message'] = $this->lang->line('tattendance_attendance_data');
						$retArray['status'] = FALSE;
						echo json_encode($retArray);
						exit;
					}
				}
			}  else {
				$retArray['message'] = $this->lang->line('tattendance_permissionmethod');
				$retArray['status'] = FALSE;
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('tattendance_permission');
			$retArray['status'] = FALSE;
			echo json_encode($retArray);
			exit;
		}
	}

	public function view($id = null) {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css'
			),
			'js' => array(
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js'
			)
		);

		if((int)$id) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['holidays'] =  $this->getHolidaysSession();
			$this->data['getWeekendDays'] =  $this->getWeekendDaysSession();

			$this->data["teacher"] = $this->teacher_m->general_get_single_teacher(array('teacherID' => $id));
			if(count($this->data["teacher"])) {
				$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear($id,$schoolyearID);
				$tattendances = $this->tattendance_m->get_order_by_tattendance(array("teacherID" => $id, 'schoolyearID' => $schoolyearID));
				$this->data['attendancesArray'] = pluck($tattendances,'obj','monthyear');
				$this->data["subview"] = "tattendance/view";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function print_preview($id = null) {
		if((int)$id) {
			if(permissionChecker('tattendance_view') || (($this->session->userdata('usertypeID') == 2) && permissionChecker('tattendance') && ($this->session->userdata('loginuserID') == $id))) {
				
				$this->data['holidays'] =  $this->getHolidaysSession();
				$this->data['getWeekendDays'] =  $this->getWeekendDaysSession();
				$this->data["teacher"] = $this->teacher_m->general_get_single_teacher(array('teacherID' => $id));
				if(count($this->data["teacher"])) {
				    $this->data['panel_title'] = $this->lang->line('panel_title');
				    $schoolyearID = $this->session->userdata('defaultschoolyearID');
				    $this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear($id,$schoolyearID);
					$tattendances = $this->tattendance_m->get_order_by_tattendance(array("teacherID" => $id, 'schoolyearID' => $schoolyearID));
					$this->data['attendancesArray'] = pluck($tattendances,'obj','monthyear');
					$this->reportPDF('tattendancemodule.css',$this->data, 'tattendance/print_preview');
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "errorpermission";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('tattendance_view') || (($this->session->userdata('usertypeID') == 2) && permissionChecker('tattendance') && ($this->session->userdata('loginuserID') == $this->input->post('id')))) {
			if($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$id = $this->input->post('id');
					if ((int)$id) {	
						$this->data['holidays'] =  $this->getHolidaysSession();
						$this->data['getWeekendDays'] =  $this->getWeekendDaysSession();

						$this->data["teacher"] = $this->teacher_m->general_get_single_teacher(array('teacherID' => $id));
						if(count($this->data["teacher"])) {
							$schoolyearID = $this->session->userdata('defaultschoolyearID');
							$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear($id,$schoolyearID);
							$tattendances = $this->tattendance_m->get_order_by_tattendance(array("teacherID" => $id, 'schoolyearID' => $schoolyearID));
							$this->data['attendancesArray'] = pluck($tattendances,'obj','monthyear');

							$email = $this->input->post('to');
							$subject = $this->input->post('subject');
							$message = $this->input->post('message');
							$this->reportSendToMail('tattendancemodule.css', $this->data, 'tattendance/print_preview', $email, $subject, $message);
							$retArray['status'] = TRUE;
							$retArray['message'] = $this->lang->line('success');
							echo json_encode($retArray);
							exit();	
						} else {
							$retArray['message'] = $this->lang->line('tattendance_data_not_found');
							echo json_encode($retArray);
							exit();	
						}
					} else {
						$retArray['message'] = $this->lang->line('tattendance_data_not_found');
						echo json_encode($retArray);
						exit();	
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('tattendance_permissionmethod');
				echo json_encode($retArray);
				exit();
			}
		} else {
			$retArray['message'] = $this->lang->line('tattendance_permission');
			echo json_encode($retArray);
			exit();
		}
	}

	private function leave_applications_date_list_by_user_and_schoolyear($teacherID, $schoolyearID) {
		$leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication(array('create_userID'=>$teacherID,'create_usertypeID'=>2,'schoolyearID'=>$schoolyearID,'status'=>1));
		
		$retArray = [];
		if(count($leaveapplications)) {
			$oneday    = 60*60*24;
			foreach($leaveapplications as $leaveapplication) {
			    for($i=strtotime($leaveapplication->from_date); $i<= strtotime($leaveapplication->to_date); $i= $i+$oneday) {
			        $retArray[] = date('d-m-Y', $i);
			    }
			}
		}
		return $retArray;
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

	public function date_valid($date) {
   		if(strlen($date) <10) {
			$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
	     	return FALSE;
		} else {
	   		$arr = explode("-", $date);
	        $dd = $arr[0];
	        $mm = $arr[1];
	        $yyyy = $arr[2];
	      	if(checkdate($mm, $dd, $yyyy)) {
	      		return TRUE;
	      	} else {
	      		$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
	     		return FALSE;
	      	}
	    }
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
				$this->form_validation->set_message('check_weekendday', 'The %s field given weekenday.');
				return FALSE;
			} else {
				return TRUE;
			}
		}
		return TRUE;
	}

	public function check_session_year_date() {
		$date = strtotime($this->input->post('date'));

		$startingdate = strtotime($this->data['schoolyearsessionobj']->startingdate);
		$endingdate   = strtotime($this->data['schoolyearsessionobj']->endingdate);

		if($date < $startingdate || $date > $endingdate) {
			$this->form_validation->set_message('check_session_year_date','The %s field given not exits.');
			return FALSE;
		} 
		return TRUE;
	}
}