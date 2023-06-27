<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transactionreport extends Admin_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('payment_m');
		$this->load->model('classes_m');
		$this->load->model('feetypes_m');
		$this->load->model('income_m');
		$this->load->model('expense_m');
		$this->load->model('usertype_m');
		$this->load->model('section_m');
		$this->load->model('make_payment_m');
		$this->load->model('weaverandfine_m');
		$this->load->model('studentrelation_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('transactionreport', $language);
	}

	public function rules() {
		$rules = array(
	        array(
                'field' => 'fromdate',
                'label' => $this->lang->line('transactionreport_fromdate'),
                'rules' => 'trim|required|xss_clean|callback_date_valid|callback_unique_date'
	        ),
	        array(
                'field' => 'todate',
                'label' => $this->lang->line('transactionreport_todate'),
                'rules' => 'trim|required|xss_clean|callback_date_valid'
	        )
		);
		return $rules;
	}

	public function send_pdf_to_mail_rules() {
		$rules = array(
	        array(
                'field' => 'fromdate',
                'label' => $this->lang->line('transactionreport_fromdate'),
                'rules' => 'trim|required|xss_clean|callback_date_valid_new|callback_unique_date_new'
	        ),
	        array(
                'field' => 'todate',
                'label' => $this->lang->line('transactionreport_todate'),
                'rules' => 'trim|required|xss_clean'
	        ),
	        array(
                'field' => 'to',
                'label' => $this->lang->line('transactionreport_to'),
                'rules' => 'trim|required|xss_clean|valid_email'
	        ),
	        array(
                'field' => 'subject',
                'label' => $this->lang->line('transactionreport_subject'),
                'rules' => 'trim|required|xss_clean'
	        ),
	        array(
                'field' => 'message',
                'label' => $this->lang->line('transactionreport_message'),
                'rules' => 'trim|xss_clean'
	        ),
	        array(
                'field' => 'querydata',
                'label' => $this->lang->line('transactionreport_querydata'),
                'rules' => 'trim|required|xss_clean'
	        )
		);
		return $rules;
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
			)
		);		
		$this->data["subview"] = "report/transaction/TransactionReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getTransactionReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('transactionreport')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
				    $retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->data['fromdate'] = $this->input->post('fromdate');
					$this->data['todate'] = $this->input->post('todate');
					$schoolyearID = $this->session->userdata('defaultschoolyearID');

					$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation(array('srschoolyearID'=>$schoolyearID)),'obj','srstudentID');
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
					$this->data['weaverandfine'] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)),'obj','paymentID');

					$array = [];
					$array['fromdate']     = date('Y-m-d',strtotime($this->input->post('fromdate')));
					$array['todate']       = date('Y-m-d',strtotime($this->input->post('todate')));
					$array['schoolyearID'] = $schoolyearID;

					$this->data['incomes'] = $this->income_m->get_income_order_by_date($array);
					$this->data['expenses'] = $this->expense_m->get_expense_order_by_date($array);
					$this->data['get_payments'] = $this->payment_m->get_payments($array);

					$this->data['allUserName'] = getAllUserObjectWithoutStudent();
					$this->data['usertypes']   = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
					$this->data['salarys'] = $this->make_payment_m->get_payment_salary_by_date(array('fromdate' => $this->input->post('fromdate'),'todate'=> $this->input->post('todate')));

					$retArray['render'] = $this->load->view('report/transaction/TransactionReport',$this->data,true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit;
				}
			} else {
				$retArray['status'] = TRUE;
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
		if(permissionChecker('transactionreport')) {
			$fromdate = $this->uri->segment(3);
			$todate = $this->uri->segment(4);
			$pdfoption = $this->uri->segment(5);
			if((isset($fromdate) && (int)$fromdate) && (isset($todate) && (int)$todate) && (isset($pdfoption) && (int)$pdfoption)) {
				$this->data['fromdate'] = $fromdate;
				$this->data['todate'] = $todate;

				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation(array('srschoolyearID'=>$schoolyearID)),'obj','srstudentID');
				$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
				$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
				$this->data['weaverandfine'] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)),'obj','paymentID');

				$array = [];
				$array['schoolyearID'] = $schoolyearID;
				$array['fromdate'] = date('Y-m-d',$fromdate);
				$array['todate'] = date('Y-m-d',$todate);
				$this->data['incomes'] = $this->income_m->get_income_order_by_date($array);
				$this->data['expenses'] = $this->expense_m->get_expense_order_by_date($array);
				$this->data['get_payments'] = $this->payment_m->get_payments($array);
				$this->data['pdfoption'] = $pdfoption;

				$this->data['allUserName'] = getAllUserObjectWithoutStudent();
				$this->data['usertypes']   = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
				$this->data['salarys'] = $this->make_payment_m->get_payment_salary_by_date(array('fromdate' => date('d-m-Y',$fromdate),'todate'=> date('d-m-Y',$todate)));

				$this->reportPDF('transactionreport.css', $this->data, 'report/transaction/TransactionReportPDF');
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
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		if(permissionChecker('transactionreport')) {
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

					$this->data['fromdate'] = $this->input->post('fromdate');
					$this->data['todate'] = $this->input->post('todate');

					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation(array('srschoolyearID'=>$schoolyearID)),'obj','srstudentID');
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
					$this->data['weaverandfine'] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)),'obj','paymentID');

					$array = [];
					$array['schoolyearID'] = $schoolyearID;
					$array['fromdate'] = date('Y-m-d',$this->data['fromdate']);
					$array['todate'] = date('Y-m-d',$this->data['todate']);
					$this->data['incomes'] = $this->income_m->get_income_order_by_date($array);
					$this->data['expenses'] = $this->expense_m->get_expense_order_by_date($array);
					$this->data['get_payments'] = $this->payment_m->get_payments($array);
					$this->data['pdfoption'] = $this->input->post('querydata');

					$this->data['allUserName'] = getAllUserObjectWithoutStudent();
					$this->data['usertypes']   = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
					$this->data['salarys'] = $this->make_payment_m->get_payment_salary_by_date(array('fromdate' => date('d-m-Y',$this->data['fromdate']),'todate'=> date('d-m-Y',$this->data['todate'])));

					$this->reportSendToMail('transactionreport.css', $this->data, 'report/transaction/TransactionReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
				    echo json_encode($retArray);
				}
			} else {
				$retArray['message'] = $this->lang->line('transactionreport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}

		} else {
			$retArray['message'] = $this->lang->line('transactionreport_permission');
			echo json_encode($retArray);
			exit;
		}

	}

	public function xlsx() {
		if(permissionChecker('transactionreport')) {
			$this->load->library('phpspreadsheet');

			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$sheet->getDefaultColumnDimension()->setWidth(20);
			$sheet->getDefaultRowDimension()->setRowHeight(20);
			$sheet->getColumnDimension('A')->setWidth(25);
			$sheet->getColumnDimension('C')->setWidth(25);
			$sheet->getRowDimension('1')->setRowHeight(30);
			$sheet->getRowDimension('2')->setRowHeight(25);
			
			$data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="transactionreport.xlsx"');
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
		$fromdate = $this->uri->segment(3);
		$todate = $this->uri->segment(4);
		$xmloption = $this->uri->segment(5);
		if((isset($fromdate) && (int)$fromdate) && (isset($todate) && (int)$todate) && (isset($xmloption) && (int)$xmloption)) {

			$this->data['fromdate'] = $fromdate;
			$this->data['todate'] = $todate;
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			
			$this->data['students'] = pluck($this->studentrelation_m->get_order_by_studentrelation(array('srschoolyearID'=>$schoolyearID)),'obj','srstudentID');
			$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
			$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
			$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
			$this->data['weaverandfine'] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID'=>$schoolyearID)),'obj','paymentID');
			
			$array = [];
			$array['schoolyearID'] = $schoolyearID;
			$array['fromdate'] = date('Y-m-d',$fromdate);
			$array['todate'] = date('Y-m-d',$todate);
			$this->data['get_payments'] = $this->payment_m->get_payments($array);
			$this->data['incomes'] = $this->income_m->get_income_order_by_date($array);
			$this->data['expenses'] = $this->expense_m->get_expense_order_by_date($array);
			$this->data['xmloption'] = $xmloption;

			$this->data['allUserName'] = getAllUserObjectWithoutStudent();
			$this->data['usertypes']   = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
			$this->data['salarys'] = $this->make_payment_m->get_payment_salary_by_date(array('fromdate' => date('d-m-Y',$fromdate),'todate'=> date('d-m-Y',$todate)));

			return $this->generateXML($this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);		
		}
	}

	private function generateXML($arrays) {
		extract($arrays);
		$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
		if($xmloption == 1 ) {
			if(count($get_payments)) {
				$headers = array();
				$headers['slno'] = $this->lang->line('slno');
				$headers['date'] = $this->lang->line('transactionreport_date');
				$headers['name'] = $this->lang->line('transactionreport_name');
                $headers['registerNO'] = $this->lang->line('transactionreport_registerNO');
				$headers['class'] = $this->lang->line('transactionreport_class');
                $headers['section'] = $this->lang->line('transactionreport_section');
                $headers['roll'] = $this->lang->line('transactionreport_roll');
				$headers['feetype'] = $this->lang->line('transactionreport_feetype');
				$headers['amount'] = $this->lang->line('transactionreport_paid');
				$headers['weaver'] = $this->lang->line('transactionreport_weaver');
				$headers['fine'] = $this->lang->line('transactionreport_fine');

				$bodys = array();
				$i=0;
				$totalamount = 0;
                $totalweaver = 0;
                $totalfine   = 0;

				foreach ($get_payments as $get_payment) {
					if(isset($weaverandfine[$get_payment->paymentID]) && (($weaverandfine[$get_payment->paymentID]->weaver != '') || ($weaverandfine[$get_payment->paymentID]->fine != '')) || $get_payment->paymentamount != '') {

						$bodys[$i][] = $i+1;
						$bodys[$i][] = date('d M Y',strtotime($get_payment->paymentdate));
                        $bodys[$i][] = isset($students[$get_payment->studentID]) ? $students[$get_payment->studentID]->srname : '';
                        $bodys[$i][] = isset($students[$get_payment->studentID]) ? $students[$get_payment->studentID]->srregisterNO : '';
                                                        
                        if(isset($students[$get_payment->studentID])) {
                            if(isset($classes[$students[$get_payment->studentID]->srclassesID])) {
                                $bodys[$i][] = $classes[$students[$get_payment->studentID]->srclassesID];
                            }  
                        }
                        
                        if(isset($students[$get_payment->studentID])) {
                            if(isset($sections[$students[$get_payment->studentID]->srsectionID])) {
                                $bodys[$i][] = $sections[$students[$get_payment->studentID]->srsectionID];
                            }  
                        }
                        
                        $bodys[$i][] = isset($students[$get_payment->studentID]) ? $students[$get_payment->studentID]->srroll : '';
                        $bodys[$i][] = isset($feetypes[$get_payment->feetypeID]) ? $feetypes[$get_payment->feetypeID] : '';
                        
                        $amount = $get_payment->paymentamount;
                        $bodys[$i][] = number_format($amount,2); 
                        $totalamount +=$amount;
                                                   
                        if(isset($weaverandfine[$get_payment->paymentID])) {
                            $weaver = $weaverandfine[$get_payment->paymentID]->weaver;
                            $bodys[$i][] = number_format($weaver,2);
                            $totalweaver += $weaver;
                        } else {
                            $bodys[$i][] = number_format(0,2);
                        }

                        if(isset($weaverandfine[$get_payment->paymentID])) {
                            $fine = $weaverandfine[$get_payment->paymentID]->fine;
                            $bodys[$i][] = number_format($fine,2);
                            $totalfine +=$fine;
                        } else{
                            $bodys[$i][] = number_format(0,2);
                        }
	                    $i++;
	                }
				}

				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = number_format($totalamount,2);
				$bodys[$i][] = number_format($totalweaver,2);
				$bodys[$i][] = number_format($totalfine,2);


				$fromdateValue = $this->lang->line('transactionreport_fromdate')." : ".date('d M Y',$fromdate);
				$todateValue   = $this->lang->line('transactionreport_todate')." : ".date('d M Y',$todate);
				$sheet->setCellValue('A1', $fromdateValue);
				$sheet->setCellValue('K1', $todateValue);

				if(count($headers)) {
					$row = 2;
					$column = "A";
					foreach($headers as $header) {
						$sheet->setCellValue($column.$row, $header);
		    			$column++;
					}
				}

				if(count($bodys)) {
					$row = 3;
					foreach($bodys as $single_rows) {
						$column = 'A';
						foreach ($single_rows as $key => $value) {
							$sheet->setCellValue($column.$row, $value);
		    				$column++;
						}
						$row++;
					}
				}

				$grandTotalValue = $this->lang->line('transactionreport_grand_total') . (!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : '');
				$sheet->setCellValue('A'.($row-1), $grandTotalValue);


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
				$sheet->getStyle('A1:K2')->applyFromArray($styleArray);

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
				$styleColumn = ($row-2);
				$sheet->getStyle('A3:K'.$styleColumn)->applyFromArray($styleArray);

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
				$styleColumn = $row-1;
				$sheet->getStyle('A'.$styleColumn.':K'.$styleColumn)->applyFromArray($styleArray);

				$startmerge = "A".($row-1);
				$endmerge = "H".($row-1);
				$sheet->mergeCells("$startmerge:$endmerge");
				
			}
		} elseif($xmloption == 2) {
			if(count($incomes)) {
				$headers = [];
				$headers['name'] = $this->lang->line('transactionreport_name');
				$headers['date'] = $this->lang->line('transactionreport_date');
				$headers['amount'] = $this->lang->line('transactionreport_amount');


				$i = 0;
				$totalincome = 0;
				$bodys = array();

				foreach($incomes as $income) {
					$bodys[$i][] = $income->name;
					$bodys[$i][] = date('d M Y',strtotime($income->date));
					$amount = $income->amount;
					$bodys[$i][] = number_format($amount,2);
                    $totalincome += $amount;
                    $i++;
				}

				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = number_format($totalincome,2);

				$fromdateValue = $this->lang->line('transactionreport_fromdate')." : ".date('d M Y',$fromdate);
				$todateValue   = $this->lang->line('transactionreport_todate')." : ".date('d M Y',$todate);
				$sheet->setCellValue('A1', $fromdateValue);
				$sheet->setCellValue('C1', $todateValue);

				if(count($headers)) {
					$column = "A";
					$row = 2;
					foreach($headers as $header) {
						$sheet->setCellValue($column.$row, $header);
		    			$column++;
					}
				}

				if(count($bodys)) {
					$row = 3;
					foreach($bodys as $single_rows) {
						$column = 'A';
						foreach($single_rows as $value) {
							$sheet->setCellValue($column.$row, $value);
		    				$column++;
						}
						$row++;
					}
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
				$sheet->getStyle('A1:C2')->applyFromArray($styleArray);

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
				$styleColumn = "C".($row-2);
				$sheet->getStyle('A3:'.$styleColumn)->applyFromArray($styleArray);

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
				$styleColumn = $row-1;
				$sheet->getStyle('A'.$styleColumn.':'.'C'.$styleColumn)->applyFromArray($styleArray);

				$grandValue = $this->lang->line('transactionreport_grand_total') . (!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : '');
				$sheet->setCellValue('A'.$styleColumn,$grandValue);

				$startmerge = "A".$styleColumn;
				$endmerge = "B".$styleColumn;
				$sheet->mergeCells("$startmerge:$endmerge");

			}
		} elseif($xmloption == 3) {
			if(count($expenses)) {
				$headers = array();
				$headers[]  = $this->lang->line('transactionreport_name');
				$headers[]  = $this->lang->line('transactionreport_date');
				$headers[]  = $this->lang->line('transactionreport_amount');

				$i= 0;
				$totalexpense = 0;
				$bodys = array();
				foreach($expenses as $expense) {
					$bodys[$i][] = $expense->expense;
					$bodys[$i][] = date('d M Y',strtotime($expense->date));
					$amount = $expense->amount;
					$bodys[$i][] = number_format($amount,2);
                    $totalexpense += $amount; 
					$i++;
				}
				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = $totalexpense;

				$fromdateValue = $this->lang->line('transactionreport_fromdate')." : ".date('d M Y',$fromdate);
				$todateValue   = $this->lang->line('transactionreport_todate')." : ".date('d M Y',$todate);
				$sheet->setCellValue('A1', $fromdateValue);
				$sheet->setCellValue('C1', $todateValue);

				if(count($headers)) {
					$column = "A";
					$row = 2;
					foreach($headers as $header) {
						$sheet->setCellValue($column.$row, $header);
		    			$column++;
					}
				}

				if(count($bodys)) {
					$row = 3;
					foreach($bodys as $single_rows) {
						$column = 'A';
						foreach($single_rows as $value) {
							$sheet->setCellValue($column.$row, $value);
		    				$column++;
						}
						$row++;
					}
				}

				$grandTotalValue = $this->lang->line('transactionreport_grand_total') . (!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : '');

				$sheet->setCellValue('A'.($row-1), $grandTotalValue);

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
				$sheet->getStyle('A1:C2')->applyFromArray($styleArray);

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
				$styleColumn = "C".($row-2);
				$sheet->getStyle('A3:'.$styleColumn)->applyFromArray($styleArray);

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
				$styleColumn = $row-1;
				$sheet->getStyle('A'.$styleColumn.':'.'C'.$styleColumn)->applyFromArray($styleArray);

				$startmerge = "A".($row-1);
				$endmerge = "B".($row-1);
				$sheet->mergeCells("$startmerge:$endmerge");
			}
		} elseif($xmloption == 4) {
			if(count($salarys)) {
				$headers = array();
				$headers[]  = $this->lang->line('slno');
				$headers[]  = $this->lang->line('transactionreport_date');
				$headers[]  = $this->lang->line('transactionreport_name');
				$headers[]  = $this->lang->line('transactionreport_type');
				$headers[]  = $this->lang->line('transactionreport_month');
				$headers[]  = $this->lang->line('transactionreport_amount');

				$i= 0;
				$totalSalary = 0;
				$bodys = array();
				foreach($salarys as $salary) {
					$bodys[$i][] = $i+1;
					$bodys[$i][] = date('d M Y',strtotime($salary->create_date));
					$bodys[$i][] = isset($allUserName[$salary->usertypeID][$salary->userID]) ? $allUserName[$salary->usertypeID][$salary->userID]->name : '';
					$bodys[$i][] = isset($usertypes[$salary->usertypeID]) ? $usertypes[$salary->usertypeID] : '';
					$bodys[$i][] = date('F Y',strtotime('01-'.$salary->month));
					$bodys[$i][] = number_format($salary->payment_amount,2);
                    $totalSalary += $salary->payment_amount;
					$i++;
				}
				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = "";
				$bodys[$i][] = number_format($totalSalary,2);

				$fromdateValue = $this->lang->line('transactionreport_fromdate')." : ".date('d M Y',$fromdate);
				$todateValue   = $this->lang->line('transactionreport_todate')." : ".date('d M Y',$todate);
				$sheet->setCellValue('A1', $fromdateValue);
				$sheet->setCellValue('F1', $todateValue);

				if(count($headers)) {
					$column = "A";
					$row = 2;
					foreach($headers as $header) {
						$sheet->setCellValue($column.$row, $header);
		    			$column++;
					}
				}

				if(count($bodys)) {
					$row = 3;
					foreach($bodys as $single_rows) {
						$column = 'A';
						foreach($single_rows as $value) {
							$sheet->setCellValue($column.$row, $value);
		    				$column++;
						}
						$row++;
					}
				}

				$grandTotalValue = $this->lang->line('transactionreport_grand_total') . (!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : '');

				$sheet->setCellValue('A'.($row-1), $grandTotalValue);

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
				$sheet->getStyle('A1:F2')->applyFromArray($styleArray);

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

				$styleColumn = "F".($row-2);
				$sheet->getStyle('A3:'.$styleColumn)->applyFromArray($styleArray);

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
				$styleColumn = $row-1;
				$sheet->getStyle('A'.$styleColumn.':'.'F'.$styleColumn)->applyFromArray($styleArray);

				$startmerge = "A".($row-1);
				$endmerge = "E".($row-1);
				$sheet->mergeCells("$startmerge:$endmerge");
				$sheet->mergeCells("B1:E1");
			}
		} else {
			redirect('transactionreport');
		}
	}

	public function date_valid($date) {
		if($date) {
			if(strlen($date) < 10) {
				$this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy");
		     	return FALSE;
			} else {
		   		$arr = explode("-", $date);
		        $dd = $arr[0];
		        $mm = $arr[1];
		        $yyyy = $arr[2];
		      	if(checkdate($mm, $dd, $yyyy)) {
		      		return TRUE;
		      	} else {
		      		$this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy");
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

	public function date_valid_new($date) {
		$date = date('d-m-Y',$date);
		if($date) {
			if(strlen($date) < 10) {
				$this->form_validation->set_message("date_valid_new", "The %s is not valid dd-mm-yyyy");
		     	return FALSE;
			} else {
		   		$arr = explode("-", $date);
		        $dd = $arr[0];
		        $mm = $arr[1];
		        $yyyy = $arr[2];
		      	if(checkdate($mm, $dd, $yyyy)) {
		      		return TRUE;
		      	} else {
		      		$this->form_validation->set_message("date_valid_new", "The %s is not valid dd-mm-yyyy");
		     		return FALSE;
		      	}
		    }
		}
		return TRUE;
	}

	public function unique_date_new() {
		$fromdate = date('d-m-Y',$this->input->post('fromdate'));
		$todate   = date('d-m-Y',$this->input->post('todate'));

		$startingdate = $this->data['schoolyearsessionobj']->startingdate;
		$endingdate = $this->data['schoolyearsessionobj']->endingdate;

		if($fromdate != '' && $todate != '') {
			if(strtotime($fromdate) > strtotime($todate)) {
				$this->form_validation->set_message("unique_date_new", "The from date can not be upper than todate .");
		   		return FALSE;
			}
			if((strtotime($fromdate) < strtotime($startingdate)) || (strtotime($fromdate) > strtotime($endingdate))) {
				$this->form_validation->set_message("unique_date_new", "The from date is invalid .");
			    return FALSE;
			}
			if((strtotime($todate) < strtotime($startingdate)) || (strtotime($todate) > strtotime($endingdate))) {
				$this->form_validation->set_message("unique_date_new", "The to date is invalid .");
			    return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

}
