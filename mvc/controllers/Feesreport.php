<?php

class Feesreport extends Admin_Controller{
	
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
		$this->lang->load('feesreport', $language);
	}

	public function rules() {
		$rules = array(
			array(
				'field'=>'classesID',
				'label'=>$this->lang->line('feesreport_class'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'sectionID',
				'label'=>$this->lang->line('feesreport_section'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'studentID',
				'label'=>$this->lang->line('feesreport_student'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'feetypeID',
				'label'=>$this->lang->line('feesreport_feetype'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'fromdate',
				'label'=>$this->lang->line('feesreport_fromdate'),
				'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
			),
			array(
				'field'=>'todate',
				'label'=>$this->lang->line('feesreport_todate'),
				'rules' => 'trim|xss_clean|callback_date_valid'
			),
		);
		return $rules;
	}

	public function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field'=>'classesID',
				'label'=>$this->lang->line('feesreport_class'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'sectionID',
				'label'=>$this->lang->line('feesreport_section'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'studentID',
				'label'=>$this->lang->line('feesreport_student'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'feetypeID',
				'label'=>$this->lang->line('feesreport_feetype'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'fromdate',
				'label'=>$this->lang->line('feesreport_fromdate'),
				'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
			),
			array(
				'field'=>'todate',
				'label'=>$this->lang->line('feesreport_todate'),
				'rules' => 'trim|xss_clean|callback_date_valid'
			),
			array(
				'field'=>'to',
				'label'=>$this->lang->line('feesreport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field'=>'subject',
				'label'=>$this->lang->line('feesreport_subject'),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field'=>'message',
				'label'=>$this->lang->line('feesreport_message'),
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

		$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data['feetypes'] = $this->feetypes_m->get_feetypes();
		$this->data["subview"] = "report/fees/FeesReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getSection() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			echo "<option value='0'>", $this->lang->line("feesreport_please_select"),"</option>";
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
			echo "<option value='0'>", $this->lang->line("feesreport_please_select"),"</option>";
			$students = $this->studentrelation_m->get_order_by_studentrelation(array('srclassesID' => $classesID,'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID));
			if(count($students)) {
				foreach($students  as $student) {
					echo "<option value=\"$student->srstudentID\">",$student->srname,"</option>";
				}
			}
		}
	}

	public function getFeesReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		if(permissionChecker('feesreport')) {
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


					$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation($studnetArray),'obj','srstudentID');
					$this->data['weaverandfine'] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)),'obj','paymentID');
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->data['invoices'] = pluck($this->invoice_m->get_order_by_invoice(array('schoolyearID'=>$schoolyearID)),'feetypeID','invoiceID');
					$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
					$this->data['getFeesReports'] = $this->payment_m->get_all_payment_for_report($this->input->post());
					
					$retArray['render'] = $this->load->view('report/fees/FeesReport', $this->data,true);
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
			echo json_encode($retArray);
			exit;
		}
	}

	public function pdf() {
		if(permissionChecker('feesreport')) { 
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

				$this->data['classesID']    = $classesID;
				$this->data['sectionID']    = $sectionID;
				$this->data['studentID']    = $studentID;
				$this->data['feetypeID']    = $feetypeID;
				$this->data['fromdate']     = $fromdate;
				$this->data['todate']       = $todate;
				$this->data['schoolyearID'] = $schoolyearID; 

				$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation($studnetArray),'obj','srstudentID');
				$this->data['weaverandfine'] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)),'obj','paymentID');

				$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');


				$this->data['invoices'] = pluck($this->invoice_m->get_order_by_invoice(array('schoolyearID'=>$schoolyearID)),'feetypeID','invoiceID');
				$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');

				$this->data['getFeesReports'] = $this->payment_m->get_all_payment_for_report($postArray);
				$this->reportPDF('feesreport.css', $this->data, 'report/fees/FeesReportPDF');
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
		if(permissionChecker('feesreport')) {
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
			header('Content-Disposition: attachment;filename="feesreport.xlsx"');
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
		if(permissionChecker('feesreport')) { 
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
				$this->data['weaverandfine'] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)),'obj','paymentID');

				$this->data['invoices'] = pluck($this->invoice_m->get_order_by_invoice(array('schoolyearID'=>$schoolyearID)),'feetypeID','invoiceID');
				$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');

				$this->data['getFeesReports'] = $this->payment_m->get_all_payment_for_report($postArray);
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

		$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
		if(count($getFeesReports)) {

			$maxColumnCount = 9;
			if($classesID == 0) {
				$maxColumnCount = 10;
			}

			if($sectionID == 0) {
				$maxColumnCount = 10;
			}

			if($classesID == 0 && $sectionID == 0) {
				$maxColumnCount = 11;
			}


			$headerColumn = "A";
        	for($i= 1; $i < $maxColumnCount; $i++) {
	        	$headerColumn++;
	        }

	        $row = 1;
	        $column = 'A';

	        //Here Will Be Header Info

	        if($classesID >= 0) {
				$className  = $this->lang->line('feesreport_class');
				$className .= ' : ';
				$className .= isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('feesreport_all_class');
				
				$sectionName  = $this->lang->line('feesreport_section'); 				
				$sectionName .= " : ";
				$sectionName .= isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('feesreport_all_section');
			
				$sheet->setCellValue('A'.$row, $className);
				$sheet->setCellValue($headerColumn.$row, $sectionName);
			} else {
				$sheet->getRowDimension('1')->setVisible(false);
			}

	        //Make Header Data Array
	        $headers = array();
	        $headers['payment_id'] = $this->lang->line('slno');
	        $headers['payment_date'] = $this->lang->line('feesreport_payment_date');
	        $headers['name'] = $this->lang->line('feesreport_name');
	        $headers['registration_number'] = $this->lang->line('feesreport_registerNO');

	        if($classesID == 0) { 
	        	$headers['class'] = $this->lang->line('feesreport_class');
	        }

	        if($sectionID == 0) { 
	        	$headers['section'] = $this->lang->line('feesreport_section');
	        }

	        $headers['roll'] = $this->lang->line('feesreport_roll');
	        $headers['feetype'] = $this->lang->line('feesreport_feetype');
	        $headers['paid'] = $this->lang->line('feesreport_paid');
	        $headers['weaver'] = $this->lang->line('feesreport_weaver');
	        $headers['fine'] = $this->lang->line('feesreport_fine');

	        //Make Xml Header Array
			$column = 'A';    		
    		$row = 2;
	        foreach($headers as $header) {
	        	$sheet->setCellValue($column.$row,$header);
	            $column++;
	        }

	        //Make Body Array
	        $i=0;
	        $j= 0;
	        $totalPaid = 0;
            $totalWeaver = 0;
            $totalFine = 0;
	        $getFeesReportArrays = array();
	        foreach($getFeesReports as $getFeesReport) {
	        	if(isset($weaverandfine[$getFeesReport->paymentID]) && (($weaverandfine[$getFeesReport->paymentID]->fine !='') || ($weaverandfine[$getFeesReport->paymentID]->weaver !='')) || $getFeesReport->paymentamount != '') {
		        	$j++;
		        	$getFeesReportArrays[$i][] = $j;
		        	$getFeesReportArrays[$i][] = date('d M Y',strtotime($getFeesReport->paymentdate));
		        	$getFeesReportArrays[$i][] = isset($students[$getFeesReport->studentID]) ? $students[$getFeesReport->studentID]->srname : '';
		        	$getFeesReportArrays[$i][] = isset($students[$getFeesReport->studentID]) ? $students[$getFeesReport->studentID]->srregisterNO : '';

		        	if($classesID == 0) { 
	                    if(isset($students[$getFeesReport->studentID])) {
	                        $stclassID = $students[$getFeesReport->studentID]->srclassesID;
		        			$getFeesReportArrays[$i][] = isset($classes[$stclassID]) ? $classes[$stclassID] : '';
	                    } 
	                }
					
					if($sectionID == 0) { 
	                    if(isset($students[$getFeesReport->studentID])) {
	                        $stsectionID = $students[$getFeesReport->studentID]->srsectionID;
		        			$getFeesReportArrays[$i][] = isset($sections[$stsectionID]) ? $sections[$stsectionID] : '';
	                    } 
	                }

		        	$getFeesReportArrays[$i][] = isset($students[$getFeesReport->studentID]) ? $students[$getFeesReport->studentID]->srroll : '';
		        	
		        	if(isset($invoices[$getFeesReport->invoiceID])) {
	                    $feetypeID = $invoices[$getFeesReport->invoiceID];
	                    if(isset($feetypes[$feetypeID])) {
		        			$getFeesReportArrays[$i][] = $feetypes[$feetypeID];
	                    }
	                }
		        	
		        	$getFeesReportArrays[$i][] = number_format($getFeesReport->paymentamount,2);
	                $totalPaid += $getFeesReport->paymentamount;

	                if(isset($weaverandfine[$getFeesReport->paymentID])) {
	                    $getFeesReportArrays[$i][] = number_format($weaverandfine[$getFeesReport->paymentID]->weaver,2);
	                    $totalWeaver += $weaverandfine[$getFeesReport->paymentID]->weaver; 
	                } else {
	                    $getFeesReportArrays[$i][] = number_format(0,2);
	                }

	                if(isset($weaverandfine[$getFeesReport->paymentID])) {
	                    $getFeesReportArrays[$i][] =  number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
	                    $totalFine += number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
	                } else {
	                    $getFeesReportArrays[$i][] = number_format(0,2);
	                }

		        	$i++;
		        }
	        }

	        $getFeesReportArrays[$i][] = '';
	        $getFeesReportArrays[$i][] = '';
	        $getFeesReportArrays[$i][] = '';
	        if($classesID == 0) { 
	        	$getFeesReportArrays[$i][] = '';
	        }
	        if($sectionID == 0) { 
	        	$getFeesReportArrays[$i][] = '';
	        }
	        $getFeesReportArrays[$i][] = '';
	        $getFeesReportArrays[$i][] = '';
	        $getFeesReportArrays[$i][] = '';
	        $getFeesReportArrays[$i][] = number_format($totalPaid,2);
	        $getFeesReportArrays[$i][] = number_format($totalWeaver,2);
	        $getFeesReportArrays[$i][] = number_format($totalFine,2);



	        //Make Here Xml Body
	        $row  = 3;
	        if(count($getFeesReportArrays)) {
	        	foreach($getFeesReportArrays as $getFeesReportArray) {
	        		$column = "A";
	        		foreach($getFeesReportArray as $value) {
	        			$sheet->setCellValue($column.$row,$value);
	            		$column++;
	        		}
	        		$row++;
	        	}
	        }

	        $startGrandMerge = 'A'.($row-1);
	        if($classesID == 0 && $sectionID == 0) {
	        	$endGrandMerge = 'H'.($row-1);
	        } elseif(($classesID == 0 && $sectionID == 1) || ($classesID == 1 && $sectionID == 0)) {
	        	$endGrandMerge = 'G'.($row-1);
	        } else {
	        	$endGrandMerge = 'F'.($row-1);
	        }

	        $sheet->mergeCells("$startGrandMerge:$endGrandMerge");
	        $grandValue  = $this->lang->line('feesreport_grand_total');
	        $grandValue .= " (".(!empty($siteinfos->currency_code) ? $siteinfos->currency_code : '').")";
	        $sheet->setCellValue($startGrandMerge,$grandValue);

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
			$row = $row-2;
			$sheet->getStyle('A3:'.$headerColumn.$row)->applyFromArray($styleArray);

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
			$row = $row+1;
			$sheet->getStyle('A'.$row.':'.$headerColumn.$row)->applyFromArray($styleArray);
	
			$headerColumn = chr(ord($headerColumn) - 1);  //Decreament Header Section Column
			$mergeCellsColumn = $headerColumn.'1';
			$sheet->mergeCells("B1:$mergeCellsColumn");

		} else {
			redirect(base_url('feesreport'));
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
		if(permissionChecker('feesreport')) { 
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
					$this->data['weaverandfine'] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)),'obj','paymentID');
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');

					$this->data['invoices'] = pluck($this->invoice_m->get_order_by_invoice(array('schoolyearID'=>$schoolyearID)),'feetypeID','invoiceID');
					$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
					
					$this->data['getFeesReports'] = $this->payment_m->get_all_payment_for_report($this->input->post());
					$this->reportSendToMail('feesreport.css', $this->data, 'report/fees/FeesReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
					$retArray['message'] = 'Success';
					echo json_encode($retArray);
				    exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('feesreport_permissionmethod');
				echo json_encode($retArray);
		    	exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('feesreport_permission');
			echo json_encode($retArray);
		    exit;
		}
	}

}

?>