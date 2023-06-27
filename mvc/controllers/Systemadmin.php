<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Systemadmin extends Admin_Controller {
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
		$this->load->model("systemadmin_m");
		$this->load->model("usertype_m");
		$this->load->model("manage_salary_m");
		$this->load->model("salaryoption_m");
		$this->load->model("salary_template_m");
		$this->load->model("hourly_template_m");
		$this->load->model("make_payment_m");
		$this->load->model("document_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('systemadmin', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("systemadmin_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'dob',
				'label' => $this->lang->line("systemadmin_dob"),
				'rules' => 'trim|required|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'sex',
				'label' => $this->lang->line("systemadmin_sex"),
				'rules' => 'trim|max_length[10]|xss_clean'
			),
			array(
				'field' => 'religion',
				'label' => $this->lang->line("systemadmin_religion"),
				'rules' => 'trim|max_length[25]|xss_clean'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("systemadmin_email"),
				'rules' => 'trim|required|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("systemadmin_phone"),
				'rules' => 'trim|min_length[5]|max_length[25]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("systemadmin_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'jod',
				'label' => $this->lang->line("systemadmin_jod"),
				'rules' => 'trim|required|max_length[10]|callback_date_valid|xss_clean'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("systemadmin_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),
			array(
				'field' => 'username',
				'label' => $this->lang->line("systemadmin_username"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean|callback_lol_username'
			),
			array(
				'field' => 'password',
				'label' => $this->lang->line("systemadmin_password"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|min_length[4]|xss_clean'
			)
		);
		return $rules;
	}


	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("systemadmin_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("systemadmin_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("systemadmin_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'systemadminID',
				'label' => $this->lang->line("systemadmin_systemadminID"),
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
		$user = array();
		if((int)$id) {
			$user = $this->systemadmin_m->get_systemadmin($id);
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
		$this->data['systemadmins'] = $this->systemadmin_m->get_systemadmin_by_usertype();
		$this->data["subview"] = "systemadmin/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js'
			)
		);
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "systemadmin/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				$array = array();
				$array["name"] = $this->input->post("name");
				$array["dob"] = date("Y-m-d", strtotime($this->input->post("dob")));
				$array["sex"] = $this->input->post("sex");
				$array["religion"] = $this->input->post("religion");
				$array["email"] = $this->input->post("email");
				$array["phone"] = $this->input->post("phone");
				$array["address"] = $this->input->post("address");
				$array["jod"] = date("Y-m-d", strtotime($this->input->post("jod")));
				$array["username"] = $this->input->post("username");
				$array['password'] = $this->systemadmin_m->hash($this->input->post("password"));
				$array["usertypeID"] = 1;
				$array["create_date"] = date("Y-m-d h:i:s");
				$array["modify_date"] = date("Y-m-d h:i:s");
				$array["create_userID"] = $this->session->userdata('loginuserID');
				$array["create_username"] = $this->session->userdata('username');
				$array["create_usertype"] = $this->session->userdata('usertype');
				$array["active"] = 1;
				$array['photo'] = $this->upload_data['file']['file_name'];

				$this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));

				$this->systemadmin_m->insert_systemadmin($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("systemadmin/index"));
			}
		} else {
			$this->data["subview"] = "systemadmin/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js'
			)
		);
		$usertype = $this->session->userdata("usertype");
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$this->data['systemadmin'] = $this->systemadmin_m->get_systemadmin($id);
			if($this->data['systemadmin']) {

				if($id != 1 && $this->session->userdata('loginuserID') != $this->data['systemadmin']->systemadminID) {
					if($_POST) {
						$rules = $this->rules();
						unset($rules[10]);
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "systemadmin/edit";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array = array();
							$array["name"] = $this->input->post("name");
							$array["dob"] = date("Y-m-d", strtotime($this->input->post("dob")));
							$array["sex"] = $this->input->post("sex");
							$array["religion"] = $this->input->post("religion");
							$array["email"] = $this->input->post("email");
							$array["phone"] = $this->input->post("phone");
							$array["address"] = $this->input->post("address");
							$array["jod"] = date("Y-m-d", strtotime($this->input->post("jod")));
							$array["modify_date"] = date("Y-m-d h:i:s");
							$array['username'] = $this->input->post('username');
							$array['photo'] = $this->upload_data['file']['file_name'];

							$this->systemadmin_m->update_systemadmin($array, $id);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("systemadmin/index"));

						}
					} else {
						$this->data["subview"] = "systemadmin/edit";
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

	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['systemadmin'] = $this->systemadmin_m->get_systemadmin($id);
			if($this->data['systemadmin']) {
				if($id != 1 && $this->session->userdata('loginuserID') != $this->data['systemadmin']->systemadminID) {
					if(config_item('demo') == FALSE) {
						if($this->data['systemadmin']->photo != 'default.png' && $this->data['systemadmin']->photo != 'defualt.png') {
							unlink(FCPATH.'uploads/images/'.$this->data['systemadmin']->photo);
						}
					}
					$this->systemadmin_m->delete_systemadmin($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("systemadmin/index"));
				} else {
					redirect(base_url("systemadmin/index"));
				}
			} else {
				redirect(base_url("systemadmin/index"));
			}
		} else {
			redirect(base_url("systemadmin/index"));
		}
	}

	public function view() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->getView($id);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}

	}

	private function getView($systemadminID) {
		if((int)$systemadminID) {
			$systemadmin = $this->systemadmin_m->get_systemadmin_by_usertype($systemadminID);
			$this->pluckInfo();
			$this->basicInfo($systemadmin);
			$this->salaryInfo($systemadmin);
			$this->paymentInfo($systemadmin);
			$this->documentInfo($systemadmin);
			if(count($systemadmin)) {
				if($systemadminID != 1 && $this->session->userdata('loginuserID') != $systemadmin->systemadminID) {
					$this->data["subview"] = "systemadmin/getView";
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

	private function pluckInfo() {
		$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
	}

	private function basicInfo($systemadmin) {
		if(count($systemadmin)) {
			$this->data['profile'] = $systemadmin;
		} else {
			$this->data['profile'] = [];
		}
	}

	private function salaryInfo($systemadmin) {
		if(count($systemadmin)) {
            $manageSalary = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $systemadmin->usertypeID, 'userID' => $systemadmin->systemadminID));
            if(count($manageSalary)) {
                $this->data['manage_salary'] = $manageSalary;
                if($manageSalary->salary == 1) {
                    $this->data['salary_template'] = $this->salary_template_m->get_single_salary_template(array('salary_templateID' => $manageSalary->template));
                    if($this->data['salary_template']) {
                        $this->db->order_by("salary_optionID", "asc");
                        $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salary_templateID' => $manageSalary->template));

                        $grosssalary = 0;
                        $totaldeduction = 0;
                        $netsalary = $this->data['salary_template']->basic_salary;
                        $orginalNetsalary = $this->data['salary_template']->basic_salary;
                        $grosssalarylist = array();
                        $totaldeductionlist = array();

                        if(count($this->data['salaryoptions'])) {
                            foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                if($salaryOption->option_type == 1) {
                                    $netsalary += $salaryOption->label_amount;
                                    $grosssalary += $salaryOption->label_amount;
                                    $grosssalarylist[$salaryOption->label_name] = $salaryOption->label_amount;
                                } elseif($salaryOption->option_type == 2) {
                                    $netsalary -= $salaryOption->label_amount;
                                    $totaldeduction += $salaryOption->label_amount;
                                    $totaldeductionlist[$salaryOption->label_name] = $salaryOption->label_amount;
                                }
                            }
                        }

                        $this->data['grosssalary'] = ($orginalNetsalary+$grosssalary);
                        $this->data['totaldeduction'] = $totaldeduction;
                        $this->data['netsalary'] = $netsalary;
                    } else {
                        $this->data['salary_template'] = [];
                        $this->data['salaryoptions'] = [];
                        $this->data['grosssalary'] = 0;
                        $this->data['totaldeduction'] = 0;
                        $this->data['netsalary'] = 0;
                    }
                } elseif($manageSalary->salary == 2) {
                    $this->data['hourly_salary'] = $this->hourly_template_m->get_single_hourly_template(array('hourly_templateID'=> $manageSalary->template));
                    if(count($this->data['hourly_salary'])) {
                        $this->data['grosssalary'] = 0;
                        $this->data['totaldeduction'] = 0;
                        $this->data['netsalary'] = $this->data['hourly_salary']->hourly_rate;
                    } else {
                    	$this->data['hourly_salary'] = [];
                        $this->data['grosssalary'] = 0;
                        $this->data['totaldeduction'] = 0;
                        $this->data['netsalary'] = 0;
                    }
                }
            } else {
            	$this->data['manage_salary'] = [];
            	$this->data['salary_template'] = [];
            	$this->data['salaryoptions'] = [];
            	$this->data['hourly_salary'] = [];
            	$this->data['grosssalary'] = 0;
                $this->data['totaldeduction'] = 0;
                $this->data['netsalary'] = 0;
            }
        } else {
        	$this->data['manage_salary'] = [];
        	$this->data['salary_template'] = [];
        	$this->data['salaryoptions'] = [];
        	$this->data['hourly_salary'] = [];
        	$this->data['grosssalary'] = 0;
            $this->data['totaldeduction'] = 0;
            $this->data['netsalary'] = 0;
        }
	}

	private function paymentInfo($systemadmin) {
		if(count($systemadmin)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['make_payments'] = $this->make_payment_m->get_order_by_make_payment(array('usertypeID' => $systemadmin->usertypeID, 'userID' => $systemadmin->systemadminID,'schoolyearID'=> $schoolyearID));
		} else {
			$this->data['make_payments'] = [];
		}
	}

	protected function rules_documentupload() {
		$rules = array(
			array(
				'field' => 'title',
				'label' => $this->lang->line("systemadmin_title"),
				'rules' => 'trim|required|xss_clean|max_length[128]'
			),
			array(
				'field' => 'file',
				'label' => $this->lang->line("systemadmin_file"),
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

	public function documentUpload() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		$retArray['errors'] = '';

		if(permissionChecker('systemadmin_add')) {
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
					$userID = $this->input->post('systemadminID');

					$array = array(
						'title' => $title,
						'file' => $file,
						'userID' => $userID,
						'usertypeID' => 1,
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

	private function documentInfo($systemadmin) {
		if(count($systemadmin)) {
			$this->data['documents'] = $this->document_m->get_order_by_document(array('usertypeID' => 1, 'userID' => $systemadmin->systemadminID));
		} else {
			$this->data['documents'] = [];
		}
	}

	public function download_document() {
		$documentID 	= htmlentities(escapeString($this->uri->segment(3)));
		$systemadminID 	= htmlentities(escapeString($this->uri->segment(4)));
		if((int)$documentID && (int)$systemadminID) {
			if(permissionChecker('systemadmin_add') && permissionChecker('systemadmin_delete')) {
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
				    	redirect(base_url('systemadmin/view/'.$systemadminID));
				    }
				} else {
					redirect(base_url('systemadmin/view/'.$systemadminID));
				}
			} else {
				redirect(base_url('systemadmin/view/'.$systemadminID));
			}
		} else {
			redirect(base_url('systemadmin/index'));
		}
	}

	public function delete_document() {
		$documentID 		= htmlentities(escapeString($this->uri->segment(3)));
		$systemadminID 	= htmlentities(escapeString($this->uri->segment(4)));
		if((int)$documentID && (int)$systemadminID) {
			if(permissionChecker('systemadmin_add') && permissionChecker('systemadmin_delete')) {
				$document = $this->document_m->get_single_document(array('documentID' => $documentID));
				if(count($document)) {
					if(config_item('demo') == FALSE) {
						if(file_exists(FCPATH.'uploads/document/'.$document->file)) {
							unlink(FCPATH.'uploads/document/'.$document->file);
						}
					}

					$this->document_m->delete_document($documentID);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url('systemadmin/view/'.$systemadminID));
				} else {
					redirect(base_url('systemadmin/view/'.$systemadminID));
				}
			} else {
				redirect(base_url('systemadmin/view/'.$systemadminID));
			}
		} else {
			redirect(base_url('systemadmin/index'));
		}
	}

	public function print_preview() {
		if(permissionChecker('systemadmin_view')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$this->data['systemadmin'] = $this->systemadmin_m->get_systemadmin_by_usertype($id);
				if(count($this->data['systemadmin'])) {
					if($id != 1 && $this->session->userdata('loginuserID') != $this->data['systemadmin']->systemadminID) {
						$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['systemadmin']->usertypeID));
						$this->data['panel_title'] = $this->lang->line('panel_title');
						$this->reportPDF('systemadminmodule.css', $this->data, 'systemadmin/print_preview');
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

	public function send_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('systemadmin_view')) {
			if($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$systemadminID = $this->input->post('systemadminID');
					if ((int)$systemadminID) {
						$this->data['systemadmin'] = $this->systemadmin_m->get_systemadmin_by_usertype($systemadminID);
						if(count($this->data["systemadmin"])) {
							$this->data['panel_title'] = $this->lang->line('panel_title');
							$email = $this->input->post('to');
							$subject = $this->input->post('subject');
							$message = $this->input->post('message');
							$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['systemadmin']->usertypeID));
							$this->reportSendToMail('systemadminmodule.css', $this->data, 'systemadmin/print_preview', $email, $subject, $message);
							$retArray['message'] = "Message";
							$retArray['status'] = TRUE;
							echo json_encode($retArray);
						    exit;
						} else {
							$retArray['message'] = $this->lang->line('systemadmin_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('systemadmin_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('systemadmin_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('systemadmin_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function lol_username() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$systemadmin_info = $this->systemadmin_m->get_single_systemadmin(array('systemadminID' => $id));
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->systemadmin_m->get_username($table, array("username" => $this->input->post('username'), "username !=" => $systemadmin_info->username));
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
				$user = $this->systemadmin_m->get_username($table, array("username" => $this->input->post('username')));
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

	public function unique_email() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$systemadmin_info = $this->systemadmin_m->get_single_systemadmin(array('systemadminID' => $id));
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->systemadmin_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $systemadmin_info->username ));
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
				$user = $this->systemadmin_m->get_username($table, array("email" => $this->input->post('email')));
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

	public function active() {
		if(permissionChecker('systemadmin_edit')) {
			$id = $this->input->post('id');
			$this->data['systemadmin'] = $this->systemadmin_m->get_systemadmin($id);
			if($id != 1 && $this->session->userdata('loginuserID') != $this->data['systemadmin']->systemadminID) {

				$status = $this->input->post('status');
				if($id != '' && $status != '') {
					if((int)$id) {
						if($status == 'chacked') {
							$this->systemadmin_m->update_systemadmin(array('active' => 1), $id);
							echo 'Success';
						} elseif($status == 'unchacked') {
							$this->systemadmin_m->update_systemadmin(array('active' => 0), $id);
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
}

/* End of file user.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/user.php */
