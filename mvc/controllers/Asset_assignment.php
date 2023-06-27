<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset_assignment extends Admin_Controller {
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
        $this->load->model("user_m");
        $this->load->model("asset_m");
        $this->load->model("location_m");
        $this->load->model("purchase_m");
        $this->load->model("systemadmin_m");
        $this->load->model("teacher_m");
        $this->load->model("student_m");
        $this->load->model("parents_m");
        $this->load->model('classes_m');
        $this->load->model("asset_assignment_m");

        $language = $this->session->userdata('lang');
        $this->lang->load('asset_assignment', $language);
    }

    public function index() {
        $this->data['asset_assignments'] = $this->asset_assignment_m->get_asset_assignment_with_userypeID();
        foreach ($this->data['asset_assignments'] as $key => $assignment) {
            $query = $this->userTableCall($assignment->usertypeID, $assignment->check_out_to);
            $this->data['asset_assignments'][$key] = (object) array_merge( (array)$assignment, array( 'assigned_to' => $query));
        }
        $this->data["subview"] = "asset_assignment/index";
        $this->load->view('_layout_main', $this->data);
    }

    private function userTableCall($usertypeID, $userID) {
        $this->load->model('systemadmin_m');
        $this->load->model('teacher_m');
        $this->load->model('student_m');
        $this->load->model('parents_m');
        $this->load->model('user_m');

        $findUserName = '';
        if($usertypeID == 1) {
            $user = $this->db->get_where('systemadmin', array("usertypeID" => $usertypeID, 'systemadminID' => $userID));
            $alluserdata = $user->row();
            if(count($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } elseif($usertypeID == 2) {
            $user = $this->db->get_where('teacher', array("usertypeID" => $usertypeID, 'teacherID' => $userID));
            $alluserdata = $user->row();
            if(count($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } elseif($usertypeID == 3) {
            $user = $this->db->get_where('student', array("usertypeID" => $usertypeID, 'studentID' => $userID));
            $alluserdata = $user->row();
            if(count($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } elseif($usertypeID == 4) {
            $user = $this->db->get_where('parents', array("usertypeID" => $usertypeID, 'parentsID' => $userID));
            $alluserdata = $user->row();
            if(count($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } else {
            $user = $this->db->get_where('user', array("usertypeID" => $usertypeID, 'userID' => $userID));
            $alluserdata = $user->row();
            if(count($alluserdata)) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        }
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'assetID',
                'label' => $this->lang->line("asset_assignment_assetID"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_assetID',
            ),
            array(
                'field' => 'assigned_quantity',
                'label' => $this->lang->line("asset_assignment_assigned_quantity"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_valid_quantity'
            ),
            array(
                'field' => 'usertypeID',
                'label' => $this->lang->line("asset_assignment_usertypeID"),
                'rules' => 'trim|numeric|xss_clean|max_length[128]',
            ),
            array(
                'field'  => 'check_out_to',
                'label'  => $this->lang->line("asset_assignment_check_out_to"),
                'rules'  => 'trim|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'due_date',
                'label' => $this->lang->line("asset_assignment_due_date"),
                'rules' => 'trim|xss_clean|max_length[128]|callback_date_valid'
            ),
            array(
                'field' => 'check_out_date',
                'label' => $this->lang->line("asset_assignment_check_out_date"),
                'rules' => 'trim|xss_clean|max_length[128]|callback_date_valid'
            ),
            array(
                'field' => 'check_in_date',
                'label' => $this->lang->line("asset_assignment_check_in_date"),
                'rules' => 'trim|xss_clean|max_length[128]|callback_date_valid'
            ),
            array(
                'field' => 'asset_locationID',
                'label' => $this->lang->line("asset_assignment_location"),
                'rules' => 'trim|xss_clean|max_length[11]'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("asset_assignment_status"),
                'rules' => 'trim|xss_clean|max_length[11]|callback_unique_status'
            ),
            array(
                'field' => 'note',
                'label' => $this->lang->line("asset_assignment_note"),
                'rules' => 'trim|xss_clean'
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
                'field' => 'asset_assignmentID',
                'label' => $this->lang->line("asset_assignment_asset_assignment"),
                'rules' => 'trim|required|max_length[10]|xss_clean'
            )
        );
        return $rules;
    }

    public function add() {
        $this->data['showClass'] = FALSE;
        $this->data['sendClasses'] = array();
        $this->data['checkOutToUesrs'] = array();
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        $this->data['usertypes'] = $this->usertype_m->get_usertype();
        $this->data['locations'] = $this->location_m->get_location();
        $this->data['assets'] = $this->asset_m->get_asset();

        if($_POST) {
            if ($this->input->post('usertypeID')) {
                $this->data['usertypeID'] = $this->input->post('usertypeID');
            }

            if($this->input->post('usertypeID') == 3) {
                $this->data['showClass'] = TRUE;
                $this->data['sendClasses'] = $this->classes_m->get_classes();
                $this->data['checkOutToUesrs'] = $this->allUsersArray($this->input->post('usertypeID'), $this->input->post('classesID'));
            } else {
                $this->data['checkOutToUesrs'] = $this->allUsersArray($this->input->post('usertypeID'));
            }


            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "/asset_assignment/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "assetID" => $this->input->post("assetID"),
                    "assigned_quantity" => $this->input->post("assigned_quantity"),
                    "usertypeID" => $this->input->post("usertypeID"),
                    "check_out_to" => $this->input->post("check_out_to"),
                    "asset_locationID" => $this->input->post("asset_locationID"),
                    "note" => $this->input->post("note"),
                    "status" => $this->input->post("status")
                );
                if ($this->input->post("check_out_to") == 'select') {
                    $array["check_out_to"] = 0;
                }
                if($this->input->post('check_out_date')) {
                    $array["check_out_date"] = date("Y-m-d", strtotime($this->input->post("check_out_date")));
                }
                if($this->input->post('due_date')) {
                    $array["due_date"] 		= date("Y-m-d", strtotime($this->input->post("due_date")));
                }
                if($this->input->post('check_in_date')) {
                    $array["check_in_date"] = date("Y-m-d", strtotime($this->input->post("check_in_date")));
                }
                $array["create_date"] = date("Y-m-d");
                $array["modify_date"] = date("Y-m-d");
                $array["create_userID"] = $this->session->userdata('loginuserID');
                $array["create_usertypeID"] = $this->session->userdata('usertypeID');

                $this->asset_assignment_m->insert_asset_assignment($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("asset_assignment/index"));
            }
        } else {
            $this->data["subview"] = "/asset_assignment/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $this->data['showClass'] = FALSE;
        $this->data['sendClasses'] = array();
        $this->data['checkOutToUesrs'] = array();
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        $this->data['usertypes'] = $this->usertype_m->get_usertype();
        $this->data['locations'] = $this->location_m->get_location();
        $this->data['assets'] = $this->asset_m->get_asset();
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['asset_assignment'] = $this->asset_assignment_m->get_single_asset_assignment(array('asset_assignmentID' => $id));
            if($this->data['asset_assignment']) {
                $usertypeID = $this->data['asset_assignment']->usertypeID;


                if($usertypeID == 3) {
                    $this->data['showClass'] = TRUE;
                    $this->data['sendClasses'] = $this->classes_m->get_classes();
                    $this->data['checkOutToUesrs'] = $this->allUsersArray($usertypeID, $this->input->post('classesID'));
                } else {
                    $this->data['checkOutToUesrs'] = $this->allUsersArray($usertypeID);
                }

                if($_POST) {

                    if($this->input->post('usertypeID') == 3) {
                        $this->data['showClass'] = TRUE;
                        $this->data['sendClasses'] = $this->classes_m->get_classes();
                        $this->data['checkOutToUesrs'] = $this->allUsersArray($this->input->post('usertypeID'), $this->input->post('classesID'));
                    } else {
                        $this->data['checkOutToUesrs'] = $this->allUsersArray($this->input->post('usertypeID'));
                    }

                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/asset_assignment/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "assetID" => $this->input->post("assetID"),
                            "assigned_quantity" => $this->input->post("assigned_quantity"),
                            "usertypeID" => $this->input->post("usertypeID"),
                            "check_out_to" => $this->input->post("check_out_to"),
                            "asset_locationID" => $this->input->post("asset_locationID"),
                            "note" => $this->input->post("note"),
                            "status" => $this->input->post("status")
                        );
                        if ($this->input->post("check_out_to")=='select') {
                            $array["check_out_to"] = 0;
                        }
                        if($this->input->post('check_out_date')) {
                            $array["check_out_date"] = date("Y-m-d", strtotime($this->input->post("check_out_date")));
                        } else {
                            $array["check_out_date"] = Null;
                        }

                        if($this->input->post('due_date')) {
                            $array["due_date"] 		= date("Y-m-d", strtotime($this->input->post("due_date")));
                        } else {
                            $array["due_date"] = Null;
                        }
                        if($this->input->post('check_in_date')) {
                            $array["check_in_date"] = date("Y-m-d", strtotime($this->input->post("check_in_date")));
                        } else {
                            $array["check_in_date"] = Null;
                        }

                        $array["modify_date"] = date("Y-m-d");


                        $this->asset_assignment_m->update_asset_assignment($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("asset_assignment/index"));
                    }
                } else {
                    $this->data["subview"] = "/asset_assignment/edit";
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
            $this->data['asset_assignment'] = $this->asset_assignment_m->get_single_asset_assignment_with_usertypeID(array('asset_assignmentID' => $id));
            $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');

            if(count($this->data['asset_assignment'])) {
                $usertypeID = $this->data['asset_assignment']->usertypeID;

                if($usertypeID == 3) {
                    $student = $this->student_m->get_single_student(array('studentID' => $this->data['asset_assignment']->check_out_to));

                    if(count($student)) {
                        $this->data['user'] = $this->allUsersArrayObject($usertypeID, $student->classesID);
                    } else {
                        $this->data['user'] = array();
                    }
                } else {
                    $this->data['user'] = $this->allUsersArrayObject($usertypeID);
                }

                $this->data["subview"] = "/asset_assignment/view";
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


    public function print_preview() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['asset_assignment'] = $this->asset_assignment_m->get_single_asset_assignment_with_usertypeID(array('asset_assignmentID' => $id));
            $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');

            if(count($this->data['asset_assignment'])) {
                $usertypeID = $this->data['asset_assignment']->usertypeID;

                if($usertypeID == 3) {
                    $student = $this->student_m->get_single_student(array('studentID' => $this->data['asset_assignment']->check_out_to));

                    if(count($student)) {
                        $this->data['user'] = $this->allUsersArrayObject($usertypeID, $student->classesID);
                    } else {
                        $this->data['user'] = array();
                    }
                } else {
                    $this->data['user'] = $this->allUsersArrayObject($usertypeID);
                }

                $this->reportPDF('assetassignmentmodule.css',$this->data, 'asset_assignment/print_preview');
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
        $retArray['status']  = FALSE;
        $retArray['message'] = '';

        if(permissionChecker('asset_assignment_view')) {
            if($_POST) {
                $rules = $this->send_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $email = $this->input->post('to');
                    $subject = $this->input->post('subject');
                    $message = $this->input->post('message');
                    $asset_assignmentID = $this->input->post('asset_assignmentID');

                    if((int)$asset_assignmentID) {
                        $this->data['asset_assignment'] = $this->asset_assignment_m->get_single_asset_assignment_with_usertypeID(array('asset_assignmentID' => $asset_assignmentID));
                        $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');

                        if(count($this->data['asset_assignment'])) {
                            $usertypeID = $this->data['asset_assignment']->usertypeID;

                            if($usertypeID == 3) {
                                $student = $this->student_m->get_single_student(array('studentID' => $this->data['asset_assignment']->check_out_to));
                                if(count($student)) {
                                    $this->data['user'] = $this->allUsersArrayObject($usertypeID, $student->classesID);
                                } else {
                                    $this->data['user'] = array();
                                }
                            } else {
                                $this->data['user'] = $this->allUsersArrayObject($usertypeID);
                            }

                            $this->reportSendToMail('assetassignmentmodule.css', $this->data, 'asset_assignment/print_preview', $email, $subject, $message);
                            $retArray['message'] = "Message";
                            $retArray['status'] = TRUE;
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $retArray['message'] = $this->lang->line('asset_assignment_data_not_found');
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('asset_assignment_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('asset_assignment_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('asset_assignment_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    public function delete() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['asset_assignment'] = $this->asset_assignment_m->get_single_asset_assignment(array('asset_assignmentID' => $id));
            if($this->data['asset_assignment']) {
                $this->asset_assignment_m->delete_asset_assignment($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("asset_assignment/index"));
            } else {
                redirect(base_url("asset_assignment/index"));
            }
        } else {
            redirect(base_url("asset_assignment/index"));
        }
    }

    public function unique_assetID() {
        if($this->input->post('assetID') == 0) {
            $this->form_validation->set_message('unique_assetID', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }

    public function valid_quantity($quantity) {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $assigned_asset = array();
        
        if((int)$id) {
            $assigned_asset = $this->asset_assignment_m->get_asset_assignment($id);
        }

        $assetID = $this->input->post('assetID');

        if($quantity && $assetID) {
            $total_quantity = 0;
            $assigned_quantity = 0;
            $balance_quantity = 0;
            $asset_purchases = $this->purchase_m->get_order_by_purchase(array('assetID' => $assetID));
            $asset_assigned = $this->asset_assignment_m->get_order_by_asset_assignment(array('assetID' => $assetID, 'status' => 1));
            if(count($asset_purchases)) {
                foreach ($asset_purchases as $key => $purchase) {
                    $total_quantity += $purchase->quantity;
                   
                }


                if (count($asset_assigned)) {
                    foreach ($asset_assigned as $key => $assigned) {
                        $assigned_quantity += $assigned->assigned_quantity;
                    }
                    $balance_quantity = $total_quantity-$assigned_quantity;

                } else {
                    $balance_quantity = $total_quantity;
                }


                if (count($assigned_asset)) {
                    if($assigned_asset->assetID == $assetID) {
                        $balance_quantity += $assigned_asset->assigned_quantity;
                    }
                }

                if ($quantity <= $balance_quantity) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message("valid_quantity", "The item is not available this quantity ");
                    return FALSE;
                }

            } else {
                $balance_quantity = $total_quantity;
                $this->form_validation->set_message("valid_quantity", "Please first purchase it.");
                return FALSE;
            }
        }
        return TRUE;
    }

    public function quantity_number() {
        $assetID = $this->input->post('assetID');
        $quantity = 0;
        $assigned_quantity = 0;
        if( (int)$assetID) {
            $asset_purchases = $this->purchase_m->get_order_by_purchase(array('assetID' => $assetID));
            $asset_assigned = $this->asset_assignment_m->get_order_by_asset_assignment(array('assetID' => $assetID, 'status' => 0));
            if(count($asset_purchases)) {
                foreach ($asset_purchases as $key => $purchase) {
                    $quantity += $purchase->quantity;
                }
                if (count($asset_assigned)) {
                    foreach ($asset_assigned as $key => $assigned) {
                        $assigned_quantity += $assigned->assigned_quantity;
                    }
                    echo $quantity-$assigned_quantity;
                } else {
                    echo $quantity;
                }
            } else {
                echo $quantity;
            }
        } else {
            echo $quantity;
        }
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

    public function unique_status() {
        if($this->input->post('status') == 0) {
            $this->form_validation->set_message('unique_status', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }


    function allusers() {
        if($this->input->post('usertypeID') == 0) {
            echo '<option value="0">'.$this->lang->line('asset_assignment_select_user').'</option>';
        } else {
            $usertypeID = $this->input->post('usertypeID');

            if($usertypeID == 1) {
                $systemadmins = $this->systemadmin_m->get_systemadmin();
                if(count($systemadmins)) {
                    echo "<option value='0'>".$this->lang->line('asset_assignment_select_user')."</option>";
                    foreach ($systemadmins as $key => $systemadmin) {
                        echo "<option value='".$systemadmin->systemadminID."'>".$systemadmin->name.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_assignment_select_user').'</option>';
                }
            } elseif($usertypeID == 2) {
                $teachers = $this->teacher_m->get_teacher();
                if(count($teachers)) {
                    echo "<option value='0'>".$this->lang->line('asset_assignment_select_user')."</option>";
                    foreach ($teachers as $key => $teacher) {
                        echo "<option value='".$teacher->teacherID."'>".$teacher->name.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_assignment_select_user').'</option>';
                }
            } elseif($usertypeID == 3) {
                $classes = $this->classes_m->get_classes();
                if(count($classes)) {
                    echo "<option value='0'>".$this->lang->line('asset_assignment_select_class')."</option>";
                    foreach ($classes as $key => $classm) {
                        echo "<option value='".$classm->classesID."'>".$classm->classes.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_assignment_select_class').'</option>';
                }
            } elseif($usertypeID == 4) {
                $parents = $this->parents_m->get_parents();
                if(count($parents)) {
                    echo "<option value='0'>".$this->lang->line('asset_assignment_select_user')."</option>";
                    foreach ($parents as $key => $parent) {
                        echo "<option value='".$parent->parentsID."'>".$parent->name.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_assignment_select_user').'</option>';
                }
            } else {
                $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
                if(count($users)) {
                    echo "<option value='0'>".$this->lang->line('asset_assignment_select_user')."</option>";
                    foreach ($users as $key => $user) {
                        echo "<option value='".$user->userID."'>".$user->name.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('asset_assignment_select_user').'</option>';
                }
            }
        }
    }

    function allstudent() {
        $schoolyearID = $this->data['siteinfos']->school_year;
        $classesID = $this->input->post('classesID');
        if((int)$schoolyearID && (int)$classesID) {
            $students = $this->student_m->get_order_by_student(array('schoolyearID' => $schoolyearID, 'classesID' => $classesID));

            if(count($students)) {
                echo '<option value="0">'.$this->lang->line('asset_assignment_select_user').'</option>';
                foreach ($students as $key => $student) {
                    echo '<option value="'.$student->studentID.'">'.$student->name.'</option>';
                }
            } else {
                echo '<option value="0">'.$this->lang->line('asset_assignment_select_user').'</option>';
            }
        } else {
            echo '<option value="0">'.$this->lang->line('asset_assignment_select_user').'</option>';
        }
    }


    public function allUsersArray($usertypeID, $classesID = 0) {
        $returnArray[0] = $this->lang->line('asset_assignment_select_user');
        if($usertypeID == 1) {
            $systemadmins = $this->systemadmin_m->get_systemadmin();
            if(count($systemadmins)) {
                foreach ($systemadmins as $key => $systemadmin) {
                    $returnArray[$systemadmin->systemadminID] = $systemadmin->name;
                }
            }
        } elseif($usertypeID == 2) {
            $teachers = $this->teacher_m->get_teacher();
            if(count($teachers)) {
                foreach ($teachers as $key => $teacher) {
                    $returnArray[$teacher->teacherID] = $teacher->name;
                }
            }
        } elseif($usertypeID == 3) {
            $students = $this->student_m->get_order_by_student(array('classesID' => $classesID, 'schoolyearID' => $this->data['siteinfos']->school_year));
            if(count($students)) {
                foreach ($students as $key => $student) {
                    $returnArray[$student->studentID] = $student->name;
                }
            }
        } elseif($usertypeID == 4) {
            $parents = $this->parents_m->get_parents();
            if(count($parents)) {
                foreach ($parents as $key => $parent) {
                    $returnArray[$parent->parentsID] = $parent->name;
                }
            }
        } else {
            $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
            if(count($users)) {
                foreach ($users as $key => $user) {
                    $returnArray[$user->userID] = $user->name;
                }
            }
        }

        return $returnArray;
    }

    public function allUsersArrayObject($usertypeID, $classesID = 0) { 
        $returnArray = [];
        if($usertypeID == 1) {
            $systemadmins = $this->systemadmin_m->get_systemadmin();
            if(count($systemadmins)) {
                foreach ($systemadmins as $key => $systemadmin) {
                    $returnArray[$systemadmin->systemadminID] = $systemadmin;
                }
            }
        } elseif($usertypeID == 2) {
            $teachers = $this->teacher_m->get_teacher();
            if(count($teachers)) {
                foreach ($teachers as $key => $teacher) {
                    $returnArray[$teacher->teacherID] = $teacher;
                }
            }
        } elseif($usertypeID == 3) {
            $students = $this->student_m->get_order_by_student(array('classesID' => $classesID, 'schoolyearID' => $this->data['siteinfos']->school_year));
            if(count($students)) {
                foreach ($students as $key => $student) {
                    $returnArray[$student->studentID] = $student;
                }
            }
        } elseif($usertypeID == 4) {
            $parents = $this->parents_m->get_parents();
            if(count($parents)) {
                foreach ($parents as $key => $parent) {
                    $returnArray[$parent->parentsID] = $parent;
                }
            }
        } else {
            $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
            if(count($users)) {
                foreach ($users as $key => $user) {
                    $returnArray[$user->userID] = $user;
                }
            }
        }
        return $returnArray;
    }

}
