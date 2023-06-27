<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examschedulereport extends Admin_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('section_m');
		$this->load->model("classes_m");
		$this->load->model("exam_m");
		$this->load->model("subject_m");
		$this->load->model("examschedule_m");
		$this->load->model("marksetting_m");
		
		$language = $this->session->userdata('lang');
		$this->lang->load('examschedulereport', $language);
	}

	public function rules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("examschedulereport_exam"),
				'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("examschedulereport_class"),
				'rules' => 'trim|required|xss_clean|greater_than_equal_to[0]'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("examschedulereport_section"),
				'rules' => 'trim|required|xss_clean|greater_than_equal_to[0]'
			)
		);

		return $rules;
	}

	public function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("examschedulereport_to"),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("examschedulereport_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("examschedulereport_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'examID',
				'label' => $this->lang->line("examschedulereport_exam"),
				'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("examschedulereport_class"),
				'rules' => 'trim|required|xss_clean|greater_than_equal_to[0]'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("examschedulereport_section"),
				'rules' => 'trim|required|xss_clean|greater_than_equal_to[0]'
			)
		);

		return $rules;
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css'
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js'
			)
		);

		$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data["subview"] = "report/examschedule/ExamscheduleReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getExamscheduleReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('examschedulereport')) {
			if($_POST) {
				$examID     = $this->input->post('examID');
				$classesID  = $this->input->post('classesID');
				$sectionID  = $this->input->post('sectionID');
				$rules = $this->rules();

				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
				    $retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$queryArray = [];

					$this->data['examID'] = $examID; 
					$this->data['classesID'] = $classesID; 
					$this->data['sectionID'] = $sectionID; 

					$this->getQuery($queryArray, $this->input->post());

					$examScheduleReports = $this->examschedule_m->get_order_by_examschedule($queryArray);
					$examreports = $this->examScheduleReport($examScheduleReports);

					$this->data['examreports'] = $examreports;
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['section'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
					$this->data['subjects'] = pluck($this->subject_m->general_get_subject(),'subject','subjectID');
					$this->data['exams'] = pluck($this->exam_m->get_exam(),'exam','examID');
					$this->data['exam_dates'] = $this->generate_date($examScheduleReports);
					$this->data['allSections'] = $this->allSection();
					$this->data['examschedule_reports'] = $examScheduleReports;
				}
				$this->data['examID'] = $examID;
				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;

				$retArray['render'] = $this->load->view('report/examschedule/ExamscheduleReport',$this->data,true);
				$retArray['status'] = true;
				echo json_encode($retArray);
			    exit;
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
		if(permissionChecker('examschedulereport')) {
			$examID     = htmlentities(escapeString($this->uri->segment(3)));
			$classesID  = htmlentities(escapeString($this->uri->segment(4)));
			$sectionID  = htmlentities(escapeString($this->uri->segment(5)));

			if(((int)$examID && $examID > 0) && ((int)$classesID || $classesID >= 0) && ((int) $sectionID || $sectionID >= 0)) {

				$exam    = $this->exam_m->get_single_exam(array('examID'=>$examID));
				$classes = $this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
				$section = $this->section_m->general_get_single_section(array('sectionID'=>$sectionID));

				if(count($exam)) {
					if(count($classes) || $classesID == 0) {
						if(count($section) || $sectionID == 0) {
							$postsArray = array(
								'examID'    => $examID,
								'classesID' => $classesID,
								'sectionID' => $sectionID,
							);

							$queryArray = [];

							$this->getQuery($queryArray, $postsArray);

							$examScheduleReports = $this->examschedule_m->get_order_by_examschedule($queryArray);
							$examreports = $this->examScheduleReport($examScheduleReports);

							$this->data['examreports'] = $examreports;

							$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
							$this->data['section'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
							$this->data['subjects'] = pluck($this->subject_m->general_get_subject(),'subject','subjectID');
							$this->data['exams'] = pluck($this->exam_m->get_exam(),'exam','examID');
							$this->data['exam_dates'] = $this->generate_date($examScheduleReports);
							$this->data['allSections'] = $this->allSection();
							$this->data['examschedule_reports'] = $examScheduleReports;
							
							$this->data['examID'] = $examID;
							$this->data['classesID'] = $classesID;
							$this->data['sectionID'] = $sectionID;

							$this->reportPDF('examschedulereport.css', $this->data, 'report/examschedule/ExamscheduleReportPDF','view','a4','l');
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

	public function xlsx() {
		if(permissionChecker('examschedulereport')) {
			$this->load->library('phpspreadsheet');
			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$sheet->getDefaultColumnDimension()->setWidth(30);
			$sheet->getDefaultRowDimension()->setRowHeight(80);

			$sheet->getRowDimension('1')->setRowHeight(25);
			$sheet->getRowDimension('2')->setRowHeight(25);

			$sheet->getPageSetup()->setFitToWidth(1);
			$sheet->getPageSetup()->setFitToHeight(0);

			$sheet->getPageMargins()->setTop(1);
			$sheet->getPageMargins()->setRight(0.75);
			$sheet->getPageMargins()->setLeft(0.75);
			$sheet->getPageMargins()->setBottom(1);

			
			$data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="examschedulereport.xlsx"');
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

	private function xmlData() {
		$examID     = htmlentities(escapeString($this->uri->segment(3)));
		$classesID  = htmlentities(escapeString($this->uri->segment(4)));
		$sectionID  = htmlentities(escapeString($this->uri->segment(5)));

		if(((int) $examID || $examID > 0) && ((int) $classesID || $classesID >= 0) && ((int) $sectionID || $sectionID >= 0)) {

			$exam = $this->exam_m->get_single_exam(array('examID'=>$examID));
			$classes = $this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
			$section = $this->section_m->general_get_single_section(array('sectionID'=>$sectionID));

			if(count($exam)) {
				if(count($classes) || $classesID == 0) {
					if(count($section) || $sectionID == 0) {
						$postsArray = array(
							'examID'    => $examID,
							'classesID' => $classesID,
							'sectionID' => $sectionID,
						);

						$queryArray = [];

						$this->getQuery($queryArray, $postsArray);

						$examScheduleReports = $this->examschedule_m->get_order_by_examschedule($queryArray);
						$examreports = $this->examScheduleReport($examScheduleReports);

						$this->data['examreports'] = $examreports;

						$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
						$this->data['section'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
						$this->data['subjects'] = pluck($this->subject_m->general_get_subject(),'subject','subjectID');
						$this->data['exams'] = pluck($this->exam_m->get_exam(),'exam','examID');
						$this->data['exam_dates'] = $this->generate_date($examScheduleReports);
						$this->data['allSections'] = $this->allSection();
						$this->data['examschedule_reports'] = $examScheduleReports;
						
						$this->data['examID'] = $examID;
						$this->data['classesID'] = $classesID;
						$this->data['sectionID'] = $sectionID;

						$this->generateXML($this->data);
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

	private function generateXML($data) {
		extract($data);

		$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

		if(count($examschedule_reports)) {
			$row = 1;
			$headerColumn = 'A';

			$countColumn = count($exam_dates);

			if($classesID == 0 && $sectionID == 0) {
				$countColumn = $countColumn + 2;
				$sheet->getColumnDimension('A')->setWidth(15);
				$sheet->getColumnDimension('B')->setWidth(10);

			} elseif($classesID != 0 && $sectionID == 0) {
				$countColumn = $countColumn + 1;
				$sheet->getColumnDimension('A')->setWidth(15);
			} elseif($classesID != 0 && $sectionID != 0) {
				$countColumn = $countColumn;
			}



			for($i =1; $i < $countColumn; $i++) {
				$headerColumn++;
			}


			if($classesID >= 0 && $sectionID >=0) { 
            	
            	$className = $this->lang->line('examschedulereport_class'); 
            	$className .= ' : '; 
            	$className .= isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('examschedulereport_all_classes');

            	$sheet->setCellValue('A'.$row, $className);                


				$sectionName = $this->lang->line('examschedulereport_section');                            
				$sectionName .= " - ";                             
                if($sectionID == 0) {
                    $sectionName .= $this->lang->line('examschedulereport_all_section');
                } else {
                    $sectionName .= isset($section[$sectionID]) ? $section[$sectionID] : '';
                }

                if($countColumn == 1) {
                	$classSection  = $className;
                	$classSection .= "\n";
                	$classSection .= $sectionName;
                	$sectionName   = $classSection;
                	$sheet->getRowDimension('1')->setRowHeight(50);
                } 

                $sheet->setCellValue($headerColumn.$row, $sectionName);
            } 


			$row = '2';
			$column = 'A';

			if($classesID == 0 && $sectionID == 0) {
				$sheet->setCellValue("A".$row, $this->lang->line('examschedulereport_class'));
				$sheet->setCellValue("B".$row, $this->lang->line('examschedulereport_section'));
				$column = "C";
			} elseif($classesID != 0 && $sectionID == 0) {
				$sheet->setCellValue("A".$row, $this->lang->line('examschedulereport_section'));
				$column = "B";

			}
			if(count($exam_dates)) {
                foreach($exam_dates as $exam_date) {
					$sheet->setCellValue($column.$row, date('d M Y',strtotime($exam_date)));
					$column++;
                }
            }


            $classArray = [];
            $allClassStatus = TRUE;
            $allSectionStatus = TRUE;

            $classStatus = FALSE;
            $sectionStatus = FALSE;

            if($classesID != 0) {
                $allClassStatus = FALSE;
            }

            if($sectionID != 0) {
                $allSectionStatus = FALSE;
            }

            $row = 3;

            if(isset($classes)) {
	            foreach($classes as $classesKey => $classesValue) {
	                if($allClassStatus == FALSE && $classesID == $classesKey) {
	                    $classStatus = TRUE;
	                } elseif($allClassStatus) {
	                    $classStatus = TRUE;
	                }

	                if($classStatus) {
	                    if(isset($allSections[$classesKey])) {
	                        foreach($allSections[$classesKey] as $sectionKey => $section) {

	                            if($allSectionStatus == FALSE && $sectionID == $sectionKey) { 
	                                $sectionStatus = TRUE;
	                            } elseif($allSectionStatus) {
	                                $sectionStatus = TRUE;
	                            }

	                            if($sectionStatus) {
	                                if($classesID == 0 && $sectionID == 0) {
	                                    if(!in_array($classesKey, $classArray)) {
	                                        $rowspanforclass = 1;
	                                        
	                                        if(isset($allSections[$classesKey])) {
	                                            $rowspanforclass = count($allSections[$classesKey]);
	                                        }

	                                        $classValue = isset($classesValue) ? $classesValue : '';

	                                        $mergeCountRow = $row+$rowspanforclass-1;

	                                        	$startMerge = 'A'.$row;
	                                       		$endMerge = 'A'.$mergeCountRow;

	                                        	$sheet->mergeCells("$startMerge:$endMerge");

	                                        $sheet->setCellValue("A".$row, $classValue);

	                                        $classArray[] = $classesKey;
	                                    }
	                                    $sheet->setCellValue("B".$row, $section);
	                                    $column = 'C';
	                                } elseif ($classesID != 0 && $sectionID == 0) {
	                                    $sheet->setCellValue("A".$row, $section);
	                                    $column = 'B';
	                                } elseif($classesID != 0 && $sectionID != 0){
	                                	$column = 'A';
	                                	$row = 3;
	                                }

	                                if(isset($exam_dates)) {
                                        foreach($exam_dates as $exam_date) {
                                            if(isset($examreports[$classesKey][$sectionKey][$exam_date])) {
                                                $examscheduledatas = $examreports[$classesKey][$sectionKey][$exam_date];
                                                $subject_count = count($examscheduledatas);
                                                $j=1;
                                                foreach($examscheduledatas as $examscheduledata) {
                                                	$examscheduleData= $this->lang->line('examschedulereport_subject');
                                                	$examscheduleData.= " : ";
                                                	$examscheduleData.= $subjects[$examscheduledata->subjectID];
                                                	$examscheduleData.= "\n";
                                                	$examscheduleData.= $this->lang->line('examschedulereport_exam_time');
                                                	$examscheduleData.= " : ";
                                                	$examscheduleData.= $examscheduledata->examfrom;
                                                	$examscheduleData.= " - ";
                                                	$examscheduleData.= $examscheduledata->examto;
                                                	$examscheduleData.= "\n";
                                                	$examscheduleData.= $this->lang->line('examschedulereport_room');
                                                	$examscheduleData.= " : ";
                                                	$examscheduleData.= $examscheduledata->room;
                                                	$examscheduleData.= "\n";
                                                	
                                                	if($j < $subject_count) {
                                                		$examscheduleData.= "\n";
                                                		$examscheduleData.= "\n";
                                                        $j++;
                                                    }

                                                	$sheet->setCellValue($column.$row, $examscheduleData);
                                                }
                                            } else {
                                                $sheet->setCellValue($column.$row, 'N/A');
                                            }
                                            $column++; 
                                        }
                                    }
	                            }

	                            $sectionStatus = FALSE;

	                            $row++;

	                        } 
	                    }   
	                }
	                $classStatus = FALSE; 
	            } 
	        }

	        $styleArray = [
			    'font' => [
			        'bold' => true,
			    ],
			    'alignment' =>[
			    	'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
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

			if($classesID != 0 && $sectionID != 0) {
				$row = $row-2;
			}

			$sheet->getStyle('A3:'.$headerColumn.$row)->applyFromArray($styleArray);

			if($countColumn != 1 && $countColumn != 2) {
				$headerColumn = chr(ord($headerColumn) - 1);  //Decreament Header Section Column
				$mergeCellsColumn = $headerColumn.'1';
				$sheet->mergeCells("B1:$mergeCellsColumn");
			}

		} else {
			redirect('examschedulereport');
		}
	}

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		$retArray['message']= '';
		if(permissionChecker('examschedulereport')) {
			if($_POST) {
				$to        = $this->input->post('to');
				$subject   = $this->input->post('subject');
				$message   = $this->input->post('message');
				$examID    = $this->input->post('examID');
				$classesID = $this->input->post('classesID');
				$sectionID = $this->input->post('sectionID');

				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					if(((int) $examID || $examID > 0) && ((int) $classesID || $classesID >= 0) && ((int) $sectionID || $sectionID >= 0)) {

						$exam = $this->exam_m->get_exam($examID);
						$classes = $this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
						$section = $this->section_m->general_get_single_section(array('sectionID'=>$sectionID));

						if(count($exam)) {
							if(count($classes) || $classesID == 0) {
								if(count($section) || $sectionID == 0) {
									$postsArray = array(
										'examID'    => $examID,
										'classesID' => $classesID,
										'sectionID' => $sectionID,
									);

									$queryArray = [];

									$this->getQuery($queryArray, $postsArray);

									$examScheduleReports = $this->examschedule_m->get_order_by_examschedule($queryArray);
									$examreports = $this->examScheduleReport($examScheduleReports);

									$this->data['examreports'] = $examreports;

									$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
									$this->data['section'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
									$this->data['subjects'] = pluck($this->subject_m->general_get_subject(),'subject','subjectID');
									$this->data['exams'] = pluck($this->exam_m->get_exam(),'exam','examID');
									$this->data['exam_dates'] = $this->generate_date($examScheduleReports);
									$this->data['allSections'] = $this->allSection();
									$this->data['examschedule_reports'] = $examScheduleReports;
									
									$this->data['examID'] = $examID;
									$this->data['classesID'] = $classesID;
									$this->data['sectionID'] = $sectionID;

									$this->reportSendToMail('examschedulereport.css', $this->data, 'report/examschedule/ExamscheduleReportPDF', $to, $subject, $message);
									$retArray['status'] = TRUE;
									echo json_encode($retArray);
				    				exit;
								} else {
									$retArray['message'] = $this->lang->line('examschedulereport_section_not_found');
									echo json_encode($retArray);
				    				exit;
								}
							} else {
								$retArray['message'] = $this->lang->line('examschedulereport_class_not_found');
								echo json_encode($retArray);
			    				exit;
							}
						} else {
							$retArray['message'] = $this->lang->line('examschedulereport_examid_not_found');
							echo json_encode($retArray);
		    				exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('examschedulereport_data_not_found');
						echo json_encode($retArray);
	    				exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('examschedulereport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('examschedulereport_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function getExam() {
		$classesID = $this->input->post('classesID');
		echo "<option value='0'>", $this->lang->line("examschedulereport_please_select"),"</option>";
		if((int)$classesID) {
			$exams    = pluck($this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID), 'obj', 'examID');
			if(count($exams)) {
				foreach ($exams as $exam) {
					echo "<option value=".$exam->examID.">".$exam->exam."</option>";
				}
			}
		}
	}

	public function getSection() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			$sections = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("examschedulereport_please_select"),"</option>";
			if(count($sections)) {
				foreach ($sections as $section) {
					echo "<option value=\"$section->sectionID\">",$section->section,"</option>";
				}
			}
		}
	}

	public function unique_data($data) {
		if($data == '0') {
			$this->form_validation->set_message("unique_data" , 'The %s is required.');
			return FALSE;
		}
		return TRUE;
	}


	private function allSection() {
		$sections = $this->section_m->general_get_section();
		$sectionArray = [];
		if(count($sections)) {
			foreach ($sections as $section) {
				$sectionArray[$section->classesID][$section->sectionID] = $section->section;
			}
		}
		return $sectionArray;
	}

	private function generate_date($datas){
		$flagDatas = array();
		if(count($datas)) {
			foreach($datas as $data) {
				if(!in_array($data->edate, $flagDatas)){
					$flagDatas[] = $data->edate;
				}
			}
		}
		asort($flagDatas);
		return $flagDatas;
	}

	private function getQuery(&$queryArray,$posts){
		$examID = $posts['examID'];
		$classesID = $posts['classesID'];
		$sectionID = $posts['sectionID'];
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$queryArray['schoolyearID'] = $schoolyearID;

		if((int)$examID > 0) { 
			$queryArray['examID'] = $examID;
		}
		if((int)$classesID > 0) {
			$queryArray['classesID'] = $classesID;
		}
		if((int)$sectionID > 0) {
			$queryArray['sectionID'] = $sectionID;
		}
	}

	private function examScheduleReport($examScheduleReports) {
		$examreportArray = [];
		if(count($examScheduleReports)) {
			foreach ($examScheduleReports as $examScheduleReport) {
				$examreportArray[$examScheduleReport->classesID][$examScheduleReport->sectionID][$examScheduleReport->edate][] = $examScheduleReport;
			}
		}
		return $examreportArray;
	}
}
