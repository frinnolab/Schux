<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice extends Admin_Controller {
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
		$this->load->model("notice_m");
		$this->load->model("alert_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('notice', $language);
	}

	public function index() {
		$schoolyearID = $this->session->userdata("defaultschoolyearID");
		$this->data['notices'] = $this->notice_m->get_order_by_notice(array('schoolyearID' => $schoolyearID));
		$this->data["subview"] = "notice/index";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
				 array(
					'field' => 'title',
					'label' => $this->lang->line("notice_title"),
					'rules' => 'trim|required|xss_clean|max_length[128]'
				),
				array(
					'field' => 'date',
					'label' => $this->lang->line("notice_date"),
					'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid'
				),
				array(
					'field' => 'notice',
					'label' => $this->lang->line("notice_notice"),
					'rules' => 'trim|required|xss_clean'
				)
			);
		return $rules;
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("notice_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("notice_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("notice_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'noticeID',
				'label' => $this->lang->line("notice_noticeID"),
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

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/datepicker/datepicker.js'
				)
			);
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data['form_validation'] = validation_errors();
					$this->data["subview"] = "notice/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$array = array(
						"title" => $this->input->post("title"),
						"notice" => $this->input->post("notice"),
						'schoolyearID' =>  $this->session->userdata('defaultschoolyearID'),
						"date" => date("Y-m-d", strtotime($this->input->post("date"))),
						"create_date" => date("Y-m-d H:i:s"),
						"create_userID" => $this->session->userdata('loginuserID'),
						"create_usertypeID" => $this->session->userdata('usertypeID')
					);
					$this->notice_m->insert_notice($array);

					$noticeID = $this->db->insert_id();
					if(!empty($noticeID)) {
						$this->alert_m->insert_alert(array('itemID' => $noticeID, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'notice'));
					}

					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("notice/index"));
				}
			} else {
				$this->data["subview"] = "notice/add";
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
					'assets/datepicker/datepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/datepicker/datepicker.js'
				)
			);
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
				if($this->data['notice']) {
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "notice/edit";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array = array(
								"title" => $this->input->post("title"),
								"notice" => $this->input->post("notice"),
								"date" => date("Y-m-d", strtotime($this->input->post("date")))
							);

							$this->notice_m->update_notice($array, $id);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("notice/index"));
						}
					} else {
						$this->data["subview"] = "notice/edit";
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
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
			if($this->data['notice']) {

				$alert = $this->alert_m->get_single_alert(array('itemID' => $id, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'notice'));
				if(!count($alert)) {
					$array = array(
						"itemID" => $id,
						"userID" => $this->session->userdata("loginuserID"),
						"usertypeID" => $this->session->userdata("usertypeID"),
						"itemname" => 'notice',
					);
					$this->alert_m->insert_alert($array);
				}

				$this->data["subview"] = "notice/view";
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

	public function delete() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
				if($this->data['notice']) {
					$this->notice_m->delete_notice($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("notice/index"));
				} else {
					redirect(base_url("notice/index"));
				}
			} else {
				redirect(base_url("notice/index"));
			}
		} else {
			redirect(base_url("notice/index"));
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

	public function print_preview() {
		if(permissionChecker('notice_view')) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
				if(count($this->data['notice'])) {
					$userID = $this->data['notice']->create_userID;
					$usertypeID = $this->data['notice']->create_usertypeID;
					$this->data['userName'] = getNameByUsertypeIDAndUserID($usertypeID, $userID);
					$usertype = $this->usertype_m->get_single_usertype(array('usertypeID'=>$usertypeID));
					$this->data['usertype'] = $usertype->usertype;
				    $this->data['panel_title'] = $this->lang->line('panel_title');
					$this->reportPDF('noticemodule.css',$this->data, 'notice/print_preview');
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	public function send_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('notice_view')) {
			if($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$id = $this->input->post('noticeID');
					if ((int)$id) {
						$this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
						if(count($this->data['notice'])) {
							$email = $this->input->post('to');
							$subject = $this->input->post('subject');
							$message = $this->input->post('message');
							$userID = $this->data['notice']->create_userID;
							$usertypeID = $this->data['notice']->create_usertypeID;
							$this->data['userName'] = getNameByUsertypeIDAndUserID($usertypeID, $userID);
							$usertype = $this->usertype_m->get_single_usertype(array('usertypeID'=>$usertypeID));
							$this->data['usertype'] = $usertype->usertype;
							$this->reportSendToMail('noticemodule.css',$this->data['notice'], 'notice/print_preview', $email, $subject, $message);
							$retArray['message'] = "Message";
							$retArray['status'] = TRUE;
							echo json_encode($retArray);
						    exit;
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
				$retArray['message'] = $this->lang->line('notice_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('notice_permission');
			echo json_encode($retArray);
			exit;
		}

	}
}