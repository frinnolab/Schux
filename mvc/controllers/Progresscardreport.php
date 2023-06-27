<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Progresscardreport extends Admin_Controller {
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
		$this->load->model("classes_m");
		$this->load->model('section_m');
		$this->load->model("studentrelation_m");
		$this->load->model("exam_m");
		$this->load->model("markpercentage_m");
		$this->load->model("subject_m");
		$this->load->model("setting_m");
		$this->load->model("mark_m");
		$this->load->model("grade_m");
		$this->load->model("studentgroup_m");
		$this->load->model("marksetting_m");

		$language = $this->session->userdata('lang');
		$this->lang->load('progresscardreport', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("progresscardreport_class"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("progresscardreport_section"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("progresscardreport_student"),
				'rules' => 'trim|xss_clean'
			),
		);
		return $rules;
	} 

	protected function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("progresscardreport_class"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("progresscardreport_section"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("progresscardreport_student"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line("progresscardreport_to"),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("progresscardreport_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("progresscardreport_message"),
				'rules' => 'trim|xss_clean'
			),
		);
		return $rules;
	}
	
 	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css',
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
			)
		);
		$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data["subview"] = "report/progresscard/ProgresscardReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getProgresscardreport () {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('progresscardreport')) {
			if($_POST) {
				$classesID    = $this->input->post('classesID');
				$sectionID    = $this->input->post('sectionID');
				$studentID    = $this->input->post('studentID');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->data['classesID'] = $classesID;
					$this->data['sectionID'] = $sectionID;
					$this->data['studentID'] = $studentID;

					$mArray       = [];
					$queryArray   = [];
					$mArray['schoolyearID']        = $schoolyearID;
					$queryArray['srschoolyearID']  = $schoolyearID;
					if((int)$classesID > 0) {
						$mArray['classesID']       = $classesID;
						$queryArray['srclassesID'] = $classesID;
					}
					if((int)$sectionID > 0) {
						$mArray['sectionID']       = $sectionID;
						$queryArray['srsectionID'] = $sectionID;
					}
					if((int)$studentID > 0) {
						$mArray['studentID']       = $studentID;
						$queryArray['srstudentID'] = $studentID;
					}

					$this->data['classes']  = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->data['groups']   = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');

					$students               = $this->studentrelation_m->general_get_order_by_student($queryArray);
					$marks                  = $this->mark_m->student_all_mark_array($mArray);
					$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 1));
					$optionalSubjects       = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 0));

					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
					$markpercentagesclassArr= isset($markpercentagesmainArr[$classesID]) ? $markpercentagesmainArr[$classesID] : [];
					$settingExam            = array_keys($markpercentagesclassArr);
					$percentageArr          = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
					
					$this->data['markpercentagesclassArr'] = $markpercentagesclassArr;
					$this->data['settingmarktypeID']       = $settingmarktypeID;

					$retMark = [];
					if(count($marks)) {
						foreach ($marks as $mark) {
							$retMark[$mark->examID][$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
						}
					}

					$markArray      = [];
					$studentChecker = [];
					$validExam      = [];
					if(count($settingExam)) {
						foreach($settingExam as $examID) {
							if(count($students)) {
								foreach ($students as $student) {
									$opuniquepercentageArr = [];
									if($student->sroptionalsubjectID > 0) {
										$opuniquepercentageArr = isset($markpercentagesclassArr[$examID][$student->sroptionalsubjectID]) ? $markpercentagesclassArr[$examID][$student->sroptionalsubjectID] : [];
									}
									$oppercentageMark = 0;
									if(count($mandatorySubjects)) {
										foreach ($mandatorySubjects as $mandatorySubject) {
											$uniquepercentageArr = isset($markpercentagesclassArr[$examID][$mandatorySubject->subjectID]) ? $markpercentagesclassArr[$examID][$mandatorySubject->subjectID] : [];
											$markpercentages     = [];
											if(count($uniquepercentageArr)) {
												$markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
											}

											if(count($markpercentages)) {
												foreach ($markpercentages as $markpercentageID) {
													$f = false;
		                                            if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
		                                                $f = true;
		                                            }

													if(isset($retMark[$examID][$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
														$markArray[$examID][$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$examID][$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
													}



													$f = false;
													if(count($opuniquepercentageArr)) {
			                                            if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
			                                                $f = true;
			                                            }
													}
													if(!isset($studentChecker['subject'][$examID][$student->srstudentID][$markpercentageID]) && $f) {
														$oppercentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
														if($student->sroptionalsubjectID > 0) {

															if(isset($retMark[$examID][$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																$markArray[$examID][$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$examID][$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
															}
														}
														$studentChecker['subject'][$examID][$student->srstudentID][$markpercentageID] = TRUE;
													}
												}
											}
										}
									}
								}
							}
						}
					}

					$this->data['percentageArr']     = $percentageArr;
					$this->data['grades']            = $this->grade_m->get_grade();
					$this->data['optionalSubjects']  = pluck($optionalSubjects,'obj','subjectID');
					$this->data['mandatorySubjects'] = $mandatorySubjects;
					$this->data['totalSubject']      = count($mandatorySubjects);
					$this->data['validExams']        = $validExam;
					$this->data['exams']             = pluck($this->exam_m->get_exam(),'exam','examID');;
					$this->data['students']          = $students;
					$this->data['markArray']         = $markArray;
					$this->data['settingExam']       = $settingExam;

					$retArray['render'] = $this->load->view('report/progresscard/ProgresscardReport',$this->data,true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit();
				}
			} else {
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
			$retArray['status'] = TRUE;
			echo json_encode($retArray);
			exit;
		}
	}

	public function pdf() {
		if(permissionChecker('progresscardreport')) {
			$classesID    = htmlentities(escapeString($this->uri->segment(3)));
			$sectionID    = htmlentities(escapeString($this->uri->segment(4)));
			$studentID    = htmlentities(escapeString($this->uri->segment(5)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;
				$this->data['studentID'] = $studentID;

				$mArray       = [];
				$queryArray   = [];
				$mArray['schoolyearID']        = $schoolyearID;
				$queryArray['srschoolyearID']  = $schoolyearID;
				if((int)$classesID > 0) {
					$mArray['classesID']       = $classesID;
					$queryArray['srclassesID'] = $classesID;
				}
				if((int)$sectionID > 0) {
					$mArray['sectionID']       = $sectionID;
					$queryArray['srsectionID'] = $sectionID;
				}
				if((int)$studentID > 0) {
					$mArray['studentID']       = $studentID;
					$queryArray['srstudentID'] = $studentID;
				}

				$this->data['classes']  = pluck($this->classes_m->general_get_classes(),'classes','classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
				$this->data['groups']   = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');

				$students               = $this->studentrelation_m->general_get_order_by_student($queryArray);
				$marks                  = $this->mark_m->student_all_mark_array($mArray);
				$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 1));
				$optionalSubjects       = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 0));

				$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
				$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
				$markpercentagesclassArr= isset($markpercentagesmainArr[$classesID]) ? $markpercentagesmainArr[$classesID] : [];
				$settingExam            = array_keys($markpercentagesclassArr);
				$percentageArr          = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
				
				$this->data['markpercentagesclassArr'] = $markpercentagesclassArr;
				$this->data['settingmarktypeID']       = $settingmarktypeID;

				$retMark = [];
				if(count($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->examID][$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}

				$markArray      = [];
				$studentChecker = [];
				$validExam      = [];
				if(count($settingExam)) {
					foreach($settingExam as $examID) {
						if(count($students)) {
							foreach ($students as $student) {
								$opuniquepercentageArr = [];
								if($student->sroptionalsubjectID > 0) {
									$opuniquepercentageArr = isset($markpercentagesclassArr[$examID][$student->sroptionalsubjectID]) ? $markpercentagesclassArr[$examID][$student->sroptionalsubjectID] : [];
								}
								$oppercentageMark = 0;
								if(count($mandatorySubjects)) {
									foreach ($mandatorySubjects as $mandatorySubject) {
										$uniquepercentageArr = isset($markpercentagesclassArr[$examID][$mandatorySubject->subjectID]) ? $markpercentagesclassArr[$examID][$mandatorySubject->subjectID] : [];
										$markpercentages     = [];
										if(count($uniquepercentageArr)) {
											$markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
										}

										if(count($markpercentages)) {
											foreach ($markpercentages as $markpercentageID) {
												$f = false;
	                                            if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
	                                                $f = true;
	                                            }

												if(isset($retMark[$examID][$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
													$markArray[$examID][$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$examID][$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
												}



												$f = false;
												if(count($opuniquepercentageArr)) {
		                                            if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
		                                                $f = true;
		                                            }
												}
												if(!isset($studentChecker['subject'][$examID][$student->srstudentID][$markpercentageID]) && $f) {
													$oppercentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
													if($student->sroptionalsubjectID > 0) {

														if(isset($retMark[$examID][$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$markArray[$examID][$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$examID][$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														}
													}
													$studentChecker['subject'][$examID][$student->srstudentID][$markpercentageID] = TRUE;
												}
											}
										}
									}
								}
							}
						}
					}
				}

				$this->data['percentageArr']     = $percentageArr;
				$this->data['grades']            = $this->grade_m->get_grade();
				$this->data['optionalSubjects']  = pluck($optionalSubjects,'obj','subjectID');
				$this->data['mandatorySubjects'] = $mandatorySubjects;
				$this->data['totalSubject']      = count($mandatorySubjects);
				$this->data['validExams']        = $validExam;
				$this->data['exams']             = pluck($this->exam_m->get_exam(),'exam','examID');;
				$this->data['students']          = $students;
				$this->data['markArray']         = $markArray;
				$this->data['settingExam']       = $settingExam;

				$this->reportPDF('progresscardreport.css', $this->data, 'report/progresscard/ProgresscardReportPDF');

			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_pdf_to_mail() {
		$retArray['status']  = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('progresscardreport')) {
			if($_POST) {
				$to           = $this->input->post('to');
				$subject      = $this->input->post('subject');
				$message      = $this->input->post('message');
				$classesID    = $this->input->post('classesID');
				$sectionID    = $this->input->post('sectionID');
				$studentID    = $this->input->post('studentID');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');

				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->data['classesID'] = $classesID;
					$this->data['sectionID'] = $sectionID;
					$this->data['studentID'] = $studentID;

					$mArray       = [];
					$queryArray   = [];
					$mArray['schoolyearID']        = $schoolyearID;
					$queryArray['srschoolyearID']  = $schoolyearID;
					if((int)$classesID > 0) {
						$mArray['classesID']       = $classesID;
						$queryArray['srclassesID'] = $classesID;
					}
					if((int)$sectionID > 0) {
						$mArray['sectionID']       = $sectionID;
						$queryArray['srsectionID'] = $sectionID;
					}
					if((int)$studentID > 0) {
						$mArray['studentID']       = $studentID;
						$queryArray['srstudentID'] = $studentID;
					}

					$this->data['classes']  = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->data['groups']   = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');

					$students               = $this->studentrelation_m->general_get_order_by_student($queryArray);
					$marks                  = $this->mark_m->student_all_mark_array($mArray);
					$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 1));
					$optionalSubjects       = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 0));

					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
					$markpercentagesclassArr= isset($markpercentagesmainArr[$classesID]) ? $markpercentagesmainArr[$classesID] : [];
					$settingExam            = array_keys($markpercentagesclassArr);
					$percentageArr          = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
					
					$this->data['markpercentagesclassArr'] = $markpercentagesclassArr;
					$this->data['settingmarktypeID']       = $settingmarktypeID;

					$retMark = [];
					if(count($marks)) {
						foreach ($marks as $mark) {
							$retMark[$mark->examID][$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
						}
					}

					$markArray      = [];
					$studentChecker = [];
					$validExam      = [];
					if(count($settingExam)) {
						foreach($settingExam as $examID) {
							if(count($students)) {
								foreach ($students as $student) {
									$opuniquepercentageArr = [];
									if($student->sroptionalsubjectID > 0) {
										$opuniquepercentageArr = isset($markpercentagesclassArr[$examID][$student->sroptionalsubjectID]) ? $markpercentagesclassArr[$examID][$student->sroptionalsubjectID] : [];
									}
									$oppercentageMark = 0;
									if(count($mandatorySubjects)) {
										foreach ($mandatorySubjects as $mandatorySubject) {
											$uniquepercentageArr = isset($markpercentagesclassArr[$examID][$mandatorySubject->subjectID]) ? $markpercentagesclassArr[$examID][$mandatorySubject->subjectID] : [];
											$markpercentages     = [];
											if(count($uniquepercentageArr)) {
												$markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
											}

											if(count($markpercentages)) {
												foreach ($markpercentages as $markpercentageID) {
													$f = false;
		                                            if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
		                                                $f = true;
		                                            }

													if(isset($retMark[$examID][$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
														$markArray[$examID][$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$examID][$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
													}



													$f = false;
													if(count($opuniquepercentageArr)) {
			                                            if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
			                                                $f = true;
			                                            }
													}
													if(!isset($studentChecker['subject'][$examID][$student->srstudentID][$markpercentageID]) && $f) {
														$oppercentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
														if($student->sroptionalsubjectID > 0) {

															if(isset($retMark[$examID][$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																$markArray[$examID][$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$examID][$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
															}
														}
														$studentChecker['subject'][$examID][$student->srstudentID][$markpercentageID] = TRUE;
													}
												}
											}
										}
									}
								}
							}
						}
					}

					$this->data['percentageArr']     = $percentageArr;
					$this->data['grades']            = $this->grade_m->get_grade();
					$this->data['optionalSubjects']  = pluck($optionalSubjects,'obj','subjectID');
					$this->data['mandatorySubjects'] = $mandatorySubjects;
					$this->data['totalSubject']      = count($mandatorySubjects);
					$this->data['validExams']        = $validExam;
					$this->data['exams']             = pluck($this->exam_m->get_exam(),'exam','examID');;
					$this->data['students']          = $students;
					$this->data['markArray']         = $markArray;
					$this->data['settingExam']       = $settingExam;

					$this->reportSendToMail('progresscardreport.css', $this->data, 'report/progresscard/ProgresscardReportPDF',$to, $subject,$message);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
    				exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('progresscardreport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('progresscardreport_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function getSection() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			$sections = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("progresscardreport_please_select"),"</option>";
			if(count($sections)) {
				foreach ($sections as $section) {
					echo "<option value=\"$section->sectionID\">".$section->section."</option>";
				}
			}
		}
	}

	public function getStudent() {
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$classesID && (int)$sectionID) {
			$students = $this->studentrelation_m->general_get_order_by_student(array('srclassesID'=>$classesID,'srsectionID'=>$sectionID,'srschoolyearID'=>$schoolyearID));
			if(count($students)) {
				echo "<option value='0'>". $this->lang->line("progresscardreport_please_select") ."</option>";
				foreach($students as $student) {
					echo "<option value=\"$student->srstudentID\">".$student->srname."</option>";
				}
			}
		}
	}	

	public function unique_data($data) {
		if($data != "") {
			if($data === "0") {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
		} 
		return TRUE;
	}
}
