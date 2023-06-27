<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentfinereport extends Admin_Controller {
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
	public function __construct() {
		parent::__construct();
		$this->load->model('classes_m');
		$this->load->model('section_m');
		$this->load->model('weaverandfine_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('studentfinereport', $language);
	}
	
	protected function rules() {
		$rules = array(
			array(
				'field' => 'fromdate',
				'label' => $this->lang->line("studentfinereport_fromdate"),
				'rules' => 'trim|required|xss_clean|callback_date_valid|callback_unique_date'
			),
			array(
				'field' => 'todate',
				'label' => $this->lang->line("studentfinereport_todate"),
				'rules' => 'trim|required|xss_clean|callback_date_valid|callback_unique_date'
			),
		);
		return $rules;
	} 

	protected function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field' => 'fromdate',
				'label' => $this->lang->line("studentfinereport_fromdate"),
				'rules' => 'trim|required|xss_clean|callback_date_valid|callback_unique_date'
			),
			array(
				'field' => 'todate',
				'label' => $this->lang->line("studentfinereport_todate"),
				'rules' => 'trim|required|xss_clean|callback_date_valid|callback_unique_date'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line("studentfinereport_to"),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("studentfinereport_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("studentfinereport_message"),
				'rules' => 'trim|xss_clean'
			),
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
		$this->data["subview"] = "report/studentfine/StudentFineReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getstudentfinereport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('studentfinereport')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$array['fromdate']    = $this->input->post('fromdate');	
					$array['todate']      = $this->input->post('todate');
					$array['schoolyearID']= $this->session->userdata('defaultschoolyearID');
					$this->data['fromdate'] = $this->input->post('fromdate');
					$this->data['todate']   = $this->input->post('todate');
					$this->data['studentfines'] = $this->weaverandfine_m->get_weaverandfine_join_with_payment($array);
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
					$retArray['render'] = $this->load->view('report/studentfine/StudentFineReport', $this->data, true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
				    exit;
				}
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
		if(permissionChecker('studentfinereport')) {
			$fromdate = htmlentities(escapeString($this->uri->segment(3)));
			$todate  = htmlentities(escapeString($this->uri->segment(4)));
			if((int)$fromdate && (int)$todate) {
				$array['fromdate']    = date('d-m-Y',$fromdate);	
				$array['todate']      = date('d-m-Y',$todate);
				$array['schoolyearID']= $this->session->userdata('defaultschoolyearID');
				$this->data['fromdate'] = $array['fromdate'];
				$this->data['todate']   = $array['todate'];
				$this->data['studentfines'] = $this->weaverandfine_m->get_weaverandfine_join_with_payment($array);
				$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
				$this->reportPDF('studentfinereport.css',$this->data,'report/studentfine/StudentFineReportPDF');
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
		if(permissionChecker('studentfinereport')) {
			$this->load->library('phpspreadsheet');
			$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
			$sheet->getDefaultColumnDimension()->setWidth(15);
			$sheet->getDefaultRowDimension()->setRowHeight(25);
			$sheet->getColumnDimension('A')->setWidth(30);
			$sheet->getColumnDimension('C')->setWidth(30);

			$data = $this->xmlData();
			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="studentfinereport.xlsx"');
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
		if(permissionChecker('studentfinereport')) {
			$fromdate = htmlentities(escapeString($this->uri->segment(3)));
			$todate  = htmlentities(escapeString($this->uri->segment(4)));
			if((int)$fromdate && (int)$todate) {
				$array['fromdate']    = date('d-m-Y',$fromdate);	
				$array['todate']      = date('d-m-Y',$todate);
				$array['schoolyearID']= $this->session->userdata('defaultschoolyearID');
				$this->data['fromdate'] = $array['fromdate'];
				$this->data['todate']   = $array['todate'];
				$this->data['studentfines'] = $this->weaverandfine_m->get_weaverandfine_join_with_payment($array);
				$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
				return $this->generateXML($this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	private function generateXML($data) {
		extract($data);
        $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
		if(count($studentfines)) {
			$countColumn = 9;
			$headerColumn = 'A';
			for($j = 1; $j < $countColumn ; $j++) {
				$headerColumn++;
			}

			$row = 1;
			$fromdateValue  = $this->lang->line('studentfinereport_fromdate');
			$fromdateValue .= " : ";
			$fromdateValue .= date('d M Y',strtotime($fromdate));

			$todateValue  = $this->lang->line('studentfinereport_todate');
			$todateValue .= " : ";
			$todateValue .= date('d M Y',strtotime($todate));

			$sheet->setCellValue('A1',$fromdateValue);
			$sheet->setCellValue($headerColumn.'1',$todateValue);

			$sheet->getColumnDimension($headerColumn)->setWidth(30);

			$headers = [];
			$headers[] = $this->lang->line('studentfinereport_slno');
			$headers[] = $this->lang->line('studentfinereport_date');
			$headers[] = $this->lang->line('studentfinereport_name');
			$headers[] = $this->lang->line('studentfinereport_registerNO');
			$headers[] = $this->lang->line('studentfinereport_class');
			$headers[] = $this->lang->line('studentfinereport_section');
			$headers[] = $this->lang->line('studentfinereport_roll');
			$headers[] = $this->lang->line('studentfinereport_feetype');
			$headers[] = $this->lang->line('studentfinereport_fine');
			$row = 2;
			$column = 'A';
			if(count($headers)) {
				foreach ($headers as $key => $value) {
					$sheet->setCellValue($column.$row, $value);
					$column++;
				}
			}

			$bodys = [];
			$i = 0;
			$j = 1;
			$totalfine = 0;
			foreach($studentfines as $studentfine) {
				$bodys[$i]['slno'] = $j;
				$bodys[$i]['date'] = date('d M Y',strtotime($studentfine->paymentdate));
				$bodys[$i]['name'] = $studentfine->srname;
				$bodys[$i]['registerNO'] = $studentfine->srregisterNO;
				$bodys[$i]['class'] = isset($classes[$studentfine->srclassesID]) ? $classes[$studentfine->srclassesID] : ' ';
				$bodys[$i]['section'] = isset($sections[$studentfine->srsectionID]) ? $sections[$studentfine->srsectionID] : ' ';
				$bodys[$i]['roll'] = $studentfine->srroll;
				$bodys[$i]['feetype'] = $studentfine->feetypes;
				$bodys[$i]['fine'] = number_format($studentfine->fine,2);
				$totalfine += $studentfine->fine;
				$i++;
				$j++;
			}

			$grandTotal = $this->lang->line('studentfinereport_grand_total').' ';
			$grandTotal .= !empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : '';

			$bodys[$i]['slno'] = $grandTotal;
			$bodys[$i]['date'] = '';
			$bodys[$i]['name'] = '';
			$bodys[$i]['registerNO'] = '';
			$bodys[$i]['class'] = '';
			$bodys[$i]['section'] = '';
			$bodys[$i]['roll'] = '';
			$bodys[$i]['feetype'] = '';
			$bodys[$i]['fine'] = number_format($totalfine,2);

			if(count($bodys)) {
				$row = 3;
				foreach($bodys as $rows) {
					$column = 'A';
					foreach ($rows as $key => $value) {
						$sheet->setCellValue($column.$row, $value);
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

			$styleArray = [
			    'font' => [
			        'bold' => TRUE,
			    ]
			];
			$sheet->getStyle('A'.$row.':'.$headerColumn.$row)->applyFromArray($styleArray);

			$headerColumn = chr(ord($headerColumn) - 1);  //Decreament Header Section Column
			$mergeCellsColumn = $headerColumn.'1';
			$sheet->mergeCells("B1:$mergeCellsColumn");
			$sheet->mergeCells("A".$row.":".$headerColumn.$row);

		} else {
			redirect('studentfinereport');
		}
	}

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';

		if(permissionChecker('studentfinereport')) {
			if($_POST) {
				$to      = $this->input->post('to');
				$subject = $this->input->post('subject');
				$message = $this->input->post('message');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');

				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$array['fromdate']    = $this->input->post('fromdate');	
					$array['todate']      = $this->input->post('todate');
					$array['schoolyearID']= $schoolyearID;
					$this->data['fromdate'] = $this->input->post('fromdate');
					$this->data['todate']   = $this->input->post('todate');
					$this->data['studentfines'] = $this->weaverandfine_m->get_weaverandfine_join_with_payment($array);
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->reportSendToMail('studentfinereport.css', $this->data, 'report/studentfine/StudentFineReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
				    echo json_encode($retArray);
				    exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('studentfinereport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('studentfinereport_permission');
			echo json_encode($retArray);
			exit;
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
}
