<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hmember extends Admin_Controller {
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
		$this->load->model("hmember_m");
		$this->load->model("category_m");
		$this->load->model("hostel_m");
		$this->load->model("student_m");
		$this->load->model("studentrelation_m");
		$this->load->model("section_m");
		$this->load->model('parents_m');
        $this->load->model('studentgroup_m');
        $this->load->model('subject_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('hmember', $language);	
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("hmember_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("hmember_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("hmember_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("hmember_studentID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("hmember_classesID"),
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
				'field' => 'hostelID', 
				'label' => $this->lang->line("hmember_hname"), 
				'rules' => 'trim|max_length[11]|required|xss_clean|numeric|callback_unique_gender'
			),
			array(
				'field' => 'categoryID', 
				'label' => $this->lang->line("hmember_class_type"), 
				'rules' => 'trim|max_length[11]|required|xss_clean|numeric|callback_unique_select|callback_unique_category'
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
			if(!permissionChecker('hmember_view')) {
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
						foreach ($sections as $key => $section) {
							$this->data['allsection'][$section->sectionID] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, "srsectionID" => $section->sectionID, 'srschoolyearID' => $schoolyearID));
						}
					} else {
						$this->data['students'] = [];
					}
				} else {
					$this->data['students'] = [];
				}

				$this->data["subview"] = "hmember/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['set'] = $id;
				$this->data['students'] = [];
				$this->data['classes'] = $this->classes_m->get_classes();
				$this->data["subview"] = "hmember/index";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
				),
				'js' => array(
					'assets/select2/select2.js',
				)
			);

			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$this->data["hostels"] = $this->hostel_m->get_hostel();
			$schoolyearID = $this->session->userdata('defaultschoolyearID');

			$hostelID = $this->input->post("hostelID");
			if($hostelID > 0) {
				$this->data['categorys'] = $this->category_m->get_order_by_category(array("hostelID" => $hostelID));
			} else {
				$this->data['categorys'] = [];
			}

			if((int)$id && (int)$url) {
				$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
				if(count($student)) {
					if($student->hostel == 0) {
						if($_POST) {
							$rules = $this->rules();
							$this->form_validation->set_rules($rules);
							if ($this->form_validation->run() == FALSE) { 
								$this->data["subview"] = "hmember/add";
								$this->load->view('_layout_main', $this->data);			
							} else {
								$hostel_main_id = $this->hostel_m->get_hostel($this->input->post("hostelID"));
								$category_main_id = $this->category_m->get_single_category(array("hostelID" => $hostel_main_id->hostelID, "categoryID" =>  $this->input->post("categoryID")));
								if($hostel_main_id) {
									if($category_main_id) {
										$array = array(
											"hostelID" => $this->input->post("hostelID"),
											"categoryID" => $this->input->post("categoryID"),
											"studentID" => $id,
											"hbalance" => $category_main_id->hbalance,
											"hjoindate" => date("Y-m-d")
										);
										$this->hmember_m->insert_hmember($array);
										$this->student_m->update_student(array("hostel" => 1), $id);
										$this->session->set_flashdata('success', $this->lang->line('menu_success'));
										redirect(base_url("hmember/index/$url"));
									} else {
										$this->data["subview"] = "error";
										$this->load->view('_layout_main', $this->data);
									}
								} else {
									$this->data["subview"] = "error";
									$this->load->view('_layout_main', $this->data);
								}
							}
						} else {
							$this->data["subview"] = "hmember/add";
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
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
				),
				'js' => array(
					'assets/select2/select2.js',
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
						$this->data["hmember"] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
						if($this->data["hmember"]) {
							$this->data["categorys"] = $this->category_m->get_order_by_category(array("hostelID" => $this->data["hmember"]->hostelID));
							if($this->data["categorys"]) {

								$this->data["hostels"] = $this->hostel_m->get_hostel();
								$this->data['set'] = $url;
								$hostelID = $this->input->post("hostelID");
								if($hostelID > 0) {
									$this->data['categorys'] = $this->category_m->get_order_by_category(array("hostelID" => $hostelID));
								} else {
									$this->data["categorys"] = $this->category_m->get_order_by_category(array("hostelID" => $this->data["hmember"]->hostelID));
								}

								if($student->hostel == 1) {
									if($_POST) {
										$rules = $this->rules();
										$this->form_validation->set_rules($rules);
										if ($this->form_validation->run() == FALSE) { 
											$this->data["subview"] = "hmember/edit";
											$this->load->view('_layout_main', $this->data);
										} else {
											$hostel_main_id = $this->hostel_m->get_hostel($this->input->post("hostelID"));
											$category_main_id = $this->category_m->get_single_category(array("hostelID" => $hostel_main_id->hostelID, "categoryID" =>  $this->input->post("categoryID")));

											if($hostel_main_id) {
												if($category_main_id) {
													$array = array(
														"hostelID" => $this->input->post("hostelID"),
														"categoryID" => $this->input->post("categoryID"),
														"studentID" => $id,
														"hbalance" => $category_main_id->hbalance
													);

													$this->hmember_m->update_hmember($array, $this->data['hmember']->hmemberID);
													$this->session->set_flashdata('success', $this->lang->line('menu_success'));
													redirect(base_url("hmember/index/$url"));
												} else {
													$this->data["subview"] = "error";
													$this->load->view('_layout_main', $this->data);
												}
											} else {
												$this->data["subview"] = "error";
												$this->load->view('_layout_main', $this->data);
											}				
										}
									} else {
										$this->data["subview"] = "hmember/edit";
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
				$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
				if($student) {
					$this->data["hmember"] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
					if($this->data["hmember"]) {
						$this->hmember_m->delete_hmember($this->data['hmember']->hmemberID);
						$this->student_m->update_student(array("hostel" => 0), $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("hmember/index/$url"));
					} else {
						redirect(base_url("hmember/index"));
					}
				} else {
					redirect(base_url("hmember/index"));
				}
			} else {
				redirect(base_url("hmember/index"));
			}
		} else {
			redirect(base_url("hmember/index"));
		}
	}

	public function view($id = null , $url = null) {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$id && (int)$url) {
			$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
			if(isset($fetchClass[$url])) {
				$this->data['set'] = $url;
				$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID), TRUE);
				$this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
	        	$this->data['optionalSubjects'] = pluck($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');
	        	$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
				if(count($this->data['student'])) {
					$this->data["class"] = $this->classes_m->get_classes($this->data['student']->srclassesID);
					$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
					$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
					if(count($this->data['hmember'])) {
						$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
						$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
						$this->data["subview"] = "hmember/getView";
						$this->load->view('_layout_main', $this->data);
					} else {
						$this->data['hostel'] = [];
						$this->data['category'] = [];
						$this->data["subview"] = "hmember/getView";
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
		$usertypeID = $this->session->userdata("usertypeID");
        $this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
        $this->data['optionalSubjects'] = pluck($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');
		if(permissionChecker('hmember_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('hmember') && ($this->session->userdata('loginuserID') == htmlentities(escapeString($this->uri->segment(3)))))) {

			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$id && (int)$url) {
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if(isset($fetchClass[$url])) {
					$this->data['set'] = $url;
					$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID), TRUE);
					$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
					if(count($this->data['student'])) {
						$this->data["classes"] = $this->classes_m->get_single_classes(array('classesID' => $this->data['student']->srclassesID));
						$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
						$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
						if(count($this->data['hmember'])) {
							$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
							$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
							$this->reportPDF('hmembermodule.css',$this->data, 'hmember/print_preview');
						} else {
							$this->data['hostel'] = [];
							$this->data['category'] = [];
							$this->reportPDF('hmembermodule.css',$this->data, 'hmember/print_preview');
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
		// if(permissionChecker('hmember_view')) {
		if(permissionChecker('hmember_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('hmember') && ($this->session->userdata('loginuserID') == $this->input->post('studentID')))) {
			if($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
			        $this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
			        $this->data['optionalSubjects'] = pluck($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');

					$id = $this->input->post('studentID');
					$url = $this->input->post('classesID');
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					if ((int)$id && (int)$url) {
						$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
						if(isset($fetchClass[$url])) {
							$this->data["student"] = $this->studentrelation_m->get_single_student(array('srschoolyearID' => $schoolyearID, 'srstudentID' => $id));
							$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
							if(count($this->data["student"])) {
								$this->data["classes"] = $this->classes_m->get_classes($this->data['student']->srclassesID);
								$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
								$this->data['hmember'] = $this->hmember_m->get_single_hmember(array("studentID" => $id));
								if($this->data['hmember']) {
									$this->data['hostel'] = $this->hostel_m->get_hostel($this->data['hmember']->hostelID);
									$this->data['category'] = $this->category_m->get_category($this->data['hmember']->categoryID);
								} else {
									$this->data['hostel'] = [];
									$this->data['category'] = [];
								}

								$email = $this->input->post('to');
								$subject = $this->input->post('subject');
								$message = $this->input->post('message');
								$this->reportSendToMail('hmembermodule.css',$this->data, 'hmember/print_preview', $email, $subject, $message);
								$retArray['message'] = "Message";
								$retArray['status'] = TRUE;
								echo json_encode($retArray);
							} else {
								$retArray['message'] = $this->lang->line('hmember_data_not_found');
								echo json_encode($retArray);
								exit;
							}
						} else {
							$retArray['message'] = $this->lang->line('hmember_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('hmember_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('hmember_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('hmember_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function student_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("hmember/index/$classID");
			echo $string;
		} else {
			redirect(base_url("hmember/index"));
		}
	}

	public function categorycall() {
		$classtype = $this->input->post('id');
		echo "<option value='0'>", $this->lang->line("hmember_select_class_type"),"</option>";
		if((int)$classtype) {
			$allclasstype = $this->category_m->get_order_by_category(array("hostelID" => $classtype));
			foreach ($allclasstype as $value) {
				echo "<option value=\"$value->categoryID\">",$value->class_type,"</option>";
			}
		} 
	}

	public function unique_select() {
		if($this->input->post("categoryID") == 0) {
			$this->form_validation->set_message("unique_select", "The %s field is required");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_gender() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			if($this->input->post("hostelID") == 0) {
				$this->form_validation->set_message("unique_gender", "The %s field is required");
				return FALSE;
			} else {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
				$hostel = $this->hostel_m->get_single_hostel(array("hostelID" => $this->input->post("hostelID")));
				if($hostel) {
					$gender = "";
					if($student->sex == "Male") {
						$gender = "Boys";
					} else {
						$gender = "Girls";
					}

					if($hostel->htype == $gender) {
						return TRUE;
					} elseif($hostel->htype == "Combine") {
						return TRUE;
					} else {
						$this->form_validation->set_message("unique_gender", "This hostel only for $hostel->htype.");
						return FALSE;
					}
				} else {
					$this->form_validation->set_message("unique_gender", "The %s field is required");
					return FALSE;
				}
			}
		}
		return FALSE;	
	}

	public function unique_category() {
		$hostelID = $this->input->post('hostelID');
		$categoryID = $this->input->post('categoryID');
		if($hostelID != 0 && $categoryID != 0 ) {
			$category = $this->category_m->get_single_category(array('hostelID' => $hostelID, 'categoryID' => $categoryID));
			if(!count($category)) {
				$this->form_validation->set_message("unique_category", "The %s field is required");
				return FALSE;
			}
			return TRUE;
		} else {
			$this->form_validation->set_message("unique_category", "The %s field is required");
			return FALSE;
		}
	}
}