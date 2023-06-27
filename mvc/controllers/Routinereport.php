<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Routinereport extends Admin_Controller {
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
		$this->load->model("routine_m");

		$weekends = [];
		if($this->data['siteinfos']->weekends != '') {
			$weekends = explode(',', $this->data['siteinfos']->weekends);
		}
		$this->data['weekends'] = $weekends;

		$language = $this->session->userdata('lang');
		$this->lang->load('routinereport', $language);
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
		$this->data['teachers'] = $this->teacher_m->general_get_teacher();
		$this->data['classes'] = $this->classes_m->general_get_classes();
		
		$this->data["subview"] = "report/routine/RoutineReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getRoutineReport () {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		if(permissionChecker('routinereport')) {
			$routinefor = $this->input->post('routinefor');
			$teacherID  = $this->input->post('teacherID');
			$classesID  = $this->input->post('classesID');
			$sectionID  = $this->input->post('sectionID');
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if($_POST){
				$rules = $this->rules($routinefor);
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->data['routinefor'] = $routinefor;
					$this->data['teacherID'] = $teacherID;
					$this->data['get_classes'] = $classesID;
					$this->data['get_section'] = $sectionID;

					if($routinefor == 'student') {
						if((int)$classesID && (int)$sectionID) {
							$this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine(array('classesID'=>$classesID,'sectionID'=>$sectionID,'schoolyearID'=> $schoolyearID)), 'obj', 'day');
							$this->data['subjects'] = pluck($this->subject_m->general_get_order_by_subject(array('classesID'=>$classesID)), 'subject', 'subjectID');
							$this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
							$this->data['sections'] = pluck($this->section_m->general_get_order_by_section(array('classesID'=>$classesID)), 'section', 'sectionID');
							$this->data['teachers'] = pluck($this->teacher_m->general_get_teacher(), 'name', 'teacherID');

							$retArray['render'] =  $this->load->view('report/routine/RoutineReport', $this->data, true);
							$retArray['status'] = TRUE;
						} else {
							$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
							$retArray['status'] = TRUE;
						}
					} elseif($routinefor == 'teacher') {
						if ((int)$teacherID) {
							$this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine(array('teacherID'=>$teacherID,'schoolyearID'=> $schoolyearID)), 'obj', 'day');
							$this->data['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
							$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
							$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
							$this->data['teacher'] = $this->teacher_m->general_get_single_teacher(array('teacherID'=>$teacherID));
							$retArray['render'] =  $this->load->view('report/routine/RoutineReport', $this->data, true);
							$retArray['status'] = TRUE;
						} else {
							$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
							$retArray['status'] = TRUE;
						}
					}
					echo json_encode($retArray);
				    exit;
				}
			} else {
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
			$retArray['status'] = TRUE;
		}
	}

	public function pdf() {
		if(permissionChecker('routinereport')) {
			$routinefor = htmlentities(escapeString($this->uri->segment(3)));
			$teacherID  = htmlentities(escapeString($this->uri->segment(4)));
			$classesID  = htmlentities(escapeString($this->uri->segment(5)));
			$sectionID  = htmlentities(escapeString($this->uri->segment(6)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');

			$this->data['routinefor'] = $routinefor;
			$this->data['teacherID'] = $teacherID;
			$this->data['get_classes'] = $classesID;
			$this->data['get_section'] = $sectionID;
			if((string)$routinefor && ((int)$teacherID >= 0) && ((int)$classesID >= 0) && ((int)$sectionID >= 0)) {
				if($routinefor == 'student') {
					if((int)$classesID && (int)$sectionID) {
						$this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine(array('classesID'=>$classesID,'sectionID'=>$sectionID,'schoolyearID'=> $schoolyearID)), 'obj', 'day');
						$this->data['subjects'] = pluck($this->subject_m->general_get_order_by_subject(array('classesID'=>$classesID)), 'subject', 'subjectID');
						$this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
						$this->data['sections'] = pluck($this->section_m->general_get_order_by_section(array('classesID'=>$classesID)), 'section', 'sectionID');
						$this->data['teachers'] = pluck($this->teacher_m->general_get_teacher(), 'name', 'teacherID');
						$this->reportPDF('routinereport.css', $this->data, 'report/routine/RoutineReportPDF','view','a4','l');
					} else {
						$this->data["subview"] = "error";
						$this->load->view('_layout_main', $this->data);
					}
				} elseif($routinefor == 'teacher') {
					if ((int)$teacherID) {
						$this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine(array('teacherID'=>$teacherID,'schoolyearID'=> $schoolyearID)), 'obj', 'day');
						$this->data['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
						$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
						$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
						$this->data['teacher'] = $this->teacher_m->general_get_single_teacher(array('teacherID'=>$teacherID));
						$this->reportPDF('routinereport.css', $this->data, 'report/routine/RoutineReportPDF','view','a4','l');
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
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function xlsx() {
		if(permissionChecker('routinereport')) {
			$this->load->library('phpspreadsheet');

			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$sheet->getDefaultColumnDimension()->setWidth(30);
			$sheet->getDefaultRowDimension()->setRowHeight(80);
			$sheet->getColumnDimension('A')->setWidth(20);
			$sheet->getRowDimension('1')->setRowHeight(25);
			$sheet->getRowDimension('2')->setRowHeight(25);
			
			$data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="routinereport.xlsx"');
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
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	private function xmlData() {
		$routinefor = htmlentities(escapeString($this->uri->segment(3)));
		$teacherID  = htmlentities(escapeString($this->uri->segment(4)));
		$classesID  = htmlentities(escapeString($this->uri->segment(5)));
		$sectionID  = htmlentities(escapeString($this->uri->segment(6)));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		$this->data['routinefor'] = $routinefor;
		$this->data['teacherID'] = $teacherID;
		$this->data['get_classes'] = $classesID;
		$this->data['get_section'] = $sectionID;

		if((string)$routinefor && ((int)$teacherID >= 0) && ((int)$classesID >= 0) && ((int)$sectionID >= 0)) {
			if($routinefor == 'student') {
				if((int)$classesID && (int)$sectionID) {
					$this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine(array('classesID'=>$classesID,'sectionID'=>$sectionID,'schoolyearID'=> $schoolyearID)), 'obj', 'day');
					$this->data['subjects'] = pluck($this->subject_m->general_get_order_by_subject(array('classesID'=>$classesID)), 'subject', 'subjectID');
					$this->data['classes'] =$this->classes_m->general_get_single_classes(array('classesID'=>$classesID));
					$this->data['sections'] = pluck($this->section_m->general_get_order_by_section(array('classesID'=>$classesID)), 'section', 'sectionID');
					$this->data['teachers'] = pluck($this->teacher_m->general_get_teacher(), 'name', 'teacherID');
					
					return $this->generateXML($this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} elseif($routinefor == 'teacher') {
				if ((int)$teacherID) {
					$this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine(array('teacherID'=>$teacherID,'schoolyearID'=> $schoolyearID)), 'obj', 'day');
					$this->data['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
					$this->data['teacher'] = $this->teacher_m->general_get_single_teacher(array('teacherID'=>$teacherID));
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
	}

	private function generateXML($data) {
		extract($data);

        $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
		if(count($routines)) {
	        $days = [
                0 => $this->lang->line('sunday'),
                1 => $this->lang->line('monday'),
                2 => $this->lang->line('tuesday'),
                3 => $this->lang->line('wednesday'),
                4 => $this->lang->line('thursday'),
                5 => $this->lang->line('friday'),
                6 => $this->lang->line('saturday'),
            ];

			$maxClass = 0; 
	        foreach ($routines as $routineKey => $routine) { 
	            if(count($routine) > $maxClass) {
	                $maxClass = count($routine);
	            }
	        }


	        $row = 1;
	        if(count($routines)) {
	        	$headerColumn = "A";
	        	for($i= 1; $i <= $maxClass; $i++) {
		        	$headerColumn++;
		        }
	            if($routinefor == 'student') { 
	            	$className   = $this->lang->line('routinereport_class');
	            	$className  .=" : ";
	            	$className  .=isset($classes->classes) ? $classes->classes : '';

	            	$sectionName  = $this->lang->line('routinereport_section');
	            	$sectionName .= " : ";
	            	$sectionName .= isset($sections[$get_section]) ? $sections[$get_section] : '';

	            	$sheet->setCellValue("A".$row,$className);
	            	$sheet->setCellValue($headerColumn.$row,$sectionName);
	            } elseif($routinefor == 'teacher') { 
	            	$teacherName  = $this->lang->line('routinereport_name');
	            	$teacherName .= " : ";
	            	$teacherName .= $teacher->name;
	            	
	            	$teacherDesignation  = $this->lang->line('routinereport_designation');
	            	$teacherDesignation .= " : ";
	            	$teacherDesignation .= $teacher->designation;

	            	$sheet->setCellValue("A".$row,$teacherName);
	            	$sheet->setCellValue($headerColumn.$row,$teacherDesignation);                     
	        	}
	      	}



	        $row      = 2;
	        $column   = 'A';
	        $sheet->setCellValue($column.$row,$this->lang->line('routinereport_day'));
	        for($i=1; $i <= $maxClass; $i++) {
	        	$column++;
	            $sheet->setCellValue($column.$row,addOrdinalNumberSuffix($i)." ".$this->lang->line('routinereport_period'));
	        }

	        $row = 3;
	        foreach ($days as $dayKey=> $day) {
	        	if(!in_array($dayKey, $weekends) && isset($routines[$dayKey])) {
		            $sheet->setCellValue('A'.$row, $day);
		            $i = 0;
		            	
	            	$column = "B";
	            	foreach ($routines[$dayKey] as $routine) {
	            		$i++;
	            		$routinevalue  = $routine->start_time;
	            		$routinevalue .= "-";
	            		$routinevalue .= $routine->end_time;
	            		$routinevalue .= "\n";
	            		$routinevalue .= $this->lang->line('routinereport_subject');
	            		$routinevalue .= " : ";
	            		$routinevalue .= isset($subjects[$routine->subjectID]) ? $subjects[$routine->subjectID] : '';
	            		$routinevalue .= "\n";
	            		if($routinefor == 'student') {
	            			$routinevalue .= $this->lang->line('routinereport_teacher');
	            			$routinevalue .= " : ";
	            			$routinevalue .= isset($teachers[$routine->teacherID]) ? $teachers[$routine->teacherID] : '';
	            			$routinevalue .= "\n";
	            		} elseif($routinefor == 'teacher') {
	            			$routinevalue .= $this->lang->line('routinereport_class');
	            			$routinevalue .= " : ";
	            			$routinevalue .= isset($classes[$routine->classesID]) ? $classes[$routine->classesID] : '';
	            			$routinevalue .= "\n";

	            			$routinevalue .= $this->lang->line('routinereport_section');
	            			$routinevalue .= " : ";
	            			$routinevalue .= isset($sections[$routine->sectionID]) ? $sections[$routine->sectionID] : '';
	            			$routinevalue .= "\n";
	            		}
	            		
	            		$routinevalue .= $this->lang->line('routinereport_room');
	            		$routinevalue .= $routine->room;

	            		$sheet->setCellValue($column.$row,$routinevalue);
	            		$column++;
	            	}
					$j = ($maxClass - $i);
					if($i < $maxClass) {
						for($i = 1; $i <= $j; $i++) { 
							$sheet->setCellValue($column.$row,'N/A');
							$column++;
						}
					}

	            	$row++;
	        	}
	        }

	        $styleArray = [
			    'font' => [
			        'bold' => true,
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
			        'bold' => false,
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

			if($maxClass > 1) {
				$headerColumn = chr(ord($headerColumn) - 1);  //Decreament Header Section Column
				$mergeCellsColumn = $headerColumn.'1';
				$sheet->mergeCells("B1:$mergeCellsColumn");
			}

		} else {
			redirect('routinereport');
		}
	}

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';

		if(permissionChecker('routinereport')) {
			if($_POST) {
				$to = $this->input->post('to');
				$subject = $this->input->post('subject');
				$message = $this->input->post('message');
				$routinefor = $this->input->post('routinefor');
				$teacherID  = $this->input->post('teacherID');
				$classesID  = $this->input->post('classesID');
				$sectionID  = $this->input->post('sectionID');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');

				$rules = $this->send_pdf_to_mail_rules($routinefor);
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->data['routinefor'] = $routinefor;
					$this->data['teacherID'] = $teacherID;
					$this->data['get_classes'] = $classesID;
					$this->data['get_section'] = $sectionID;

					if((string)$routinefor && ((int)$teacherID >= 0) && ((int)$classesID >= 0) && ((int)$sectionID >= 0)) {
						if($routinefor == 'student') {
							if((int)$classesID && (int)$sectionID) {

								$classess = $this->classes_m->get_classes($classesID);
								$sections = $this->section_m->get_section($sectionID);

								$this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine(array('classesID'=>$classesID,'sectionID'=>$sectionID,'schoolyearID'=> $schoolyearID)), 'obj', 'day');
								$this->data['subjects'] = pluck($this->subject_m->general_get_order_by_subject(array('classesID'=>$classesID)), 'subject', 'subjectID');
								$this->data['classes'] = $this->classes_m->general_get_single_classes(array('classesID'=> $classesID));
								$this->data['sections'] = pluck($this->section_m->general_get_order_by_section(array('classesID'=>$classesID)), 'section', 'sectionID');
								$this->data['teachers'] = pluck($this->teacher_m->general_get_teacher(), 'name', 'teacherID');
								
								$this->reportSendToMail('routinereport.css', $this->data, 'report/routine/RoutineReportPDF', $to, $subject, $message);
								$retArray['message'] = "Message";
								$retArray['status'] = TRUE;
								echo json_encode($retArray);
			    				exit;
							} else {
								$retArray['message'] = $this->lang->line('routinereport_data_not_found');
								echo json_encode($retArray);
								exit;
							}
						} elseif($routinefor == 'teacher') {
							if ((int)$teacherID) {
								$this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine(array('teacherID'=>$teacherID,'schoolyearID'=> $schoolyearID)), 'obj', 'day');
								$this->data['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
								$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
								$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
								$this->data['teacher'] = $this->teacher_m->general_get_single_teacher(array('teacherID'=>$teacherID));
								$this->reportSendToMail('routinereport.css', $this->data, 'report/routine/RoutineReportPDF', $to, $subject, $message);
								$retArray['status'] = TRUE;
								echo json_encode($retArray);
			    				exit;
							} else {
								$retArray['message'] = $this->lang->line('routinereport_data_not_found');
								echo json_encode($retArray);
								exit;
							}
						}
					} else {
						$retArray['message'] = $this->lang->line('routinereport_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('routinereport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('routinereport_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function getSection() {
		$id = $this->input->post('id');
		if((int)$id) {
			$sections = $this->section_m->general_get_order_by_section(array('classesID' => $id));
			echo "<option value='0'>", $this->lang->line("routinereport_please_select"),"</option>";
			if(count($sections)) {
				foreach ($sections as $section) {
					echo "<option value=\"$section->sectionID\">",$section->section,"</option>";
				}
			}
		}
	}

	protected function rules($status = 'student') {
		if($status === "0") {
			$rules = array(
				array(
					'field' => 'routinefor',
					'label' => $this->lang->line("routinereport_routine_for"),
					'rules' => 'trim|required|xss_clean|callback_unique_data'
				)
			);
		} elseif($status == 'teacher') {
			$rules = array(
				array(
					'field' => 'routinefor',
					'label' => $this->lang->line("routinereport_routine_for"),
					'rules' => 'trim|required|xss_clean|callback_unique_data'
				),
				array(
					'field' => 'teacherID',
					'label' => $this->lang->line("routinereport_teacher"),
					'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
				),
			);
		} elseif($status == 'student') {
			$rules = array(
				array(
					'field' => 'routinefor',
					'label' => $this->lang->line("routinereport_routine_for"),
					'rules' => 'trim|required|xss_clean|callback_unique_data'
				),
				array(
					'field' => 'classesID',
					'label' => $this->lang->line("routinereport_class"),
					'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
				),
				array(
					'field' => 'sectionID',
					'label' => $this->lang->line("routinereport_section"),
					'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
				),
			);
		}

		return $rules;
	} 

	protected function send_pdf_to_mail_rules($status = 'student') {
		if($status === "0") {
			$rules = array(
				array(
					'field' => 'routinefor',
					'label' => $this->lang->line("routinereport_routine_for"),
					'rules' => 'trim|required|xss_clean|callback_unique_data'
				)
			);
		} elseif($status == 'teacher') {
			$rules = array(
				array(
					'field' => 'routinefor',
					'label' => $this->lang->line("routinereport_routine_for"),
					'rules' => 'trim|required|xss_clean|callback_unique_data'
				),
				array(
					'field' => 'teacherID',
					'label' => $this->lang->line("routinereport_teacher"),
					'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
				),
			);
		} elseif($status == 'student') {
			$rules = array(
				array(
					'field' => 'routinefor',
					'label' => $this->lang->line("routinereport_routine_for"),
					'rules' => 'trim|required|xss_clean|callback_unique_data'
				),
				array(
					'field' => 'classesID',
					'label' => $this->lang->line("routinereport_class"),
					'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
				),
				array(
					'field' => 'sectionID',
					'label' => $this->lang->line("routinereport_section"),
					'rules' => 'trim|required|xss_clean|numeric|callback_unique_data'
				),
			);
		}

		$rules[] = array(
			'field' => 'to',
			'label' => $this->lang->line("routinereport_to"),
			'rules' => 'trim|required|xss_clean|valid_email'
		);
		$rules[] = array(
			'field' => 'subject',
			'label' => $this->lang->line("routinereport_subject"),
			'rules' => 'trim|required|xss_clean'
		);
		$rules[] = array(
			'field' => 'message',
			'label' => $this->lang->line("routinereport_message"),
			'rules' => 'trim|xss_clean'
		);

		return $rules;
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
