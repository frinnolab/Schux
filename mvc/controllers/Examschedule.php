<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examschedule extends Admin_Controller {
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
		$this->load->model("examschedule_m");
		$this->load->model("parents_m");
		$this->load->model("student_m");
		$this->load->model("classes_m");
		$this->load->model("section_m");
		$this->load->model("exam_m");
		$this->load->model("subject_m");
		$this->load->model("marksetting_m");

		$language = $this->session->userdata('lang');
		$this->lang->load('examschedule', $language);	
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
		if($this->session->userdata('usertypeID') == 3) {
			$id = $this->data['myclass'];
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
		}

		if((int)$id) {
			$this->data['set'] = $id;
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['examschedules'] = $this->examschedule_m->get_join_examschedule_with_exam_classes_section_subject(array('classesID' => $id, 'schoolyearID' => $schoolyearID));
			if($this->data['examschedules']) {
				$sections = $this->section_m->general_get_order_by_section(array("classesID" => $id));
				$this->data['sections'] = $sections;
				foreach ($sections as $key => $section) {
					$this->data['allsection'][$section->section] = $this->examschedule_m->get_join_examschedule_with_exam_classes_section_subject(array('classesID' => $id, 'sectionID' => $section->sectionID, 'schoolyearID' => $schoolyearID));
				}
			} else {
				$this->data['examschedules'] = [];
			}

			$this->data["subview"] = "examschedule/index";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data['set'] = 0;
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['examschedules'] = [];
			$this->data["subview"] = "examschedule/index";
			$this->load->view('_layout_main', $this->data);
		}
	}
	
	protected function rules() {
		$rules = array(
				array(
					'field' => 'examID', 
					'label' => $this->lang->line("examschedule_name"), 
					'rules' => 'trim|required|numeric|xss_clean|max_length[11]|callback_unique_examID'
				),
				array(
					'field' => 'classesID', 
					'label' => $this->lang->line("examschedule_classes"), 
					'rules' => 'trim|required|numeric|xss_clean|max_length[11]|callback_unique_classesID'
				),
				array(
					'field' => 'sectionID', 
					'label' => $this->lang->line("examschedule_section"), 
					'rules' => 'trim|required|numeric|xss_clean|max_length[11]|callback_unique_sectionID'
				),
				array(
					'field' => 'subjectID', 
					'label' => $this->lang->line("examschedule_subject"), 
					'rules' => 'trim|required|numeric|xss_clean|max_length[11]|callback_unique_subjectID|callback_check_duplicate_subject'
				),
				array(
					'field' => 'date',
					'label' => $this->lang->line("examschedule_date"), 
					'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid|callback_pastdate_check|callback_check_weekendday|callback_check_holiday'
				),
				array(
					'field' => 'examfrom', 
					'label' => $this->lang->line("examschedule_examfrom"), 
					'rules' => 'trim|required|xss_clean|max_length[10]'
				),
				array(
					'field' => 'examto', 
					'label' => $this->lang->line("examschedule_examto"), 
					'rules' => 'trim|required|xss_clean|max_length[10]'
				),
				array(
					'field' => 'room', 
					'label' => $this->lang->line("examschedule_room"), 
					'rules' => 'trim|xss_clean|max_length[10]'
				)
			);
		return $rules;
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
					'assets/datepicker/datepicker.css',
					'assets/timepicker/timepicker.css'
				),
				'js' => array(
					'assets/select2/select2.js',
					'assets/datepicker/datepicker.js',
					'assets/timepicker/timepicker.js'
				)
			);

			$this->data['classes']          = $this->classes_m->get_classes();
			$this->data['get_all_holidays'] = $this->getHolidaysSession();
			
			$classesID = $this->input->post("classesID");
			if($classesID > 0) {
				$this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
				$this->data['sections'] = $this->section_m->general_get_order_by_section(array("classesID" => $classesID));
				$this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID);
			} else {
				$this->data['subjects'] = [];
				$this->data['sections'] = [];
				$this->data['exams']    = [];
			}

			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "examschedule/add";
					$this->load->view('_layout_main', $this->data);			
				} else {
					$array = array(
						"examID" => $this->input->post("examID"),
						"classesID" => $this->input->post("classesID"),
						"sectionID" => $this->input->post("sectionID"),
						"subjectID" => $this->input->post("subjectID"),
						"edate" => date("Y-m-d", strtotime($this->input->post("date"))),
						"examfrom" => $this->input->post("examfrom"),
						"examto" => $this->input->post("examto"),
						"room" => $this->input->post("room"),
						"schoolyearID" => $this->session->userdata('defaultschoolyearID')
					);

					$this->examschedule_m->insert_examschedule($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("examschedule/index"));
				}
			} else {
				$this->data["subview"] = "examschedule/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
					'assets/datepicker/datepicker.css',
					'assets/timepicker/timepicker.css'
				),
				'js' => array(
					'assets/select2/select2.js',
					'assets/datepicker/datepicker.js',
					'assets/timepicker/timepicker.js'
				)
			);

			$id     = htmlentities(escapeString($this->uri->segment(3)));
			$url    = htmlentities(escapeString($this->uri->segment(4)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['get_all_holidays'] = $this->getHolidaysSession();
			
			if((int)$id && (int)$url) {
				$this->data['classes'] = $this->classes_m->get_classes();
				$fetchClass            = pluck($this->data['classes'], 'classesID', 'classesID');
				if(isset($fetchClass[$url])) {
					$this->data['examschedule'] = $this->examschedule_m->get_single_examschedule(array('examscheduleID' => $id, 'classesID' => $url, 'schoolyearID' => $schoolyearID));
					if(count($this->data['examschedule'])) {
						$classID     = $this->data['examschedule']->classesID;
						if($this->input->post('classesID')) {
							$classID = $this->input->post("classesID");
						}
						$this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array('classesID' => $classID));
						$this->data['sections'] = $this->section_m->general_get_order_by_section(array("classesID" => $classID));
						$this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classID);
						$this->data['set']      = $url;

						if($_POST) {
							$rules = $this->rules();
							$this->form_validation->set_rules($rules);
							if ($this->form_validation->run() == FALSE) {
								$this->data["subview"] = "examschedule/edit";
								$this->load->view('_layout_main', $this->data);			
							} else {
								$array = array(
									"examID" => $this->input->post("examID"),
									"classesID" => $this->input->post("classesID"),
									"sectionID" => $this->input->post("sectionID"),
									"subjectID" => $this->input->post("subjectID"),
									"edate" => date("Y-m-d", strtotime($this->input->post("date"))),
									"examfrom" => $this->input->post("examfrom"),
									"examto" => $this->input->post("examto"),
									"room" => $this->input->post("room")
								);

								$this->examschedule_m->update_examschedule($array, $id);
								$this->session->set_flashdata('success', $this->lang->line('menu_success'));
								redirect(base_url("examschedule/index/$url"));
							}
						} else {
							$this->data["subview"] = "examschedule/edit";
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

	public function delete() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$classesID = htmlentities(escapeString($this->uri->segment(4)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$id && (int)$classesID) {
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');

				if(isset($fetchClass[$classesID])) {
					$this->data['examschedule'] = $this->examschedule_m->get_single_examschedule(array('examscheduleID' => $id, 'classesID' => $classesID, 'schoolyearID' => $schoolyearID));
					if(count($this->data['examschedule'])) {
						$this->examschedule_m->delete_examschedule($id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("examschedule/index/$classesID"));
					} else {
						redirect(base_url("examschedule/index/$classesID"));
					}
				} else {
					redirect(base_url("examschedule/index"));
				}
			} else {
				redirect(base_url("examschedule/index"));
			}
		} else {
			redirect(base_url('examschedule/index'));
		}
	}

	public function examschedule_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("examschedule/index/$classID");
			echo $string;
		} else {
			redirect(base_url("examschedule/index"));
		}
	}

	public function date_valid($date) {
		if(!empty($date)) {
			if(strlen($date) < 10) {
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
		} else {
			$this->form_validation->set_message("date_valid", "The %s field is required.");
		     return FALSE;
		}
	} 

	public function unique_subjectID() {
		if($this->input->post('subjectID') == 0) {
			$this->form_validation->set_message("unique_subjectID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function examcall() {
		$classesID = $this->input->post('classesID');
		echo "<option value='0'>", $this->lang->line("examschedule_select_exam"),"</option>";
		if((int)$classesID) {
			$exams    = pluck($this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID), 'obj', 'examID');
			if(count($exams)) {
				foreach ($exams as $exam) {
					echo "<option value=".$exam->examID.">".$exam->exam."</option>";
				}
			}
		}
	}

	public function subjectcall() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$allclasses = $this->subject_m->general_get_order_by_subject(array('classesID' => $classID));
			echo "<option value='0'>", $this->lang->line("examschedule_select_subject"),"</option>";
			foreach ($allclasses as $value) {
				echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
			}
		} 
	}

	public function sectioncall() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$allsection = $this->section_m->general_get_order_by_section(array("classesID" => $classID));
			echo "<option value='0'>", $this->lang->line("examschedule_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
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
		$examID = $this->input->post('classesID');
		if($examID === '0') {
			$this->form_validation->set_message("unique_classesID", "The %s field is required");
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

	public function pastdate_check() {
		$date = strtotime($this->input->post("date"));
		$nowDate = strtotime(date("d-m-Y"));
		if($date) {
			if($date < $nowDate) {
				$this->form_validation->set_message("pastdate_check", "The %s field is past date");
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
				$this->form_validation->set_message('check_holiday','The date is already assigned holiday.');
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
				$this->form_validation->set_message('check_weekendday','The date is already assigned weekend.');
				return FALSE;
			} else {
				return TRUE;
			}
		}
		return TRUE;
	}

	public function check_duplicate_subject() {
		if($_POST) {
			$examscheduleID = htmlentities(escapeString($this->uri->segment(3)));
			$qArray = [];
			$qArray['examID']       = (int)$this->input->post("examID");
			$qArray['classesID']    = (int)$this->input->post("classesID");
			$qArray['sectionID']    = (int)$this->input->post("sectionID");
			$qArray['subjectID']    = (int)$this->input->post("subjectID");
			$qArray['schoolyearID'] = (int)$this->session->userdata('defaultschoolyearID');
			if((int)$examscheduleID) {
				$qArray['examscheduleID !='] = (int)$examscheduleID;
			}
			$examschedule = $this->examschedule_m->get_order_by_examschedule($qArray);
			if(count($examschedule)) {
				$this->form_validation->set_message('check_duplicate_subject','This subject exam schedule is already assigned.');
				return FALSE;
			}
		}
		return TRUE;
	}

}