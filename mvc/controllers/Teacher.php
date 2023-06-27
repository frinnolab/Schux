<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacher extends Admin_Controller {
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
        $this->load->model("teacher_m");
        $this->load->model("routine_m");
        $this->load->model("subject_m");
        $this->load->model("classes_m");
        $this->load->model("section_m");
        $this->load->model("tattendance_m");
        $this->load->model("manage_salary_m");
        $this->load->model("salaryoption_m");
        $this->load->model("salary_template_m");
        $this->load->model("hourly_template_m");
        $this->load->model("make_payment_m");
        $this->load->model("document_m");
        $this->load->model("leaveapplication_m");
        $this->load->library('updatechecker');
        $language = $this->session->userdata('lang');
        $this->lang->load('teacher', $language);
    }

    protected function rules()
    {
        $rules = [
            [
                'field' => 'name',
                'label' => $this->lang->line("teacher_name"),
                'rules' => 'trim|required|xss_clean|max_length[60]'
            ],
            [
                'field' => 'designation',
                'label' => $this->lang->line("teacher_designation"),
                'rules' => 'trim|required|max_length[128]|xss_clean'
            ],
            [
                'field' => 'dob',
                'label' => $this->lang->line("teacher_dob"),
                'rules' => 'trim|required|max_length[10]|callback_date_valid|xss_clean'
            ],
            [
                'field' => 'sex',
                'label' => $this->lang->line("teacher_sex"),
                'rules' => 'trim|required|max_length[10]|xss_clean'
            ],
            [
                'field' => 'religion',
                'label' => $this->lang->line("teacher_religion"),
                'rules' => 'trim|max_length[25]|xss_clean'
            ],
            [
                'field' => 'email',
                'label' => $this->lang->line("teacher_email"),
                'rules' => 'trim|required|max_length[40]|valid_email|xss_clean|callback_unique_email'
            ],
            [
                'field' => 'phone',
                'label' => $this->lang->line("teacher_phone"),
                'rules' => 'trim|required|min_length[5]|max_length[25]|xss_clean'
            ],
            [
                'field' => 'alternate_phone',
                'label' => $this->lang->line("teacher_alternate_phone"),
                'rules' => 'trim|min_length[5]|max_length[25]|xss_clean'
            ],
            [
                'field' => 'address',
                'label' => $this->lang->line("teacher_address"),
                'rules' => 'trim|max_length[200]|xss_clean'
            ],
			[
				'field' => 'country',
				'label' => $this->lang->line("teacher_country"),
				'rules' => 'trim|required|max_length[128]|xss_clean'
            ],
            [
                'field' => 'jod',
                'label' => $this->lang->line("teacher_jod"),
                'rules' => 'trim|required|max_length[10]|callback_date_valid|xss_clean'
            ],
            [
                'field' => 'photo',
                'label' => $this->lang->line("teacher_photo"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
            ],
            [
                'field' => 'username',
                'label' => $this->lang->line("teacher_username"),
                'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean|callback_lol_username'
            ],
            [
                'field' => 'password',
                'label' => $this->lang->line("teacher_password"),
                'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
            ]
        ];
        return $rules;
    }

    public function send_mail_rules()
    {
        $rules = [
            [
                'field' => 'to',
                'label' => $this->lang->line("teacher_to"),
                'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
            ],
            [
                'field' => 'subject',
                'label' => $this->lang->line("teacher_subject"),
                'rules' => 'trim|required|xss_clean'
            ],
            [
                'field' => 'message',
                'label' => $this->lang->line("teacher_message"),
                'rules' => 'trim|xss_clean'
            ],
            [
                'field' => 'teacherID',
                'label' => $this->lang->line("teacher_teacherID"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
            ]
        ];
        return $rules;
    }

    public function unique_data( $data )
    {
        if ( $data != '' ) {
            if ( $data == '0' ) {
                $this->form_validation->set_message('unique_data', 'The %s field is required.');
                return false;
            }
            return true;
        }
        return true;
    }

    public function photoupload()
    {
        $id   = htmlentities(escapeString($this->uri->segment(3)));
        $user = [];
        if ( (int) $id ) {
            $user = $this->teacher_m->get_single_teacher([ 'teacherID' => $id ]);
        }

        $new_file = "default.png";
        if ( $_FILES["photo"]['name'] != "" ) {
            $file_name        = $_FILES["photo"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512',
                $random . $this->input->post('username') . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if ( count($explode) >= 2 ) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name']     = $new_file;
                $config['max_size']      = '1024';
                $config['max_width']     = '3000';
                $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("photo") ) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['file'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return false;
            }
        } else {
            if ( count($user) ) {
                $this->upload_data['file'] = [ 'file_name' => $user->photo ];
                return true;
            } else {
                $this->upload_data['file'] = [ 'file_name' => $new_file ];
                return true;
            }
        }
    }

    public function index()
    {
        $myProfile = false;
        if ( $this->session->userdata('usertypeID') == 2 ) {
            if ( !permissionChecker('teacher_view') ) {
                $myProfile = true;
            }
        }

        if ( $this->session->userdata('usertypeID') == 2 && $myProfile ) {
            $id = $this->session->userdata('loginuserID');
            $this->getView($id);
        } else {
            $this->data['teachers'] = $this->teacher_m->get_teacher();
            $this->data["subview"]  = "teacher/index";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function add()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/datepicker/datepicker.css'
            ],
            'js'  => [
                'assets/datepicker/datepicker.js'
            ]
        ];
        if ( $_POST ) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ( $this->form_validation->run() == false ) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"]         = "teacher/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                if ( config_item('demo') == false ) {
                    $updateValidation = $this->updatechecker->verifyValidUser();
                    if ( $updateValidation->status == false ) {
                        $this->session->set_flashdata('error', $updateValidation->message);
                        redirect(base_url('teacher/add'));
                    }
                }

                $array['name']            = $this->input->post("name");
                $array['designation']     = $this->input->post("designation");
                $array["dob"]             = date("Y-m-d", strtotime($this->input->post("dob")));
                $array["sex"]             = $this->input->post("sex");
                $array['religion']        = $this->input->post("religion");
                $array['email']           = $this->input->post("email");
                $array['phone']           = $this->input->post("phone");
                $array['alternate_phone'] = $this->input->post("alternate_phone");
                $array['address']         = $this->input->post("address");
                $array["country"]         = $this->input->post("country");
                $array['jod']             = date("Y-m-d", strtotime($this->input->post("jod")));
                $array['username']        = $this->input->post("username");
                $array['password']        = $this->teacher_m->hash($this->input->post("password"));
                $array['usertypeID']      = 2;
                $array["create_date"]     = date("Y-m-d h:i:s");
                $array["modify_date"]     = date("Y-m-d h:i:s");
                $array["create_userID"]   = $this->session->userdata('loginuserID');
                $array["create_username"] = $this->session->userdata('username');
                $array["create_usertype"] = $this->session->userdata('usertype');
                $array["active"]          = 1;
                $array['photo']           = $this->upload_data['file']['file_name'];

                $this->usercreatemail($this->input->post('email'), $this->input->post('username'),
                    $this->input->post('password'));

                $this->teacher_m->insert_teacher($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("teacher/index"));
            }
        } else {
            $this->data["subview"] = "teacher/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/datepicker/datepicker.css'
            ],
            'js'  => [
                'assets/datepicker/datepicker.js'
            ]
        ];
        $id                         = htmlentities(escapeString($this->uri->segment(3)));
        if ( (int) $id ) {
            $this->data['teacher'] = $this->teacher_m->get_single_teacher([ 'teacherID' => $id ]);
            if ( $this->data['teacher'] ) {
                if ( $_POST ) {
                    $rules = $this->rules();
                    unset($rules[13]);
                    $this->form_validation->set_rules($rules);
                    if ( $this->form_validation->run() == false ) {
                        $this->data["subview"] = "teacher/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array                = [];
                        $array['name']        = $this->input->post("name");
                        $array['designation'] = $this->input->post("designation");
                        $array["dob"]         = date("Y-m-d", strtotime($this->input->post("dob")));
                        $array["sex"]         = $this->input->post("sex");
                        $array['religion']    = $this->input->post("religion");
                        $array['email']       = $this->input->post("email");
                        $array['phone']       = $this->input->post("phone");
                        $array['alternate_phone'] = $this->input->post("alternate_phone");
                        $array['address']     = $this->input->post("address");
                        $array["country"]     = $this->input->post("country");
                        $array['jod']         = date("Y-m-d", strtotime($this->input->post("jod")));
                        $array['username']    = $this->input->post('username');
                        $array["modify_date"] = date("Y-m-d h:i:s");
                        $array['photo']       = $this->upload_data['file']['file_name'];
                        
                        $this->teacher_m->update_teacher($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("teacher/index"));
                    }
                } else {
                    $this->data["subview"] = "teacher/edit";
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
            $this->data['teacher'] = $this->teacher_m->get_single_teacher([ 'teacherID' => $id ]);
            if ( $this->data['teacher'] ) {
                if ( config_item('demo') == false ) {
                    if ( $this->data['teacher']->photo != 'default.png' && $this->data['teacher']->photo != 'defualt.png' ) {
                        if ( file_exists(FCPATH . 'uploads/images/' . $this->data['teacher']->photo) ) {
                            unlink(FCPATH . 'uploads/images/' . $this->data['teacher']->photo);
                        }
                    }
                }
                $this->teacher_m->delete_teacher($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("teacher/index"));
            } else {
                redirect(base_url("teacher/index"));
            }
        } else {
            redirect(base_url("teacher/index"));
        }
    }

    public function view()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/custom-scrollbar/jquery.mCustomScrollbar.css',
                'assets/datepicker/datepicker.css',
            ],
            'js'  => [
                'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
                'assets/datepicker/datepicker.js',
            ]
        ];
        $usertype                   = $this->session->userdata('usertype');
        if ( $usertype ) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if ( (int) $id ) {
                $this->getView($id);
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function getView( $teacherID )
    {
        if ( (int) $teacherID ) {
            $teacherinfo = $this->teacher_m->get_single_teacher([ 'teacherID' => $teacherID ]);
            $this->pluckInfo();
            $this->teacherInfo($teacherinfo);
            $this->routineInfo($teacherinfo);
            $this->attendanceInfo($teacherinfo);
            $this->salaryInfo($teacherinfo);
            $this->paymentInfo($teacherinfo);
            $this->documentInfo($teacherinfo);

            if ( count($teacherinfo) ) {
                $schoolyearID                    = $this->session->userdata('defaultschoolyearID');
                $this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear($teacherID,
                    $schoolyearID, $teacherinfo->usertypeID);
                $this->data["subview"]           = "teacher/getView";
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

    private function pluckInfo()
    {
        $this->data['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
        $this->data['classess'] = pluck($this->classes_m->get_classes(), 'classes', 'classesID');
        $this->data['sections'] = pluck($this->section_m->get_section(), 'section', 'sectionID');
    }

    private function teacherinfo( $teacherinfo )
    {
        if ( count($teacherinfo) ) {
            $this->data['profile'] = $teacherinfo;
        } else {
            $this->data['profile'] = [];
        }
    }

    private function routineInfo( $teacherinfo )
    {
        $settingWeekends = [];
        if ( $this->data['siteinfos']->weekends != '' ) {
            $settingWeekends = explode(',', $this->data['siteinfos']->weekends);
        }
        $this->data['routineweekends'] = $settingWeekends;

        $this->data['routines'] = [];
        if ( count($teacherinfo) ) {
            $schoolyearID           = $this->session->userdata('defaultschoolyearID');
            $this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine([ 'teacherID'    => $teacherinfo->teacherID,
                                                                                                 'schoolyearID' => $schoolyearID
            ]), 'obj', 'day');
        }
    }

    private function attendanceInfo( $teacherinfo )
    {
        $this->data['holidays']       = $this->getHolidaysSession();
        $this->data['getWeekendDays'] = $this->getWeekendDaysSession();
        $schoolyearID                 = $this->session->userdata('defaultschoolyearID');
        if ( count($teacherinfo) ) {
            $tattendances                   = $this->tattendance_m->get_order_by_tattendance([
                "teacherID"    => $teacherinfo->teacherID,
                'schoolyearID' => $schoolyearID
            ]);
            $this->data['attendancesArray'] = pluck($tattendances, 'obj', 'monthyear');
        } else {
            $this->data['attendancesArray'] = [];
        }
    }

    private function salaryInfo( $teacherinfo )
    {
        if ( count($teacherinfo) ) {
            $manageSalary = $this->manage_salary_m->get_single_manage_salary([
                'usertypeID' => $teacherinfo->usertypeID,
                'userID'     => $teacherinfo->teacherID
            ]);
            if ( count($manageSalary) ) {
                $this->data['manage_salary'] = $manageSalary;
                if ( $manageSalary->salary == 1 ) {
                    $this->data['salary_template'] = $this->salary_template_m->get_single_salary_template([ 'salary_templateID' => $manageSalary->template ]);
                    if ( $this->data['salary_template'] ) {
                        $this->db->order_by("salary_optionID", "asc");
                        $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption([ 'salary_templateID' => $manageSalary->template ]);

                        $grosssalary        = 0;
                        $totaldeduction     = 0;
                        $netsalary          = $this->data['salary_template']->basic_salary;
                        $orginalNetsalary   = $this->data['salary_template']->basic_salary;
                        $grosssalarylist    = [];
                        $totaldeductionlist = [];

                        if ( count($this->data['salaryoptions']) ) {
                            foreach ( $this->data['salaryoptions'] as $salaryOptionKey => $salaryOption ) {
                                if ( $salaryOption->option_type == 1 ) {
                                    $netsalary                                    += $salaryOption->label_amount;
                                    $grosssalary                                  += $salaryOption->label_amount;
                                    $grosssalarylist[ $salaryOption->label_name ] = $salaryOption->label_amount;
                                } elseif ( $salaryOption->option_type == 2 ) {
                                    $netsalary                                       -= $salaryOption->label_amount;
                                    $totaldeduction                                  += $salaryOption->label_amount;
                                    $totaldeductionlist[ $salaryOption->label_name ] = $salaryOption->label_amount;
                                }
                            }
                        }

                        $this->data['grosssalary']    = ( $orginalNetsalary + $grosssalary );
                        $this->data['totaldeduction'] = $totaldeduction;
                        $this->data['netsalary']      = $netsalary;
                    } else {
                        $this->data['salary_template'] = [];
                        $this->data['salaryoptions']   = [];
                        $this->data['grosssalary']     = 0;
                        $this->data['totaldeduction']  = 0;
                        $this->data['netsalary']       = 0;
                    }
                } elseif ( $manageSalary->salary == 2 ) {
                    $this->data['hourly_salary'] = $this->hourly_template_m->get_single_hourly_template([ 'hourly_templateID' => $manageSalary->template ]);
                    if ( count($this->data['hourly_salary']) ) {
                        $this->data['grosssalary']    = 0;
                        $this->data['totaldeduction'] = 0;
                        $this->data['netsalary']      = $this->data['hourly_salary']->hourly_rate;
                    } else {
                        $this->data['hourly_salary']  = [];
                        $this->data['grosssalary']    = 0;
                        $this->data['totaldeduction'] = 0;
                        $this->data['netsalary']      = 0;
                    }
                }
            } else {
                $this->data['manage_salary']   = [];
                $this->data['salary_template'] = [];
                $this->data['salaryoptions']   = [];
                $this->data['hourly_salary']   = [];
                $this->data['grosssalary']     = 0;
                $this->data['totaldeduction']  = 0;
                $this->data['netsalary']       = 0;
            }
        } else {
            $this->data['manage_salary']   = [];
            $this->data['salary_template'] = [];
            $this->data['salaryoptions']   = [];
            $this->data['hourly_salary']   = [];
            $this->data['grosssalary']     = 0;
            $this->data['totaldeduction']  = 0;
            $this->data['netsalary']       = 0;
        }
    }

    private function paymentInfo( $teacherinfo )
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if ( count($teacherinfo) ) {
            $this->data['make_payments'] = $this->make_payment_m->get_order_by_make_payment([
                'usertypeID'   => $teacherinfo->usertypeID,
                'userID'       => $teacherinfo->teacherID,
                'schoolyearID' => $schoolyearID
            ]);
        } else {
            $this->data['make_payments'] = [];
        }
    }

    private function documentInfo( $teacherinfo )
    {
        if ( count($teacherinfo) ) {
            $this->data['documents'] = $this->document_m->get_order_by_document([
                'usertypeID' => 2,
                'userID'     => $teacherinfo->teacherID
            ]);
        } else {
            $this->data['documents'] = [];
        }
    }

    public function documentUpload()
    {
        $retArray['status'] = false;
        $retArray['render'] = '';
        $retArray['errors'] = '';

        if ( permissionChecker('teacher_add') ) {
            if ( $_POST ) {
                $rules = $this->rules_documentupload();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $retArray['errors'] = $this->form_validation->error_array();
                    $retArray['status'] = false;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $title  = $this->input->post('title');
                    $description  = $this->input->post('description');
                    $type  = $this->input->post('type');
                    if($this->input->post('expiration_date')) {
                        $expiration_date  = date("Y-m-d", strtotime($this->input->post('expiration_date')));
                    }
                    if($this->input->post('date_of_issue')) {
                        $date_of_issue  = date("Y-m-d", strtotime($this->input->post('date_of_issue')));
                    }
                    $file   = $this->upload_data['file']['file_name'];
                    $userID = $this->input->post('teacherID');

                    $array = [
                        'title'             => $title,
                        'description'       => $description,
                        'type'              => $type,
                        'expiration_date'   => $expiration_date,
                        'date_of_issue'     => $date_of_issue,
                        'file'              => $file,
                        'userID'            => $userID,
                        'usertypeID'        => 2,
                        "create_date"       => date("Y-m-d H:i:s"),
                        "create_userID"     => $this->session->userdata('loginuserID'),
                        "create_usertypeID" => $this->session->userdata('usertypeID')
                    ];

                    $this->document_m->insert_document($array);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));

                    $retArray['status'] = true;
                    $retArray['render'] = 'Success';
                    echo json_encode($retArray);
                    exit;
                }
            } else {
                $retArray['status'] = false;
                $retArray['render'] = 'Error';
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['status'] = false;
            $retArray['render'] = 'Permission Denay.';
            echo json_encode($retArray);
            exit;
        }
    }

    public function download_document()
    {
        $documentID = htmlentities(escapeString($this->uri->segment(3)));
        $teacherID  = htmlentities(escapeString($this->uri->segment(4)));
        if ( (int) $documentID && (int) $teacherID ) {
            if ( ( permissionChecker('teacher_add') && permissionChecker('teacher_delete') ) || ( $this->session->userdata('usertypeID') == 2 && $this->session->userdata('loginuserID') == $teacherID ) ) {
                $document = $this->document_m->get_single_document([ 'documentID' => $documentID ]);
                if ( count($document) ) {
                    $file = realpath('uploads/documents/' . $document->file);
                    if ( file_exists($file) ) {
                        $expFileName  = explode('.', $file);
                        $originalname = ( $document->title ) . '.' . end($expFileName);
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="' . basename($originalname) . '"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($file));
                        readfile($file);
                        exit;
                    } else {
                        redirect(base_url('teacher/view/' . $teacherID));
                    }
                } else {
                    redirect(base_url('teacher/view/' . $teacherID));
                }
            } else {
                redirect(base_url('teacher/view/' . $teacherID));
            }
        } else {
            redirect(base_url('teacher/index'));
        }
    }

    public function delete_document()
    {
        $documentID = htmlentities(escapeString($this->uri->segment(3)));
        $teacherID  = htmlentities(escapeString($this->uri->segment(4)));
        if ( (int) $documentID && (int) $teacherID ) {
            if ( permissionChecker('teacher_add') && permissionChecker('teacher_delete') ) {
                $document = $this->document_m->get_single_document([ 'documentID' => $documentID ]);
                if ( count($document) ) {
                    if ( config_item('demo') == false ) {
                        if ( file_exists(FCPATH . 'uploads/document/' . $document->file) ) {
                            unlink(FCPATH . 'uploads/document/' . $document->file);
                        }
                    }
                    $this->document_m->delete_document($documentID);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url('teacher/view/' . $teacherID));
                } else {
                    redirect(base_url('teacher/view/' . $teacherID));
                }
            } else {
                redirect(base_url('teacher/view/' . $teacherID));
            }
        } else {
            redirect(base_url('teacher/index'));
        }
    }

    protected function rules_documentupload()
    {
        $rules = [
            [
                'field' => 'title',
                'label' => $this->lang->line("teacher_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ],
            [
                'field' => 'file',
                'label' => $this->lang->line("teacher_file"),
                'rules' => 'trim|xss_clean|max_length[200]|callback_unique_document_upload'
            ]
        ];
        return $rules;
    }

    public function unique_document_upload()
    {
        if ( $_FILES["file"]['name'] != "" ) {
            $file_name        = $_FILES["file"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512',
                $random . ( strtotime(date('Y-m-d H:i:s')) ) . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if ( count($explode) >= 2 ) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/documents";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name']     = $new_file;
                $config['max_size']      = '5120';
                $config['max_width']     = '10000';
                $config['max_height']    = '10000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("file") ) {
                    $this->form_validation->set_message("unique_document_upload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['file'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("unique_document_upload", "Invalid file");
                return false;
            }
        } else {
            $this->form_validation->set_message("unique_document_upload", "The file is required.");
            return false;
        }
    }

    public function lol_username()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ( (int) $id ) {
            $teacher_info = $this->teacher_m->get_single_teacher([ 'teacherID' => $id ]);
            $tables = [
                'student'     => 'student',
                'parents'     => 'parents',
                'teacher'     => 'teacher',
                'user'        => 'user',
                'systemadmin' => 'systemadmin'
            ];
            $array        = [];
            $i            = 0;
            foreach ( $tables as $table ) {
                $user = $this->teacher_m->get_username($table,
                    [ "username" => $this->input->post('username'), "username !=" => $teacher_info->username ]);
                if ( count($user) ) {
                    $this->form_validation->set_message("lol_username", "%s already exists");
                    $array['permition'][ $i ] = 'no';
                } else {
                    $array['permition'][ $i ] = 'yes';
                }
                $i++;
            }
            if ( in_array('no', $array['permition']) ) {
                return false;
            } else {
                return true;
            }
        } else {
            $tables = [
                'student'     => 'student',
                'parents'     => 'parents',
                'teacher'     => 'teacher',
                'user'        => 'user',
                'systemadmin' => 'systemadmin'
            ];
            $array  = [];
            $i      = 0;
            foreach ( $tables as $table ) {
                $user = $this->teacher_m->get_username($table, [ "username" => $this->input->post('username') ]);
                if ( count($user) ) {
                    $this->form_validation->set_message("lol_username", "%s already exists");
                    $array['permition'][ $i ] = 'no';
                } else {
                    $array['permition'][ $i ] = 'yes';
                }
                $i++;
            }

            if ( in_array('no', $array['permition']) ) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function date_valid( $date )
    {
        if ( strlen($date) < 10 ) {
            $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
            return false;
        } else {
            $arr  = explode("-", $date);
            $dd   = $arr[0];
            $mm   = $arr[1];
            $yyyy = $arr[2];
            if ( checkdate($mm, $dd, $yyyy) ) {
                return true;
            } else {
                $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                return false;
            }
        }
    }

    public function print_preview()
    {
        if ( permissionChecker('teacher_view') || ( ( $this->session->userdata('usertypeID') == 2 ) && permissionChecker('teacher') && ( $this->session->userdata('loginuserID') == htmlentities(escapeString($this->uri->segment(3))) ) ) ) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if ( (int) $id ) {
                $this->data["teacher"] = $this->teacher_m->get_single_teacher([ 'teacherID' => $id ]);
                if ( count($this->data["teacher"]) ) {
                    $this->data['panel_title'] = $this->lang->line('panel_title');
                    $this->reportPDF('teachermodule.css', $this->data, 'teacher/print_preview');
                } else {
                    $this->data["subview"] = "error";
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "errorpermission";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function send_mail()
    {
        $retArray['status']  = false;
        $retArray['message'] = '';
        if ( permissionChecker('teacher_view') || ( ( $this->session->userdata('usertypeID') == 2 ) && permissionChecker('teacher') && ( $this->session->userdata('loginuserID') == $this->input->post('teacherID') ) ) ) {
            if ( $_POST ) {
                $rules = $this->send_mail_rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $retArray           = $this->form_validation->error_array();
                    $retArray['status'] = false;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $teacherID = $this->input->post('teacherID');
                    if ( (int) $teacherID ) {
                        $this->data["teacher"] = $this->teacher_m->get_single_teacher([ 'teacherID' => $teacherID ]);
                        if ( $this->data["teacher"] ) {
                            $email   = $this->input->post('to');
                            $subject = $this->input->post('subject');
                            $message = $this->input->post('message');
                            $this->reportSendToMail('teachermodule.css', $this->data, 'teacher/print_preview', $email,
                                $subject, $message);
                            $retArray['message'] = "Message";
                            $retArray['status']  = true;
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $retArray['message'] = $this->lang->line('teacher_data_not_found');
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('teacher_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('teacher_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('teacher_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    public function unique_email()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ( (int) $id ) {
            $teacher_info = $this->teacher_m->get_single_teacher([ 'teacherID' => $id ]);
            $tables = [
                'student'     => 'student',
                'parents'     => 'parents',
                'teacher'     => 'teacher',
                'user'        => 'user',
                'systemadmin' => 'systemadmin'
            ];
            $array        = [];
            $i            = 0;
            foreach ( $tables as $table ) {
                $user = $this->teacher_m->get_username($table,
                    [ "email" => $this->input->post('email'), 'username !=' => $teacher_info->username ]);
                if ( count($user) ) {
                    $this->form_validation->set_message("unique_email", "%s already exists");
                    $array['permition'][ $i ] = 'no';
                } else {
                    $array['permition'][ $i ] = 'yes';
                }
                $i++;
            }
            if ( in_array('no', $array['permition']) ) {
                return false;
            } else {
                return true;
            }
        } else {
            $tables = [
                'student'     => 'student',
                'parents'     => 'parents',
                'teacher'     => 'teacher',
                'user'        => 'user',
                'systemadmin' => 'systemadmin'
            ];
            $array  = [];
            $i      = 0;
            foreach ( $tables as $table ) {
                $user = $this->teacher_m->get_username($table, [ "email" => $this->input->post('email') ]);
                if ( count($user) ) {
                    $this->form_validation->set_message("unique_email", "%s already exists");
                    $array['permition'][ $i ] = 'no';
                } else {
                    $array['permition'][ $i ] = 'yes';
                }
                $i++;
            }

            if ( in_array('no', $array['permition']) ) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function active()
    {
        if ( permissionChecker('teacher_edit') ) {
            $id     = $this->input->post('id');
            $status = $this->input->post('status');
            if ( $id != '' && $status != '' ) {
                if ( (int) $id ) {
                    $teacher = $this->teacher_m->get_single_teacher([ 'teacherID' => $id ]);
                    if ( count($teacher) ) {
                        if ( $status == 'chacked' ) {
                            $this->teacher_m->update_teacher([ 'active' => 1 ], $id);
                            echo 'Success';
                        } elseif ( $status == 'unchacked' ) {
                            $this->teacher_m->update_teacher([ 'active' => 0 ], $id);
                            echo 'Success';
                        } else {
                            echo "Error";
                        }
                    } else {
                        echo "Error";
                    }
                } else {
                    echo "Error";
                }
            } else {
                echo "Error";
            }
        } else {
            echo "Error";
        }
    }

    private function leave_applications_date_list_by_user_and_schoolyear( $userID, $schoolyearID, $usertypeID )
    {
        $leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication([
            'create_userID'     => $userID,
            'create_usertypeID' => $usertypeID,
            'schoolyearID'      => $schoolyearID,
            'status'            => 1
        ]);

        $retArray = [];
        if ( count($leaveapplications) ) {
            $oneday = 60 * 60 * 24;
            foreach ( $leaveapplications as $leaveapplication ) {
                for ( $i = strtotime($leaveapplication->from_date); $i <= strtotime($leaveapplication->to_date); $i = $i + $oneday ) {
                    $retArray[] = date('d-m-Y', $i);
                }
            }
        }
        return $retArray;
    }
}