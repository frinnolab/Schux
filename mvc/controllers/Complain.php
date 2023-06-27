<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Complain extends Admin_Controller {
    /*
    | -----------------------------------------------------
    | PRODUCT NAME:     INILABS SCHOOL MANAGEMENT SYSTEM
    | -----------------------------------------------------
    | AUTHOR:           INILABS TEAM
    | -----------------------------------------------------
    | EMAIL:            info@inilabs.net
    | -----------------------------------------------------
    | COPYRIGHT:        RESERVED BY INILABS IT
    | -----------------------------------------------------
    | WEBSITE:          http://inilabs.net
    | -----------------------------------------------------
    */

    function __construct() {
        parent::__construct();
        $this->load->model("complain_m");
        $this->load->model("systemadmin_m");
        $this->load->model("teacher_m");
        $this->load->model("student_m");
        $this->load->model("studentrelation_m");
        $this->load->model("parents_m");
        $this->load->model("user_m");
        $this->load->model("classes_m");
        $this->load->model("section_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('complain', $language);
    }

    public function index() {
        $usertypeID = $this->session->userdata('usertypeID');
        $userID = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if($usertypeID == 1) {
            $this->data['complains'] = $this->complain_m->get_order_by_complain(array('schoolyearID' => $schoolyearID));
            $this->data["subview"] = "complain/index";
            $this->load->view('_layout_main', $this->data);
        } else {
            $this->data['complains'] = $this->complain_m->get_order_by_complain(array('schoolyearID' => $schoolyearID, 'create_userID' => $userID, 'create_usertypeID' => $usertypeID));
            $this->data["subview"] = "complain/index";
            $this->load->view('_layout_main', $this->data);
        }
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("complain_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'usertypeID',
                'label' => $this->lang->line("complain_usertypeID"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("complain_userID"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("complain_description"),
                'rules' => 'trim|xss_clean|required'
            ),
            array(
                'field' => 'attachment',
                'label' => $this->lang->line("complain_attachment"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_attachUpload'
            )
        );
        return $rules;
    }

    public function send_mail_rules() {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("to"),
                'rules' => 'trim|required|xss_clean'
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
                'field' => 'complainID',
                'label' => $this->lang->line("complain_complainID"),
                'rules' => 'trim|xss_clean|required'
            )
        );
        return $rules;
    }

    public function attachUpload() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $complain = [];
        if((int)$id) {
            $complain = $this->complain_m->get_complain($id);
        }

        $original_file_name = '';
        if($_FILES["attachment"]['name'] != "") {
            $file_name = $_FILES["attachment"]['name'];
            $original_file_name = $file_name;
            $random = random19();
            $makeRandom = hash('sha512', $random.config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/attach";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv|XLS|XLSX|TXT|PPT|CSV";
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
            if(count($complain)) {
                $this->upload_data['file'] = array('file_name' => $complain->attachment);
                $this->upload_data['file']['original_file_name'] = $complain->originalfile;
                return TRUE;
            } else {
                $this->upload_data['file'] = array('file_name' => NULL);
                $this->upload_data['file']['original_file_name'] = NULL;
                return TRUE;
            }
        }
    }

    public function add() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                    'assets/editor/jquery-te-1.4.0.css'
                ),
                'js' => array(
                    'assets/select2/select2.js',
                    'assets/editor/jquery-te-1.4.0.min.js'
                )
            );

            $this->data['classes'] = $this->classes_m->general_get_classes();
            $this->data['usertypes'] = $this->usertype_m->get_usertype();
            $this->data['usertypeID'] = 0;
            
            if($_POST) {
                if($this->input->post('usertypeID')) {
                    $this->data['usertypeID'] = $this->input->post('usertypeID');
                } else {
                    $this->data['usertypeID'] = 0;
                }

                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "complain/add";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $array = array(
                        "title" => $this->input->post("title"),
                        "usertypeID" => $this->input->post("usertypeID"),
                        "userID" => $this->input->post("userID"),
                        "schoolyearID" => $schoolyearID,
                        "description" => $this->input->post("description"),
                        'create_userID' => $this->session->userdata('loginuserID'),
                        'create_usertypeID' => $this->session->userdata('usertypeID'),
                        'create_date' => date("Y-m-d H:i:s"),
                        'modify_date' => date("Y-m-d H:i:s")
                    );

                    $array['attachment'] = $this->upload_data['file']['file_name'];
                    $array['originalfile'] = $this->upload_data['file']['original_file_name'];

                    $this->complain_m->insert_complain($array);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("complain/index"));
                }
            } else {
                $this->data["subview"] = "complain/add";
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
                    'assets/select2/css/select2-bootstrap.css',
                    'assets/editor/jquery-te-1.4.0.css'
                ),
                'js' => array(
                    'assets/select2/select2.js',
                    'assets/editor/jquery-te-1.4.0.min.js'
                )
            );
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $this->data['classes'] = $this->classes_m->general_get_classes();
            $this->data['usertypes'] = $this->usertype_m->get_usertype();
            $this->data['classesID'] = 0;

            if((int)$id) {
                $loginuserID = $this->session->userdata('loginuserID');
                $loginusertypeID = $this->session->userdata('usertypeID');
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                if($loginusertypeID == 1) {
                    $this->data['complain'] = $this->complain_m->get_single_complain(array('complainID' => $id, 'schoolyearID' => $schoolyearID));
                } else {
                    $this->data['complain'] = $this->complain_m->get_single_complain(array('complainID' => $id, 'create_usertypeID' => $loginusertypeID, 'create_userID' => $loginuserID, 'schoolyearID' => $schoolyearID));
                }
                if(count($this->data['complain'])) {
                    if($this->input->post("userID")) {
                        $userID = $this->input->post('userID');
                        $this->data['userID'] = $this->input->post('userID');
                    } else {
                        $userID = $this->data['complain']->userID;
                        $this->data['userID'] = $this->data['complain']->userID;
                    }

                    if($this->input->post('usertypeID')) {
                        $usertypeID = $this->input->post('usertypeID');
                        $this->data['usertypeID'] = $this->input->post('usertypeID');
                    } else {
                        $usertypeID = $this->data['complain']->usertypeID;
                        $this->data['usertypeID'] = $this->data['complain']->usertypeID;                
                    }

                    if($usertypeID != 0) {
                        if($usertypeID == 1) {
                            $this->data['users'] = pluck($this->systemadmin_m->get_systemadmin(), 'name', 'systemadminID');
                        } elseif($usertypeID == 2) {
                            $this->data['users'] = pluck($this->teacher_m->get_teacher(), 'name', 'teacherID');
                        } elseif($usertypeID == 3) {
                            $student = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $this->data['userID'], 'srschoolyearID' => $schoolyearID));
                            if(count($student)) {
                                $this->data['classesID'] = $student->srclassesID;
                            } else {
                                $this->data['classesID'] = 0;
                            }

                            $students = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $this->data['classesID']));
                            $studentArray = [];
                            if(count($students)) {
                                foreach ($students as $student) {
                                    $studentArray[$student->srstudentID] = $student->srname .' - '.$this->lang->line('complain_roll').' - '.$student->srroll; 
                                }
                            }
                            $this->data['users'] = $studentArray;
                        } elseif($usertypeID == 4) {
                            $this->data['users'] = pluck($this->parents_m->get_parents(), 'name', 'parentsID');
                        } else {
                            $this->data['users'] = pluck($this->user_m->get_order_by_user(array('usertypeID' => $usertypeID)), 'name', 'userID');
                        }
                    } else {
                        $this->data['users'] = array();
                    }

                    if($_POST) {
                        $rules = $this->rules();
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                            $this->data['usertypeID'] = $this->input->post('usertypeID');
                            $this->data['userID'] = $this->input->post('userID');
                            $this->data["subview"] = "complain/edit";
                            $this->load->view('_layout_main', $this->data);
                        } else {
                            $array = array(
                                "title" => $this->input->post("title"),
                                "usertypeID" => $this->input->post("usertypeID"),
                                "userID" => $this->input->post("userID"),
                                "description" => $this->input->post("description"),
                                'modify_date' => date("Y-m-d H:i:s")
                            );

                            $array['attachment'] = $this->upload_data['file']['file_name'];
                            $array['originalfile'] = $this->upload_data['file']['original_file_name'];

                            $this->complain_m->update_complain($array, $id);
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("complain/index"));
                        }
                    } else {
                        $this->data["subview"] = "complain/edit";
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

    public function view() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $loginuserID = $this->session->userdata('loginuserID');
        $loginusertypeID = $this->session->userdata('usertypeID');
        if((int)$id) {
            if($loginusertypeID == 1) {
                $this->data['complain'] = $this->complain_m->get_single_complain(array('complainID' => $id, 'schoolyearID' => $schoolyearID));
            } else {
                $this->data['complain'] = $this->complain_m->get_single_complain(array('complainID' => $id, 'schoolyearID' => $schoolyearID, 'create_userID' => $loginuserID, 'create_usertypeID' => $loginusertypeID));
            }
            if(count($this->data['complain'])) {
                $usertypeID = $this->data['complain']->usertypeID;
                $userID     = $this->data['complain']->userID;
                $this->data['createinfo'] = getObjectByUserTypeIDAndUserID($this->data['complain']->create_usertypeID, $this->data['complain']->create_userID, $schoolyearID);
                if($usertypeID > 0 && $userID > 0) {
                    $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                    if((int)$usertypeID) {
                        if($usertypeID == 1) {
                            $this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('systemadminID'=> $userID));
                        } elseif($usertypeID == 2) {
                            $this->data['user'] = $this->teacher_m->get_single_teacher(array('teacherID'=> $userID));
                        } elseif($usertypeID == 3) {
                            $this->data['user'] = $this->studentrelation_m->general_get_single_student(array('srstudentID'=> $userID, 'srschoolyearID' => $schoolyearID));
                            $this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID'=>$this->data['user']->srclassesID));
                            $this->data['section'] = $this->section_m->general_get_single_section(array('sectionID'=>$this->data['user']->srsectionID));
                        } elseif($usertypeID == 4) {
                            $this->data['user'] = $this->parents_m->get_single_parents(array('parentsID'=> $userID));
                        } else {
                            $this->data['user'] = $this->user_m->get_single_user(array('usertypeID' => $usertypeID, 'userID'=> $userID));
                        }
                    } else {
                        $this->data['user'] = [];
                        $this->data['classes'] = [];
                        $this->data['section'] = [];
                    }
                } else {
                    $this->data['user'] = [];
                    $this->data['classes'] = [];
                    $this->data['section'] = [];
                }

                $this->data["subview"] = "complain/view";
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
        if(permissionChecker('complain_view')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $loginuserID = $this->session->userdata('loginuserID');
            $loginusertypeID = $this->session->userdata('usertypeID');
            if((int)$id) {
                if($loginusertypeID == 1) {
                    $this->data['complain'] = $this->complain_m->get_single_complain(array('complainID' => $id, 'schoolyearID' => $schoolyearID));
                } else {
                    $this->data['complain'] = $this->complain_m->get_single_complain(array('complainID' => $id, 'schoolyearID' => $schoolyearID, 'create_userID' => $loginuserID, 'create_usertypeID' => $loginusertypeID));
                }
                if(count($this->data['complain'])) {
                    $usertypeID = $this->data['complain']->usertypeID;
                    $userID     = $this->data['complain']->userID;
                    $this->data['createinfo'] = getObjectByUserTypeIDAndUserID($this->data['complain']->create_usertypeID, $this->data['complain']->create_userID, $schoolyearID);
                    if($usertypeID > 0 && $userID > 0) {
                        $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                        if((int)$usertypeID) {
                            if($usertypeID == 1) {
                                $this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('systemadminID'=> $userID));
                            } elseif($usertypeID == 2) {
                                $this->data['user'] = $this->teacher_m->get_single_teacher(array('teacherID'=> $userID));
                            } elseif($usertypeID == 3) {
                                $this->data['user'] = $this->studentrelation_m->general_get_single_student(array('srstudentID'=> $userID, 'srschoolyearID' => $schoolyearID));
                                $this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID'=>$this->data['user']->srclassesID));
                                $this->data['section'] = $this->section_m->general_get_single_section(array('sectionID'=>$this->data['user']->srsectionID));
                            } elseif($usertypeID == 4) {
                                $this->data['user'] = $this->parents_m->get_single_parents(array('parentsID'=> $userID));
                            } else {
                                $this->data['user'] = $this->user_m->get_single_user(array('usertypeID' => $usertypeID, 'userID'=> $userID));
                            }
                        } else {
                            $this->data['user'] = [];
                            $this->data['classes'] = [];
                            $this->data['section'] = [];
                        }
                    } else {
                        $this->data['user'] = [];
                        $this->data['classes'] = [];
                        $this->data['section'] = [];
                    }

                    $this->reportPDF('complainmodule.css',$this->data,'complain/print_preview');
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
        if(permissionChecker('complain_view')) {
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
                    $id = $this->input->post('complainID');
                    
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');
                    $loginuserID = $this->session->userdata('loginuserID');
                    $loginusertypeID = $this->session->userdata('usertypeID');
                    if((int)$id) {
                        if($loginusertypeID == 1) {
                            $this->data['complain'] = $this->complain_m->get_single_complain(array('complainID' => $id, 'schoolyearID' => $schoolyearID));
                        } else {
                            $this->data['complain'] = $this->complain_m->get_single_complain(array('complainID' => $id, 'schoolyearID' => $schoolyearID, 'create_userID' => $loginuserID, 'create_usertypeID' => $loginusertypeID));
                        }
                        if(count($this->data['complain'])) {
                            $usertypeID = $this->data['complain']->usertypeID;
                            $userID     = $this->data['complain']->userID;
                            $this->data['createinfo'] = getObjectByUserTypeIDAndUserID($this->data['complain']->create_usertypeID, $this->data['complain']->create_userID, $schoolyearID);
                            if($usertypeID > 0 && $userID > 0) {
                                $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                                if((int)$usertypeID) {
                                    if($usertypeID == 1) {
                                        $this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('systemadminID'=> $userID));
                                    } elseif($usertypeID == 2) {
                                        $this->data['user'] = $this->teacher_m->get_single_teacher(array('teacherID'=> $userID));
                                    } elseif($usertypeID == 3) {
                                        $this->data['user'] = $this->studentrelation_m->general_get_single_student(array('srstudentID'=> $userID, 'srschoolyearID' => $schoolyearID));
                                        $this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID'=>$this->data['user']->srclassesID));
                                        $this->data['section'] = $this->section_m->general_get_single_section(array('sectionID'=>$this->data['user']->srsectionID));
                                    } elseif($usertypeID == 4) {
                                        $this->data['user'] = $this->parents_m->get_single_parents(array('parentsID'=> $userID));
                                    } else {
                                        $this->data['user'] = $this->user_m->get_single_user(array('usertypeID' => $usertypeID, 'userID'=> $userID));
                                    }
                                } else {
                                    $this->data['user'] = [];
                                    $this->data['classes'] = [];
                                    $this->data['section'] = [];
                                }
                            } else {
                                $this->data['user'] = [];
                                $this->data['classes'] = [];
                                $this->data['section'] = [];
                            }

                            $this->reportSendToMail('complainmodule.css',$this->data,'complain/print_preview', $email, $subject, $message);
                            $retArray['message'] = "Success";
                            $retArray['status'] = TRUE;
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $retArray['message'] = $this->lang->line('complain_data_not_found');
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('complain_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('complain_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('complain_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    public function delete() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $loginuserID = $this->session->userdata('loginuserID');
            $loginusertypeID = $this->session->userdata('usertypeID');
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            if((int)$id) {
                if($loginusertypeID == 1) {
                    $this->data['complain'] = $this->complain_m->get_single_complain(array('complainID' => $id, 'schoolyearID' => $schoolyearID));
                } else {
                    $this->data['complain'] = $this->complain_m->get_single_complain(array('complainID' => $id, 'create_usertypeID' => $loginusertypeID, 'create_userID' => $loginuserID, 'schoolyearID' => $schoolyearID));
                }
                if(count($this->data['complain'])) {
                    if(config_item('demo') == FALSE) {
                        if($this->data['complain']->attachment) {
                            if(file_exists(FCPATH.'uploads/attach/'.$this->data['complain']->attachment)) {
                                unlink(FCPATH.'uploads/attach/'.$this->data['complain']->attachment);
                            }
                        }
                    }

                    $this->complain_m->delete_complain($id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("complain/index"));
                } else {
                    redirect(base_url("complain/index"));
                }
            } else {
                redirect(base_url("complain/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function allStudent() {
        $classesID = $this->input->post('classes');
        if( (int)$classesID) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $students = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
            if(count($students)) {
                $loginuserID = $this->session->userdata('loginuserID');
                $usertypeID = $this->session->userdata('usertypeID');

                echo '<option value="0">'.$this->lang->line('complain_select_users').'</option>';
                foreach ($students as $key => $student) {
                    if(($student->srstudentID != $loginuserID) && ($usertypeID == 3)) {
                        echo '<option value="'.$student->srstudentID.'">'.$student->srname. ' - '.$this->lang->line('complain_roll').' - '.$student->srroll.'</option>';
                    }
                }
            } else {
                echo '<option value="0">'.$this->lang->line('complain_select_users').'</option>';
            }
        } else {
            echo '<option value="0">'.$this->lang->line('complain_select_users').'</option>';
        }
    }

    public function allusers() {
        $loginuserID = $this->session->userdata('loginuserID');
        $usertypeID = $this->session->userdata('usertypeID');
        if($this->input->post('usertypeID') == '0') {
            echo '<option value="0">'.$this->lang->line('complain_select_users').'</option>';
        } else {
            $usertypeID = $this->input->post('usertypeID');

            if($usertypeID == 1) {
                $systemadmins = $this->systemadmin_m->get_systemadmin();
                if(count($systemadmins)) {
                    echo "<option value='0'>".$this->lang->line('complain_select_users')."</option>";
                    foreach ($systemadmins as $key => $systemadmin) {
                        if(($systemadmin->systemadminID != $loginuserID) && ($usertypeID == 1)) {
                            echo "<option value='".$systemadmin->systemadminID."'>".$systemadmin->name.'</option>';
                        }
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('complain_select_users').'</option>';
                }
            } elseif($usertypeID == 2) {
                $teachers = $this->teacher_m->get_teacher();
                if(count($teachers)) {
                    echo "<option value='0'>".$this->lang->line('complain_select_users')."</option>";
                    foreach ($teachers as $key => $teacher) {
                        if(($teacher->teacherID != $loginuserID) && ($usertypeID == 2)) {
                            echo "<option value='".$teacher->teacherID."'>".$teacher->name.'</option>';
                        }
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('complain_select_users').'</option>';
                }
            } elseif($usertypeID == 3) {
                $classes = $this->classes_m->general_get_classes();
                if(count($classes)) {
                    echo "<option value='0'>".$this->lang->line('complain_select_class')."</option>";
                    foreach ($classes as $key => $classm) {
                        echo "<option value='".$classm->classesID."'>".$classm->classes.'</option>';
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('complain_select_class').'</option>';
                }
            } elseif($usertypeID == 4) {
                $parents = $this->parents_m->get_parents();
                if(count($parents)) {
                    echo "<option value='0'>".$this->lang->line('complain_select_users')."</option>";
                    foreach ($parents as $key => $parent) {
                        if(($parent->parentsID != $loginuserID) && ($usertypeID == 4)) {
                            echo "<option value='".$parent->parentsID."'>".$parent->name.'</option>';
                        }
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('complain_select_users').'</option>';
                }
            } else {
                $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
                if(count($users)) {
                    echo "<option value='0'>".$this->lang->line('complain_select_users')."</option>";
                    foreach ($users as $key => $user) {
                        if(($user->userID != $loginuserID) && ($usertypeID == $user->usertypeID)) {
                            echo "<option value='".$user->userID."'>".$user->name.'</option>';
                        }
                    }
                } else {
                    echo '<option value="0">'.$this->lang->line('complain_select_users').'</option>';
                }
            }
        }
    }

    public function download() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $complain = $this->complain_m->get_single_complain(array('complainID' => $id));
            if(count($complain)) {
                $file = realpath('uploads/attach/'.$complain->attachment);
                $originalname = $complain->originalfile;
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
                    redirect(base_url('complain/index'));
                }
            } else {
                redirect(base_url('complain/index'));
            }
        } else {
            redirect(base_url('complain/index'));
        }
    }
}
