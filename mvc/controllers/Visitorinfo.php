<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitorinfo extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("visitorinfo_m");
		$this->load->model('usertype_m');
		$this->load->model('systemadmin_m');
		$this->load->model('student_m');
		$this->load->model('parents_m');
		$this->load->model('teacher_m');
		$this->load->model('user_m');
		$this->load->model('studentrelation_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('visitorinfo', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'email_id',
				'label' => $this->lang->line("email_id"),
				'rules' => 'trim|required|max_length[40]|valid_email|xss_clean'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("phone"),
				'rules' => 'trim|required|max_length[25]|min_length[5]|xss_clean'
			),
			array(
				'field' => 'company_name',
				'label' => $this->lang->line("company_name"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
            array(
				'field' => 'to_meet_usertypeID',
				'label' => $this->lang->line("visitor_usrtype"),
				'rules' => 'trim|required|max_length[200]|xss_clean'
			),
			array(
				'field' => 'coming_from',
				'label' => $this->lang->line("coming_from"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'to_meet_personID',
				'label' => $this->lang->line("to_meet"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'representing',
				'label' => $this->lang->line("representing"),
				'rules' => 'trim|required|max_length[40]|xss_clean'
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

		$this->data['usertypes'] = $this->usertype_m->get_usertype();
		$usertypeID = $this->input->post("usertypeID");
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		$this->data['to_meet'] = 0;
        $this->data['passes'] = $this->visitorinfo_m->get_order_by_visitorinfo(array('schoolyearID' => $schoolyearID));
        $mapUsertype = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
        $mapArray = [];

        $systemadmins = $this->systemadmin_m->get_systemadmin();
        if(count($systemadmins)) {
            foreach ($systemadmins as $systemadmin) {
                $mapArray[$systemadmin->usertypeID][$systemadmin->systemadminID] = array($systemadmin->name, $mapUsertype[$systemadmin->usertypeID]);
            }
        }

        $teachers = $this->teacher_m->get_teacher();
        if(count($teachers)) {
            foreach ($teachers as $teacher) {
                $mapArray[$teacher->usertypeID][$teacher->teacherID] = array($teacher->name, $mapUsertype[$teacher->usertypeID]);
            }
        }

        $students = $this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID));
        if(count($students)) {
            foreach ($students as $student) {
                $mapArray[$student->usertypeID][$student->studentID] = array($student->name, $mapUsertype[$student->usertypeID]);
            }
        }

        $parents = $this->parents_m->get_parents();
        if(count($parents)) {
            foreach ($parents as $parent) {
                $mapArray[$parent->usertypeID][$parent->parentsID] = array($parent->name, $mapUsertype[$parent->usertypeID]);
            }
        }

        $users = $this->user_m->get_order_by_user();
        if(count($users)) {
            foreach ($users as $user) {
                $mapArray[$user->usertypeID][$user->userID] = array($user->name, $mapUsertype[$user->usertypeID]);
            }
        }

        $this->data['allUsers'] = $mapArray;
        $retArray['status'] = FALSE;
		if($_POST) {
			$rules = $this->rules();
			$array = array();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
            	$retArray = $this->form_validation->error_array();
			    $retArray['status'] = FALSE;
			    echo json_encode($retArray);
			    exit;
            } else {
                for($i=0; $i<count($rules); $i++) {
                    $array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
                }

                $array["check_in"] = date("Y-m-d h:i:s");
                $array["status"] = 0;
                $array["schoolyearID"] = $schoolyearID;
                $encoded_data = $_POST['image'];
                $binary_data = base64_decode( $encoded_data );
                $file_name_rename = random19();
                $new_file = "visitor".$file_name_rename.'.jpeg';
                $result = file_put_contents( 'uploads/visitor/'.$new_file, $binary_data );
                $array["photo"] = $new_file;
                if ($result) {
                    $id = $this->visitorinfo_m->insert_visitorinfo($array);
                    if($id) {
                        $this->session->set_flashdata('success', $this->lang->line("upload_success"));
                        $retArray = array(
                            'id' => $id,
                            'to_meet' => (isset($mapArray[$array["to_meet_usertypeID"]][$array["to_meet_personID"]]) ? $mapArray[$array["to_meet_usertypeID"]][$array["to_meet_personID"]][0] : ''),
                            'to_meet_type' => (isset($mapArray[$array["to_meet_usertypeID"]][$array["to_meet_personID"]]) ? $mapArray[$array["to_meet_usertypeID"]][$array["to_meet_personID"]][1] : ''),
                        );

                        $retArray['status'] = TRUE;
			    		echo json_encode($retArray);
			    		exit;
                    } else {
                    	$retArray['error'] = $this->lang->line("upload_error_data");
			    		echo json_encode($retArray);
			    		exit;
                    }
                } else {
                    $retArray['error'] = $this->lang->line("upload_error");
		    		echo json_encode($retArray);
		    		exit;
                }
            }
	    } else {
			$this->data["subview"] = "visitorinfo/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function usercall() {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$usertypeID = $this->input->post('id');
		if((int)$usertypeID) {
			$this->data['users'] = array();
			if($usertypeID == 1) {
				$this->data['users'] = $this->systemadmin_m->get_systemadmin();
				echo "<option value='0'>", $this->lang->line("visitor_select_user"),"</option>";
				if(count($this->data['users'])) {
					foreach ($this->data['users'] as $value) {
						echo "<option value=\"$value->systemadminID\">",$value->name,"</option>";
					}
				}
			} elseif($usertypeID == 2) {
				$this->data['users'] = $this->teacher_m->get_teacher();
				echo "<option value='0'>", $this->lang->line("visitor_select_user"),"</option>";
				if(count($this->data['users'])) {
					foreach ($this->data['users'] as $value) {
						echo "<option value=\"$value->teacherID\">",$value->name,"</option>";
					}
				}
			} elseif($usertypeID == 3) {
				$this->data['users'] = $this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID));
				echo "<option value='0'>", $this->lang->line("visitor_select_user"),"</option>";
				if(count($this->data['users'])) {
					foreach ($this->data['users'] as $value) {
						echo "<option value=\"$value->studentID\">",$value->name,"</option>";
					}
				}
			} elseif($usertypeID == 4) {
				$this->data['users'] = $this->parents_m->get_parents();
				echo "<option value='0'>", $this->lang->line("visitor_select_user"),"</option>";
				if(count($this->data['users'])) {
					foreach ($this->data['users'] as $value) {
						echo "<option value=\"$value->parentsID\">",$value->name,"</option>";
					}
				}
			} else {
				$this->data['users'] = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
				echo "<option value='0'>", $this->lang->line("visitor_select_user"),"</option>";
				if(count($this->data['users'])) {
					foreach ($this->data['users'] as $value) {
						echo "<option value=\"$value->userID\">",$value->name,"</option>";
					}
				}
			}
		}
	}

	public function logout() {
		$id = $this->input->post('visitorID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$id) {
			$visitorinfo = $this->visitorinfo_m->get_single_visitorinfo(array('visitorID' => $id, 'schoolyearID' => $schoolyearID));
			if(count($visitorinfo)) {
				$array['check_out'] = date("Y-m-d h:i:s");
				$array['status'] = 1;
				$this->visitorinfo_m->update_visitorinfo($array, $id);
	    		$this->session->set_flashdata('success', $this->lang->line("checkout_success"));
			} else {
				$this->session->set_flashdata('error', $this->lang->line("invalid_id"));
			}
		} else {
			$this->session->set_flashdata('error', $this->lang->line("invalid_id"));
		}

		echo base_url("visitorinfo/index");
	}

	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$visitorinfo = $this->visitorinfo_m->get_single_visitorinfo(array('visitorID' => $id, 'schoolyearID' => $schoolyearID));
			if(count($visitorinfo)) {
				$this->visitorinfo_m->delete_visitorinfo($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("visitorinfo/index"));
			}
		} else {
			redirect(base_url("visitorinfo/index"));
		}
	}

	public function view() {
		$id = $this->input->post('visitorinfoID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$arr['returnstatus'] = FALSE;
		if((int)$id) {
			$usertypes = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
			$visitorinfo = $this->visitorinfo_m->get_single_visitorinfo(array('visitorID' => $id, 'schoolyearID' => $schoolyearID));
			if(count($visitorinfo)) {
				$arr = array(
				  	'id'=>$visitorinfo->visitorID,
				  	'photo'=>$visitorinfo->photo,
				  	'phone'=>$visitorinfo->phone,
				  	'email_id'=>$visitorinfo->email_id,
				  	'name'=>$visitorinfo->name,
	              	'to_meet'=> getNameByUsertypeIDAndUserID($visitorinfo->to_meet_usertypeID, $visitorinfo->to_meet_personID),
	              	'to_meet_type'=> (isset($usertypes[$visitorinfo->to_meet_usertypeID]) ? $usertypes[$visitorinfo->to_meet_usertypeID] : ''),
				  	'company_name'=>$visitorinfo->company_name,
				  	'coming_from'=>$visitorinfo->coming_from,
				  	'representing'=>$visitorinfo->representing,
				);
				$arr['returnstatus'] = TRUE;
				echo json_encode($arr);
				exit;
			} else {
				$arr['error'] = 'Invalid visitor id';
				echo json_encode($arr);
				exit;
			}
		} else {
			$arr['error'] = 'Invalid visitor id';
			echo json_encode($arr);
			exit;
		}
	}
}