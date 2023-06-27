<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Accountledgerreport extends Admin_Controller {
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
		$this->load->model('income_m');
		$this->load->model('expense_m');
		$this->load->model('make_payment_m');
		$this->load->model('payment_m');
		$this->load->model('schoolyear_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('accountledgerreport', $language);
	}
	
	protected function rules() {
		$rules = array(
			array(
				'field' => 'schoolyearID',
				'label' => $this->lang->line("accountledgerreport_academicyear"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'fromdate',
				'label' => $this->lang->line("accountledgerreport_fromdate"),
				'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
			),
			array(
				'field' => 'todate',
				'label' => $this->lang->line("accountledgerreport_todate"),
				'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
			),
		);
		return $rules;
	} 

	protected function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field' => 'schoolyearID',
				'label' => $this->lang->line("accountledgerreport_academicyear"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'fromdate',
				'label' => $this->lang->line("accountledgerreport_fromdate"),
				'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
			),
			array(
				'field' => 'todate',
				'label' => $this->lang->line("accountledgerreport_todate"),
				'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line("accountledgerreport_to"),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("accountledgerreport_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("accountledgerreport_message"),
				'rules' => 'trim|xss_clean'
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
		
		$this->data['schoolyears'] = $this->schoolyear_m->get_order_by_schoolyear();
		$this->data["subview"] = "report/accountledger/AccountledgerReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function queryArray($array) {
		$retArray = [];
		if(!empty($array['fromdate']) && !empty($array['todate'])) {
			$retArray['fromdate']    = date('Y-m-d',strtotime($array['fromdate']));	
			$retArray['todate']      = date('Y-m-d',strtotime($array['todate']));
		}

		if(!empty($array['schoolyearID'])) {
			$retArray['schoolyearID']= $array['schoolyearID'];
		}
		$schoolyear = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID'=>$array['schoolyearID']));
		$this->data['schoolyearName'] = count($schoolyear) ? $schoolyear->schoolyear : $this->lang->line('accountledgerreport_all_accademic_year');
		return $retArray;
	}

	public function getaccountledgerreport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('accountledgerreport')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {

					$array = $this->queryArray($_POST);

					$totalincome        = $this->income_m->get_income_order_by_with_date_schoolyear($array);
					$totalexpense       = $this->expense_m->get_expense_order_with_date_schoolyear($array);
					$totalsalarypayment = $this->make_payment_m->get_payment_salary_with_date_schoolyear($array);

					$payments = $this->payment_m->get_payment_with_fine_schoolyear($array);
					$totalcollection = 0;
					$totalfine    = 0;
					if(count($payments)) {
						foreach ($payments as $payment) {
							if($payment->paymentamount != null) {
								$totalcollection += $payment->paymentamount;
							}
							if($payment->fine != null) {
								$totalfine += $payment->fine;
							}
						}
					}

					$this->data['totalincome']   = $totalincome->amount;
					$this->data['totalexpense']  = $totalexpense->amount;
					$this->data['totalsalarypayment']  = $totalsalarypayment->payment_amount;
					$this->data['totalcollection']  = $totalcollection;
					$this->data['totalfine']     = $totalfine;

					$this->data['fromdate'] = $this->input->post('fromdate');
					$this->data['todate']   = $this->input->post('todate');
					$this->data['schoolyearID']   = $this->input->post('schoolyearID');

					$retArray['render'] = $this->load->view('report/accountledger/AccountledgerReport', $this->data, true);
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
		if(permissionChecker('accountledgerreport')) {
			$schoolyearID = htmlentities(escapeString($this->uri->segment(3)));
			$fromdate = htmlentities(escapeString($this->uri->segment(4)));
			$todate  = htmlentities(escapeString($this->uri->segment(5)));
			if(((int)$schoolyearID || ($schoolyearID ==0)) && ((int)$fromdate || ($fromdate == null)) && ((int)$todate || $todate == null)) {
				$postArray = [];
				if((int)$fromdate && (int) $todate) {
					$postArray['fromdate'] = date('d-m-Y',$fromdate);
					$postArray['todate'] = date('d-m-Y',$todate);
				}
				$postArray['schoolyearID'] = $schoolyearID;

				$array = $this->queryArray($postArray);

				$totalincome        = $this->income_m->get_income_order_by_with_date_schoolyear($array);
				$totalexpense       = $this->expense_m->get_expense_order_with_date_schoolyear($array);
				$totalsalarypayment = $this->make_payment_m->get_payment_salary_with_date_schoolyear($array);

				$payments = $this->payment_m->get_payment_with_fine_schoolyear($array);
				$totalcollection = 0;
				$totalfine    = 0;
				if(count($payments)) {
					foreach ($payments as $payment) {
						if($payment->paymentamount != null) {
							$totalcollection += $payment->paymentamount;
						}
						if($payment->fine != null) {
							$totalfine += $payment->fine;
						}
					}
				}

				$this->data['totalincome']   = $totalincome->amount;
				$this->data['totalexpense']  = $totalexpense->amount;
				$this->data['totalsalarypayment']  = $totalsalarypayment->payment_amount;
				$this->data['totalcollection']  = $totalcollection;
				$this->data['totalfine']     = $totalfine;

				$this->data['fromdate'] = $fromdate;
				$this->data['todate']   = $todate;

				$this->reportPDF('accountledgerreport.css',$this->data,'report/accountledger/AccountledgerReportPDF');
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
		$retArray['message'] = '';

		if(permissionChecker('accountledgerreport')) {
			if($_POST) {
				$to      = $this->input->post('to');
				$subject = $this->input->post('subject');
				$message = $this->input->post('message');

				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$array = $this->queryArray($_POST);

					$totalincome        = $this->income_m->get_income_order_by_with_date_schoolyear($array);
					$totalexpense       = $this->expense_m->get_expense_order_with_date_schoolyear($array);
					$totalsalarypayment = $this->make_payment_m->get_payment_salary_with_date_schoolyear($array);

					$payments = $this->payment_m->get_payment_with_fine_schoolyear($array);
					$totalcollection = 0;
					$totalfine    = 0;
					if(count($payments)) {
						foreach ($payments as $payment) {
							if($payment->paymentamount != null) {
								$totalcollection += $payment->paymentamount;
							}
							if($payment->fine != null) {
								$totalfine += $payment->fine;
							}
						}
					}

					$this->data['totalincome']   = $totalincome->amount;
					$this->data['totalexpense']  = $totalexpense->amount;
					$this->data['totalsalarypayment']  = $totalsalarypayment->payment_amount;
					$this->data['totalcollection']  = $totalcollection;
					$this->data['totalfine']     = $totalfine;

					$this->data['fromdate'] = $this->input->post('fromdate');
					$this->data['todate']   = $this->input->post('todate');
					$this->data['schoolyearID']   = $this->input->post('schoolyearID');
					$this->reportSendToMail('accountledgerreport.css', $this->data, 'report/accountledger/AccountledgerReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
				    echo json_encode($retArray);
				    exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('accountledgerreport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('accountledgerreport_permission');
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
			return TRUE;
		} elseif($fromdate == '' && $todate != '') {
			$this->form_validation->set_message("unique_date", "The to date are invalid .");
			return FALSE;
		} elseif($fromdate != '' && $todate == '') {
			$this->form_validation->set_message("unique_date", "The to date are invalid .");
			return FALSE;
		}
		return TRUE;
	}
}
