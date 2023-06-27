<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productcategory extends Admin_Controller {
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
        $this->load->model("productcategory_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('productcategory', $language);
    }

    public function index() {
        $this->data['productcategorys'] = $this->productcategory_m->get_productcategory();
        $this->data["subview"] = "productcategory/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'productcategoryname',
                'label' => $this->lang->line("productcategory_name"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_productcategoryname'
            ),
            array(
                'field' => 'productcategorydesc',
                'label' => $this->lang->line("productcategory_desc"),
                'rules' => 'trim|xss_clean|max_length[520]'
            )
        );
        return $rules;
    }

    public function add() {
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = "productcategory/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "productcategoryname" => $this->input->post("productcategoryname"),
                    "productcategorydesc" => $this->input->post("productcategorydesc"),
                    "create_date" => date("Y-m-d H:i:s"),
                    "modify_date" => date("Y-m-d H:i:s"),
                    "create_userID" => $this->session->userdata('loginuserID'),
                    "create_usertypeID" => $this->session->userdata('usertypeID')
                );
                $this->productcategory_m->insert_productcategory($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("productcategory/index"));
            }
        } else {
            $this->data["subview"] = "productcategory/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['productcategory'] = $this->productcategory_m->get_single_productcategory(array('productcategoryID' => $id));
            if($this->data['productcategory']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "productcategory/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "productcategoryname" => $this->input->post("productcategoryname"),
                            "productcategorydesc" => $this->input->post("productcategorydesc"),
                            "modify_date" => date("Y-m-d H:i:s"),
                        );

                        $this->productcategory_m->update_productcategory($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("productcategory/index"));
                    }
                } else {
                    $this->data["subview"] = "/productcategory/edit";
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
            $this->data['productcategory'] = $this->productcategory_m->get_single_productcategory(array('productcategoryID' => $id));
            if($this->data['productcategory']) {
                $this->productcategory_m->delete_productcategory($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("productcategory/index"));
            } else {
                redirect(base_url("productcategory/index"));
            }
        } else {
            redirect(base_url("productcategory/index"));
        }
    }

    public function unique_productcategoryname() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $product = $this->productcategory_m->get_order_by_productcategory(array("productcategoryname" => $this->input->post("productcategoryname"), "productcategoryID !=" => $id));
            if(count($product)) {
                $this->form_validation->set_message("unique_productcategoryname", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $product = $this->productcategory_m->get_order_by_productcategory(array("productcategoryname" => $this->input->post("productcategoryname")));
            if(count($product)) {
                $this->form_validation->set_message("unique_productcategoryname", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }
}
