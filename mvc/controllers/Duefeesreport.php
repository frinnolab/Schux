<?php

class Duefeesreport extends Admin_Controller{
	
	public function __construct() {
		parent::__construct();
		$this->load->model('classes_m');
		$this->load->model('feetypes_m');
		$this->load->model('section_m');
		$this->load->model('schoolyear_m');
		$this->load->model('invoice_m');
		$this->load->model('studentrelation_m');
		$this->load->model('feetypes_m');
		$this->load->model('weaverandfine_m');
		$this->load->model('payment_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('duefeesreport', $language);
	}

	public function rules() {
		$rules = array(
			array(
				'field'=>'classesID',
				'label'=>$this->lang->line('duefeesreport_class'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'sectionID',
				'label'=>$this->lang->line('duefeesreport_section'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'studentID',
				'label'=>$this->lang->line('duefeesreport_student'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'feetypeID',
				'label'=>$this->lang->line('duefeesreport_feetype'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'fromdate',
				'label'=>$this->lang->line('duefeesreport_fromdate'),
				'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
			),
			array(
				'field'=>'todate',
				'label'=>$this->lang->line('duefeesreport_todate'),
				'rules' => 'trim|xss_clean|callback_date_valid'
			),
		);
		return $rules;
	}

	public function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field'=>'classesID',
				'label'=>$this->lang->line('duefeesreport_class'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'sectionID',
				'label'=>$this->lang->line('duefeesreport_section'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'studentID',
				'label'=>$this->lang->line('duefeesreport_student'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'feetypeID',
				'label'=>$this->lang->line('duefeesreport_feetype'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'fromdate',
				'label'=>$this->lang->line('duefeesreport_fromdate'),
				'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
			),
			array(
				'field'=>'todate',
				'label'=>$this->lang->line('duefeesreport_todate'),
				'rules' => 'trim|xss_clean|callback_date_valid'
			),
			array(
				'field'=>'to',
				'label'=>$this->lang->line('duefeesreport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field'=>'subject',
				'label'=>$this->lang->line('duefeesreport_subject'),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field'=>'message',
				'label'=>$this->lang->line('duefeesreport_message'),
				'rules' => 'trim|xss_clean'
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

		$this->data['classes']  = $this->classes_m->general_get_classes();
		$this->data['feetypes'] = $this->feetypes_m->get_feetypes();
		$this->data["subview"]  = "report/duefees/DueFeesReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getSection() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			$allSection = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("duefeesreport_please_select"),"</option>";
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
		
		echo "<option value='0'>", $this->lang->line("duefeesreport_please_select"),"</option>";
		if((int)$classesID && (int)$sectionID && (int)$schoolyearID) {
			$students = $this->studentrelation_m->get_order_by_studentrelation(array('srclassesID' => $classesID,'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID));
			if(count($students)) {
				foreach($students  as $student) {
					echo "<option value=\"$student->srstudentID\">",$student->srname,"</option>";
				}
			}
		}
	}

	public function getDueFeesReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		if(permissionChecker('duefeesreport')) {
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
					$_POST['schoolyearID'] = $schoolyearID;
					
					$classesID    = $this->input->post('classesID'); 
					$sectionID    = $this->input->post('sectionID'); 
					$studentID    = $this->input->post('studentID'); 
					$feetypeID    = $this->input->post('feetypeID'); 
					$fromdate     = $this->input->post('fromdate'); 
					$todate       = $this->input->post('todate'); 

					$this->data['classesID']    = $classesID;
					$this->data['sectionID']    = $sectionID;
					$this->data['studentID']    = $studentID;
					$this->data['feetypeID']    = $feetypeID;
					$this->data['fromdate']     = $fromdate;
					$this->data['todate']       = $todate;
					$this->data['schoolyearID'] = $schoolyearID; 

					$studnetArray = [];
					if((int)$classesID) {
						$studnetArray['srclassesID'] = $classesID;
					}
					if((int)$sectionID) {
						$studnetArray['srsectionID'] = $sectionID;
					}
					if((int)$studentID) {
						$studnetArray['srstudentID'] = $studentID;
					}
					$studnetArray['srschoolyearID'] = $schoolyearID;

					$this->data['students'] = pluck($this->studentrelation_m->general_get_order_by_student($studnetArray),'obj','srstudentID');
					$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
					
					$this->data['classes']  = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
					
					$this->data['getFeesReports'] = $this->totalPayment($this->payment_m->get_all_payment_for_report($this->input->post()), $schoolyearID);

					$this->data['getDueFeesReports'] = $this->invoice_m->get_all_duefees_for_report($this->input->post());

					$retArray['render'] = $this->load->view('report/duefees/DueFeesReport', $this->data,true);
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

	private function totalPayment($arrays, $schoolyearID) {
		$weaverandfine = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)),'obj','paymentID');
		$retArray = [];
		if(count($arrays)) {
			foreach ($arrays as $array) {
				if(isset($retArray[$array->invoiceID])) {
					$oldAmount = $retArray[$array->invoiceID];
					$oldAmount += $array->paymentamount;
					$retArray[$array->invoiceID] = (int) $oldAmount;
					if(isset($weaverandfine[$array->paymentID])) {
						$oldAmount = $retArray[$array->invoiceID];
						$oldAmount += $weaverandfine[$array->paymentID]->weaver;
						$retArray[$array->invoiceID] = (int) $oldAmount;
					}
				} else {
					$retArray[$array->invoiceID] = (int) $array->paymentamount;
					if(isset($weaverandfine[$array->paymentID])) {
						$oldAmount = $retArray[$array->invoiceID];
						$oldAmount += $weaverandfine[$array->paymentID]->weaver;
						$retArray[$array->invoiceID] = (int) $oldAmount;
					}
				}
			}
		}

		return $retArray;
	}

	public function pdf() {
		if(permissionChecker('duefeesreport')) { 
			$classesID = htmlentities(escapeString($this->uri->segment(3)));
			$sectionID = htmlentities(escapeString($this->uri->segment(4)));
			$studentID = htmlentities(escapeString($this->uri->segment(5)));
			$feetypeID = htmlentities(escapeString($this->uri->segment(6)));
			$fromdate = htmlentities(escapeString($this->uri->segment(7)));
			$todate = htmlentities(escapeString($this->uri->segment(8)));

			if((int)($classesID >= 0) || (int)($sectionID >= 0) || (int)($studentID >= 0) || (int)($feetypeID >= 0) || ((int)($fromdate >= 0) || (int)($fromdate =='') || ((int)($todate >= 0) || (int)($todate == '')))) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$postArray = [];
				$postArray['schoolyearID'] = $schoolyearID;
				$postArray['classesID'] = $classesID;
				$postArray['sectionID'] = $sectionID;
				$postArray['studentID'] = $studentID;
				$postArray['feetypeID'] = $feetypeID;
				if($fromdate !='' && $todate != '') {
					$postArray['fromdate'] = date('d-m-Y',$fromdate);
					$postArray['todate'] = date('d-m-Y',$todate);
				}

				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;
				$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');


				$studnetArray = [];
				if((int)$classesID) {
					$studnetArray['srclassesID'] = $classesID;
				}
				if((int)$sectionID) {
					$studnetArray['srsectionID'] = $sectionID;
				}
				if((int)$studentID) {
					$studnetArray['srstudentID'] = $studentID;
				}
				$studnetArray['srschoolyearID'] = $schoolyearID;

				$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation($studnetArray),'obj','srstudentID');
				$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
				
				$this->data['getFeesReports'] = $this->totalPayment($this->payment_m->get_all_payment_for_report($postArray), $schoolyearID);
				$this->data['getDueFeesReports'] = $this->invoice_m->get_all_duefees_for_report($postArray);

				$this->reportPDF('duefeesreport.css', $this->data, 'report/duefees/DueFeesReportPDF');
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
		if(permissionChecker('duefeesreport')) {
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
			header('Content-Disposition: attachment;filename="duefeesreport.xlsx"');
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
		if(permissionChecker('duefeesreport')) { 
			$classesID = htmlentities(escapeString($this->uri->segment(3)));
			$sectionID = htmlentities(escapeString($this->uri->segment(4)));
			$studentID = htmlentities(escapeString($this->uri->segment(5)));
			$feetypeID = htmlentities(escapeString($this->uri->segment(6)));
			$fromdate = htmlentities(escapeString($this->uri->segment(7)));
			$todate = htmlentities(escapeString($this->uri->segment(8)));

			if((int)($classesID >= 0) || (int)($sectionID >= 0) || (int)($studentID >= 0) || (int)($feetypeID >= 0) || ((int)($fromdate >= 0) || (int)($fromdate =='') || ((int)($todate >= 0) || (int)($todate == '')))) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$postArray = [];
				$postArray['schoolyearID'] = $schoolyearID;
				$postArray['classesID'] = $classesID;
				$postArray['sectionID'] = $sectionID;
				$postArray['studentID'] = $studentID;
				$postArray['feetypeID'] = $feetypeID;

				if($fromdate !='' && $todate != '') {
					$postArray['fromdate'] = date('d-m-Y',$fromdate);
					$postArray['todate'] = date('d-m-Y',$todate);
				}

				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;
				$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');

				$studnetArray = [];
				if((int)$classesID) {
					$studnetArray['srclassesID'] = $classesID;
				}
				if((int)$sectionID) {
					$studnetArray['srsectionID'] = $sectionID;
				}
				if((int)$studentID) {
					$studnetArray['srstudentID'] = $studentID;
				}
				$studnetArray['srschoolyearID'] = $schoolyearID;

				$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation($studnetArray),'obj','srstudentID');
				$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
				$this->data['getFeesReports'] = $this->totalPayment($this->payment_m->get_all_payment_for_report($postArray), $schoolyearID);
				$this->data['getDueFeesReports'] = $this->invoice_m->get_all_duefees_for_report($postArray);

				if(count($this->data['getDueFeesReports'])) {
					return $this->generateXML($this->data);
				} else {
					redirect('duefeesreport');
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
		if(count($getDueFeesReports)) {
			// $maxColumnCount = 8;
			if($classesID > 0 ) {
				$maxColumnCount = 9;
			}

			if($sectionID > 0 ) {
				$maxColumnCount = 9;
			}

			if($classesID == 0 && $sectionID == 0 ) {
				$maxColumnCount = 10;
			}

			if($classesID > 0 && $sectionID > 0) {
				$maxColumnCount = 8;
			}

			$headerColumn = "A";
        	for($i= 1; $i < $maxColumnCount; $i++) {
	        	$headerColumn++;
	        }

	        $row = 1;
	        $column = 'A';

	        //Here Will Be Header Info
	        if($classesID >= 0) {
				$className  = $this->lang->line('duefeesreport_class');
				$className .= ' : ';
				$className .= isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('duefeesreport_all_class');
				
				$sectionName  = $this->lang->line('duefeesreport_section'); 				
				$sectionName .= " : ";
				$sectionName .= isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('duefeesreport_all_section');
			
				$sheet->setCellValue('A'.$row, $className);
				$sheet->setCellValue($headerColumn.$row, $sectionName);
			} else {
				$sheet->getRowDimension('1')->setVisible(false);
			}

	        //Make Header Data Array
	        $headers = array();
	        $headers['invoice_id'] = $this->lang->line('slno');
	        $headers['invoice_date'] = $this->lang->line('duefeesreport_invoice_date');
	        $headers['name'] = $this->lang->line('duefeesreport_name');
	        $headers['registration_number'] = $this->lang->line('duefeesreport_registerNO');

	        if($classesID == 0) { 
	        	$headers['class'] = $this->lang->line('duefeesreport_class');
	        }

	        if($sectionID == 0) { 
	        	$headers['section'] = $this->lang->line('duefeesreport_section');
	        }

	        $headers['roll'] = $this->lang->line('duefeesreport_roll');
	        $headers['feetype'] = $this->lang->line('duefeesreport_feetype');
	        $headers['discount'] = $this->lang->line('duefeesreport_discount');
	        $headers['due'] = $this->lang->line('duefeesreport_due');

	        //Make Xml Header Array
			$column = 'A';    		
    		$row = 2;
	        foreach($headers as $header) {
	        	$sheet->setCellValue($column.$row,$header);
	            $column++;
	        }

	        //Make Body Array
	        $getDueFeesReportArrays = array();
	        $i=0;
	        $j = 0;
	        $totalDue = 0;
	        foreach($getDueFeesReports as $getDueFeesReport) {
	        	if($sectionID > 0) { if(isset($students[$getDueFeesReport->studentID]) && $students[$getDueFeesReport->studentID]->srsectionID == $sectionID) { 
	        		$j++;
		        	$getDueFeesReportArrays[$i][] = $j;
		        	$getDueFeesReportArrays[$i][] = date('d M Y',strtotime($getDueFeesReport->create_date));
		        	$getDueFeesReportArrays[$i][] = isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srname : '';
		        	$getDueFeesReportArrays[$i][] = isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srregisterNO : '';

		        	if($classesID == 0) { 
	                    if(isset($students[$getDueFeesReport->studentID])) {
	                        $stclassID = $students[$getDueFeesReport->studentID]->srclassesID;
		        			$getDueFeesReportArrays[$i][] = isset($classes[$stclassID]) ? $classes[$stclassID] : '';
	                    } 
	                }

	                if($sectionID == 0) { 
	                    if(isset($students[$getDueFeesReport->studentID])) {
	                        $stsectionID = $students[$getDueFeesReport->studentID]->srsectionID;
		        			$getDueFeesReportArrays[$i][] = isset($sections[$stsectionID]) ? $sections[$stsectionID] : '';
	                    } 
	                }

		        	$getDueFeesReportArrays[$i][] = isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srroll : '';
		        	
		        	if(isset($feetypes[$getDueFeesReport->feetypeID])) {
	        			$getDueFeesReportArrays[$i][] = $feetypes[$getDueFeesReport->feetypeID];
	                }
		        	
		        	$getDueFeesReportArrays[$i][] = number_format($getDueFeesReport->discount, 2);


		            $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
		            if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
		                $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
		        		$getDueFeesReportArrays[$i][] = number_format($due,2);
		                $totalDue += $due;
		            } else {
		                $due = ($getDueFeesReport->amount - $discount);
		        		$getDueFeesReportArrays[$i][] = number_format($due,2);
		                $totalDue += $due;
		            }
		        	$i++;

	        	} } else {
	        		$j++;
	        		$getDueFeesReportArrays[$i][] = $j;
		        	$getDueFeesReportArrays[$i][] = date('d M Y',strtotime($getDueFeesReport->create_date));
		        	$getDueFeesReportArrays[$i][] = isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srname : '';
		        	$getDueFeesReportArrays[$i][] = isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srregisterNO : '';

		        	if($classesID == 0) { 
	                    if(isset($students[$getDueFeesReport->studentID])) {
	                        $stclassID = $students[$getDueFeesReport->studentID]->srclassesID;
		        			$getDueFeesReportArrays[$i][] = isset($classes[$stclassID]) ? $classes[$stclassID] : '';
	                    } 
	                }

	                if($sectionID == 0) { 
	                    if(isset($students[$getDueFeesReport->studentID])) {
	                        $stsectionID = $students[$getDueFeesReport->studentID]->srsectionID;
		        			$getDueFeesReportArrays[$i][] = isset($sections[$stsectionID]) ? $sections[$stsectionID] : '';
	                    } 
	                }

		        	$getDueFeesReportArrays[$i][] = isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srroll : '';
		        	
		        	if(isset($feetypes[$getDueFeesReport->feetypeID])) {
	        			$getDueFeesReportArrays[$i][] = $feetypes[$getDueFeesReport->feetypeID];
	                }
		        	
		        	$getDueFeesReportArrays[$i][] = number_format($getDueFeesReport->discount, 2);


		            $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
		            if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
		                $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
		        		$getDueFeesReportArrays[$i][] = number_format($due,2);
		                $totalDue += $due;
		            } else {
		                $due = ($getDueFeesReport->amount - $discount);
		        		$getDueFeesReportArrays[$i][] = number_format($due,2);
		                $totalDue += $due;
		            }
		        	$i++;

	        	}

	        }


	        //Make Here Xml Body
	        $row  = 3;
	        if(count($getDueFeesReportArrays)) {
	        	foreach($getDueFeesReportArrays as $getDueFeesReportArray) {
	        		$column = "A";
	        		foreach($getDueFeesReportArray as $value) {
	        			$sheet->setCellValue($column.$row,$value);
	            		$column++;
	        		}
	        		$row++;
	        	}
	        }

	        if($totalDue > 0) {
	        	$total_value = $this->lang->line('duefeesreport_grand_total');
	        	$total_value .= (!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.')' : '');
	        	$sheet->setCellValue('A'.$row,$total_value);
	        	$sheet->setCellValue($headerColumn.$row,number_format($totalDue,2));
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

			$decrementrow = $row-1;
			$sheet->getStyle('A3:'.$headerColumn.$decrementrow)->applyFromArray($styleArray);


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

			$sheet->getStyle('A'.$row.':'.$headerColumn.$row)->applyFromArray($styleArray);

	
			$headerColumn = chr(ord($headerColumn) - 1);  //Decreament Header Section Column
			$mergeCellsColumn = $headerColumn.'1';
			$sheet->mergeCells("B1:$mergeCellsColumn");

			$startMergeCellsColumn = 'A'.$row;
			$endMergeCellsColumn = $headerColumn.$row;
			$sheet->mergeCells("$startMergeCellsColumn:$endMergeCellsColumn");
		} else {
			redirect('duefeesreport');
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

		if(permissionChecker('duefeesreport')) { 
			if($_POST) {
				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
			    if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$_POST['schoolyearID'] = $schoolyearID;
					
					$classesID    = $this->input->post('classesID'); 
					$sectionID    = $this->input->post('sectionID'); 
					$studentID    = $this->input->post('studentID'); 
					$feetypeID    = $this->input->post('feetypeID'); 
					$fromdate     = $this->input->post('fromdate'); 
					$todate       = $this->input->post('todate'); 

					$this->data['classesID']    = $classesID;
					$this->data['sectionID']    = $sectionID;
					$this->data['studentID']    = $studentID;
					$this->data['feetypeID']    = $feetypeID;
					$this->data['fromdate']     = $fromdate;
					$this->data['todate']       = $todate;
					$this->data['schoolyearID'] = $schoolyearID; 

					$studnetArray = [];
					if((int)$classesID) {
						$studnetArray['srclassesID'] = $classesID;
					}
					if((int)$sectionID) {
						$studnetArray['srsectionID'] = $sectionID;
					}
					if((int)$studentID) {
						$studnetArray['srstudentID'] = $studentID;
					}
					$studnetArray['srschoolyearID'] = $schoolyearID;

					$to = $this->input->post('to'); 
					$subject = $this->input->post('subject'); 
					$message = $this->input->post('message'); 

					$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation($studnetArray),'obj','srstudentID');
					$this->data['classes']  = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
					$this->data['getFeesReports'] = $this->totalPayment($this->payment_m->get_all_payment_for_report($this->input->post()), $schoolyearID);
					$this->data['getDueFeesReports'] = $this->invoice_m->get_all_duefees_for_report($this->input->post());
					$this->reportSendToMail('duefeesreport.css', $this->data, 'report/duefees/DueFeesReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
					$retArray['message'] = 'Success';
					echo json_encode($retArray);
			    	exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('duefeesreport_permissionmethod');
				echo json_encode($retArray);
		    	exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('duefeesreport_permission');
			echo json_encode($retArray);
	    	exit;
		}
	}

}

?>