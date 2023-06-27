<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Routine extends Admin_Controller {
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
		$this->load->model("routine_m");
		$this->load->model("classes_m");
		$this->load->model("section_m");
		$this->load->model("subject_m");
		$this->load->model('parents_m');
		$this->load->model('student_m');
		$this->load->model('teacher_m');
		$this->load->model('schoolyear_m');
		$this->load->model('subjectteacher_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('routine', $language);
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css'
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js'
			)
		);

		if($this->session->userdata('usertypeID') == 3) {
			$id = $this->data['myclass'];
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
		}

        $this->data['weekends'] = [];
        if($this->data['siteinfos']->weekends != '') {
            $this->data['weekends'] = explode(',', $this->data['siteinfos']->weekends);
        }
		
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$id) {
			$this->data['set'] = $id;
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['routines'] = $this->routine_m->get_routine_with_teacher_class_section_subject(array('classesID' => $id, 'schoolyearID' => $schoolyearID));
			$fetchClass = pluck($this->data['classes'], 'classesID', 'classesID');
			if(isset($fetchClass[$id])) {
				if($this->data['routines']) {
					$sections = $this->section_m->general_get_order_by_section(array("classesID" => $id));
					$this->data['sections'] = $sections;
					foreach ($sections as $key => $section) {
						$this->data['allsection'][$section->section] = $this->routine_m->get_routine_with_teacher_class_section_subject(array('classesID' => $id, 'sectionID' => $section->sectionID, 'schoolyearID' => $schoolyearID));
					}
				} else {
					$this->data['routines'] = [];
				}
				$this->data["subview"] = "routine/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['set'] = 0;
				$this->data['routines'] = [];
				$this->data['classes'] = $this->classes_m->get_classes();
				$this->data["subview"] = "routine/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data['set'] = 0;
			$this->data['routines'] = [];
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data["subview"] = "routine/index";
			$this->load->view('_layout_main', $this->data);
		}
	}
	
	protected function rules() {
		$rules = array(
				array(
					'field' => 'schoolyearID', 
					'label' => $this->lang->line("routine_schoolyear"), 
					'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_data'
				),
				array(
					'field' => 'classesID', 
					'label' => $this->lang->line("routine_classes"), 
					'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_data'
				),
				array(
					'field' => 'sectionID', 
					'label' => $this->lang->line("routine_section"), 
					'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_data|callback_unique_section'
				),
				array(
					'field' => 'subjectID', 
					'label' => $this->lang->line("routine_subject"), 
					'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_data'
				),
				array(
					'field' => 'teacherID', 
					'label' => $this->lang->line("routine_teacher"), 
					'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_data'
				),
				array(
					'field' => 'combinedSubjectID', 
					'label' => $this->lang->line("routine_combined_subject"), 
					'rules' => 'trim|xss_clean|numeric|max_length[11]'
				),
				array(
					'field' => 'combinedTeacherID', 
					'label' => $this->lang->line("routine_combined_teacher"), 
					'rules' => 'trim|xss_clean|numeric|max_length[11]'
				),
				array(
					'field' => 'day',
					'label' => $this->lang->line("routine_day"), 
					'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_day'
				),
				array(
					'field' => 'start_time', 
					'label' => $this->lang->line("routine_start_time"), 
					'rules' => 'trim|required|xss_clean|max_length[10]'
				),
				array(
					'field' => 'end_time', 
					'label' => $this->lang->line("routine_end_time"), 
					'rules' => 'trim|required|xss_clean|max_length[10]'
				),
				array(
					'field' => 'room', 
					'label' => $this->lang->line("routine_room"), 
					'rules' => 'trim|required|xss_clean|max_length[11]'
				),
				array(
					'field' => 'combinedRoom', 
					'label' => $this->lang->line("routine_combined_room"), 
					'rules' => 'trim|xss_clean|max_length[11]'
				)
			);
		return $rules;
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/timepicker/timepicker.css'
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/timepicker/timepicker.js'
			)
		);
		
		$this->data['schoolyears'] = $this->schoolyear_m->get_schoolyear();
		$this->data['classes'] = $this->classes_m->get_classes();
		$classesID = $this->input->post("classesID");

		if($classesID > 0) {
			$this->data['subjects'] = $this->subject_m->get_order_by_subject(array('classesID' =>$classesID));
			$this->data['sections'] = $this->section_m->get_order_by_section(array("classesID" => $classesID));
			if($this->input->post('subjectID') > 0) {
				$this->data['teachers'] = $this->subjectteacher_m->get_subjectteacher_with_teacher($this->input->post('subjectID'));
			}
			if($this->input->post('combinedSubjectID') > 0) {
				$this->data['teachers'] = $this->subjectteacher_m->get_subjectteacher_with_teacher($this->input->post('combinedSubjectID'));
			}
		} else {
			$this->data['subjects'] = [];
			$this->data['sections'] = [];
			$this->data['teachers'] = [];
		}

		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors(); 
				$this->data["subview"] = "routine/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"classesID" 			=> $this->input->post("classesID"),
					"sectionID" 			=> $this->input->post("sectionID"),
					"subjectID"	 			=> $this->input->post("subjectID"),
					"combinedSubjectID"	 	=> $this->input->post("combinedSubjectID"),
					'schoolyearID' 			=> $this->input->post('schoolyearID'),
 					"day" 					=> $this->input->post("day"),
 					'teacherID' 			=> $this->input->post('teacherID'),
 					'combinedTeacherID' 	=> $this->input->post('combinedTeacherID'),
					"start_time" 			=> $this->input->post("start_time"),
					"end_time" 				=> $this->input->post("end_time"),
					"room" 					=> $this->input->post("room"),
					"combinedRoom" 					=> $this->input->post("combinedRoom")
				);
				$this->routine_m->insert_routine($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("routine/index"));
			}
		} else {
			$this->data["subview"] = "routine/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/timepicker/timepicker.css'
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/timepicker/timepicker.js'
			)
		);

		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$id && (int)$url) {
			if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$this->data['routine'] = $this->routine_m->get_single_routine(array('routineID' => $id, 'classesID' => $url, 'schoolyearID' => $schoolyearID));
				if(count($this->data['routine'])) {
					if($this->input->post('classesID') > 0) {
						$classID = $this->input->post('classesID');
						$subjectID = $this->input->post('subjectID');
	 				} else {
						$classID = $this->data['routine']->classesID;
						$subjectID = $this->data['routine']->subjectID;
					}

					$this->data['classes'] = $this->classes_m->get_classes();
					$this->data['schoolyears'] = $this->schoolyear_m->get_schoolyear();
					$this->data['sections'] = $this->section_m->general_get_order_by_section(array("classesID" => $classID));
					$this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array('classesID' => $classID));
					$this->data['teachers'] = $this->subjectteacher_m->get_subjectteacher_with_teacher($subjectID);
					$this->data['set'] = $url;
					$fetchClass = pluck($this->data['classes'], 'classesID', 'classesID');
					if(isset($fetchClass[$url])) {
						if($_POST) {
							$rules = $this->rules();
							$this->form_validation->set_rules($rules);
							if ($this->form_validation->run() == FALSE) {
								$this->data["subview"] = "routine/edit";
								$this->load->view('_layout_main', $this->data);			
							} else {
								$array = array(
									"classesID" => $this->input->post("classesID"),
									"sectionID" => $this->input->post("sectionID"),
									"subjectID" => $this->input->post("subjectID"),
									"day" => $this->input->post("day"),
									'teacherID' => $this->input->post('teacherID'),
									"start_time" => $this->input->post("start_time"),
									"end_time" => $this->input->post("end_time"),
									"room" => $this->input->post("room"),
									"combinedSubjectID"	=> $this->input->post("combinedSubjectID"),
									'combinedTeacherID'	=> $this->input->post('combinedTeacherID'),
									"combinedRoom"	=> $this->input->post("combinedRoom")
								);

								$this->routine_m->update_routine($array, $id);
								$this->session->set_flashdata('success', $this->lang->line('menu_success'));
								redirect(base_url("routine/index/$url"));
							}
						} else {
							$this->data["subview"] = "routine/edit";
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
		$routineID = htmlentities(escapeString($this->uri->segment(3)));
		$classesID = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$routineID && (int)$classesID) {
			if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if(isset($fetchClass[$classesID])) {
					$routine = $this->routine_m->get_order_by_routine(array('routineID' => $routineID, 'classesID' => $classesID, 'schoolyearID' => $schoolyearID));
					if(count($routine)) {
						$this->routine_m->delete_routine($routineID);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("routine/index/$classesID"));
					} else {
						redirect(base_url("routine/index"));
					}
				} else {
					redirect(base_url("routine/index"));
				}
			} else {
				redirect(base_url('routine/index'));
			}
		} else {
			redirect(base_url("routine/index"));
		}
	}

	public function routine_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("routine/index/$classID");
			echo $string;
		} else {
			redirect(base_url("routine/index"));
		}
	}

	public function unique_teacher() {
		$id = htmlentities(escapeString($this->uri->segment(3)));

		$data = [
			'day' => $this->input->post('day'),
			'start_time' => $this->input->post('start_time'),
			'end_time' => $this->input->post('end_time'),
			'schoolyearID' => $this->input->post('schoolyearID'),
			'teacherID' => $this->input->post('teacherID'),
		];

		if ((int)$id) {
			
			$data['id'] = (int)$id;

			if ($this->input->post('combinedTeacherID')) {
				$data['combinedTeacherID'] = $this->input->post('combinedTeacherID');
			}
			
		} else {
			
			if ($this->input->post('combinedTeacherID')) {
				$data['combinedTeacherID'] = $this->input->post('combinedTeacherID');
			}

		}

		$routine = $this->routine_m->check_teacher_collision_in_routine($data);

		if(count($routine)) {
			$this->form_validation->set_message("unique_teacher", "%s already assign another schedule in this time.");
			return FALSE;
		}
		
		return TRUE;
	}

	public function unique_data($data) {
		if($data == 0) {
			$this->form_validation->set_message("unique_data", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function teachercall() {
		$subjectID = $this->input->post('subjectID');
		echo "<option value='0'>", $this->lang->line("routine_select_teacher"),"</option>";
		if((int)$subjectID) {
			$teachers = $this->subjectteacher_m->get_subjectteacher_with_teacher($subjectID);
			if(count($teachers)) {
				foreach ($teachers as $teacher) {
					echo "<option value=\"$teacher->teacherID\">",$teacher->name,"</option>";
				}
			}
		}
	}

	public function subjectcall() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$allclasses = $this->subject_m->general_get_order_by_subject(array('classesID' => $classID));
			echo "<option value='0'>", $this->lang->line("routine_subject_select"),"</option>";
			foreach ($allclasses as $value) {
				echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
			}
		} 
	}

	public function sectioncall() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$allsection = $this->section_m->general_get_order_by_section(array('classesID' => $classID));
			echo "<option value='0'>", $this->lang->line("routine_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
		} 
	}

	public function unique_room() {
		$id = htmlentities(escapeString($this->uri->segment(3)));

		$routine = $this->check_room_collision($id, $this->input->post(), 'room');
		
		if(count($routine)) {
			$this->form_validation->set_message("unique_room", "%s already assign another schedule in this time.");
			return FALSE;
		}
	
		return TRUE;
	}

	public function unique_combined_room() {
		$id = htmlentities(escapeString($this->uri->segment(3)));

		$routine = $this->check_room_collision($id, $this->input->post(), 'combined_room');
		
		if(count($routine)) {
			$this->form_validation->set_message("unique_combined_room", "%s already assign another schedule in this time.");
			return FALSE;
		}

		return TRUE;
	}

	public function check_room_collision($id, $post, $type)
	{
		$data = [
			'classesID' => $post['classesID'],
			'day' => $post['day'],
			'start_time' => $post['start_time'],
			'end_time' => $post['end_time'],
			'schoolyearID' => $post['schoolyearID'],
			'room' => $post['room']
		];

		if ((int)$id) {
			
			$data['id'] = (int)$id;

		}

		if ($type == 'combined_room') {

			$data['combinedRoom'] = $post['combinedRoom'];

			return $this->routine_m->check_combined_room_collision_in_routine($data);

		} 
		
		if($type == 'room')
		{

			if ($post['combinedRoom']) {
				$data['combinedRoom'] = $post['combinedRoom'];
			}

			return $this->routine_m->check_room_collision_in_routine($data);
		}
	}

	public function unique_section() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$routine = $this->routine_m->get_order_by_routine(array('classesID' => $this->input->post('classesID'), 'day' => $this->input->post('day'), 'start_time' => $this->input->post('start_time'), 'end_time' => $this->input->post('end_time'), 'sectionID' => $this->input->post('sectionID'), 'schoolyearID' => $this->input->post('schoolyearID'), 'routineID !=' => $id ));
			if(count($routine)) {
				$this->form_validation->set_message("unique_section", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$routine = $this->routine_m->get_order_by_routine(array('classesID' => $this->input->post('classesID'), 'day' => $this->input->post('day'), 'start_time' => $this->input->post('start_time'), 'end_time' => $this->input->post('end_time'), 'sectionID' => $this->input->post('sectionID'), 'schoolyearID' => $this->input->post('schoolyearID')));
			if(count($routine)) {
				$this->form_validation->set_message("unique_section", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}
	}

	public function unique_day() {
		$day = $this->input->post('day');
		if($day == '100') {
			$this->form_validation->set_message("unique_day", "The %s field is required.");
			return FALSE;
		}
		return TRUE;
	}
}