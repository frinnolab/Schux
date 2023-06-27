<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activitiescategory extends Admin_Controller {
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
		$this->load->model("activitiescategory_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('activitiescategory', $language);
	}

	public function index() {
		$this->data['activitiescategorys'] = $this->activitiescategory_m->get_activitiescategory();
		$this->data["subview"] = "activitiescategory/index";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'title',
				'label' => $this->lang->line("activitiescategory_title"),
				'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_activitiescategory'
			),
            array(
                'field' => 'fa_icon',
                'label' => $this->lang->line("activitiescategory_fa_icon"),
                'rules' => 'trim|required|xss_clean|max_length[60]'
            )
			);
		return $rules;
	}

	public function add() {
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "activitiescategory/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"title" => $this->input->post("title"),
                    "fa_icon" => $this->input->post("fa_icon"),
                    "schoolyearID" => $this->session->userdata('defaultschoolyearID'),
                    "usertypeID" => $this->session->userdata('usertypeID'),
                    "userID" => $this->session->userdata('loginuserID'),
				);
                $array["create_date"] = date("Y-m-d h:i:s");
                $array["modify_date"] = date("Y-m-d h:i:s");

				$this->activitiescategory_m->insert_activitiescategory($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("activitiescategory/index"));
			}
		} else {
			$this->data["subview"] = "activitiescategory/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['activitiescategory'] = $this->activitiescategory_m->get_activitiescategory($id);
			if($this->data['activitiescategory']) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "activitiescategory/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
                        $array = array(
                            "title" => $this->input->post("title"),
                            "fa_icon" => $this->input->post("fa_icon")
                        );
                        $array["modify_date"] = date("Y-m-d h:i:s");

						$this->activitiescategory_m->update_activitiescategory($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("activitiescategory/index"));
					}
				} else {
					$this->data["subview"] = "activitiescategory/edit";
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
			$this->activitiescategory_m->delete_activitiescategory($id);
			$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			redirect(base_url("activitiescategory/index"));
		} else {
			redirect(base_url("activitiescategory/index"));
		}
	}

	public function unique_activitiescategory() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$activitiescategory = $this->activitiescategory_m->get_order_by_activitiescategory(array("title" => $this->input->post("title"), "activitiescategoryID !=" => $id));
			if(count($activitiescategory)) {
				$this->form_validation->set_message("unique_activitiescategory", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$activitiescategory = $this->activitiescategory_m->get_order_by_activitiescategory(array("title" => $this->input->post("title")));
			if(count($activitiescategory)) {
				$this->form_validation->set_message("unique_activitiescategory", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}	
	}
}