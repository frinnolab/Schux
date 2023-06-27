<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marksetting extends Admin_Controller {
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
		$this->load->model("exam_m");
		$this->load->model("classes_m");
		$this->load->model("subject_m");
		$this->load->model("setting_m");
		$this->load->model("marksetting_m");
		$this->load->model("markpercentage_m");
		$this->load->model("marksettingrelation_m");


		$language = $this->session->userdata('lang');
		$this->lang->load('marksetting', $language);	
	}

	protected function rules() {
		$marktypeID = $this->input->post('marktypeID');
		$rules = array(
			array(
				'field' => 'marktypeID', 
				'label' => $this->lang->line("marksetting_mark_type"), 
				'rules' => 'trim|required|xss_clean|callback_required_marktype'
			),
			array(
				'field' => 'markpercentages[]', 
				'label' => $this->lang->line("marksetting_mark_percentage"), 
				'rules' => 'trim|required|xss_clean|callback_required_markpercentages|callback_check_markpercentage'
			)
		);
		if(($marktypeID == 0) || ($marktypeID == 1) || ($marktypeID == 2) || ($marktypeID == 3) || ($marktypeID == 5) || ($marktypeID == 6)) {
			$rules[] = array(
				'field' => 'exams[]', 
				'label' => $this->lang->line("marksetting_exam"), 
				'rules' => 'trim|required|xss_clean|callback_required_exams'
			);
		}
		return $rules;
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
		
		$ex_class                      = $this->data['siteinfos']->ex_class;
		$this->data['classes']         = $this->classes_m->general_get_order_by_classes(['classesID !='=> $ex_class]);
		$this->data['exams']           = $this->exam_m->get_exam();
		$this->data['subjects']        = pluck_multi_array($this->subject_m->general_get_subject(), 'obj', 'classesID');
		$this->data['markpercentages'] = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

		$marksetting                     = $this->marksetting_m->get_marksetting_with_marksettingrelation();

		$examArr                       = [];
		$markpercentageArr             = [];
		$classpercentageArr            = [];
		$exampercentageArr             = [];
		$subjectpercentageArr          = [];
		$classexampercentageArr        = [];
		$classexamsubjectpercentageArr = [];
		if(count($marksetting)) {
			foreach ($marksetting as $marksett) {
				$examArr[$marksett->marktypeID][]            = $marksett->examID;
				$markpercentageArr[$marksett->marktypeID][]  = $marksett->markpercentageID;
				$classpercentageArr[$marksett->marktypeID][$marksett->classesID][]  = $marksett->markpercentageID;
				$exampercentageArr[$marksett->marktypeID][$marksett->examID][]      = $marksett->markpercentageID;
				$subjectpercentageArr[$marksett->marktypeID][$marksett->classesID][$marksett->subjectID][]  = $marksett->markpercentageID;
				$classexampercentageArr[$marksett->marktypeID][$marksett->classesID][$marksett->examID][]   = $marksett->markpercentageID;
				$classexamsubjectpercentageArr[$marksett->marktypeID][$marksett->classesID][$marksett->examID][$marksett->subjectID][]  = $marksett->markpercentageID;
			}
		}
		$this->data['examArr']                       = $examArr;
		$this->data['markpercentageArr']             = $markpercentageArr;
		$this->data['classpercentageArr']            = $classpercentageArr;
		$this->data['exampercentageArr']             = $exampercentageArr;
		$this->data['subjectpercentageArr']          = $subjectpercentageArr;
		$this->data['classexampercentageArr']        = $classexampercentageArr;
		$this->data['classexamsubjectpercentageArr'] = $classexamsubjectpercentageArr;

		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$errors  = $this->form_validation->error_array();
				$message = '';
				if(count($errors)) {
					foreach ($errors as $error) {
						$message .= $error.'<br/>';
					}
				}
				$this->session->set_flashdata('error', $message);
				$this->data["subview"]          = "marksetting/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$marktypeID = $this->input->post('marktypeID');
				$this->setting_m->insertorupdate(['marktypeID'=> $marktypeID]);

				$marksettingArr         = [];
				$marksettingRelationArr = [];
				if($marktypeID == 0) {
					$exams = $this->input->post('exams');
					if(count($exams)) {
						$i = 0;
						foreach ($exams as $exam) {
							$examArr = explode('_', $exam);
							$examID  = isset($examArr[1]) ? $examArr[1] : 0;
							$marksettingArr[$i]['examID']     = $examID;
							$marksettingArr[$i]['classesID']  = 0;
							$marksettingArr[$i]['subjectID']  = 0;
							$marksettingArr[$i]['marktypeID'] = 0;
							$i++;
						}
					}

					$this->marksetting_m->delete_marksetting_by_array(['marktypeID'=> 0]);
					$marksettingCount  = ((count($marksettingArr) > 0) ? count($marksettingArr) : 0);
					$marksettingID     = 0;
					if($marksettingCount > 0) {
						$marksettingID = $this->marksetting_m->insert_batch_marksetting($marksettingArr);
					}

					$markpercentages   = $this->input->post('markpercentages');
					$i = 0; $j = 0;
					while ($j < $marksettingCount) {
						if(count($markpercentages)) {
							foreach ($markpercentages as $markpercentage) {
								$markpercentage    = explode('_', $markpercentage);
								$markpercentageID  = isset($markpercentage[1]) ? $markpercentage[1] : 0;
								
								$marksettingRelationArr[$i]['marktypeID']       = 0;
								$marksettingRelationArr[$i]['marksettingID']    = $marksettingID;
								$marksettingRelationArr[$i]['markpercentageID'] = $markpercentageID;
								$i++;
							}
						}
						$j++; $marksettingID++;
					}

					$this->marksettingrelation_m->delete_marksettingrelation_by_array(['marktypeID'=> 0]);
					if(count($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				} elseif($marktypeID == 1) {
					$markpercentageArr = [];
					$markpercentages   = $this->input->post('markpercentages');
					if(count($markpercentages)) {
						foreach ($markpercentages as $markpercentage) {
							$markpercentage    = explode('_', $markpercentage);
							$classesID         = isset($markpercentage[1]) ? $markpercentage[1] : 0;
							$markpercentageID  = isset($markpercentage[2]) ? $markpercentage[2] : 0;

							$markpercentageArr[$classesID][$markpercentageID] = $markpercentageID; 
						}
					}

					$this->marksetting_m->delete_marksetting_by_array(['marktypeID'=> 1]);
					$exams = $this->input->post('exams');
					if(count($exams)) {
						$j=0;
						foreach ($exams as $exam) {
							$examArr = explode('_', $exam);
							$examID  = isset($examArr[1]) ? $examArr[1] : 0;
							if(count($markpercentageArr)) {
								foreach ($markpercentageArr as $classesID => $markpercentages) {
									$marksettingArr['examID']     = $examID;
									$marksettingArr['classesID']  = $classesID;
									$marksettingArr['subjectID']  = 0;
									$marksettingArr['marktypeID'] = 1;

									$marksettingID = $this->marksetting_m->insert_marksetting($marksettingArr);
									if(count($markpercentages)) {
										foreach ($markpercentages as $markpercentage) {
											$marksettingRelationArr[$j]['marktypeID']       = 1;
											$marksettingRelationArr[$j]['marksettingID']    = $marksettingID;
											$marksettingRelationArr[$j]['markpercentageID'] = $markpercentage;
											$j++;
										}
									}
								}
							}
						}
					}

					$this->marksettingrelation_m->delete_marksettingrelation_by_array(['marktypeID'=> 1]);
					if(count($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				} elseif($marktypeID == 2) {
					$markpercentageArr = [];
					$markpercentages   = $this->input->post('markpercentages');
					if(count($markpercentages)) {
						foreach ($markpercentages as $markpercentage) {
							$markpercentage    = explode('_', $markpercentage);
							$examID            = isset($markpercentage[1]) ? $markpercentage[1] : 0;
							$markpercentageID  = isset($markpercentage[2]) ? $markpercentage[2] : 0;

							$markpercentageArr[$examID][$markpercentageID] = $markpercentageID; 
						}
					}

					$this->marksetting_m->delete_marksetting_by_array(['marktypeID'=> 2]);
					$j = 0;
					if(count($markpercentageArr)) {
						foreach ($markpercentageArr as $examID => $markpercentages) {
							$marksettingArr['examID']     = $examID;
							$marksettingArr['classesID']  = 0;
							$marksettingArr['subjectID']  = 0;
							$marksettingArr['marktypeID'] = 2;

							$marksettingID = $this->marksetting_m->insert_marksetting($marksettingArr);
							if(count($markpercentages)) {
								foreach ($markpercentages as $markpercentage) {
									$marksettingRelationArr[$j]['marktypeID']       = 2;
									$marksettingRelationArr[$j]['marksettingID']    = $marksettingID;
									$marksettingRelationArr[$j]['markpercentageID'] = $markpercentage;
									$j++;
								}
							}
						}
					}

					$this->marksettingrelation_m->delete_marksettingrelation_by_array(['marktypeID'=> 2]);
					if(count($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				} elseif($marktypeID == 3) {
					$markpercentageArr = [];
					$markpercentages   = $this->input->post('markpercentages');
					if(count($markpercentages)) {
						foreach ($markpercentages as $markpercentage) {
							$markpercentage    = explode('_', $markpercentage);
							$examID            = isset($markpercentage[1]) ? $markpercentage[1] : 0;
							$markpercentageID  = isset($markpercentage[2]) ? $markpercentage[2] : 0;

							$markpercentageArr[$examID][$markpercentageID] = $markpercentageID; 
						}
					}

					$this->marksetting_m->delete_marksetting_by_array(['marktypeID'=> 3]);
					$j = 0;
					if(count($markpercentageArr)) {
						foreach ($markpercentageArr as $examID => $markpercentages) {
							$marksettingArr['examID']     = $examID;
							$marksettingArr['classesID']  = 0;
							$marksettingArr['subjectID']  = 0;
							$marksettingArr['marktypeID'] = 3;

							$marksettingID = $this->marksetting_m->insert_marksetting($marksettingArr);
							if(count($markpercentages)) {
								foreach ($markpercentages as $markpercentage) {
									$marksettingRelationArr[$j]['marktypeID']       = 3;
									$marksettingRelationArr[$j]['marksettingID']    = $marksettingID;
									$marksettingRelationArr[$j]['markpercentageID'] = $markpercentage;
									$j++;
								}
							}
						}
					}

					$this->marksettingrelation_m->delete_marksettingrelation_by_array(['marktypeID'=> 3]);
					if(count($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				} elseif($marktypeID == 4) {
					$markpercentageArr = [];
					$markpercentages   = $this->input->post('markpercentages');
					if(count($markpercentages)) {
						foreach ($markpercentages as $markpercentage) {
							$markpercentage    = explode('_', $markpercentage);
							$classesID         = isset($markpercentage[1]) ? $markpercentage[1] : 0;
							$subjectID         = isset($markpercentage[2]) ? $markpercentage[2] : 0;
							$markpercentageID  = isset($markpercentage[3]) ? $markpercentage[3] : 0;
							$markpercentageArr[$classesID][$subjectID][$markpercentageID] = $markpercentageID; 
						}
					}

					$this->marksetting_m->delete_marksetting_by_array(['marktypeID'=> 4]);
					$i = 0;
					if(count($markpercentageArr)) {
						foreach ($markpercentageArr as $classesID => $subjectmarkpercentageArr) {
							if(count($subjectmarkpercentageArr)) {
								foreach ($subjectmarkpercentageArr as $subjectID=> $markpercentages) {
									$marksettingArr['examID']     = 0;
									$marksettingArr['classesID']  = $classesID;
									$marksettingArr['subjectID']  = $subjectID;
									$marksettingArr['marktypeID'] = 4;
									$marksettingID = $this->marksetting_m->insert_marksetting($marksettingArr);

									if(count($markpercentages)) {
										foreach ($markpercentages as $markpercentage) {
											$marksettingRelationArr[$i]['marktypeID']       = 4;
											$marksettingRelationArr[$i]['marksettingID']    = $marksettingID;
											$marksettingRelationArr[$i]['markpercentageID'] = $markpercentage;
											$i++;
										}
									}

								}
							}
						}
					}
					$this->marksettingrelation_m->delete_marksettingrelation_by_array(['marktypeID'=> 4]);
					if(count($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				} elseif($marktypeID == 5) {
					$markpercentageArr = [];
					$markpercentages   = $this->input->post('markpercentages');
					if(count($markpercentages)) {
						foreach ($markpercentages as $markpercentage) {
							$markpercentage    = explode('_', $markpercentage);
							$classesID         = isset($markpercentage[1]) ? $markpercentage[1] : 0;
							$examID            = isset($markpercentage[2]) ? $markpercentage[2] : 0;
							$markpercentageID  = isset($markpercentage[3]) ? $markpercentage[3] : 0;

							$markpercentageArr[$classesID][$examID][$markpercentageID] = $markpercentageID;
						}
					}

					$this->marksetting_m->delete_marksetting_by_array(['marktypeID'=> 5]);
					$j=0;
					if(count($markpercentageArr)) {
						foreach ($markpercentageArr as $classesID => $exammarkpercentageArr) {
							if(count($exammarkpercentageArr)) {
								foreach($exammarkpercentageArr as $examID=> $markpercentages) {
									$marksettingArr['examID']     = $examID;
									$marksettingArr['classesID']  = $classesID;
									$marksettingArr['subjectID']  = 0;
									$marksettingArr['marktypeID'] = 5;

									$marksettingID = $this->marksetting_m->insert_marksetting($marksettingArr);
									if(count($markpercentages)) {
										foreach ($markpercentages as $markpercentage) {
											$marksettingRelationArr[$j]['marktypeID']       = 5;
											$marksettingRelationArr[$j]['marksettingID']    = $marksettingID;
											$marksettingRelationArr[$j]['markpercentageID'] = $markpercentage;
											$j++;
										}
									}
								}
							}

						}
					}

					$this->marksettingrelation_m->delete_marksettingrelation_by_array(['marktypeID'=> 5]);
					if(count($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				} elseif($marktypeID == 6) {
					$markpercentageArr = [];
					$markpercentages   = $this->input->post('markpercentages');
					if(count($markpercentages)) {
						foreach ($markpercentages as $markpercentage) {
							$markpercentage    = explode('_', $markpercentage);
							$classesID         = isset($markpercentage[1]) ? $markpercentage[1] : 0;
							$examID            = isset($markpercentage[2]) ? $markpercentage[2] : 0;
							$subjectID         = isset($markpercentage[3]) ? $markpercentage[3] : 0;
							$markpercentageID  = isset($markpercentage[4]) ? $markpercentage[4] : 0;

							$markpercentageArr[$classesID][$examID][$subjectID][$markpercentageID] = $markpercentageID;
						}
					}

					$this->marksetting_m->delete_marksetting_by_array(['marktypeID'=> 6]);
					$j=0;
					if(count($markpercentageArr)) {
						foreach ($markpercentageArr as $classesID => $examsubjectmarkpercentageArr) {
							if(count($examsubjectmarkpercentageArr)) {
								foreach($examsubjectmarkpercentageArr as $examID=> $subjectmarkpercentages) {
									if(count($subjectmarkpercentages)) {
										foreach ($subjectmarkpercentages as $subjectID => $markpercentages) {
											$marksettingArr['examID']     = $examID;
											$marksettingArr['classesID']  = $classesID;
											$marksettingArr['subjectID']  = $subjectID;
											$marksettingArr['marktypeID'] = 6;
											
											$marksettingID = $this->marksetting_m->insert_marksetting($marksettingArr);
											if(count($markpercentages)) {
												foreach ($markpercentages as $markpercentage) {
													$marksettingRelationArr[$j]['marktypeID']       = 6;
													$marksettingRelationArr[$j]['marksettingID']    = $marksettingID;
													$marksettingRelationArr[$j]['markpercentageID'] = $markpercentage;
													$j++;
												}
											}
										}
									}

								}
							}
						}
					}

					$this->marksettingrelation_m->delete_marksettingrelation_by_array(['marktypeID'=> 6]);
					if(count($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				}

				$this->session->set_flashdata('success', "Success");
				redirect(base_url('marksetting/index'));
			}
		} else {
			$this->data["subview"]          = "marksetting/index";
			$this->load->view('_layout_main', $this->data);
		}
		
	}

	public function required_markpercentages() {
		if($_POST) {
			$markpercentages = $this->input->post('markpercentages');
			if(count($markpercentages)) {
				return TRUE;
			} else {
				$this->form_validation->set_message("required_markpercentages", "The %s field is required.");
				return FALSE;
			}
		} else {
			$this->form_validation->set_message("required_markpercentages", "The %s field is required.");
			return FALSE;
		}
	} 

	public function required_exams() {
		if($_POST) {
			$exams = $this->input->post('exams');
			if(count($exams)) {
				return TRUE;
			} else {
				$this->form_validation->set_message("required_exams", "The %s field is required.");
				return FALSE;
			}
		} else {
			$this->form_validation->set_message("required_exams", "The %s field is required.");
			return FALSE;
		}
	} 

	public function required_marktype($marktypeID) {
		if($marktypeID == '') {
			$this->form_validation->set_message('required_marktype', 'The %s field is required.');
			return FALSE;
		}
		return TRUE;
	}

	public function check_markpercentage() {
		$marktypeID        = $this->input->post('marktypeID');
		$markpercentages   = $this->input->post('markpercentages');
		$markpercentageArr = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
		if($marktypeID == 0) {
			// Global
			$totalmark     = 0;
			if(count($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$markpercentage    = explode('_', $markpercentage);
					$markpercentageID  = isset($markpercentage[1]) ? $markpercentage[1] : 0;
					$totalmark += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
				}
			}
			if($totalmark != 100) {
				$this->form_validation->set_message('check_markpercentage', 'Select mark percentage of 100 percent.');
				return FALSE;
			}
			return TRUE;
		} elseif($marktypeID == 1) {
			// Class Wise
			$ex_class   = $this->data['siteinfos']->ex_class;
			$classes    = $this->classes_m->general_get_order_by_classes(['classesID !='=> $ex_class]);
			
			$markArr = [];
			if(count($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$exampercentageArr = explode('_', $markpercentage);
					$classesID         = isset($exampercentageArr[1]) ? $exampercentageArr[1] : 0;
					$markpercentageID  = isset($exampercentageArr[2]) ? $exampercentageArr[2] : 0;

					if(!isset($markArr[$classesID])) {
						$markArr[$classesID]  = 0;
					}
					$markArr[$classesID] += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
				}
			}

			$message    = "";
			if(count($classes)) {
				foreach($classes as $class) {
					$totalmark = isset($markArr[$class->classesID]) ? $markArr[$class->classesID] : 0;
					if($totalmark != 100) {
						$message .= "Select mark percentage in 100 percent of class $class->classes .<br/>";
					}
				}
			}
			if(strlen($message) > 0) {
				$this->form_validation->set_message('check_markpercentage', $message);
				return FALSE;
			}
			return TRUE;
		} elseif($marktypeID == 2) {
			//Exam Wise
			$exams      = pluck($this->exam_m->get_exam(), 'exam', 'examID');
			$inputexams = $this->input->post('exams');

			$totalmark = 0;
			if(count($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$exampercentageArr = explode('_', $markpercentage);
					$markpercentageID  = isset($exampercentageArr[2]) ? $exampercentageArr[2] : 0;
					$totalmark   += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
				}
			}

			$message    = "";
			if($totalmark != 100) {
				$message .= "Select mark percentage in 100 percent of all exam .<br/>";
			}

			if(strlen($message) > 0) {
				$this->form_validation->set_message('check_markpercentage', $message);
				return FALSE;
			}
			return TRUE;
		} elseif($marktypeID == 3) {
			//Exam Wise Individual
			$exams      = pluck($this->exam_m->get_exam(), 'exam', 'examID');
			$inputexams = $this->input->post('exams');

			$markArr = [];
			if(count($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$exampercentageArr = explode('_', $markpercentage);
					$examID            = isset($exampercentageArr[1]) ? $exampercentageArr[1] : 0;
					$markpercentageID  = isset($exampercentageArr[2]) ? $exampercentageArr[2] : 0;

					if(!isset($markArr[$examID])) {
						$markArr[$examID]  = 0;
					}
					$markArr[$examID] += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
				}
			}

			$message    = "";
			if(count($inputexams)) {
				foreach($inputexams as $exam) {
					$examArr   = explode('_', $exam);
					$examID    = isset($examArr[1]) ? $examArr[1] : 0;

					$totalmark = isset($markArr[$examID]) ? $markArr[$examID] : 0;
					// if($totalmark != 100) {
					// 	$exam = isset($exams[$examID]) ? $exams[$examID] : '';
					// 	$message .= "Select mark percentage in 100 percent of exam $exam .<br/>";
					// }
				}
			}
			if(strlen($message) > 0) {
				$this->form_validation->set_message('check_markpercentage', $message);
				return FALSE;
			}
			return TRUE;
		} elseif($marktypeID == 4) {
			// Subject Wise
			$subjects   = pluck_multi_array_key($this->subject_m->general_get_subject(), 'obj', 'classesID', 'subjectID');
			$ex_class   = $this->data['siteinfos']->ex_class;
			$classes    = pluck($this->classes_m->general_get_order_by_classes(['classesID !='=> $ex_class]), 'obj', 'classesID');

			$markArr = [];
			if(count($classes)) {
				foreach ($classes as $class) {
					$classsubjects = isset($subjects[$class->classesID]) ? $subjects[$class->classesID] : [];
					if(count($classsubjects)) {
						foreach ($classsubjects as $subject) {
							$markArr[$class->classesID][$subject->subjectID]  = 0;	
						}
					}
				}
			}

			if(count($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$exampercentageArr = explode('_', $markpercentage);
					$classesID         = isset($exampercentageArr[1]) ? $exampercentageArr[1] : 0;
					$subjectID         = isset($exampercentageArr[2]) ? $exampercentageArr[2] : 0;
					$markpercentageID  = isset($exampercentageArr[3]) ? $exampercentageArr[3] : 0;
					$markArr[$classesID][$subjectID] += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
				}
			}

			$message    = "";
			if(count($markArr)) {
				foreach($markArr as $classesID => $subjectmarks) {
					$classsubjects = isset($subjects[$classesID]) ? $subjects[$classesID] : [];
					if($subjectmarks) {
						foreach ($subjectmarks as $subjectID=> $totalmark) {
							if($totalmark != 100) {
								$subject  = isset($classsubjects[$subjectID]) ? $classsubjects[$subjectID]->subject : '';
								$class    = isset($classes[$classesID]) ? $classes[$classesID]->classes : '';
								$message .= "Select mark percentage in 100 percent of subject $subject in class $class .<br/>";
							}
						}
					}
				}
			}
			if(strlen($message) > 0) {
				$this->form_validation->set_message('check_markpercentage', $message);
				return FALSE;
			}
			return TRUE;
		} elseif($marktypeID == 5) {
			// Class Exam Wise
			$ex_class   = $this->data['siteinfos']->ex_class;
			$classes    = pluck($this->classes_m->general_get_order_by_classes(['classesID !='=> $ex_class]), 'obj', 'classesID');
			$exams      = pluck($this->exam_m->get_exam(), 'obj', 'examID');

			$classArr = [];
			if(count($classes)) {
				foreach ($classes as $class) {
					$classArr[$class->classesID]  = (int)$class->classesID;	
				}
			}

			if(count($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$exampercentageArr = explode('_', $markpercentage);
					$classesID         = isset($exampercentageArr[1]) ? $exampercentageArr[1] : 0;
					$examID            = isset($exampercentageArr[2]) ? $exampercentageArr[2] : 0;
					$markpercentageID  = isset($exampercentageArr[3]) ? $exampercentageArr[3] : 0;
					if(!isset($markArr[$classesID][$examID])) {
						$markArr[$classesID][$examID] = 0;
					}
					$markArr[$classesID][$examID] += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
					if(in_array($classesID, $classArr)) {
						unset($classArr[$classesID]);
					}
				}
			}

			$message    = "";
			if(count($classArr)) {
				foreach ($classArr as $classesID) {
					$class    = isset($classes[$classesID]) ? $classes[$classesID]->classes : '';
					$message .= "Select mark percentage of class $class .<br/>";
				}
			}
			if(count($markArr)) {
				foreach($markArr as $classesID => $exammarks) {
					if($exammarks) {
						foreach ($exammarks as $examID=> $totalmark) {
							if($totalmark != 100) {
								$exam     = isset($exams[$examID]) ? $exams[$examID]->exam : '';
								$class    = isset($classes[$classesID]) ? $classes[$classesID]->classes : '';
								$message .= "Select mark percentage in 100 percent of exam $exam in class $class .<br/>";
							}
						}
					}
				}
			}
			if(strlen($message) > 0) {
				$this->form_validation->set_message('check_markpercentage', $message);
				return FALSE;
			}
			return TRUE;
		} elseif($marktypeID == 6) {
			// Class Exam Wise
			$ex_class   = $this->data['siteinfos']->ex_class;
			$classes    = pluck($this->classes_m->general_get_order_by_classes(['classesID !='=> $ex_class]), 'obj', 'classesID');
			$exams      = pluck($this->exam_m->get_exam(), 'exam', 'examID');
			$subjects   = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
			$subjectClass   = pluck_multi_array($this->subject_m->general_get_subject(), 'obj', 'classesID');

			$classArr = [];
			if(count($classes)) {
				foreach ($classes as $class) {
					$classArr[$class->classesID]  = (int)$class->classesID;	
				}
			}

			$inputexams   = $this->input->post('exams');
			$markArr = [];
			if(count($classes)) {
				foreach ($classes as $class) {
					$classsubjects = isset($subjectClass[$class->classesID]) ? $subjectClass[$class->classesID] : [];
					if(count($inputexams)) {
						foreach ($inputexams as $inputexam) {
							$inputexamArr =  explode('_', $inputexam);
							$classesID    = isset($inputexamArr[1]) ? $inputexamArr[1] : 0;
							$examID       = isset($inputexamArr[2]) ? $inputexamArr[2] : 0;

							if($class->classesID == $classesID) {
								if(count($classsubjects)) {
									foreach ($classsubjects as $subject) {
										$markArr[$class->classesID][$examID][$subject->subjectID]  = 0;	
									}
								}
							}
						}
					}
				}
			}


			if(count($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$exampercentageArr = explode('_', $markpercentage);
					$classesID         = isset($exampercentageArr[1]) ? $exampercentageArr[1] : 0;
					$examID            = isset($exampercentageArr[2]) ? $exampercentageArr[2] : 0;
					$subjectID         = isset($exampercentageArr[3]) ? $exampercentageArr[3] : 0;
					$markpercentageID  = isset($exampercentageArr[4]) ? $exampercentageArr[4] : 0;

					if(!isset($markArr[$classesID][$examID][$subjectID])) {
						$markArr[$classesID][$examID][$subjectID]  = 0;
					}
					$markArr[$classesID][$examID][$subjectID] += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);

					if(in_array($classesID, $classArr)) {
						unset($classArr[$classesID]);
					}
				}
			}

			$message    = "";
			if(count($classArr)) {
				foreach ($classArr as $classesID) {
					$class    = isset($classes[$classesID]) ? $classes[$classesID]->classes : '';
					$message .= "Select mark percentage in 100 percent of class $class .<br/>";
				}
			}
			if(count($markArr)) {
				foreach($markArr as $classesID => $examsubjectmarks) {
					if($examsubjectmarks) {
						foreach ($examsubjectmarks as $examID => $subjectmarks) {
							if(count($subjectmarks)) {
								foreach ($subjectmarks as $subjectID => $totalmark) {
									if($totalmark != 100) {
										$class    = isset($classes[$classesID]) ? $classes[$classesID]->classes : '';
										$exam     = isset($exams[$examID]) ? $exams[$examID] : '';
										$subject  = isset($subjects[$subjectID]) ? $subjects[$subjectID] : '';
										$message .= "Select mark percentage in 100 percent of exam $exam in class $class in $subject .<br/>";
									}
								}
							}
						}
					}
				}
			}
			if(strlen($message) > 0) {
				$this->form_validation->set_message('check_markpercentage', $message);
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}


}

