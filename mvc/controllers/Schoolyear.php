<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schoolyear extends Admin_Controller {
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
		$this->load->model("schoolyear_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('schoolyear', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'schoolyear', 
				'label' => $this->lang->line("schoolyear_schoolyear"), 
				'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_schoolyear'
			), 
			array(
				'field' => 'schoolyeartitle', 
				'label' => $this->lang->line("schoolyear_schoolyeartitle"),
				'rules' => 'trim|xss_clean|max_length[128]|callback_unique_schoolyeartitle',
			),
			array(
				'field' => 'startingdate', 
				'label' => $this->lang->line("schoolyear_startingdate"), 
				'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid'
			), 
			array(
				'field' => 'endingdate', 
				'label' => $this->lang->line("schoolyear_endingdate"), 
				'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid|callback_unique_endingdate'
			), 
			array(
				'field' => 'semestercode', 
				'label' => $this->lang->line("schoolyear_semestercode"),
				'rules' => 'trim|xss_clean|max_length[11]|numeric'
			)
		);
		return $rules;
	}

	public function index() { 
		$this->data['schoolyears'] = $this->schoolyear_m->get_order_by_schoolyear();
		$this->data["subview"]     = "schoolyear/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function schoolyear_list() {
		$schoolyearID = $this->input->post('schoolyearID');
		if($schoolyearID) {
			$string = base_url("schoolyear/index/$schoolyearID");
			echo $string;
		} else {
			redirect(base_url("schoolyear/index"));
		}
	}

	public function add() {
		
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
			)
		);

		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) { 
				$this->data["subview"] = "schoolyear/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"schooltype" => 'classbase',
					"schoolyear" => $this->input->post("schoolyear"),
					"schoolyeartitle" => $this->input->post("schoolyeartitle"),
					"create_date" => date("Y-m-d h:i:s"),
					"modify_date" => date("Y-m-d h:i:s"),
					"create_userID" => $this->session->userdata('loginuserID'),
					"create_username" => $this->session->userdata('username'),
					"create_usertype" => $this->session->userdata('usertype')
				);

				if($this->input->post('startingdate')) {
					$array["startingdate"] = date("Y-m-d", strtotime($this->input->post("startingdate")));
				}

				if($this->input->post('endingdate')) {
					$array["endingdate"] = date("Y-m-d", strtotime($this->input->post("endingdate")));
				}

				$this->schoolyear_m->insert_schoolyear($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("schoolyear/index"));
			}
		} else {
			$this->data["subview"] = "schoolyear/add";
			$this->load->view('_layout_main', $this->data);
		}	
	}

	public function edit() {

		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
			)
		);

		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['schoolyear'] = $this->schoolyear_m->get_schoolyear($id);
			if($this->data['schoolyear']) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "schoolyear/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$array = array(
							"schoolyear" => $this->input->post("schoolyear"),
							"schoolyeartitle" => $this->input->post("schoolyeartitle"),
							"modify_date" => date("Y-m-d h:i:s")
						);

						if($this->input->post('startingdate')) {
							$array["startingdate"] = date("Y-m-d", strtotime($this->input->post("startingdate")));
						}

						if($this->input->post('endingdate')) {
							$array["endingdate"] = date("Y-m-d", strtotime($this->input->post("endingdate")));
						}

						$this->schoolyear_m->update_schoolyear($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("schoolyear/index"));
					}
				} else {
					$this->data["subview"] = "schoolyear/edit";
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
		if((int)$id) {
			$schoolyear = $this->schoolyear_m->get_schoolyear($id);
			if($schoolyear) {
				if($schoolyear->schoolyearID != 1) {
					$this->schoolyear_m->delete_schoolyear($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("schoolyear/index"));
				} else {
					redirect(base_url("schoolyear/index"));
				}
			} else {
				redirect(base_url("schoolyear/index"));
			}
		} else {
			redirect(base_url("schoolyear/index"));
		}
	}

	public function valid_number() {
		if($this->input->post('semestercode') < 0) {
			$this->form_validation->set_message("valid_number", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_schoolyear() {
		if($this->input->post('schoolyear') && $this->input->post('schoolyeartitle') == '') {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$schoolyear = $this->schoolyear_m->get_schoolyear_where_not($this->input->post("schoolyear"), $id);
				if(count($schoolyear)) {
					$this->form_validation->set_message("unique_schoolyear", "%s already exists");
					return FALSE;
				}
				return TRUE;
			} else {
				$schoolyear = $this->schoolyear_m->get_schoolyear_where($this->input->post('schoolyear'));
				if(count($schoolyear)) {
					$this->form_validation->set_message("unique_schoolyear", "%s already exists");
					return FALSE;
				}
				return TRUE;

			}
		} 
		return TRUE;
	}

	public function unique_schoolyeartitle() {
		if($this->input->post('schoolyeartitle') && $this->input->post('schoolyear')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$schoolyeartitle = $this->schoolyear_m->get_order_by_schoolyear(array("schoolyear" => $this->input->post("schoolyear"), 'schoolyeartitle' => $this->input->post('schoolyeartitle'),
					'schoolyearID !=' => $id ));
				if(count($schoolyeartitle)) {
					$this->form_validation->set_message("unique_schoolyeartitle", "%s already exists");
					return FALSE;
				}
				return TRUE;
			} else {
				$schoolyeartitle = $this->schoolyear_m->get_order_by_schoolyear(array("schoolyear" => $this->input->post("schoolyear"), 'schoolyeartitle' => $this->input->post('schoolyeartitle')));

				if(count($schoolyeartitle)) {
					$this->form_validation->set_message("unique_schoolyeartitle", "%s already exists");
					return FALSE;
				}
				return TRUE;

			}
		}
		return TRUE;
	}

	public function toggleschoolyear() {
		if(permissionChecker('schoolyear')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$this->session->set_userdata(array('defaultschoolyearID' => $id));
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function date_valid($date) {
		if($date) {
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
		}
		return TRUE;
	}

	public function unique_endingdate() {

		$startingdate = strtotime($this->input->post('startingdate'));
		$endingdate   = strtotime($this->input->post('endingdate'));

		if($startingdate && $endingdate) {
			if($startingdate >= $endingdate) {
				$this->form_validation->set_message("unique_endingdate", "%s cannot be less than starting date");
		     	return FALSE;
			}
			return TRUE;
		}
		return TRUE;
		
	}

}

/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/class.php */