<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_group extends Admin_Controller {
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
        $this->load->model("question_group_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('question_group', $language);
    }

    public function index() {
        $this->data['groups'] = $this->question_group_m->get_order_by_question_group();
        $this->data["subview"] = "question/group/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("question_group_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            )
        );
        return $rules;
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
                $this->data["subview"] = "question/group/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "title" => $this->input->post("title"),
                );
                $this->question_group_m->insert_question_group($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("question_group/index"));
            }
        } else {
            $this->data["subview"] = "question/group/add";
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
            $this->data['group'] = $this->question_group_m->get_single_question_group(array('questionGroupID' => $id));
            if($this->data['group']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "question/group/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "title" => $this->input->post("title")
                        );

                        $this->question_group_m->update_question_group($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("question_group/index"));
                    }
                } else {
                    $this->data["subview"] = "question/group/edit";
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
            $this->data['group'] = $this->question_group_m->get_single_question_group(array('questionGroupID' => $id));
            if($this->data['group']) {
                $this->question_group_m->delete_question_group($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("question_group/index"));
            } else {
                redirect(base_url("question_group/index"));
            }
        } else {
            redirect(base_url("question_group/index"));
        }
    }
}
