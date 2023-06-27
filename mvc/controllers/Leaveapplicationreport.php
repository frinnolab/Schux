<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaveapplicationreport extends Admin_Controller {
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
| WEBSITE:			http://iNilabs.net
| -----------------------------------------------------
*/
	function __construct() {
		parent::__construct();
        $this->load->model('usertype_m');
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('user_m');
        $this->load->model('teacher_m');
        $this->load->model('schoolyear_m');
        $this->load->model('parents_m');
        $this->load->model('systemadmin_m');
        $this->load->model('leavecategory_m');
        $this->load->model('studentrelation_m');
        $this->load->model('leaveapplication_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('leaveapplicationreport', $language);
	}

    protected function rules($usertypeID) {
        $rules = array(
            array(
                'field' => 'usertypeID',
                'label' => $this->lang->line('leaveapplicationreport_role'),
                'rules' => 'trim|required|xss_clean|numeric'
            ), array(
                'field' => 'userID',
                'label' => $this->lang->line('leaveapplicationreport_user'),
                'rules' => 'trim|xss_clean|numeric'
            ), array(
                'field' => 'categoryID',
                'label' => $this->lang->line('leaveapplicationreport_category'),
                'rules' => 'trim|xss_clean|numeric'
            ), array(
                'field' => 'statusID',
                'label' => $this->lang->line('leaveapplicationreport_status'),
                'rules' => 'trim|xss_clean|numeric'
            ), array(
                'field' => 'fromdate',
                'label' => $this->lang->line('leaveapplicationreport_fromdate'),
                'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date|callback_date_required_check'
            ), array(
                'field' => 'todate',
                'label' => $this->lang->line('leaveapplicationreport_todate'),
                'rules' => 'trim|xss_clean|callback_date_valid'
            )
        );
        if($usertypeID == 3) {
            $rules[] = array(
                'field' => 'classesID', 
                'label' => $this->lang->line('leaveapplicationreport_class'),
                'rules' => 'trim|xss_clean|numeric'
            );

            $rules[] = array(
                'field' => 'sectionID',
                'label' => $this->lang->line('leaveapplicationreport_section'),
                'rules' => 'trim|xss_clean|numeric'
            );
        }
        return $rules;
    }

    protected function send_pdf_to_mail_rules($usertypeID) {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line('leaveapplicationreport_to'),
                'rules' => 'trim|required|xss_clean|valid_email'
            ), array(
                'field' => 'subject',
                'label' => $this->lang->line('leaveapplicationreport_subject'),
                'rules' => 'trim|required|xss_clean'
            ), array(
                'field' => 'message',
                'label' => $this->lang->line('leaveapplicationreport_message'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'usertypeID',
                'label' => $this->lang->line('leaveapplicationreport_role'),
                'rules' => 'trim|required|xss_clean|numeric'
            ), array(
                'field' => 'userID',
                'label' => $this->lang->line('leaveapplicationreport_user'),
                'rules' => 'trim|xss_clean|numeric'
            ), array(
                'field' => 'categoryID',
                'label' => $this->lang->line('leaveapplicationreport_category'),
                'rules' => 'trim|xss_clean|numeric'
            ), array(
                'field' => 'statusID',
                'label' => $this->lang->line('leaveapplicationreport_status'),
                'rules' => 'trim|xss_clean|numeric'
            ), array(
                'field' => 'fromdate',
                'label' => $this->lang->line('leaveapplicationreport_fromdate'),
                'rules' => 'trim|xss_clean|callback_date_valid_new|callback_unique_date_new|callback_date_required_check_new'
            ), array(
                'field' => 'todate',
                'label' => $this->lang->line('leaveapplicationreport_todate'),
                'rules' => 'trim|xss_clean|callback_date_valid_new'
            )
        );
        if($usertypeID == 3) {
            $rules[] = array(
                'field' => 'classesID', 
                'label' => $this->lang->line('leaveapplicationreport_class'),
                'rules' => 'trim|xss_clean|numeric'
            );

            $rules[] = array(
                'field' => 'sectionID',
                'label' => $this->lang->line('leaveapplicationreport_section'),
                'rules' => 'trim|xss_clean|numeric'
            );
        }
        return $rules;
    }

    public function valid_data($data) {
        if($data != '') {
            if($data == 0) {
                $this->form_validation->set_message('valid_data','The %s field is required.');
                return FALSE;
            }
            return TRUE;
        } 
        return TRUE;
    }

    public function index() {
        $this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
				'assets/select2/select2.js'
			)
		);


        $this->data['usertypes'] = $this->usertype_m->get_usertype();
        $this->data['classes'] = $this->classes_m->general_get_classes();
        $this->data['leavecategories'] = $this->leavecategory_m->get_leavecategory();
        $this->data["subview"] = "report/leaveapplication/LeaveapplicationReportView";
        $this->load->view('_layout_main', $this->data);
    }

    public function getleaveapplicationreport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('leaveapplicationreport')) {
            if($_POST) {
                $usertypeID = $this->input->post('usertypeID');
                $rules = $this->rules($usertypeID);
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {

                	$queryArray = $this->queryArray($this->input->post());

                    $this->data['usertypeID']  = $usertypeID;
                    $this->data['classesID']   = $this->input->post('classesID');
                    $this->data['sectionID']   = $this->input->post('sectionID');
                    $this->data['userID']      = $this->input->post('userID');
                    $this->data['categoryID']  = $this->input->post('categoryID');
                    $this->data['statusID']    = $this->input->post('statusID');
                    $this->data['fromdate']    = strtotime($this->input->post('fromdate'));
                    $this->data['todate']      = strtotime($this->input->post('todate'));
                    
                    if($usertypeID == 3) {
                    	$this->data['classes']   = pluck($this->classes_m->general_get_classes(),'classes','classesID');
	                    $this->data['sections']  = pluck($this->section_m->general_get_section(),'section','sectionID');
                    }

                    if($this->input->post('categoryID')) {
                        $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID'=>$this->input->post('categoryID')));
                        if(count($leavecategory)) {
                            $this->data['categoryName'] = $leavecategory->leavecategory;
                        } else {
                            $this->data['categoryName'] = '';
                        }
                    }

                    if($this->input->post('statusID')) {    
                        $statusID = $this->input->post('statusID');
                        if($statusID == 1) {
                            $this->data['statusName'] = $this->lang->line("leaveapplicationreport_status_pending");
                        } elseif($statusID == 2) {
                            $this->data['statusName'] = $this->lang->line("leaveapplicationreport_status_declined");
                        } elseif ($statusID == 3) {
                            $this->data['statusName'] = $this->lang->line("leaveapplicationreport_status_approved");
                        } else {
                            $this->data['statusName'] = '';
                        }
                    }

                    $this->data['usertypes']   = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                    $this->data['userObejct']   = getAllUserObjectWithStudentRelation($this->session->userdata('defaultschoolyearID'), TRUE);
                    $this->data['leaveapplications'] = $this->leaveapplication_m->get_leaveapplication_for_report($queryArray);
                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/leaveapplication/LeaveapplicationReport', $this->data,true);
                    echo json_encode($retArray);
                    exit;
                }
            } else {
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['status'] = TRUE;
            $retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
            echo json_encode($retArray);
            exit;
        }
    }

    public function pdf() {
        if(permissionChecker('leaveapplicationreport')) {
            $usertypeID = htmlentities(escapeString($this->uri->segment(3)));
            $classesID  = htmlentities(escapeString($this->uri->segment(4)));
            $sectionID  = htmlentities(escapeString($this->uri->segment(5)));
            $userID     = htmlentities(escapeString($this->uri->segment(6)));
            $categoryID = htmlentities(escapeString($this->uri->segment(7)));
            $statusID   = htmlentities(escapeString($this->uri->segment(8)));
            $fromdate   = htmlentities(escapeString($this->uri->segment(9)));
            $todate     = htmlentities(escapeString($this->uri->segment(10)));

            if(((int)$usertypeID || $usertypeID == 0) && ((int)$classesID || $classesID == 0) && ((int)$sectionID || $sectionID == 0) && ((int)$userID || $userID == 0) && ((int)$categoryID || $categoryID == 0) && ((int)$statusID || $statusID == 0) && ((int)$fromdate || $fromdate == null) && ((int)$todate || $todate == null)) {

                $qArray['usertypeID'] = $usertypeID; 
                $qArray['classesID'] = $classesID; 
                $qArray['sectionID'] = $sectionID; 
                $qArray['userID'] = $userID; 
                $qArray['categoryID'] = $categoryID; 
                $qArray['statusID'] = $statusID;
                
                if((int)$fromdate && (int)$fromdate) {
                    $qArray['fromdate'] = date('d-m-Y',$fromdate); 
                    $qArray['todate'] = date('d-m-Y',$todate); 
                } else {
                    $qArray['fromdate'] = $fromdate; 
                    $qArray['todate'] = $todate; 
                }

                $queryArray = $this->queryArray($qArray);

                $this->data['usertypeID']  = $usertypeID;
                $this->data['classesID']   = $classesID;
                $this->data['sectionID']   = $sectionID;
                $this->data['userID']      = $userID;
                $this->data['categoryID']  = $categoryID;
                $this->data['statusID']    = $statusID;
                $this->data['fromdate']    = $fromdate;
                $this->data['todate']      = $todate;
                
                if($usertypeID == 3) {
                    $this->data['classes']   = pluck($this->classes_m->general_get_classes(),'classes','classesID');
                    $this->data['sections']  = pluck($this->section_m->general_get_section(),'section','sectionID');
                }

                if($categoryID) {
                    $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID' => $categoryID));
                    if(count($leavecategory)) {
                        $this->data['categoryName'] = $leavecategory->leavecategory;
                    } else {
                        $this->data['categoryName'] = '';
                    }
                }

                if($statusID) {    
                    if($statusID == 1) {
                        $this->data['statusName'] = $this->lang->line("leaveapplicationreport_status_pending");
                    } elseif($statusID == 2) {
                        $this->data['statusName'] = $this->lang->line("leaveapplicationreport_status_declined");
                    } elseif ($statusID == 3) {
                        $this->data['statusName'] = $this->lang->line("leaveapplicationreport_status_approved");
                    } else {
                        $this->data['statusName'] = '';
                    }
                }
                $this->data['usertypes']   = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                $this->data['userObejct']   = getAllUserObjectWithStudentRelation($this->session->userdata('defaultschoolyearID'), TRUE);
                $this->data['leaveapplications'] = $this->leaveapplication_m->get_leaveapplication_for_report($queryArray);
                $this->reportPDF('leaveapplicationreport.css', $this->data,'report/leaveapplication/LeaveapplicationReportPDF');
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }


    private function queryArray($posts) {
        $usertypeID = $posts['usertypeID'];
        $classesID  = $posts['classesID'];
        $sectionID  = $posts['sectionID'];
        $userID     = $posts['userID'];
        $categoryID = $posts['categoryID'];
        $statusID   = $posts['statusID'];
        $fromdate   = $posts['fromdate'];
        $todate     = $posts['todate'];

        $queryArray = [];

        if($usertypeID > 0) {
            $queryArray['usertypeID'] = $usertypeID;
        }

        if($userID > 0) {
            $queryArray['userID'] = $userID;
        }

        if($usertypeID == 3) {
            if($classesID > 0) {
                $queryArray['classesID'] = $classesID;
            }

            if($sectionID > 0) {
                $queryArray['sectionID'] = $sectionID;
            }
        }

        if($categoryID > 0) {
            $queryArray['categoryID'] = $categoryID;
        }

        if($statusID > 0) {
            $queryArray['statusID'] = $statusID;
        }

        if($fromdate && $todate) {
            $fromdate = date('Y-m-d',strtotime($fromdate));
            $queryArray['fromdate'] = $fromdate." 00:00:00";
            $todate = date('Y-m-d',strtotime($todate));
            $queryArray['todate'] = $todate." 23:59:59";
        }
        
        $queryArray['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
        return $queryArray;
    }

    public function getUser() {
        $usertypeID = $this->input->post('usertypeID');
        if((int)$usertypeID) {
            echo "<option value='0'>".$this->lang->line("leaveapplicationreport_please_select")."</option>";
            if($usertypeID == 1) {
                $users = $this->systemadmin_m->get_systemadmin();
                if(count($users)) {
                    foreach ($users as $user) {
                        echo "<option value='".$user->systemadminID."'>".$user->name."</option>";
                    }
                }
            } elseif($usertypeID == 2) {
                $users = $this->teacher_m->general_get_teacher();
                if(count($users)) {
                    foreach ($users as $user) {
                        echo "<option value='".$user->teacherID."'>".$user->name."</option>";
                    }
                }
            } elseif($usertypeID == 3) {
                $users = [];
            } elseif($usertypeID == 4) {
                $users = [];
            } else {
                $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
                if(count($users)) {
                    foreach ($users as $user) {
                        echo "<option value='".$user->userID."'>".$user->name."</option>";
                    }
                }
            }
        }
    }

    public function getSection() {
        $id = $this->input->post('id');
        if((int)$id) {
            $sections = $this->section_m->general_get_order_by_section(array('classesID' => $id));
            echo "<option value='0'>".$this->lang->line("leaveapplicationreport_please_select")."</option>";
            foreach ($sections as $section) {
                echo "<option value='".$section->sectionID."'>".$section->section."</option>";
            }
        }
    }

    public function getStudentByClass() {
        $usertypeID = $this->input->post('usertypeID');
        $classesID = $this->input->post('classesID');
        if(((int)$usertypeID && $usertypeID == 3) && ((int)$classesID && $classesID > 0)) {
            $queryArray['srschoolyearID'] = $this->session->userdata('defaultschoolyearID');
            $queryArray['srclassesID'] = $classesID;
            $users = $this->studentrelation_m->general_get_order_by_student($queryArray);
            if(count($users)) {
                echo "<option value='0'>".$this->lang->line("leaveapplicationreport_please_select")."</option>";
                foreach ($users as $user) {
                    echo "<option value='".$user->srstudentID."'>".$user->srname."</option>";
                }
            }  
        }
    }

    public function getStudentBySection() {
        $usertypeID = $this->input->post('usertypeID');
        $classesID = $this->input->post('classesID');
        $sectionID = $this->input->post('sectionID');
        if(((int)$usertypeID && $usertypeID == 3) && ((int)$classesID && $classesID > 0)) {
            $queryArray['srschoolyearID'] = $this->session->userdata('defaultschoolyearID');
            $queryArray['srclassesID'] = $classesID;
            if((int)$sectionID && $sectionID > 0) {
                $queryArray['srsectionID'] = $sectionID;
            }
            $users = $this->studentrelation_m->general_get_order_by_student($queryArray);
            if(count($users)) {
                echo "<option value='0'>".$this->lang->line("leaveapplicationreport_please_select")."</option>";
                foreach ($users as $user) {
                    echo "<option value='".$user->srstudentID."'>".$user->srname."</option>";
                }
            }  
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';

        if(permissionChecker('leaveapplicationreport')) {
            if($_POST) {
                $to         = $this->input->post('to');
                $subject    = $this->input->post('subject');
                $message    = $this->input->post('message');
                $usertypeID = $this->input->post('usertypeID');
                $classesID  = $this->input->post('classesID');
                $sectionID  = $this->input->post('sectionID');
                $userID     = $this->input->post('userID');
                $categoryID = $this->input->post('categoryID');
                $statusID   = $this->input->post('statusID');
                $fromdate   = $this->input->post('fromdate');
                $todate     = $this->input->post('todate');
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $rules = $this->send_pdf_to_mail_rules($usertypeID);
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    if(((int)$usertypeID || $usertypeID == 0) && ((int)$classesID || $classesID == 0) && ((int)$sectionID || $sectionID == 0) && ((int)$userID || $userID == 0) && ((int)$categoryID || $categoryID == 0) && ((int)$statusID || $statusID == 0) && ((int)$fromdate || $fromdate == null) && ((int)$todate || $todate == null)) {

                        $qArray['usertypeID'] = $usertypeID; 
                        $qArray['classesID'] = $classesID; 
                        $qArray['sectionID'] = $sectionID; 
                        $qArray['userID'] = $userID; 
                        $qArray['categoryID'] = $categoryID; 
                        $qArray['statusID'] = $statusID; 
                        
                        if((int)$fromdate && (int)$fromdate) {
                            $qArray['fromdate'] = date('d-m-Y',$fromdate); 
                            $qArray['todate'] = date('d-m-Y',$todate); 
                        } else {
                            $qArray['fromdate'] = $fromdate; 
                            $qArray['todate'] = $todate; 
                        }
                        
                        $queryArray = $this->queryArray($qArray);

                        $this->data['usertypeID']  = $usertypeID;
                        $this->data['classesID']   = $classesID;
                        $this->data['sectionID']   = $sectionID;
                        $this->data['userID']      = $userID;
                        $this->data['categoryID']  = $categoryID;
                        $this->data['statusID']    = $statusID;
                        $this->data['fromdate']    = $fromdate;
                        $this->data['todate']      = $todate;
                        
                        if($usertypeID == 3) {
                            $this->data['classes']   = pluck($this->classes_m->general_get_classes(),'classes','classesID');
                            $this->data['sections']  = pluck($this->section_m->general_get_section(),'section','sectionID');
                        }

                        if((int)$this->input->post('categoryID')) {
                            $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID'=>$this->input->post('categoryID')));
                            if(count($leavecategory)) {
                                $this->data['categoryName'] = $leavecategory->leavecategory;
                            } else {
                                $this->data['categoryName'] = '';
                            }
                        }

                        if($this->input->post('statusID')) {    
                            $statusID = $this->input->post('statusID');
                            if($statusID == 1) {
                                $this->data['statusName'] = $this->lang->line("leaveapplicationreport_status_pending");
                            } elseif($statusID == 2) {
                                $this->data['statusName'] = $this->lang->line("leaveapplicationreport_status_declined");
                            } elseif ($statusID == 3) {
                                $this->data['statusName'] = $this->lang->line("leaveapplicationreport_status_approved");
                            } else {
                                $this->data['statusName'] = '';
                            }
                        }
                        $this->data['usertypes']   = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                        $this->data['userObejct']   = getAllUserObjectWithStudentRelation($this->session->userdata('defaultschoolyearID'), TRUE);
                        $this->data['leaveapplications'] = $this->leaveapplication_m->get_leaveapplication_for_report($queryArray);
                        $this->reportSendToMail('leaveapplicationreport.css', $this->data, 'report/leaveapplication/LeaveapplicationReportPDF', $to, $subject, $message);
                        $retArray['message'] = "Message";
                        $retArray['status'] = TRUE;
                        echo json_encode($retArray);
                        exit;
                    } else {
                        $retArray['message'] = $this->lang->line('leaveapplicationreport_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('leaveapplicationreport_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('leaveapplicationreport_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    public function date_valid($date) {
		if($date) {
			if(strlen($date) < 10) {
				$this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy");
		     	return FALSE;
			} else {
		   		$arr = explode("-", $date);
		        $dd = $arr[0];
		        $mm = $arr[1];
		        $yyyy = $arr[2];
		      	if(checkdate($mm, $dd, $yyyy)) {
		      		return TRUE;
		      	} else {
		      		$this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy");
		     		return FALSE;
		      	}
		    }
		}
		return TRUE;
	}

	public function unique_date() {
		$fromdate = $this->input->post('fromdate');
		$todate   = $this->input->post('todate');

		$startingdate = $this->data['schoolyearsessionobj']->startingdate;
		$endingdate = $this->data['schoolyearsessionobj']->endingdate;

		if($fromdate != '' && $todate != '') {
			if(strtotime($fromdate) > strtotime($todate)) {
				$this->form_validation->set_message("unique_date", "The from date can not be upper than todate .");
		   		return FALSE;
			}
			if((strtotime($fromdate) < strtotime($startingdate)) || (strtotime($fromdate) > strtotime($endingdate))) {
				$this->form_validation->set_message("unique_date", "The from date are invalid .");
			    return FALSE;
			}
			if((strtotime($todate) < strtotime($startingdate)) || (strtotime($todate) > strtotime($endingdate))) {
				$this->form_validation->set_message("unique_date", "The to date are invalid .");
			    return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	public function date_valid_new($date) {
        if($date != '') {
		    $date = date('d-m-Y',$date);
			if(strlen($date) < 10) {
				$this->form_validation->set_message("date_valid_new", "The %s is not valid dd-mm-yyyy");
		     	return FALSE;
			} else {
		   		$arr = explode("-", $date);
		        $dd = $arr[0];
		        $mm = $arr[1];
		        $yyyy = $arr[2];
		      	if(checkdate($mm, $dd, $yyyy)) {
		      		return TRUE;
		      	} else {
		      		$this->form_validation->set_message("date_valid_new", "The %s is not valid dd-mm-yyyy");
		     		return FALSE;
		      	}
		    }
		}
		return TRUE;
	}

	public function unique_date_new() {
        $fromdate = $this->input->post('fromdate');
        $todate   = $this->input->post('todate');

        if((int)$fromdate && (int)$todate ) {
    		$fromdate = date('d-m-Y',$fromdate);
    		$todate   = date('d-m-Y',$todate);
        }

		$startingdate = $this->data['schoolyearsessionobj']->startingdate;
		$endingdate = $this->data['schoolyearsessionobj']->endingdate;

		if($fromdate != '' && $todate != '') {
			if(strtotime($fromdate) > strtotime($todate)) {
				$this->form_validation->set_message("unique_date_new", "The from date can not be upper than todate .");
		   		return FALSE;
			}
			if((strtotime($fromdate) < strtotime($startingdate)) || (strtotime($fromdate) > strtotime($endingdate))) {
				$this->form_validation->set_message("unique_date_new", "The from date is invalid .");
			    return FALSE;
			}
			if((strtotime($todate) < strtotime($startingdate)) || (strtotime($todate) > strtotime($endingdate))) {
				$this->form_validation->set_message("unique_date_new", "The to date is invalid .");
			    return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

    public function date_required_check() {
        $fromdate = $this->input->post('fromdate');
        $todate   = $this->input->post('todate');
        if($fromdate == '' && $todate != '') {
            $this->form_validation->set_message("date_required_check", "The from date field is required.");
            return FALSE;
        } elseif($fromdate != '' && $todate == '') {
            $this->form_validation->set_message("date_required_check", "The to date field is required.");
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function date_required_check_new() {
        $fromdate = $this->input->post('fromdate');
        $todate   = $this->input->post('todate');

        if((int)$fromdate && (int)$todate ) {
            $fromdate = date('d-m-Y',$fromdate);
            $todate   = date('d-m-Y',$todate);
        }

        if($fromdate == '' && $todate != '') {
            $this->form_validation->set_message("date_required_check", "The from date field is required.");
            return FALSE;
        } elseif($fromdate != '' && $todate == '') {
            $this->form_validation->set_message("date_required_check", "The to date field is required.");
            return FALSE;
        } else {
            return TRUE;
        }
    }
}