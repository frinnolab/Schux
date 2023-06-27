<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentreport extends Admin_Controller {
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
	protected $_bloodArray = array(
		'0' => 'A+',
		'1' => 'A-',
		'2' => 'B+',
		'3' => 'B-',
		'4' => 'AB+',
		'5' => 'AB-',
		'6' => 'O+',
		'7' => 'O-',
		'8' => 'Unknown',
	); 

	function __construct() {
		parent::__construct();
		$this->load->model('section_m');
		$this->load->model("classes_m");
		$this->load->model("transport_m");
		$this->load->model("hostel_m");
		$this->load->model("hmember_m");
		$this->load->model("tmember_m");
		$this->load->model("studentrelation_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('studentreport', $language);
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
                'assets/datepicker/datepicker.css',
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
                'assets/datepicker/datepicker.js',
				'assets/select2/select2.js'
			)
		);

		$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data['transports'] = $this->transport_m->get_transport();
		$this->data['hostels'] = $this->hostel_m->get_hostel();
		
		$this->data["subview"] = "report/student/StudentReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function rules($reportfor= 'blood') {		
		$rules = array(
			array(
				'field' => 'reportfor',
				'label' => $this->lang->line("studentreport_routine_for"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			)
		);

		if($reportfor == "blood") {
			$rules[] = array(
				'field' => 'blood',
				'label' => $this->lang->line("studentreport_blood"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			);
		} elseif($reportfor == "country") {
			$rules[] = array(
				'field' => 'country',
				'label' => $this->lang->line("studentreport_country"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			);
		} elseif($reportfor == 'gender') {
			$rules[] = array(
				'field' => 'gender',
				'label' => $this->lang->line("studentreport_gender"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			);
		} elseif($reportfor == 'transport') {
			$rules[] = array(
				'field' => 'transport',
				'label' => $this->lang->line("studentreport_transport"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			);
		} elseif($reportfor == 'hostel') {
			$rules[] = array(
				'field' => 'hostel',
				'label' => $this->lang->line("studentreport_hostel"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			);
		} elseif($reportfor == 'birthday') {
			$rules[] = array(
				'field' => 'birthdaydate',
				'label' => $this->lang->line("studentreport_birthdaydate"),
                'rules' => 'trim|required|xss_clean|callback_date_valid'
			);
		}

		$rules[] = array(
			'field' => 'classesID',
			'label' => $this->lang->line("studentreport_class"),
			'rules' => 'trim|xss_clean'
		);

		$rules[] = array(
			'field' => 'sectionID',
			'label' => $this->lang->line("studentreport_section"),
			'rules' => 'trim|xss_clean'
		);

		return $rules;
	} 

	public function send_pdf_to_mail_rules($reportfor= 'blood') {		
		$rules = array(
			array(
				'field' => 'reportfor',
				'label' => $this->lang->line("studentreport_routine_for"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line("studentreport_to"),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("studentreport_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("studentreport_message"),
				'rules' => 'trim|xss_clean'
			)
		);

		if($reportfor == "blood") {
			$rules[] = array(
				'field' => 'bloodID',
				'label' => $this->lang->line("studentreport_blood"),
				'rules' => 'trim|required|xss_clean'
			);
		} elseif($reportfor == "country") {
			$rules[] = array(
				'field' => 'country',
				'label' => $this->lang->line("studentreport_country"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			);
		} elseif($reportfor == 'gender') {
			$rules[] = array(
				'field' => 'gender',
				'label' => $this->lang->line("studentreport_gender"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			);
		} elseif($reportfor == 'transport') {
			$rules[] = array(
				'field' => 'transport',
				'label' => $this->lang->line("studentreport_transport"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			);
		} elseif($reportfor == 'hostel') {
			$rules[] = array(
				'field' => 'hostel',
				'label' => $this->lang->line("studentreport_hostel"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			);
		} elseif($reportfor == 'birthday') {
            $rules[] = array(
                'field' => 'birthdaydate',
                'label' => $this->lang->line("studentreport_birthdaydate"),
                'rules' => 'trim|required|xss_clean|callback_date_valid'
            );
        }

		$rules[] = array(
			'field' => 'classesID',
			'label' => $this->lang->line("studentreport_class"),
			'rules' => 'trim|xss_clean'
		);

		$rules[] = array(
			'field' => 'sectionID',
			'label' => $this->lang->line("studentreport_section"),
			'rules' => 'trim|xss_clean'
		);
		return $rules;
	}

	public function date_valid($date) {
		if($date) {
			if(strlen($date) < 10) {
				$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy.");
		     	return FALSE;
			} else {
		   		$arr = explode("-", $date);
		        $dd = $arr[0];
		        $mm = $arr[1];
		        $yyyy = $arr[2];
		      	if(checkdate($mm, $dd, $yyyy)) {
		      		return TRUE;
		      	} else {
		      		$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy.");
		     		return FALSE;
		      	}
		    }
		}
		return TRUE;
	}

	public function unique_data($data) {
		if($data != "") {
			if($data == "0") {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		} 
		return TRUE;
	}

	public function getStudentReport()
	{
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		if(permissionChecker('studentreport')) {
			if($_POST) {
				$reportfor     = $this->input->post('reportfor');
				$blood         = $this->input->post('blood');
				$country       = $this->input->post('country');
				$transport     = $this->input->post('transport');
				$hostel        = $this->input->post('hostel');
				$birthdaydate  = $this->input->post('birthdaydate');
				$gender        = $this->input->post('gender');
				$classesID     = $this->input->post('classesID');
				$sectionID     = $this->input->post('sectionID');

				$rules = $this->rules($reportfor);
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->data['reportfor'] = $reportfor;
					$bloodArray = array_flip($this->_bloodArray);
					$this->data['bloodID'] = isset($bloodArray[$blood]) ? $bloodArray[$blood] : '0';
					$this->data['country'] = $country; 
					$this->data['transport'] = $transport; 
					$this->data['hostel'] = $hostel; 
					$this->data['birthdaydate'] = $birthdaydate;
					$this->data['gender'] = $gender;
					$this->data['classesID'] = $classesID; 
					$this->data['sectionID'] = $sectionID;

					$queryArray = [];
					$this->getArray($queryArray, $this->input->post());

					if($reportfor == 'transport') {
						$transports = $this->tmember_m->get_order_by_tmember(array('transportID' => $transport));
						$getstudents = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srstudentID');
						$students = [];
						if(count($transports)) {
							foreach ($transports as $transport) {
								if(isset($getstudents[$transport->studentID])) {
									$students[] = $getstudents[$transport->studentID];
								}
							}
						}
						$this->data['students'] = $students;
					} elseif($reportfor == 'hostel') {
						$hostels = $this->hmember_m->get_order_by_hmember(array('hostelID' => $hostel));
						$getstudents = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srstudentID');
						$students = [];
						if(count($hostels)) {
							foreach ($hostels as $hostel) {
								if(isset($getstudents[$hostel->studentID])) {
									$students[] = $getstudents[$hostel->studentID];
								}
							}
						}	
						$this->data['students'] = $students;
					} else {
						$this->data['students'] = $this->studentrelation_m->general_get_order_by_student($queryArray);
					}

					$retArray['render'] = $this->load->view('report/student/StudentReport', $this->data,true);
					$retArray['status'] = true;
					echo json_encode($retArray);
				    exit;
				}
			} else {
				echo json_encode($retArray);
		    	exit;
			}
		} else {
			echo json_encode($retArray);
		    exit;
		}
	}

	private function getArray(&$queryArray, $post) {
		$classesID 		= $post['classesID'];
		$sectionID 		= $post['sectionID'];
		$reportfor  	= $post['reportfor'];

		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$queryArray['srschoolyearID'] = $schoolyearID;

		if($classesID > 0) {
			$queryArray['srclassesID'] = $classesID;
		}
		if($sectionID != '' && $sectionID != '0') {
			$queryArray['srsectionID'] = $sectionID;
		}

		if(isset($post['reportfor']) && $post['reportfor'] == 'blood') {
			$queryArray['bloodgroup'] = $post['blood'];
			$this->data['reportTitle'] = $post['blood'];
		}

		if(isset($post['reportfor']) && $post['reportfor'] == 'country') {
			$queryArray['country'] = $post['country'];
			$this->data['reportTitle'] = $this->data['allcountry'][$post['country']];
		}

		if(isset($post['reportfor']) && $post['reportfor'] == 'gender') {
			$queryArray['sex'] = $post['gender'];
			$this->data['reportTitle'] = $post['gender'];
		}

		if(isset($post['reportfor']) && $post['reportfor'] == 'transport') {
			$transport = $this->transport_m->get_transport($post['transport']);
			$queryArray['transport'] = 1;
			$this->data['reportTitle'] = $transport->route;
		}

		if(isset($post['reportfor']) && $post['reportfor'] == 'hostel') {
			$hostel = $this->hostel_m->get_hostel($post['hostel']);
			$queryArray['hostel'] = 1;
			$this->data['reportTitle'] = $hostel->name;
		}
		if(isset($post['reportfor']) && $post['reportfor'] == 'birthday') {
			$queryArray['dob'] = date('Y-m-d', strtotime($post['birthdaydate']));
			$this->data['reportTitle'] = date('d F Y',strtotime($post['birthdaydate']));
		}

		$this->data['classes'] 	= pluck($this->classes_m->general_get_classes(), 'obj' , 'classesID');
		$this->data['sections'] = pluck($this->section_m->general_get_section(), 'obj', 'sectionID');
	}


	public function pdf() {
		if(permissionChecker('studentreport')) {
			$reportfor      = htmlentities(escapeString($this->uri->segment(3)));
			$bloodID        = htmlentities(escapeString($this->uri->segment(4)));
			$country        = htmlentities(escapeString($this->uri->segment(5)));
			$transport      = htmlentities(escapeString($this->uri->segment(6)));
			$hostel         = htmlentities(escapeString($this->uri->segment(7))); 
			$gender         = htmlentities(escapeString($this->uri->segment(8))); 
			$birthdaydate   = htmlentities(escapeString($this->uri->segment(9)));
			$classesID      = htmlentities(escapeString($this->uri->segment(10)));
			$sectionID      = htmlentities(escapeString($this->uri->segment(11)));

			$this->data['reportfor'] = $reportfor;

			if(isset($bloodID)) {
				$this->data['blood'] = isset($this->_bloodArray[$bloodID]) ? $this->_bloodArray[$bloodID] : 8; 
				$blood = $this->data['blood'];
			}

			$this->data['country'] = $country; 
			$this->data['transport'] = $transport; 
			$this->data['hostel'] = $hostel; 
			$this->data['gender'] = $gender;
			$this->data['birthdaydate'] = date('Y-m-d', $birthdaydate);
			$this->data['classesID'] = $classesID; 
			$this->data['sectionID'] = $sectionID;

			$reportforArray = array('blood','country','gender','transport','hostel','birthday');

			if((string) $reportfor && ((int) $blood || $blood >= 0) && ((int) $country || $country >= 0) && ((int) $transport || $transport >= 0) && ((int) $gender || $gender >= 0) && ((int) $birthdaydate || $birthdaydate >= 0) && ((int) $classesID || $classesID >= 0) && ((int) $sectionID || $sectionID == NULL || $sectionID >= 0)) {
				if(in_array(strtolower($reportfor), $reportforArray)) {
					if($bloodID <= 7) {
						$allCountry = $this->getAllCountry();
						$allCountry[0] = 'Select One';

						if(array_key_exists(strtoupper($country), $allCountry)) {
							if(isset($transport)) {
								$transportss = $this->transport_m->get_transport($transport);
							}

							if(count($transportss) || $transport == 0) {
								if(isset($hostel)) {
									$hostelss = $this->hostel_m->get_hostel($hostel);
								}
								if(count($hostelss) || $hostel == 0) {
									if($gender == 'Male' || $gender = 'Female' || $gender == 0) {
										if(isset($classesID)) {
												$classesIDs = $this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
											}

											if(count($classesIDs) || $classesID == 0) {
												if(isset($sectionID)) {
													$sectionIDs = $this->section_m->general_get_single_section(array('sectionID'=>$sectionID));
												}
												if(count($sectionIDs) || $sectionID == '0' || $sectionID == NULL) {
													$queryArray = [];
													$this->getArray($queryArray, $this->data);
													if($reportfor == 'transport') {
														$transports = $this->tmember_m->get_order_by_tmember(array('transportID' => $transport));
														$getstudents = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srstudentID');
														$students = [];
														foreach ($transports as $transport) {
															if(isset($getstudents[$transport->studentID])) {
																$students[] = $getstudents[$transport->studentID];
															}
														}
														$this->data['students'] = $students;
													} elseif($reportfor == 'hostel') {
														$hostels = $this->hmember_m->get_order_by_hmember(array('hostelID' => $hostel));
														$getstudents = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srstudentID');
														$students = [];
														foreach ($hostels as $hostel) {
															if(isset($getstudents[$hostel->studentID])) {
																$students[] = $getstudents[$hostel->studentID];
															}
														}
														$this->data['students'] = $students;
													} else {
														$this->data['students'] = $this->studentrelation_m->general_get_order_by_student($queryArray);	
													}

													$this->reportPDF('studentreport.css', $this->data, 'report/student/StudentReportPDF');
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
			else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else{
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function xlsx() {
		if(permissionChecker('studentreport')) { 
			$this->load->library('phpspreadsheet');
			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$sheet->getDefaultColumnDimension()->setWidth(30);
			$sheet->getDefaultRowDimension()->setRowHeight(50);

			$sheet->getPageSetup()->setFitToWidth(1);
			$sheet->getPageSetup()->setFitToHeight(0);

			$sheet->getPageMargins()->setTop(1);
			$sheet->getPageMargins()->setRight(0.75);
			$sheet->getPageMargins()->setLeft(0.75);
			$sheet->getPageMargins()->setBottom(1);

			$data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="studentreport.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0

			$this->phpspreadsheet->output($this->phpspreadsheet->spreadsheet);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	private function xmlData()
	{
		$reportfor      = htmlentities(escapeString($this->uri->segment(3)));
		$bloodID        = htmlentities(escapeString($this->uri->segment(4)));
		$country        = htmlentities(escapeString($this->uri->segment(5)));
		$transport      = htmlentities(escapeString($this->uri->segment(6)));
		$hostel         = htmlentities(escapeString($this->uri->segment(7))); 
		$gender         = htmlentities(escapeString($this->uri->segment(8)));
        $birthdaydate   = htmlentities(escapeString($this->uri->segment(9)));
		$classesID      = htmlentities(escapeString($this->uri->segment(10)));
		$sectionID      = htmlentities(escapeString($this->uri->segment(11)));

		$this->data['reportfor'] = $reportfor;

		if(isset($bloodID)) {
			$this->data['blood'] = isset($this->_bloodArray[$bloodID]) ? $this->_bloodArray[$bloodID] : 8; 
			$blood = $this->data['blood'];
		}

		$this->data['country'] = $country; 
		$this->data['transport'] = $transport; 
		$this->data['hostel'] = $hostel; 
		$this->data['gender'] = $gender; 
		$this->data['birthdaydate'] = date('Y-m-d', $birthdaydate);

        $this->data['classesID'] = $classesID;
		$this->data['sectionID'] = $sectionID;

		$reportforArray = array('blood','country','gender','transport','hostel','birthday');

		if((string) $reportfor && ((int) $blood || $blood >= 0) && ((int) $country || $country >= 0) && ((int) $transport || $transport >= 0) && ((int) $gender || $gender >= 0)  && ((int) $birthdaydate || $birthdaydate >= 0) && ((int) $classesID || $classesID >= 0) && ((int) $sectionID || $sectionID == NULL || $sectionID >= 0)) {
			if(in_array(strtolower($reportfor), $reportforArray)) {
				if($bloodID <= 7) {
					$allCountry = $this->getAllCountry();
					$allCountry[0] = 'Select One';

					if(array_key_exists(strtoupper($country), $allCountry)) {
						if(isset($transport)) {
							$transportss = $this->transport_m->get_transport($transport);
						}

						if(count($transportss) || $transport == 0) {
							if(isset($hostel)) {
								$hostelss = $this->hostel_m->get_hostel($hostel);
							}
							if(count($hostelss) || $hostel == 0) {
								if($gender == 'Male' || $gender = 'Female' || $gender == 0) {
									if(isset($classesID)) {
										$classesIDs = $this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
									}

									if(count($classesIDs) || $classesID == 0) {
										if(isset($sectionID)) {
											$sectionIDs = $this->section_m->general_get_single_section(array('sectionID'=>$sectionID));
										}
										if(count($sectionIDs) || $sectionID == '0' || $sectionID == NULL) {
											$queryArray = [];
											$this->getArray($queryArray, $this->data);
											if($reportfor == 'transport') {
												$transports = $this->tmember_m->get_order_by_tmember(array('transportID' => $transport));
												$getstudents = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srstudentID');
												$students = [];
												foreach ($transports as $transport) {
													if(isset($getstudents[$transport->studentID])) {
														$students[] = $getstudents[$transport->studentID];
													}
												}
												$this->data['students'] = $students;
											} elseif($reportfor == 'hostel') {
												$hostels = $this->hmember_m->get_order_by_hmember(array('hostelID' => $hostel));
												$getstudents = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srstudentID');
												$students = [];
												foreach ($hostels as $hostel) {
													if(isset($getstudents[$hostel->studentID])) {
														$students[] = $getstudents[$hostel->studentID];
													}
												}
												$this->data['students'] = $students;
											} else {
												$this->data['students'] = $this->studentrelation_m->general_get_order_by_student($queryArray);	
											}
											
											return $this->generateXML($this->data);

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
		else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	private function generateXML($data) {
		extract($data);
		if($students) {
			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			if($classesID == '0' && ($sectionID =='' || $sectionID == '0')) {
				$countColumn = 9;
			} elseif($classesID > 0 && $sectionID == 0) {
				$countColumn = 8;
			} elseif($classesID > 0 && $sectionID > 0) {
				$countColumn = 7;
			}
			$headerColumn = 'A';
			$row =1;
			for($j = 1; $j < $countColumn ; $j++) {
				$headerColumn++;
			}

			$className  = $this->lang->line('studentreport_class'); 
			$className  .= ' : '; 
			$className  .= isset($classes[$classesID]) ? $classes[$classesID]->classes : $this->lang->line('studentreport_select_all_class');
			$sectionName   = $this->lang->line('studentreport_section');
			$sectionName  .= ' . ';
			$sectionName  .= isset($sections[$sectionID]) ? $sections[$sectionID]->section : $this->lang->line('studentreport_select_all_section');

			$sheet->setCellValue('A'.$row, $className);
			$sheet->setCellValue($headerColumn.$row, $sectionName);

			$headers   = array();
			$headers['slno'] = $this->lang->line('studentreport_slno');
			$headers['photo'] = $this->lang->line('studentreport_photo');
			$headers['name'] = $this->lang->line('studentreport_name');
			$headers['registerNO'] = $this->lang->line('studentreport_register');
			if($classesID == 0) { 
				$headers['class'] = $this->lang->line('studentreport_class');
			} 
			if($sectionID == 0 || $sectionID == '') {
				$headers['section'] = $this->lang->line('studentreport_section');
			}
			$headers['roll'] = $this->lang->line('studentreport_roll');
			$headers['email'] = $this->lang->line('studentreport_email');
			$headers['phone'] = $this->lang->line('studentreport_phone');

			$column = 'A';
			$row = 2;
			foreach($headers as $headerKey => $header) {
				if($headerKey == 'slno') {
					$sheet->getColumnDimension('A')->setWidth(15);
				} elseif($headerKey == 'photo') {
					$sheet->getColumnDimension('B')->setWidth(10);
				} elseif($headerKey == 'name') {
					$sheet->getColumnDimension('C')->setWidth(25);
				}
				$sheet->setCellValue($column.$row, $header);
		    	$column++;
			}

			$bodys = [];
			$i = 1;
			foreach($students as $student) {
				$bodys[$i]['slno'] = $i;
				$bodys[$i]['photo'] = $student->photo;
				$bodys[$i]['name'] = $student->srname;
				$bodys[$i]['registerNO'] = $student->srregisterNO;

				if($classesID == 0) { 
					$bodys[$i]['class'] = (isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID]->classes : '');
				} 
				if($sectionID == 0 || $sectionID == '') {
					$bodys[$i]['section'] = (isset($sections[$student->srsectionID])) ? $sections[$student->srsectionID]->section : '';
				}
				$bodys[$i]['roll'] = $student->srroll;
				$bodys[$i]['email'] = $student->email;
				$bodys[$i]['phone'] = $student->phone;
				$i++;
			}

			$row = 3;
		    foreach($bodys as $single_bodys) {
		    	$column = 'A';
		    	foreach($single_bodys as $single_bodyKey => $single_body) {
		    		if($single_bodyKey == 'photo') {
			    		if (file_exists(FCPATH.'uploads/images/'.$single_body)) {
						    $this->phpspreadsheet->draw_images(FCPATH.'uploads/images/'.$single_body, $column.$row,$sheet, 40);
						} else {
							$sheet->setCellValue($column.$row, $single_body);
						} 
					} else {
		    			$sheet->setCellValue($column.$row, $single_body);
					}

		    		$column++;
		    	}
		    	$row++;
		    }

		    $styleArray = [
			    'font' => [
			        'bold' => TRUE,
			    ],
			    'alignment' =>[
			    	'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			    	'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			    'borders' => [
		            'allBorders' => [
		                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		            ]
		        ]
			];
			
			$sheet->getStyle('A1:'.$headerColumn.'2')->applyFromArray($styleArray);

			$styleArray = [
			    'font' => [
			        'bold' => FALSE,
			    ],
			    'alignment' =>[
			    	'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			    	'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			    ],
			    'borders' => [
		            'allBorders' => [
		                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		            ]
		        ]
			];
			$row = $row-1;
			$sheet->getStyle('A3:'.$headerColumn.$row)->applyFromArray($styleArray);

			$headerColumn = chr(ord($headerColumn) - 1);  //Decreament Header Section Column
			$mergeCellsColumn = $headerColumn.'1';
			$sheet->mergeCells("B1:$mergeCellsColumn");
		} else {
			redirect('studentreport');
		}
	}

	private function uriChecker() {
		$totalUri = count($this->uri->segment_array());
		if($totalUri == 10 || $totalUri == 11) {
			return TRUE;
		}
		$this->errorHandeler();
	}

	private function errorHandeler($data = FALSE) {
		$this->data["subview"] = "error";
		$this->load->view('_layout_main', $this->data);
	}

	public function getSection() {
		$id = $this->input->post('id');
		if((int)$id) {
			$allSection = $this->section_m->general_get_order_by_section(array('classesID' => $id));
			echo "<option value='0'>", $this->lang->line("studentreport_please_select"),"</option>";
			if(count($allSection)) {
				foreach ($allSection as $value) {
					echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
				}
			}
		}
	}

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		$retArray['message']= '';

		if(permissionChecker('studentreport')) {
        	if($_POST) {
        		$to             = $this->input->post('to'); 
	        	$subject        = $this->input->post('subject'); 
	        	$message        = $this->input->post('message'); 
				$reportfor      = $this->input->post('reportfor');
				$bloodID        = $this->input->post('bloodID');
				$country        = $this->input->post('country');
				$transport      = $this->input->post('transport');
				$hostel         = $this->input->post('hostel'); 
				$birthdaydate   = $this->input->post('birthdaydate');
				$gender         = $this->input->post('gender');
				$classesID      = $this->input->post('classesID'); 
				$sectionID      = $this->input->post('sectionID');

				$rules = $this->send_pdf_to_mail_rules($reportfor);
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->data['reportfor'] = $reportfor;
					if(isset($bloodID)) {
						$this->data['blood'] = isset($this->_bloodArray[$bloodID]) ? $this->_bloodArray[$bloodID] : 8; 
						$blood = $this->data['blood'];
					}


					$this->data['country'] = $country; 
					$this->data['transport'] = $transport; 
					$this->data['hostel'] = $hostel; 
					$this->data['birthdaydate'] = $birthdaydate;
					$this->data['gender'] = $gender;
					$this->data['classesID'] = $classesID; 
					$this->data['sectionID'] = $sectionID;

					$reportforArray = array('blood','country','gender','transport','hostel','birthday');

					if((string) $reportfor && ((int) $blood || $blood >= 0) && ((int) $country || $country >= 0) && ((int) $transport || $transport >= 0) && ((int) $gender || $gender >= 0) && ((int) $birthdaydate || $birthdaydate >= 0) && ((int) $classesID || $classesID >= 0) && ((int) $sectionID || $sectionID == NULL || $sectionID >= 0)) {
						if(in_array(strtolower($reportfor), $reportforArray)) {
							if($bloodID <= 7) {
								$allCountry = $this->getAllCountry();
								$allCountry[0] = 'Select One';

								if(array_key_exists(strtoupper($country), $allCountry)) {
									if(isset($transport)) {
										$transportss = $this->transport_m->get_transport($transport);
									}

									if(count($transportss) || $transport == 0) {
										if(isset($hostel)) {
											$hostelss = $this->hostel_m->get_hostel($hostel);
										}
										if(count($hostelss) || $hostel == 0) {
											if($gender == 'Male' || $gender == 'Female' || $gender == 0) {
													if(isset($classesID)) {
														$classesIDs = $this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
													}

													if(count($classesIDs) || $classesID == 0) {
														if(isset($sectionID)) {
															$sectionIDs = $this->section_m->general_get_single_section(array('sectionID'=>$sectionID));
														}
														if(count($sectionIDs) || $sectionID == '0' || $sectionID == NULL) {
															$queryArray = [];
															$this->getArray($queryArray, $this->data);
															if($reportfor == 'transport') {
																$transports = $this->tmember_m->get_order_by_tmember(array('transportID' => $transport));
																$getstudents = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srstudentID');
																$students = [];
																foreach ($transports as $transport) {
																	if(isset($getstudents[$transport->studentID])) {
																		$students[] = $getstudents[$transport->studentID];
																	}
																}
																$this->data['students'] = $students;
															} elseif($reportfor == 'hostel') {
																$hostels = $this->hmember_m->get_order_by_hmember(array('hostelID' => $hostel));
																$getstudents = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srstudentID');
																$students = [];
																foreach ($hostels as $hostel) {
																	if(isset($getstudents[$hostel->studentID])) {
																		$students[] = $getstudents[$hostel->studentID];
																	}
																}
																$this->data['students'] = $students;
															} else {
																$this->data['students'] = $this->studentrelation_m->general_get_order_by_student($queryArray);	
															}
															$this->reportSendToMail('studentreport.css', $this->data, 'report/student/StudentReportPDF',$to,$subject,$message);
															$retArray['status'] = TRUE;
															echo json_encode($retArray);
				    										exit;
														} else {
															$retArray['message'] = $this->lang->line('studentreport_section_not_found');
															echo json_encode($retArray);
															exit;
														}
													} else {
														$retArray['message'] = $this->lang->line('studentreport_class_not_found');
														echo json_encode($retArray);
														exit;
													}
											} else {
												$retArray['message'] = $this->lang->line('studentreport_permission');
												echo json_encode($retArray);
												exit;
											}
										} else {
											$retArray['message'] = $this->lang->line('studentreport_invalid_hostel');
											echo json_encode($retArray);
											exit;	
										}
									} else {
										$retArray['message'] = $this->lang->line('studentreport_invalid_transport');
										echo json_encode($retArray);
										exit;
									}
								} else {
									$retArray['message'] = $this->lang->line('studentreport_invalid_country');
									echo json_encode($retArray);
									exit;
								}
							} else {
								$retArray['message'] = $this->lang->line('studentreport_invalid_blood');
								echo json_encode($retArray);
								exit;
							}
						} else {
							$retArray['message'] = $this->lang->line('studentreport_invalid_reportfor');
							echo json_encode($retArray);
							exit;
						}
					} 
					else {
						$retArray['message'] = $this->lang->line('studentreport_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
        	} else {
        		$retArray['message'] = $this->lang->line('studentreport_permissionmethod');
				echo json_encode($retArray);
				exit;
        	}
		} else{
			$retArray['message'] = $this->lang->line('studentreport_permission');
			echo json_encode($retArray);
			exit;
		}
	}


}
