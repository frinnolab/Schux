<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchase extends Admin_Controller {
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
        $this->load->model("purchase_m");
        $this->load->model("user_m");
        $this->load->model("asset_m");
        $this->load->model("vendor_m");
        $this->load->model("productsupplier_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('purchase', $language);
    }

    public function index() {
        $this->data['unit'] = array(
            1 => $this->lang->line('purchase_unit_kg'), 
            2 => $this->lang->line('purchase_unit_piece'),
            3 => $this->lang->line('purchase_unit_box'),
        );

        $this->data['purchases'] = $this->purchase_m->get_purchase_with_all();
        $this->data["subview"] = "purchase/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'assetID',
                'label' => $this->lang->line("purchase_assetID"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[128]|callback_unique_asset',
            ),
            array(
                'field' => 'vendorID',
                'label' => $this->lang->line("purchase_vendorID"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[11]|callback_unique_vendor',
            ),
            array(
                'field' => 'purchased_by',
                'label' => $this->lang->line("purchased_by"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[11]|callback_unique_purchase_by',
            ),
            array(
                'field' => 'quantity',
                'label' => $this->lang->line("purchase_quantity"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[11]'
            ),
            array(
                'field' => 'unit',
                'label' => $this->lang->line("purchase_unit"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[11]|callback_unique_unit'
            ),
            array(
                'field' => 'purchase_price',
                'label' => $this->lang->line("purchase_price"),
                'rules' => 'trim|required|xss_clean|max_length[11]'
            ),
            array(
                'field' => 'purchase_date',
                'label' => $this->lang->line("purchase_date"),
                'rules' => 'trim|xss_clean|max_length[10]|callback_date_valid'
            ),
            array(
                'field' => 'service_date',
                'label' => $this->lang->line("purchase_service_date"),
                'rules' => 'trim|xss_clean|max_length[10]|callback_date_valid'
            ),
            array(
                'field' => 'expire_date',
                'label' => $this->lang->line("purchase_expire_date"),
                'rules' => 'trim|xss_clean|max_length[10]|callback_date_valid'
            ),
            
        );
        return $rules;
    }

    public function date_valid($date) {
        if($date) {
            if(strlen($date) <10) {
                $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                return FALSE;
            } else {
                $arr = explode("-", $date);
                $dd = $arr[0];
                $mm = $arr[1];
                $yyyy = $arr[2];
                if(checkdate($mm, $dd, $yyyy)) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    public function add() {
        $usertypeID = $this->session->userdata("usertypeID");
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/datepicker/datepicker.js'
            )
        );
        $this->data['users'] = $this->user_m->get_user();
        $this->data['assets'] = $this->asset_m->get_asset();
        $this->data['vendors'] = $this->productsupplier_m->get_productsupplier();
        
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "purchase/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "assetID" => $this->input->post("assetID"),
                    "vendorID" => $this->input->post("vendorID"),
                    "purchased_by" => $this->input->post("purchased_by"),
                    "quantity" => $this->input->post("quantity"),
                    "unit" => $this->input->post("unit"),
                    "purchase_price" => $this->input->post("purchase_price"),
                );

                if($this->input->post('purchased_by')) {
                    $user = $this->user_m->get_user($this->input->post('purchased_by'));
                    $array['usertypeID'] = isset($user->usertypeID) ? $user->usertypeID : 0 ;
                }

                if($this->input->post('purchase_date')) {
                    $array["purchase_date"] 	= date("Y-m-d", strtotime($this->input->post("purchase_date")));
                }

                if($this->input->post('service_date')) {
                    $array["service_date"] 		= date("Y-m-d", strtotime($this->input->post("service_date")));
                }

                if($this->input->post('expire_date')) {
                    $array["expire_date"] 		= date("Y-m-d", strtotime($this->input->post("expire_date")));
                }
                
                $array["create_date"] = date("Y-m-d");
                $array["modify_date"] = date("Y-m-d");
                $array["create_userID"] = $this->session->userdata('loginuserID');
                $array["create_usertypeID"] = $this->session->userdata('usertypeID');

                if ($usertypeID == 1 || $usertypeID == 5) {
                    $array["status"] = 1;
                } else {
                    $array["status"] = 0;
                }
                $this->purchase_m->insert_purchase($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("purchase/index"));
            }
        } else {
            $this->data["subview"] = "purchase/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $usertypeID = $this->session->userdata("usertypeID");
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/datepicker/datepicker.js'
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['purchase'] = $this->purchase_m->get_single_purchase_with_all(array('purchaseID' => $id));
            if($this->data['purchase']) {
                $this->data['users'] = $this->user_m->get_user();
                $this->data['assets'] = $this->asset_m->get_asset();
                $this->data['vendors'] = $this->productsupplier_m->get_productsupplier();

                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/purchase/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "assetID" => $this->input->post("assetID"),
                            "vendorID" => $this->input->post("vendorID"),
                            "purchased_by" => $this->input->post("purchased_by"),
                            "quantity" => $this->input->post("quantity"),
                            "unit" => $this->input->post("unit"),
                            "purchase_price" => $this->input->post("purchase_price"),
                        );

                        if($this->input->post('purchased_by')) {
                            $user = $this->user_m->get_user($this->input->post('purchased_by'));
                            $array['usertypeID'] = isset($user->usertypeID) ? $user->usertypeID : 0 ;
                        }

                        if($this->input->post('purchase_date')) {
                            $array["purchase_date"] 		= date("Y-m-d", strtotime($this->input->post("purchase_date")));
                        } else {
                            $array["purchase_date"] = NULL;
                        }

                        if($this->input->post('service_date')) {
                            $array["service_date"] 		= date("Y-m-d", strtotime($this->input->post("service_date")));
                        } else {
                            $array["service_date"] = NULL;
                        }

                        if($this->input->post('expire_date')) {
                            $array["expire_date"] 		= date("Y-m-d", strtotime($this->input->post("expire_date")));
                        } else {
                            $array["expire_date"] = NULL;
                        }

                        $array["modify_date"] = date("Y-m-d");

                        if ($usertypeID == 1 || $usertypeID == 5) {
                            $array["status"] = 1;
                        } else {
                            $array["status"] = 0;
                        }

                        $this->purchase_m->update_purchase($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("purchase/index"));
                        dd($array);
                    }
                } else {
                    $this->data["subview"] = "/purchase/edit";
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
            $this->data['purchase'] = $this->purchase_m->get_single_purchase(array('purchaseID' => $id));
            if($this->data['purchase']) {
                $this->purchase_m->delete_purchase($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("purchase/index"));
            } else {
                redirect(base_url("purchase/index"));
            }
        } else {
            redirect(base_url("purchase/index"));
        }
    }

    function status() {
        if(permissionChecker('purchase_edit')) {
            $id = $id = htmlentities(escapeString($this->uri->segment(3)));

            if($id != '') {
                if((int)$id) {
                    $purchase = $this->purchase_m->get_purchase($id);
                    if(count($purchase)) {
                        if($purchase->status == 1) {
                            $this->purchase_m->update_purchase(array('status' => 0), $id);
                        } else {
                            $this->purchase_m->update_purchase(array('status' => 1), $id);
                        }
                        redirect(base_url("purchase/index"));
                    } else {
                        redirect(base_url("purchase/index"));
                    }
                } else {
                    redirect(base_url("purchase/index"));
                }
            } else {
                redirect(base_url("purchase/index"));
            }
        } else {
            redirect(base_url("exceptionpage/index"));
        }
    }

    public function unique_asset() {
        if($this->input->post('assetID') == 0) {
            $this->form_validation->set_message('unique_asset', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }

    public function unique_vendor() {
        if($this->input->post('vendorID') == 0) {
            $this->form_validation->set_message('unique_vendor', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }

    public function unique_purchase_by() {
        if($this->input->post('purchased_by') == 0) {
            $this->form_validation->set_message('unique_purchase_by', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }

    public function unique_unit() {
        if($this->input->post('unit') == 0) {
            $this->form_validation->set_message('unique_unit', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }

    
}
