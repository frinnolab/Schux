<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Onlineexamquestionreport extends Admin_Controller {
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
		$this->load->model('subject_m');
		$this->load->model('online_exam_m');
		$this->load->model('online_exam_question_m');
		$this->load->model('question_bank_m');
		$this->load->model('question_option_m');
		$this->load->model('question_answer_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('onlineexamquestionreport', $language);
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
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		$this->data['online_exams'] = $this->online_exam_m->get_order_by_online_exam(array('published'=>1, 'schoolyearID' => $schoolyearID));
		$this->data["subview"] = "report/onlineexamquestion/OnlineexamquestionReportView";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'onlineExamID',
				'label' => $this->lang->line('onlineexamquestionreport_examID'),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'typeID',
				'label' => $this->lang->line('onlineexamquestionreport_typeID'),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
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

	public function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line('onlineexamquestionreport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line('onlineexamquestionreport_subject'),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line('onlineexamquestionreport_message'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'onlineExamID',
				'label' => $this->lang->line('onlineexamquestionreport_examID'),
				'rules' => 'trim|numeric|required|xss_clean'
			),
			array(
				'field' => 'typeID',
				'label' => $this->lang->line('onlineexamquestionreport_typeID'),
				'rules' => 'trim|required|numeric|xss_clean'
			),
		);
		return $rules;
	}


	public function getQuestionList() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('onlineexamquestionreport')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {		
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$onlineExamID 	= $this->input->post('onlineExamID');
					$typeID 		= $this->input->post('typeID');
					
					$this->data['onlineExamID'] = $onlineExamID;
					$this->data['typeID'] = $typeID;
					$this->data['exam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID'=>$onlineExamID,'schoolyearID'=> $schoolyearID));
					if(count($this->data['exam'])) {
						$this->data['typeName'] = '';
						if($typeID == 1) {
							$this->data['typeName'] = $this->lang->line('onlineexamquestionreport_question');
						} elseif($typeID == 2) {
							$this->data['typeName'] = $this->lang->line('onlineexamquestionreport_ormsheet');
							$this->data['subjects'] = pluck($this->subject_m->general_get_subject(),'subject','subjectID');
						} elseif($typeID == 3) {
							$this->data['typeName'] = $this->lang->line('onlineexamquestionreport_answersheet');
						}

						$examquestions = pluck($this->online_exam_question_m->get_order_by_online_exam_question(array('onlineExamID'=>$onlineExamID)),'questionID');

						$this->data['questions'] = pluck($this->question_bank_m->get_question_bank_questionArray($examquestions,'questionBankID'),'obj','questionBankID');

						$this->data['question_options'] = pluck_multi_array($this->question_option_m->get_question_option_by_questionArray($examquestions,'questionID'),'obj','questionID');

						$this->data['getMark'] = $this->getTotalExamMark($this->data['questions']);

						if($typeID == 3) {
							$allAnswers = $this->question_answer_m->get_where_in_question_answer($examquestions,'questionID');
							$answers = [];
							if(count($allAnswers)) {
								foreach ($allAnswers as $answer) {
								    $answers[$answer->questionID][] = $answer;
								}
							}

							$this->data['answers'] = $answers;
						}
						$retArray['render'] = $this->load->view('report/onlineexamquestion/OnlineexamquestionReport', $this->data, true);
						$retArray['status'] = TRUE;
						echo json_encode($retArray);
					    exit;
					} else {
						$retArray['render'] = $this->load->view('report/reporterror', $this->data, true);
						$retArray['status'] = TRUE;
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['render'] = $this->load->view('report/reporterror', $this->data, true);
				$retArray['status'] = TRUE;
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['render'] = $this->load->view('report/reporterror', $this->data, true);
			$retArray['status'] = TRUE;
			echo json_encode($retArray);
			exit;
		}
	}

	public function pdf() {
		if(permissionChecker('onlineexamquestionreport')) {
			$onlineExamID = htmlentities(escapeString($this->uri->segment(3)));
			$typeID = htmlentities(escapeString($this->uri->segment(4)));
			if((int)$onlineExamID && (int)$typeID) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$this->data['onlineExamID'] = $onlineExamID;
				$this->data['typeID'] = $typeID;
				$this->data['exam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID'=>$onlineExamID,'schoolyearID'=> $schoolyearID));
				if(count($this->data['exam'])) {
					$this->data['typeName'] = '';
					if($typeID == 1) {
						$this->data['typeName'] = $this->lang->line('onlineexamquestionreport_question');
					} elseif($typeID == 2) {
						$this->data['typeName'] = $this->lang->line('onlineexamquestionreport_ormsheet');
						$this->data['subjects'] = pluck($this->subject_m->general_get_subject(),'subject','subjectID');
					} elseif($typeID == 3) {
						$this->data['typeName'] = $this->lang->line('onlineexamquestionreport_answersheet');
					}

					$examquestions = pluck($this->online_exam_question_m->get_order_by_online_exam_question(array('onlineExamID'=>$onlineExamID)),'questionID');

					$this->data['questions'] = pluck($this->question_bank_m->get_question_bank_questionArray($examquestions,'questionBankID'),'obj','questionBankID');

					$this->data['question_options'] = pluck_multi_array($this->question_option_m->get_question_option_by_questionArray($examquestions,'questionID'),'obj','questionID');

					$this->data['getMark'] = $this->getTotalExamMark($this->data['questions']);

					if($typeID == 3) {
						$allAnswers = $this->question_answer_m->get_where_in_question_answer($examquestions,'questionID');
						$answers = [];
						if(count($allAnswers)) {
							foreach ($allAnswers as $answer) {
							    $answers[$answer->questionID][] = $answer;
							}
						}

						$this->data['answers'] = $answers;
					}
					$this->reportPDF('onlineexamquestionreport.css', $this->data, 'report/onlineexamquestion/OnlineexamquestionReportPDF');
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

	private function getTotalExamMark($examQuestions) {
		$mark = 0;
		if(count($examQuestions)) {
			foreach ($examQuestions as $examQuestion) {
				if((int)$examQuestion->mark) {
					$mark += $examQuestion->mark;
				} else {
					$mark += 0;
				}
			}
		}
		return $mark;
	}

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		$retArray['message']= '';
		if(permissionChecker('onlineexamquestionreport')) {
			if($_POST) {
				$to           = $this->input->post('to');
				$subject      = $this->input->post('subject');
				$message 	  = $this->input->post('message');
				$onlineExamID = $this->input->post('onlineExamID');
				$typeID	      = $this->input->post('typeID');
				
				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$this->data['onlineExamID'] = $onlineExamID;
					$this->data['typeID'] = $typeID;
					$this->data['exam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID'=>$onlineExamID,'schoolyearID'=> $schoolyearID));
					if(count($this->data['exam'])) {
						$this->data['typeName'] = '';
						if($typeID == 1) {
							$this->data['typeName'] = $this->lang->line('onlineexamquestionreport_question');
						} elseif($typeID == 2) {
							$this->data['typeName'] = $this->lang->line('onlineexamquestionreport_ormsheet');
							$this->data['subjects'] = pluck($this->subject_m->general_get_subject(),'subject','subjectID');
						} elseif($typeID == 3) {
							$this->data['typeName'] = $this->lang->line('onlineexamquestionreport_answersheet');
						}

						$examquestions = pluck($this->online_exam_question_m->get_order_by_online_exam_question(array('onlineExamID'=>$onlineExamID)),'questionID');

						$this->data['questions'] = pluck($this->question_bank_m->get_question_bank_questionArray($examquestions,'questionBankID'),'obj','questionBankID');

						$this->data['question_options'] = pluck_multi_array($this->question_option_m->get_question_option_by_questionArray($examquestions,'questionID'),'obj','questionID');

						$this->data['getMark'] = $this->getTotalExamMark($this->data['questions']);

						if($typeID == 3) {
							$allAnswers = $this->question_answer_m->get_where_in_question_answer($examquestions,'questionID');
							$answers = [];
							if(count($allAnswers)) {
								foreach ($allAnswers as $answer) {
								    $answers[$answer->questionID][] = $answer;
								}
							}

							$this->data['answers'] = $answers;
						}

						$this->reportSendToMail('onlineexamquestionreport.css', $this->data, 'report/onlineexamquestion/OnlineexamquestionReportPDF', $to, $subject, $message);
						$retArray['status'] = TRUE;
						echo json_encode($retArray);
					    exit;
					} else {
						$retArray['message'] = $this->lang->line("onlineexamquestionreport_data_not_found");
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line("onlineexamquestionreport_permisionmethod");
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line("onlineexamquestionreport_permision");
			echo json_encode($retArray);
			exit;
		}
	}

}
