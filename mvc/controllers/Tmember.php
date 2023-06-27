<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tmember extends Admin_Controller {
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
		$this->load->model("tmember_m");
		$this->load->model("transport_m");
		$this->load->model("student_m");
		$this->load->model("studentrelation_m");
		$this->load->model("section_m");
        $this->load->model('studentgroup_m');
        $this->load->model('subject_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('tmember', $language);	
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("tmember_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("tmember_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("tmember_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("tmember_studentID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("tmember_classesID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			)
		);
		return $rules;
	}

	public function unique_data($data) {
		if($data != '') {
			if($data == '0') {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'transportID', 
				'label' => $this->lang->line("tmember_route_name"), 
				'rules' => 'trim|required|max_length[11]|xss_clean|callback_unique_transportID'
			),
			array(
				'field' => 'vehicle', 
				'label' => $this->lang->line("tmember_vehicle"), 
				'rules' => 'trim|required|max_length[20]|xss_clean'
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

		$myProfile = false;
		if($this->session->userdata('usertypeID') == 3) {
			$id = $this->data['myclass'];
			if(!permissionChecker('tmember_view')) {
				$myProfile = true;
			}
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
		}

		if($this->session->userdata('usertypeID') == 3 && $myProfile) {
			$url = $id;
			$id = $this->session->userdata('loginuserID');
			$this->view($id, $url);
		} else {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$id) {
				$this->data['set'] = $id;
				$this->data['classes'] = $this->classes_m->get_classes();
				$fetchClass = pluck($this->data['classes'], 'classesID', 'classesID');
				if(isset($fetchClass[$id])) {
					$this->data['students'] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, 'srschoolyearID' => $schoolyearID));

					if(count($this->data['students'])) {
						$sections = $this->section_m->general_get_order_by_section(array("classesID" => $id));
						$this->data['sections'] = $sections;
						if(count($sections)) {
							foreach ($sections as $key => $section) {
								$this->data['allsection'][$section->sectionID] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, "srsectionID" => $section->sectionID, 'srschoolyearID' => $schoolyearID));
							}
						}
					} else {
						$this->data['students'] = [];
					}
				} else {
					$this->data['students'] = [];
				}

				$this->data["subview"] = "tmember/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['set'] = $id;
				$this->data['students'] = [];
				$this->data['classes'] = $this->classes_m->get_classes();
				$this->data["subview"] = "tmember/index";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
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
			$this->data['transports'] = $this->transport_m->get_transport();
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			
			if((int)$id && (int)$url) {
				$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
				if(count($student)) {
					$this->data['set'] = $url;
					if($student->transport == 0) {
						if($_POST) {
							$rules = $this->rules();
							$this->form_validation->set_rules($rules);
							if ($this->form_validation->run() == FALSE) {
								$this->data["subview"] = "tmember/add";
								$this->load->view('_layout_main', $this->data);			
							} else {
								$array = array(
									"studentID" => $student->srstudentID,
									"transportID" => $this->input->post("transportID"),
									"name" => $student->srname,
									"email" => $student->email,
									"phone" => $student->phone,
									"vehicle" => $this->input->post("vehicle"),
									"tjoindate" => date("Y-m-d")
								);

								$this->tmember_m->insert_tmember($array);
								$this->student_m->update_student(array("transport" => 1), $id);
								$this->session->set_flashdata('success', $this->lang->line('menu_success'));
								redirect(base_url("tmember/index/$url"));
							}
						} else {
							$this->data["subview"] = "tmember/add";
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

	public function edit() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
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
			$schoolyearID = $this->session->userdata('defaultschoolyearID');

			if((int)$id && (int)$url) {
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if(isset($fetchClass[$url])) {
					$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
					if(count($student)) {
						$this->data['tmember'] = $this->tmember_m->get_single_tmember(array("studentID" =>$id));
						if(count($this->data['tmember'])) {
							$this->data['transports'] = $this->transport_m->get_transport();
							$this->data['set'] = $url;
							if($student->transport == 1) {
								if($_POST) {
									$rules = $this->rules();
									$this->form_validation->set_rules($rules);
									if ($this->form_validation->run() == FALSE) { 
										$this->data["subview"] = "tmember/edit";
										$this->load->view('_layout_main', $this->data);
									} else {
										$array = array(
											"transportID" => $this->input->post("transportID"),
											"vehicle" => $this->input->post("vehicle")
										);
										$this->tmember_m->update_tmember($array, $this->data['tmember']->tmemberID);
										$this->session->set_flashdata('success', $this->lang->line('menu_success'));
										redirect(base_url("tmember/index/$url"));	
									}
								} else {
									$this->data["subview"] = "tmember/edit";
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
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$id && (int)$url) {
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if(isset($fetchClass[$url])) {
					$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
					if($student) {
						$this->tmember_m->delete_tmember_sID($id);
						$this->student_m->update_student(array("transport" => 0), $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("tmember/index/$url"));
					} else {
						redirect(base_url("tmember/index"));
					}
				} else {
					redirect(base_url("tmember/index"));
				}
			} else {
				redirect(base_url("tmember/index"));
			}
		} else {
			redirect(base_url("tmember/index"));
		}
	}

	public function view($id = null, $url = null) {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$id && (int)$url) {
			$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
			if(isset($fetchClass[$url])) {
				$this->data['set'] = $url;
				$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID), true);
				$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
				if(count($this->data['student'])) {
					$this->data["classes"] = $this->classes_m->get_classes($this->data['student']->srclassesID);
					$this->data['tmember'] = $this->tmember_m->get_single_tmember(array('studentID' => $id));
					$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
					if(count($this->data['tmember'])) {
						$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
						$this->data["subview"] = "tmember/getView";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data['transport'] = [];
						$this->data["subview"] = "tmember/getView";
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

	public function print_preview() {
		if(permissionChecker('tmember_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('tmember') && ($this->session->userdata('loginuserID') == htmlentities(escapeString($this->uri->segment(3)))))) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$id && (int)$url) {
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if(isset($fetchClass[$url])) {
					$this->data['set'] = $url;
					$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
					$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
					if(count($this->data['student'])) {
						$this->data["classes"] = $this->classes_m->get_classes($this->data['student']->srclassesID);
						$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
						$this->data['tmember'] = $this->tmember_m->get_single_tmember(array('studentID' => $id));
						if(count($this->data['tmember'])) {
							$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
							$this->reportPDF('tmembermodule.css',$this->data, 'tmember/print_preview');
						} else {
							$this->data['transport'] = [];
							$this->reportPDF('tmembermodule.css',$this->data, 'tmember/print_preview');
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
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}
	
	public function send_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('tmember_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('tmember') && ($this->session->userdata('loginuserID') == $this->input->post('studentID')))) {
			if($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$id = $this->input->post('studentID');
					$url = $this->input->post('classesID');
					if ((int)$id && (int)$url) {
						$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
						if(isset($fetchClass[$url])) {
							$schoolyearID = $this->session->userdata('defaultschoolyearID');
							$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
							$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
							if(count($this->data["student"])) {
								$this->data["classes"] = $this->classes_m->get_classes($this->data['student']->srclassesID);
								$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
								$this->data['tmember'] = $this->tmember_m->get_single_tmember(array('studentID' => $id));
								if(count($this->data['tmember'])) {
									$this->data['transport'] = $this->transport_m->get_transport($this->data['tmember']->transportID);
									$email = $this->input->post('to');
									$subject = $this->input->post('subject');
									$message = $this->input->post('message');
									$this->reportSendToMail('tmembermodule.css',$this->data, 'tmember/print_preview', $email, $subject, $message);
									$retArray['message'] = "Success";
									$retArray['status'] = TRUE;
									echo json_encode($retArray);
								} else {
									$this->data['transport'] = [];
									$email = $this->input->post('to');
									$subject = $this->input->post('subject');
									$message = $this->input->post('message');
									$this->reportSendToMail('tmembermodule.css',$this->data, 'tmember/print_preview', $email, $subject, $message);
									$retArray['message'] = "Success";
									$retArray['status'] = TRUE;
									echo json_encode($retArray);
								}
							} else {
								$retArray['message'] = $this->lang->line('student_data_not_found');
								echo json_encode($retArray);
								exit;
							}
						} else {
							$retArray['message'] = $this->lang->line('student_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('student_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('tmember_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('tmember_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function student_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("tmember/index/$classID");
			echo $string;
		} else {
			redirect(base_url("tmember/index"));
		}
	}

	public function transport_fare() {
		$transportID = $this->input->post('id');
		if((int)$transportID) {
			$string = $this->transport_m->get_transport($transportID);
			echo $string->fare;
		} else {
			echo '';
		}
	}

	public function unique_transportID() {
		if($this->input->post('transportID') == 0) {
			$this->form_validation->set_message("unique_transportID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function valid_number() {
		if($this->input->post('tbalance') && $this->input->post('tbalance') < 0) {
			$this->form_validation->set_message("valid_number", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}
}