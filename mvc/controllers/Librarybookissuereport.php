<?php

class Librarybookissuereport extends Admin_Controller{
	
	public function __construct() {
		parent::__construct();
		$this->load->model('classes_m');
		$this->load->model('book_m');
		$this->load->model('lmember_m');
		$this->load->model('section_m');
		$this->load->model('studentrelation_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('librarybookissuereport', $language);
	}

	public function rules() {
		$rules = array(
			array(
				'field'=>'classesID',
				'label'=>$this->lang->line('librarybookissuereport_class'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'sectionID',
				'label'=>$this->lang->line('librarybookissuereport_section'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'studentID',
				'label'=>$this->lang->line('librarybookissuereport_student'),
				'rules' => 'trim|xss_clean'
			),
            array(
				'field'=>'lID',
				'label'=>$this->lang->line('librarybookissuereport_libraryID'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'typeID',
				'label'=>$this->lang->line('librarybookissuereport_type'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'fromdate',
				'label'=>$this->lang->line('librarybookissuereport_fromdate'),
				'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
			),
			array(
				'field'=>'todate',
				'label'=>$this->lang->line('librarybookissuereport_todate'),
				'rules' => 'trim|xss_clean|callback_date_valid'
			),
		);
		return $rules;
	}

	public function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field'=>'to',
				'label'=>$this->lang->line('librarybookissuereport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field'=>'subject',
				'label'=>$this->lang->line('librarybookissuereport_subject'),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field'=>'message',
				'label'=>$this->lang->line('librarybookissuereport_message'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'classesID',
				'label'=>$this->lang->line('librarybookissuereport_class'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'sectionID',
				'label'=>$this->lang->line('librarybookissuereport_section'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'studentID',
				'label'=>$this->lang->line('librarybookissuereport_student'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'lID',
				'label'=>$this->lang->line('librarybookissuereport_libraryID'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'typeID',
				'label'=>$this->lang->line('librarybookissuereport_type'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'fromdate',
				'label'=>$this->lang->line('librarybookissuereport_fromdate'),
				'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
			),
			array(
				'field'=>'todate',
				'label'=>$this->lang->line('librarybookissuereport_todate'),
				'rules' => 'trim|xss_clean|callback_date_valid'
			)
		);
		return $rules;
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
		$this->data["subview"] = "report/librarybookissue/LibraryBookIssueReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getSection() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			echo "<option value='0'>", $this->lang->line("librarybookissuereport_please_select"),"</option>";
			$allSection = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			if(count($allSection)) {
				foreach ($allSection as $value) {
					echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
				}
			}
		}
	}

	public function getStudent() {
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$classesID && (int)$sectionID && (int)$schoolyearID) {
			echo "<option value='0'>", $this->lang->line("librarybookissuereport_please_select"),"</option>";
			$students = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID,'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID));
			if(count($students)) {
				foreach($students  as $student) {
					echo "<option value=\"$student->srstudentID\">",$student->srname,"</option>";
				}
			}
		}
	}

	public function getLibrarybookissueReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

        if(permissionChecker('librarybookissuereport')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
			    if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$this->data['classesID'] = $this->input->post('classesID'); 
					$this->data['sectionID'] = $this->input->post('sectionID'); 
					$this->data['studentID'] = $this->input->post('studentID'); 
					$this->data['typeID']    = $this->input->post('typeID');
					$this->data['fromdate']  = !empty($this->input->post('fromdate')) ? strtotime($this->input->post('fromdate')) : '0';
					$this->data['todate']    = !empty($this->input->post('todate')) ? strtotime($this->input->post('todate')) : '0';
					$this->data['lID']       = $this->input->post('lID');
					$_POST['schoolyearID']   = $schoolyearID;

                    $this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->data['books'] = pluck($this->book_m->get_order_by_book(),'obj','bookID');
                    $this->data['getLibrarybookissueReports'] = $this->lmember_m->get_all_librarybooks_for_report($this->input->post());

					$retArray['render'] = $this->load->view('report/librarybookissue/LibraryBookIssueReport', $this->data,true);
					$retArray['status'] = TRUE;
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
			echo json_encode($retArray);
			exit;
		}
	}

	public function pdf() {
		if(permissionChecker('librarybookissuereport')) {
			$classesID = htmlentities(escapeString($this->uri->segment(3)));
			$sectionID = htmlentities(escapeString($this->uri->segment(4)));
			$studentID = htmlentities(escapeString($this->uri->segment(5)));
			$typeID    = htmlentities(escapeString($this->uri->segment(6)));
			$fromdate  = htmlentities(escapeString($this->uri->segment(7)));
			$todate    = htmlentities(escapeString($this->uri->segment(8)));
            $lID       = htmlentities(escapeString($this->uri->segment(9)));

        	if((int)($classesID >= 0) && (int)($sectionID >= 0) && (int)($studentID >= 0) && (int)($typeID >= 0) && (int)($fromdate >= 0) && (int)($todate >= 0)) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$postArray = [];
				$postArray['schoolyearID'] = $schoolyearID;
				$postArray['classesID'] = $classesID;
				$postArray['sectionID'] = $sectionID;
				$postArray['studentID'] = $studentID;
				$postArray['typeID'] = $typeID;

				if($fromdate !='0' && $todate != '0') {
					$postArray['fromdate'] = date('d-m-Y',$fromdate);
					$postArray['todate'] = date('d-m-Y',$todate);
				}

				if($lID != '') {
					$postArray['lID'] = $lID;
				}

				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;
				$this->data['typeID']    = $typeID;
				$this->data['lID']       = $lID;
				$this->data['fromdate']  = $fromdate;
				$this->data['todate']    = $todate;
                $this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
                $this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
                $this->data['books'] = pluck($this->book_m->get_order_by_book(),'obj','bookID');
                $this->data['getLibrarybookissueReports'] = $this->lmember_m->get_all_librarybooks_for_report($postArray);
				$this->reportPDF('librarybookissuereport.css', $this->data, 'report/librarybookissue/LibraryBookIssueReportPDF');
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
		if(permissionChecker('librarybookissuereport')) {
			$this->load->library('phpspreadsheet');

			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$sheet->getDefaultColumnDimension()->setWidth(25);
			$sheet->getDefaultRowDimension()->setRowHeight(25);
			$sheet->getColumnDimension('A')->setWidth(20);
			$sheet->getRowDimension('1')->setRowHeight(25);
			$sheet->getRowDimension('2')->setRowHeight(25);
			
			$data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="librarybookissuereport.xlsx"');
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
		if(permissionChecker('librarybookissuereport')) {
            $classesID = htmlentities(escapeString($this->uri->segment(3)));
            $sectionID = htmlentities(escapeString($this->uri->segment(4)));
            $studentID = htmlentities(escapeString($this->uri->segment(5)));
            $typeID    = htmlentities(escapeString($this->uri->segment(6)));
            $fromdate  = htmlentities(escapeString($this->uri->segment(7)));
            $todate    = htmlentities(escapeString($this->uri->segment(8)));
            $lID       = htmlentities(escapeString($this->uri->segment(9)));

            if((int)($classesID >= 0) && (int)($sectionID >= 0) && (int)($studentID >= 0) && (int)($typeID >= 0) && (int)($fromdate >= 0) && (int)($todate >= 0)) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $postArray = [];
				$postArray['schoolyearID'] = $schoolyearID;
				$postArray['classesID'] = $classesID;
				$postArray['sectionID'] = $sectionID;
				$postArray['studentID'] = $studentID;
				$postArray['typeID'] = $typeID;


				if($fromdate !='0' && $todate != '0') {
					$postArray['fromdate'] = date('d-m-Y',$fromdate);
					$postArray['todate'] = date('d-m-Y',$todate);
				}

				if($lID != '') {
					$postArray['lID'] = $lID;
				}

                $this->data['classesID'] = $classesID;
                $this->data['sectionID'] = $sectionID;
                $this->data['lID']       = $lID;
                $this->data['typeID']    = $typeID;
                $this->data['fromdate']  = $fromdate;
                $this->data['todate']    = $todate;
                $this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
                $this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
                $this->data['books'] = pluck($this->book_m->get_order_by_book(),'obj','bookID');

                $this->data['getLibrarybookissueReports'] = $this->lmember_m->get_all_librarybooks_for_report($postArray);
				return $this->generateXML($this->data);																
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
		if(count($getLibrarybookissueReports)) {
			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$maxColumnCount = 9;
			
            if($typeID == 0) {
                $maxColumnCount = $maxColumnCount+2;
            }

			$headerColumn = "A";
        	for($i= 1; $i < $maxColumnCount; $i++) {
                $headerColumn++;
            }

	        $row = 1;
	        $column = 'A';

	        $f = FALSE;
	        $topMargeCell = FALSE;
	        if($fromdate != '0' && $todate != '0') {
	            $fromdateName  = $this->lang->line('librarybookissuereport_fromdate')." : ";
	            $fromdateName .= date('d M Y',$fromdate);
				
				$sheet->setCellValue('A'.$row, $fromdateName);
	        } elseif($lID != '') {
	            $lID  = $this->lang->line('librarybookissuereport_libraryID')." : ";
	            $lID .= $lID;

				$sheet->setCellValue('A'.$row, $lID);
	        } elseif($typeID !=0) {
	            $typeName = $this->lang->line('librarybookissuereport_type')." : ";
	            if($typeID == 1) {
	                $typeName .= $this->lang->line('librarybookissuereport_issuedate');
	            } elseif($typeID == 2) {
	                $typeName .= $this->lang->line('librarybookissuereport_returndate');
	            } elseif($typeID == 3) {
	                $typeName .= $this->lang->line('librarybookissuereport_duedate');
	            }

				$sheet->setCellValue('A'.$row, $typeName);
	        } else {
	            $className  = $this->lang->line('librarybookissuereport_class')." : ";
	            $className .= isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('librarybookissuereport_all_class');
	            $f = TRUE;
				$sheet->setCellValue('A'.$row, $className);
	        }

			if($fromdate != '0' && $todate != '0') {
	        	$topMargeCell = TRUE;
                $todateName  = $this->lang->line('librarybookissuereport_todate')." : ";
                $todateName .= date('d M Y',$todate);
				
				$sheet->setCellValue($headerColumn.$row, $todateName);
            } else {
                if($f) {
	        		$topMargeCell = TRUE;
                    $sectionName  = $this->lang->line('librarybookissuereport_section')." : ";
                    $sectionName .= isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('librarybookissuereport_all_section');
					$sheet->setCellValue($headerColumn.$row, $sectionName);
                }
            }


	        //Make Header Data Array
	        $headers = array();
	        $headers['id'] = $this->lang->line('slno');
	        $headers['libraryID'] = $this->lang->line('librarybookissuereport_libraryID');

	        if($typeID == 0) {
                $headers['issuedate'] = $this->lang->line('librarybookissuereport_issuedate');
                $headers['duedate'] = $this->lang->line('librarybookissuereport_duedate');
                $headers['returndate'] = $this->lang->line('librarybookissuereport_returndate');
            } elseif($typeID == 1) {
                $headers['issuedate'] = $this->lang->line('librarybookissuereport_issuedate');
            } elseif($typeID == 2) {
                $headers['returndate'] = $this->lang->line('librarybookissuereport_returndate');
            } elseif($typeID == 3) {
                $headers['duedate'] = $this->lang->line('librarybookissuereport_duedate');
            }

	        $headers['name'] = $this->lang->line('librarybookissuereport_name');
	        $headers['registerNO'] = $this->lang->line('librarybookissuereport_registerNO');
	        $headers['subject_code'] = $this->lang->line('librarybookissuereport_subject_code');
	        $headers['book'] = $this->lang->line('librarybookissuereport_book');
	        $headers['serialNO'] = $this->lang->line('librarybookissuereport_serial');
            $headers['status'] = $this->lang->line('librarybookissuereport_status');


	        //Make Xml Header Array
			$column = 'A';    		
    		$row = 2;
	        foreach($headers as $header) {
	        	$sheet->setCellValue($column.$row,$header);
	            $column++;
	        }

	        //Make Body Array
	        $i=0;
	        $libraryBooksReportArray = array();
	        foreach($getLibrarybookissueReports as $libraryBooksReport) {
                $libraryBooksReportArray[$i][] = $i+1;
                $libraryBooksReportArray[$i][] = $libraryBooksReport->lID;
	        	if($typeID == 0) {
	                if(isset($libraryBooksReport->issue_date)) {
	                    $libraryBooksReportArray[$i][] = date('d M Y', strtotime($libraryBooksReport->issue_date));
	                } else {
	                    $libraryBooksReportArray[$i][] = '';
	                }

	                if(isset($libraryBooksReport->due_date)) {
	                    $libraryBooksReportArray[$i][] = date('d M Y', strtotime($libraryBooksReport->due_date));
	                } else {
	                    $libraryBooksReportArray[$i][] = '';
	                }

	                if(isset($libraryBooksReport->return_date)) {
	                    $libraryBooksReportArray[$i][] = date('d M Y', strtotime($libraryBooksReport->return_date));
	                } else {
	                    $libraryBooksReportArray[$i][] = '';
	                }
	            } elseif($typeID == 1) {
	                if(isset($libraryBooksReport->issue_date)) {
	                    $libraryBooksReportArray[$i][] = date('d M Y', strtotime($libraryBooksReport->issue_date));
	                } else {
	                    $libraryBooksReportArray[$i][] = '';
	                }
	            } elseif($typeID == 2) {
	                if(isset($libraryBooksReport->return_date)) {
	                    $libraryBooksReportArray[$i][] = date('d M Y', strtotime($libraryBooksReport->return_date));
	                } else {
	                    $libraryBooksReportArray[$i][] = '';
	                }
	            } elseif ($typeID == 3) {
	                if(isset($libraryBooksReport->due_date)) {
	                    $libraryBooksReportArray[$i][] = date('d M Y', strtotime($libraryBooksReport->due_date));
	                } else {
	                    $libraryBooksReportArray[$i][] = '';
	                }
	            }
                $libraryBooksReportArray[$i][] = $libraryBooksReport->srname;
	        	$libraryBooksReportArray[$i][] = $libraryBooksReport->srregisterNO;
	        	$libraryBooksReportArray[$i][] = isset($books[$libraryBooksReport->bookID]) ? $books[$libraryBooksReport->bookID]->subject_code : '';
	        	$libraryBooksReportArray[$i][] = isset($books[$libraryBooksReport->bookID]) ? $books[$libraryBooksReport->bookID]->book : '';
	        	$libraryBooksReportArray[$i][] = $libraryBooksReport->serial_no;

                if($libraryBooksReport->return_date != '') {
                    $libraryBooksReportArray[$i][] =  $this->lang->line('librarybookissuereport_return');
                } else {
                    $libraryBooksReportArray[$i][] = $this->lang->line('librarybookissuereport_non_return');
                }
	        	$i++;
	        }

            //Make Here Xml Body
	        $row  = 3;
	        if(count($libraryBooksReportArray)) {
	        	foreach($libraryBooksReportArray as $libraryBooksReports) {
	        		$column = "A";
	        		foreach($libraryBooksReports as $value) {
	        			$sheet->setCellValue($column.$row,$value);
	            		$column++;
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
			if($topMargeCell) {
            	$headerColumn = chr(ord($headerColumn) - 1);
			}
            $mergeCellsColumn = $headerColumn.'1';
            $sheet->mergeCells("B1:$mergeCellsColumn");
		} else {
			redirect(base_url('librarybookissuereport'));
		}
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

	public function unique_date() {
		$fromdate = $this->input->post('fromdate');
		$todate   = $this->input->post('todate');

		$startingdate = $this->data['schoolyearsessionobj']->startingdate;
		$endingdate = $this->data['schoolyearsessionobj']->endingdate;

		if($fromdate != '' && $todate == '') {
			$this->form_validation->set_message("unique_date", "The to date field not be empty .");
		    return FALSE;
		} 

		if($fromdate == '' && $todate != '') {
			$this->form_validation->set_message("unique_date", "The from date field not be empty .");
		    return FALSE;
		}

		if($fromdate != '' && $todate != '') {
			if(strtotime($fromdate) > strtotime($todate)) {
				$this->form_validation->set_message("unique_date", "The from date can not be upper than todate .");
		   		return FALSE;
			}
			
			if((strtotime($fromdate) < strtotime($startingdate)) || (strtotime($fromdate) > strtotime($endingdate))) {
				$this->form_validation->set_message("unique_date", "The from date are invalid .");
			    return FALSE;
			}

			if((strtotime($todate) < strtotime($startingdate)) || (strtotime($todate) > strtotime($endingdate))) {
				$this->form_validation->set_message("unique_date", "The to date are invalid .");
			    return FALSE;
			}
			return TRUE;
		}
		
		return TRUE;
	}

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('librarybookissuereport')) {
			if($_POST) {
				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
			    if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$to = $this->input->post('to'); 
					$subject = $this->input->post('subject'); 
					$message = $this->input->post('message'); 

					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$this->data['classesID'] = $this->input->post('classesID'); 
					$this->data['sectionID'] = $this->input->post('sectionID'); 
					$this->data['studentID'] = $this->input->post('studentID'); 
					$this->data['typeID']    = $this->input->post('typeID');
					$this->data['fromdate']  = !empty($this->input->post('fromdate')) ? strtotime($this->input->post('fromdate')) : '0';
					$this->data['todate']    = !empty($this->input->post('todate')) ? strtotime($this->input->post('todate')) : '0';
					$this->data['lID']       = $this->input->post('lID');
					$_POST['schoolyearID']   = $schoolyearID; 

					$this->data['classes']  = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
                    $this->data['books']    = pluck($this->book_m->get_order_by_book(),'obj','bookID');
                    $this->data['getLibrarybookissueReports'] = $this->lmember_m->get_all_librarybooks_for_report($this->input->post());
					$this->reportSendToMail('librarybookissuereport.css', $this->data, 'report/librarybookissue/LibraryBookIssueReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
					$retArray['message'] = 'Success';
					echo json_encode($retArray);
				    exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('librarybookissuereport_permissionmethod');
				echo json_encode($retArray);
		    	exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('librarybookissuereport_permission');
			echo json_encode($retArray);
		    exit;
		}
	}

}

?>