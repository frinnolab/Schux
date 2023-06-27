<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor extends Admin_Controller {
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
        $this->load->model("vendor_m");
        $this->load->model("student_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('vendor', $language);
    }

    public function index() {
        $this->data['vendors'] = $this->vendor_m->get_order_by_vendor();
        $this->data["subview"] = "/vendor/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("vendor_name"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line("vendor_email"),
                'rules' => 'trim|max_length[40]|valid_email|xss_clean|callback_unique_email'
            ),
            array(
                'field' => 'phone',
                'label' => $this->lang->line("vendor_phone"),
                'rules' => 'trim|xss_clean|max_length[25]'
            ),
            array(
                'field' => 'contact_name',
                'label' => $this->lang->line("vendor_contact_name"),
                'rules' => 'trim|xss_clean|max_length[40]'
            )
        );
        return $rules;
    }

    public function unique_email() {
        if($this->input->post('email')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $vendor = $this->vendor_m->get_single_vendor(array('email' => $this->input->post('email'), 'vendorID !=' => $id));
                if(count($vendor)) {
                    $this->form_validation->set_message("unique_email", "The %s is already exists.");
                    return FALSE;
                }
                return TRUE;
            } else {
                $vendor = $this->vendor_m->get_single_vendor(array('email' => $this->input->post('email')));
                if(count($vendor)) {
                    $this->form_validation->set_message("unique_email", "The %s is already exists.");
                    return FALSE;
                }
                return TRUE;
            }
        }
        return TRUE;
    }

    public function add() {
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
                $this->data["subview"] = "/vendor/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "name" => $this->input->post("name"),
                    "email" => $this->input->post("email"),
                    "phone" => $this->input->post("phone"),
                    "contact_name" => $this->input->post("contact_name"),
                    "date" => date("Y-m-d")
                );
                $this->vendor_m->insert_vendor($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("vendor/index"));
            }
        } else {
            $this->data["subview"] = "/vendor/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
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
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['vendor'] = $this->vendor_m->get_single_vendor(array('vendorID' => $id));
            if($this->data['vendor']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/vendor/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "name" => $this->input->post("name"),
                            "email" => $this->input->post("email"),
                            "phone" => $this->input->post("phone"),
                            "contact_name" => $this->input->post("contact_name"),
                            "date" => date("Y-m-d")
                        );

                        $this->vendor_m->update_vendor($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("vendor/index"));
                    }
                } else {
                    $this->data["subview"] = "/vendor/edit";
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
            $this->data['vendor'] = $this->vendor_m->get_single_vendor(array('vendorID' => $id));
            if($this->data['vendor']) {
                $this->vendor_m->delete_vendor($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("vendor/index"));
            } else {
                redirect(base_url("vendor/index"));
            }
        } else {
            redirect(base_url("vendor/index"));
        }
    }
}
