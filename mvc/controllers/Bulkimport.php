<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bulkimport extends Admin_Controller {
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
        $this->load->model("teacher_m");
        $this->load->model("parents_m");
        $this->load->model("student_m");
        $this->load->model("user_m");
        $this->load->model("book_m");
        $this->load->model("studentrelation_m");
        $this->load->model("section_m");
        $this->load->model("classes_m");
        $this->load->model("studentextend_m");
        $this->load->model("subject_m");
        $this->load->model("studentgroup_m");

        $this->load->library('csvimport');

        $language = $this->session->userdata('lang');
        $this->lang->load('bulkimport', $language);
	}

	public function index() {
    	$this->data["subview"] = "bulkimport/index";
    	$this->load->view('_layout_main', $this->data);
	}

    public function teacher_bulkimport() {
        if(isset($_FILES["csvFile"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvFile"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvFile")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data = $this->upload->data();
                $file_path =  './uploads/csv/'.$file_data['file_name'];
                // $column_headers = array("Name", "Designation", "Dob", "Gender", "Religion", "Email", "Phone", "Address", "Nationality", "Jod", "Username", "Password");
                $column_headers = array("Name", "Designation", "Dob", "Gender", "Religion", "Email", "Phone", "Address", "Nationality", "Username", "Password");
                
                if ($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(count($csv_array)) {
                        $msg = "";
                        $i       = 1;
                        $csv_col = [];
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                                $csv_col = array_keys($row);
                            }
                            $match = array_diff($column_headers, $csv_col);
                            if (count($match) <= 0) {
                                $array              = $this->arrayToPost($row);
                                $singleteacherCheck = $this->singleteacherCheck($array);

                                if($singleteacherCheck['status']) {
                                    $insert_data = array(
                                        'name'            => $row['Name'],
                                        'designation'     => $row['Designation'],
                                        'dob'             => $this->trim_required_convertdate($row['Dob']),
                                        'sex'             => $row['Gender'],
                                        'religion'        => $row['Religion'],
                                        'email'           => $row['Email'],
                                        'phone'           => $row['Phone'],
                                        'address'         => $row['Address'],
                                        'country'         => $this->get_country($row['Nationality']),
                                        // 'jod'             => $this->trim_required_convertdate($row['Jod']),
                                        'username'        => $row['Username'],
                                        'password'        => $this->teacher_m->hash($row['Password']),
                                        'usertypeID'      => 2,
                                        'photo'           => 'default.png',
                                        "create_date"     => date("Y-m-d h:i:s"),
                                        "modify_date"     => date("Y-m-d h:i:s"),
                                        "create_userID"   => $this->session->userdata('loginuserID'),
                                        "create_username" => $this->session->userdata('username'),
                                        "create_usertype" => $this->session->userdata('usertype'),
                                        "active"          => 1,
                                    );
                                    $this->usercreatemail($row['Email'], $row['Username'], $row['Password']);
                                    $this->teacher_m->insert_teacher($insert_data);
                                } else {
                                    $msg .= $i.". ". $row['Name']." is not added! , ";
                                    $msg .= implode(' , ', $singleteacherCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if ($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_select_file'));
            redirect(base_url("bulkimport/index"));
        }
    }

    public function parent_bulkimport() {
        if(isset($_FILES["csvParent"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvParent"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvParent")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("Name", "Father Name", "Mother Name", "Father Profession","Mother Profession", "Email", "Phone", "Address", "Username", "Password");

                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(count($csv_array)) {
                        $i       = 1;
                        $msg     = "";
                        $csv_col = [];
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                                $csv_col = array_keys($row);
                            }
                            $match       = array_diff($column_headers, $csv_col);
                            if (count($match) <= 0) {
                                $array = $this->arrayToPost($row);
                                $singleparentCheck = $this->singleparentCheck($array);
                                if($singleparentCheck['status']) {
                                    $insert_data = array(
                                        'name'        => $row['Name'],
                                        'father_name' => $row['Father Name'],
                                        'mother_name' => $row['Mother Name'],
                                        'father_profession' => $row['Father Profession'],
                                        'mother_profession' => $row['Mother Profession'],
                                        'email'       => $row['Email'],
                                        'phone'       => $row['Phone'],
                                        'photo'       => 'default.png',
                                        'address'     => $row['Address'],
                                        'username'    => $row['Username'],
                                        'password'    => $this->parents_m->hash($row['Password']),
                                        'usertypeID'  => 2,
                                        'photo'       => 'default.png',
                                        "create_date" => date("Y-m-d h:i:s"),
                                        "modify_date" => date("Y-m-d h:i:s"),
                                        "create_userID"     => $this->session->userdata('loginuserID'),
                                        "create_username"   => $this->session->userdata('username'),
                                        "create_usertype"   => $this->session->userdata('usertype'),
                                        "active"      => 1,
                                    );
                                    // For Email
                                    $this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));
                                    $this->parents_m->insert_parents($insert_data);
                                } else {
                                    $msg .= $i.". ". $row['Name']." is not added! , ";
                                    $msg .= implode(' , ', $singleparentCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_select_file'));
            redirect(base_url("bulkimport/index"));
        }
    }
    
    public function user_bulkimport() {
        if(isset($_FILES["csvUser"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvUser"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvUser")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("Name", "Dob", "Gender", "Religion", "Email", "Phone", "Address", "Jod", "Username", "Password", "Usertype");
                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(count($csv_array)) {
                        $i       = 1;
                        $msg     = "";
                        $csv_col = [];
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                                $csv_col = array_keys($row);
                            }
                            $match = array_diff($column_headers, $csv_col);
                            if (count($match) <= 0) {
                                $array = $this->arrayToPost($row);
                                $singleuserCheck = $this->singleuserCheck($array);
                                if($singleuserCheck['status']) {
                                    $dob = $this->trim_required_convertdate($row['Dob']);
                                    $jod = $this->trim_required_convertdate($row['Jod']);
                                    $insert_data = array(
                                        'name'     => $row['Name'],
                                        'dob'      => $dob,
                                        'sex'      => $row['Gender'],
                                        'religion' => $row['Religion'],
                                        'email'    => $row['Email'],
                                        'phone'    => $row['Phone'],
                                        'address'  => $row['Address'],
                                        'jod'      => $jod,
                                        'photo'    => 'default.png',
                                        'username' => $row['Username'],
                                        'password' => $this->user_m->hash($row['Password']),
                                        'usertypeID'      => $this->trim_check_usertype($row['Usertype']),
                                        "create_date"     => date("Y-m-d h:i:s"),
                                        "modify_date"     => date("Y-m-d h:i:s"),
                                        "create_userID"   => $this->session->userdata('loginuserID'),
                                        "create_username" => $this->session->userdata('username'),
                                        "create_usertype" => $this->session->userdata('usertype'),
                                        "active"   => 1,
                                    );
                                    $this->user_m->insert_user($insert_data);
                                    $this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));
                                } else {
                                    $msg .= $i.". ". $row['Name']." is not added! , ";
                                    $msg .= implode(' , ', $singleuserCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if ($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_select_file'));
            redirect(base_url("bulkimport/index"));
        }
    }

    public function book_bulkimport() {
        if(isset($_FILES["csvBook"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvBook"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvBook")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];
                $column_headers = array("Book", "Subject code", "Author", "Price", "ISBN", "Quantity", "Rack");
                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(count($csv_array)) {
                        $i       = 1;
                        $msg     = "";
                        $csv_col = [];
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                              $csv_col = array_keys($row);
                            }

                            $match = array_diff($column_headers, $csv_col);
                            if (count($match) <= 0) {
                                $array           = $this->arrayToPost($row);
                                $singlebookCheck = $this->singlebookCheck($array);

                                if($singlebookCheck['status']) {
                                    $insert_data = array(
                                        'book'     => $row['Book'],
                                        'subject_code' => $row['Subject code'],
                                        'author'   => $row['Author'],
                                        'price'    => $row['Price'],
                                        'quantity' => $row['Quantity'],
                                        'due_quantity' => 0,
                                        'rack'     => $row['Rack']
                                    );
                                    $this->book_m->insert_book($insert_data);
                                } else {
                                    $msg .= $i.". ". $row['Book']." is not added! , ";
                                    $msg .= implode(' , ', $singlebookCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', $this->lang->line('bulkimport_success'));
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_error'));
            redirect(base_url("bulkimport/index"));
        }
    }

    public function student_bulkimport() {
        if(isset($_FILES["csvStudent"])) {
            $config['upload_path']   = "./uploads/csv/";
            $config['allowed_types'] = 'text/plain|text/csv|csv';
            $config['max_size']      = '2048';
            $config['file_name']     = $_FILES["csvStudent"]['name'];
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload("csvStudent")) {
                $this->session->set_flashdata('error', $this->lang->line('bulkimport_upload_fail'));
                redirect(base_url("bulkimport/index"));
            } else {
                $file_data      = $this->upload->data();
                $file_path      =  './uploads/csv/'.$file_data['file_name'];
                // $column_headers = array("Name", "Dob", "Gender", "Religion", "Email", "Phone", "Address", "Class", "Section", "Username", "Password", "Roll", "BloodGroup", "State", "Nationality", "UniqueNo", "Group", "OptionalSubject");
                $column_headers = array("Name", "Class", "Section", "Username", "Password", "UniqueNo");
                
                if($csv_array = @$this->csvimport->get_array($file_path, $column_headers)) {
                    if(count($csv_array)) {
                        $msg     = "";
                        $i       = 1;
                        $csv_col = [];
                        foreach ($csv_array as $row) {
                            if ($i==1) {
                                $csv_col = array_keys($row);
                            }
                            $match = array_diff($column_headers, $csv_col);
                            
                            if (count($match) <= 0) {
                                $array = $this->arrayToPost($row);
                                $singlestudentCheck  = $this->singlestudentCheck($array);
                                if($singlestudentCheck['status']) {
                                    $classID         = $this->get_student_class($row['Class']);
                                    $sections        = $this->get_student_section($classID, $row['Section']);
                                    $group           = $this->get_student_group($row['Group']);
                                    $optionalSubject = $this->get_student_optional_subject($classID, $row['OptionalSubject']);
                                    // $dob             = $this->trim_required_convertdate($row['Dob']);

                                    $insert_data = array(
                                        'name'       => $row['Name'],
                                        // 'dob'        => $dob,
                                        // 'sex'        => $row['Gender'],
                                        // 'religion'   => $row['Religion'],
                                        // 'email'      => $row['Email'],
                                        // 'phone'      => $row['Phone'],
                                        'photo'      => 'default.png',
                                        // 'address'    => $row['Address'],
                                        // "bloodgroup" => $row['BloodGroup'],
                                        // "state"      => $row['State'],
                                        // "country"    => $this->get_country($row['Nationality']),
                                        "registerNO" => $row['UniqueNo'],
                                        "unique_number" => $row['UniqueNo'],
                                        'classesID'  => $classID,
                                        'sectionID'  => $sections->sectionID,
                                        // 'roll'       => $row['Roll'],
                                        'username'   => $row['Username'],
                                        'password'   => $this->student_m->hash($row['Password']),
                                        'usertypeID' => 3,
                                        'parentID'   => 0,
                                        'library'    => 0,
                                        'hostel'     => 0,
                                        'transport'  => 0,
                                        'createschoolyearID' => $this->session->userdata('defaultschoolyearID'),
                                        'schoolyearID'       => $this->session->userdata('defaultschoolyearID'),
                                        "create_date"        => date("Y-m-d h:i:s"),
                                        "modify_date"        => date("Y-m-d h:i:s"),
                                        "create_userID"      => $this->session->userdata('loginuserID'),
                                        "create_username"    => $this->session->userdata('username'),
                                        "create_usertype"    => $this->session->userdata('usertype'),
                                        "active"     => 1,
                                    );

                                    $this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));
                                    $this->student_m->insert_student($insert_data);
                                    $studentID = $this->db->insert_id();

                                    $classes = $this->classes_m ->general_get_single_classes(array('classesID'=>$classID));
                                    $section = $this->section_m->general_get_single_section(array('classesID'=>$classID, 'sectionID'=>$sections->sectionID));

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

                                    $studentReletion = $this->studentrelation_m->get_order_by_studentrelation(array('srstudentID' => $studentID, 'srschoolyearID' => $this->session->userdata('defaultschoolyearID')));
                                    if(!count($studentReletion)) {
                                        $arrayStudentRelation = array(
                                            'srstudentID'  => $studentID,
                                            'srname'       => $row['Name'],
                                            'srclassesID'  => $classID,
                                            'srclasses'    => $setClasses,
                                            'srroll'       => $row['Roll'],
                                            'srregisterNO' => $row['RegistrationNO'],
                                            'srsectionID'  => $sections->sectionID,
                                            'srsection'    => $setSection,
                                            'srstudentgroupID'    => $group->studentgroupID,
                                            'sroptionalsubjectID' => $optionalSubject->subjectID,
                                            'srschoolyearID'      => $this->session->userdata('defaultschoolyearID')
                                        );
                                        $this->studentrelation_m->insert_studentrelation($arrayStudentRelation);
                                    } else {
                                        $arrayStudentRelation = array(
                                            'srname'      => $row['Name'],
                                            'srclassesID' => $classID,
                                            'srclasses'   => $setClasses,
                                            'srroll'      => $row['Roll'],
                                            'srregisterNO'=> $row['RegistrationNO'],
                                            'srsectionID' => $sections->sectionID,
                                            'srsection'   => $setSection,
                                            'srstudentgroupID'    => $group->studentgroupID,
                                            'sroptionalsubjectID' => $optionalSubject->subjectID,
                                        );
                                        $this->studentrelation_m->update_studentrelation_with_multicondition($arrayStudentRelation, array('srstudentID' => $studentID, 'srschoolyearID' => $this->session->userdata('defaultschoolyearID')));
                                    }

                                    $studentExtend = $this->studentextend_m->get_single_studentextend(array('studentID' => $studentID));
                                    if(!count($studentExtend)) {
                                        $studentExtendArray = array(
                                            'studentID'         => $studentID,
                                            'studentgroupID'    => $group->studentgroupID,
                                            'optionalsubjectID' => $optionalSubject->subjectID,
                                            'extracurricularactivities' => NULL,
                                            'remarks' => NULL
                                        );
                                        $this->studentextend_m->insert_studentextend($studentExtendArray);
                                    } else {
                                        $studentExtendArray = array(
                                            'studentID'         => $studentID,
                                            'studentgroupID'    => $group->studentgroupID,
                                            'optionalsubjectID' => $optionalSubject->subjectID,
                                            'extracurricularactivities' => NULL,
                                            'remarks' => NULL
                                        );
                                        $this->studentextend_m->update_studentextend($studentExtendArray, $studentExtend->studentextendID);
                                    }
                                } else {
                                    $msg .= $i.". ". $row['Name']." is not added! , ";
                                    $msg .= implode(' , ', $singlestudentCheck['error']);
                                    $msg .= ". <br/>";
                                }
                            } else {
                                $this->session->set_flashdata('error', "Wrong csv file!");
                                redirect(base_url("bulkimport/index"));
                            }
                            $i++;
                        }
                        if($msg != "") {
                            $this->session->set_flashdata('msg', $msg);
                        }
                        $this->session->set_flashdata('success', 'Success');
                        redirect(base_url("bulkimport/index"));
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('bulkimport_data_not_found'));
                        redirect(base_url("bulkimport/index"));
                    }
                } else {
                    $this->session->set_flashdata('error', "Wrong csv file!");
                    redirect(base_url("bulkimport/index"));
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('bulkimport_select_file'));
            redirect(base_url("bulkimport/index"));
        } 
    }

    // Single  Validation Check
    private function singleteacherCheck($array) {
        $name     = $this->trim_required_string_maxlength_minlength_Check($array['name'],60);
        $designation = $this->trim_required_string_maxlength_minlength_Check($array['designation'],128);
        $dob      = $this->trim_required_date_Check($array['dob']);    
        $gender   = $this->trim_required_string_maxlength_minlength_Check($array['gender'],10);
        $religion = $this->trim_required_string_maxlength_minlength_Check($array['religion'],25);
        $email    = $this->trim_check_unique_email($array['email'],40);
        $phone    = $this->trim_required_string_maxlength_minlength_Check($array['phone'],25,5);
        $address  = $this->trim_required_string_maxlength_minlength_Check($array['address'],200);
        $nationality  = $this->trim_required_string_maxlength_minlength_Check($array['nationality'],128);
        // $jod      = $this->trim_required_date_Check($array['jod']);
        $username = $this->trim_check_unique_username($array['username'],40);
        $password = $this->trim_required_string_maxlength_minlength_Check($array['password'],40);

        $retArray['status'] = TRUE;
        // if($name && $designation && $dob && $gender && $religion && $email && $phone && $address && $nationality && $jod && $username && $password) {
        if($name && $designation && $dob && $gender && $religion && $email && $phone && $address && $nationality && $username && $password) {
            $retArray['status'] = TRUE;
        } else {
            $retArray['status'] = FALSE;
            if(!$name) {
                $retArray['error']['name'] = 'Invalid Teacher Name';
            }
            if(!$designation) {
                $retArray['error']['designation'] = 'Invalid Designation';
            }
            if(!$dob) {
                $retArray['error']['dob'] = 'Invalid Date Of Birth';
            }
            if(!$gender) {
                $retArray['error']['gender'] = 'Invalid Gender';
            }
            if(!$religion) {
                $retArray['error']['religion'] = 'Invalid Riligion';
            }
            if(!$email) {
                $retArray['error']['email'] = 'Invalid email address or email address already exists.';
            }
            if(!$phone) {
                $retArray['error']['phone'] = 'Invalid Phone Number';
            }
            if(!$address) {
                $retArray['error']['address'] = 'Invalid Address';
            }
            if(!$nationality) {
                $retArray['error']['nationality'] = 'Invalid Nationality or no nationality given';
            }
            // if(!$jod) {
            //     $retArray['error']['jod'] = 'Invalid Joining Date';
            // }
            if(!$username) {
                $retArray['error']['username'] = 'Invalid username or username already exists';
            }
            if(!$password) {
                $retArray['error']['password'] = 'Invalid Password';
            }
        }
        return $retArray;
    }

    private function singleparentCheck($array) {
        $name            = $this->trim_required_string_maxlength_minlength_Check($array['name'],60);
        $father_name     = $this->trim_required_string_maxlength_minlength_Check($array['father_name'],60);
        $mother_name     = $this->trim_required_string_maxlength_minlength_Check($array['mother_name'],40);
        $father_profession = $this->trim_required_string_maxlength_minlength_Check($array['father_profession'],40);
        $mother_profession = $this->trim_required_string_maxlength_minlength_Check($array['mother_profession'],40);
        $email    = $this->trim_check_unique_email($array['email'],40);
        $phone    = $this->trim_required_string_maxlength_minlength_Check($array['phone'],25,5);
        $address  = $this->trim_required_string_maxlength_minlength_Check($array['address'],200);
        $username = $this->trim_check_unique_username($array['username'],40,4);
        $password = $this->trim_required_string_maxlength_minlength_Check($array['password'],40,4);

        $retArray['status'] = TRUE;
        if($name && $father_name && $mother_name && $father_profession && $mother_profession && $email && $phone && $address && $username && $password) {
            $retArray['status'] = TRUE;
        } else {
            $retArray['status'] = FALSE;
            if(!$name) {
                $retArray['error']['name'] = 'Invalid Parent Name';
            }
            if(!$father_name) {
                $retArray['error']['father_name'] = 'Invalid Father Name';
            }
            if(!$mother_name) {
                $retArray['error']['mother_name'] = 'Invalid Mother Name';
            }
            if(!$father_profession) {
                $retArray['error']['father_profession'] = 'Invalid Father Profession';
            }
            if(!$mother_profession) {
                $retArray['error']['mother_profession'] = 'Invalid Mother Profession';
            }
            if(!$email) {
                $retArray['error']['email'] = 'Invalid email address or email address already exists.';
            }
            if(!$phone) {
                $retArray['error']['phone'] = 'Invalid Phone Number';
            }
            if(!$address) {
                $retArray['error']['address'] = 'Invalid Address';
            }
            if(!$username) {
                $retArray['error']['username'] = 'Invalid username or username already exists';
            }
            if(!$password) {
                $retArray['error']['password'] = 'Invalid Password';
            }
        }
        return $retArray;
    }

    private function singleuserCheck($array) {
        $name     = $this->trim_required_string_maxlength_minlength_Check($array['name'],60);
        $dob      = $this->trim_required_date_Check($array['dob']);    
        $gender   = $this->trim_required_string_maxlength_minlength_Check($array['gender'],10);
        $religion = $this->trim_required_string_maxlength_minlength_Check($array['religion'],25);
        $email    = $this->trim_check_unique_email($array['email'],40);
        $phone    = $this->trim_required_string_maxlength_minlength_Check($array['phone'],25,5);
        $address  = $this->trim_required_string_maxlength_minlength_Check($array['address'],200);
        $jod      = $this->trim_required_date_Check($array['jod']);
        $username = $this->trim_check_unique_username($array['username'],40);
        $password = $this->trim_required_string_maxlength_minlength_Check($array['password'],40);
        $usertype = $this->trim_check_usertype($array['usertype']);

        $retArray['status'] = TRUE;
        if($name && $dob && $gender && $religion && $email && $phone && $address && $jod && $username && $password && $usertype) {
            $retArray['status'] = TRUE;
        } else {
            $retArray['status'] = FALSE;
            if(!$name) {
                $retArray['error']['name'] = 'Invalid User Name';
            }
            if(!$dob) {
                $retArray['error']['dob'] = 'Invalid Date Of Birth';
            }
            if(!$gender) {
                $retArray['error']['gender'] = 'Invalid Gender';
            }
            if(!$religion) {
                $retArray['error']['religion'] = 'Invalid Riligion';
            }
            if(!$email) {
                $retArray['error']['email'] = 'Invalid email address or email address already exists.';
            }
            if(!$phone) {
                $retArray['error']['phone'] = 'Invalid Phone Number';
            }
            if(!$address) {
                $retArray['error']['address'] = 'Invalid Address';
            }
            if(!$jod) {
                $retArray['error']['jod'] = 'Invalid Date Of Birth';
            }
            if(!$username) {
                $retArray['error']['username'] = 'Invalid username or username already exists';
            }
            if(!$password) {
                $retArray['error']['password'] = 'Invalid Password';
            }
            if(!$usertype) {
                $retArray['error']['usertype'] = 'Invalid Usertype';
            }
        }
        return $retArray;
    }

    private function singlebookCheck($array) {
        $book         = $this->trim_required_string_maxlength_minlength_Check($array['book'], 60);
        $price        = $this->trim_required_int_maxlength_minlength_Check($array['price'], 10);
        $rack         = $this->trim_string_maxlength_minlength_Check($array['rack'], 60);
        $author       = $this->trim_required_string_maxlength_minlength_Check($array['author'], 100);
        $quantity     = $this->trim_required_int_maxlength_minlength_Check($array['quantity'], 10);
        $subject_code = $this->trim_string_maxlength_minlength_Check($array['subject_code'], 20);
        $isbn         = $this->trim_string_maxlength_minlength_Check($array['isbn'], 20);

        $retArray['status'] = TRUE;
        if($book && $author && $quantity || $subject_code || $rack || $isbn  || $price) {
            $books = $this->book_m->get_single_book(array('LOWER(book)' => strtolower($book), 'LOWER(author)' => strtolower($author), 'LOWER(subject_code)' => strtolower($subject_code)));
            if(count($books)) {
                $retArray['status'] = FALSE;
                $retArray['error']['book'] = 'Book already exits';
            } else {
                $retArray['status'] = TRUE;
            }
        } else {
            $retArray['status'] = FALSE;
            if(!$book) {
                $retArray['error']['book'] = 'Invalid Book Name';
            }
            if(!$price) {
                $retArray['error']['price'] = 'Price are not valid';
            }
            if(!$rack) {
                $retArray['error']['rack'] = 'Rack are not valid';
            }
            if(!$author) {
                $retArray['error']['author'] = 'Author are not valid';
            }
            if(!$quantity) {
                $retArray['error']['quantity'] = 'Quantity are not valid';
            }
            if(!$subject_code) {
                $retArray['error']['subject_code'] = 'Subject Code are not valid';
            }
        }
        return $retArray;
    }

    public function singlestudentCheck($array) {
        $name       = $this->trim_required_string_maxlength_minlength_Check($array['name'],60);
        // $dob        = $this->trim_required_date_Check($array['dob']);    
        // $gender     = $this->trim_required_string_maxlength_minlength_Check($array['gender'],10);
        // $religion   = $this->trim_required_string_maxlength_minlength_Check($array['religion'],25);
        // $email      = $this->trim_check_unique_email($array['email'],40);
        // $phone      = $this->trim_required_string_maxlength_minlength_Check($array['phone'],25,5);
        // $address    = $this->trim_required_string_maxlength_minlength_Check($array['address'],200);
        $class      = $this->trim_required_class_Check($array['class']);
        $section    = $this->trim_required_section_Check($array['class'], $array['section']);
        $username   = $this->trim_check_unique_username($array['username'],40);
        $password   = $this->trim_required_string_maxlength_minlength_Check($array['password'],40);
        // $roll       = $this->trim_roll_Check($array);
        // $bloodgroup = $this->trim_required_string_maxlength_minlength_Check($array['bloodgroup'],5);
        // $state      = $this->trim_required_string_maxlength_minlength_Check($array['state'],128);
        // $nationality    = $this->trim_required_string_maxlength_minlength_Check($array['nationality'],128);
        $uniqueno  = $this->trim_required_registration_Check($array['uniqueno']);
        // $group           = $this->trim_group_Check($array['group'],40);
        // $optionalsubject = $this->trim_optionalsubject_Check($array['optionalsubject'], $array['class']);

        $checkStudent    = $this->trim_check_section_student($array);

        $retArray['status'] = FALSE;
        // if($name && $dob && $gender && $religion && $email && $phone && $address && $class && $section && $username && $password && $roll && $bloodgroup && $state && $nationality && $uniqueno && $group && $optionalsubject && $checkStudent) {
        if($name && $class && $section && $username && $password && $uniqueno && $checkStudent) {
            $retArray['status'] = TRUE;
        } else {
            if(!$name) {
                $retArray['error']['name'] = 'Invalid Teacher Name';
            }
            // if(!$dob) {
            //     $retArray['error']['dob'] = 'Invalid Date Of Birth';
            // }
            // if(!$gender) {
            //     $retArray['error']['gender'] = 'Invalid Gender';
            // }
            // if(!$religion) {
            //     $retArray['error']['religion'] = 'Invalid Riligion';
            // }
            // if(!$email) {
            //     $retArray['error']['email'] = 'Invalid email address or email address already exists.';
            // }
            // if(!$phone) {
            //     $retArray['error']['phone'] = 'Invalid Phone Number';
            // }
            // if(!$address) {
            //     $retArray['error']['address'] = 'Invalid Address';
            // }
            if(!$class) {
                $retArray['error']['class'] = 'Invalid Class';
            }
            if(!$section) {
                $retArray['error']['section'] = 'Invalid Stream or maximum capicity of stream reached';
            }
            if(!$username) {
                $retArray['error']['username'] = 'Invalid username or username already exists';
            }
            if(!$password) {
                $retArray['error']['password'] = 'Invalid Password';
            }
            // if(!$roll) {
            //     $retArray['error']['roll'] = 'Invalid roll or roll already exists in class';
            // }
            // if(!$bloodgroup) {
            //     $retArray['error']['bloodgroup'] = 'Invalid bloodgroup';
            // }
            // if(!$state) {
            //     $retArray['error']['state'] = 'Invalid state';
            // }
            // if(!$nationality) {
            //     $retArray['error']['nationality'] = 'Invalid nationality or nationality not provided';
            // }
            if(!$uniqueno) {
                $retArray['error']['uniqueno'] = 'Invalid unique no or unique no already exists';
            }
            // if(!$group) {
            //     $retArray['error']['group'] = 'Invalid Group';
            // }
            // if(!$optionalsubject) {
            //     $retArray['error']['optionalsubject'] = 'Invalid OptionalSubject Subject';
            // } 
            if(!$checkStudent) {
                $retArray['error']['checkStudent'] = 'Student can not add in section';
            }
        }
        return $retArray;
    }

    // Student Valiadtion Check
    private function trim_check_section_student($array) {
        $classes  = strtolower(trim($array['class']));
        $section  = strtolower(trim($array['section']));

        if($classes && $section) {
            $result = $this->classes_m->general_get_single_classes(array('LOWER(classes)'=>$classes));
            if(count($result)) {
                $result   = $this->section_m->general_get_single_section(array('classesID'=> $result->classesID, 'LOWER(section)'=> $section));
                if(count($result)) {
                    $capacity     = $result->capacity;
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');
                    $students     = $this->studentrelation_m->general_get_order_by_student(array('srclassesID'=>$result->classesID,'srsectionID'=>$result->sectionID,'srschoolyearID'=>$schoolyearID));
                    $totalStudent = count($students);
                    if($totalStudent <= $capacity) {
                        return TRUE;
                    }
                }
            }
        }
        return FALSE;
    }

    private function trim_required_registration_Check($data) {
        $data = trim($data);
        if($data) {
            $student = $this->studentrelation_m->general_get_single_student(array("srregisterNO" => $data));
            if(count($student)) {
                return FALSE;
            } else {
                return $data;
            }
        }
        return FALSE;
    }

    private function trim_roll_Check($data) {
        $roll    = trim($data['roll']);
        $classes = strtolower(trim($data['class']));
        if($roll && $classes) {
            $result       = $this->classes_m->general_get_single_classes(array('LOWER(classes)'=>$classes));
            if(count($result)) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $student      = $this->studentrelation_m->general_get_order_by_student(array("srroll" => $roll, "srclassesID" => $result->classesID, 'srschoolyearID' => $schoolyearID));
                if(count($student)) {
                    return FALSE;
                } else {
                    return $roll;
                }
            }
        }
        return FALSE;
    }

    private function trim_optionalsubject_Check($subject, $classes) {
        if($subject == '') {
            $array = array(
                'subjectID' => 0,
                'subject' => ''
            );
            $array = (object) $array;
            return $array;
        } else {
            $subject = strtolower(trim($subject));
            $classes = strtolower(trim($classes));
            if($subject && $classes) {
                $result       = $this->classes_m->general_get_single_classes(array('LOWER(classes)'=>$classes));
                if(count($result)) {
                    $result   = $this->subject_m->general_get_single_subject(array('classesID'=> $result->classesID, 'type'=> 0, 'LOWER(subject)'=> $subject));
                    if(count($result)) {
                        return $result;
                    }
                }
            } 
            return FALSE;
        }
    }

    private function trim_required_class_Check($classes) {
        $classes = strtolower(trim($classes));
        if($classes) {
            $result       = $this->classes_m->general_get_single_classes(array('LOWER(classes)'=>$classes));
            if(count($result)) {
                return $result;
            }
        }
        return FALSE;
    }

    private function trim_group_Check($group) {
        $group1 = strtolower(trim($group));
        $group2 = trim($group);
        
        if($group1 && $group2) {
            $result1   = $this->studentgroup_m->get_single_studentgroup(array('group'=>$group1));
            $result2   = $this->studentgroup_m->get_single_studentgroup(array('group'=>$group2));
            if(count($result1)) {
                return $result1;
            } elseif(count($result2)) {
                return $result2;
            }
        }
        return TRUE;
    }

    private function trim_required_section_Check($classes, $section) {
        $classes  = strtolower(trim($classes));
        $section  = strtolower(trim($section));
        if($classes && $section) {
            $result = $this->classes_m->general_get_single_classes(array('LOWER(classes)'=>$classes));
            if(count($result)) {
                $result   = $this->section_m->general_get_single_section(array('classesID'=> $result->classesID, 'LOWER(section)'=> $section));
                if(count($result)) {
                    return $result;
                }
            }
        }
        return FALSE;
    }

    // User Validation Check
    private function trim_check_usertype($usertype) {
        $usertype = strtolower(trim($usertype));
        if($usertype) {
            $result         = $this->usertype_m->get_single_usertype(array('LOWER(usertype)'=>$usertype));
            if(count($result)) {
                $usertypeID   = $result->usertypeID;
                $blockuserArr = array(1, 2, 3, 4);
                if(in_array($usertypeID, $blockuserArr)) {
                    return FALSE;
                } else {
                    return $usertypeID;
                }
            }
        }
        return FALSE;
    }

    // Username and Email Validation Check
    private function trim_check_unique_username($data) {
        $data = (string)trim($data);
        if($data) {
            $tables = array('student', 'parents', 'teacher', 'user', 'systemadmin');
            $i = 0;
            $array = array();
            foreach ($tables as $table) {
                $user = $this->student_m->get_username($table, array("username" => $data));
                if(count($user)) {
                    $array['permition'][$i] = 'no';
                } else {
                    $array['permition'][$i] = 'yes';
                }
                $i++;
            }

            if(in_array('no', $array['permition'])) {
                return FALSE;
            } else {
                return $data;
            }
        }
        return FALSE;
    }

    private function trim_check_unique_email($data) {
        $data = trim($data);
        if(filter_var($data, FILTER_VALIDATE_EMAIL)) {
            $tables = array('student', 'parents', 'teacher', 'user', 'systemadmin');
            $array  = array();
            $i = 0;
            foreach ($tables as $table) {
                $user = $this->student_m->get_username($table, array("email" => $data));
                if(count($user)) {
                    $array['permition'][$i] = 'no';
                } else {
                    $array['permition'][$i] = 'yes';
                }
                $i++;
            }
            if(in_array('no', $array['permition'])) {
                return FALSE;
            } else {
                return $data;
            }
        }
        return FALSE;
    }

   // Default Function All Import Validation Check
    public function arrayToPost($data) {
        if(is_array($data)) {
            $post = [];
            foreach ($data as $key => $item) {
                $key = preg_replace('/\s+/', '_', $key);
                $key = strtolower($key);
                $post[$key] = $item;
            }
            return $post;
        }
        return [];
    }

    private function trim_required_string_maxlength_minlength_Check($data,$maxlength= 10, $minlength= 0) {
        $data       = (string)trim($data);
        $dataLength = strlen($data);

        if(($dataLength == 0) || ($dataLength > $maxlength) || ($dataLength < $minlength)) {
            return FALSE;
        } else {
            if(is_string($data)) {
                return $data;
            }
            return FALSE;
        }
    }

    private function trim_string_maxlength_minlength_Check($data,$maxlength= 10, $minlength= 0) {
        $data       = (string)trim($data);
        $dataLength = strlen($data);

        if(is_string($data)) {
            return $data;
        }
        return FALSE;
    }

    private function trim_required_int_maxlength_minlength_Check($data,$maxlength= 10, $minlength = 0) {
        $data = (int)trim($data);
        $dataLength = strlen($data);

        if(($dataLength == 0) || ($dataLength > $maxlength) || ($dataLength < $minlength)) {
            return FALSE;
        } else {
            if(is_int($data)) {
                return $data;
            }
            return FALSE;
        }
    }

    private function trim_int_maxlength_minlength_Check($data,$maxlength= 10, $minlength = 0) {
        $data = (int)trim($data);
        $dataLength = strlen($data);

        if(is_int($data)) {
            return $data;
        }
        return FALSE;
    }

    private function trim_required_date_Check($date) {
        $date = trim($date);
        if($date) {
            $date = str_replace('/', '-', $date);
            return date("Y-m-d", strtotime($date));
        } 
        return FALSE;
    }
    
    private function trim_required_convertdate($date) {
        $date = trim($date);
        if($date) {
            $date = str_replace('/', '-', $date);
            return date("Y-m-d", strtotime($date));
        }
        return 0;
    }

    // For Only Student Import Check Query
    public function get_student_class($classes) {
        $classes  = strtolower(trim($classes));
        if($classes) {
            $result  = $this->classes_m->general_get_single_classes(array('LOWER(classes)'=>$classes));
            if(count($result)) {
                return $result->classesID;
            }
        }
        return 0;
    }

    public function get_student_section($classesID, $section) {
        $section  = strtolower(trim($section));
        if($classesID) {
            $result   = $this->section_m->general_get_single_section(array('classesID'=> $classesID, 'LOWER(section)'=> $section));
            if(count($result)) {
                return $result;
            }
        }
        return 0;
    }

    public function get_student_group($group) {
        $group1 = strtolower(trim($group));
        $group2 = trim($group);
        if($group1 && $group2) {
            $result1   = $this->studentgroup_m->get_single_studentgroup(array('group'=>$group1));
            $result2   = $this->studentgroup_m->get_single_studentgroup(array('group'=>$group2));
            if(count($result1)) {
                return $result1;
            } elseif(count($result2)) {
                return $result2;
            }
        }
        $array = array(
            'studentgroupID' => 0,
            'group' => ''
        );
        $array = (object) $array;
        return $array;
    }

    public function get_student_optional_subject($classesID, $subject) {
        $subject  = strtolower(trim($subject));
        if($subject) {
            $result   = $this->subject_m->general_get_single_subject(array('classesID'=> $classesID, 'type'=> 0, 'LOWER(subject)'=> $subject));
            if(count($result)) {
                return $result;
            }
        }
        $array = array(
            'subjectID' => 0,
            'subject'   => ''
        );
        $array = (object) $array;
        return $array;
    }

    public function get_country($country) {
        $countryArr = $this->getAllCountry();
        $key        = array_search($country, $countryArr);
        return ($key) ? $key : 0;
    }

}