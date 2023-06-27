<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Onlineadmissionreport extends Admin_Controller {
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
		$this->load->model('schoolyear_m');
		$this->load->model('onlineadmission_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('onlineadmissionreport', $language);
	}
	
	protected function rules() {
		$rules = array(
			array(
				'field' => 'schoolyearID',
				'label' => $this->lang->line("onlineadmissionreport_academicyear"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("onlineadmissionreport_class"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'status',
				'label' => $this->lang->line("onlineadmissionreport_status"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("onlineadmissionreport_phone"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'admissionID',
				'label' => $this->lang->line("onlineadmissionreport_admissionID"),
				'rules' => 'trim|xss_clean|numeric'
			)
		);
		return $rules;
	} 



	protected function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field' => 'schoolyearID',
				'label' => $this->lang->line("onlineadmissionreport_academicyear"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("onlineadmissionreport_class"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'status',
				'label' => $this->lang->line("onlineadmissionreport_status"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("onlineadmissionreport_phone"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'admissionID',
				'label' => $this->lang->line("onlineadmissionreport_admissionID"),
				'rules' => 'trim|xss_clean|numeric'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line("onlineadmissionreport_to"),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("onlineadmissionreport_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("onlineadmissionreport_message"),
				'rules' => 'trim|xss_clean'
			),
		);
		return $rules;
	} 

 	public function index() {
 		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		
		$this->data['schoolyears'] = $this->schoolyear_m->get_order_by_schoolyear();
		$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data["subview"] = "report/onlineadmission/OnlineadmissionReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function queryArray($array) {
		$retArray = [];
		if(!empty($array['admissionID'])) {
			$retArray['onlineadmissionID']= abs($array['admissionID']);
		} else {
			$retArray['schoolyearID']= $array['schoolyearID'];
			if(!empty($array['classesID']) && (int)$array['classesID']) {
				$retArray['classesID']= $array['classesID'];
			}

			if(isset($array['status'])) {
				if($array['status'] != '10') {
					$retArray['status'] = $array['status'];
				}
			}
		}

		$schoolyear = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID'=>$array['schoolyearID']));
		$this->data['schoolyearName'] = count($schoolyear) ? $schoolyear->schoolyear : '';
		$this->data['checkstatus'] = $this->checkstatus();
		$this->data['classes'] = pluck($this->classes_m->general_get_classes(),'classes','classesID');
		$this->data['onlineadmissions'] = $this->onlineadmission_m->get_order_by_onlineadmission($retArray);
	}

	public function getonlineadmissionreport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('onlineadmissionreport')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->queryArray($_POST);
					$this->data['schoolyearID'] = $this->input->post('schoolyearID');
					$this->data['classesID'] = $this->input->post('classesID');
					$this->data['status'] = $this->input->post('status');
					$this->data['phone'] = $this->input->post('phone');
					$this->data['admissionID'] = !empty($this->input->post('admissionID')) ? $this->input->post('admissionID') : '0';
					
					$retArray['render'] = $this->load->view('report/onlineadmission/OnlineadmissionReport', $this->data, true);
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
		if(permissionChecker('onlineadmissionreport')) {
			$schoolyearID = htmlentities(escapeString($this->uri->segment(3)));
			$classesID    = htmlentities(escapeString($this->uri->segment(4)));
			$status       = htmlentities(escapeString($this->uri->segment(5)));
			$phone        = htmlentities(escapeString($this->uri->segment(6)));
			$admissionID  = htmlentities(escapeString($this->uri->segment(7)));

			if(((int)$schoolyearID) && ((int)$classesID || ($classesID == 0)) && ((int)$status || ($status == 0)) && ((int)$phone || ($phone == 0)) && ((int)$admissionID || ($admissionID == 0))) {

				$postArray = [];
				$postArray['schoolyearID'] = $schoolyearID;
				$postArray['classesID'] = $classesID;
				$postArray['status'] = $status;
				$postArray['admissionID'] = $admissionID;

				$array = $this->queryArray($postArray);

				$this->data['schoolyearID']= $schoolyearID;
				$this->data['classesID']   = $classesID;
				$this->data['status']      = $status;
				$this->data['phone']       = $phone;
				$this->data['admissionID'] = $admissionID;
				$this->reportPDF('onlineadmissionreport.css',$this->data,'report/onlineadmission/OnlineadmissionReportPDF');
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

		if(permissionChecker('onlineadmissionreport')) {
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

					$this->data['schoolyearID'] = $this->input->post('schoolyearID');
					$this->data['classesID'] = $this->input->post('classesID');
					$this->data['status'] = $this->input->post('status');
					$this->data['phone'] = $this->input->post('phone');
					$this->data['admissionID'] = !empty($this->input->post('admissionID')) ? $this->input->post('admissionID') : '0';

					$this->reportSendToMail('onlineadmissionreport.css', $this->data, 'report/onlineadmission/OnlineadmissionReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
				    echo json_encode($retArray);
				    exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('onlineadmissionreport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('onlineadmissionreport_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function unique_data($data) {
		if($data == "0") {
			$this->form_validation->set_message('unique_data', 'The %s field is required.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	private function checkstatus() {
		$retArray['0'] = $this->lang->line('onlineadmissionreport_new');
		$retArray['1'] = $this->lang->line('onlineadmissionreport_approved');
		$retArray['2'] = $this->lang->line('onlineadmissionreport_waiting');
		$retArray['3'] = $this->lang->line('onlineadmissionreport_decline');
		$retArray['10'] = $this->lang->line('onlineadmissionreport_all_applicant');
		return $retArray;
	}

}
