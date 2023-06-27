<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Markpercentage extends Admin_Controller {
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
    public function __construct()
    {
        parent::__construct();
        $this->load->model("markpercentage_m");
        $this->load->model('Marksettingrelation_m');
        $language = $this->session->userdata('lang');
        $this->lang->load('markpercentage', $language);
    }

    public function index()
    {
        $this->data['markpercentage'] = $this->markpercentage_m->get_markpercentage();
        $this->data["subview"]        = "markpercentage/index";
        $this->load->view('_layout_main', $this->data);

    }

    protected function rules()
    {
        $rules = [
            [
                'field' => 'markpercentagetype',
                'label' => $this->lang->line("markpercentage_markpercentagetype"),
                'rules' => 'trim|required|xss_clean|max_length[100]|callback_unique_markpercentage'
            ],
            [
                'field' => 'percentage',
                'label' => $this->lang->line("markpercentage_percentage"),
                'rules' => 'trim|required|xss_clean|max_length[3]'
            ]
        ];
        return $rules;
    }

    public function add()
    {
        if ( $_POST ) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ( $this->form_validation->run() == false ) {
                $this->data["subview"] = "markpercentage/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = [
                    "markpercentagetype" => $this->input->post("markpercentagetype"),
                    "percentage"         => $this->input->post("percentage"),
                    "create_date"        => date("Y-m-d h:i:s"),
                    "modify_date"        => date("Y-m-d h:i:s"),
                    "create_userID"      => $this->session->userdata('loginuserID'),
                    "create_username"    => $this->session->userdata('username'),
                    "create_usertype"    => $this->session->userdata('usertype')
                ];
                $this->markpercentage_m->insert_markpercentage($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("markpercentage/index"));
            }
        } else {
            $this->data["subview"] = "markpercentage/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ( (int) $id ) {
            $this->data['markpercentage'] = $this->markpercentage_m->get_markpercentage($id);
            if ( $this->data['markpercentage'] ) {
                if ( $_POST ) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ( $this->form_validation->run() == false ) {
                        $this->data["subview"] = "markpercentage/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = [
                            "markpercentagetype" => $this->input->post("markpercentagetype"),
                            "percentage"         => $this->input->post("percentage"),
                            "modify_date"        => date("Y-m-d h:i:s")
                        ];

                        $this->markpercentage_m->update_markpercentage($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("markpercentage/index"));
                    }
                } else {
                    $this->data["subview"] = "markpercentage/edit";
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

    public function delete()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ( (int) $id ) {
            $this->data['markpercentage'] = $this->markpercentage_m->get_markpercentage($id);
            if ( $this->data['markpercentage'] ) {
                if ( $this->data['markpercentage']->markpercentageID != 1 ) {
                    $markSettingRelation = $this->Marksettingrelation_m->get_single_marksettingrelation(['markpercentageID' => $id]);
                    if(!count($markSettingRelation)) {
                        $this->markpercentage_m->delete_markpercentage($id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("markpercentage/index"));
                    } else {
                        $this->session->set_flashdata('error', 'You have used this mark percentage that\'s why you cannot delete the percentage');
                        redirect(base_url("markpercentage/index"));
                    }
                } else {
                    redirect(base_url("markpercentage/index"));
                }
            } else {
                redirect(base_url("markpercentage/index"));
            }
        } else {
            redirect(base_url("markpercentage/index"));
        }
    }

    public function unique_markpercentage()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ( (int) $id ) {
            $markpercentagetype = $this->markpercentage_m->get_order_by_markpercentage([
                "markpercentagetype" => $this->input->post("markpercentagetype"),
                'percentage' => $this->input->post('percentage'),
                'markpercentageID !=' => $id
            ]);
            if ( count($markpercentagetype) ) {
                $this->form_validation->set_message("unique_markpercentage", "%s already exists");
                return false;
            }
            return true;
        } else {
            $markpercentagetype = $this->markpercentage_m->get_order_by_markpercentage([
                "markpercentagetype" => $this->input->post("markpercentagetype"),
                'percentage'         => $this->input->post('percentage')
            ]);
            if ( count($markpercentagetype) ) {
                $this->form_validation->set_message("unique_markpercentage", "%s already exists");
                return false;
            }
            return true;
        }
    }
}