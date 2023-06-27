<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sociallink extends Admin_Controller {
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
        $this->load->model("sociallink_m");
        $this->load->model("systemadmin_m");
        $this->load->model("teacher_m");
        $this->load->model("student_m");
        $this->load->model("parents_m");
        $this->load->model("user_m");
        $this->load->model("usertype_m");
        $this->load->helper("text");
        $language = $this->session->userdata('lang');
        $this->lang->load('sociallink', $language);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'userroleID',
                'label' => $this->lang->line("sociallink_userroleID"),
                'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_usertype'
            ),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("sociallink_userID"),
                'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_user'
            ),
            array(
                'field' => 'facebook',
                'label' => $this->lang->line("sociallink_facebook"),
                'rules' => 'trim|xss_clean|max_length[200]|callback_check_url|callback_unique_url'
            ),
            array(
                'field' => 'twitter',
                'label' => $this->lang->line("sociallink_twitter"),
                'rules' => 'trim|xss_clean|max_length[200]|callback_check_url|callback_unique_url'
            ),
            array(
                'field' => 'linkedin',
                'label' => $this->lang->line("sociallink_linkedin"),
                'rules' => 'trim|xss_clean|max_length[200]|callback_check_url|callback_unique_url'
            ),array(
                'field' => 'googleplus',
                'label' => $this->lang->line("sociallink_googleplus"),
                'rules' => 'trim|xss_clean|max_length[200]|callback_check_url|callback_unique_url'
            )
        );
        return $rules;
    }

    public function index() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
            )
        );

        $this->data['usertypes'] = $this->usertype_m->get_usertype();
        $this->data['roles'] = pluck($this->data['usertypes'],'usertype','usertypeID');
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['uriID'] = $id;

        if((int)$id) {
            $sociallinks = $this->sociallink_m->get_order_by_sociallink(array('usertypeID' => $id));
        } else {
            $sociallinks = $this->sociallink_m->get_sociallink();
        }

        $this->data['alluser'] = $this->userListName($sociallinks);

        $this->data['sociallinks'] = $sociallinks;
        $this->data["subview"] = "sociallink/index";
        $this->load->view('_layout_main', $this->data);
    }

    private function userListName($sociallinks) {
        $returnArray = [];
        $studentIDArray = [];
        $student = [];

        if(count($sociallinks)) {
            $i = 0;
            foreach ($sociallinks as $sociallink) {
                if($sociallink->usertypeID == 3) {
                    $studentIDArray[$i] = $sociallink->userID;
                    $i++;
                }
            }
        }

        if(count($studentIDArray)) {
            $student = $this->student_m->general_get_where_in_student($studentIDArray);
        }

        $systemadmin = $this->systemadmin_m->get_systemadmin();
        if(count($systemadmin)) {
            $returnArray[1]= pluck($systemadmin, 'obj', 'systemadminID');
        }

        $teacher = $this->teacher_m->get_teacher();
        if(count($teacher)) {
            $returnArray[2] = pluck($teacher, 'obj', 'teacherID');
        }

        if(count($student)) {
            $returnArray[3] = pluck($student, 'obj', 'studentID');
        }

        $parent = $this->parents_m->get_parents();
        if(count($parent)) {
            $returnArray[4] = pluck($parent, 'obj', 'parentsID');
        }

        $users = $this->user_m->get_user();
        if(count($users)) {
            foreach ($users as $user) {
                $returnArray[$user->usertypeID][$user->userID] = $user;
            }
        }

        return $returnArray;
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
        if($this->input->post('userroleID')) {
            $this->data['userArray'] = $this->getUserData($this->input->post('userroleID'));
        }
        $this->data['usertypes'] = $this->usertype_m->get_usertype();
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "sociallink/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "usertypeID" => $this->input->post("userroleID"),
                    "userID" => $this->input->post("userID"),
                    "facebook" => $this->input->post("facebook"),
                    "twitter" => $this->input->post("twitter"),
                    "linkedin" => $this->input->post("linkedin"),
                    "googleplus" => $this->input->post("googleplus"),
                );
                $this->sociallink_m->insert_sociallink($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("sociallink/index"));
            }
        } else {
            $this->data["subview"] = "sociallink/add";
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
            $this->data['sociallink'] = $this->sociallink_m->get_single_sociallink(array('sociallinkID' => $id));
            if($this->data['sociallink']) {
                if($_POST) {
                    $rules = $this->rules();
                    unset($rules[0], $rules[1]);
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "sociallink/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "facebook" => $this->input->post("facebook"),
                            "twitter" => $this->input->post("twitter"),
                            "linkedin" => $this->input->post("linkedin"),
                            "googleplus" => $this->input->post("googleplus"),
                        );
                        $this->sociallink_m->update_sociallink($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("sociallink/index"));
                    }
                } else {
                    $this->data["subview"] = "sociallink/edit";
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
            $this->data['sociallink'] = $this->sociallink_m->get_single_sociallink(array('sociallinkID' => $id));
            if($this->data['sociallink']) {
                $this->sociallink_m->delete_sociallink($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("sociallink/index"));
            } else {
                redirect(base_url("sociallink/index"));
            }
        } else {
            redirect(base_url("sociallink/index"));
        }
    }

    private function getUserData($userroleID) {
        $sociallink = [];
        $userArray['0'] = $this->lang->line('sociallink_user_select');
        if((int)$userroleID) {
            $sociallink = pluck($this->sociallink_m->get_order_by_sociallink(array('usertypeID' => $userroleID)), 'obj', 'userID');

            if($userroleID == 1) {
                $systemadmins = $this->systemadmin_m->get_systemadmin();
                if(count($systemadmins)) {
                    foreach ($systemadmins as $systemadmin) {
                        $userArray[$systemadmin->systemadminID] = $systemadmin->name;
                    }
                }
            } elseif($userroleID == 2) {
                $teachers = $this->teacher_m->get_teacher();
                if(count($teachers)) {
                    foreach ($teachers as $teacher) {
                        $userArray[$teacher->teacherID] = $teacher->name;
                    }
                }
            } elseif ($userroleID == 3) {
                $students = $this->student_m->get_student();
                if(count($students)) {
                    foreach ($students as $student) {
                        $userArray[$student->studentID] = $student->name;
                    }
                }
            } elseif ($userroleID == 4) {
                $parents = $this->parents_m->get_parents();
                if(count($parents)) {
                    foreach ($parents as $parent) {
                        $userArray[$parent->parentsID] = $parent->name;
                    }
                }
            } else {
                $users = $this->user_m->get_order_by_user(array('usertypeID' => $userroleID));
                if(count($users)) {
                    foreach ($users as $user) {
                        $userArray[$user->userID] = $user->name;
                    }
                }
            }
        }

        if(count($userArray)) {
            foreach($userArray as $key => $value) {
                if(!isset($sociallink[$key])) {
                    $RetUserArray[$key] = $value;
                }
            }
        }

        return $RetUserArray;
    }

    public function gerUser() {
        $userroleID = $this->input->post('userroleID');
        $sociallink = [];
        $userArray['0'] = $this->lang->line('sociallink_user_select');
        if((int)$userroleID) {
            $sociallink = pluck($this->sociallink_m->get_order_by_sociallink(array('usertypeID' => $userroleID)), 'obj', 'userID');

            if($userroleID == 1) {
                $systemadmins = $this->systemadmin_m->get_systemadmin();
                if(count($systemadmins)) {
                    foreach ($systemadmins as $systemadmin) {
                        $userArray[$systemadmin->systemadminID] = $systemadmin->name;
                    }
                }
            } elseif($userroleID == 2) {
                $teachers = $this->teacher_m->get_teacher();
                if(count($teachers)) {
                    foreach ($teachers as $teacher) {
                        $userArray[$teacher->teacherID] = $teacher->name;
                    }
                }
            } elseif ($userroleID == 3) {
                $students = $this->student_m->get_student();
                if(count($students)) {
                    foreach ($students as $student) {
                        $userArray[$student->studentID] = $student->name;
                    }
                }
            } elseif ($userroleID == 4) {
                $parents = $this->parents_m->get_parents();
                if(count($parents)) {
                    foreach ($parents as $parent) {
                        $userArray[$parent->parentsID] = $parent->name;
                    }
                }
            } else {
                $users = $this->user_m->get_order_by_user(array('usertypeID' => $userroleID));
                if(count($users)) {
                    foreach ($users as $user) {
                        $userArray[$user->userID] = $user->name;
                    }
                }
            }
        }

        if(count($userArray)) {
            foreach($userArray as $key => $value) {
                if(!isset($sociallink[$key])) {
                    echo "<option value=".$key.">".$value."</option>";
                }
            }
        }
    }

    public function unique_usertype() {
        $userroleID = $this->input->post('userroleID');
        if($userroleID == "0") {
            $this->form_validation->set_message('unique_usertype',"The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_user() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $userID = $this->input->post('userID');
        if($userID == "0") {
            $this->form_validation->set_message('unique_user',"The %s fields is required.");
            return FALSE;
        } else {
            $userroleID = $this->input->post('userroleID');
            if((int)$id) {
                $result = $this->sociallink_m->get_order_by_sociallink(array('usertypeID'=>$userroleID,'userID'=>$userID,'sociallinkID !='=>$id));
                return TRUE;
            } else {
                $result = $this->sociallink_m->get_order_by_sociallink(array('usertypeID'=>$userroleID,'userID'=>$userID));
                if(count($result)) {
                    $this->form_validation->set_message('unique_user',"The %s fields already exits.");
                    return FALSE;
                }
                return TRUE;
            }
        }
    }

    public function check_url($url) {
        if($url !="" ) {
            if(!filter_var($url, FILTER_VALIDATE_URL)) {
                $this->form_validation->set_message('check_url','The %s link is invalid');
                return FALSE;
            }   
        } 
        return TRUE;
    }

    public function unique_url() {
        $facebook = $this->input->post('facebook');
        $twitter = $this->input->post('twitter');
        $linkedin = $this->input->post('linkedin');
        $googleplus = $this->input->post('googleplus');
        if($facebook == '' && $twitter == '' && $linkedin == '' && $googleplus == '') {
            $this->form_validation->set_message('unique_url','Please provide a link.');
            return FALSE;
        }
        return TRUE;
    }
}
