<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holiday extends Admin_Controller {
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
	public function __construct() {
		parent::__construct();
		$this->load->model("holiday_m");
		$this->load->model("alert_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('holiday', $language);
	}

	public function index() {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$this->data['holidays'] = $this->holiday_m->get_order_by_holiday(array('schoolyearID' => $schoolyearID));
		$this->data["subview"] = "holiday/index";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'title',
				'label' => $this->lang->line("holiday_title"),
				'rules' => 'trim|required|xss_clean|max_length[75]|min_length[3]'
			),
			array(
				'field' => 'fdate',
				'label' => $this->lang->line("holiday_fdate"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid'
			),
			array(
				'field' => 'tdate',
				'label' => $this->lang->line("holiday_tdate"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_todate_valid'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("holiday_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),
			array(
				'field' => 'holiday_details',
				'label' => $this->lang->line("holiday_details"),
				'rules' => 'trim|required|xss_clean'
			)
		);
		return $rules;
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("holiday_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("holiday_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("holiday_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'holidayID',
				'label' => $this->lang->line("holiday_holidayID"),
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

	public function photoupload() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$holiday = array();
		if((int)$id) {
			$holiday = $this->holiday_m->get_holiday($id);	
		}

		$new_file = "holiday.png";
		if($_FILES["photo"]['name'] !="") {
			$file_name = $_FILES["photo"]['name'];
			$random = random19();
	    	$makeRandom = hash('sha512', $random.$this->input->post('title') . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png";
				$config['file_name'] = $new_file;
				$config['max_size'] = '1024';
				$config['max_width'] = '3000';
				$config['max_height'] = '3000';
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("photo")) {
					$this->form_validation->set_message("photoupload", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("photoupload", "Invalid file");
	     		return FALSE;
			}
		} else {
			if(count($holiday)) {
				$this->upload_data['file'] = array('file_name' => $holiday->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
			return TRUE;
			}
		}
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/datepicker/datepicker.js',
				)
			);

			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data['form_validation'] = validation_errors();
					$this->data["subview"] = "holiday/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$array['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
					$array["title"] = $this->input->post("title");
					$array["fdate"] = date("Y-m-d", strtotime($this->input->post("fdate")));
					$array["tdate"] = date("Y-m-d", strtotime($this->input->post("tdate")));
					$array["details"] = $this->input->post("holiday_details");
					$array['photo'] = $this->upload_data['file']['file_name'];
					$array['create_date'] = date('Y-m-d H:i:s');
					$array['create_userID'] = $this->session->userdata('loginuserID');
					$array['create_usertypeID'] = $this->session->userdata('usertypeID');

					$this->holiday_m->insert_holiday($array);
					$holidayID = $this->db->insert_id();
					if(!empty($holidayID)) {
						$this->alert_m->insert_alert(array('itemID' => $holidayID, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'holiday'));
					}

					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("holiday/index"));			
				}
			} else {
				$this->data["subview"] = "holiday/add";
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
					'assets/datepicker/datepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/datepicker/datepicker.js',
				)
			);

			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
				if($this->data['holiday']) {
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "holiday/edit";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array = array(
								"title" => $this->input->post("title"),
								"details" => $this->input->post("holiday_details"),
								"fdate" => date("Y-m-d", strtotime($this->input->post("fdate"))),
								"tdate" => date("Y-m-d", strtotime($this->input->post("tdate")))
							);
							$array['photo'] = $this->upload_data['file']['file_name'];
							$this->holiday_m->update_holiday($array,$id);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("holiday/index"));	
						}
					} else {
						$this->data["subview"] = "holiday/edit";
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
			$this->data['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
			if($this->data['holiday']) {

				$alert = $this->alert_m->get_single_alert(array('itemID' => $id, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'holiday'));
				if(!count($alert)) {
					$array = array(
						"itemID" => $id,
						"userID" => $this->session->userdata("loginuserID"),
						"usertypeID" => $this->session->userdata("usertypeID"),
						"itemname" => 'holiday',
					);
					$this->alert_m->insert_alert($array);
				}

				$this->data["subview"] = "holiday/view";
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
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
				if(count($this->data['holiday'])) {
					if(config_item('demo') == FALSE) {
						if($this->data['holiday']->photo != 'holiday.png') {
							if(file_exists(FCPATH.'uploads/images/'.$this->data['holiday']->photo)) {
								unlink(FCPATH.'uploads/images/'.$this->data['holiday']->photo);
							}
						}
					}
					$this->holiday_m->delete_holiday($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("holiday/index"));
				} else {
					redirect(base_url("holiday/index"));
				}
			} else {
				redirect(base_url("holiday/index"));
			}
		} else {
			redirect(base_url("holiday/index"));
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

	public function todate_valid($date) {
		$fdate = $this->input->post('fdate');
		if(strlen($date) < 10) {
			$this->form_validation->set_message("todate_valid", "%s is not valid dd-mm-yyyy");
				return FALSE;
		} else {
			$arr = explode("-", $date);
			$dd = $arr[0];
			$mm = $arr[1];
			$yyyy = $arr[2];
			if(checkdate($mm, $dd, $yyyy)) {
				$fdate = strtotime($fdate); 
				$date = strtotime($date);
				if($fdate>$date) {
					$this->form_validation->set_message("todate_valid", "%s must be greater than From Date");
					return FALSE;
				} else {
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("todate_valid", "%s is not valid dd-mm-yyyy");
				return FALSE;
			}
		}
	}

	public function print_preview() {
		if(permissionChecker('holiday_view')) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->data['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
				if(count($this->data['holiday'])) {
					$userID = $this->data['holiday']->create_userID;
					$usertypeID = $this->data['holiday']->create_usertypeID;
					$this->data['userName'] = getNameByUsertypeIDAndUserID($usertypeID, $userID);
					$usertype = $this->usertype_m->get_single_usertype(array('usertypeID'=>$usertypeID));
					$this->data['usertype'] = $usertype->usertype;
					$this->reportPDF('holidaymodule.css', $this->data, 'holiday/print_preview');
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
		if(permissionChecker('holiday_view')) {
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
					$id = $this->input->post('holidayID');
					if ((int)$id) {
						$this->data['holiday'] = $this->holiday_m->get_single_holiday(array('schoolyearID' => $schoolyearID, 'holidayID' => $id));
						if(count($this->data['holiday'])) {
							$email = $this->input->post('to');
							$subject = $this->input->post('subject');
							$message = $this->input->post('message');
							$userID = $this->data['holiday']->create_userID;
							$usertypeID = $this->data['holiday']->create_usertypeID;
							$this->data['userName'] = getNameByUsertypeIDAndUserID($usertypeID, $userID);
							$usertype = $this->usertype_m->get_single_usertype(array('usertypeID'=>$usertypeID));
							$this->data['usertype'] = $usertype->usertype;
							$this->reportSendToMail('holidaymodule.css',$this->data, 'holiday/print_preview', $email, $subject, $message);
							$retArray['message'] = "Message";
							$retArray['status'] = TRUE;
							echo json_encode($retArray);
						    exit;
						} else {
							$retArray['message'] = $this->lang->line('holiday_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('holiday_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('holiday_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('holiday_permission');
			echo json_encode($retArray);
			exit;
		}
	}
}