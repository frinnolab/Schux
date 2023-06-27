<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Income extends Admin_Controller {
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
	public function __construct() {
		parent::__construct();
		$this->load->model('income_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('income', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("income_name"),
				'rules' => 'trim|required|xss_clean|max_length[128]'
			),
			array(
				'field' => 'date',
				'label' => $this->lang->line("income_date"),
				'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid|callback_unique_date'
			),
			array(
				'field' => 'amount',
				'label' => $this->lang->line("income_amount"),
				'rules' => 'trim|required|xss_clean|numeric|max_length[10]|callback_valid_number'
			),
			array(
				'field' => 'file',
				'label' => $this->lang->line("income_file"),
				'rules' => 'trim|xss_clean|max_length[200]|callback_fileupload'
			),
			array(
				'field' => 'note',
				'label' => $this->lang->line("income_note"),
				'rules' => 'trim|xss_clean|max_length[128]'
			)
		);
		return $rules;
	}

	public function fileupload() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$income = array();
		if((int)$id) {
			$income = $this->income_m->get_income($id);
		}

		$new_file = "";

		if($_FILES["file"]['name'] !="") {
			$file_name = $_FILES["file"]['name'];
			$random = random19();
	    	$makeRandom = hash('sha512', $random.$this->input->post('name') . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
				$config['file_name'] = $new_file;
				$config['max_size'] = '5120';
				$config['max_width'] = '3000';
				$config['max_height'] = '3000';
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("file")) {
					$this->form_validation->set_message("fileupload", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("fileupload", "Invalid file");
	     		return FALSE;
			}
		} else {
			if(count($income)) {
				$this->upload_data['file'] = array('file_name' => $income->file);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
			return TRUE;
			}
		}
	}

	public function index() {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$this->data['alluser'] = getAllUserObjectWithStudentRelation(array('schoolyearID' => $schoolyearID));
		$this->data['incomes'] = $this->income_m->get_order_by_income(array('schoolyearID' => $schoolyearID));
		$this->data["subview"] = "income/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
				),
				'js' => array(
					'assets/datepicker/datepicker.js',
				)
			);
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "income/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$array['name'] 			= $this->input->post('name');
					$array['date'] 			= date("Y-m-d", strtotime($this->input->post("date")));
					$array['incomeday'] 	= date("d", strtotime($this->input->post("date")));
					$array['incomemonth'] 	= date("m", strtotime($this->input->post("date")));
					$array['incomeyear']	= date("Y", strtotime($this->input->post("date")));
					$array['amount'] 		= $this->input->post('amount');
					$array['file'] 			= $this->upload_data['file']['file_name'];
					$array['note'] 			= $this->input->post('note');
					$array['create_date'] 	= date('Y-m-d');
					$array['schoolyearID'] 	= $this->session->userdata('defaultschoolyearID');
					$array['userID'] 		= $this->session->userdata('loginuserID');
					$array['usertypeID'] 	= $this->session->userdata('usertypeID');
					
					$this->income_m->insert_income($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("income/index"));
				}
			} else {
				$this->data["subview"] = "income/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}

	}

	public function edit() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
				),
				'js' => array(
					'assets/datepicker/datepicker.js',
				)
			);

			$incomeID = htmlentities(escapeString($this->uri->segment(3)));
			if($incomeID) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$this->data['income'] = $this->income_m->get_single_income(array('incomeID' => $incomeID, 'schoolyearID' => $schoolyearID));
				if(count($this->data['income'])) {
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "income/edit";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array['name'] 			= $this->input->post('name');
							$array['date'] 			= date("Y-m-d", strtotime($this->input->post("date")));
							$array['incomeday'] 	= date("d", strtotime($this->input->post("date")));
							$array['incomemonth'] 	= date("m", strtotime($this->input->post("date")));
							$array['incomeyear']	= date("Y", strtotime($this->input->post("date")));
							$array['amount'] 		= $this->input->post('amount');
							$array['file'] 			= $this->upload_data['file']['file_name'];
							$array['note'] 			= $this->input->post('note');
							$array['userID'] 		= $this->session->userdata('loginuserID');
							$array['usertypeID'] 	= $this->session->userdata('usertypeID');

							$this->income_m->update_income($array, $incomeID);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("income/index"));
						}
					} else {
						$this->data["subview"] = "income/edit";
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
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) {
			$incomeID = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$incomeID) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$income = $this->income_m->get_single_income(array('incomeID' => $incomeID, 'schoolyearID' => $schoolyearID));
				if(count($income)) {
					if(config_item('demo') == FALSE) {
						if(file_exists(FCPATH.'uploads/images/'.$income->file)) {
							unlink(FCPATH.'uploads/images/'.$income->file);
						}
					}

					$this->income_m->delete_income($incomeID);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("income/index"));
				} else{
					redirect(base_url("income/index"));
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

	public function date_valid($date) {
		if($date) {
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
		}
		return TRUE;
	}

	public function valid_number() {
		if($this->input->post('amount') && $this->input->post('amount') < 0) {
			$this->form_validation->set_message("valid_number", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}

	public function download() {
		if(permissionChecker('income')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$income = $this->income_m->get_single_income(array('incomeID' => $id, 'schoolyearID' => $schoolyearID));
				if(count($income)) {
					$fileName = $income->file;
					$expFileName = explode('.', $fileName);
					$originalname = $income->name.'.'.$expFileName[1];
					$file = realpath('uploads/images/'.$income->file);
				    if (file_exists($file)) {
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
				    	redirect(base_url('income/index'));
				    }
				} else {
				   	redirect(base_url('income/index'));
				}
			} else {
				redirect(base_url('income/index'));
			}
		} else {
			redirect(base_url('income/index'));
		}
	}

	public function unique_date() {
		$date = strtotime($this->input->post('date'));
		$startdate = strtotime($this->data['schoolyearsessionobj']->startingdate);
		$endingdate = strtotime($this->data['schoolyearsessionobj']->endingdate);
		if($date != '') {
			if(($date < $startdate) || ($date > $endingdate)) {
				$this->form_validation->set_message('unique_date','The %s field is invalid.');
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}
}