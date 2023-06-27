<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paymenthistory extends Admin_Controller {
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
		$this->load->model('feetypes_m');
		$this->load->model('invoice_m');
		$this->load->model('payment_m');
		$this->load->model('student_m');
		$this->load->model('parents_m');
		$this->load->model('maininvoice_m');
		$this->load->model('weaverandfine_m');
		$this->load->model('studentrelation_m');
		$this->load->model('studentrelation_m');
		$this->load->model('globalpayment_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('paymenthistory', $language);	
	}

	protected function payment_rules() {
		$usertypeID = $this->session->userdata('usertypeID');
		$rules = array(
			array(
				'field' => 'amount',
				'label' => $this->lang->line("paymenthistory_amount"),
				'rules' => 'trim|required|xss_clean|max_length[11]|numeric|callback_valid_number|callback_unique_amount'
			),
			array(
				'field' => 'payment_method',
				'label' => $this->lang->line("paymenthistory_paymentmethod"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_paymentmethod'
			)
		);
		return $rules;
	}

	public function index() {
		$usertypeID = $this->session->userdata('usertypeID');
		$userID = $this->session->userdata('loginuserID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if($usertypeID == 3) {
			$this->data['payments'] = $this->payment_m->get_payment_with_studentrelation_by_studentID($userID, $schoolyearID);
			$this->data["subview"] = "paymenthistory/index_parents";
			$this->load->view('_layout_main', $this->data);
		} elseif($usertypeID == 4) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);

			$students = $this->studentrelation_m->get_order_by_student(array('parentID' => $userID, 'schoolyearID' => $schoolyearID));
			if(count($students)) {
				$studentArray = pluck($students, 'srstudentID');
				$this->data['payments'] = [];
				$this->data['payments'] = $this->payment_m->get_payment_with_studentrelation_by_studentID($studentArray, $schoolyearID);
				$this->data["subview"] = "paymenthistory/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['payments'] = [];
				$this->data["subview"] = "paymenthistory/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data['payments'] = $this->payment_m->get_payment_with_studentrelation($schoolyearID);
			$this->data["subview"] = "paymenthistory/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);

			$id = htmlentities(escapeString($this->uri->segment(3)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$id) {
				$this->data['payment'] = $this->payment_m->get_single_payment(array('paymentID' => $id, 'paymentamount !=' => NULL, 'schoolyearID' => $schoolyearID));
				if(count($this->data['payment'])) {
					if(($this->data['payment']->paymenttype != "Paypal") && ($this->data['payment']->paymenttype != 'Stripe') && ($this->data['payment']->paymenttype != 'Payumoney') && ($this->data['payment']->paymenttype != 'Voguepay')) {
						$this->data['invoice'] = $this->invoice_m->get_invoice($this->data['payment']->invoiceID);
						if(count($this->data['invoice'])) {
							if($_POST) {
								$rules = $this->payment_rules();
								$this->form_validation->set_rules($rules);
								if ($this->form_validation->run() == FALSE) {
									$this->data["subview"] = "paymenthistory/edit";
									$this->load->view('_layout_main', $this->data);
								} else {
									$maininvoicestatus = 0;
									$invoicepaidstatus = 0;
									$schoolyearID = $this->data['invoice']->schoolyearID;
									$maininvoice = $this->maininvoice_m->get_single_maininvoice(array('maininvoiceID' => $this->data['invoice']->maininvoiceID));
									if(count($maininvoice)) {
										$grandtotalandpayment = $this->grandtotalandpaidsingle($maininvoice, $schoolyearID, $this->data['invoice']->studentID);
										$paymentsum = $this->payment_m->get_payment_by_sum_for_edit($this->data['payment']->invoiceID, $id);
										$weaversum = $this->weaverandfine_m->get_sum_weaverandfine('weaver', array('invoiceID' => $this->data['invoice']->invoiceID));
										
										$amountcalculation = (($grandtotalandpayment['grandtotal'] + $this->data['payment']->paymentamount) - ($grandtotalandpayment['totalpayment'] + $grandtotalandpayment['totalweaver'] + $this->input->post('amount')));
										
										$duecalculation = ($this->data['invoice']->amount - ((($this->data['invoice']->amount/100) * $this->data['invoice']->discount) + $paymentsum->paymentamount + $weaversum->weaver));

										if($amountcalculation <= 0) {
											$maininvoicestatus = 2;
										} else {
											$maininvoicestatus = 1;
										}

										if($this->input->post('amount') >= $duecalculation) {
											$invoicepaidstatus = 2;
										} else {
											$invoicepaidstatus = 1;
										}

										$paymentArray = array(
											'paymentamount' => $this->input->post('amount'),
											'paymentdate' => date('Y-m-d'),
											'userID' => $this->session->userdata('loginuserID'),
											'usertypeID' => $this->session->userdata('usertypeID'), 
											'uname' => $this->session->userdata('name'),
										);

										$this->payment_m->update_payment($paymentArray, $id);
										$this->invoice_m->update_invoice(array('paidstatus' => $invoicepaidstatus), $this->data['invoice']->invoiceID);
										$this->maininvoice_m->update_maininvoice(array('maininvoicestatus' => $maininvoicestatus), $this->data['invoice']->maininvoiceID);
										$this->session->set_flashdata('success', $this->lang->line('menu_success'));
										redirect(base_url("paymenthistory/index"));
									} else {
										$this->session->set_flashdata('error', 'invoice data does not found');
										redirect(base_url("paymenthistory/index"));
									}
								}
							} else {
								$this->data["subview"] = "paymenthistory/edit";
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
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$id) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$singlePayment = $this->payment_m->get_single_payment(array('paymentID' => $id, 'paymentamount !=' => NULL, 'schoolyearID' => $schoolyearID));
				if(count($singlePayment)) {
					if(($singlePayment->paymenttype != "Paypal") && ($singlePayment->paymenttype != 'Stripe') && ($singlePayment->paymenttype != 'PayUmoney') && ($singlePayment->paymenttype != 'Voguepay')) {
						$singleInvoice = $this->invoice_m->get_invoice($singlePayment->invoiceID);
						if(count($singleInvoice)) {

							$mainInvoiceStatus = 0;
							$invoicePaidStatus = 0;
							$singleWeaverAmount = 0;
							$singleFineAmount = 0;
							$schoolyearID = $singleInvoice->schoolyearID;

							$singleMainInvoice = $this->maininvoice_m->get_single_maininvoice(array('maininvoiceID' => $singleInvoice->maininvoiceID));
							$invoicePaymentHistory = $this->grandtotalandpaidsingle($singleMainInvoice, $schoolyearID, $singleInvoice->studentID);
							$paymentSum = $this->payment_m->get_payment_sum('paymentamount', array('invoiceID' => $singleInvoice->invoiceID));
							$weaverSum = $this->weaverandfine_m->get_sum_weaverandfine('weaver', array('invoiceID' => $singleInvoice->invoiceID));

							$singleWeaverFine = $this->weaverandfine_m->get_single_weaverandfine(array('paymentID' => $id));
							
							if(count($singleWeaverFine)) {
								$singleWeaverAmount = $singleWeaverFine->weaver;
								$singleFineAmount = $singleWeaverFine->fine;
							}
							
							$extraPaymentWeaverAmount = (($paymentSum->paymentamount+$weaverSum->weaver) - ($singlePayment->paymentamount+$singleWeaverAmount));

							if($singleWeaverAmount > 0) {
								$invoicePaidStatus = 1;
							} else {
								if($extraPaymentWeaverAmount > 0) {
									$invoicePaidStatus = 1;
								}
							}

							$grandTotalAmount = $invoicePaymentHistory['grandtotal'];
							$grandTotalPaymentAmount = $invoicePaymentHistory['totalpayment'];
							$grandTotalWeaverAmount = $invoicePaymentHistory['totalweaver'];
							$grandTotalFineAmount = $invoicePaymentHistory['totalfine'];
							$extraPaymentAmount = ($grandTotalAmount - $singlePayment->paymentamount);

							if($grandTotalWeaverAmount > 0 || $grandTotalFineAmount > 0) {
								$mainInvoiceStatus = 1;
							} else {
								if(($grandTotalPaymentAmount - $singlePayment->paymentamount) == 0) {
									$mainInvoiceStatus = 0;
								} else {
									$mainInvoiceStatus = 1;
								}
							}


							$this->payment_m->update_payment(array('paymentamount' => NULL), $singlePayment->paymentID);
							$this->invoice_m->update_invoice(array('paidstatus' => $invoicePaidStatus), $singleInvoice->invoiceID);
							$this->maininvoice_m->update_maininvoice(array('maininvoicestatus' => $mainInvoiceStatus), $singleInvoice->maininvoiceID);

							$globalID = $singlePayment->globalpaymentID;
							if($globalID) {
								$payments = $this->payment_m->get_order_by_payment(array('paymentamount != ' => NULL, 'globalpaymentID' => $globalID));
								$weaverFines = $this->weaverandfine_m->get_order_by_weaverandfine(array('globalpaymentID' => $globalID));

								if(!count($payments) && !count($weaverFines)) {
									$payments = $this->payment_m->get_order_by_payment(array('globalpaymentID' => $globalID));
									$paymentArray = pluck($payments, 'paymentID');
									$this->payment_m->delete_batch_payment($paymentArray);
									$this->globalpayment_m->delete_globalpayment($globalID);
								}
							}

							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("paymenthistory/index"));
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

	public function valid_number() {
		if($this->input->post('amount') != 0) {
			if($this->input->post('amount') && $this->input->post('amount') < 0) {
				$this->form_validation->set_message("valid_number", "%s is invalid number");
				return FALSE;
			}
			return TRUE;
		} else {
			$this->form_validation->set_message("valid_number", "Give me valid amount not zero");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_amount() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$this->data['payment'] = $this->payment_m->get_payment($id);
		if(count($this->data['payment'])) {
			$this->data['invoice'] = $this->invoice_m->get_single_invoice(array('invoiceID' => $this->data['payment']->invoiceID));
			if(count($this->data['invoice'])) {
				$this->data['getDbPayment'] = $this->payment_m->get_payment_by_sum_for_edit($this->data['payment']->invoiceID, $id);
				$this->data['weaverandfine'] = $this->weaverandfine_m->get_sum_weaverandfine('weaver', array('invoiceID' => $this->data['invoice']->invoiceID));
				$this->data['dueamount'] = ($this->data['invoice']->amount - ((($this->data['invoice']->amount/100) * $this->data['invoice']->discount) + $this->data['getDbPayment']->paymentamount + $this->data['weaverandfine']->weaver));
				if($this->input->post('amount') > $this->data['dueamount']) {
					$this->form_validation->set_message("unique_amount", "The %s is greater than of due amount");
					return FALSE;
				}
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		} 
	}

	public function unique_paymentmethod() {
		if($this->input->post('payment_method') === '0') {
			$this->form_validation->set_message("unique_paymentmethod", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	private function grandtotalandpaidsingle($maininvoice, $schoolyearID, $studentID = NULL) {
    	$retArray = ['grandtotal' => 0, 'totalamount' => 0, 'totaldiscount' => 0, 'totalpayment' => 0, 'totalfine' => 0, 'totalweaver' => 0];
        if(count($maininvoice)) {
	    	if((int)$studentID && $studentID != NULL) {
		        $invoiceitems = pluck_multi_array_key($this->invoice_m->get_order_by_invoice(array('studentID' => $studentID, 'maininvoiceID' => $maininvoice->maininvoiceID,  'schoolyearID' => $schoolyearID)), 'obj', 'maininvoiceID', 'invoiceID');
		        $paymentitems = pluck_multi_array($this->payment_m->get_order_by_payment(array('schoolyearID' => $schoolyearID, 'paymentamount !=' => NULL)), 'obj', 'invoiceID');
		        $weaverandfineitems = pluck_multi_array($this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID' => $schoolyearID)), 'obj', 'invoiceID');
	    	} else {
	    		$invoiceitem = [];
	    		$paymentitems = [];
	    		$weaverandfineitems = [];
	    	}

    		if(isset($invoiceitems[$maininvoice->maininvoiceID])) {
    			if(count($invoiceitems[$maininvoice->maininvoiceID])) {
    				foreach ($invoiceitems[$maininvoice->maininvoiceID] as $invoiceitem) {
    					$amount = $invoiceitem->amount;
    					if($invoiceitem->discount > 0) {
    						$amount = ($invoiceitem->amount - (($invoiceitem->amount/100) *$invoiceitem->discount));
    					}

    					if(isset($retArray['grandtotal'])) {
    						$retArray['grandtotal'] = ($retArray['grandtotal'] + $amount);
    					} else {
    						$retArray['grandtotal'] = $amount;
    					}

    					if(isset($retArray['totalamount'])) {
    						$retArray['totalamount'] = ($retArray['totalamount'] + $invoiceitem->amount);
    					} else {
    						$retArray['totalamount'] = $invoiceitem->amount;
    					}

    					if(isset($retArray['totaldiscount'])) {
    						$retArray['totaldiscount'] = ($retArray['totaldiscount'] + (($invoiceitem->amount/100) *$invoiceitem->discount));
    					} else {
    						$retArray['totaldiscount'] = (($invoiceitem->amount/100) *$invoiceitem->discount);
    					}

    					if(isset($paymentitems[$invoiceitem->invoiceID])) {
    						if(count($paymentitems[$invoiceitem->invoiceID])) {
    							foreach ($paymentitems[$invoiceitem->invoiceID] as $paymentitem) {
    								if(isset($retArray['totalpayment'])) {
    									$retArray['totalpayment'] = ($retArray['totalpayment'] + $paymentitem->paymentamount);
    								} else {
    									$retArray['totalpayment'] = $paymentitem->paymentamount;
    								}
    							}
    						}
    					}

    					if(isset($weaverandfineitems[$invoiceitem->invoiceID])) {
    						if(count($weaverandfineitems[$invoiceitem->invoiceID])) {
    							foreach ($weaverandfineitems[$invoiceitem->invoiceID] as $weaverandfineitem) {
    								if(isset($retArray['totalweaver'])) {
    									$retArray['totalweaver'] = ($retArray['totalweaver'] + $weaverandfineitem->weaver);
    								} else {
    									$retArray['totalweaver'] = $weaverandfineitem->weaver;
    								}

    								if(isset($retArray['totalfine'])) {
    									$retArray['totalfine'] = ($retArray['totalfine'] + $weaverandfineitem->fine);
    								} else {
    									$retArray['totalfine'] = $weaverandfineitem->fine;
    								}
    							}
    						}
    					}
    				}
    			}
    		}
        }

        return $retArray;
    }
}