<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_type extends Admin_Controller {
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
        $this->load->model("question_type_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('question_type', $language);
    }

    public function index() {
        $this->data['question_types'] = $this->question_type_m->get_order_by_question_type();
        $this->data["subview"] = "question/type/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("question_type_name"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'typeNumber',
                'label' => $this->lang->line("question_type_number"),
                'rules' => 'trim|required|xss_clean'
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
                $this->data["subview"] = "question/type/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "name" => $this->input->post("name"),
                    "typeNumber" => $this->input->post("typeNumber")
                );
                $this->question_type_m->insert_question_type($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("question_type/index"));
            }
        } else {
            $this->data["subview"] = "question/type/add";
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
            $this->data['question_type'] = $this->question_type_m->get_single_question_type(array('questionTypeID' => $id));
            if($this->data['question_type']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "question/type/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "name" => $this->input->post("name"),
                            "typeNumber" => $this->input->post("typeNumber")
                        );

                        $this->question_type_m->update_question_type($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("question_type/index"));
                    }
                } else {
                    $this->data["subview"] = "question/type/edit";
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
            $this->data['question_type'] = $this->question_type_m->get_single_question_type(array('questionTypeID' => $id));
            if($this->data['question_type']) {
                $this->question_type_m->delete_question_type($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("question_type/index"));
            } else {
                redirect(base_url("question_type/index"));
            }
        } else {
            redirect(base_url("question_type/index"));
        }
    }

}
