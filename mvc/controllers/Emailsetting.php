<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emailsetting extends Admin_Controller {
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
| WEBSITE:          http://iNilabs.net
| -----------------------------------------------------
*/
    function __construct() {
        parent::__construct();
        $this->load->model("emailsetting_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('emailsetting', $language);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'email_engine',
                'label' => $this->lang->line("emailsetting_email_engine"),
                'rules' => 'trim|required|xss_clean|callback_email_engine'
            )
        );

        if($this->input->post('email_engine') == 'smtp') {
            $rules[] = array(
                'field' => 'smtp_username',
                'label' => $this->lang->line("emailsetting_smtp_username"),
                'rules' => 'trim|required|xss_clean|max_length[255]'
            );

            $rules[] = array(
                'field' => 'smtp_password',
                'label' => $this->lang->line("emailsetting_smtp_password"),
                'rules' => 'trim|required|xss_clean|max_length[255]'
            );

            $rules[] = array(
                'field' => 'smtp_server',
                'label' => $this->lang->line("emailsetting_smtp_server"),
                'rules' => 'trim|required|xss_clean|max_length[255]'
            );

            $rules[] = array(
                'field' => 'smtp_port',
                'label' => $this->lang->line("emailsetting_smtp_port"),
                'rules' => 'trim|required|xss_clean|max_length[255]'
            );

            $rules[] = array(
                'field' => 'smtp_security',
                'label' => $this->lang->line("emailsetting_smtp_security"),
                'rules' => 'trim|xss_clean|max_length[255]'
            );
        }


        return $rules;
    }

	public function index() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js'
            )
        );

        $this->data['emailsetting'] = $this->emailsetting_m->get_emailsetting();

        if(count($this->data['emailsetting'])) {
            if($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "emailsetting/index";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $array = array();
                    for($i=0; $i<count($rules); $i++) {
                        if($this->input->post($rules[$i]['field']) == false) {
                            $array[$rules[$i]['field']] = '';
                        } else {
                            $array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
                        }
                    }

                    $this->emailsetting_m->insertorupdate($array);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("emailsetting/index"));
                }
            } else {
                $this->data["subview"] = "emailsetting/index";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function email_engine() {
        $email_engine = $this->input->post('email_engine');
        if($email_engine == 'select') {
            $this->form_validation->set_message('email_engine', 'The %s field is required.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}