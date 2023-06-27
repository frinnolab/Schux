<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset extends Admin_Controller {
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
        $this->load->model("asset_m");
        $this->load->model("asset_category_m");
        $this->load->model("location_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('asset', $language);
    }

    public function index() {
        $this->data['assets'] = $this->asset_m->get_asset_with_category_and_location();
        $this->data['asset_purchased_quantity'] = $this->asset_m->get_asset_purchased_quantity();
        $this->data['asset_assigned_quantity'] = $this->asset_m->get_asset_assigned_quantitiy();
        $this->data["subview"] = "asset/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'serial',
                'label' => $this->lang->line("asset_serial"),
                'rules' => 'trim|required|xss_clean|max_length[40]|callback_unique_serial'
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("asset_description"),
                'rules' => 'trim|required|xss_clean|max_length[100]'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("asset_status"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[20]|callback_unique_status'
            ),
            array(
                'field' => 'asset_condition',
                'label' => $this->lang->line("asset_condition"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[20]|callback_unique_condition'
            ),
            array(
                'field' => 'asset_categoryID',
                'label' => $this->lang->line("asset_categoryID"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[11]|callback_unique_category'
            ),
            array(
                'field' => 'asset_locationID',
                'label' => $this->lang->line("asset_locationID"),
                'rules' => 'trim|numeric|xss_clean|max_length[11]|callback_unique_loaction'
            ),
            array(
                'field' => 'attachment',
                'label' => $this->lang->line("asset_attachment"),
                'rules' => 'trim|max_length[512]|xss_clean|callback_attachUpload'
            ),
        );
        return $rules;
    }


    public function send_mail_rules() {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("to"),
                'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("subject"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("message"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'assetID',
                'label' => $this->lang->line("asset_asset"),
                'rules' => 'trim|required|max_length[10]|xss_clean'
            )
        );
        return $rules;
    }


    public function attachUpload() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $asset = array();
        if((int)$id) {
            $asset = $this->asset_m->get_asset($id);
        }

        $new_file = "";
        $original_file_name = '';
        if($_FILES["attachment"]['name'] !="") {
            $file_name = $_FILES["attachment"]['name'];
            $original_file_name = $file_name;
            $random = random19();
            $makeRandom = hash('sha512', $random.config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/attach";
                $config['allowed_types'] = "gif|jpg|jpeg|png|docx|pdf";
                $config['file_name'] = $new_file;
                $config['max_size'] = '1024';
                $config['max_width'] = '3000';
                $config['max_height'] = '3000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("attachment")) {
                    $this->form_validation->set_message("attachUpload", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['file'] =  $this->upload->data();
                    $this->upload_data['file']['original_file_name'] = $original_file_name;
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("attachUpload", "Invalid file");
                return FALSE;
            }
        } else {
            if(count($asset)) {
                $this->upload_data['file'] = array('file_name' => $asset->attachment);
                $this->upload_data['file']['original_file_name'] = $asset->originalfile;
                return TRUE;
            } else {
                $this->upload_data['file'] = array('file_name' => $new_file);
                $this->upload_data['file']['original_file_name'] = $original_file_name;
                return TRUE;
            }
        }
    }

    public function add() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
            )
        );
        $this->data['categories'] = $this->asset_category_m->get_asset_category();
        $this->data['locations'] = $this->location_m->get_location();
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = "asset/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "serial" => $this->input->post("serial"),
                    "description" => $this->input->post("description"),
                    "status" => $this->input->post("status"),
                    "asset_condition" => $this->input->post("asset_condition"),
                    "asset_categoryID" => $this->input->post("asset_categoryID"),
                    "asset_locationID" => $this->input->post("asset_locationID"),
                );

                $array['attachment'] = $this->upload_data['file']['file_name'];
                $array['originalfile'] = $this->upload_data['file']['original_file_name'];
                $array["create_date"] = date("Y-m-d");
                $array["modify_date"] = date("Y-m-d");
                $array["create_userID"] = $this->session->userdata('loginuserID');
                $array["create_usertypeID"] = $this->session->userdata('usertypeID');

                $this->asset_m->insert_asset($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("asset/index"));
            }
        } else {
            $this->data["subview"] = "asset/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['asset'] = $this->asset_m->get_single_asset(array('assetID' => $id));

            if($this->data['asset']) {
                $this->data['categories'] = $this->asset_category_m->get_asset_category();
                $this->data['locations'] = $this->location_m->get_location();
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/asset/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "serial" => $this->input->post("serial"),
                            "description" => $this->input->post("description"),
                            "status" => $this->input->post("status"),
                            "asset_condition" => $this->input->post("asset_condition"),
                            "asset_categoryID" => $this->input->post("asset_categoryID"),
                            "asset_locationID" => $this->input->post("asset_locationID"),
                        );
                        $array['attachment'] = $this->upload_data['file']['file_name'];
                        $array['originalfile'] = $this->upload_data['file']['original_file_name'];
                        $array["modify_date"] = date("Y-m-d");

                        $this->asset_m->update_asset($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("asset/index"));
                    }
                } else {
                    $this->data["subview"] = "/asset/edit";
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
            $this->data['asset'] = $this->asset_m->get_single_asset_with_category_and_location(array('asset.assetID' => $id));

            if(count($this->data['asset'])) {
                $this->data["subview"] = "asset/view";
                $this->load->view('_layout_main', $this->data);
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main');
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main');
        }
    }

    public function delete() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['asset'] = $this->asset_m->get_single_asset(array('assetID' => $id));
            if($this->data['asset']) {
                if(config_item('demo') == FALSE) {
                    if($this->data['asset']->attachment) {
                        if(file_exists(FCPATH.'uploads/attach/'.$this->data['asset']->attachment)) {
                            unlink(FCPATH.'uploads/attach/'.$this->data['asset']->attachment);
                        }
                    }
                }
                
                $this->asset_m->delete_asset($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("asset/index"));
            } else {
                redirect(base_url("asset/index"));
            }
        } else {
            redirect(base_url("asset/index"));
        }
    }

    public function print_preview() {
        if(permissionChecker('asset_view')) {
            $assetID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$assetID) {
                $this->data['asset'] = $this->asset_m->get_single_asset_with_category_and_location(array('asset.assetID' => $assetID));

                if(count($this->data['asset'])) {
                    $this->reportPDF('assetmodule.css',$this->data, 'asset/print_preview');
                } else {
                    $this->data["subview"] = "error";
                    $this->load->view('_layout_main');
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main');
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main');
        }
    }


    public function send_mail() {
        $retArray['status']  = FALSE;
        $retArray['message'] = '';

        if(permissionChecker('asset_view')) {
            if($_POST) {
                $rules = $this->send_mail_rules();

                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $assetID = $this->input->post('assetID');
                    if ((int)$assetID) {
                        $this->data['asset'] = $this->asset_m->get_single_asset_with_category_and_location(array('asset.assetID' => $assetID));
                        if(count($this->data["asset"])) {
                            $email = $this->input->post('to');
                            $subject = $this->input->post('subject');
                            $message = $this->input->post('message');
                            $this->reportSendToMail('assetmodule.css', $this->data, 'asset/print_preview', $email, $subject, $message);
                            $retArray['message'] = "Message";
                            $retArray['status'] = TRUE;
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $retArray['message'] = $this->lang->line('asset_data_not_found');
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('asset_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('asset_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('asset_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    public function unique_serial() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $asset = $this->asset_m->get_single_asset(array("serial" => $this->input->post("serial"), "assetID !=" => $id));
            if(count($asset)) {
                $this->form_validation->set_message("unique_serial", "%s already exists");
                return FALSE;
            }
            return TRUE;
        } else {
            $asset = $this->asset_m->get_single_asset(array("serial" => $this->input->post("serial")));

            if(count($asset)) {
                $this->form_validation->set_message("unique_serial", "%s already exists");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function unique_category() {
        if($this->input->post('asset_categoryID') == 0) {
            $this->form_validation->set_message("unique_category", "The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_loaction() {
        if($this->input->post('status') != 2) {
            if($this->input->post('asset_locationID') == 0) {
                $this->form_validation->set_message("unique_loaction", "The %s field is required");
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }

    public function download() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $asset = $this->asset_m->get_single_asset(array('assetID' => $id));
            $file = realpath('uploads/attach/'.$asset->attachment);
            $originalname = $asset->originalfile;

            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($originalname).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;
            } else {
                redirect(base_url('asset/index'));
            }
        } else {
            redirect(base_url('asset/index'));
        }
    }

    public function unique_status() {
        if($this->input->post('status') == 0) {
            $this->form_validation->set_message('unique_status', "The %s field is required.");
            return FALSE;
        }   
        return TRUE;
    }

    public function unique_condition() {
        if($this->input->post('asset_condition') == 0) {
            $this->form_validation->set_message('unique_condition', "The %s field is required.");
            return FALSE;
        }   
        return TRUE;
    }
}
