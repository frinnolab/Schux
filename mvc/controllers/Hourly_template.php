<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hourly_template extends Admin_Controller {
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
        $this->load->model("hourly_template_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('hourly_template', $language);
    }

    public function index() {
        $this->data['hourly_templates'] = $this->hourly_template_m->get_order_by_hourly_template();
        $this->data["subview"] = "hourly_template/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'hourly_grades',
                'label' => $this->lang->line("hourly_template_hourly_grades"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_hourly_grades'
            ),
            array(
                'field' => 'hourly_rate',
                'label' => $this->lang->line("hourly_template_hourly_rate"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[11]'
            )
        );
        return $rules;
    }

    public function add() {
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = "hourly_template/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "hourly_grades" => $this->input->post("hourly_grades"),
                    'hourly_rate' => $this->input->post('hourly_rate')
                );

                $this->hourly_template_m->insert_hourly_template($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("hourly_template/index"));
            }
        } else {
            $this->data["subview"] = "hourly_template/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['hourly_template'] = $this->hourly_template_m->get_single_hourly_template(array('hourly_templateID' => $id));
            if($this->data['hourly_template']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "hourly_template/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "hourly_grades" => $this->input->post("hourly_grades"),
                            'hourly_rate' => $this->input->post('hourly_rate')
                        );

                        $this->hourly_template_m->update_hourly_template($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("hourly_template/index"));
                    }
                } else {
                    $this->data["subview"] = "hourly_template/edit";
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
            $this->data['hourly_template'] = $this->hourly_template_m->get_single_hourly_template(array('hourly_templateID' => $id));
            if($this->data['hourly_template']) {
                $this->hourly_template_m->delete_hourly_template($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("hourly_template/index"));
            } else {
                redirect(base_url("hourly_template/index"));
            }
        } else {
            redirect(base_url("hourly_template/index"));
        }
    }

    public function unique_hourly_grades() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int) $id) {
            $hourly_grades = $this->hourly_template_m->get_single_hourly_template(array('hourly_grades' => $this->input->post("hourly_grades"), 'hourly_templateID !=' => $id));
            if(count($hourly_grades)) {
                $this->form_validation->set_message("unique_hourly_grades", "%s already exists");
                return FALSE;
            }
            return TRUE;
        } else {
            $hourly_grades = $this->hourly_template_m->get_single_hourly_template(array('hourly_grades' => $this->input->post('hourly_grades')));
            if(count($hourly_grades)) {
                $this->form_validation->set_message("unique_hourly_grades", "%s already exists");
                return FALSE;
            }
            return TRUE;

        }
    }
}
