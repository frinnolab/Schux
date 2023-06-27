<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Onlineadmission extends Admin_Controller {
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
	function __construct () {
		parent::__construct();
		$this->load->model("student_m");
		$this->load->model("section_m");
		$this->load->model("classes_m");
		$this->load->model("onlineadmission_m");
		$this->load->model('studentrelation_m');
		$this->load->model('studentgroup_m');
		$this->load->model('studentextend_m');
		$this->load->model('subject_m');
		$this->load->model('schoolyear_m');
		$this->load->model('usertype_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('onlineadmission', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'schoolyearID',
				'label' => $this->lang->line("onlineadmission_schoolyear"),
				'rules' => 'trim|required|xss_clean|callback_unique_schoolyear'
			),
			array(
				'field' => 'name',
				'label' => $this->lang->line("onlineadmission_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'dob',
				'label' => $this->lang->line("onlineadmission_dob"),
				'rules' => 'trim|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'sex',
				'label' => $this->lang->line("onlineadmission_gender"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'bloodgroup',
				'label' => $this->lang->line("onlineadmission_bloodgroup"),
				'rules' => 'trim|max_length[5]|xss_clean'
			),
			array(
				'field' => 'religion',
				'label' => $this->lang->line("onlineadmission_religion"),
				'rules' => 'trim|max_length[25]|xss_clean'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("onlineadmission_email"),
				'rules' => 'trim|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("onlineadmission_phone"),
				'rules' => 'trim|max_length[25]|min_length[5]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("onlineadmission_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'state',
				'label' => $this->lang->line("onlineadmission_state"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),
			array(
				'field' => 'country',
				'label' => $this->lang->line("onlineadmission_country"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("onlineadmission_classes"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_classesID'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("onlineadmission_section"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_sectionID|callback_unique_capacity'
			),
			array(
				'field' => 'registerNO',
				'label' => $this->lang->line("onlineadmission_registerNO"),
				'rules' => 'trim|required|max_length[40]|callback_unique_registerNO|xss_clean'
			),
			array(
				'field' => 'roll',
				'label' => $this->lang->line("onlineadmission_roll"),
				'rules' => 'trim|required|max_length[11]|numeric|callback_unique_roll|xss_clean'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("onlineadmission_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),

            array(
                'field' => 'studentGroupID',
                'label' => $this->lang->line("onlineadmission_studentgroup"),
                'rules' => 'trim|max_length[11]|xss_clean|numeric'
            ),

            array(
                'field' => 'optionalSubjectID',
                'label' => $this->lang->line("onlineadmission_optionalsubject"),
                'rules' => 'trim|max_length[11]|xss_clean|numeric'
            ),

            array(
                'field' => 'extraCurricularActivities',
                'label' => $this->lang->line("onlineadmission_extracurricularactivities"),
                'rules' => 'trim|max_length[128]|xss_clean'
            ),

            array(
                'field' => 'remarks',
                'label' => $this->lang->line("onlineadmission_remarks"),
                'rules' => 'trim|max_length[128]|xss_clean'
            ),

			array(
				'field' => 'username',
				'label' => $this->lang->line("onlineadmission_username"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean|callback_lol_username'
			),
			array(
				'field' => 'password',
				'label' => $this->lang->line("onlineadmission_password"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
			)
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
                'field' => 'onlineadmissionID',
                'label' => $this->lang->line("onlineadmission_onlineadmission"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
            )
        );
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

        $this->data['classes'] = $this->classes_m->general_get_classes();
        $id = htmlentities(escapeString($this->uri->segment(3)));
        
        if((int)$id) {
            $this->data['classesID'] = $id;
            $this->data['onlineadmissions'] = $this->onlineadmission_m->get_where_in_onlineadmission(array(0, 2), 'status', array('classesID' => $id));
        } else {
            $this->data['onlineadmissions'] = [];
	        $this->data['classesID'] = 0;
        }
        $this->data["subview"] = "onlineadmission/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function view() {
        if(permissionChecker('onlineadmission')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $this->data['admissioninfo'] = $this->onlineadmission_m->get_where_in_onlineadmission(array(0, 2), 'status', array('onlineadmissionID' => $id));
                if(count($this->data['admissioninfo'])) {
                    $this->data['admissioninfo'] = $this->data['admissioninfo'][0];
                    $classesID = $this->data['admissioninfo']->classesID;
                    $this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
	                $this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => 3));

                    $this->data["subview"] = "onlineadmission/view";
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
		if(permissionChecker('onlineadmission')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
                $this->data['admissioninfo'] = $this->onlineadmission_m->get_where_in_onlineadmission(array(0, 2), 'status', array('onlineadmissionID' => $id));
                
                if(count($this->data['admissioninfo'])) {
                    $this->data['admissioninfo'] = $this->data['admissioninfo'][0];
	                $classesID = $this->data['admissioninfo']->classesID;
	                $this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
	                $this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID'=>3));

                    $this->reportPDF('onlineadmissionmodule.css',$this->data, 'onlineadmission/print_preview');
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
        if(permissionChecker('onlineadmission')) {
            if($_POST) {
                $rules = $this->send_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $id = $this->input->post('onlineadmissionID');
                    if((int)$id) {
                        $this->data['admissioninfo'] = $this->onlineadmission_m->get_where_in_onlineadmission(array(0, 2), 'status', array('onlineadmissionID' => $id));
                        if(count($this->data['admissioninfo'])) {
                            $this->data['admissioninfo'] = $this->data['admissioninfo'][0];
                            $classesID = $this->data['admissioninfo']->classesID;
                            $this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
                            $this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID'=>3));

                            $email = $this->input->post('to');
                            $subject = $this->input->post('subject');
                            $message = $this->input->post('message');
                            $this->reportSendToMail('onlineadmissionmodule.css', $this->data, 'onlineadmission/print_preview', $email, $subject, $message);
                            $retArray['message'] = "Success";
                            $retArray['status'] = TRUE;
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $$retArray['message'] = $this->lang->line('onlineadmission_data_not_found');
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('onlineadmission_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('onlineadmission_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('onlineadmission_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    public function approve() {
		if(permissionChecker('onlineadmission')) {
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
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $url = htmlentities(escapeString($this->uri->segment(4)));
			if((int)$id && (int)$url) {
                $this->data['admissioninfo'] = $this->onlineadmission_m->get_where_in_onlineadmission(array(0, 2), 'status', array('onlineadmissionID' => $id));
                if(count($this->data['admissioninfo'])) {
                    $this->data['admissioninfo'] = $this->data['admissioninfo'][0];
	                $classesID = $this->data['admissioninfo']->classesID;
	                $this->data['classes'] = $this->classes_m->general_get_classes();
	                $this->data['studentgroups'] = $this->studentgroup_m->get_studentgroup();
	                $this->data['schoolyears'] = $this->schoolyear_m->get_schoolyear();

	                if((int)$this->input->post("classesID")) {
	                	$classesID = $this->input->post("classesID");
	                    $this->data['sections'] = $this->section_m->general_get_order_by_section(array("classesID" =>$classesID));
	                    $this->data['optionalSubjects'] = $this->subject_m->general_get_order_by_subject(array("classesID" =>$classesID, 'type' => 0));
	                } elseif((int)$classesID) {
	                    $this->data['sections'] = $this->section_m->general_get_order_by_section(array("classesID" =>$classesID));
	                    $this->data['optionalSubjects'] = $this->subject_m->general_get_order_by_subject(array("classesID" =>$classesID, 'type' => 0));
	                } else {
	                    $this->data['sections'] = [];
	                    $this->data['optionalSubjects'] = [];
	                }

                    if($_POST) {
                        $rules = $this->rules();
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                            $this->data["subview"] = "onlineadmission/approved";
                            $this->load->view('_layout_main', $this->data);
                        } else {
                            $array["name"] = $this->input->post("name");
                            if(!empty($this->input->post('dob'))) {
                                $array["dob"] 		= date("Y-m-d", strtotime($this->input->post("dob")));
                            }
                            $array["sex"] = $this->input->post("sex");
                            $array["religion"] = $this->input->post("religion");
                            $array["email"] = $this->input->post("email");
                            $array["phone"] = $this->input->post("phone");
                            $array["address"] = $this->input->post("address");
                            $array["classesID"] = $this->input->post("classesID");
                            $array["sectionID"] = $this->input->post("sectionID");
                            $array["roll"] = $this->input->post("roll");
                            $array["bloodgroup"] = $this->input->post("bloodgroup");
                            $array["country"] = $this->input->post("country");
                            $array["registerNO"] = $this->input->post("registerNO");
                            $array["state"] = $this->input->post("state");
                            $array['library'] = 0;
                            $array['hostel'] = 0;
                            $array['transport'] = 0;
                            $array['parentID'] = 0;
                            $array['photo'] = $this->upload_data['file']['file_name'];
                            $array['createschoolyearID'] = $this->input->post('schoolyearID');
                            $array['schoolyearID'] = $this->input->post('schoolyearID');
                            $array["username"] = $this->input->post("username");
                            $array['password'] = $this->student_m->hash($this->input->post("password"));
                            $array['usertypeID'] = 3;
                            $array["create_date"] = date("Y-m-d h:i:s");
                            $array["modify_date"] = date("Y-m-d h:i:s");
                            $array["create_userID"] = $this->session->userdata('loginuserID');
                            $array["create_username"] = $this->session->userdata('username');
                            $array["create_usertype"] = $this->session->userdata('usertype');
                            $array["active"] = 1;

                            $this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));

                            $this->student_m->insert_student($array);
                            $studentID = $this->db->insert_id();

                            $classes = $this->classes_m ->general_get_single_classes(array('classesID' => $this->input->post("classesID")));
                            $section = $this->section_m->general_get_single_section(array('sectionID' => $this->input->post("sectionID")));

                            if(count($classes)) {
                                $setClasses = $classes->classes;
                            } else {
                                $setClasses = NULL;
                            }

                            if(count($section)) {
                                $setSection = $section->section;
                            } else {
                                $setSection = NULL;
                            }

                            $arrayStudentRelation = array(
                                'srstudentID' => $studentID,
                                'srname' => $this->input->post("name"),
                                'srclassesID' => $this->input->post("classesID"),
                                'srclasses' => $setClasses,
                                'srroll' => $this->input->post("roll"),
                                'srregisterNO' => $this->input->post("registerNO"),
                                'srsectionID' => $this->input->post("sectionID"),
                                'srsection' => $setSection,
                                'srstudentgroupID' => $this->input->post('studentGroupID'),
                                'sroptionalsubjectID' => $this->input->post('optionalSubjectID'),
                                'srschoolyearID' => $this->input->post('schoolyearID')
                            );

                            $studentExtendArray = array(
                                'studentID' => $studentID,
                                'studentgroupID' => $this->input->post('studentGroupID'),
                                'optionalsubjectID' => $this->input->post('optionalSubjectID'),
                                'extracurricularactivities' => $this->input->post('extraCurricularActivities'),
                                'remarks' => $this->input->post('remarks')
                            );

                            $this->studentextend_m->insert_studentextend($studentExtendArray);
                            $this->studentrelation_m->insert_studentrelation($arrayStudentRelation);

                            $this->onlineadmission_m->update_onlineadmission(array('status' => 1, 'studentID' => $studentID), $id);
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("onlineadmission/index/".$url));
                        }
                    } else {
                        $this->data["subview"] = "onlineadmission/approved";
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

	public function decline() {
        if(permissionChecker('onlineadmission')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $url = htmlentities(escapeString($this->uri->segment(4)));
            if((int)$id && (int)$url) {
                $onlineadmission = $this->onlineadmission_m->get_where_in_onlineadmission(array(0, 2), 'status', array('onlineadmissionID' => $id));
                if(count($onlineadmission)) {
                    $this->onlineadmission_m->update_onlineadmission(array('status' => 3), $id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("onlineadmission/index/".$url));
                }  else {
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

    public function waiting() {
        if(permissionChecker('onlineadmission')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			if((int)$id && (int)$url) {
                $onlineadmission = $this->onlineadmission_m->get_where_in_onlineadmission(array(0, 2), 'status', array('onlineadmissionID' => $id));
                if(count($onlineadmission)) {
                    $this->onlineadmission_m->update_onlineadmission(array('status' => 2), $id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("onlineadmission/index/".$url));
                }  else {
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

	public function photoupload() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$onlineadmission = array();
		if((int)$id) {
			$onlineadmission = $this->onlineadmission_m->get_single_onlineadmission(array('onlineadmissionID'=>$id));
		}

		$new_file = "default.png";
		if($_FILES["photo"]['name'] !="") {
			$file_name = $_FILES["photo"]['name'];
			$random = rand(1, 1000000000);
	    	$makeRandom = hash('sha512', $random.$this->input->post('username') . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png";
				$config['file_name'] = $new_file;
				$config['max_size'] = '1024';
				$config['max_width'] = '3000';
				$config['max_height'] = '3000';
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("photo")) {
					$this->form_validation->set_message("photoupload", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("photoupload", "Invalid file");
	     		return FALSE;
			}
		} else {
			if(count($onlineadmission) && isset($onlineadmission->photo)) {
				$this->upload_data['file'] = array('file_name' => $onlineadmission->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
				return TRUE;
			}
		}
	}

    public function unique_roll() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $schoolyearID = $this->input->post('schoolyearID');
        if((int)$id) {
            $student = $this->studentrelation_m->general_get_order_by_student(array("srroll" => $this->input->post("roll"), "srclassesID" => $this->input->post('classesID'), 'srschoolyearID' => $schoolyearID));
			if(count($student)) {
				$this->form_validation->set_message("unique_roll", "The %s is already exists.");
				return FALSE;
			}
			return TRUE;
        }
    }

    public function lol_username() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
            $array = array();
            $i = 0;
            foreach ($tables as $table) {
                $user = $this->student_m->get_username($table, array("username" => $this->input->post('username')));
                if(count($user)) {
                    $this->form_validation->set_message("lol_username", "%s already exists");
                    $array['permition'][$i] = 'no';
                } else {
                    $array['permition'][$i] = 'yes';
                }
                $i++;
            }

            if(in_array('no', $array['permition'])) {
                return FALSE;
            } else {
                return TRUE;
            }
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
    
    public function unique_classesID() {
        if($this->input->post('classesID') == 0) {
            $this->form_validation->set_message("unique_classesID", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_sectionID() {
        if($this->input->post('sectionID') == 0) {
            $this->form_validation->set_message("unique_sectionID", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_schoolyear() {
        if($this->input->post('schoolyearID') == 0) {
            $this->form_validation->set_message("unique_schoolyear", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }
	
	public function unique_email() {
		if($this->input->post('email')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email')));
					if(count($user)) {
						$this->form_validation->set_message("unique_email", "%s already exists");
						$array['permition'][$i] = 'no';
					} else {
						$array['permition'][$i] = 'yes';
					}
					$i++;
				}

				if(in_array('no', $array['permition'])) {
					return FALSE;
				} else {
					return TRUE;
				}
			}
		}
		return TRUE;
	}

	public function sectioncall() {
		$classesID = $this->input->post('id');
		if((int)$classesID) {
			$allsection = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("onlineadmission_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
		}
	}

    public function optionalsubjectcall() {
        $classesID = $this->input->post('id');
        if((int)$classesID) {
            $allOptionalSubjects = $this->subject_m->general_get_order_by_subject(array("classesID" =>$classesID, 'type' => 0));
            echo "<option value='0'>", $this->lang->line("onlineadmission_select_optionalsubject"),"</option>";
            foreach ($allOptionalSubjects as $value) {
                echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
            }
        }
    }

	public function unique_capacity() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			if($this->input->post('sectionID')) {
				$sectionID = $this->input->post('sectionID');
				$classesID = $this->input->post('classesID');
				$schoolyearID = $this->input->post('schoolyearID');
				$section = $this->section_m->general_get_section($this->input->post('sectionID'));
				$student = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID));
				if(count($student) >= $section->capacity) {
					$this->form_validation->set_message("unique_capacity", "The %s capacity is full.");
		     		return FALSE;
				}
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_capacity", "The %s field is required.");
		     	return FALSE;
			}
		}
	}

	public function unique_registerNO() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$schoolyearID = $this->input->post('schoolyearID');
		if((int)$id) {
			$student = $this->studentrelation_m->general_get_single_student(array("srregisterNO" => $this->input->post("registerNO")));
			if(count($student)) {
				$this->form_validation->set_message("unique_registerNO", "The %s is already exists.");
				return FALSE;
			}
			return TRUE;
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
}