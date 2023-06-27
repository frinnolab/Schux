<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parents extends Admin_Controller {
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
	public function __construct () {
		parent::__construct();
		$this->load->model("parents_m");
		$this->load->model("student_m");
		$this->load->model("usertype_m");
		$this->load->model("section_m");
		$this->load->model("document_m");
		$this->load->model("studentrelation_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('parents', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("parents_guargian_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'father_name',
				'label' => $this->lang->line("parents_father_name"),
				'rules' => 'trim|xss_clean|max_length[60]'
			),
			array(
				'field' => 'mother_name',
				'label' => $this->lang->line("parents_mother_name"),
				'rules' => 'trim|xss_clean|max_length[60]'
			),
			array(
				'field' => 'father_profession',
				'label' => $this->lang->line("parents_father_name"),
				'rules' => 'trim|xss_clean|max_length[40]'
			),
			array(
				'field' => 'mother_profession',
				'label' => $this->lang->line("parents_mother_name"),
				'rules' => 'trim|xss_clean|max_length[40]'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("parents_email"),
				'rules' => 'trim|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("parents_phone"),
				'rules' => 'trim|required|min_length[5]|max_length[25]|xss_clean'
			),
			array(
				'field' => 'father_phone',
				'label' => $this->lang->line("father_phone"),
				'rules' => 'trim|min_length[5]|max_length[25]|xss_clean'
			),
			array(
				'field' => 'father_alternate_phone',
				'label' => $this->lang->line("father_alternate_phone"),
				'rules' => 'trim|min_length[5]|max_length[25]|xss_clean'
			),
			array(
				'field' => 'mother_phone',
				'label' => $this->lang->line("mother_phone"),
				'rules' => 'trim|min_length[5]|max_length[25]|xss_clean'
			),
			array(
				'field' => 'mother_alternate_phone',
				'label' => $this->lang->line("mother_alternate_phone"),
				'rules' => 'trim|min_length[5]|max_length[25]|xss_clean'
			),
			array(
				'field' => 'father_country',
				'label' => $this->lang->line("father_country"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'mother_country',
				'label' => $this->lang->line("mother_country"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("parents_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("parents_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),
			array(
				'field' => 'username',
				'label' => $this->lang->line("parents_username"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean|callback_lol_username'
			),
			array(
				'field' => 'password',
				'label' => $this->lang->line("parents_password"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
			)
		);
		return $rules;
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("parents_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("parents_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("parents_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'parentsID',
				'label' => $this->lang->line("parents_parentsID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			)
		);
		return $rules;
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

	public function photoupload() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$user = [];
		if((int)$id) {
			$user = $this->parents_m->get_single_parents(array('parentsID' => $id));
		}

		$new_file = "default.png";
		if($_FILES["photo"]['name'] !="") {
			$file_name = $_FILES["photo"]['name'];
			$random = random19();
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
			if(count($user)) {
				$this->upload_data['file'] = array('file_name' => $user->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
				return TRUE;
			}
		}
	}

	public function index() {
		$myProfile = false;
		if($this->session->userdata('usertypeID') == 4) {
			if(!permissionChecker('parents_view')) {
				$myProfile = true;
			}
		}

		if($this->session->userdata('usertypeID') == 4 && $myProfile) {
			$id = $this->session->userdata('loginuserID');
			$this->getView($id);
		} else {
			$this->data['parents'] = $this->parents_m->get_parents();
			$this->data["subview"] = "parents/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function add() {
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data['form_validation'] = validation_errors();
				$this->data["subview"] = "parents/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				$array = array();
				for($i=0; $i<count($rules); $i++) {
					$array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
				}

				$array['password'] = $this->student_m->hash($this->input->post("password"));
				$array['usertypeID'] = 4;
				$array["create_date"] = date("Y-m-d h:i:s");
				$array["modify_date"] = date("Y-m-d h:i:s");
				$array["create_userID"] = $this->session->userdata('loginuserID');
				$array["create_username"] = $this->session->userdata('username');
				$array["create_usertype"] = $this->session->userdata('usertype');
				$array["active"] = 1;
				$array['photo'] = $this->upload_data['file']['file_name'];

				// For Email
				$this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));
				$this->parents_m->insert_parents($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("parents/index"));

			}
		} else {
			$this->data["subview"] = "parents/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$this->data['parents'] = $this->parents_m->get_single_parents(array('parentsID' => $id));
			if($this->data['parents']) {
				if($_POST) {
					$rules = $this->rules();
					unset($rules[16]);
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "parents/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array = array();
						for($i=0; $i<count($rules); $i++) {
							$array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
						}
						$array["modify_date"] = date("Y-m-d h:i:s");
						$array['photo'] = $this->upload_data['file']['file_name'];

						$this->parents_m->update_parents($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("parents/index"));
					}
				} else {
					$this->data["subview"] = "parents/edit";
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

	public function view() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$this->getView($id);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	private function getView($parentsID) {
		if ((int)$parentsID) {
			$parents = $this->parents_m->get_single_parents(array('parentsID' => $parentsID));
			$this->plucInfo();
			$this->basicInfo($parents);
			$this->childrenInfo($parents);
			$this->documentInfo($parents);

			if(count($parents)) {
				$this->data["subview"] = "parents/getView";
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

	private function basicInfo($parents) {
		if(count($parents)) {
			$this->data['profile'] = $parents;
		} else {
			$this->data['profile'] = [];
		}
	}

	private function childrenInfo($parents) {
		$this->data['childrens'] = [];
		if(count($parents)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->db->order_by('student.classesID', 'asc');
			$this->data['childrens'] = $this->studentrelation_m->general_get_order_by_student(array('parentID' => $parents->parentsID, 'srschoolyearID' => $schoolyearID));
		}
	}

	private function plucInfo() {
		$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
		$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
		$this->data['sections'] = pluck($this->section_m->get_section(),'section','sectionID');
	}

	public function documentUpload() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		$retArray['errors'] = '';

		if(permissionChecker('parents_add')) {
			if($_POST) {
				$rules = $this->rules_documentupload();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray['errors'] = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$title = $this->input->post('title');
					$file = $this->upload_data['file']['file_name'];
					$userID = $this->input->post('parentsID');

					$array = array(
						'title' => $title,
						'file' => $file,
						'userID' => $userID,
						'usertypeID' => 4,
						"create_date" => date("Y-m-d H:i:s"),
						"create_userID" => $this->session->userdata('loginuserID'),
						"create_usertypeID" => $this->session->userdata('usertypeID')
					);

					$this->document_m->insert_document($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));

					$retArray['status'] = TRUE;
					$retArray['render'] = 'Success';
				    echo json_encode($retArray);
				    exit;
				}
			} else {
				$retArray['status'] = FALSE;
				$retArray['render'] = 'Error';
			    echo json_encode($retArray);
			    exit;
			}
		} else {
			$retArray['status'] = FALSE;
			$retArray['render'] = 'Permission Denay.';
		    echo json_encode($retArray);
		    exit;
		}
	} 

	protected function rules_documentupload() {
		$rules = array(
			array(
				'field' => 'title',
				'label' => $this->lang->line("parents_title"),
				'rules' => 'trim|required|xss_clean|max_length[128]'
			),
			array(
				'field' => 'file',
				'label' => $this->lang->line("parents_file"),
				'rules' => 'trim|xss_clean|max_length[200]|callback_unique_document_upload'
			)
		);

		return $rules;
	}

	public function unique_document_upload() {
		$new_file = '';
		if($_FILES["file"]['name'] !="") {
			$file_name = $_FILES["file"]['name'];
			$random = random19();
	    	$makeRandom = hash('sha512', $random.(strtotime(date('Y-m-d H:i:s'))). config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/documents";
				$config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
				$config['file_name'] = $new_file;
				$config['max_size'] = '5120';
				$config['max_width'] = '10000';
				$config['max_height'] = '10000';
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("file")) {
					$this->form_validation->set_message("unique_document_upload", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_document_upload", "Invalid file");
	     		return FALSE;
			}
		} else {
			$this->form_validation->set_message("unique_document_upload", "The file is required.");
			return FALSE;
		}
	}

	private function documentInfo($parents) {
		if(count($parents)) {
			$this->data['documents'] = $this->document_m->get_order_by_document(array('usertypeID' => 4, 'userID' => $parents->parentsID));
		} else {
			$this->data['documents'] = [];
		}
	}

	public function download_document() {
		$documentID = htmlentities(escapeString($this->uri->segment(3)));
		$parentsID 	= htmlentities(escapeString($this->uri->segment(4)));
		if((int)$documentID && (int)$parentsID) {
			if((permissionChecker('parents_add') && permissionChecker('parents_delete')) || ($this->session->userdata('usertypeID') == 4 && $this->session->userdata('loginuserID') == $parentsID)) {
				$document = $this->document_m->get_single_document(array('documentID' => $documentID));
				if(count($document)) {
					$file = realpath('uploads/documents/'.$document->file);
				    if (file_exists($file)) {
				    	$expFileName = explode('.', $file);
						$originalname = ($document->title).'.'.end($expFileName);
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
				    	redirect(base_url('parents/view/'.$parentsID));
				    }
				} else {
					redirect(base_url('parents/view/'.$parentsID));
				}
			} else {
				redirect(base_url('parents/view/'.$parentsID));
			}
		} else {
			redirect(base_url('parents/index'));
		}
	}

	public function delete_document() {
		$documentID = htmlentities(escapeString($this->uri->segment(3)));
		$parentsID 	= htmlentities(escapeString($this->uri->segment(4)));
		if((int)$documentID && (int)$parentsID) {
			if(permissionChecker('parents_add') && permissionChecker('parents_delete')) {
				$document = $this->document_m->get_single_document(array('documentID' => $documentID));
				if(count($document)) {
					if(config_item('demo') == FALSE) {
						if(file_exists(FCPATH.'uploads/document/'.$document->file)) {
							unlink(FCPATH.'uploads/document/'.$document->file);
						}
					}

					$this->document_m->delete_document($documentID);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url('parents/view/'.$parentsID));
				} else {
					redirect(base_url('parents/view/'.$parentsID));
				}
			} else {
				redirect(base_url('parents/view/'.$parentsID));
			}
		} else {
			redirect(base_url('parents/index'));
		}
	}

	public function print_preview() {
		if(permissionChecker('parents_view') || (($this->session->userdata('usertypeID') == 4) && permissionChecker('parents') && ($this->session->userdata('loginuserID') == htmlentities(escapeString($this->uri->segment(3)))))) {
			$parentsID = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$parentsID) {
				$this->data['parents'] = $this->parents_m->get_single_parents(array('parentsID' => $parentsID));
				if(count($this->data['parents'])) {
					$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['parents']->usertypeID));
					$this->data['panel_title'] = $this->lang->line('panel_title');
					$this->reportPDF('parentsmodule.css',$this->data, 'parents/print_preview');
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

	public function send_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('parents_view') || (($this->session->userdata('usertypeID') == 4) && permissionChecker('parents') && ($this->session->userdata('loginuserID') == $this->input->post('parentsID')))) {
			if($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$parentsID = $this->input->post('parentsID');
					if ((int)$parentsID) {
						$this->data['parents'] = $this->parents_m->get_single_parents(array('parentsID' => $parentsID));
						if($this->data['parents']) {
							$email = $this->input->post('to');
							$subject = $this->input->post('subject');
							$message = $this->input->post('message');
							$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['parents']->usertypeID));
							$this->reportSendToMail('parentsmodule.css', $this->data, 'parents/print_preview', $email, $subject, $message);
							$retArray['message'] = "Success";
							$retArray['status'] = TRUE;
							echo json_encode($retArray);
						    exit;
						} else {
							$retArray['message'] = $this->lang->line('parents_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('parents_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('parents_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('parents_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$this->data['parents'] = $this->parents_m->get_single_parents(array('parentsID' => $id));
			if($this->data['parents']) {
				if(config_item('demo') == FALSE) {
					if($this->data['parents']->photo != 'default.png' && $this->data['parents']->photo != 'defualt.png') {
						unlink(FCPATH.'uploads/images/'.$this->data['parents']->photo);
					}
				}
				$this->parents_m->delete_parents($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("parents/index"));
			} else {
				redirect(base_url("parents/index"));
			}
		} else {
			redirect(base_url("parents/index"));
		}
	}

	public function unique_email() {
		if($this->input->post('email')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$parents = $this->parents_m->get_single_parents(array('parentsID' => $id));
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->parents_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $parents->username ));
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
			} else {
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
	}

	public function lol_username() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$parents_info = $this->parents_m->get_single_parents(array('parentsID' => $id));
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_username($table, array("username" => $this->input->post('username'), "username !=" => $parents_info->username ));
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
		} else {
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

	public function active() {
		if(permissionChecker('parents_edit')) {
			$id = $this->input->post('id');
			$status = $this->input->post('status');
			if($id != '' && $status != '') {
				if((int)$id) {
					$parent = $this->parents_m->get_single_parents(array('parentsID' => $id));
					if(count($parent)) {
						if($status == 'chacked') {
							$this->parents_m->update_parents(array('active' => 1), $id);
							echo 'Success';
						} elseif($status == 'unchacked') {
							$this->parents_m->update_parents(array('active' => 0), $id);
							echo 'Success';
						} else {
							echo "Error";
						}
					} else {
						echo 'Error';
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
}