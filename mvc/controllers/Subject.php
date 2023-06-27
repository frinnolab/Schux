<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Subject extends Admin_Controller {
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
		$this->load->model("subject_m");
		$this->load->model("parents_m");
		$this->load->model("classes_m");
		$this->load->model("teacher_m");
		$this->load->model("student_m");
		$this->load->model("subjectteacher_m");
		$this->load->model("studentrelation_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('subject', $language);	
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

		if($this->session->userdata('usertypeID') == 3) {
			$id = $this->data['myclass'];
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
		}

		if((int)$id) {
			$this->data['set'] = $id;
			$this->data['teachers'] = pluck($this->teacher_m->general_get_teacher(), 'name', 'teacherID');
			$this->data['classes'] = $this->student_m->get_classes();
			$fetchClass = pluck($this->data['classes'], 'classesID', 'classesID');
			if(isset($fetchClass[$id])) {
				$this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array('classesID' => $id));
				$this->data['subjectteachers'] = pluck_multi_array($this->subjectteacher_m->get_order_by_subjectteacher(array('classesID' => $id)), 'teacherID', 'subjectID');
				$this->data["subview"] = "subject/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['set'] = 0;
				$this->data['subjects'] = [];
				$this->data['subjectteachers'] = [];
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["subview"] = "subject/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data['set'] = 0;
			$this->data['subjects'] = [];
			$this->data['subjectteachers'] = [];
			$this->data['classes'] = $this->student_m->get_classes();
			$this->data["subview"] = "subject/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	protected function rules() {
		$rules = array(
				array(
					'field' => 'classesID', 
					'label' => $this->lang->line("subject_class_name"), 
					'rules' => 'trim|numeric|required|xss_clean|max_length[11]|callback_unique_classes'
				),
				array(
					'field' => 'teacherID', 
					'label' => $this->lang->line("subject_teacher_name"), 
					'rules' => 'trim|xss_clean|callback_unique_teacher'
				),
				array(
					'field' => 'type', 
					'label' => $this->lang->line("subject_type"), 
					'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_type'
				),
				array(
					'field' => 'passmark', 
					'label' => $this->lang->line("subject_passmark"), 
					'rules' => 'trim|required|xss_clean|max_length[11]|numeric'
				),
				array(
					'field' => 'finalmark', 
					'label' => $this->lang->line("subject_finalmark"), 
					'rules' => 'trim|required|xss_clean|max_length[11]|numeric'
				),
				array(
					'field' => 'subject', 
					'label' => $this->lang->line("subject_name"), 
					'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_subject'
				), 
				array(
					'field' => 'subject_author', 
					'label' => $this->lang->line("subject_author"), 
					'rules' => 'trim|xss_clean|max_length[100]'
				), 
				array(
					'field' => 'subject_code', 
					'label' => $this->lang->line("subject_code"),
					'rules' => 'trim|required|max_length[20]|xss_clean'
				),
			);
		return $rules;
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		$this->data['classes'] = $this->classes_m->get_classes();
		$this->data['teachers'] = $this->teacher_m->general_get_teacher();
		$this->data['teachersID'] = [];
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				if(count($this->input->post('teacherID'))) {
					$this->data['teachersID'] = pluck($this->teacher_m->get_where_in_teacher($this->input->post('teacherID')), 'teacherID');
				}
				$this->data["subview"] = "subject/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"classesID" => $this->input->post("classesID"),
					"subject" => $this->input->post("subject"),
					'type' => $this->input->post('type'),
					'passmark' => $this->input->post('passmark'),
					'finalmark' => $this->input->post('finalmark'),
					"subject_author" => $this->input->post("subject_author"),
					"subject_code" => $this->input->post("subject_code"),
					"teacher_name" => '',
					"create_date" => date("Y-m-d h:i:s"),
					"modify_date" => date("Y-m-d h:i:s"),
					"create_userID" => $this->session->userdata('loginuserID'),
					"create_username" => $this->session->userdata('username'),
					"create_usertype" => $this->session->userdata('usertype')
				);
				$this->subject_m->insert_subject($array);
				$subjectID = $this->db->insert_id();

				$teachers = $this->input->post('teacherID');
				$subjectteacherArray = [];
				if($teachers) {
					foreach ($teachers as $teacherID) {
						$subjectteacherArray[] = [
							'subjectID' => $subjectID,
							'teacherID' => $teacherID,
							'classesID' => $this->input->post("classesID"),
						];
					}
				}

				if(count($subjectteacherArray)) {
					$this->subjectteacher_m->insert_batch_subjectteacher($subjectteacherArray);
				}

				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("subject/index/".$this->input->post("classesID")));
			}
		} else {
			$this->data["subview"] = "subject/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$id && (int)$url) {
			$this->data['classes'] = $this->classes_m->get_classes();
			$fetchClass = pluck($this->data['classes'], 'classesID', 'classesID');
			if(isset($fetchClass[$url])) {
				$this->data['teachers'] = $this->teacher_m->general_get_teacher();
				$this->data['subject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $id, 'classesID' => $url));
				$this->data['teachersID'] = pluck($this->subjectteacher_m->get_order_by_subjectteacher(array('subjectID' => $id)), 'teacherID');
				if(count($this->data['subject'])) {
					$this->data['set'] = $url;
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							if(count($this->input->post('teacherID'))) {
								$this->data['teachersID'] = pluck($this->teacher_m->get_where_in_teacher($this->input->post('teacherID')), 'teacherID');
							}
							$this->data["subview"] = "subject/edit";
							$this->load->view('_layout_main', $this->data);			
						} else {
							$array = array(
								"classesID" => $this->input->post("classesID"),
								"subject" => $this->input->post("subject"),
								'type' => $this->input->post('type'),
								'passmark' => $this->input->post('passmark'),
								'finalmark' => $this->input->post('finalmark'),
								"subject_author" => $this->input->post("subject_author"),
								"subject_code" => $this->input->post("subject_code"),
								"modify_date" => date("Y-m-d h:i:s")
							);
							
							$this->subject_m->update_subject($array, $id);
							$teachers = $this->input->post('teacherID');
							$subjectteacherArray = [];
							if($teachers) {
								foreach ($teachers as $teacherID) {
									$subjectteacherArray[] = [
										'subjectID' => $id,
										'teacherID' => $teacherID,
										'classesID' => $this->input->post("classesID"),
									];
								}
							}

							if(count($subjectteacherArray)) {
								$this->subjectteacher_m->delete_subjectteacher_by_array(array('subjectID' => $id));
								$this->subjectteacher_m->insert_batch_subjectteacher($subjectteacherArray);
							}

							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("subject/index/$url"));
						}
					} else {
						$this->data["subview"] = "subject/edit";
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
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$id && (int)$url) {
			$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
			if(isset($fetchClass[$url])) {
				$subject = $this->subject_m->general_get_single_subject(array('subjectID' => $id, 'classesID' => $url));
				if(count($subject)) {
					$this->subjectteacher_m->delete_subjectteacher_by_array(array('subjectID' => $id));
					$this->subject_m->delete_subject($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("subject/index/$url"));
				} else {
					redirect(base_url("subject/index"));
				}
			} else {
				redirect(base_url("subject/index"));
			}
		} else {
			redirect(base_url("subject/index"));
		}
	}

	public function unique_subject() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$subject = $this->subject_m->general_get_order_by_subject(array("subject" => $this->input->post("subject"), "subjectID !=" => $id, "classesID" => $this->input->post("classesID")));
			if(count($subject)) {
				$this->form_validation->set_message("unique_subject", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$subject = $this->subject_m->general_get_order_by_subject(array("subject" => $this->input->post("subject"), "classesID" => $this->input->post("classesID"), "subject_code" => $this->input->post("subject_code")));

			if(count($subject)) {
				$this->form_validation->set_message("unique_subject", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}	
	}

	public function unique_subject_code() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$subject = $this->subject_m->general_get_order_by_subject(array("subject_code" => $this->input->post("subject_code"), "subjectID !=" => $id));
			if(count($subject)) {
				$this->form_validation->set_message("unique_subject_code", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$subject = $this->subject_m->general_get_order_by_subject(array("subject_code" => $this->input->post("subject_code")));

			if(count($subject)) {
				$this->form_validation->set_message("unique_subject_code", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}	
	}

	public function subject_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("subject/index/$classID");
			echo $string;
		} else {
			redirect(base_url("subject/index"));
		}
	}

	public function unique_classes() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classes", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_teacher() {
		$error = 0;
		$teachers = $this->input->post('teacherID');
		if(count($teachers)) {
			foreach($teachers as $teacher) {
				$teacherID = $teacher;
				$teacher = $this->teacher_m->general_get_single_teacher(array('teacherID' => $teacherID));
				if(!count($teacher)) {
					$error++;
				}
			}

			if($error == 0) {
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_teacher", "The %s is required.");
	     		return FALSE;
			}
		} else {
			$this->form_validation->set_message("unique_teacher", "The %s is required.");
	     	return FALSE;
		}
	}

	public function unique_type() {
		if($this->input->post('type') == 'select') {
			$this->form_validation->set_message("unique_type", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}
}