<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transport extends Admin_Controller {
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
		$this->load->model("transport_m");
		$this->load->model("student_m");
		$this->load->model("tmember_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('transport', $language);	
	}

	public function index() {
		$this->data['transports'] = $this->transport_m->get_order_by_transport();
		$this->data["subview"] = "transport/index";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'transport_owner', 
				'label' => $this->lang->line("transport_owner"), 
				'rules' => 'trim|required|xss_clean|max_length[128]'
			), 
			array(
				'field' => 'transport_owner_phone', 
				'label' => $this->lang->line("transport_owner_phone"), 
				'rules' => 'trim|required|xss_clean|max_length[128]'
			), 
			array(
				'field' => 'transport_owner_alternate_phone', 
				'label' => $this->lang->line("transport_owner_alternate_phone"), 
				'rules' => 'trim|xss_clean|max_length[128]'
			), 
			array(
				'field' => 'transport_driver', 
				'label' => $this->lang->line("transport_driver"), 
				'rules' => 'trim|required|xss_clean|max_length[128]'
			), 
			array(
				'field' => 'transport_driver_phone', 
				'label' => $this->lang->line("transport_driver_phone"), 
				'rules' => 'trim|required|xss_clean|max_length[128]'
			), 
			array(
				'field' => 'transport_driver_alternate_phone', 
				'label' => $this->lang->line("transport_driver_alternate_phone"), 
				'rules' => 'trim|xss_clean|max_length[128]'
			), 
			array(
				'field' => 'vehicle', 
				'label' => $this->lang->line("transport_vehicle"),
				'rules' => 'trim|required|max_length[11]|xss_clean'
			),
			array(
				'field' => 'capacity', 
				'label' => $this->lang->line("capacity"),
				'rules' => 'trim|required|max_length[11]|xss_clean|numeric'
			),
			array(
				'field' => 'note', 
				'label' => $this->lang->line("transport_note"), 
				'rules' => 'trim|max_length[200]|xss_clean'
			)
		);
		return $rules;
	}

	public function add() {
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors(); 
				$this->data["subview"] = "transport/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"transport_owner" => $this->input->post("transport_owner"),
					"transport_owner_phone" => $this->input->post("transport_owner_phone"),
					"transport_owner_alternate_phone" => $this->input->post("transport_owner_alternate_phone"),
					"transport_driver" => $this->input->post("transport_driver"),
					"transport_driver_phone" => $this->input->post("transport_driver_phone"),
					"transport_driver_alternate_phone" => $this->input->post("transport_driver_alternate_phone"),
					"vehicle" => $this->input->post("vehicle"),
					"capacity" => $this->input->post("capacity"),
					"note" => $this->input->post("note")
				);

				$this->transport_m->insert_transport($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("transport/index"));
			}
		} else {
			$this->data["subview"] = "transport/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['transport'] = $this->transport_m->get_transport($id);
			if($this->data['transport']) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "transport/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$array = array(
							"transport_owner" => $this->input->post("transport_owner"),
							"transport_owner_phone" => $this->input->post("transport_owner_phone"),
							"transport_owner_alternate_phone" => $this->input->post("transport_owner_alternate_phone"),
							"transport_driver" => $this->input->post("transport_driver"),
							"transport_driver_phone" => $this->input->post("transport_driver_phone"),
							"transport_driver_alternate_phone" => $this->input->post("transport_driver_alternate_phone"),
							"vehicle" => $this->input->post("vehicle"),
							"capacity" => $this->input->post("capacity"),
							"note" => $this->input->post("note")
						);

						$this->transport_m->update_transport($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("transport/index"));
					}
				} else {
					$this->data["subview"] = "transport/edit";
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
			$lmembers = $this->tmember_m->get_order_by_tmember(array("transportID" => $id));
			foreach ($lmembers as $lmember) {
				$this->student_m->update_student_classes(array("transport" => 0), array("studentID" => $lmember->studentID));
			}
			$this->tmember_m->delete_tmember_tID($id);
			$this->transport_m->delete_transport($id);
			$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			redirect(base_url("transport/index"));
		} else {
			redirect(base_url("transport/index"));
		}
	}

	function unique_route() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$transport = $this->transport_m->get_order_by_transport(array("route" => $this->input->post("route"), "transportID !=" => $id));
			if(count($transport)) {
				$this->form_validation->set_message("unique_route", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$transport = $this->transport_m->get_order_by_transport(array("route" => $this->input->post("route")));

			if(count($transport)) {
				$this->form_validation->set_message("unique_route", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}	
	}

	function valid_number() {
		if($this->input->post('vehicle') && $this->input->post('vehicle') < 0) {
			$this->form_validation->set_message("valid_number", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}

	function valid_number_for_fare() {
		if($this->input->post('fare') && $this->input->post('fare') < 0) {
			$this->form_validation->set_message("valid_number_for_fare", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}
}

/* End of file transport.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/transport.php */