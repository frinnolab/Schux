<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Childcare extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("childcare_m");
		$this->load->model('usertype_m');
		$this->load->model('systemadmin_m');
		$this->load->model('student_m');
		$this->load->model('parents_m');
		$this->load->model('teacher_m');
		$this->load->model('user_m');
		$this->load->model('studentrelation_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('childcare', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("classesID"),
				'rules' => 'trim|numeric|max_length[11]|xss_clean|callback_unique_classes'
			),
			array(
				'field' => 'userID',
				'label' => $this->lang->line("userID"),
				'rules' => 'trim|numeric|required|xss_clean|callback_unique_userID'
			),
			array(
				'field' => 'receiver_name',
				'label' => $this->lang->line("receiver_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),

            array(
				'field' => 'drop_date',
				'label' => $this->lang->line("drop_date"),
				'rules' => 'trim|required|xss_clean|max_length[60]|callback_date_valid|callback_unique_date'
			),
            array(
				'field' => 'receive_date',
				'label' => $this->lang->line("receive_date"),
				'rules' => 'trim|xss_clean|max_length[60]'
			),
            array(
				'field' => 'drop_time',
				'label' => $this->lang->line("add_drop_time"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
            array(
				'field' => 'receive_time',
				'label' => $this->lang->line("add_receive_time"),
				'rules' => 'trim|xss_clean|max_length[60]'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("phone"),
				'rules' => 'trim|required|max_length[25]|min_length[5]|xss_clean'
			),
			array(
				'field' => 'document',
				'label' => $this->lang->line("document"),
				'rules' => 'trim|xss_clean'
			),
            array(
				'field' => 'comment',
				'label' => $this->lang->line("comment"),
				'rules' => 'trim|max_length[40]|xss_clean'
			)
		);
		return $rules;
	}

	public function index() {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->data['drop_receive'] = $this->childcare_m->get_join_childcare_all($schoolyearID);
		$this->data["subview"] = "childcare/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
	            'css' => array(
	                'assets/timepicker/timepicker.css',
	                'assets/datepicker/datepicker.css',
	                'assets/select2/css/select2.css',
	                'assets/select2/css/select2-bootstrap.css'
	            ),
	            'js' => array(
	                'assets/datepicker/datepicker.js',
	                'assets/select2/select2.js',
	                'assets/timepicker/timepicker.js'
	            )
	        );

			$schoolyearID = $this->session->userdata('defaultschoolyearID');
	        $this->data['parents'] = $this->parents_m->get_parents();
	        $this->data['classes'] = $this->classes_m->get_classes();

	        if($this->input->post('classesID')) {
	        	$this->data['students'] = $this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID, 'srclassesID' => $this->input->post('classesID')));
	        } else {
	        	$this->data['students'] = [];
	        }

			if($_POST) {
				// return print_r(json_encode($this->input->post()));
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) { 
					$this->data["subview"] = "childcare/add";
					$this->load->view('_layout_main', $this->data);			
				} else {
					for($i=0; $i < count($rules); $i++) {
	                    $array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
	                }
	                
	                if($this->input->post('userID')) {
	                	$student = $this->student_m->get_single_student(array('studentID' => $this->input->post('userID')));
	                	if(count($student)) {
	                		$data['parentID'] = $student->parentID;
	                	} else {
	                		$data['parentID'] = 0;
	                	}
	                }

	                $received_at = NULL;
	                if(!empty($array['receive_date'])) {
	                	$received_at = date("Y-m-d H:i:s", strtotime($array['receive_date']." ". $array['receive_time']));
					}
					$file = time();
					$file_name = $file.'.pdf';

	                $data["classesID"] = $array['classesID'];
	                $data["userID"] = $array['userID'];
	                $data["receiver_name"] = $array['receiver_name'];
	                $data["phone"] = $array['phone'];
	                $data["dropped_at"] = date("Y-m-d H:i:s", strtotime($array['drop_date']." ". $array['drop_time']));
	                $data["received_at"] = $received_at;
	                $data["document"] = $file_name;
	                $data["usertypeID"] = 3;
	                $data["schoolyearID"] = $schoolyearID;
					$data["comment"] = $array['comment'];
					
					$config['upload_path']       = './uploads/documents/';
					$config['allowed_types']     = 'pdf';
					$config['max_size']          = '5000';
					$config['file_ext_tolower']  = TRUE;
					$config['file_name']  = $file;
					$this->load->library('upload', $config);

					if(!$this->upload->do_upload('document')){
						$this->session->set_flashdata('error', $this->lang->line('menu_error'));
						redirect(base_url('childcare/index'));
					} else {
						$this->childcare_m->insert_childcare($data);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url('childcare/index'));
					}
				}
			} else {
				$this->data["subview"] = "childcare/add";
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
	                'assets/timepicker/timepicker.css',
	                'assets/datepicker/datepicker.css',
	                'assets/select2/css/select2.css',
	                'assets/select2/css/select2-bootstrap.css'
	            ),
	            'js' => array(
	                'assets/datepicker/datepicker.js',
	                'assets/select2/select2.js',
	                'assets/timepicker/timepicker.js'
	            )
	        );

			$id = htmlentities(escapeString($this->uri->segment(3)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			
			if((int)$id) {
				$this->data['careinfo'] = $this->childcare_m->get_single_childcare(array('childcareID' => $id, 'schoolyearID' => $schoolyearID));
				if(count($this->data['careinfo'])) {
			        $this->data['parents'] = $this->parents_m->get_parents();
			        $this->data['classes'] = $this->classes_m->get_classes();

			        if($this->input->post('classesID')) {
			        	$this->data['students'] = $this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID, 'srclassesID' => $this->input->post('classesID')));
			        } else {
			        	$this->data['students'] = $this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID, 'srclassesID' => $this->data['careinfo']->classesID));
			        }
					 
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "childcare/edit";
							$this->load->view('_layout_main', $this->data);			
						} else {
							for($i=0; $i<count($rules); $i++) {
			                    $array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
			                }
			                
			                if($this->input->post('userID')) {
			                	$student = $this->student_m->get_single_student(array('studentID' => $this->input->post('userID')));
			                	if(count($student)) {
			                		$data['parentID'] = $student->parentID;
			                	} else {
			                		$data['parentID'] = 0;
			                	}
			                }

			                $received_at = NULL;
			                if(!empty($array['receive_date'])) {
			                	$received_at = date("Y-m-d H:i:s", strtotime($array['receive_date']." ". $array['receive_time']));
			                }

			                $data["classesID"] = $array['classesID'];
			                $data["userID"] = $array['userID'];
			                $data["receiver_name"] = $array['receiver_name'];
			                $data["phone"] = $array['phone'];
			                $data["dropped_at"] = date("Y-m-d H:i:s", strtotime($array['drop_date']." ". $array['drop_time']));
			                $data["received_at"] = $received_at;
							$data["comment"] = $array['comment'];
							if (isset($array['document'])) {
								$data["document"] = $array['document'];
								
								$config['upload_path']       = './uploads/documents/';
								$config['allowed_types']     = 'pdf';
								$config['max_size']          = '5000';
								$config['file_ext_tolower']  = TRUE;
								$config['file_name']  = $file;
								$this->load->library('upload', $config);

								if(!$this->upload->do_upload('document')){
									$this->session->set_flashdata('error', $this->lang->line('menu_error'));
									redirect(base_url('childcare/index'));
								} else {
									$this->childcare_m->insert_childcare($data);
									$this->session->set_flashdata('success', $this->lang->line('menu_success'));
									redirect(base_url('childcare/index'));
								}
							}

			                $this->childcare_m->update_childcare($data, $id);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("childcare/index"));
						}
					} else {
						$this->data["subview"] = "childcare/edit";
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

	public function all_student() {
		$classesID = $this->input->post('classes');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$classesID) {
            $this->data['users'] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
            echo "<option value='0'>", $this->lang->line("select_student"),"</option>";
            if(count($this->data['users'])) {
                foreach ($this->data['users'] as $value) {
                    echo "<option value=\"$value->srstudentID\">",$value->srname.' - '.$this->lang->line('child_care_roll').' - '.$value->roll,"</option>";
                }
            }
		}
	}

	public function delete() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$id) {
				$this->data['childcare'] = $this->childcare_m->get_single_childcare(array('childcareID' => $id, 'schoolyearID' => $schoolyearID));
				if(count($this->data['childcare'])) {
					$this->childcare_m->delete_childcare($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				}
				redirect(base_url("childcare/index"));
			} else {
				redirect(base_url("childcare/index"));
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function unique_classes() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classes", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_userID() {
		if($this->input->post('userID') == 0) {
			$this->form_validation->set_message("unique_userID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function date_valid($date) {
		if(!empty($date)) {
			if(strlen($date) < 10) {
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
		} else {
			$this->form_validation->set_message("date_valid", "The %s field is required.");
		     return FALSE;
		}
	}

	public function unique_date($date) {
		$startingdate = $this->data['schoolyearsessionobj']->startingdate;
		$endingdate = $this->data['schoolyearsessionobj']->endingdate;

		if($date != '') {
			if((strtotime($date) < strtotime($startingdate)) || (strtotime($date) > strtotime($endingdate))) {
				$this->form_validation->set_message("unique_date", "The %s date is invalid .");
			    return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}
}
