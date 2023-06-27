<?php

class Balancefeesreport extends Admin_Controller{	
	public function __construct() {
		parent::__construct();
		$this->load->model('classes_m');
		$this->load->model('feetypes_m');
		$this->load->model('section_m');
		$this->load->model('student_m');
		$this->load->model('schoolyear_m');
		$this->load->model('invoice_m');
		$this->load->model('studentrelation_m');
		$this->load->model('weaverandfine_m');
		$this->load->model('parents_m');
		$this->load->model('payment_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('balancefeesreport', $language);
	}

	public function rules() {
		$rules = array(
			array(
				'field'=>'classesID',
				'label'=>$this->lang->line('balancefeesreport_class'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'sectionID',
				'label'=>$this->lang->line('balancefeesreport_section'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'studentID',
				'label'=>$this->lang->line('balancefeesreport_student'),
				'rules' => 'trim|xss_clean'
			)
		);
		return $rules;
	}

	public function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field'=>'classesID',
				'label'=>$this->lang->line('balancefeesreport_class'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'sectionID',
				'label'=>$this->lang->line('balancefeesreport_section'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'studentID',
				'label'=>$this->lang->line('balancefeesreport_student'),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field'=>'to',
				'label'=>$this->lang->line('balancefeesreport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field'=>'subject',
				'label'=>$this->lang->line('balancefeesreport_subject'),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field'=>'message',
				'label'=>$this->lang->line('balancefeesreport_message'),
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

		$this->data['date'] = date("d-m-Y");
		$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data["subview"] = "report/balancefees/BalanceFeesReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getSection() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			$allSection = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("balancefeesreport_please_select"),"</option>";
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
		
		echo "<option value='0'>", $this->lang->line("balancefeesreport_please_select"),"</option>";
		if((int)$classesID && (int)$sectionID && (int)$schoolyearID) {
			$students = $this->studentrelation_m->get_order_by_studentrelation(array('srclassesID' => $classesID,'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID));
			if(count($students)) {
				foreach($students  as $student) {
					echo "<option value=\"$student->srstudentID\">",$student->srname,"</option>";
				}
			}
		}
	}

	public function getBalanceFeesReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		if(permissionChecker('balancefeesreport')) {
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

					$this->data['classesID']    = $classesID;
					$this->data['sectionID']    = $sectionID;
					$this->data['studentID']    = $studentID;
					$this->data['schoolyearID'] = $schoolyearID; 

					$studentArray = [];
					if((int)$classesID) {
						$studentArray['srclassesID'] = $classesID;
					}
					if((int)$sectionID) {
						$studentArray['srsectionID'] = $sectionID;
					}
					if((int)$studentID) {
						$studentArray['srstudentID'] = $studentID;
					}
					$studentArray['srschoolyearID'] = $schoolyearID;

					$this->db->order_by('srclassesID','ASC');
					$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation($studentArray),'obj','srstudentID');
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
		
					$this->data['totalAmountAndDiscount'] = $this->totalAmountAndDiscount($this->invoice_m->get_all_balancefees_for_report($this->input->post()));
					$this->data['totalPayment'] = $this->totalPaymentAndWeaver($this->payment_m->get_order_by_payment(array('schoolyearID' => $schoolyearID)));
					$this->data['totalweavar'] = $this->totalWeaver($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)));

					$retArray['render'] = $this->load->view('report/balancefees/BalanceFeesReport', $this->data, true);
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

	private function totalAmountAndDiscount($arrays) {
		$totalAmountAndDiscount = [];
		
		if(count($arrays)) {
			foreach($arrays as $key => $array) {
				// if(isset($totalAmountAndDiscount[$array->studentID]['amount'])) {
				// 	$totalAmountAndDiscount[$array->studentID]['amount'] += $array->amount;
				// } else {
				// 	$totalAmountAndDiscount[$array->studentID]['amount'] = $array->amount;
				// }
				
				if ($array->currency == "USD") {
					if(isset($totalAmountAndDiscount[$array->studentID]['amountUSD'])) {
						$totalAmountAndDiscount[$array->studentID]['amountUSD'] += $array->amount;
					} else {
						$totalAmountAndDiscount[$array->studentID]['amountUSD'] = $array->amount;
					}

					if(isset($totalAmountAndDiscount[$array->studentID]['discountUSD'])) {
						$discount = (($array->amount / 100) * $array->discount);
						$totalAmountAndDiscount[$array->studentID]['discountUSD'] += $discount;
					} else {
						$discount = (($array->amount / 100) * $array->discount);
						$totalAmountAndDiscount[$array->studentID]['discountUSD'] = $discount;
					}
				}
				
				if ($array->currency == "GBP") {
					if(isset($totalAmountAndDiscount[$array->studentID]['amountGBP'])) {
						$totalAmountAndDiscount[$array->studentID]['amountGBP'] += $array->amount;
					} else {
						$totalAmountAndDiscount[$array->studentID]['amountGBP'] = $array->amount;
					}

					if(isset($totalAmountAndDiscount[$array->studentID]['discountGBP'])) {
						$discount = (($array->amount / 100) * $array->discount);
						$totalAmountAndDiscount[$array->studentID]['discountGBP'] += $discount;
					} else {
						$discount = (($array->amount / 100) * $array->discount);
						$totalAmountAndDiscount[$array->studentID]['discountGBP'] = $discount;
					}
				}

				if ($array->currency == "TZS") {
					if(isset($totalAmountAndDiscount[$array->studentID]['amountTZS'])) {
						$totalAmountAndDiscount[$array->studentID]['amountTZS'] += $array->amount;
					} else {
						$totalAmountAndDiscount[$array->studentID]['amountTZS'] = $array->amount;
					}

					if(isset($totalAmountAndDiscount[$array->studentID]['discountTZS'])) {
						$discount = (($array->amount / 100) * $array->discount);
						$totalAmountAndDiscount[$array->studentID]['discountTZS'] += $discount;
					} else {
						$discount = (($array->amount / 100) * $array->discount);
						$totalAmountAndDiscount[$array->studentID]['discountTZS'] = $discount;
					}
				}

				// if(isset($totalAmountAndDiscount[$array->studentID]['discount'])) {
				// 	$discount = (($array->amount / 100) * $array->discount);
				// 	$totalAmountAndDiscount[$array->studentID]['discount'] += $discount;
				// } else {
				// 	$discount = (($array->amount / 100) * $array->discount);
				// 	$totalAmountAndDiscount[$array->studentID]['discount'] = $discount;
				// }

			}
		}
		return $totalAmountAndDiscount;
	}

	private function totalPaymentAndWeaver($arrays) {
		$totalPayment = [];
		if(count($arrays)) {
			foreach($arrays as $key => $array) {
				// if(isset($totalPayment[$array->studentID]['payment'])) {
				// 	$totalPayment[$array->studentID]['payment'] += $array->paymentamount;
				// } else {
				// 	$totalPayment[$array->studentID]['payment'] = $array->paymentamount;
				// }
				if ($array->currency == "USD") {
					if(isset($totalPayment[$array->studentID]['paymentUSD'])) {
						$totalPayment[$array->studentID]['paymentUSD'] += $array->paymentamount;
					} else {
						$totalPayment[$array->studentID]['paymentUSD'] = $array->paymentamount;
					}
				}
				if ($array->currency == "GBP") {
					if(isset($totalPayment[$array->studentID]['paymentGBP'])) {
						$totalPayment[$array->studentID]['paymentGBP'] += $array->paymentamount;
					} else {
						$totalPayment[$array->studentID]['paymentGBP'] = $array->paymentamount;
					}
				}
				if ($array->currency == "TZS") {
					if(isset($totalPayment[$array->studentID]['paymentTZS'])) {
						$totalPayment[$array->studentID]['paymentTZS'] += $array->paymentamount;
					} else {
						$totalPayment[$array->studentID]['paymentTZS'] = $array->paymentamount;
					}
				}
			}
		}
		return $totalPayment;
	}

	private function totalWeaver($arrays) {
		$totalWeaver = [];
		if(count($arrays)) {
			foreach ($arrays as $array) {
				if(isset($totalWeaver[$array->studentID]['weaver'])) {
					$totalWeaver[$array->studentID]['weaver'] += $array->weaver;
				} else {
					$totalWeaver[$array->studentID]['weaver'] = $array->weaver; 
				}
			}
		}
		return $totalWeaver;
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
		if(permissionChecker('balancefeesreport')) { 
			$classesID = htmlentities(escapeString($this->uri->segment(3)));
			$sectionID = htmlentities(escapeString($this->uri->segment(4)));
			$studentID = htmlentities(escapeString($this->uri->segment(5)));

			if((int)($classesID >= 0) || (int)($sectionID >= 0) || (int)($studentID >= 0)) {
				$postArray = [];
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$postArray['schoolyearID'] = $schoolyearID;
				$postArray['classesID'] = $classesID;
				$postArray['sectionID'] = $sectionID;
				$postArray['studentID'] = $studentID;

				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;
				$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');

				$studentArray = [];
				if((int)$classesID) {
					$studentArray['srclassesID'] = $classesID;
				}
				if((int)$sectionID) {
					$studentArray['srsectionID'] = $sectionID;
				}
				if((int)$studentID) {
					$studentArray['srstudentID'] = $studentID;
				}
				$studentArray['srschoolyearID'] = $schoolyearID;

				$this->db->order_by('srclassesID','ASC');
				$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation($studentArray),'obj','srstudentID');
				$this->data['totalAmountAndDiscount'] = $this->totalAmountAndDiscount($this->invoice_m->get_all_balancefees_for_report($postArray));
				$this->data['totalPayment'] = $this->totalPaymentAndWeaver($this->payment_m->get_order_by_payment(array('schoolyearID' => $schoolyearID)));
				$this->data['totalweavar'] = $this->totalWeaver($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)));

				$this->reportPDF('balancefeesreport.css', $this->data, 'report/balancefees/BalanceFeesReportPDF');
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
		if(permissionChecker('balancefeesreport')) {
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
			header('Content-Disposition: attachment;filename="balancefeereport.xlsx"');
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
		if(permissionChecker('balancefeesreport')) { 
			$classesID = htmlentities(escapeString($this->uri->segment(3)));
			$sectionID = htmlentities(escapeString($this->uri->segment(4)));
			$studentID = htmlentities(escapeString($this->uri->segment(5)));

			if((int)($classesID >= 0) || (int)($sectionID >= 0) || (int)($studentID >= 0)) {
				$postArray = [];
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$postArray['schoolyearID'] = $schoolyearID;
				$postArray['classesID'] = $classesID;
				$postArray['sectionID'] = $sectionID;
				$postArray['studentID'] = $studentID;

				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;
				$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');

				$studentArray = [];
				if((int)$classesID) {
					$studentArray['srclassesID'] = $classesID;
				}
				if((int)$sectionID) {
					$studentArray['srsectionID'] = $sectionID;
				}
				if((int)$studentID) {
					$studentArray['srstudentID'] = $studentID;
				}
				$studentArray['srschoolyearID'] = $schoolyearID;

				$this->db->order_by('srclassesID','ASC');
				$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation($studentArray),'obj','srstudentID');

				$this->data['totalAmountAndDiscount'] = $this->totalAmountAndDiscount($this->invoice_m->get_all_balancefees_for_report($postArray));
				$this->data['totalPayment'] = $this->totalPaymentAndWeaver($this->payment_m->get_order_by_payment(array('schoolyearID' => $schoolyearID)));
				$this->data['totalweavar'] = $this->totalWeaver($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)));
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
		if(count($students)) {
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
				$className  = $this->lang->line('balancefeesreport_class');
				$className .= ' : ';
				$className .= isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('balancefeesreport_all_class');
				
				$sectionName  = $this->lang->line('balancefeesreport_section'); 				
				$sectionName .= " : ";
				$sectionName .= isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('balancefeesreport_all_section');
			
				$sheet->setCellValue('A'.$row, $className);
				$sheet->setCellValue($headerColumn.$row, $sectionName);
			} else {
				$sheet->getRowDimension('1')->setVisible(false);
			}

	        //Make Header Data Array
	        $headers = array();
	        $headers['hash_id'] = "#";
	        $headers['student_name'] = $this->lang->line('balancefeesreport_name');
	        $headers['admission_number'] = $this->lang->line('balancefeesreport_registerNO');

	        if($classesID == 0) { 
	        	$headers['class'] = $this->lang->line('balancefeesreport_class');
	        } 

	        if($sectionID == 0) { 
	        	$headers['section'] = $this->lang->line('balancefeesreport_section');
	        }

	        $headers['roll'] = $this->lang->line('balancefeesreport_roll');
	        $headers['total_amount'] = $this->lang->line('balancefeesreport_fees_amount');
	        $headers['discount'] = $this->lang->line('balancefeesreport_discount');
	        $headers['total_paid'] = $this->lang->line('balancefeesreport_paid');
	        $headers['total_weaver'] = $this->lang->line('balancefeesreport_weaver');
	        $headers['due'] = $this->lang->line('balancefeesreport_balance');



	        //Make Xml Header Array
			$column = 'A';    		
    		$row = 2;
	        foreach($headers as $header) {
	        	$sheet->setCellValue($column.$row,$header);
	            $column++;
	        }


	        $studentArray = [];
	        $totalAmountArray = [];
	        $i = 0;

	        $totalAmount = 0;
            $totalDiscount = 0;
            $totalPayments = 0;
            $totalWeaver = 0;
            $totalBalance = 0;

	      	foreach ($students as $student) {
	      		$i++;
	      		$studentArray[$i]['srno']   =  $i;
	      		$studentArray[$i]['srname'] = $student->srname;
	      		$studentArray[$i]['srregisterNO'] = $student->srregisterNO;

	      		if($classesID == 0) {
                    $studentArray[$i]['classes']  = isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : '';
                }

                if($sectionID == 0) { 
                	$studentArray[$i]['section'] = isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : '';
                }

	      		$studentArray[$i]['srroll'] = $student->srroll;
	      		$studentArray[$i]['amount'] = isset($totalAmountAndDiscount[$student->srstudentID]['amount']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['amount'],2) : '0';
	      		$studentArray[$i]['discount'] = isset($totalAmountAndDiscount[$student->srstudentID]['discount']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['discount'],2) : '0';
	      		$studentArray[$i]['payment'] = isset($totalPayment[$student->srstudentID]['payment']) ? number_format($totalPayment[$student->srstudentID]['payment'],2) : '0';
	      		$studentArray[$i]['weaver'] = isset($totalweavar[$student->srstudentID]['weaver']) ? number_format($totalweavar[$student->srstudentID]['weaver'],2) : '0';

	      		$Amount = 0;
                $Discount = 0;
                $Payment = 0;
                $Weaver = 0;

                if(isset($totalAmountAndDiscount[$student->srstudentID]['amount'])) {
                    $Amount = $totalAmountAndDiscount[$student->srstudentID]['amount'];
                    $totalAmount += $Amount;
                }

                if(isset($totalAmountAndDiscount[$student->srstudentID]['discount'])) {
                    $Discount = $totalAmountAndDiscount[$student->srstudentID]['discount'];
                    $totalDiscount += $Discount;
                }

                if(isset($totalPayment[$student->srstudentID]['payment'])) {
                    $Payment = $totalPayment[$student->srstudentID]['payment'];
                    $totalPayments += $Payment;
                }

                if(isset($totalweavar[$student->srstudentID]['weaver'])) {
                    $Weaver = $totalweavar[$student->srstudentID]['weaver'];
                    $totalWeaver += $Weaver;
                }

                $Balance = ($Amount - $Discount) - ($Payment+$Weaver);

                $totalBalance += $Balance;

                $studentArray[$i]['balance'] = number_format($Balance,2);
	      	}

	      	$i++;

	      	$studentArray[$i]['srno'] = '';
	      	$studentArray[$i]['srname'] = '';
	      	$studentArray[$i]['srregisterNO'] = '';
	      	if($classesID == 0) {
	      		$studentArray[$i]['classes'] = '';
	     	}

	     	if($sectionID == 0) { 
	        	$studentArray[$i]['section'] = '';
	        }

	      	$studentArray[$i]['srroll'] = '';
	      	$studentArray[$i]['amount'] = number_format($totalAmount,2);
	      	$studentArray[$i]['discount'] = number_format($totalDiscount,2);
	      	$studentArray[$i]['payment'] = number_format($totalPayments,2);
	      	$studentArray[$i]['weaver'] = number_format($totalWeaver,2);
	      	$studentArray[$i]['balance'] = number_format($totalBalance,2);

	        //Make Here Xml Body
	        $row  = 3;
	        if(count($studentArray)) {
	        	foreach($studentArray as $studentArray) {
	        		$column = "A";
	        		foreach($studentArray as $value) {
	        			$sheet->setCellValue($column.$row,$value);
	            		$column++;
	        		}
	        		$row++;
	        	}
	        }

	        if(count($totalAmountArray)) {
	        	foreach($totalAmountArray as $value) {
	        		$sheet->setCellValue($column.$row,$value);
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

			$decrementrow = $row-2;
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
			$decrementrow = $decrementrow+1;
			$sheet->getStyle('A'.$decrementrow.':'.$headerColumn.$decrementrow)->applyFromArray($styleArray);

			$headerColumn = chr(ord($headerColumn) - 1);  //Decreament Header Section Column
			$mergeCellsColumn = $headerColumn.'1';
			$sheet->mergeCells("B1:$mergeCellsColumn");

			
			$row = $row-1;
			
			$sheet->setCellValue('A'.$row, $this->lang->line('balancefeesreport_grand_total').(!empty($this->data['siteinfos']->currency_code) ? ' ('.$this->data['siteinfos']->currency_code.')' : ''));
			
			$startMergeCellsColumn = 'A'.$row;
			$headerColumn = chr(ord($headerColumn) - 4);
			$endMergeCellsColumn = $headerColumn.$row;
			$sheet->mergeCells("$startMergeCellsColumn:$endMergeCellsColumn");
		} else {
			redirect(base_url('balancefeesreport'));
		}
	}

	public function date_valid($date) {
		if($date) {
			if(strlen($date) < 10) {
				$this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy.");
		     	return FALSE;
			} else {
		   		$arr = explode("-", $date);
		        $dd = $arr[0];
		        $mm = $arr[1];
		        $yyyy = $arr[2];
		      	if(checkdate($mm, $dd, $yyyy)) {
		      		return TRUE;
		      	} else {
		      		$this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy.");
		     		return FALSE;
		      	}
		    }
		}
		return TRUE;
	}

	public function unique_date() {
		$fromdate = $this->input->post('fromdate');
		$todate   = $this->input->post('todate');

		$startingdate = $this->data['schoolyearobj']->startingdate;
		$endingdate = $this->data['schoolyearobj']->endingdate;

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

		if(permissionChecker('balancefeesreport')) { 
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
					$classesID = $this->input->post('classesID');
					$sectionID = $this->input->post('sectionID');
					$studentID = $this->input->post('studentID');

					$this->data['schoolyearID'] = $schoolyearID; 
					$this->data['classesID'] = $classesID;
					$this->data['sectionID'] = $sectionID;
					$this->data['studentID'] = $studentID;

					$postArray = [];
					$postArray['schoolyearID'] = $schoolyearID;
					$postArray['classesID'] = $classesID;
					$postArray['sectionID'] = $sectionID;
					$postArray['studentID'] = $studentID;

					$to      = $this->input->post('to'); 
					$subject = $this->input->post('subject'); 
					$message = $this->input->post('message');


					$studentArray = [];
					if((int)$classesID) {
						$studentArray['srclassesID'] = $classesID;
					}
					if((int)$sectionID) {
						$studentArray['srsectionID'] = $sectionID;
					}
					if((int)$studentID) {
						$studentArray['srstudentID'] = $studentID;
					}
					$studentArray['srschoolyearID'] = $schoolyearID;

					$this->db->order_by('srclassesID','ASC');
					$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation($studentArray),'obj','srstudentID');

					$this->data['classes']  = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');

					$this->data['totalAmountAndDiscount'] = $this->totalAmountAndDiscount($this->invoice_m->get_all_balancefees_for_report($postArray));
					$this->data['totalPayment'] = $this->totalPaymentAndWeaver($this->payment_m->get_order_by_payment(array('schoolyearID' => $schoolyearID)));
					$this->data['totalweavar'] = $this->totalWeaver($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)));

					$this->reportSendToMail('balancefeesreport.css', $this->data, 'report/balancefees/BalanceFeesReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
					$retArray['message'] = 'Success';
					echo json_encode($retArray);
			    	exit;
				}
			} else {
				$retArray['status'] = FALSE;
				$retArray['message'] = $this->lang->line('balancefeesreport_permissionmethod');
				echo json_encode($retArray);
		    	exit;
			}
		} else {
			$retArray['status'] = FALSE;
			$retArray['message'] = $this->lang->line('balancefeesreport_permission');
			echo json_encode($retArray);
	    	exit;
		}
	}

}

?>