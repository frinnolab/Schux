<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Leaveapply extends Admin_Controller
{
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
        $this->load->model("leaveapplication_m");
        $this->load->model("leavecategory_m");
        $this->load->model("leaveassign_m");
        $this->load->model("usertype_m");
        $this->load->model("systemadmin_m");
        $this->load->model("teacher_m");
        $this->load->model("student_m");
        $this->load->model("parents_m");
        $this->load->model("user_m");
        $this->load->model("studentrelation_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('leaveapply', $language);
    }

    public function index() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->data['myleaveapplications'] = $this->leaveapplication_m->get_order_by_leaveapplication(array('schoolyearID' => $schoolyearID, 'create_usertypeID' => $this->session->userdata('usertypeID'), 'create_userID' => $this->session->userdata('loginuserID')));
        $this->data['leavecategorys'] = pluck($this->leavecategory_m->get_leavecategory(), 'leavecategory', 'leavecategoryID');
        $this->data['userName'] = getAllUserObjectWithStudentRelation($schoolyearID);
        
        $this->data["subview"]  = "leaveapply/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function attachmentUpload() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $leaveapplication = [];
        if ((int)$id) {
            $leaveapplication = $this->leaveapplication_m->get_leaveapplication($id);
        }

        $new_file = "";
        $original_file_name = '';
        if ($_FILES["attachment"]['name'] != "") {
            $file_name          = $_FILES["attachment"]['name'];
            $original_file_name = $file_name;
            $random             = random19();
            $makeRandom         = hash('sha512', $random.'leaveapplication'.config_item("encryption_key"));
            $file_name_rename   = $makeRandom;
            $explode            = explode('.', $file_name);
            if (count($explode) >= 2) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name']     = $new_file;
                $config['max_size']      = '1024';
                $config['max_width']     = '3000';
                $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("attachment")) {
                    $this->form_validation->set_message("attachmentUpload", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['file'] = $this->upload->data();
                    $this->upload_data['file']['original_file_name'] = $original_file_name;
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("attachmentUpload", "Invalid file");
                return FALSE;
            }
        } else {
            if (count($leaveapplication)) {
                $this->upload_data['file'] = array('file_name' => $leaveapplication->attachment);
                $this->upload_data['file']['original_file_name'] = $leaveapplication->attachmentorginalname;
                return TRUE;
            } else {
                $this->upload_data['file'] = array('file_name' => $new_file);
                $this->upload_data['file']['original_file_name'] = $original_file_name;
                return TRUE;
            }
        }
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'applicationto_usertypeID',
                'label' => $this->lang->line("leaveapply_role"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_data',
            ),
            array(
                'field' => 'applicationto_userID',
                'label' => $this->lang->line("leaveapply_applicationto"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_data',
            ),
            array(
                'field' => 'leavecategoryID',
                'label' => $this->lang->line("leaveapply_category"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_data',
            ),
            array(
                'field' => 'leave_schedule',
                'label' => $this->lang->line("leaveapply_schedule"),
                'rules' => 'trim|required|xss_clean|callback_date_schedule_valid',
            ),
            array(
                'field' => 'reason',
                'label' => $this->lang->line("leaveapply_reason"),
                'rules' => 'trim|required|xss_clean|max_length[10000]',
            ),
            array(
                'field' => 'attachment',
                'label' => $this->lang->line("leaveapply_attachment"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_attachmentUpload',
            ),
        );
        return $rules;
    }

    public function add() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/datepicker/datepicker.css',
                    'assets/daterangepicker/daterangepicker.css',
                    'assets/editor/jquery-te-1.4.0.css',
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                ),
                'js'  => array(
                    'assets/datepicker/datepicker.js',
                    'assets/editor/jquery-te-1.4.0.min.js',
                    'assets/daterangepicker/moment.min.js',
                    'assets/daterangepicker/daterangepicker.js',
                    'assets/select2/select2.js'
                ),
            );

            $schoolyearID = $this->session->userdata("defaultschoolyearID");
            $usertypeID = $this->session->userdata("usertypeID");
            // $this->data['usertypes'] = $this->usertype_m->get_usertype_by_permission('leaveapplication', 1);
            $this->data['usertypes'] = $this->usertype_m->get_usertype();

            $this->data['myleaveapplications'] = $this->leaveapplication_m->get_sum_of_leave_days_by_user($this->session->userdata('usertypeID'), $this->session->userdata('loginuserID'), $schoolyearID);

            // $this->data['leavecategories'] = $this->leavecategory_m->get_join_leavecategory_and_leaveassign($this->session->userdata('usertypeID'), $schoolyearID);
            $this->data['leavecategories'] = $this->leavecategory_m->get_join_leavecategory_and_leaveassign($schoolyearID, NULL);
            
            $result = pluck($this->data['myleaveapplications'], 'days', 'leavecategoryID');

            foreach ($this->data['leavecategories'] as $item) {
                if (array_key_exists($item->leavecategoryID, $result)) {
                    $item->leaveassignday = $item->leaveassignday - $result[$item->leavecategoryID];
                }
            }

            foreach ($this->data['usertypes'] as $usertypeKey => $usertype) {
                // if (count($usertype) && $usertype->usertypeID == 3 || $usertype->usertypeID == 4) {
                //     unset($this->data['usertypes'][$usertypeKey]);
                // }
                if (count($usertype) && $usertype->usertypeID == 4) {
                    unset($this->data['usertypes'][$usertypeKey]);
                }
            }
            
            if($this->input->post('applicationto_usertypeID') > 0) {
                $this->data['users'] = $this->getuserlistbyrole($this->input->post('applicationto_usertypeID'));
            } else {
                $this->data['users'] = [];
            }

            $this->data['usertypeID'] = $this->input->post('applicationto_usertypeID');
            $this->data['userID'] = $this->input->post('applicationto_userID');

            if ($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"]         = "leaveapply/add";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $explode            = explode('-', $this->input->post("leave_schedule"));

                    $array["from_date"] = date("Y-m-d", strtotime(trim($explode[0])));
                    $array["to_date"] = date("Y-m-d", strtotime(trim($explode[1])));
                    $leavedaysCount = $this->leavedaysCount(trim($explode[0]), trim($explode[1]));

                    $array["leave_days"]                = isset($leavedaysCount['totaldayCount']) ? $leavedaysCount['totaldayCount'] : 0;
                    $array["leavecategoryID"]           = $this->input->post("leavecategoryID");
                    $array["applicationto_usertypeID"]  = $this->input->post("applicationto_usertypeID");
                    $array["applicationto_userID"]      = $this->input->post("applicationto_userID");
                    $array["reason"]                    = $this->input->post("reason");
                    $array["attachment"]                = $this->upload_data['file']['file_name'];
                    $array["attachmentorginalname"]     = $this->upload_data['file']['original_file_name'];
                    $array["from_time"]                 = date('H:i:s');
                    $array["to_time"]                   = date('H:i:s');
                    $array["create_date"]               = date("Y-m-d H:i:s");
                    $array["modify_date"]               = date("Y-m-d H:i:s");
                    $array["create_userID"]             = $this->session->userdata('loginuserID');
                    $array["create_usertypeID"]         = $this->session->userdata('usertypeID');
                    $array['schoolyearID']              = $schoolyearID;
                    if ($this->input->post("od_status")) {
                        $array["od_status"] = $this->input->post("od_status");
                    }

                    $this->leaveapplication_m->insert_leaveapplication($array);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("leaveapply/index"));
                }
            } else {
                $this->data["subview"] = "leaveapply/add";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function leavedaysCount($fromdate, $todate) {
        $allholidayArray    = explode('","', $this->getHolidaysSession());
        $getweekenddayArray = $this->getWeekendDaysSession();
        $leavedays = get_day_using_two_date(strtotime($fromdate), strtotime($todate));

        $holidayCount    = 0;
        $weekenddayCount = 0;
        $leavedayCount   = 0;
        $totaldayCount   = 0;
        $retArray = [];
        if(count($leavedays)) {
            foreach($leavedays as $leaveday) {
                if(in_array($leaveday, $allholidayArray)) {
                    $holidayCount++;
                } elseif(in_array($leaveday, $getweekenddayArray)) {
                    $weekenddayCount++;
                } else {
                    $leavedayCount++;
                }
                $totaldayCount++;
            }
        }

        $retArray['fromdate']        = $fromdate;
        $retArray['todate']          = $todate;
        $retArray['holidayCount']    = $holidayCount;
        $retArray['weekenddayCount'] = $weekenddayCount;
        $retArray['leavedayCount']   = $leavedayCount;
        $retArray['totaldayCount']   = $totaldayCount;
        return $retArray;
    }

    public function date_valid($date) {
        if($date) {
            if(strlen($date) < 10) {
                $this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy.");
                return FALSE;
            } else {
                $arr = explode("-", $date);
                $dd = $arr[0];
                $mm = $arr[1];
                $yyyy = $arr[2];
                if(checkdate($mm, $dd, $yyyy)) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy.");
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    public function date_schedule_valid($date) {
        if($date) {
            $dateLength = strlen($date);
            if($dateLength == 23) {
                $dataArray  = explode('-', $date);
                $from_date = trim($dataArray[0]);
                $to_date = trim($dataArray[1]);

                if($from_date) {
                    if(strlen($from_date) != 10) {
                        $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                        return FALSE;
                    } else {
                        $arr = explode("/", $from_date);
                        $dd = $arr[1];
                        $mm = $arr[0];
                        $yyyy = $arr[2];
                        if(checkdate($mm, $dd, $yyyy)) {
                            if($to_date) {
                                if(strlen($to_date) != 10) {
                                    $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                                    return FALSE;
                                } else {
                                    $arr = explode("/", $to_date);
                                    $dd = $arr[1];
                                    $mm = $arr[0];
                                    $yyyy = $arr[2];
                                    if(checkdate($mm, $dd, $yyyy)) {
                                        return TRUE;
                                    } else {
                                        $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                                        return FALSE;
                                    }
                                }
                            } else {
                                $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                                return FALSE;
                            }
                        } else {
                            $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                            return FALSE;
                        }
                    }
                } else {
                    $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                    return FALSE;
                }
            } else {
                $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    public function unique_data($data) {
        if($data != "") {
            if($data == "0") {
                $this->form_validation->set_message('unique_data', 'The %s field is required.');
                return FALSE;
            }
            return TRUE;
        } 
        return TRUE;
    }

    public function edit() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/datepicker/datepicker.css',
                    'assets/daterangepicker/daterangepicker.css',
                    'assets/editor/jquery-te-1.4.0.css',
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                ),
                'js'  => array(
                    'assets/datepicker/datepicker.js',
                    'assets/editor/jquery-te-1.4.0.min.js',
                    'assets/daterangepicker/moment.min.js',
                    'assets/daterangepicker/daterangepicker.js',
                    'assets/select2/select2.js'
                ),
            );

            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $schoolyearID = $this->session->userdata("defaultschoolyearID");
                $this->data['usertypes'] = $this->usertype_m->get_usertype_by_permission('leaveapplication', 1);

                foreach ($this->data['usertypes'] as $usertypeKey => $usertype) {
                    if (count($usertype) && ($usertype->usertypeID == 3 || $usertype->usertypeID == 4)) {
                        unset($this->data['usertypes'][$usertypeKey]);
                    }
                }

                $this->data['leaveapplication'] = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $id, 'schoolyearID' => $schoolyearID, 'create_usertypeID' => $this->session->userdata('usertypeID'), 'create_userID' => $this->session->userdata('loginuserID')));
                        
                if (count($this->data['leaveapplication'])) {
                    if($this->data['leaveapplication']->status == NULL) {
                        if($this->data['leaveapplication']->create_userID == $this->session->userdata('loginuserID') && $this->data['leaveapplication']->create_usertypeID == $this->session->userdata('usertypeID')) {
                            $this->data['usertypeID'] = $this->data['leaveapplication']->applicationto_usertypeID;
                            $this->data['userID']     = $this->data['leaveapplication']->applicationto_userID;
                            $this->data['users'] = $this->getuserlistbyrole($this->data['usertypeID']);


                            $this->data['myleaveapplications'] = $this->leaveapplication_m->get_sum_of_leave_days_by_user($this->session->userdata('usertypeID'), $this->session->userdata('loginuserID'), $schoolyearID);
                            $this->data['leavecategories']      = $this->leavecategory_m->get_join_leavecategory_and_leaveassign($this->session->userdata('usertypeID'), $schoolyearID);
                            $result                              = pluck($this->data['myleaveapplications'], 'days', 'leavecategoryID');
                            foreach ($this->data['leavecategories'] as $item) {
                                if (array_key_exists($item->leavecategoryID, $result)) {
                                    $item->leaveassignday = $item->leaveassignday - $result[$item->leavecategoryID];
                                    if ($this->data['leaveapplication']->leavecategoryID == $item->leavecategoryID) {
                                        $item->leaveassignday += $this->data['leaveapplication']->leave_days;
                                    }
                                }
                            }

                            if ($_POST) {
                                $this->data['usertypeID'] = $this->input->post('applicationto_usertypeID');
                                $this->data['userID'] = $this->input->post('applicationto_userID');
                                $this->data['users'] = $this->getuserlistbyrole($this->data['usertypeID']);
                                $rules = $this->rules();
                                $this->form_validation->set_rules($rules);
                                if ($this->form_validation->run() == FALSE) {
                                    $this->data["subview"] = "leaveapply/edit";
                                    $this->load->view('_layout_main', $this->data);
                                } else {
                                    $explode = explode('-', $this->input->post("leave_schedule"));
                                    $array["from_date"] = date("Y-m-d", strtotime(trim($explode[0])));
                                    $array["to_date"] = date("Y-m-d", strtotime(trim($explode[1])));
                                    $leavedaysCount = $this->leavedaysCount(trim($explode[0]), trim($explode[1]));

                                    $array["leave_days"]                = isset($leavedaysCount['totaldayCount']) ? $leavedaysCount['totaldayCount'] : '1';
                                    $array["leavecategoryID"]           = $this->input->post("leavecategoryID");
                                    $array["applicationto_usertypeID"]  = $this->input->post("applicationto_usertypeID");
                                    $array["applicationto_userID"]      = $this->input->post("applicationto_userID");
                                    $array["reason"]                    = $this->input->post("reason");
                                    $array["attachment"]                = $this->upload_data['file']['file_name'];
                                    $array["attachmentorginalname"]     = $this->upload_data['file']['original_file_name'];
                                    $array["status"]                    = null;
                                    $array["from_time"]                 = date('H:i:s');
                                    $array["to_time"]                   = date('H:i:s');

                                    if ($this->input->post("od_status")) {
                                        $array["od_status"] = $this->input->post("od_status");
                                    }
                                    $array["modify_date"] = date("Y-m-d H:i:s");

                                    $this->leaveapplication_m->update_leaveapplication($array, $id);
                                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                                    redirect(base_url("leaveapply/index"));
                                }
                            } else {
                                $this->data["subview"] = "leaveapply/edit";
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
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $schoolyearID      = $this->session->userdata("defaultschoolyearID");
                $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $id, 'schoolyearID' => $schoolyearID, 'create_usertypeID' => $this->session->userdata('usertypeID'), 'create_userID' => $this->session->userdata('loginuserID')));
                if (count($leaveapplication)) {
                    if($leaveapplication->status == NULL) {
                        if($leaveapplication->create_userID == $this->session->userdata('loginuserID') && $leaveapplication->create_usertypeID == $this->session->userdata('usertypeID')) {
                            if (config_item('demo') == FALSE) {
                                if ($leaveapplication->attachment != null) {
                                    if (file_exists(FCPATH . 'uploads/leaveapplications/' . $leaveapplication->attachment)) {
                                        unlink(FCPATH . 'uploads/leaveapplications/' . $leaveapplication->attachment);
                                    }
                                }
                            }
                            $this->leaveapplication_m->delete_leaveapplication($id);
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("leaveapply/index"));
                        } else {
                            redirect(base_url("leaveapply/index"));
                        }
                    } else {
                        redirect(base_url("leaveapply/index"));
                    }
                } else {
                    redirect(base_url("leaveapply/index"));
                }
            } else {
                redirect(base_url("leaveapply/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function usercall() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $usertypeID = $this->input->post('id');
        $sessionUsertypeID = $this->session->userdata('usertypeID');
        $sessionUserID = $this->session->userdata('loginuserID');
        if((int)$usertypeID) {
            $this->data['users'] = array();
            if($usertypeID == 1) {
                $this->data['users'] = $this->systemadmin_m->get_systemadmin();
                echo "<option value='0'>", $this->lang->line("leaveapply_select_user"),"</option>";
                if(count($this->data['users'])) {
                    foreach ($this->data['users'] as $value) {
                        if(!(($value->systemadminID == $sessionUserID) && $sessionUsertypeID == $usertypeID)) {
                            echo "<option value=\"$value->systemadminID\">",$value->name,"</option>";
                        }
                    }
                }
            } elseif($usertypeID == 2) {
                $this->data['users'] = $this->teacher_m->get_teacher();
                echo "<option value='0'>", $this->lang->line("leaveapply_select_user"),"</option>";
                if(count($this->data['users'])) {
                    foreach ($this->data['users'] as $value) {
                        if(!(($value->teacherID == $sessionUserID) && $sessionUsertypeID == $usertypeID)) {
                            echo "<option value=\"$value->teacherID\">",$value->name,"</option>";
                        }
                    }
                }
            } elseif($usertypeID == 3) {
                $this->data['users'] = $this->studentrelation_m->get_order_by_student(array('schoolyearID' => $schoolyearID));
                echo "<option value='0'>", $this->lang->line("leaveapply_select_user"),"</option>";
                if(count($this->data['users'])) {
                    foreach ($this->data['users'] as $value) {
                        if(!(($value->studentID == $sessionUserID) && $sessionUsertypeID == $usertypeID)) {
                            echo "<option value=\"$value->studentID\">",$value->name,"</option>";
                        }
                    }
                }
            } elseif($usertypeID == 4) {
                $this->data['users'] = $this->parents_m->get_parents();
                echo "<option value='0'>", $this->lang->line("leaveapply_select_user"),"</option>";
                if(count($this->data['users'])) {
                    foreach ($this->data['users'] as $value) {
                        if((!($value->parentsID == $sessionUserID) && $sessionUsertypeID == $usertypeID)) {
                            echo "<option value=\"$value->parentsID\">",$value->name,"</option>";
                        }
                    }
                }
            } else {
                $this->data['users'] = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
                echo "<option value='0'>", $this->lang->line("leaveapply_select_user"),"</option>";
                if(count($this->data['users'])) {
                    foreach ($this->data['users'] as $value) {
                        if(!(($value->userID == $sessionUserID) && $sessionUsertypeID == $usertypeID)) {
                            echo "<option value=\"$value->userID\">",$value->name,"</option>";
                        }
                    }
                }
            }
        }
    }

    private function getuserlistbyrole($usertypeID, $classesID = 0, $obj = FALSE)  {
        $userArray = [];
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        if($usertypeID == 1) {
            $systemadmins = $this->systemadmin_m->get_systemadmin();
            if($obj == FALSE) {
                $userArray = array(0 => $this->lang->line("leaveapply_select_user"));
                if(count($systemadmins)) {
                    foreach ($systemadmins as $systemadmin) {
                        $userArray[$systemadmin->systemadminID] = $systemadmin->name;
                    }
                }
            } else {
                $userArray = $systemadmins;
            }
        } elseif($usertypeID == 2) {
            $teachers = $this->teacher_m->get_teacher();
            if($obj == FALSE) {
                $userArray = array(0 => $this->lang->line("leaveapply_select_user"));
                if(count($teachers)) {
                    foreach ($teachers as $teacher) {
                        $userArray[$teacher->teacherID] = $teacher->name;
                    }
                }
            } else {
                $userArray = $teachers;
            }
        } elseif($usertypeID == 3) {
            if($classesID == 0) {
                $students = $this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID));
            } else {
                $students = $this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID, 'srclassesID' => $classesID));
            }

            if($obj == FALSE) {
                $userArray = array(0 => $this->lang->line("leaveapply_select_user"));
                if(count($students)) {
                    foreach ($students as $student) {
                        if(isset($student->name)) {
                            $userArray[$student->studentID] = $student->name.' - '.$this->lang->line('productsale_roll').' - '.$student->roll;
                        } else {
                            $userArray[$student->srstudentID] = $student->srname.' - '.$this->lang->line('productsale_roll').' - '.$student->srroll;
                        }
                    }
                }
            } else {
                $userArray = $students;
            }
        } elseif($usertypeID == 4) {
            $parents = $this->parents_m->get_parents();
            if($obj == FALSE) {
                $userArray = array(0 => $this->lang->line("leaveapply_select_user"));
                if(count($parents)) {
                    foreach ($parents as $parent) {
                        $userArray[$parent->parentsID] = $parent->name;
                    }
                }
            } else {
                $userArray = $parents;
            }
        } else {
            $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
            if($obj == FALSE) {
                $userArray = array(0 => $this->lang->line("leaveapply_select_user"));
                if(count($users)) {
                    foreach ($users as $user) {
                        $userArray[$user->userID] = $user->name;
                    }
                }
            } else {
                $userArray = $users;
            }
        }

        return $userArray;
    }

    public function download() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if(permissionChecker('leaveapply')) {
            $leaveapplicationID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$leaveapplicationID) {
                $leaveapply = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $leaveapplicationID, 'schoolyearID' => $schoolyearID));
                if(count($leaveapply)) {
                    if(($leaveapply->create_userID == $this->session->userdata('loginuserID')) && ($leaveapply->create_usertypeID == $this->session->userdata('usertypeID'))) {
                        $file = realpath('uploads/images/'.$leaveapply->attachment);
                        $originalname = $leaveapply->attachmentorginalname;
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
                            redirect(base_url('leaveapply/index'));
                        }
                    } else {
                        redirect(base_url('leaveapply/index'));
                    }
                } else {
                    redirect(base_url('leaveapply/index'));
                }
            } else {
                redirect(base_url('leaveapply/index'));
            }
        } else {
            redirect(base_url('leaveapply/index'));
        }
    }

    public function view() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$id) {
            $schoolyearID  = $this->session->userdata("defaultschoolyearID");
            $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
            $this->data['leaveapply'] = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $id, 'schoolyearID' => $schoolyearID));
            $this->data['userName'] = getAllUserObjectWithStudentRelation($schoolyearID);
            
            if(count($this->data['leaveapply'])) {
                if(($this->data['leaveapply']->create_userID == $this->session->userdata('loginuserID')) && ($this->data['leaveapply']->create_usertypeID == $this->session->userdata('usertypeID'))) {

                    $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID' => $this->data['leaveapply']->leavecategoryID));
                    if(count($leavecategory)) {
                        $this->data['leaveapply']->category = $leavecategory->leavecategory;
                    } else {
                        $this->data['leaveapply']->category = '';    
                    }

                    $availableleave = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($this->session->userdata('usertypeID'), $this->session->userdata('loginuserID'), $schoolyearID, $this->data['leaveapply']->leavecategoryID);                    
                    if(isset($availableleave->days) && $availableleave->days > 0) {
                        $availableleavedays = $availableleave->days;
                    } else {
                        $availableleavedays = 0;    
                    }

                    $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('leavecategoryID' => $this->data['leaveapply']->leavecategoryID, 'schoolyearID' => $schoolyearID));
                    if(count($leaveassign)) {
                        $this->data['leaveapply']->leaveavabledays = ($leaveassign->leaveassignday - $availableleavedays);
                    } else {
                        $this->data['leaveapply']->leaveavabledays = $this->lang->line('leaveapply_deleted');
                    }

                    $this->data['applicant']= getObjectByUserTypeIDAndUserID($this->data['leaveapply']->create_usertypeID, $this->data['leaveapply']->create_userID, $schoolyearID);

                    $this->data['daysArray'] = $this->leavedaysCount($this->data['leaveapply']->from_date, $this->data['leaveapply']->to_date);
                    // return print_r(json_encode($this->data['applicant']));
                    // return print_r(json_encode($this->data['userName'][$this->data['leaveapply']->applicationto_usertypeID][$this->data['leaveapply']->applicationto_userID]));
                    $this->data["subview"] = "leaveapply/view";
                    $this->load->view('_layout_main', $this->data);
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

    public function print_preview() {
        if(permissionChecker('leaveapply_view')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $schoolyearID  = $this->session->userdata("defaultschoolyearID");
                $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                $this->data['leaveapply'] = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $id, 'schoolyearID' => $schoolyearID));

                if(count($this->data['leaveapply'])) {
                    if(($this->data['leaveapply']->create_userID == $this->session->userdata('loginuserID')) && ($this->data['leaveapply']->create_usertypeID == $this->session->userdata('usertypeID'))) {
                        $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID' => $this->data['leaveapply']->leavecategoryID));
                        if(count($leavecategory)) {
                            $this->data['leaveapply']->category = $leavecategory->leavecategory;    
                        } else {
                            $this->data['leaveapply']->category = '';    
                        }

                        $availableleave = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($this->session->userdata('usertypeID'), $this->session->userdata('loginuserID'), $schoolyearID, $this->data['leaveapply']->leavecategoryID);                    
                        if(isset($availableleave->days) && $availableleave->days > 0) {
                            $availableleavedays = $availableleave->days;
                        } else {
                            $availableleavedays = 0;    
                        }

                        $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('leavecategoryID' => $this->data['leaveapply']->leavecategoryID, 'schoolyearID' => $schoolyearID));
                        if(count($leaveassign)) {
                            $this->data['leaveapply']->leaveavabledays = ($leaveassign->leaveassignday - $availableleavedays);
                        } else {
                            $this->data['leaveapply']->leaveavabledays = $this->lang->line('leaveapply_deleted');
                        }


                        $this->data['applicant']= getObjectByUserTypeIDAndUserID($this->data['leaveapply']->create_usertypeID, $this->data['leaveapply']->create_userID, $schoolyearID);

                        $this->data['userName'] = getAllUserObjectWithStudentRelation($schoolyearID);
                        
                        $this->data['daysArray'] = $this->leavedaysCount($this->data['leaveapply']->from_date, $this->data['leaveapply']->to_date);

                        $this->reportPDF('leaveapplicationmodule.css',$this->data,'leaveapply/print_preview');
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
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function send_mail_rules() {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("leaveapply_to"),
                'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("leaveapply_subject"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("leaveapply_message"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'leaveapplicationID',
                'label' => $this->lang->line("leaveapply_leaveapplicationID"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
            )
        );
        return $rules;
    }

    public function send_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('leaveapply_view')) {
            if($_POST) {
                $rules = $this->send_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $leaveapplicationID = $this->input->post('leaveapplicationID');
                    if((int)$leaveapplicationID) {
                        $email = $this->input->post('to');
                        $subject = $this->input->post('subject');
                        $message = $this->input->post('message');
                        $schoolyearID  = $this->session->userdata("defaultschoolyearID");
                        $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                        $this->data['leaveapply'] = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $leaveapplicationID, 'schoolyearID' => $schoolyearID));

                        if(count($this->data['leaveapply'])) {
                            if(($this->data['leaveapply']->create_userID == $this->session->userdata('loginuserID')) && ($this->data['leaveapply']->create_usertypeID == $this->session->userdata('usertypeID'))) {

                                $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID' => $this->data['leaveapply']->leavecategoryID));
                                if(count($leavecategory)) {
                                    $this->data['leaveapply']->category = $leavecategory->leavecategory;    
                                } else {
                                    $this->data['leaveapply']->category = '';    
                                }

                                $availableleave = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($this->session->userdata('usertypeID'), $this->session->userdata('loginuserID'), $schoolyearID, $this->data['leaveapply']->leavecategoryID);                    
                                if(isset($availableleave->days) && $availableleave->days > 0) {
                                    $availableleavedays = $availableleave->days;
                                } else {
                                    $availableleavedays = 0;    
                                }

                                $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('leavecategoryID' => $this->data['leaveapply']->leavecategoryID, 'schoolyearID' => $schoolyearID));
                                if(count($leaveassign)) {
                                    $this->data['leaveapply']->leaveavabledays = ($leaveassign->leaveassignday - $availableleavedays);
                                } else {
                                    $this->data['leaveapply']->leaveavabledays = $this->lang->line('leaveapply_deleted');
                                }

                                $this->data['applicant']= getObjectByUserTypeIDAndUserID($this->data['leaveapply']->create_usertypeID, $this->data['leaveapply']->create_userID, $schoolyearID);

                                $this->data['daysArray'] = $this->leavedaysCount($this->data['leaveapply']->from_date, $this->data['leaveapply']->to_date);

                                $this->reportSendToMail('leaveapplicationmodule.css', $this->data, 'leaveapply/print_preview', $email, $subject, $message);
                                $retArray['message'] = "Success";
                                $retArray['status'] = TRUE;
                                echo json_encode($retArray);
                                exit;
                            } else {
                                $retArray['message'] = $this->lang->line('leaveapply_data_not_found');
                                echo json_encode($retArray);
                                exit;
                            }
                        } else {
                            $retArray['message'] = $this->lang->line('leaveapply_data_not_found');
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('leaveapply_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('leaveapply_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('leaveapply_permission');
            echo json_encode($retArray);
            exit;
        }
    }
}
