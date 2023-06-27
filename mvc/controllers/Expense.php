<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expense extends Admin_Controller {
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
		$this->load->model("expense_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('expense', $language);
	}

	public function index() {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$this->data['alluser'] = getAllUserObjectWithStudentRelation(array('schoolyearID' => $schoolyearID));
		$this->data['expenses'] = $this->expense_m->get_order_by_expense(array('schoolyearID' => $schoolyearID));
		$this->data["subview"] = "expense/index";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
				 array(
					'field' => 'expense',
					'label' => $this->lang->line("expense_expense"),
					'rules' => 'trim|required|xss_clean|max_length[128]'
				),
				array(
					'field' => 'date',
					'label' => $this->lang->line("expense_date"),
					'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid|callback_unique_date'
				),
				array(
					'field' => 'amount',
					'label' => $this->lang->line("expense_amount"),
					'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_valid_number'
				),
				array(
					'field' => 'file',
					'label' => $this->lang->line("expense_file"),
					'rules' => 'trim|xss_clean|max_length[200]|callback_fileupload'
				),
				array(
					'field' => 'note',
					'label' => $this->lang->line("expense_note"),
					'rules' => 'trim|max_length[200]|xss_clean'
				)
			);
		return $rules;
	}

	public function fileupload() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$expense = [];
		if((int)$id) {
			$expense = $this->expense_m->get_expense($id);
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
			if(count($expense)) {
				$this->upload_data['file'] = array('file_name' => $expense->file);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
			return TRUE;
			}
		}
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
					$this->data["subview"] = "expense/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$array = array(
						"expense" 		=> $this->input->post("expense"),
						"amount" 		=> $this->input->post("amount"),
						"file" 			=> $this->upload_data['file']['file_name'],
						"note" 			=> $this->input->post("note"),
						"create_date" 	=> date("Y-m-d"),
						"date" 			=> date("Y-m-d", strtotime($this->input->post("date"))),
						"expenseday" 	=> date("d", strtotime($this->input->post("date"))),
						"expensemonth" 	=> date("m", strtotime($this->input->post("date"))),
						"expenseyear" 	=> date("Y", strtotime($this->input->post("date"))),
						'usertypeID' 	=> $this->session->userdata('usertypeID'),
						'uname' 		=> $this->session->userdata('name'),
						'userID' 		=> $this->session->userdata('loginuserID'),
						'schoolyearID' 	=> $this->session->userdata('defaultschoolyearID')
					);
					$this->expense_m->insert_expense($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("expense/index"));
				}
			} else {
				$this->data["subview"] = "expense/add";
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

			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');	
				$this->data['expense'] = $this->expense_m->get_single_expense(array('expenseID' => $id, 'schoolyearID' => $schoolyearID));
				if($this->data['expense']) {
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "expense/edit";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array = array(
								"expense" 		=> $this->input->post("expense"),
								"amount" 		=> $this->input->post("amount"),
								"file" 			=> $this->upload_data['file']['file_name'],
								"note" 			=> $this->input->post("note"),
								"date" 			=> date("Y-m-d", strtotime($this->input->post("date"))),
								"expenseday" 	=> date("d", strtotime($this->input->post("date"))),
								"expensemonth" 	=> date("m", strtotime($this->input->post("date"))),
								"expenseyear" 	=> date("Y", strtotime($this->input->post("date"))),
								'usertypeID' 	=> $this->session->userdata('usertypeID'),
								'uname' 		=> $this->session->userdata('name'),
								'userID' 		=> $this->session->userdata('loginuserID'),
							);

							$this->expense_m->update_expense($array, $id);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("expense/index"));
						}
					} else {
						$this->data["subview"] = "expense/edit";
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
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$expense = $this->expense_m->get_single_expense(array('expenseID' => $id, 'schoolyearID' => $schoolyearID));
				if(count($expense)) {
					$this->expense_m->delete_expense($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("expense/index"));
				} else {
					redirect(base_url("expense/index"));
				}
			} else {
				redirect(base_url("expense/index"));
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function download() {
		if(permissionChecker('expense')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$expense = $this->expense_m->get_single_expense(array('expenseID' => $id, 'schoolyearID' => $schoolyearID));
				if(count($expense)) {
					$fileName = $expense->file;
					$expFileName = explode('.', $fileName);
					$originalname = $expense->expense.'.'.$expFileName[1];
					$file = realpath('uploads/images/'.$expense->file);
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
				    	redirect(base_url('expense/index'));
				    }
				} else {
				   	redirect(base_url('expense/index'));
				}
			} else {
				redirect(base_url('expense/index'));
			}
		} else {
			redirect(base_url('expense/index'));
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

	public function valid_number() {
		if($this->input->post('amount') && $this->input->post('amount') < 0) {
			$this->form_validation->set_message("valid_number", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
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