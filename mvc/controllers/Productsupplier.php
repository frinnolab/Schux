<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productsupplier extends Admin_Controller {
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
        $this->load->model("productsupplier_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('productsupplier', $language);
    }

    public function index() {
        $this->data['suppliers'] = $this->productsupplier_m->get_productsupplier();
        $this->data["subview"] = "productsupplier/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'productsuppliercompanyname',
                'label' => $this->lang->line("productsupplier_companyname"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_companyname'
            ),
            array(
                'field' => 'productsuppliername',
                'label' => $this->lang->line("productsupplier_suppliername"),
                'rules' => 'trim|required|xss_clean|max_length[40]'
            ),
            array(
                'field' => 'productsuppliertin',
                'label' => $this->lang->line("productsupplier_tin"),
                'rules' => 'trim|xss_clean|max_length[40]'
            ),
            array(
                'field' => 'productsuppliervrn',
                'label' => $this->lang->line("productsupplier_vrn"),
                'rules' => 'trim|xss_clean|max_length[40]'
            ),
            array(
                'field' => 'productsupplieremail',
                'label' => $this->lang->line("productsupplier_email"),
                'rules' => 'trim|xss_clean|max_length[40]|valid_email'
            ),
            array(
                'field' => 'productsupplierphone',
                'label' => $this->lang->line("productsupplier_phone"),
                'rules' => 'trim|xss_clean|max_length[25]|min_length[5]'
            ),
            array(
                'field' => 'productsupplieralternatephone',
                'label' => $this->lang->line("productsupplier_alternate_phone"),
                'rules' => 'trim|xss_clean|max_length[25]|min_length[5]'
            ),
            array(
                'field' => 'productsupplieraddress',
                'label' => $this->lang->line("productsupplier_address"),
                'rules' => 'trim|xss_clean|max_length[128]'
            )
        );
        return $rules;
    }

    public function add() {
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = "productsupplier/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "productsuppliercompanyname" => $this->input->post("productsuppliercompanyname"),
                    "productsuppliername" => $this->input->post("productsuppliername"),
                    "productsupplieremail" => $this->input->post("productsupplieremail"),
                    "productsupplierphone" => $this->input->post("productsupplierphone"),
                    "productsupplieraddress" => $this->input->post("productsupplieraddress"),
                    "productsuppliertin" => $this->input->post("productsuppliertin"),
                    "productsuppliervrn" => $this->input->post("productsuppliervrn"),
                    "productsupplieralternatephone" => $this->input->post("productsupplieralternatephone"),
                    "create_date" => date("Y-m-d H:i:s"),
                    "modify_date" => date("Y-m-d H:i:s"),
                    "create_userID" => $this->session->userdata('loginuserID'),
                    "create_usertypeID" => $this->session->userdata('usertypeID')
                );

                $this->productsupplier_m->insert_productsupplier($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("productsupplier/index"));
            }
        } else {
            $this->data["subview"] = "productsupplier/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['productsupplier'] = $this->productsupplier_m->get_single_productsupplier(array('productsupplierID' => $id));
            if($this->data['productsupplier']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "productsupplier/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "productsuppliercompanyname" => $this->input->post("productsuppliercompanyname"),
                            "productsuppliername" => $this->input->post("productsuppliername"),
                            "productsupplieremail" => $this->input->post("productsupplieremail"),
                            "productsupplierphone" => $this->input->post("productsupplierphone"),
                            "productsupplieraddress" => $this->input->post("productsupplieraddress"),
                            "productsuppliertin" => $this->input->post("productsuppliertin"),
                            "productsuppliervrn" => $this->input->post("productsuppliervrn"),
                            "productsupplieralternatephone" => $this->input->post("productsupplieralternatephone"),
                            "modify_date" => date("Y-m-d H:i:s"),
                        );

                        $this->productsupplier_m->update_productsupplier($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("productsupplier/index")); 
                    }
                } else {
                    $this->data["subview"] = "productsupplier/edit";
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
            $this->data['productsupplier'] = $this->productsupplier_m->get_single_productsupplier(array('productsupplierID' => $id));
            if($this->data['productsupplier']) {
                $this->productsupplier_m->delete_productsupplier($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("productsupplier/index"));
            } else {
                redirect(base_url("productsupplier/index"));
            }
        } else {
            redirect(base_url("productsupplier/index"));
        }
    }

    public function unique_companyname() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $productsupplier = $this->productsupplier_m->get_order_by_productsupplier(array("productsuppliercompanyname" => $this->input->post("productsuppliercompanyname"), "productsupplierID !=" => $id));
            if(count($productsupplier)) {
                $this->form_validation->set_message("unique_companyname", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $productsupplier = $this->productsupplier_m->get_order_by_productsupplier(array("productsuppliercompanyname" => $this->input->post("productsuppliercompanyname")));
            if(count($productsupplier)) {
                $this->form_validation->set_message("unique_companyname", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }
}
