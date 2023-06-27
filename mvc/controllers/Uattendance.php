<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uattendance extends Admin_Controller {
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
		$this->load->model("user_m");
		$this->load->model("usertype_m");
		$this->load->model('uattendance_m');
		$this->load->model('leaveapplication_m');
		
		$language = $this->session->userdata('lang');
		$this->lang->load('uattendance', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'date',
				'label' => $this->lang->line("uattendance_date"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid|callback_valid_future_date|callback_check_holiday|callback_check_weekendday|callback_check_session_year_date'
			)
		);
		return $rules;
	}

	protected function attendance_rules() {
		$rules = array(
			array(
				'field' => 'day',
				'label' => $this->lang->line("uattendance_day"),
				'rules' => 'trim|required|numeric|xss_clean|max_length[11]'
			),
			array(
				'field' => 'monthyear',
				'label' => $this->lang->line("uattendance_monthyear"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'attendance[]',
				'label' => $this->lang->line("uattendance_attendance"),
				'rules' => 'trim|required|xss_clean'
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

		$usertype = pluck($this->usertype_m->get_usertype(), 'obj', 'usertypeID');
		unset($usertype[1], $usertype[2], $usertype[3], $usertype[4]);
		
		$myProfile = false;
		if(isset($usertype[$this->session->userdata('usertypeID')])) {
			if(!permissionChecker('uattendance_view')) {
				$myProfile = true;
			}
		}

		if(isset($usertype[$this->session->userdata('usertypeID')]) && $myProfile) {
			$id = $this->session->userdata('loginuserID');
			$this->view($id);
		} else {
			$this->data['users'] = $this->user_m->get_user_by_usertype();
			$this->data["subview"] = "uattendance/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css'
				),
				'js' => array(
					'assets/datepicker/datepicker.js'
				)
			);

			$this->data['date'] = date("d-m-Y");
			$this->data['get_all_holidays'] = $this->getHolidaysSession();
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['users'] = [];
			$this->data['dateinfo'] = [];

			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "uattendance/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$date = $this->input->post("date");
					$this->data['date'] = $date;
					$explode_date = explode("-", $date);
					$monthyear = $explode_date[1]."-".$explode_date[2];

					$users = $this->user_m->get_user_by_usertype();
					if(count($users)) {
						$uattendance_monthyear = pluck($this->uattendance_m->get_order_by_uattendance(array("monthyear" => $monthyear, 'schoolyearID' => $schoolyearID)), 'obj', 'userID');;
						$userArray = [];
						foreach($users as $user) {
							if(!isset($uattendance_monthyear[$user->userID])) {
								$userArray[] = array(
									'schoolyearID' 	=> $schoolyearID,
									"userID" 		=> $user->userID,
									"usertypeID" 	=> $user->usertypeID,
									"monthyear" 	=> $monthyear
								);
							}
						}

						if(count($userArray)) {
							$this->uattendance_m->insert_batch_uattendance($userArray);
						}

						$this->data['dateinfo']['day'] = date('l', strtotime($date));
						$this->data['dateinfo']['date'] = date('jS F Y', strtotime($date));
						$this->data['users'] = $users;
						$this->data['uattendances'] = pluck($this->uattendance_m->get_order_by_uattendance(array('monthyear' => $monthyear, 'schoolyearID' => $schoolyearID)), 'obj', 'userID');

						$this->data['monthyear'] = $monthyear;
						$this->data['day'] = $explode_date[0];
					}
					$this->data["subview"] = "uattendance/add";
					$this->load->view('_layout_main', $this->data);					
				}
			} else {
				$this->data["subview"] = "uattendance/add";
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
		if(permissionChecker('uattendance')) {
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
								'uattendanceID' => $id,
								'a'.abs($day) => $singleAttendance,
							); 
						}
					}

					if($updateArray) {
						$this->uattendance_m->update_batch_uattendance($updateArray, 'uattendanceID');
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
				$retArray['message'] = $this->lang->line('tattendance_post_method');
				$retArray['status'] = FALSE;
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('uattendance_permission');
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

		if ((int)$id) {
			$this->data['holidays'] =  $this->getHolidaysSession();
			$this->data['getWeekendDays'] =  $this->getWeekendDaysSession();

			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data["user"] = $this->user_m->get_single_user(array('userID' => $id));
			if(count($this->data["user"])) {
				$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear($id,$schoolyearID,$this->data["user"]->usertypeID);
				$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID' );
				$uattendances = $this->uattendance_m->get_order_by_uattendance(array("userID" => $id, 'schoolyearID' => $schoolyearID));
				$this->data['attendancesArray'] = pluck($uattendances,'obj','monthyear');
				$this->data["subview"] = "uattendance/view";
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

	public function print_preview() {
		$usertype = pluck($this->usertype_m->get_usertype(), 'obj', 'usertypeID');
		unset($usertype[1], $usertype[2], $usertype[3], $usertype[4]);
		
		if(permissionChecker('uattendance_view') || ((isset($usertype[$this->session->userdata('usertypeID')])) && permissionChecker('uattendance') && ($this->session->userdata('loginuserID') == htmlentities(escapeString($this->uri->segment(3)))))) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$this->data['holidays'] =  $this->getHolidaysSession();
				$this->data['getWeekendDays'] =  $this->getWeekendDaysSession();
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$this->data["user"] = $this->user_m->get_single_user(array('userID' => $id));
				if(count($this->data["user"])) {
					$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear($id,$schoolyearID,$this->data["user"]->usertypeID);
					$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID' );
					$uattendances = $this->uattendance_m->get_order_by_uattendance(array("userID" => $id, 'schoolyearID' => $schoolyearID));
					$this->data['attendancesArray'] = pluck($uattendances,'obj','monthyear');
					$this->reportPDF('uattendancemodule.css',$this->data, 'uattendance/print_preview');
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

		$usertype = pluck($this->usertype_m->get_usertype(), 'obj', 'usertypeID');
		unset($usertype[1], $usertype[2], $usertype[3], $usertype[4]);
		if(permissionChecker('uattendance_view') || ((isset($usertype[$this->session->userdata('usertypeID')])) && permissionChecker('uattendance') && ($this->session->userdata('loginuserID') == $this->input->post('id')))) {
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
					if((int)$id) {
						$this->data['holidays'] =  $this->getHolidaysSession();
						$this->data['getWeekendDays'] =  $this->getWeekendDaysSession();
						$schoolyearID = $this->session->userdata('defaultschoolyearID');
						$this->data["user"] = $this->user_m->get_user($id);
						if(count($this->data["user"])) {
							$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear($id,$schoolyearID,$this->data["user"]->usertypeID);
							$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID' );
							$uattendances = $this->uattendance_m->get_order_by_uattendance(array("userID" => $id, 'schoolyearID' => $schoolyearID));
							$this->data['attendancesArray'] = pluck($uattendances,'obj','monthyear');

							$email = $this->input->post('to');
							$subject = $this->input->post('subject');
							$message = $this->input->post('message');
							$this->reportSendToMail('uattendancemodule.css',$this->data, 'uattendance/print_preview', $email, $subject, $message);
							$retArray['status'] = TRUE;
							$retArray['message'] = $this->lang->line('success');
							echo json_encode($retArray);
							exit();	
						} else {
							$retArray['message'] = $this->lang->line('uattendance_data_not_found');
							echo json_encode($retArray);
							exit();	
						}
					} else {
						$retArray['message'] = $this->lang->line('uattendance_data_not_found');
						echo json_encode($retArray);
						exit();	
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('uattendance_permissionmethod');
				echo json_encode($retArray);
				exit();	
			}
		} else {
			$retArray['message'] = $this->lang->line('uattendance_permission');
			echo json_encode($retArray);
			exit();
		}
	}

	private function leave_applications_date_list_by_user_and_schoolyear($userID, $schoolyearID, $usertypeID) {
		$leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication(array('create_userID'=>$userID,'create_usertypeID'=>$usertypeID,'schoolyearID'=>$schoolyearID,'status'=>1));
		
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

	public function valid_future_date($date) {
		$presentdate = date('Y-m-d');
		$date = date("Y-m-d", strtotime($date));
		if($date > $presentdate) {
			$this->form_validation->set_message('valid_future_date','The %s field given invalid.');
			return FALSE;
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
				$this->form_validation->set_message('check_weekendday','The %s field given weekenday.');
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