<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Searchpaymentfeesreport extends Admin_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('payment_m');
        $this->load->model('student_m');
        $this->load->model('feetypes_m');
        $this->load->model('studentgroup_m');
        $this->load->model('weaverandfine_m');
		$this->load->model('studentrelation_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('searchpaymentfeesreport', $language);
	}

	public function rules() {
		$rules = array(
	        array(
                'field' => 'gspaymentID',
                'label' => $this->lang->line('searchpaymentfeesreport_gspaymentID'),
                'rules' => 'trim|required|xss_clean|callback_check_invoiceID'
	        ),
		);
		return $rules;
	}

	public function send_pdf_to_mail_rules() {
		$rules = array(
            array(
                'field' => 'gspaymentID',
                'label' => $this->lang->line('searchpaymentfeesreport_gspaymentID'),
                'rules' => 'trim|required|xss_clean|callback_check_invoiceID'
            ),
	        array(
                'field' => 'to',
                'label' => $this->lang->line('searchpaymentfeesreport_to'),
                'rules' => 'trim|required|xss_clean|valid_email'
	        ),
	        array(
                'field' => 'subject',
                'label' => $this->lang->line('searchpaymentfeesreport_subject'),
                'rules' => 'trim|required|xss_clean'
	        ),
	        array(
                'field' => 'message',
                'label' => $this->lang->line('searchpaymentfeesreport_message'),
                'rules' => 'trim|xss_clean'
	        )
		);
		return $rules;
	}

	public function index() {
		$this->data["subview"] = "report/searchpaymentfees/SearchPaymentFeesReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getSearchPaymentFeesReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('searchpaymentfeesreport')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $this->data['gspaymentID'] = $this->input->post('gspaymentID');
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');

                    $gspaymentIDstring =  $this->data['gspaymentID'];
                    $count = strlen($gspaymentIDstring);
                    $gspayment =  substr($gspaymentIDstring,  -$count, 6);
                    $gspaymentID =  substr($gspaymentIDstring, 6,$count);
                    $this->data['gspayment'] = $gspayment;

                    if((int)$gspaymentID && (($gspayment == 'INV-G-') || ($gspayment == 'inv-g-'))) {
                        $this->data['globalpayments'] = $this->payment_m->get_globalpayments($gspaymentID);
                        $globalpayments = $this->data['globalpayments'];
                        if(count($globalpayments)) {
                            $studentID = $globalpayments[0]->studentID;
                            $globalschoolyearID = $globalpayments[0]->schoolyearID;
                            $this->data['studentinfo'] = $this->studentrelation_m->get_single_studentrelation(array('srschoolyearID' => $globalschoolyearID, 'srstudentID' => $studentID));
                            $this->data['groups'] = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
                            $this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
                            $this->data['weaverandfines'] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('globalpaymentID'=>$gspaymentID)),'obj','paymentID');
                        }
						
                        $retArray['render'] = $this->load->view('report/searchpaymentfees/SearchPaymentFeesReport',$this->data,true);
                        $retArray['status'] = TRUE;
                        echo json_encode($retArray);
                        exit;
                    } elseif((int)$gspaymentID && ($gspayment == 'INV-S-') || ($gspayment == 'inv-s-')) {
                        $this->data["student"]     = [];
                        $this->data['studentinfo'] = [];
                        $this->data["invoice"]     = [];

                    	$this->data['singlepayments'] = $this->payment_m->get_single_payment_by_globalpaymentID($gspaymentID);
                        if(count($this->data['singlepayments'])) {
                            $studentID = $this->data['singlepayments']['0']->studentID;
                            $globalschoolyearID = $this->data['singlepayments']['0']->schoolyearID;
                            $this->data['globalpaymentID']  = $gspaymentID;
                            $this->data['paymenttype'] = $this->data['singlepayments']['0']->paymenttype;
                            $this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
                            $this->data['studentinfo'] = $this->studentrelation_m->get_single_studentrelation(array('srschoolyearID' => $globalschoolyearID, 'srstudentID' => $studentID));
                            $this->data["student"] = $this->student_m->get_single_student(array('studentID' => $studentID));
                        }

                        $retArray['render'] = $this->load->view('report/searchpaymentfees/SearchPaymentFeesReport',$this->data,true);
                        $retArray['status'] = TRUE;
                        echo json_encode($retArray);
                        exit;
                    } else {
                        echo json_encode($retArray);
                        exit;
                    }
                }
			} else {
				$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
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
		if(permissionChecker('searchpaymentfeesreport')) {
			$urlgspaymentID = htmlentities(escapeString($this->uri->segment(3)));
            $gspaymentIDstring = $urlgspaymentID;
            $count = strlen($gspaymentIDstring);
            $gspayment =  substr($gspaymentIDstring,  -$count, 6);
            $gspaymentID =  substr($gspaymentIDstring, 6,$count);

            $this->data['gspayment'] = $gspayment;
            $this->data['gspaymentID'] = $gspaymentID;
            $schoolyearID = $this->session->userdata('defaultschoolyearID');

            if((int)$gspaymentID && (($gspayment == 'INV-G-') || ($gspayment == 'inv-g-'))) {
                $this->data['globalpayments'] = $this->payment_m->get_globalpayments($gspaymentID);
                $globalpayments = $this->data['globalpayments'];
                if(count($globalpayments)) {
                    $studentID = $globalpayments[0]->studentID;
                    $globalschoolyearID = $globalpayments[0]->schoolyearID;
                    $this->data['studentinfo'] = $this->studentrelation_m->get_single_studentrelation(array('srschoolyearID' => $globalschoolyearID, 'srstudentID' => $studentID));
                    $this->data['groups'] = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
                    $this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
                    $this->data['weaverandfines'] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('globalpaymentID'=>$gspaymentID)),'obj','paymentID');
                }
            } elseif ((int)$gspaymentID && (($gspayment == 'INV-S-') || ($gspayment == 'inv-s-'))) {
                $this->data["student"]     = [];
                $this->data['studentinfo'] = [];
                $this->data["invoice"]     = [];

                $this->data['singlepayments'] = $this->payment_m->get_single_payment_by_globalpaymentID($gspaymentID);
                if(count($this->data['singlepayments'])) {
                    $studentID = $this->data['singlepayments']['0']->studentID;
                    $globalschoolyearID = $this->data['singlepayments']['0']->schoolyearID;
                    $this->data['globalpaymentID']  = $gspaymentID;
                    $this->data['paymenttype'] = $this->data['singlepayments']['0']->paymenttype;
                    $this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');

                    $this->data['studentinfo'] = $this->studentrelation_m->get_single_studentrelation(array('srschoolyearID' => $globalschoolyearID, 'srstudentID' => $studentID));
                    $this->data["student"] = $this->student_m->get_single_student(array('studentID' => $studentID));
                }
            }
            $this->reportPDF('searchpaymentfeesreport.css', $this->data, 'report/searchpaymentfees/SearchPaymentFeesReportPDF');
        } else {
            $this->data["subview"] = "errorpermission";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        if(permissionChecker('searchpaymentfeesreport')) {
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
                    $gspaymentIDstring =  $this->input->post('gspaymentID');
                    $count = strlen($gspaymentIDstring);
                    $gspayment =  substr($gspaymentIDstring,  -$count, 6);
                    $gspaymentID =  substr($gspaymentIDstring, 6,$count);
                    $this->data['gspayment'] = $gspayment;
                    $this->data['gspaymentID'] = $gspaymentID;

                    if((int)$gspaymentID && (($gspayment == 'INV-G-') || ($gspayment == 'inv-g-'))) {
                        $this->data['globalpayments'] = $this->payment_m->get_globalpayments($gspaymentID);
                        $globalpayments = $this->data['globalpayments'];
                        if(count($globalpayments)) {
                            $studentID = $globalpayments[0]->studentID;
                            $globalschoolyearID = $globalpayments[0]->schoolyearID;
                            $this->data['studentinfo'] = $this->studentrelation_m->get_single_studentrelation(array('srschoolyearID' => $globalschoolyearID, 'srstudentID' => $studentID));
                            $this->data['groups'] = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
                            $this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');
                            $this->data['weaverandfines'] = pluck($this->weaverandfine_m->get_order_by_weaverandfine(array('globalpaymentID'=>$gspaymentID)),'obj','paymentID');
                        }
                    } elseif ((int)$gspaymentID && (($gspayment == 'INV-S-') || ($gspayment == 'inv-s-'))) {
                        $this->data["student"]     = [];
                        $this->data['studentinfo'] = [];
                        $this->data["invoice"]     = [];

                        $this->data['singlepayments'] = $this->payment_m->get_single_payment_by_globalpaymentID($gspaymentID);
                        if(count($this->data['singlepayments'])) {
                            $studentID = $this->data['singlepayments']['0']->studentID;
                            $globalschoolyearID = $this->data['singlepayments']['0']->schoolyearID;
                            $this->data['globalpaymentID']  = $gspaymentID;
                            $this->data['paymenttype'] = $this->data['singlepayments']['0']->paymenttype;
                            $this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(),'feetypes','feetypesID');

                            $this->data['studentinfo'] = $this->studentrelation_m->get_single_studentrelation(array('srschoolyearID' => $globalschoolyearID, 'srstudentID' => $studentID));
                            $this->data["student"] = $this->student_m->get_single_student(array('studentID' => $studentID));
                        }
                    }

                    $this->reportSendToMail('searchpaymentfeesreport.css', $this->data, 'report/searchpaymentfees/SearchPaymentFeesReportPDF', $to, $subject, $message);
                    $retArray['status'] = TRUE;
                    echo json_encode($retArray);
                }
            } else {
                $retArray['message'] = $this->lang->line('searchpaymentfeesreport_permissionmethod');
                echo json_encode($retArray);
                exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('searchpaymentfeesreport_permission');
			echo json_encode($retArray);
			exit;
		}
	}

    public function check_invoiceID() {
    	$gspaymentID = trim($this->input->post('gspaymentID'));
    	if($gspaymentID != '') {
    		$count = strlen($gspaymentID);
            $gspayment =  substr($gspaymentID, -$count, 6);
            $gspaymentID =  substr($gspaymentID, 6,$count);
            if($count <= 6) {
            	$this->form_validation->set_message('check_invoiceID', 'The %s field is InvalidD.');
				return FALSE;
	        } elseif((($gspayment == "INV-G-") || ($gspayment == "inv-g-")) && is_numeric($gspaymentID)) {
	            return TRUE;
	        } elseif((($gspayment == "INV-S-") || ($gspayment == "inv-s-")) && is_numeric($gspaymentID)) {
	            return TRUE;
	        } else {
	        	$this->form_validation->set_message('check_invoiceID', 'The %s field is Invalid.');
				return FALSE;
	        }
    	} else {
    		return TRUE;
    	}
    }

}
