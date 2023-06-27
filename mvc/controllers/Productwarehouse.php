<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productwarehouse extends Admin_Controller {
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
        $this->load->model("productwarehouse_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('productwarehouse', $language);
    }

    public function index() {
        $this->data['productwarehouses'] = $this->productwarehouse_m->get_productwarehouse();
        $this->data["subview"] = "productwarehouse/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'productwarehousename',
                'label' => $this->lang->line("productwarehouse_name"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_productwarehousename'
            ),
            array(
                'field' => 'productwarehousecode',
                'label' => $this->lang->line("productwarehouse_code"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_warehousecode'
            ),
            array(
                'field' => 'productwarehouseemail',
                'label' => $this->lang->line("productwarehouse_email"),
                'rules' => 'trim|xss_clean|max_length[40]|valid_email'
            ),array(
                'field' => 'productwarehousephone',
                'label' => $this->lang->line("productwarehouse_phone"),
                'rules' => 'trim|xss_clean|max_length[20]'
            ),
            array(
                'field' => 'productwarehouseaddress',
                'label' => $this->lang->line("productwarehouse_address"),
                'rules' => 'trim|xss_clean|max_length[520]'
            ),
        );
        return $rules;
    }

    public function unique_productwarehousename() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $productwarehouse = $this->productwarehouse_m->get_order_by_productwarehouse(array("productwarehousename" => $this->input->post("productwarehousename"), 'productwarehousecode' => $this->input->post('productwarehousecode'), "productwarehouseID !=" => $id));
            if(count($productwarehouse)) {
                $this->form_validation->set_message("unique_productwarehousename", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $productwarehouse = $this->productwarehouse_m->get_order_by_productwarehouse(array("productwarehousename" => $this->input->post("productwarehousename"), 'productwarehousecode' => $this->input->post('productwarehousecode')));
            if(count($productwarehouse)) {
                $this->form_validation->set_message("unique_productwarehousename", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function add() {
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = "productwarehouse/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "productwarehousename" => $this->input->post("productwarehousename"),
                    "productwarehousecode" => $this->input->post("productwarehousecode"),
                    "productwarehouseemail" => $this->input->post("productwarehouseemail"),
                    "productwarehousephone" => $this->input->post("productwarehousephone"),
                    "productwarehouseaddress" => $this->input->post("productwarehouseaddress"),
                    "create_date" => date('Y-m-d H:i:s'),
                    "modify_date" => date('Y-m-d H:i:s'),
                    "create_userID" => $this->session->userdata("loginuserID"),
                    "create_usertypeID" => $this->session->userdata("usertypeID")
                );
                $this->productwarehouse_m->insert_productwarehouse($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("productwarehouse/index"));
            }
        } else {
            $this->data["subview"] = "productwarehouse/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['productwarehouse'] = $this->productwarehouse_m->get_single_productwarehouse(array('productwarehouseID' => $id));
            if($this->data['productwarehouse']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "productwarehouse/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                         $array = array(
                            "productwarehousename" => $this->input->post("productwarehousename"),
                            "productwarehousecode" => $this->input->post("productwarehousecode"),
                            "productwarehouseemail" => $this->input->post("productwarehouseemail"),
                            "productwarehousephone" => $this->input->post("productwarehousephone"),
                            "productwarehouseaddress" => $this->input->post("productwarehouseaddress"),
                            "modify_date" => date('Y-m-d H:i:s'),
                        );

                        $this->productwarehouse_m->update_productwarehouse($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("productwarehouse/index"));
                    }
                } else {
                    $this->data["subview"] = "/productwarehouse/edit";
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
            $this->data['productwarehouse'] = $this->productwarehouse_m->get_single_productwarehouse(array('productwarehouseID' => $id));
            if($this->data['productwarehouse']) {
                $this->productwarehouse_m->delete_productwarehouse($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("productwarehouse/index"));
            } else {
                redirect(base_url("productwarehouse/index"));
            }
        } else {
            redirect(base_url("productwarehouse/index"));
        }
    }

    public function unique_warehousecode() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $productwarehouse = $this->productwarehouse_m->get_order_by_productwarehouse(array("productwarehousecode" => $this->input->post("productwarehousecode"), "productwarehouseID !=" => $id));
            if(count($productwarehouse)) {
                $this->form_validation->set_message("unique_warehousecode", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $productwarehouse = $this->productwarehouse_m->get_order_by_productwarehouse(array("productwarehousecode" => $this->input->post("productwarehousecode")));

            if(count($productwarehouse)) {
                $this->form_validation->set_message("unique_warehousecode", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }
}
