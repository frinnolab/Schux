<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activities extends Admin_Controller {
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
		$this->load->model("activities_m");
		$this->load->model("activitiescategory_m");
		$this->load->model("activitiesstudent_m");
		$this->load->model("activitiesmedia_m");
		$this->load->model("activitiescomment_m");
		$this->load->model("student_m");
		$this->load->model("classes_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('activities', $language);
        $this->load->helper('date');
	}

	public function index() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->data['user'] = getAllSelectUser();
        $this->data['userID'] = $this->session->userdata('loginuserID');
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['activitiescategories'] = pluck($this->activitiescategory_m->get_activitiescategory(), 'obj', 'activitiescategoryID');
        $this->data['activities'] = $this->activities_m->get_order_by_activities(array('schoolyearID' => $schoolyearID));
        $this->data['activitiesmedia'] = pluck_multi_array($this->activitiesmedia_m->get_activitiesmedia(), 'obj', 'activitiesID');
        $this->data['activitiescomments'] = pluck_multi_array($this->activitiescomment_m->get_order_by_activitiescomment(array('schoolyearID' => $schoolyearID)), 'obj', 'activitiesID');

        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            if($_POST) {
                $id = htmlentities(escapeString($this->uri->segment(3)));
                if((int)$id) {
                    if($_POST['comment']) {
                        $array['activitiesID'] = $id;
                        $array['comment'] = $this->input->post('comment');
                        $array['schoolyearID'] = $schoolyearID;
                        $array['userID'] = $this->session->userdata("loginuserID");
                        $array['usertypeID'] = $this->session->userdata("usertypeID");
                        $array['create_date'] = date("Y-m-d H:i:s");
                        $this->activitiescomment_m->insert_activitiescomment($array);
                        $this->session->set_flashdata('success', $this->lang->line("menu_success"));
                        redirect(base_url("activities/index"));
                    }
                }
            }
        }
		$this->data["subview"] = "activities/index";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
            array(
                'field' => 'description',
                'label' => $this->lang->line("activities_description"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'attachment',
                'label' => $this->lang->line("attachment"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'time_from',
                'label' => $this->lang->line("activities_time_from"),
                'rules' => 'trim|max_length[10]|xss_clean'
            ),
            array(
                'field' => 'time_to',
                'label' => $this->lang->line("activities_time_to"),
                'rules' => 'trim|max_length[10]|xss_clean'
            ),
            array(
                'field' => 'location',
                'label' => $this->lang->line("activities_location"),
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'date',
                'label' => $this->lang->line("activities_date"),
                'rules' => 'required|trim|xss_clean'
            ),
            array(
                'field' => 'time_at',
                'label' => $this->lang->line("activities_time_at"),
                'rules' => 'trim|max_length[10]|xss_clean'
            )
        );
		return $rules;
	}

	public function add() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/datepicker/datepicker.css',
                    'assets/timepicker/timepicker.css',
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                    'assets/tooltipster/css/tooltipster.bundle.min.css'
                ),
                'js' => array(
                    'assets/datepicker/datepicker.js',
                    'assets/select2/select2.js',
                    'assets/tooltipster/js/tooltipster.bundle.min.js',
                    'assets/timepicker/timepicker.js'
                )
            );
            $categoryID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$categoryID) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->data['activities_categories'] = $this->activitiescategory_m->get_activitiescategory();
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "activities/add";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "description" => $this->input->post("description"),
                            "activitiescategoryID" => $categoryID,
                            "schoolyearID" => $schoolyearID,
                            "usertypeID" => $this->session->userdata('usertypeID'),
                            "userID" => $this->session->userdata('loginuserID'),
                        );
                        if ($this->input->post("time_to") !="0:00"){
                            $array["time_to"] = date('H:i:s',strtotime($this->input->post("time_to")));
                        }
                        if ($this->input->post("time_from") !="0:00"){
                            $array["time_from"] = date('H:i:s',strtotime($this->input->post("time_from")));
                        }
                        if ($this->input->post("time_at") !="0:00"){
                            $array["time_at"] = date('H:i:s',strtotime($this->input->post("time_at")));
                        }

                        $array["create_date"] = date("Y-m-d H:i:s");
                        $array["modify_date"] = date("Y-m-d H:i:s");
                        $array["date"] = date('Y-m-d',strtotime($this->input->post("date")));
                        $array["location"] = $this->input->post("location");

                        $id = $this->activities_m->insert_activities($array);
                        
                        if($id) {
                            if(!empty($_FILES['attachment']['name'])){
                                $filesCount = count($_FILES['attachment']['name']);
                                for($i = 0; $i < $filesCount; $i++){
                                    $_FILES['attach']['name'] = $_FILES['attachment']['name'][$i];
                                    $_FILES['attach']['type'] = $_FILES['attachment']['type'][$i];
                                    $_FILES['attach']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
                                    $_FILES['attach']['error'] = $_FILES['attachment']['error'][$i];
                                    $_FILES['attach']['size'] = $_FILES['attachment']['size'][$i];

                                    $uploadPath = 'uploads/activities';
                                    $config['upload_path'] = $uploadPath;
                                    $config['allowed_types'] = 'gif|jpg|png';

                                    $this->load->library('upload', $config);
                                    $this->upload->initialize($config);
                                    if($this->upload->do_upload('attach')){
                                        $fileData = $this->upload->data();
                                        $uploadData[$i]['attachment'] = $fileData['file_name'];
                                        $uploadData[$i]['activitiesID'] = $id;
                                        $uploadData[$i]['create_date'] = date("Y-m-d H:i:s");
                                    }
                                }
                                if(!empty($uploadData)){
                                    $this->activitiesmedia_m->insert_batch_activitiesmedia($uploadData);
                                }
                            }
                        }

                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("activities/index"));
                    }
                } else {
                    $this->data["subview"] = "activities/add";
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = "activities/add";
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
            $usertypeID = $this->session->userdata('usertypeID');
            $userID = $this->session->userdata('loginuserID');

    		if((int)$id) {
                $activities = $this->activities_m->get_activities($id);
                if(($usertypeID == $activities->usertypeID && $userID == $activities->userID) || ($usertypeID == 1)) {
                    $this->activities_m->delete_activities($id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                }
    			redirect(base_url("activities/index"));
    		} else {
    			redirect(base_url("activities/index"));
    		}
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
	}

	public function delete_comment() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
    		$id = htmlentities(escapeString($this->uri->segment(3)));
            $usertypeID = $this->session->userdata('usertypeID');
            $userID = $this->session->userdata('loginuserID');

            if((int)$id) {
                $comment = $this->activitiescomment_m->get_activitiescomment($id);
                $activities = $this->activities_m->get_activities($comment->activitiesID);
                if(($usertypeID == $activities->usertypeID && $userID == $activities->userID) || ($usertypeID == 1)) {
                    $this->activitiescomment_m->delete_activitiescomment($id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                }
    			redirect(base_url("activities/index"));
    		} else {
    			redirect(base_url("activities/index"));
    		}
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
	}
}