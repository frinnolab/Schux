<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaveassign extends Admin_Controller {
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
        $this->load->model("leaveassign_m");
        $this->load->model("leavecategory_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('leaveassign', $language);
    }

    public function index() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->data['leaveassign'] = $this->leaveassign_m->get_order_by_leaveassign(array('schoolyearID' => $schoolyearID));
        $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
        $this->data['leavecategorys'] = pluck($this->leavecategory_m->get_leavecategory(), 'leavecategory', 'leavecategoryID');
        $this->data["subview"] = "leaveassign/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'usertypeID',
                'label' => $this->lang->line("leaveassign_usertypeID"),
                'rules' => 'trim|required|xss_clean|max_length[255]|callback_unique_data'
            ),
            array(
                'field' => 'leavecategoryID',
                'label' => $this->lang->line("leaveassign_categoryID"),
                'rules' => 'trim|required|xss_clean|callback_unique_category|callback_unique_data'
            ),
            array(
                'field' => 'leaveassignday',
                'label' => $this->lang->line("leaveassign_number_of_day"),
                'rules' => 'trim|required|xss_clean|is_natural_no_zero'
            ),
        );
        return $rules;
    }

    public function unique_data($data) {
        if($data != "") {
            if($data == "0") {
                $this->form_validation->set_message('unique_data', 'The %s field is required.');
                return FALSE;
            }
            return TRUE;
        } 
        return TRUE;
    }

    public function unique_category() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            if((int)$this->input->post('usertypeID')) {
                $result = $this->leaveassign_m->get_single_leaveassign(["leavecategoryID" => $this->input->post('leavecategoryID'), "usertypeID" => $this->input->post('usertypeID'), 'schoolyearID' => $schoolyearID, "leaveassignID !=" => $id]);
                if(count($result)) {
                    $this->form_validation->set_message("unique_category", "The %s is already assigned!");
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
        } else {
            if((int)$this->input->post('usertypeID')) {
                $result = $this->leaveassign_m->get_single_leaveassign(["leavecategoryID" => $this->input->post('leavecategoryID'), "usertypeID" => $this->input->post('usertypeID'), 'schoolyearID' => $schoolyearID]);
                if(count($result)) {
                    $this->form_validation->set_message("unique_category", "The %s is already assigned!");
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
        }
        return TRUE;
    }

    public function add() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css'
                ),
                'js' => array(
                    'assets/select2/select2.js'
                )
            );
            $this->data['leavecategorys'] = $this->leavecategory_m->get_leavecategory();
            $this->data['usertypes'] = $this->usertype_m->get_order_by_usertype();
            if($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "leaveassign/add";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $array = array(
                        "usertypeID" => $this->input->post("usertypeID"),
                        "leavecategoryID" => $this->input->post("leavecategoryID"),
                        "leaveassignday" => $this->input->post("leaveassignday"),
                        "schoolyearID" => $this->session->userdata('defaultschoolyearID'),
                        "create_date" => date("Y-m-d H:i:s"),
                        "modify_date" => date("Y-m-d H:i:s"),
                        "create_userID" => $this->session->userdata('loginuserID'),
                        "create_usertypeID" => $this->session->userdata('usertypeID')
                    );
                    $this->leaveassign_m->insert_leaveassign($array);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("leaveassign/index"));
                }
            } else {
                $this->data["subview"] = "leaveassign/add";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css'
                ),
                'js' => array(
                    'assets/select2/select2.js'
                )
            );
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->data['leaveassign'] = $this->leaveassign_m->get_single_leaveassign(array('leaveassignID' => $id, 'schoolyearID' => $schoolyearID));

                $this->data['leavecategorys'] = $this->leavecategory_m->get_order_by_leavecategory();
                $this->data['usertypes'] = $this->usertype_m->get_order_by_usertype();
                if(count($this->data['leaveassign'])) {
                    if($_POST) {
                        $rules = $this->rules();
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                            $this->data["subview"] = "/leaveassign/edit";
                            $this->load->view('_layout_main', $this->data);
                        } else {
                            $array = array(
                               "usertypeID" => $this->input->post("usertypeID"),
                                "leavecategoryID" => $this->input->post("leavecategoryID"),
                                "leaveassignday" => $this->input->post("leaveassignday"),
                                "modify_date" => date("Y-m-d H:i:s")
                            );

                            $this->leaveassign_m->update_leaveassign($array, $id);
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("leaveassign/index"));
                        }
                    } else {
                        $this->data["subview"] = "leaveassign/edit";
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

    public function delete() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->data['leaveassign'] = $this->leaveassign_m->get_single_leaveassign(array('leaveassignID' => $id, 'schoolyearID' => $schoolyearID));
                if($this->data['leaveassign']) {
                    $this->leaveassign_m->delete_leaveassign($id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("leaveassign/index"));
                } else {
                    redirect(base_url("leaveassign/index"));
                }
            } else {
                redirect(base_url("leaveassign/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }
}
