<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classesreport extends Admin_Controller {
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
		$this->load->model("subject_m");
		$this->load->model('section_m');
		$this->load->model("classes_m");
		$this->load->model("teacher_m");
		$this->load->model("invoice_m");
		$this->load->model("payment_m");
		$this->load->model("routine_m");
		$this->load->model("studentrelation_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('classesreport', $language);
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/highcharts/highcharts.js',
				'assets/highcharts/highcharts-more.js',
				'assets/highcharts/data.js',
				'assets/highcharts/drilldown.js',
				'assets/highcharts/exporting.js',
				'assets/select2/select2.js'
			)
		);

		$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data["subview"] = "report/class/ClassReportView";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line('classesreport_classname'),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line('classesreport_sectionname'),
				'rules' => 'trim|required|greater_than_equal_to[0]|xss_clean'
			)
		);
		return $rules;
	}

	protected function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line('classesreport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),array(
				'field' => 'subject',
				'label' => $this->lang->line('classesreport_subject'),
				'rules' => 'trim|required|xss_clean'
			),array(
				'field' => 'message',
				'label' => $this->lang->line('classesreport_message'),
				'rules' => 'trim|xss_clean'
			),array(
				'field' => 'classesID',
				'label' => $this->lang->line('classesreport_class'),
				'rules' => 'trim|required|numeric|xss_clean'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line('classesreport_section'),
				'rules' => 'trim|required|greater_than_equal_to[0]|xss_clean'
			)
		);
		return $rules;
	}

	public function unique_data($data) {
		if($data === "0") {
			$this->form_validation->set_message('unique_data', 'The %s field is required.');
			return FALSE;
		}
		return TRUE;
	}

	public function getSection() {
		$id = $this->input->post('id');
		if((int)$id) {
			$sections = $this->section_m->general_get_order_by_section(array('classesID' => $id));
			echo "<option value='0'>", $this->lang->line("classesreport_please_select"),"</option>";
			foreach ($sections as $section) {
				echo "<option value=\"$section->sectionID\">",$section->section,"</option>";
			}
		}
	}
	
	public function getClassReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('classesreport')) {
			if($_POST) {
				$classID 		= $this->input->post('classesID');
				$sectionID 		= $this->input->post('sectionID');
				$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$classes  = $this->classes_m->general_get_single_classes(array('classesID'=>$classID));
					$subjects = $this->subject_m->general_get_order_by_subject(array( 'classesID' => $classID));
					$teachers = pluck($this->teacher_m->general_get_teacher(), 'obj', 'teacherID');

					$sectionArray = [];
					$studentArray = [];
					$routineArray = [];
					$sectionArray['classesID']       = $classID;
					$studentArray['srclassesID']    = $classID;
					$routineArray['classesID']    = $classID;
					$studentArray['srschoolyearID'] = $schoolyearID;
					$routineArray['schoolyearID'] = $schoolyearID;
					if((int)$sectionID > 0) {
						$sectionArray['sectionID']    = $sectionID;
						$studentArray['srsectionID'] = $sectionID;
						$routineArray['sectionID'] = $sectionID;
					}

					$sections = pluck($this->section_m->general_get_order_by_section($sectionArray), 'obj', 'sectionID');
					$students = pluck($this->studentrelation_m->general_get_order_by_student($studentArray), 'obj', 'srstudentID');
					$routines = pluck_multi_array_key($this->routine_m->get_order_by_routine($routineArray),'teacherID','subjectID','teacherID');

					$postArray = array('classesID' => $classID,'sectionID' => $sectionID, 'schoolyearID'=> $schoolyearID);
					$getFeeTypes = $this->payment_m->get_all_fee_types($postArray);

					$feetypesArray = [];
					$totalPayment = 0; 
					if(count($getFeeTypes)) {
						foreach($getFeeTypes as $getFeeType) {
							if($sectionID > 0) {
								if(isset($students[$getFeeType->studentID]) && $students[$getFeeType->studentID]->srsectionID == $sectionID) {
									$totalPayment += $getFeeType->paymentamount;
									if(!isset($feetypesArray[$getFeeType->feetype])) {
										$feetypesArray[$getFeeType->feetype] = $getFeeType->paymentamount;
									} else {
										$feetypesArray[$getFeeType->feetype] += $getFeeType->paymentamount;
									}
								}
							} else {
								$totalPayment += $getFeeType->paymentamount;
								if(!isset($feetypesArray[$getFeeType->feetype])) {
									$feetypesArray[$getFeeType->feetype] = $getFeeType->paymentamount;
								} else {
									$feetypesArray[$getFeeType->feetype] += $getFeeType->paymentamount;
								}
							}
						}
					}

					$totalInvoiceAmounts = $this->getDueAmount($postArray, $students, $sectionID);
					
					$this->data['class'] 	= $classes;
					$this->data['subjects'] = $subjects;
					$this->data['teachers'] = $teachers;
					$this->data['sections'] = $sections;
					$this->data['students'] = $students;
					$this->data['routines'] = $routines;
					
					if(isset($sections[$sectionID])) {
						$this->data['sectionName'] = $this->lang->line("classesreport_section")." ".$sections[$sectionID]->section;
					} elseif ($sectionID == 0) {
						$this->data['sectionName'] = $this->lang->line("classesreport_select_all_section");
					}
					
					$this->data['collectionAmount'] = $totalPayment;
					$this->data['dueAmount'] = $totalInvoiceAmounts-$totalPayment;
					$this->data['feetypes'] = $feetypesArray;
					$this->data['classID']   = $classID;
					$this->data['sectionID'] = $sectionID;

					$retArray['render'] = $this->load->view('report/class/ClassReport', $this->data, true);
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

	public function pdf() {
		if(permissionChecker('classesreport')) {
			$classID 		= $this->uri->segment(3);
			$sectionID 		= $this->uri->segment(4);
			$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
			if(((int) $classID && $classID > 0) && ((int) $sectionID || $sectionID >= 0)) {
				$classes  = $this->classes_m->general_get_single_classes(array('classesID'=>$classID));
				$subjects = $this->subject_m->general_get_order_by_subject(array( 'classesID' => $classID));
				$teachers = pluck($this->teacher_m->general_get_teacher(), 'obj', 'teacherID');

				$sectionArray = [];
				$studentArray = [];
				$routineArray = [];
				$sectionArray['classesID']       = $classID;
				$studentArray['srclassesID']    = $classID;
				$routineArray['classesID']    = $classID;
				$studentArray['srschoolyearID'] = $schoolyearID;
				$routineArray['schoolyearID'] = $schoolyearID;
				if((int)$sectionID > 0) {
					$sectionArray['sectionID']    = $sectionID;
					$studentArray['srsectionID'] = $sectionID;
					$routineArray['sectionID'] = $sectionID;
				}

				$sections = pluck($this->section_m->general_get_order_by_section($sectionArray), 'obj', 'sectionID');
				$students = pluck($this->studentrelation_m->general_get_order_by_student($studentArray), 'obj', 'srstudentID');
				$routines = pluck_multi_array_key($this->routine_m->get_order_by_routine($routineArray),'teacherID','subjectID','teacherID');

				$postArray = array('classesID' => $classID,'sectionID' => $sectionID, 'schoolyearID'=> $schoolyearID);
				$getFeeTypes = $this->payment_m->get_all_fee_types($postArray);

				$feetypesArray = [];
				$totalPayment = 0; 
				if(count($getFeeTypes)) {
					foreach($getFeeTypes as $getFeeType) {
						if($sectionID > 0) {
							if(isset($students[$getFeeType->studentID]) && $students[$getFeeType->studentID]->srsectionID == $sectionID) {
								$totalPayment += $getFeeType->paymentamount;
								if(!isset($feetypesArray[$getFeeType->feetype])) {
									$feetypesArray[$getFeeType->feetype] = $getFeeType->paymentamount;
								} else {
									$feetypesArray[$getFeeType->feetype] += $getFeeType->paymentamount;
								}
							}
						} else {
							$totalPayment += $getFeeType->paymentamount;
							if(!isset($feetypesArray[$getFeeType->feetype])) {
								$feetypesArray[$getFeeType->feetype] = $getFeeType->paymentamount;
							} else {
								$feetypesArray[$getFeeType->feetype] += $getFeeType->paymentamount;
							}
						}
					}
				}

				$totalInvoiceAmounts = $this->getDueAmount($postArray, $students, $sectionID);
				
				$this->data['class'] 	= $classes;
				$this->data['subjects'] = $subjects;
				$this->data['teachers'] = $teachers;
				$this->data['sections'] = $sections;
				$this->data['students'] = $students;
				$this->data['routines'] = $routines;
				
				if(isset($sections[$sectionID])) {
					$this->data['sectionName'] = $this->lang->line("classesreport_section")." ".$sections[$sectionID]->section;
				} elseif ($sectionID == 0) {
					$this->data['sectionName'] = $this->lang->line("classesreport_select_all_section");
				}
				
				$this->data['collectionAmount'] = $totalPayment;
				$this->data['dueAmount'] = $totalInvoiceAmounts-$totalPayment;
				$this->data['feetypes'] = $feetypesArray;
				$this->data['classID']   = $classID;
				$this->data['sectionID'] = $sectionID;

				$this->reportPDF('classreport.css', $this->data, 'report/class/ClassReportPDF');
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		$retArray['message']= '';
		if(permissionChecker('classesreport')) {
			if($_POST) {
				$to           = $this->input->post('to');
				$subject      = $this->input->post('subject');
				$message      = $this->input->post('message');
				$classID 	  = $this->input->post('classesID');
				$sectionID 	  = $this->input->post('sectionID');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == FALSE) {
					$retArray[] = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$classes  = $this->classes_m->general_get_single_classes(array('classesID'=>$classID));
					$subjects = $this->subject_m->general_get_order_by_subject(array( 'classesID' => $classID));
					$teachers = pluck($this->teacher_m->general_get_teacher(), 'obj', 'teacherID');

					$sectionArray = [];
					$studentArray = [];
					$routineArray = [];
					$sectionArray['classesID']       = $classID;
					$studentArray['srclassesID']    = $classID;
					$routineArray['classesID']    = $classID;
					$studentArray['srschoolyearID'] = $schoolyearID;
					$routineArray['schoolyearID'] = $schoolyearID;
					if((int)$sectionID > 0) {
						$sectionArray['sectionID']    = $sectionID;
						$studentArray['srsectionID'] = $sectionID;
						$routineArray['sectionID'] = $sectionID;
					}

					$sections = pluck($this->section_m->general_get_order_by_section($sectionArray), 'obj', 'sectionID');
					$students = pluck($this->studentrelation_m->general_get_order_by_student($studentArray), 'obj', 'srstudentID');
					$routines = pluck_multi_array_key($this->routine_m->get_order_by_routine($routineArray),'teacherID','subjectID','teacherID');

					$postArray = array('classesID' => $classID,'sectionID' => $sectionID, 'schoolyearID'=> $schoolyearID);
					$getFeeTypes = $this->payment_m->get_all_fee_types($postArray);

					$feetypesArray = [];
					$totalPayment = 0; 
					if(count($getFeeTypes)) {
						foreach($getFeeTypes as $getFeeType) {
							if($sectionID > 0) {
								if(isset($students[$getFeeType->studentID]) && $students[$getFeeType->studentID]->srsectionID == $sectionID) {
									$totalPayment += $getFeeType->paymentamount;
									if(!isset($feetypesArray[$getFeeType->feetype])) {
										$feetypesArray[$getFeeType->feetype] = $getFeeType->paymentamount;
									} else {
										$feetypesArray[$getFeeType->feetype] += $getFeeType->paymentamount;
									}
								}
							} else {
								$totalPayment += $getFeeType->paymentamount;
								if(!isset($feetypesArray[$getFeeType->feetype])) {
									$feetypesArray[$getFeeType->feetype] = $getFeeType->paymentamount;
								} else {
									$feetypesArray[$getFeeType->feetype] += $getFeeType->paymentamount;
								}
							}
						}
					}

					$totalInvoiceAmounts = $this->getDueAmount($postArray, $students, $sectionID);
					
					$this->data['class'] 	= $classes;
					$this->data['subjects'] = $subjects;
					$this->data['teachers'] = $teachers;
					$this->data['sections'] = $sections;
					$this->data['students'] = $students;
					$this->data['routines'] = $routines;
					
					if(isset($sections[$sectionID])) {
						$this->data['sectionName'] = $this->lang->line("classesreport_section")." ".$sections[$sectionID]->section;
					} elseif ($sectionID == 0) {
						$this->data['sectionName'] = $this->lang->line("classesreport_select_all_section");
					}
					
					$this->data['collectionAmount'] = $totalPayment;
					$this->data['dueAmount'] = $totalInvoiceAmounts-$totalPayment;
					$this->data['feetypes'] = $feetypesArray;
					$this->data['classID']   = $classID;
					$this->data['sectionID'] = $sectionID;

					$this->reportSendToMail('classreport.css', $this->data, 'report/class/ClassReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
				    exit;
				}
			} else {
				$retArray['message'] = $this->lang->line("classesreport_permissionmethod");
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line("classesreport_permission");
			echo json_encode($retArray);
			exit;
		}
	}

	private function getDueAmount($array, $students, $sectionID) {
		$invoiceAmount = 0;
		$getDueAmounts = $this->invoice_m->get_dueamount($array);
		if(count($getDueAmounts)) {
			foreach($getDueAmounts as $getDueAmount) {
				if($sectionID > 0) {
					if(isset($students[$getDueAmount->studentID]) && $students[$getDueAmount->studentID]->srsectionID == $sectionID) {
						$amount = $getDueAmount->amount;
						$discountAmount = (($amount / 100) * $getDueAmount->discount);
						$weaver = $getDueAmount->weaver;
						$invoiceAmount += (($amount-$discountAmount)-$weaver);
					}
				} else {
					$amount = $getDueAmount->amount;
					$discountAmount = (($amount / 100) * $getDueAmount->discount);
					$weaver = $getDueAmount->weaver;
					$invoiceAmount += (($amount-$discountAmount)-$weaver);
				}
			}
		}
		return $invoiceAmount;
	}
}

