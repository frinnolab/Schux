<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Location extends Admin_Controller {
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
        $this->load->model("location_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('location', $language);
    }

    public function index() {
        $this->data['locations'] = $this->location_m->get_order_by_location();
        $this->data["subview"] = "location/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'location',
                'label' => $this->lang->line("location"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("location_description"),
                'rules' => 'trim|xss_clean'
            )
        );
        return $rules;
    }

    public function add() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
            )
        );
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "location/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "location" => $this->input->post("location"),
                    "description" => $this->input->post("description")
                );
                $array["create_date"] = date("Y-m-d");
                $array["modify_date"] = date("Y-m-d");
                $array["create_userID"] = $this->session->userdata('loginuserID');
                $array["create_usertypeID"] = $this->session->userdata('usertypeID');
                $array["active"] = 1;

                $this->location_m->insert_location($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("location/index"));
            }
        } else {
            $this->data["subview"] = "location/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['location'] = $this->location_m->get_single_location(array('locationID' => $id));
            if($this->data['location']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/location/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "location" => $this->input->post("location"),
                            "description" => $this->input->post("description")
                        );
                        $array["modify_date"] = date("Y-m-d");

                        $this->location_m->update_location($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("location/index"));
                    }
                } else {
                    $this->data["subview"] = "/location/edit";
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
            $this->data['location'] = $this->location_m->get_single_location(array('locationID' => $id));
            if($this->data['location']) {
                $this->location_m->delete_location($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("location/index"));
            } else {
                redirect(base_url("location/index"));
            }
        } else {
            redirect(base_url("location/index"));
        }
    }

}
