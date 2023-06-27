<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Admin_Controller {
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
		$this->load->model('usertype_m');
		$this->load->model('section_m');
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model("teacher_m");
		$this->load->model("user_m");
		$this->load->model("systemadmin_m");
		$this->load->model('studentrelation_m');
		$this->load->model('studentgroup_m');
		$this->load->model('manage_salary_m');
		$this->load->model('salary_template_m');
		$this->load->model('salaryoption_m');
		$this->load->model('uattendance_m');
		$this->load->model('make_payment_m');
		$this->load->model('tattendance_m');
		$this->load->model('routine_m');
		$this->load->model('subject_m');
		$this->load->model('sattendance_m');
		$this->load->model('payment_m');
		$this->load->model('exam_m');
		$this->load->model('grade_m');
		$this->load->model('mark_m');
		$this->load->model('markpercentage_m');
		$this->load->model('invoice_m');
		$this->load->model('weaverandfine_m');
		$this->load->model('feetypes_m');
		$this->load->model('document_m');
		$this->load->model('hourly_template_m');
		$this->load->model('subjectattendance_m');
		$this->load->model('leaveapplication_m');
		$this->load->model('marksetting_m');
		
		$language = $this->session->userdata('lang');
		$this->lang->load('profile', $language);
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("profile_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("profile_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("profile_message"),
				'rules' => 'trim|xss_clean'
			)
		);
		return $rules;
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css'
			),
			'js' => array(
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js'
			)
		);

		$usertypeID = $this->session->userdata("usertypeID");
		$loginuserID = $this->session->userdata('loginuserID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if($usertypeID == 1) {
			$user = $this->systemadmin_m->get_single_systemadmin(array('systemadminID' => $loginuserID));
		} elseif($usertypeID == 2) {
			$user = $this->teacher_m->get_single_teacher(array('teacherID' => $loginuserID));
		} elseif($usertypeID == 3) {
			$user = $this->studentrelation_m->get_single_student(array('srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID), TRUE);
		} elseif($usertypeID == 4) {
			$user = $this->parents_m->get_single_parents(array("parentsID" => $loginuserID));
		} else {
			$user = $this->user_m->get_single_user(array("userID" => $loginuserID, 'usertypeID' => $usertypeID));
		}
		
		$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear($loginuserID,$schoolyearID,$usertypeID);
		
		$this->getView($user);
	}
	
	private function getView($getUser) {
		if(count($getUser)) {
			$this->pluckInfo();
			$this->basicInfo($getUser);
			$this->salaryInfo($getUser);
			$this->attendanceInfo($getUser);
			$this->paymentInfo($getUser);

			if($getUser->usertypeID == 3) {
				$this->parentInfo($getUser);
				$this->markInfo($getUser);
				$this->invoiceInfo($getUser);
			}

			if($getUser->usertypeID == 4) {
				$this->childrenInfo($getUser);
			}

			$this->routineInfo($getUser);
			$this->documentInfo($getUser);
			
			if(count($getUser)) {
				$this->data["subview"] = "profile/index";
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

	private function pluckInfo() {
		$this->data['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
		$this->data['classess'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
		$this->data['sections'] = pluck($this->section_m->get_section(), 'section', 'sectionID');
		$this->data['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
		$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(), 'feetypes', 'feetypesID');
		$this->data['teachers'] = pluck($this->teacher_m->get_teacher(), 'name', 'teacherID');
	}

	private function basicInfo($getUser) {
		if(count($getUser)) {
			$this->data['profile'] = $getUser;
			if($getUser->usertypeID == 3) {
				$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $getUser->usertypeID));
				$this->data['class'] = $this->classes_m->get_single_classes(array('classesID' => $getUser->srclassesID));
				$this->data['sectionn'] = $this->section_m->get_single_section(array('sectionID' => $getUser->srsectionID));
				$this->data['group'] = $this->studentgroup_m->get_single_studentgroup(array('studentgroupID' => $getUser->srstudentgroupID));
				$this->data['optionalsubject'] = $this->subject_m->get_single_subject(array('subjectID' => $getUser->sroptionalsubjectID));
			}

		} else {
			$this->data['profile'] = [];
		}
	}

	private function salaryInfo($getUser) {
		if(count($getUser)) {
			if($getUser->usertypeID == 1) {
            	$manageSalary = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $getUser->usertypeID, 'userID' => $getUser->systemadminID));
			} elseif($getUser->usertypeID == 2) {
            	$manageSalary = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $getUser->usertypeID, 'userID' => $getUser->teacherID));
			} elseif($getUser->usertypeID == 3) {
				$manageSalary = [];
			} elseif($getUser->usertypeID == 4) {
				$manageSalary = [];
			} else {
            	$manageSalary = $this->manage_salary_m->get_single_manage_salary(array('usertypeID' => $getUser->usertypeID, 'userID' => $getUser->userID));
			}
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

	public function attendanceInfo($getUser) {
		if(count($getUser)) {
			$this->data['holidays'] =  $this->getHolidaysSession();
			$this->data['getWeekendDays'] =  $this->getWeekendDaysSession();
			$schoolyearID = $this->session->userdata('defaultschoolyearID');

			if($getUser->usertypeID == 1) {
				$uattendances = [];
			} elseif($getUser->usertypeID == 2) {
				$uattendances = $this->tattendance_m->get_order_by_tattendance(array("teacherID" => $getUser->teacherID, 'schoolyearID' => $schoolyearID));
			} elseif($getUser->usertypeID == 3) {
				$this->data['setting'] = $this->setting_m->get_setting();

				if($this->data['setting']->attendance == "subject") {
					$this->data["attendancesubjects"] = $this->subject_m->get_order_by_subject(array("classesID" => $getUser->srclassesID));
					$uattendances = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $getUser->srstudentID, "classesID" => $getUser->srclassesID, 'schoolyearID'=> $schoolyearID));
					$this->data['attendances_subjectwisess'] = pluck_multi_array_key($uattendances, 'obj', 'subjectID', 'monthyear');
				} else {
					$uattendances = $this->sattendance_m->get_order_by_attendance(array("studentID" => $getUser->srstudentID, "classesID" => $getUser->srclassesID,'schoolyearID'=> $schoolyearID));
				}
			} elseif($getUser->usertypeID == 4) {
				$uattendances = [];
			} else {
				$uattendances = $this->uattendance_m->get_order_by_uattendance(array("userID" => $getUser->userID, 'schoolyearID' => $schoolyearID));
			}
			$this->data['attendancesArray'] = pluck($uattendances,'obj','monthyear');
		} else {
			$this->data['holidays'] = [];
			$this->data['getWeekendDays'] = [];
			$this->data['attendancesArray'] = [];
		}
	}

	private function paymentInfo($getUser) {
		if(count($getUser)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if($getUser->usertypeID == 1) {
				$this->data['make_payments'] = $this->make_payment_m->get_order_by_make_payment(array('usertypeID' => $getUser->usertypeID, 'userID' => $getUser->systemadminID,'schoolyearID'=>$schoolyearID));
			} elseif($getUser->usertypeID == 2) {
				$this->data['make_payments'] = $this->make_payment_m->get_order_by_make_payment(array('usertypeID' => $getUser->usertypeID, 'userID' => $getUser->teacherID, 'schoolyearID'=>$schoolyearID));
			} elseif($getUser->usertypeID == 3) {
				$this->data['payments'] = $this->payment_m->get_payment_with_studentrelation_by_studentID_and_schoolyearID($getUser->srstudentID, $schoolyearID);
			} elseif($getUser->usertypeID == 4) {
				$this->data['make_payments'] = [];
			} else {
				$this->data['make_payments'] = $this->make_payment_m->get_order_by_make_payment(array('usertypeID' => $getUser->usertypeID, 'userID' => $getUser->userID, 'schoolyearID'=>$schoolyearID));
			}
		} else {
			$this->data['make_payments'] = [];
		}
	}

	private function routineInfo($getUser) {
		$settingWeekends = [];
		if($this->data['siteinfos']->weekends != '') {
			$settingWeekends = explode(',', $this->data['siteinfos']->weekends);
		}
		$this->data['routineweekends'] = $settingWeekends;
		$this->data['routines']        = [];
		if(count($getUser)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if($getUser->usertypeID == 1) {
				$this->data['routines'] = [];
			} elseif($getUser->usertypeID == 2) {
				$this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine(array('teacherID'=>$getUser->teacherID, 'schoolyearID'=> $schoolyearID)), 'obj', 'day');
			} elseif($getUser->usertypeID == 3) {
				$this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine(array('classesID'=> $getUser->srclassesID, 'sectionID'=> $getUser->srsectionID, 'schoolyearID'=> $schoolyearID)), 'obj', 'day');
			} else {
				$this->data['routines'] = [];
			}
		}
	}

	private function parentInfo($getUser) {
		if(count($getUser)) {
			$this->data['parents'] = $this->parents_m->get_single_parents(array('parentsID' => $getUser->parentID));
		} else {
			$this->data['parents'] = [];
		}
	}

	private function markInfo($getUser) {
		if(count($getUser)) {
			$this->getMark($getUser->srstudentID, $getUser->srclassesID);
		} else {
			$this->data['set'] 				= [];
			$this->data["exams"] 			= [];
			$this->data["grades"] 			= [];
			$this->data['markpercentages']	= [];
			$this->data['validExam'] 		= [];
			$this->data['separatedMarks'] 	= [];
			$this->data["highestMarks"] 	= [];
			$this->data["section"] 			= [];
		}
	}

	private function getMark($studentID, $classesID) {
		if((int)$studentID && (int)$classesID) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$student      = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
			$classes      = $this->classes_m->get_single_classes(array('classesID' => $classesID));

			if(count($student) && count($classes)) {
				$queryArray = [
					'classesID'    => $student->srclassesID,
					'sectionID'    => $student->srsectionID,
					'studentID'    => $student->srstudentID, 
					'schoolyearID' => $schoolyearID, 
				];

				$exams             = pluck($this->exam_m->get_exam(), 'exam', 'examID');
				$grades            = $this->grade_m->get_grade();
				$marks             = $this->mark_m->student_all_mark_array($queryArray);
				$markpercentages   = $this->markpercentage_m->get_markpercentage();

				$subjects          = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
				$subjectArr        = [];
				$optionalsubjectArr= [];
				if(count($subjects)) {
					foreach ($subjects as $subject) {
						if($subject->type == 0) {
							$optionalsubjectArr[$subject->subjectID] = $subject->subjectID;
						}
						$subjectArr[$subject->subjectID] = $subject;
					}
				}

				$retMark = [];
				if(count($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}

				$allStudentMarks = $this->mark_m->student_all_mark_array(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
				$highestMarks    = [];
				foreach ($allStudentMarks as $value) {
					if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
						$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
					}
					$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
				}
				$marksettings  = $this->marksetting_m->get_marksetting_markpercentages();

				$this->data['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;
				$this->data['subjects']          = $subjectArr;
				$this->data['exams']             = $exams;
				$this->data['grades']            = $grades;
				$this->data['markpercentages']   = pluck($markpercentages, 'obj', 'markpercentageID');
				$this->data['optionalsubjectArr']= $optionalsubjectArr;
				$this->data['marks']             = $retMark;
				$this->data['highestmarks']      = $highestMarks;
				$this->data['marksettings']      = isset($marksettings[$classesID]) ? $marksettings[$classesID] : [];
			} else {
				$this->data['settingmarktypeID'] = 0;
				$this->data['subjects']          = [];
				$this->data['exams']             = [];
				$this->data['grades']            = [];
				$this->data['markpercentages']   = [];
				$this->data['optionalsubjectArr']= [];
				$this->data['marks']             = [];
				$this->data['highestmarks']      = [];
				$this->data['marksettings']      = [];
			}
		} else {
			$this->data['settingmarktypeID'] = 0;
			$this->data['subjects']          = [];
			$this->data['exams']             = [];
			$this->data['grades']            = [];
			$this->data['markpercentages']   = [];
			$this->data['optionalsubjectArr']= [];
			$this->data['marks']             = [];
			$this->data['highestmarks']      = [];
			$this->data['marksettings']      = [];
		}
	}

	private function invoiceInfo($getUser) {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if(count($getUser)) {
			$this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('schoolyearID' => $schoolyearID, 'studentID' => $getUser->srstudentID, 'deleted_at'=>1));

			$payments = $this->payment_m->get_order_by_payment(array('schoolyearID' => $schoolyearID, 'studentID' => $getUser->srstudentID));
			$weaverandfines = $this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID' => $schoolyearID, 'studentID' => $getUser->srstudentID));

			$this->data['allpaymentbyinvoice'] = $this->allPaymentByInvoice($payments);
			$this->data['allweaverandpaymentbyinvoice'] = $this->allWeaverAndFineByInvoice($weaverandfines);
		} else {
			$this->data['invoices'] = [];
			$this->data['allpaymentbyinvoice'] = [];
			$this->data['allweaverandpaymentbyinvoice'] = [];
		}
	}

	private function allPaymentByInvoice($payments) {
		$retPaymentArr = [];
		if($payments) {
			foreach ($payments as $payment) {
				if(isset($retPaymentArr[$payment->invoiceID])) {
					$retPaymentArr[$payment->invoiceID] += $payment->paymentamount;
				} else {
					$retPaymentArr[$payment->invoiceID] = $payment->paymentamount;					
				}
			}
		}
		return $retPaymentArr;
	}

	private function allWeaverAndFineByInvoice($weaverandfines) {
		$retWeaverAndFineArr = [];
		if($weaverandfines) {
			foreach ($weaverandfines as $weaverandfine) {
				if(isset($retWeaverAndFineArr[$weaverandfine->invoiceID]['weaver'])) {
					$retWeaverAndFineArr[$weaverandfine->invoiceID]['weaver'] += $weaverandfine->weaver;
				} else {
					$retWeaverAndFineArr[$weaverandfine->invoiceID]['weaver'] = $weaverandfine->weaver;					
				}

				if(isset($retWeaverAndFineArr[$weaverandfine->invoiceID]['fine'])) {
					$retWeaverAndFineArr[$weaverandfine->invoiceID]['fine'] += $weaverandfine->fine;
				} else {
					$retWeaverAndFineArr[$weaverandfine->invoiceID]['fine'] = $weaverandfine->fine;					
				}
			}
		}
		return $retWeaverAndFineArr;
	}

	private function documentInfo($getUser) {
		if(count($getUser)) {
			$userID = 0;
			if($getUser->usertypeID == 1) {
				$userID = $getUser->systemadminID;
			} elseif($getUser->usertypeID == 2) {
				$userID = $getUser->teacherID;
			} elseif($getUser->usertypeID == 3) {
				$userID = $getUser->srstudentID;
			} elseif($getUser->usertypeID == 4) {
				$userID = $getUser->parentsID;
			} else {
				$userID = $getUser->userID;
			}

			$this->data['documentUserID'] = $userID;

			$this->data['documents'] = $this->document_m->get_order_by_document(array('userID' => $userID, 'usertypeID' => $getUser->usertypeID));
		} else {
			$this->data['documents'] = [];
		}
	}

	private function childrenInfo($getUser) {
		$this->data['childrens'] = [];
		if(count($getUser)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->db->order_by('student.classesID', 'asc');
			$this->data['childrens'] = $this->studentrelation_m->general_get_order_by_student(array('parentID' => $getUser->parentsID, 'srschoolyearID' => $schoolyearID));
		}
	}

	public function download_document() {
		$documentID = htmlentities(escapeString($this->uri->segment(3)));
		$userID 	= htmlentities(escapeString($this->uri->segment(4)));
		$usertypeID 	= htmlentities(escapeString($this->uri->segment(5)));
		if((int)$documentID && (int)$userID && (int)$usertypeID) {
			$document = $this->document_m->get_single_document(array('documentID' => $documentID));
			if(count($document)) {
				if($document->usertypeID == $this->session->userdata('usertypeID') && $document->userID == $this->session->userdata('loginuserID')) {
					$file = realpath('uploads/documents/'.$document->file);
				    if(file_exists($file)) {
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
				    	redirect(base_url('profile/index'));
				    }
				} else {
					redirect(base_url('profile/index'));
				}
			} else {
				redirect(base_url('profile/index'));
			}
		} else {
			redirect(base_url('profile/index'));
		}
	}

	protected function rules() {
		if($this->session->userdata('usertypeID') == 3) {
			$dobRules = 'trim|max_length[10]|callback_date_valid|xss_clean';
		} else {
			$dobRules = 'trim|required|max_length[10]|callback_date_valid|xss_clean';
		}
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("profile_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
			array(
				'field' => 'dob',
				'label' => $this->lang->line("profile_dob"),
				'rules' => $dobRules,
			),
			array(
				'field' => 'sex',
				'label' => $this->lang->line("profile_sex"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("profile_phone"),
				'rules' => 'trim|max_length[25]|min_length[5]|xss_clean'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("profile_address"),
				'rules' => 'trim|max_length[200]|xss_clean'
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("profile_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			),
			
			array(
				'field' => 'religion',
				'label' => $this->lang->line("profile_religion"),
				'rules' => 'trim|max_length[25]|xss_clean'
			),
			array(
				'field' => 'bloodgroup',
				'label' => $this->lang->line("profile_bloodgroup"),
				'rules' => 'trim|max_length[5]|xss_clean'
			),
			array(
				'field' => 'state',
				'label' => $this->lang->line("profile_state"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),
			array(
				'field' => 'country',
				'label' => $this->lang->line("profile_country"),
				'rules' => 'trim|max_length[128]|xss_clean'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("profile_email"),
				'rules' => 'trim|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("profile_email"),
				'rules' => 'trim|required|max_length[40]|valid_email|xss_clean|callback_unique_email'
			),
			
			array(
				'field' => 'designation', 
				'label' => $this->lang->line("profile_designation"),
				'rules' => 'trim|required|max_length[128]|xss_clean'
			),
			array(
				'field' => 'father_name',
				'label' => $this->lang->line("profile_father_name"), 
				'rules' => 'trim|xss_clean|max_length[60]'
			),
			array(
				'field' => 'mother_name', 
				'label' => $this->lang->line("profile_mother_name"), 
				'rules' => 'trim|xss_clean|max_length[60]'
			),
			array(
				'field' => 'father_profession', 
				'label' => $this->lang->line("profile_father_name"), 
				'rules' => 'trim|xss_clean|max_length[40]'
			),
			array(
				'field' => 'mother_profession', 
				'label' => $this->lang->line("profile_mother_name"), 
				'rules' => 'trim|xss_clean|max_length[40]'
			),
		);
		return $rules;
	}

	public function photoupload() {
		$passUserData = array();
		$username = $this->session->userdata('username');
		if($username) {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_single_username($table, array('username' => $username ));
				if(count($user)) {
					$this->form_validation->set_message("unique_email", "%s already exists");
					$passUserData = $user;
				}
			}
		}

		$new_file = "default.png";
		if($_FILES["photo"]['name'] !="") {
			$file_name = $_FILES["photo"]['name'];
			$random = random19();
	    	$makeRandom = hash('sha512', $random.rand(1, 9999999999) . config_item("encryption_key"));
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
			if(count($passUserData)) {
				$this->upload_data['file'] = array('file_name' => $passUserData->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
				return TRUE;
			}
		}
	}

	public function edit() {
		if($this->data['siteinfos']->profile_edit || ($this->session->userdata('usertypeID') == 1 && $this->session->userdata('loginuserID') == 1)) {
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

			$tableArray = array('1' => 'systemadmin', '2' => 'teacher', '3' => 'student', '4' => 'parents');
			if(!isset($tableArray[$this->session->userdata('usertypeID')])) {
				$tableArray[$this->session->userdata('usertypeID')] = 'user';
			}

			$usertypeID   = $this->session->userdata('usertypeID');
			$username     = $this->session->userdata('username');
			$this->data['usertypeID'] = $usertypeID;
			if($usertypeID == 1) {
				$rules = $this->rules();
				unset($rules[7], $rules[8], $rules[9], $rules[10], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
				$this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('username' => $username));
			} elseif($usertypeID == 2) {
				$rules = $this->rules();
				unset($rules[7], $rules[8], $rules[9], $rules[10], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
				$this->data['user'] = $this->teacher_m->get_single_teacher(array('username' => $username));
			} elseif($usertypeID == 3) {
				$rules = $this->rules();
				unset($rules[11], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$this->data['user'] = $this->studentrelation_m->get_single_student(array('username' => $username,'srschoolyearID'=>$schoolyearID));
			} elseif($usertypeID == 4) {
				$rules = $this->rules();
				unset($rules[1], $rules[2], $rules[6], $rules[7], $rules[8], $rules[9], $rules[11], $rules[12]);
				$this->data['user'] = $this->parents_m->get_single_parents(array('username' => $username));
			} else {
				$rules = $this->rules();
				unset($rules[7], $rules[8], $rules[9], $rules[10], $rules[12], $rules[13], $rules[14], $rules[15], $rules[16]);
				$this->data['user'] = $this->user_m->get_single_user(array('username' => $username));
			}

			if($_POST) {
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "profile/edit";
					$this->load->view('_layout_main', $this->data);
				} else {
					$array = array();
					foreach ($rules as $rulekey => $rule) {
						if($rule['field'] == 'dob') {
							if($this->input->post($rule['field'])) {
								$array[$rule['field']] = date("Y-m-d", strtotime($this->input->post($rule['field'])));	
							}
						} else {
							$array[$rule['field']] = $this->input->post($rule['field']);
						}
					}

					if($usertypeID == 3) {
						$schoolyearID = $this->session->userdata('defaultschoolyearID');
						$getRelationTableStudent = $this->studentrelation_m->get_single_studentrelation(array('srstudentID' => $this->data['user']->srstudentID,'srschoolyearID'=> $schoolyearID));
						if(count($getRelationTableStudent)) {
							$this->student_m->profileRelationUpdate('studentrelation', array('srname' => $this->input->post('name')), $this->data['user']->srstudentID,$schoolyearID);
						}
					}

					$array['photo'] = $this->upload_data['file']['file_name'];
					
					$this->session->set_userdata(array('name' => $this->input->post('name'), 'email' => $this->input->post('email'), 'photo' => $array['photo']));
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
					$this->student_m->profileUpdate($tableArray[$usertypeID], $array, $username);
					redirect(base_url('profile/index'));
				}
			} else {
				$this->data['subview'] = 'profile/edit';
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			redirect(base_url('profile/index'));
		}
	}

	public function date_valid($date) {
		if($date) {
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
		return TRUE;
	}

	public function unique_email() {
		if($this->input->post('email')) {
			$username = $this->session->userdata('username');
			if($username) {
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $username ));
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
		return TRUE;
	}

	public function print_preview() {
		$usertypeID   = $this->session->userdata("usertypeID");
		$userID       = $this->session->userdata('loginuserID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if($usertypeID == 1) {
			$this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('systemadminID' => $userID));
		} elseif($usertypeID == 2) {
			$this->data['user'] = $this->teacher_m->get_single_teacher(array('teacherID' => $userID));
		} elseif($usertypeID == 3) {
			$this->data['user']    = $this->studentrelation_m->get_single_student(array('srstudentID' => $userID,'srschoolyearID'=>$schoolyearID),TRUE);
			$this->data["class"]   = $this->classes_m->general_get_single_classes(['classesID'=> $this->data['user']->srclassesID]);
			$this->data["section"] = $this->section_m->general_get_single_section(['sectionID'=> $this->data['user']->srsectionID]);
			$this->data['optionalsubject'] = $this->subject_m->get_single_subject(array('subjectID' => $this->data['user']->sroptionalsubjectID));
			$this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
		} elseif($usertypeID == 4) {
			$this->data['user'] = $this->parents_m->get_single_parents(array("parentsID" => $userID));
		} else {
			$this->data['user'] = $this->user_m->get_single_user(array("userID" => $userID));
		}
		
		$this->data['usertypeID'] =$usertypeID;
		$this->data['usertype'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
		if(count($this->data['user'])) {
			$this->reportPDF('profilemodule.css',$this->data, 'profile/print_preview');
		} else {
			$this->data['subview'] ='error';
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if($_POST) {
			$rules = $this->send_mail_rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$retArray = $this->form_validation->error_array();
				$retArray['status'] = FALSE;
			    echo json_encode($retArray);
			    exit;
			} else {
				$usertypeID = $this->session->userdata("usertypeID");
				$userID     = $this->session->userdata('loginuserID');
				$username   = $this->session->userdata('username');
				if($usertypeID == 1) {
					$this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('username' => $username));
				} elseif($usertypeID == 2) {
					$this->data['user'] = $this->teacher_m->get_single_teacher(array('username' => $username));
				} elseif($usertypeID == 3) {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$this->data['user'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $userID,'srschoolyearID'=>$schoolyearID),TRUE);
					$this->data['optionalsubject'] = $this->subject_m->get_single_subject(array('subjectID' => $this->data['user']->sroptionalsubjectID));
				} elseif($usertypeID == 4) {
					$this->data['user'] = $this->parents_m->get_single_parents(array("username" => $username));
				} else {
					$this->data['user'] = $this->user_m->get_single_user(array("username" => $username));
				}

				$this->data['usertypeID'] = $usertypeID;
				$this->data['usertype'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
				if(count($this->data['user'])) {
					$email = $this->input->post('to');
					$subject = $this->input->post('subject');
					$message = $this->input->post('message');
					$this->reportSendToMail('profilemodule.css',$this->data, 'profile/print_preview', $email, $subject, $message);
					$retArray['message'] = "Message";
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
				    exit;
				} else {
					$retArray['message'] = $this->lang->line('profile_data_not_found');
					echo json_encode($retArray);
					exit;
				}
			}
		} else {
			$retArray['message'] = $this->lang->line('profile_permissionmethod');
			echo json_encode($retArray);
			exit;
		}
	}

	private function leave_applications_date_list_by_user_and_schoolyear($userID, $schoolyearID, $usertypeID) {
		$leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication(array('create_userID'=>$userID,'create_usertypeID'=>$usertypeID,'schoolyearID'=>$schoolyearID,'status'=>1));
		
		$retArray = [];
		if(count($leaveapplications)) {
			$oneday    = 60*60*24;
			foreach($leaveapplications as $leaveapplication) {
			    for($i=strtotime($leaveapplication->from_date); $i<= strtotime($leaveapplication->to_date); $i= $i+$oneday) {
			        $retArray[] = date('d-m-Y', $i);
			    }
			}
		}
		return $retArray;
	}
}
