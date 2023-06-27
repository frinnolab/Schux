<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Leaveapplication extends Admin_Controller
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
        $this->load->model("classes_m");
        $this->load->model("section_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('leaveapplication', $language);
    }

    public function index() {
        $userID        = $this->session->userdata("loginuserID");
        $usertypeID    = $this->session->userdata("usertypeID");
        $schoolyearID  = $this->session->userdata("defaultschoolyearID");
        if($usertypeID != 1) {
            $this->data['leaveapplications'] = $this->leaveapplication_m->get_order_by_leaveapplication(['applicationto_userID' => $userID, 'applicationto_usertypeID' => $usertypeID, 'schoolyearID' => $schoolyearID]);
        } else {
            $this->data['leaveapplications'] = $this->leaveapplication_m->get_order_by_leaveapplication(array('schoolyearID' => $schoolyearID));
        }

        $this->data['leavecategorys'] = pluck($this->leavecategory_m->get_leavecategory(), 'leavecategory', 'leavecategoryID');
        $this->data['allUser'] = getAllUserObjectWithStudentRelation($schoolyearID, TRUE);
        $this->data['allUserTypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
        $this->data["subview"] = "leaveapplication/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function status() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            if(permissionChecker('leaveapplication')) {
                $usertypeID = $this->session->userdata("usertypeID");
                $userID = $this->session->userdata("loginuserID");
                $id = htmlentities(escapeString($this->uri->segment(3)));
                if ((int)$id) {
                    $schoolyearID      = $this->session->userdata("defaultschoolyearID");
                    $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $id, 'schoolyearID' => $schoolyearID));
                    if (count($leaveapplication)) {
                        if($usertypeID == 1 || ($leaveapplication->applicationto_usertypeID == $usertypeID && $leaveapplication->applicationto_userID == $userID)) {
                            if ($leaveapplication->status == 1) {
                                $array["status"] = 0;
                            } else {
                                $array["status"] = 1;
                            }
                            $array["modify_date"] = date("Y-m-d H:i:s");
                            $this->leaveapplication_m->update_leaveapplication($array, $id);
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect_back();
                        } else {
                            redirect_back();
                        }
                    } else {
                        redirect_back();
                    }
                } else {
                    redirect_back();
                }
            } else {
                redirect_back();
            }
        } else {
            redirect_back();
        }
    }

    public function view() {
        if(permissionChecker('leaveapplication')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if ((int)$id) {
                $schoolyearID  = $this->session->userdata("defaultschoolyearID");
                $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                $this->data['leaveapplication'] = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $id, 'schoolyearID' => $schoolyearID));

                if(count($this->data['leaveapplication'])) {
                    if((($this->data['leaveapplication']->applicationto_userID == $this->session->userdata('loginuserID')) && ($this->data['leaveapplication']->applicationto_usertypeID == $this->session->userdata('usertypeID'))) || ($this->session->userdata('usertypeID') == 1)) {
                        $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID' => $this->data['leaveapplication']->leavecategoryID));
                        if(count($leavecategory)) {
                            $this->data['leaveapplication']->category = $leavecategory->leavecategory;    
                        } else {
                            $this->data['leaveapplication']->category = '';    
                        }

                        $availableleave = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($this->data['leaveapplication']->create_usertypeID, $this->data['leaveapplication']->create_userID, $schoolyearID, $this->data['leaveapplication']->leavecategoryID);              
                        if(isset($availableleave->days) && $availableleave->days > 0) {
                            $availableleavedays = $availableleave->days;
                        } else {
                            $availableleavedays = 0;    
                        }

                        $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('leavecategoryID' => $this->data['leaveapplication']->leavecategoryID, 'schoolyearID' => $schoolyearID));
                        if(count($leaveassign)) {
                            $this->data['leaveapplication']->leaveavabledays = ($leaveassign->leaveassignday - $availableleavedays);
                        } else {
                            $this->data['leaveapplication']->leaveavabledays = $this->lang->line('leaveapplication_deleted');
                        }

                        $this->data['applicant'] = getObjectByUserTypeIDAndUserID($this->data['leaveapplication']->create_usertypeID, $this->data['leaveapplication']->create_userID, $schoolyearID);

                        $this->data['daysArray'] = $this->leavedaysCount($this->data['leaveapplication']->from_date, $this->data['leaveapplication']->to_date);

                        $this->data['allUser'] = getAllUserObjectWithStudentRelation($schoolyearID, TRUE);
                        
                        $this->data["subview"] = "leaveapplication/view";
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

    public function print_preview() {
        if(permissionChecker('leaveapplication')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $schoolyearID  = $this->session->userdata("defaultschoolyearID");
                $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                $this->data['leaveapplication'] = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $id, 'schoolyearID' => $schoolyearID));
                $this->data['allUser'] = getAllUserObjectWithStudentRelation($schoolyearID, TRUE);
                
                if(count($this->data['leaveapplication'])) {
                    if((($this->data['leaveapplication']->applicationto_userID == $this->session->userdata('loginuserID')) && ($this->data['leaveapplication']->applicationto_usertypeID == $this->session->userdata('usertypeID'))) || ($this->session->userdata('usertypeID') == 1)) {
                        $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID' => $this->data['leaveapplication']->leavecategoryID));
                        if(count($leavecategory)) {
                            $this->data['leaveapplication']->category = $leavecategory->leavecategory;    
                        } else {
                            $this->data['leaveapplication']->category = '';    
                        }

                        $availableleave = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($this->data['leaveapplication']->create_usertypeID, $this->data['leaveapplication']->create_userID, $schoolyearID, $this->data['leaveapplication']->leavecategoryID);              
                        if(isset($availableleave->days) && $availableleave->days > 0) {
                            $availableleavedays = $availableleave->days;
                        } else {
                            $availableleavedays = 0;    
                        }

                        $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('leavecategoryID' => $this->data['leaveapplication']->leavecategoryID, 'schoolyearID' => $schoolyearID));
                        if(count($leaveassign)) {
                            $this->data['leaveapplication']->leaveavabledays = ($leaveassign->leaveassignday - $availableleavedays);
                        } else {
                            $this->data['leaveapplication']->leaveavabledays = $this->lang->line('leaveapplication_deleted');
                        }

                        $this->data['applicant']= getObjectByUserTypeIDAndUserID($this->data['leaveapplication']->create_usertypeID, $this->data['leaveapplication']->create_userID, $schoolyearID);
                        $this->data['daysArray'] = $this->leavedaysCount($this->data['leaveapplication']->from_date, $this->data['leaveapplication']->to_date);
                        
                        $this->reportPDF('leaveapplicationmodule.css',$this->data,'leaveapplication/print_preview');
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
                'label' => $this->lang->line("leaveapplication_to"),
                'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("leaveapplication_subject"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("leaveapplication_message"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'leaveapplicationID',
                'label' => $this->lang->line("leaveapplication_leaveapplicationID"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
            )
        );
        return $rules;
    }

    public function send_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('leaveapplication')) {
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
                        $this->data['leaveapplication'] = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $leaveapplicationID, 'schoolyearID' => $schoolyearID));

                        if(count($this->data['leaveapplication'])) {
                            if((($this->data['leaveapplication']->applicationto_userID == $this->session->userdata('loginuserID')) && ($this->data['leaveapplication']->applicationto_usertypeID == $this->session->userdata('usertypeID'))) || ($this->session->userdata('usertypeID') == 1)) {
                                $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID' => $this->data['leaveapplication']->leavecategoryID));
                                if(count($leavecategory)) {
                                    $this->data['leaveapplication']->category = $leavecategory->leavecategory;    
                                } else {
                                    $this->data['leaveapplication']->category = '';    
                                }

                                $availableleave = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($this->data['leaveapplication']->create_usertypeID, $this->data['leaveapplication']->create_userID, $schoolyearID, $this->data['leaveapplication']->leavecategoryID);              
                                if(isset($availableleave->days) && $availableleave->days > 0) {
                                    $availableleavedays = $availableleave->days;
                                } else {
                                    $availableleavedays = 0;    
                                }

                                $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('leavecategoryID' => $this->data['leaveapplication']->leavecategoryID, 'schoolyearID' => $schoolyearID));
                                if(count($leaveassign)) {
                                    $this->data['leaveapplication']->leaveavabledays = ($leaveassign->leaveassignday - $availableleavedays);
                                } else {
                                    $this->data['leaveapplication']->leaveavabledays = $this->lang->line('leaveapplication_deleted');
                                }

                                $this->data['applicant']= getObjectByUserTypeIDAndUserID($this->data['leaveapplication']->create_usertypeID, $this->data['leaveapplication']->create_userID, $schoolyearID);
                                 $this->data['daysArray'] = $this->leavedaysCount($this->data['leaveapplication']->from_date, $this->data['leaveapplication']->to_date);

                                $this->reportSendToMail('leaveapplicationmodule.css', $this->data, 'leaveapplication/print_preview', $email, $subject, $message);
                                $retArray['message'] = "Success";
                                $retArray['status'] = TRUE;
                                echo json_encode($retArray);
                                exit;
                            } else {
                                $retArray['message'] = $this->lang->line('leaveapplication_data_not_found');
                                echo json_encode($retArray);
                                exit;
                            }
                        } else {
                            $retArray['message'] = $this->lang->line('leaveapplication_data_not_found');
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('leaveapplication_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('leaveapplication_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('leaveapplication_permission');
            echo json_encode($retArray);
            exit;
        }
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

    public function download() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if(permissionChecker('leaveapplication')) {
            $leaveapplicationID   = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$leaveapplicationID) {
                $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $leaveapplicationID, 'schoolyearID' => $schoolyearID));
                if(count($leaveapplication)) {
                    if((($leaveapplication->applicationto_userID == $this->session->userdata('loginuserID')) && ($leaveapplication->applicationto_usertypeID == $this->session->userdata('usertypeID'))) || ($this->session->userdata('usertypeID') == 1)) {
                        $file         = realpath('uploads/images/'.$leaveapplication->attachment);
                        $originalname = $leaveapplication->attachmentorginalname;
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
                            redirect(base_url('leaveapplication/index'));
                        }
                    } else {
                        redirect(base_url('leaveapplication/index'));
                    }
                } else {
                    redirect(base_url('leaveapplication/index'));
                }
            } else {
                redirect(base_url('leaveapplication/index'));
            }
        } else {
            redirect(base_url('leaveapplication/index'));
        }
    }
}
