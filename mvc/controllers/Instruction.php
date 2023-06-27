<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instruction extends Admin_Controller {
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
        $this->load->model("instruction_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('instruction', $language);
    }

    public function index() {
        $this->data['instructions'] = $this->instruction_m->get_order_by_instruction();
        $this->data["subview"] = "online_exam/instruction/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("instruction_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'content',
                'label' => $this->lang->line("instruction_content"),
                'rules' => 'trim|required|xss_clean'
            )
        );
        return $rules;
    }

    public function send_mail_rules() {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("instruction_to"),
                'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("instruction_subject"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("instruction_message"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'instructionID',
                'label' => $this->lang->line("instruction_instructionID"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
            )
        );
        return $rules;
    }

    public function unique_data($data) {
        if($data != '') {
            if($data == '0') {
                $this->form_validation->set_message('unique_data', 'The %s field is required.');
                return FALSE;
            }
            return TRUE;
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
                $this->data["subview"] = "online_exam/instruction/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "title" => $this->input->post("title"),
                    "content" => $this->input->post("content"),
                );
                $this->instruction_m->insert_instruction($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("instruction/index"));
            }
        } else {
            $this->data["subview"] = "online_exam/instruction/add";
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
            $this->data['instruction'] = $this->instruction_m->get_single_instruction(array('instructionID' => $id));
            if($this->data['instruction']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "online_exam/instruction/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "title" => $this->input->post("title"),
                            "content" => $this->input->post("content")
                        );

                        $this->instruction_m->update_instruction($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("instruction/index"));
                    }
                } else {
                    $this->data["subview"] = "online_exam/instruction/edit";
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

    public function view() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['instruction'] = $this->instruction_m->get_single_instruction(array('instructionID' => $id));
            if($this->data['instruction']) {
                $this->data["subview"] = "online_exam/instruction/view";
                $this->load->view('_layout_main', $this->data);
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
            $this->data['instruction'] = $this->instruction_m->get_single_instruction(array('instructionID' => $id));
            if($this->data['instruction']) {
                $this->instruction_m->delete_instruction($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("instruction/index"));
            } else {
                redirect(base_url("instruction/index"));
            }
        } else {
            redirect(base_url("instruction/index"));
        }
    }

    public function print_preview() {
        if(permissionChecker('instruction_view')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $this->data['instruction'] = $this->instruction_m->get_single_instruction(array('instructionID' => $id));
                if(count($this->data['instruction'])) {
                    $this->data['panel_title'] = $this->lang->line('panel_title');
                    $this->reportPDF('instructionmodule.css',$this->data, 'online_exam/instruction/print_preview');
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

    public function send_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('instruction_view')) {
            if($_POST) {
                $rules = $this->send_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $id = $this->input->post('instructionID');
                    if ((int)$id) {
                        $this->data['instruction'] = $this->instruction_m->get_single_instruction(array('instructionID' => $id));
                        if(count($this->data['instruction'])) {
                            $email = $this->input->post('to');
                            $subject = $this->input->post('subject');
                            $message = $this->input->post('message');

                            $this->reportSendToMail('instructionmodule.css',$this->data['instruction'], 'online_exam/instruction/print_preview', $email, $subject, $message);
                            $retArray['message'] = "Message";
                            $retArray['status'] = TRUE;
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $retArray['message'] = $this->lang->line('instruction_data_not_found');
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('instruction_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('instruction_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('instruction_permission');
            echo json_encode($retArray);
            exit;
        }
    }
}
