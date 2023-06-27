<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_level extends Admin_Controller {
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
        $this->load->model("question_level_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('question_level', $language);
    }

    public function index() {
        $this->data['question_levels'] = $this->question_level_m->get_order_by_question_level();
        $this->data["subview"] = "question/level/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("question_level_title"),
                'rules' => 'trim|required|xss_clean|max_length[50]'
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
                $this->data["subview"] = "question/level/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "name" => $this->input->post("name"),
                );
                $this->question_level_m->insert_question_level($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("question_level/index"));
            }
        } else {
            $this->data["subview"] = "question/level/add";
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
            $this->data['question_level'] = $this->question_level_m->get_single_question_level(array('questionLevelID' => $id));
            if($this->data['question_level']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "question/level/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "name" => $this->input->post("name")
                        );

                        $this->question_level_m->update_question_level($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("question_level/index"));
                    }
                } else {
                    $this->data["subview"] = "question/level/edit";
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
            $this->data['question_level'] = $this->question_level_m->get_single_question_level(array('questionLevelID' => $id));
            if($this->data['question_level']) {
                $this->question_level_m->delete_question_level($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("question_level/index"));
            } else {
                redirect(base_url("question_level/index"));
            }
        } else {
            redirect(base_url("question_level/index"));
        }
    }
}
