<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Issue extends Admin_Controller {
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
		$this->load->model("lmember_m");
		$this->load->model("book_m");
		$this->load->model("issue_m");
		$this->load->model("student_m");
		$this->load->model("studentrelation_m");
		$this->load->model("classes_m");
		$this->load->model("section_m");
		$this->load->model("parents_m");
		$this->load->model('invoice_m');
		$this->load->model('feetypes_m');
		$this->load->model("maininvoice_m");
		
		$language = $this->session->userdata('lang');
		$this->lang->load('issue', $language);	
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("issue_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("issue_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("issue_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'id',
				'label' => $this->lang->line("issue_issueID"),
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

	public function index() {
		$usertypeID = $this->session->userdata("usertypeID");
		if($usertypeID == 3) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentID = $this->session->userdata("loginuserID");			
			$student = $this->studentrelation_m->get_single_student(array("srstudentID" => $studentID, 'srschoolyearID' => $schoolyearID));
			if(count($student) && $student->library === '1') {
				$lmember = $this->lmember_m->get_single_lmember(array('studentID' => $student->studentID));
				$lID = $lmember->lID;
				$this->data['libraryID'] = $lID;

				$this->data['issues'] = $this->issue_m->get_issue_with_books(array("lID" => $lID));
				$this->data["subview"] = "issue/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['libraryID'] = 0;
				$this->data['issues'] = [];
				$this->data["subview"] = "issue/index";
				$this->load->view('_layout_main', $this->data);
			}
		} elseif($usertypeID == 4) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
				),
				'js' => array(
					'assets/select2/select2.js',
					'https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js',
				)
			);

			$parentID = $this->session->userdata("loginuserID");
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$parent = $this->parents_m->get_single_parents(array('parentsID' => $parentID));
			if(count($parent)) {
				$this->data['students'] = $this->studentrelation_m->get_order_by_student(array('parentID' => $parent->parentsID, 'srschoolyearID' => $schoolyearID));
				$id = htmlentities(escapeString($this->uri->segment(3)));
				if((int)$id) {
					$this->data['set'] = $id;
					$checkstudent = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
					if(count($checkstudent)) {
						if($checkstudent->library === '1' && 0 == 1) {
							$lmember = $this->lmember_m->get_single_lmember(array('studentID' => $checkstudent->studentID));
							$lID = $lmember->lID;
							$this->data['libraryID'] = $lID;

							$this->data['issues'] = $this->issue_m->get_issue_with_books(array("lID" => $lID));
							$this->data["subview"] = "issue/index";
							$this->load->view('_layout_main', $this->data);
						} else {
							$this->data['libraryID'] = 0;
							$this->data['issues'] = [];
							$this->data["subview"] = "issue/index";
							$this->load->view('_layout_main', $this->data);
						}
					} else {
						$this->data["subview"] = "error";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data['set'] = 0;
					$this->data['issues'] = [];
					$this->data["subview"] = "issue/search_parent";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$ulID = htmlentities(escapeString($this->uri->segment(3)));
			$lID = htmlentities(escapeString($this->input->post("lid")));
			if($lID != "" || !empty($lID)) {
				redirect(base_url('issue/index/'.urlencode($lID)));
			} elseif($ulID) {
				$condition = "issue.lID='".urldecode($ulID)."' OR lmember.name LIKE '%".urldecode($ulID)."%'";
				$this->data['issues'] = $this->issue_m->get_issue_with_books($condition);
				$this->data['libraryID'] = urldecode($ulID);
				$this->data["subview"] = "issue/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "issue/search";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'lid', 
				'label' => $this->lang->line("issue_lid"), 
				'rules' => 'trim|required|xss_clean|max_length[40]|callback_unique_lID'
			), 
			array(
				'field' => 'book', 
				'label' => $this->lang->line("issue_book"),
				'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_book_call|callback_unique_quantity|callback_unique_book'
			), 
			array(
				'field' => 'author', 
				'label' => $this->lang->line("issue_author"),
				'rules' => 'trim|required|xss_clean'
			), 
			array(
				'field' => 'subject_code', 
				'label' => $this->lang->line("issue_subject_code"),
				'rules' => 'trim|xss_clean'
			), 
			array(
				'field' => 'serial_no', 
				'label' => $this->lang->line("issue_serial_no"),
				'rules' => 'trim|required|xss_clean|max_length[40]'
			),
			array(
				'field' => 'due_date', 
				'label' => $this->lang->line("issue_due_date"),
				'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid|callback_wrong_date'
			),
			array(
				'field' => 'note', 
				'label' => $this->lang->line("issue_note"), 
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'issue_condition', 
				'label' => $this->lang->line("issue_condition"), 
				'rules' => 'trim|required'
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
					'assets/datepicker/datepicker.css'
				),
				'js' => array(
					'assets/select2/select2.js',
					'assets/datepicker/datepicker.js',
					'assets/fullcalendar/lib/moment.min.js',
				)
			);
			$this->data['books'] = $this->book_m->get_book();
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "issue/add";
					$this->load->view('_layout_main', $this->data);			
				} else {
					$array = array(
						"lID" => $this->input->post("lid"),
						"bookID" => $this->input->post("book"),
						"serial_no" => $this->input->post("serial_no"),
						"issue_date" => date("Y-m-d"),
						"issue_condition" => $this->input->post("issue_condition"),
						"due_date" => date("Y-m-d", strtotime($this->input->post("due_date"))),
						"note" => $this->input->post("note")
					);

					$quantity = $this->book_m->get_single_book(array("bookID" => $this->input->post("book")));
					$allDueQuantity = ($quantity->due_quantity)+1;

					$this->book_m->update_book(array("due_quantity" => $allDueQuantity), $this->input->post("book"));
					$this->issue_m->insert_issue($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("issue/index"));
				}
			} else {
				$this->data["subview"] = "issue/add";
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
					'assets/datepicker/datepicker.css'
				),
				'js' => array(
					'assets/datepicker/datepicker.js'
				)
			);
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$lID = htmlentities(escapeString($this->uri->segment(4)));
			$this->data['books'] = $this->book_m->get_book();
			if((int)$id && $lID) {
				$this->data['issue'] = $this->issue_m->get_issue_with_books(array('issueID' => $id), TRUE);
				$dbGet_bookID = $this->data['issue']->bookID;
				$this->data['bookinfo'] = $this->book_m->get_book($dbGet_bookID);

				if(count($this->data['issue'])) {
					$this->data['set'] = $lID;
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "issue/edit";
							$this->load->view('_layout_main', $this->data);			
						} else {
							$array = array(
								"lID" => $this->input->post("lid"),
								"serial_no" => $this->input->post("serial_no"),
								"issue_condition" => $this->input->post("issue_condition"),
								"due_date" => date("Y-m-d", strtotime($this->input->post("due_date"))),
								"note" => $this->input->post("note")
							);

							$this->issue_m->update_issue($array, $id);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							if($this->session->userdata('usertypeID') == 4) {
								$lmember = $this->lmember_m->get_single_lmember(array('lID' => $this->data['issue']->lID));
								redirect(base_url("issue/index/$lmember->studentID"));
							} else {
								redirect(base_url("issue/index/$lID"));
							}
						}
					} else {
						$this->data["subview"] = "issue/edit";
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

	public function view() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['book'] = $this->issue_m->get_issue_with_books(array('issueID' => $id), TRUE);
			if(count($this->data['book'])) {
				$lmember = $this->lmember_m->get_single_lmember(array('lID' => $this->data['book']->lID));
				if(count($lmember)) {
					$this->data['lmember'] = $lmember;
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $lmember->studentID, 'srschoolyearID' => $schoolyearID));
					if(!count($this->data['student'])) {
						$this->data['student'] = $this->student_m->general_get_single_student(array('studentID' => $lmember->studentID));
						if(count($this->data['student'])) {
							$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $lmember->studentID, 'srschoolyearID' => $this->data['student']->schoolyearID));
						}
					}

					if(count($this->data['student'])) {
						$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['student']->usertypeID));
						$this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID' => $this->data['student']->srclassesID));
						$this->data['section'] = $this->section_m->general_get_single_section(array('sectionID' => $this->data['student']->srsectionID));
						$usertypeID = $this->session->userdata('usertypeID');
						$userID = $this->session->userdata('loginuserID');
						if($usertypeID == 3) {
							if($this->data['student']->studentID == $userID) {
								if($this->data['book']->return_date == NULL) {
									$this->data["subview"] = "issue/view";
									$this->load->view('_layout_main', $this->data);
								} else {
									$this->data["subview"] = "error";
									$this->load->view('_layout_main', $this->data);
								}
							} else {
								$this->data["subview"] = "error";
								$this->load->view('_layout_main', $this->data);
							}
						} elseif($usertypeID == 4) {
							if($this->data['student']->parentID == $userID) {
								if($this->data['book']->return_date == NULL) {
									$this->data["subview"] = "issue/view";
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
							if($this->data['book']->return_date == NULL) {
								$this->data["subview"] = "issue/view";
								$this->load->view('_layout_main', $this->data);
							} else {
								$this->data["subview"] = "error";
								$this->load->view('_layout_main', $this->data);
							}
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

	public function print_preview() {
		if(permissionChecker('issue_view')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['book'] = $this->issue_m->get_issue_with_books(array('issueID' => $id), TRUE);
				if($this->data['book']) {
					$lmember = $this->lmember_m->get_single_lmember(array('lID' => $this->data['book']->lID));
					if(count($lmember)) {
						$this->data['lmember'] = $lmember;
						$schoolyearID = $this->session->userdata('defaultschoolyearID');
						$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $lmember->studentID, 'srschoolyearID' => $schoolyearID));
						if(!count($this->data['student'])) {
							$this->data['student'] = $this->student_m->general_get_single_student(array('studentID' => $lmember->studentID));
							if(count($this->data['student'])) {
								$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $lmember->studentID, 'srschoolyearID' => $this->data['student']->schoolyearID));
							}
						}

						if(count($this->data['student'])) {
							$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['student']->usertypeID));
							$this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID' => $this->data['student']->srclassesID));
							$this->data['section'] = $this->section_m->general_get_single_section(array('sectionID' => $this->data['student']->srsectionID));

							$usertypeID = $this->session->userdata('usertypeID');
							$userID = $this->session->userdata('loginuserID');
							if($usertypeID == 3) {
								if($this->data['student']->studentID == $userID) {
									if($this->data['book']->return_date == NULL) {
										$this->reportPDF('issuemodule.css',$this->data, 'issue/print_preview');
									} else {
										$this->data["subview"] = "error";
										$this->load->view('_layout_main', $this->data);
									}
								} else {
									$this->data["subview"] = "error";
									$this->load->view('_layout_main', $this->data);
								}
							} elseif($usertypeID == 4) {
								if($this->data['student']->parentID == $userID) {
									if($this->data['book']->return_date == NULL) {
										$this->reportPDF('issuemodule.css',$this->data, 'issue/print_preview');
									} else {
										$this->data["subview"] = "error";
										$this->load->view('_layout_main', $this->data);
									}
								} else {
									$this->data["subview"] = "error";
									$this->load->view('_layout_main', $this->data);
								}
							} else {
								if($this->data['book']->return_date == NULL) {
									$this->reportPDF('issuemodule.css',$this->data, 'issue/print_preview');
								} else {
									$this->data["subview"] = "error";
									$this->load->view('_layout_main', $this->data);
								}
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

	public function send_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('issue_view')) {
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
						$this->data['book'] = $this->issue_m->get_issue_with_books(array('issueID' => $id), TRUE);
						if($this->data['book']) {
							$lmember = $this->lmember_m->get_single_lmember(array('lID' => $this->data['book']->lID));
							if(count($lmember)) {
								$this->data['lmember'] = $lmember;

								$schoolyearID = $this->session->userdata('defaultschoolyearID');
								$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $lmember->studentID, 'srschoolyearID' => $schoolyearID));
								if(!count($this->data['student'])) {
									$this->data['student'] = $this->student_m->general_get_single_student(array('studentID' => $lmember->studentID));
									if(count($this->data['student'])) {
										$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $lmember->studentID, 'srschoolyearID' => $this->data['student']->schoolyearID));
									}
								}

								if(count($this->data['student'])) {
									$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['student']->usertypeID));
									$this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID' => $this->data['student']->srclassesID));
									$this->data['section'] = $this->section_m->general_get_single_section(array('sectionID' => $this->data['student']->srsectionID));
									if($this->data['book']->return_date == NULL) {
										$email = $this->input->post('to');
										$subject = $this->input->post('subject');
										$message = $this->input->post('message');
										$this->reportSendToMail('issuemodule.css',$this->data, 'issue/print_preview', $email, $subject, $message);
										$retArray['message'] = "Message";
										$retArray['status'] = TRUE;
										echo json_encode($retArray);
									    exit;
									} else {
										$retArray['message'] = $this->lang->line('issue_data_not_found');
										echo json_encode($retArray);
										exit;
									}
								} else {
									$retArray['message'] = $this->lang->line('issue_data_not_found');
									echo json_encode($retArray);
									exit;
								}
							} else {
								$retArray['message'] = $this->lang->line('issue_data_not_found');
								echo json_encode($retArray);
								exit;
							}
						} else {
							$retArray['message'] = $this->lang->line('issue_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('issue_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('issue_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('issue_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function returnbook() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$lID = htmlentities(escapeString($this->uri->segment(4)));
		$condition = $this->input->get('return_condition', TRUE);
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			if(permissionChecker('issue_add') && permissionChecker('issue_edit')) {
				if((int)$id && $lID) {
					$date = date("Y-m-d");
					$issue = $this->issue_m->get_issue($id);
					if(count($issue)) {
						$dbGet_bookID = $issue->bookID;
						$book = $this->book_m->get_book($dbGet_bookID);
						$due_quantity = ($book->due_quantity-1);
						$this->book_m->update_book(array("due_quantity" => $due_quantity), $dbGet_bookID);
						$this->issue_m->update_issue(array("return_date" => $date, "return_condition" => $condition), $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						if($this->session->userdata('usertypeID') == 4) {
							$lmember = $this->lmember_m->get_single_lmember(array('lID' => $issue->lID));
							redirect(base_url("issue/index/$lmember->studentID"));
						} else {
							redirect(base_url("issue/index/".urlencode($lID)));
						}
					} else {
						redirect(base_url("issue/index/".urlencode($lID)));
					}
				} else {
					redirect(base_url("issue/index/".urlencode($lID)));
				}
			} else {
				redirect(base_url("issue/index/".urlencode($lID)));
			}
		} else {
			redirect(base_url("issue/index/".urlencode($lID)));
		}
	}

	public function unique_quantity() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$bookID = $this->input->post("book");
		$author = $this->input->post("author");
		if($id) {
			if((int)$bookID) {
				$bookandauthor = $this->issue_m->get_single_issue(array("bookID" => $bookID, "issueID" => $id));
				if(count($bookandauthor)) {
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_quantity", "%s are not available.");
				return FALSE;
			}
		} else {
			if((int)$bookID) {
				$bookandauthor = $this->book_m->get_single_book(array("bookID" => $bookID));
				if(count($bookandauthor)) {
					if($bookandauthor->due_quantity >= $bookandauthor->quantity) {
						$this->form_validation->set_message("unique_quantity", "%s are not available.");
						return FALSE;
					}
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_quantity", "%s are not available.");
				return FALSE;
			}
		}	
	}

	public function unique_lID() {
		$lID = $this->lmember_m->get_single_lmember(array("lID" => $this->input->post("lid")));
		if(!count($lID)) {
			$this->form_validation->set_message("unique_lID", "%s  is wrong.");
			return FALSE;	
		}
		return TRUE;
	}

	public function unique_book() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if($id) {
			$book = $this->issue_m->get_single_issue(array("bookID" => $this->input->post("book"), "return_date" => NULL, "issueID" => $id, 'serial_no' => $this->input->post("serial_no")));
			if(count($book)) {
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_book", "%s already issue.");
				return FALSE;
			}
		} else {
			$book = $this->issue_m->get_single_issue(array("bookID" => $this->input->post("book"), "return_date" => NULL, "lID" => $this->input->post("lid"), 'serial_no' => $this->input->post("serial_no")));
			if(count($book)) {
				$this->form_validation->set_message("unique_book", "%s already issue.");
				return FALSE;
			}
			return TRUE;
		}
	}

	public function unique_book_call() {
		if($this->input->post('book') === '0') {
			$this->form_validation->set_message("unique_book_call", "The %s field is required.");
	     	return FALSE;
		}
		return TRUE;
	}

	public function wrong_date() {
		$due_date = strtotime(date("Y-m-d", strtotime($this->input->post("due_date"))));
		$date = strtotime(date("Y-m-d"));
		if($due_date < $date) {
			$this->form_validation->set_message("wrong_date", "%s is smaller of present date");
	     	return FALSE;
		} else {
			return TRUE;
		}
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

	public function bookIDcall() {
		$bookID = $this->input->post('bookID');
		if($bookID) {
			$bookinfo = $this->book_m->get_book($bookID);
			$author = $bookinfo->author;
			$subject_code = $bookinfo->subject_code;
			$json = array("author" => $author, "subject_code" => $subject_code);
			header("Content-Type: application/json", true);
			echo json_encode($json);
			exit;
		}
	}

	public function match_bookauthor() {
		$bookID = $this->input->post("book");
		$author = $this->input->post("author");

		if((int)$bookID && $bookID != "") {
			$bookandauthor = $this->book_m->get_single_book(array("bookID" => $bookID));
			if($bookandauthor) {
				if($bookandauthor->author == $author) {
					return TRUE;
				} else {
					$this->form_validation->set_message("match_bookauthor", "%s author dose not match.");
					return FALSE;
				}
			} else {
				$this->form_validation->set_message("match_bookauthor", "%s author dose not match.");
				return FALSE;
			}
		} else {
			$this->form_validation->set_message("match_bookauthor", "%s author dose not match.");
			return FALSE;
		}
	}

	public function valid_number () {
		if($this->input->post('fine') && $this->input->post('fine') < 0) {
			$this->form_validation->set_message("valid_number", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}

	public function student_list() {
		$studentID = $this->input->post('id');
		if((int)$studentID) {
			$string = base_url("issue/index/$studentID");
			echo $string;
		} else {
			redirect(base_url("issue/index"));
		}
	}

	public function add_invoice() {
		$libraryID = $this->input->post('libraryID');
		$amount = $this->input->post('amount');
		if(permissionChecker('issue_add') && permissionChecker('issue_edit')) {
			if($libraryID && $amount) {
				$librarymember = $this->issue_m->get_student_by_libraryID_with_studenallinfo($libraryID);

				if(count($librarymember)) {
					$feetype = $this->feetypes_m->get_single_feetypes(array('feetypes' => $this->lang->line('issue_bookfine')));

					if(!count($feetype)) {
						$this->feetypes_m->insert_feetypes(array('feetypes' => $this->lang->line('issue_bookfine'), 'note' => "Don't delete it!"));
					}

					$feetype = $this->feetypes_m->get_single_feetypes(array('feetypes' => $this->lang->line('issue_bookfine')));
					
					$invoiceMainArray = array(
                 		'maininvoiceschoolyearID' => $this->data['siteinfos']->school_year,
                 		'maininvoiceclassesID' => $librarymember->classesID,
                 		'maininvoicestudentID' => $librarymember->studentID,
                 		'maininvoicestatus' => 0,
                 		'maininvoiceuserID' => $this->session->userdata('loginuserID'),
                 		'maininvoiceusertypeID' => $this->session->userdata('usertypeID'),
                 		'maininvoiceuname' => $this->session->userdata('name'), 
                 		'maininvoicedate' => date('Y-m-d'),
                 		'maininvoicecreate_date' => date('Y-m-d'),
                 		'maininvoiceday' => date('d'),
                 		'maininvoicemonth' => date('m'),
                 		'maininvoiceyear' => date('Y'),
                 		'maininvoicedeleted_at' => 1
             		);

             		$this->maininvoice_m->insert_maininvoice($invoiceMainArray);
             		$maininvoiceID = $this->db->insert_id();

					$invoiceArray = array(
						'schoolyearID' => $this->data['siteinfos']->school_year,
						'classesID' => $librarymember->classesID,
						'studentID' => $librarymember->studentID,
						'feetypeID' => (count($feetype) ? $feetype->feetypesID : 0),
						'feetype' => (count($feetype) ? $feetype->feetypes : $this->lang->line('issue_bookfine')),
						'amount' => $amount,
						'discount' => 0,
						'paidstatus' => 0,
						'userID' => $this->session->userdata('loginuserID'),
						'usertypeID' => $this->session->userdata('usertypeID'),
						'uname' => $this->session->userdata('name'),
						'date' => date('Y-m-d'),
						'create_date' => date('Y-m-d'),
						'day' => date('d'),
						'month' => date('m'),
						'year' => date('Y'),
						'deleted_at' => 1,
						'maininvoiceID' => $maininvoiceID
					);

					$this->invoice_m->insert_invoice($invoiceArray);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					if($this->session->userdata('usertypeID') == 4) {
						echo base_url("issue/index/".$librarymember->studentID);
					} else {
						echo base_url("issue/index/".$libraryID);
					}
				} else {
					echo base_url("issue/index/".$libraryID);
				}
			} else {
				echo base_url("issue/index/".$libraryID);
			}
		} else {
			echo base_url("issue/index/".$libraryID);
		}
	}
}