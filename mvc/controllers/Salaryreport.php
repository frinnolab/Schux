<?php

class Salaryreport extends Admin_Controller{
	public function __construct() {
		parent::__construct();
        $this->load->model('usertype_m');
        $this->load->model('user_m');
        $this->load->model("make_payment_m");
        $this->load->model('systemadmin_m');
        $this->load->model('teacher_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('salaryreport', $language);
	}

	public function rules() {
		$rules = array(
            array(
                'field'=>'usertypeID',
                'label'=>$this->lang->line('salaryreport_user_type'),
                'rules' => 'trim|xss_clean|numeric'
            ),
            array(
                'field'=>'userID',
                'label'=>$this->lang->line('salaryreport_user_name'),
                'rules' => 'trim|xss_clean|numeric'
            ),
            array(
                'field'=>'month',
                'label'=>$this->lang->line('salaryreport_month'),
                'rules' => 'trim|xss_clean|callback_month_date_valid'
            ),
            array(
                'field'=>'fromdate',
                'label'=>$this->lang->line('salaryreport_fromdate'),
                'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
            ),
			array(
				'field'=>'todate',
				'label'=>$this->lang->line('salaryreport_todate'),
				'rules' => 'trim|xss_clean|callback_date_valid'
			),
		);
		return $rules;
	}

	public function send_pdf_to_mail_rules() {
		$rules = array(

			array(
				'field'=>'to',
				'label'=>$this->lang->line('salaryreport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field'=>'subject',
				'label'=>$this->lang->line('salaryreport_subject'),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field'=>'message',
				'label'=>$this->lang->line('salaryreport_message'),
				'rules' => 'trim|xss_clean'
			),
            array(
                'field'=>'usertypeID',
                'label'=>$this->lang->line('salaryreport_user_type'),
                'rules' => 'trim|xss_clean|numeric'
            ),
            array(
                'field'=>'userID',
                'label'=>$this->lang->line('salaryreport_user_name'),
                'rules' => 'trim|xss_clean|numeric'
            ),
            array(
                'field'=>'month',
                'label'=>$this->lang->line('salaryreport_month'),
                'rules' => 'trim|xss_clean|callback_date_valid'
            ),
            array(
                'field'=>'fromdate',
                'label'=>$this->lang->line('salaryreport_fromdate'),
                'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
            ),
            array(
                'field'=>'todate',
                'label'=>$this->lang->line('salaryreport_todate'),
                'rules' => 'trim|xss_clean|callback_date_valid'
            ),
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
        $usertypeData = [];
        $usertypes = $this->usertype_m->get_usertype();
        if(count($usertypes)) {
            foreach($usertypes as $key => $usertype) {
                if(($usertype->usertypeID != 3) && ($usertype->usertypeID != 4)) {
                    $usertypeData[$key] = $usertype;
                }
            }
        }

        $this->data['usertypes'] = $usertypeData;
		$this->data["subview"] = "report/salary/SalaryReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getSalaryReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('salaryreport')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
			    if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
                    $month = '01-'.$this->input->post('month');
                    $this->data['usertypeID']   = $this->input->post('usertypeID');
                    $this->data['userID']       = $this->input->post('userID');
                    $this->data['month']        = !empty($this->input->post('month')) ? strtotime($month) : '0';
                    $this->data['fromdate']     = !empty($this->input->post('fromdate')) ? strtotime($this->input->post('fromdate')) : '0';
                    $this->data['todate']       = !empty($this->input->post('todate')) ? strtotime($this->input->post('todate')) : '0';
                    $this->data['allUserName']  = getAllUserObjectWithoutStudent();
                    $this->data['usertypes']    = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                    $this->data['salarys']      = $this->make_payment_m->get_all_salary_for_report($this->input->post());

                    $retArray['render'] = $this->load->view('report/salary/SalaryReport', $this->data, true);
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

    public function getUser() {
        $usertypeID = $this->input->post('usertypeID');
        if((int)$usertypeID) {
            echo "<option value='0'>".$this->lang->line("salaryreport_please_select")."</option>";
            if($usertypeID == 1) {
                $users = $this->systemadmin_m->get_systemadmin();
                if(count($users)) {
                    foreach ($users as $user) {
                        echo "<option value='".$user->systemadminID."'>".$user->name."</option>";
                    }
                }
            } elseif($usertypeID == 2) {
                $users = $this->teacher_m->general_get_teacher();
                if(count($users)) {
                    foreach ($users as $user) {
                        echo "<option value='".$user->teacherID."'>".$user->name."</option>";
                    }
                }
            } elseif($usertypeID == 3) {
                $users = [];
            } elseif($usertypeID == 4) {
                $users = [];
            } else {
                $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
                if(count($users)) {
                    foreach ($users as $user) {
                        echo "<option value='".$user->userID."'>".$user->name."</option>";
                    }
                }
            }
        }
    }

    public function pdf() {
		if(permissionChecker('salaryreport')) {
            $usertypeID = htmlentities(escapeString($this->uri->segment(3)));
            $userID     = htmlentities(escapeString($this->uri->segment(4)));
            $fromdate   = htmlentities(escapeString($this->uri->segment(5)));
            $todate     = htmlentities(escapeString($this->uri->segment(6)));
            $month      = htmlentities(escapeString($this->uri->segment(7)));

            if((int)($usertypeID >= 0) && (int)($userID >= 0) && (int)($month >= 0) && (int)($fromdate >= 0) && (int)($todate >= 0)) {
                $postArray = [];
                $postArray['usertypeID'] = $usertypeID;
                $postArray['userID'] = $userID;
                if($month != '0') {
                    $monthDate = date('d-m-Y',$month);
                    $monthArray = explode('-',$monthDate);
                    $postArray['month'] = $monthArray[1].'-'.$monthArray[2];
                }

                if($fromdate !='0' && $todate != '0') {
                    $postArray['fromdate']  = date('d-m-Y',$fromdate);
                    $postArray['todate']    = date('d-m-Y',$todate);
                }

                $this->data['usertypeID']   = $usertypeID;
                $this->data['userID']       = $userID;
                $this->data['month']        = $month;
                $this->data['fromdate']     = $fromdate;
                $this->data['todate']       = $todate;

                $this->data['allUserName'] = getAllUserObjectWithoutStudent();
                $this->data['usertypes']   = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                $this->data['salarys'] = $this->make_payment_m->get_all_salary_for_report($postArray);

                $this->reportPDF('salaryreport.css', $this->data, 'report/salary/SalaryReportPDF');
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
		if(permissionChecker('salaryreport')) {
            $this->load->library('phpspreadsheet');

            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            $sheet->getDefaultColumnDimension()->setWidth(25);
            $sheet->getDefaultRowDimension()->setRowHeight(25);
            $sheet->getColumnDimension('A')->setWidth(25);
            $sheet->getRowDimension('1')->setRowHeight(25);
            $sheet->getRowDimension('2')->setRowHeight(25);

            $data = $this->xmlData();

            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="salaryreport.xlsx"');
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
		if(permissionChecker('salaryreport')) {
            $usertypeID = htmlentities(escapeString($this->uri->segment(3)));
            $userID     = htmlentities(escapeString($this->uri->segment(4)));
            $fromdate   = htmlentities(escapeString($this->uri->segment(5)));
            $todate     = htmlentities(escapeString($this->uri->segment(6)));
            $month      = htmlentities(escapeString($this->uri->segment(7)));

            if((int)($usertypeID >= 0) && (int)($userID >= 0) && (int)($month >= 0) && (int)($fromdate >= 0) && (int)($todate >= 0)) {

                $postArray = [];
                $postArray['usertypeID'] = $usertypeID;
                $postArray['userID'] = $userID;
                if($month != '0') {
                    $monthDate = date('d-m-Y',$month);
                    $monthArray = explode('-',$monthDate);
                    $postArray['month'] = $monthArray[1].'-'.$monthArray[2];
                }

                if($fromdate !='0' && $todate != '0') {
                    $postArray['fromdate']  = date('d-m-Y',$fromdate);
                    $postArray['todate']    = date('d-m-Y',$todate);
                }
                
                $this->data['fromdate']     = $fromdate;
                $this->data['todate']       = $todate;
                $this->data['usertypeID']   = $usertypeID;
                $this->data['userID']       = $userID;
                $this->data['month']        = $month;

                $this->data['allUserName']  = getAllUserObjectWithoutStudent();
                $this->data['usertypes']    = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                $this->data['salarys']      = $this->make_payment_m->get_all_salary_for_report($postArray);
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

	private function generateXML($array) {
		extract($array);
        if(count($salarys)) {
    		$sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            $row = 1;
            $topCellMerge = TRUE;

            if($fromdate != 0 && $todate != 0 ) { 
                $datefrom   = $this->lang->line('salaryreport_fromdate')." : ";
                $datefrom   .= date('d M Y',$fromdate);
                $dateto     = $this->lang->line('salaryreport_todate')." : ";
                $dateto     .= date('d M Y', $todate);
             
                $sheet->setCellValue('A'.$row, $datefrom);
                $sheet->setCellValue('F'.$row, $dateto);
            } elseif($month != 0 ) {
                $topCellMerge = FALSE;
                $usertype = $this->lang->line('salaryreport_month')." : ";
                $usertype .= date('M Y', $month);
               
                $sheet->setCellValue('A'.$row, $usertype);
            } elseif($usertypeID != 0 && $userID != 0) {
                $usertype = $this->lang->line('salaryreport_role')." : ";
                $usertype .= $usertypes[$usertypeID];
                $username = $this->lang->line('salaryreport_user_name')." : ";
                $username .= $allUserName[$usertypeID][$userID]->name;

                $sheet->setCellValue('A'.$row, $usertype);
                $sheet->setCellValue('F'.$row, $username);
            } elseif($usertypeID != 0) {
                $topCellMerge = FALSE;
                $usertype = $this->lang->line('salaryreport_role')." : ";
                $usertype .= $usertypes[$usertypeID];

                $sheet->setCellValue('A'.$row, $usertype);
            } elseif($usertypeID == 0) {
                $topCellMerge = FALSE;
                $usertype = $this->lang->line('salaryreport_role')." : ";
                $usertype .= $this->lang->line('salaryreport_alluser');

                $sheet->setCellValue('A'.$row, $usertype);
            }

            $headers = array();
            $headers[]  = $this->lang->line('slno');
            $headers[]  = $this->lang->line('salaryreport_date');
            $headers[]  = $this->lang->line('salaryreport_name');
            $headers[]  = $this->lang->line('salaryreport_role');
            $headers[]  = $this->lang->line('salaryreport_month');
            $headers[]  = $this->lang->line('salaryreport_amount');

            if(count($headers)) {
                $column = "A";
                $row = 2;
                foreach($headers as $header) {
                    $sheet->setCellValue($column.$row, $header);
                    $column++;
                }
            }

            $i= 0;
            $totalSalary = 0;
            $bodys = array();
            foreach($salarys as $salary) {
                $bodys[$i][] = $i+1;
                $bodys[$i][] = date('d M Y',strtotime($salary->create_date));
                $bodys[$i][] = isset($allUserName[$salary->usertypeID][$salary->userID]) ? $allUserName[$salary->usertypeID][$salary->userID]->name : '';
                $bodys[$i][] = isset($usertypes[$salary->usertypeID]) ? $usertypes[$salary->usertypeID] : '';
                $bodys[$i][] = date('M Y',strtotime('01-'.$salary->month));
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

            $grandTotalValue = $this->lang->line('salaryreport_grand_total') . (!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : '');

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

            if($topCellMerge) {
                $sheet->mergeCells("B1:E1");
            } else {
                $sheet->mergeCells("B1:F1");
            }

            $startmerge = "A".$styleColumn;
            $endmerge = "E".$styleColumn;
            $sheet->mergeCells("$startmerge:$endmerge");
        } else {
            redirect(base_url('salaryreport'));
        }
    }

    public function month_date_valid() {
        $month = $this->input->post('month');
        if($month != '') {
            $date = '01-'.$month;
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

		if(permissionChecker('salaryreport')) {
			if($_POST) {
				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);

			    if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
                    $to      = $this->input->post('to');
                    $subject = $this->input->post('subject');
                    $message = $this->input->post('message');

                    $usertypeID= $this->input->post('usertypeID');
                    $userID    = $this->input->post('userID');
                    $month     = $this->input->post('month');
                    $fromdate  = $this->input->post('fromdate');
                    $todate    = $this->input->post('todate');

                    $postArray = [];
                    $postArray['usertypeID'] = $usertypeID;
                    $postArray['userID'] = $userID;
                    if($month != '') {
                        $monthDate = date('d-m-Y',strtotime($month));
                        $monthArray = explode('-',$monthDate);
                        $postArray['month'] = $monthArray[1].'-'.$monthArray[2];
                    }

                    if($fromdate !='' && $todate != '') {
                        $postArray['fromdate']  = date('d-m-Y',strtotime($fromdate));
                        $postArray['todate']    = date('d-m-Y',strtotime($todate));
                    }

                    $this->data['usertypeID']= $usertypeID;
                    $this->data['userID']    = $userID;
                    $this->data['month']     = strtotime($month);
                    $this->data['fromdate']  = strtotime($fromdate);
                    $this->data['todate']    = strtotime($todate);

                    $this->data['allUserName'] = getAllUserObjectWithoutStudent();
                    $this->data['usertypes']   = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
                    $this->data['salarys'] = $this->make_payment_m->get_all_salary_for_report($postArray);

                    $this->reportSendToMail('salaryreport.css', $this->data, 'report/salary/SalaryReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
					$retArray['message'] = 'Success';
					echo json_encode($retArray);
			    	exit;
				}
			} else {
				$retArray['status'] = FALSE;
				$retArray['message'] = $this->lang->line('salaryreport_permissionmethod');
				echo json_encode($retArray);
		    	exit;
			}
		} else {
			$retArray['status'] = FALSE;
			$retArray['message'] = $this->lang->line('salaryreport_permission');
			echo json_encode($retArray);
	    	exit;
		}
	}
}

?>