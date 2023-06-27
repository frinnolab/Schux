<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productsalereport extends Admin_Controller {
	public function __construct() {
		parent::__construct();

        $this->load->model('usertype_m');
        $this->load->model('classes_m');
        $this->load->model('systemadmin_m');
        $this->load->model('teacher_m');
        $this->load->model("studentrelation_m");
        $this->load->model('parents_m');
        $this->load->model('user_m');
        $this->load->model("productcategory_m");
        $this->load->model("product_m");
        $this->load->model('productsale_m');
        $this->load->model("productsaleitem_m");
        $this->load->model("productsalepaid_m");
        $this->load->model("productpurchaseitem_m");

        $language = $this->session->userdata('lang');
		$this->lang->load('productsalereport', $language);
	}

	public function rules() {
		$rules = array(
	        array(
                'field' => 'productsalecustomertypeID',
                'label' => $this->lang->line('productsalereport_role'),
                'rules' => 'trim|xss_clean'
	        ),
            array(
                'field' => 'productsaleclassesID',
                'label' => $this->lang->line('productsalereport_classes'),
                'rules' => 'trim|xss_clean'
	        ),
            array(
                'field' => 'productsalecustomerID',
                'label' => $this->lang->line('productsalereport_user'),
                'rules' => 'trim|xss_clean'
	        ),
            array(
                'field' => 'reference_no',
                'label' => $this->lang->line('productsalereport_referenceNo'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line('productsalereport_status'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'fromdate',
                'label' => $this->lang->line('productsalereport_fromdate'),
                'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
            ),
            array(
                'field' => 'todate',
                'label' => $this->lang->line('productsalereport_todate'),
                'rules' => 'trim|xss_clean|callback_date_valid'
            )
        );
        return $rules;
    }

    public function send_pdf_to_mail_rules() {
        $rules = array(
	        array(
                'field' => 'to',
                'label' => $this->lang->line('productsalereport_to'),
                'rules' => 'trim|required|xss_clean|valid_email'
	        ),
	        array(
                'field' => 'subject',
                'label' => $this->lang->line('productsalereport_subject'),
                'rules' => 'trim|required|xss_clean'
	        ),
	        array(
                'field' => 'message',
                'label' => $this->lang->line('productsalereport_message'),
                'rules' => 'trim|xss_clean'
	        ),
            array(
                'field' => 'productsalecustomertypeID',
                'label' => $this->lang->line('productsalereport_role'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'productsaleclassesID',
                'label' => $this->lang->line('productsalereport_classes'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'productsalecustomerID',
                'label' => $this->lang->line('productsalereport_user'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'reference_no',
                'label' => $this->lang->line('productsalereport_referenceNo'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line('productsalereport_status'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'fromdate',
                'label' => $this->lang->line('productsalereport_fromdate'),
                'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
            ),
            array(
                'field' => 'todate',
                'label' => $this->lang->line('productsalereport_todate'),
                'rules' => 'trim|xss_clean|callback_date_valid'
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
        $this->data['usertypes'] = $this->usertype_m->get_usertype();
        $this->data['classes'] = $this->classes_m->general_get_classes();
        $this->data["subview"] = "report/productsale/ProductSaleReportView";
		$this->load->view('_layout_main', $this->data);
	}

    public function getProductSaleReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('productsalereport')) {
            
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
				    $retArray['status'] = FALSE;
				} else {
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');
                    $this->data['productsalecustomertypeID'] = $this->input->post('productsalecustomertypeID');
                    $this->data['productsaleclassesID'] = $this->input->post('productsaleclassesID');
                    $this->data['productsalecustomerID'] = $this->input->post('productsalecustomerID');
                    $this->data['reference_no'] = !empty($this->input->post('reference_no')) ? $this->input->post('reference_no') : '0';
                    $this->data['statusID'] = $this->input->post('statusID');
                    $this->data['fromdate'] = $this->input->post('fromdate');
					$this->data['todate'] = $this->input->post('todate');

                    $usertypes = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
                    $this->data['usertypes'] = $usertypes;
                    $users = $this->getuserlist($_POST);
                    $this->data['users'] = $users;
					$productsales = $this->productsale_m->get_all_productsale_for_report($this->input->post());
                    
                    $productsalepaidsArray = [];
                    $productsalepaids = $this->productsalepaid_m->get_order_by_productsalepaid(array('schoolyearID' => $schoolyearID));
                    if(count($productsalepaids)) {
                        foreach($productsalepaids as $productsalepaid) {
                            if(isset($productsalepaidsArray[$productsalepaid->productsaleID])) {
                                $productsalepaidsArray[$productsalepaid->productsaleID] += $productsalepaid->productsalepaidamount;
                            } else {
                                $productsalepaidsArray[$productsalepaid->productsaleID] = $productsalepaid->productsalepaidamount;
                            }
                        }
                    }

                    $productArray = [];
                    $totalproductsaleprice = 0;
                    $totalproductsalepaidamount = 0;
                    $totalproductsalebalanceamount = 0;
                    
                    $lCheck = FALSE;
                    if($this->data['productsalecustomertypeID'] == 3) {
                        $lCheck = TRUE;
                    }

                    if(count($productsales)) {
                        foreach($productsales as $product) {
                            if($lCheck) {
                                $classesID = (int)$this->data['productsaleclassesID'];
                                if($classesID) {
                                    if(!(isset($users[3][$product->productsalecustomerID]) && ($users[3][$product->productsalecustomerID]->srclassesID == $classesID))) {
                                        continue;
                                    }
                                }
                            }   

                            if(isset($productArray[$product->productsaleID])) {
                                $productsalebalanceamount = $productArray[$product->productsaleID]['productsalebalanceamount'];

                                $productArray[$product->productsaleID]['productsaleprice'] += ($product->productsaleunitprice * $product->productsalequantity);

                                $productArray[$product->productsaleID]['productsalebalanceamount'] = ($product->productsaleunitprice * $product->productsalequantity) + $productsalebalanceamount;
                            } else {
                                $productArray[$product->productsaleID]['productsaleID'] = $product->productsaleID;
                                $productArray[$product->productsaleID]['productname'] = $product->productname;
                                $productArray[$product->productsaleID]['productsalequantity'] = $product->productsalequantity;
                                $productArray[$product->productsaleID]['productsalereferenceno'] = $product->productsalereferenceno;
                                $productArray[$product->productsaleID]['productsalerefund'] = $product->productsalerefund;
                                $productArray[$product->productsaleID]['productsalecustomertype'] = isset($usertypes[$product->productsalecustomertypeID]) ? $usertypes[$product->productsalecustomertypeID] : '';
                                
                                $name = '';
                                if(isset($users[$product->productsalecustomertypeID][$product->productsalecustomerID])) {
                                    $name = isset($users[$product->productsalecustomertypeID][$product->productsalecustomerID]->name) ? $users[$product->productsalecustomertypeID][$product->productsalecustomerID]->name : $users[$product->productsalecustomertypeID][$product->productsalecustomerID]->srname;
                                }
                                $productArray[$product->productsaleID]['productsalecustomerName'] = $name;
                                

                                $productArray[$product->productsaleID]['productsaledate'] = date('d M Y', strtotime($product->productsaledate));
                                $productArray[$product->productsaleID]['productsaleprice'] = ($product->productsaleunitprice * $product->productsalequantity);
                                $productArray[$product->productsaleID]['productsalepaidamount'] = isset($productsalepaidsArray[$product->productsaleID]) ? $productsalepaidsArray[$product->productsaleID] : '0';
                                $productArray[$product->productsaleID]['productsalebalanceamount'] = ($productArray[$product->productsaleID]['productsaleprice'] - $productArray[$product->productsaleID]['productsalepaidamount']);
                                
                                $totalproductsalepaidamount += isset($productsalepaidsArray[$product->productsaleID]) ? $productsalepaidsArray[$product->productsaleID] : '0';
                            }
                            $totalproductsaleprice += ($product->productsaleunitprice * $product->productsalequantity);
                        }
                        $totalproductsalebalanceamount = $totalproductsaleprice - $totalproductsalepaidamount;
                    }

                    $this->data['totalproductsaleprice'] = $totalproductsaleprice;
                    $this->data['totalproductsalepaidamount'] = $totalproductsalepaidamount;
                    $this->data['totalproductsalebalanceamount'] = $totalproductsalebalanceamount;

                    $this->data['productsales'] = $productArray;

					$retArray['render'] = $this->load->view('report/productsale/ProductSaleReport',$this->data,true);
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
		if(permissionChecker('productsalereport')) {
            $productsalecustomertypeID = htmlentities(escapeString($this->uri->segment(3)));
            $productsaleclassesID      = htmlentities(escapeString($this->uri->segment(4)));
            $productsalecustomerID     = htmlentities(escapeString($this->uri->segment(5)));
            $reference_no = htmlentities(escapeString($this->uri->segment(6)));
            $statusID     = htmlentities(escapeString($this->uri->segment(7)));
            $fromdate     = htmlentities(escapeString($this->uri->segment(8)));
            $todate       = htmlentities(escapeString($this->uri->segment(9)));

            if(((int)$productsalecustomertypeID >= 0) && ((int)$productsaleclassesID >= 0) && ((int)$productsalecustomerID >= 0) && ((string)$reference_no || (int)$reference_no == 0) && ((int)$statusID >= 0) && (((int)$fromdate >= 0) || ((int)$fromdate =='')) && (((int)$todate >= 0) || ((int)$todate == ''))) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->data['productsalecustomertypeID'] = $productsalecustomertypeID;
                $this->data['productsaleclassesID'] = $productsaleclassesID;
                $this->data['productsalecustomerID'] = $productsalecustomerID;
                $this->data['reference_no'] = $reference_no;
                $this->data['statusID'] = $statusID;

                 if($fromdate != '' && $todate != '') {
                    $this->data['fromdate'] = date('d-m-Y',$fromdate);
                    $this->data['todate'] = date('d-m-Y',$todate);
                } else {
                    $this->data['fromdate'] = '';
                    $this->data['todate'] = '';
                }

                $postArray = [];
                $postArray['productsalecustomertypeID'] = $productsalecustomertypeID;
                $postArray['productsaleclassesID']      = $productsaleclassesID;
                $postArray['productsalecustomerID']     = $productsalecustomerID;
                $postArray['reference_no']   = $reference_no;
                $postArray['statusID']       = $statusID;
                if($fromdate != '' && $todate != '') {
                    $postArray['fromdate'] = date('d-m-Y',$fromdate);
                    $postArray['todate'] = date('d-m-Y',$todate);
                }

                $usertypes = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
                $this->data['usertypes'] = $usertypes;
                $users = $this->getuserlist($postArray);
                $this->data['users'] = $users;
                $productsales = $this->productsale_m->get_all_productsale_for_report($postArray);

                $productsalepaidsArray = [];
                $productsalepaids = $this->productsalepaid_m->get_order_by_productsalepaid(array('schoolyearID' => $schoolyearID));
                if(count($productsalepaids)) {
                    foreach($productsalepaids as $productsalepaid) {
                        if(isset($productsalepaidsArray[$productsalepaid->productsaleID])) {
                            $productsalepaidsArray[$productsalepaid->productsaleID] += $productsalepaid->productsalepaidamount;
                        } else {
                            $productsalepaidsArray[$productsalepaid->productsaleID] = $productsalepaid->productsalepaidamount;
                        }
                    }
                }

                $productArray = [];
                $totalproductsaleprice = 0;
                $totalproductsalepaidamount = 0;
                $totalproductsalebalanceamount = 0;
                
                $lCheck = FALSE;
                if($this->data['productsalecustomertypeID'] == 3) {
                    $lCheck = TRUE;
                }

                if(count($productsales)) {
                    foreach($productsales as $product) {
                        if($lCheck) {
                            $classesID = (int)$this->data['productsaleclassesID'];
                            if($classesID) {
                                if(!(isset($users[3][$product->productsalecustomerID]) && ($users[3][$product->productsalecustomerID]->srclassesID == $classesID))) {
                                    continue;
                                }
                            }
                        }   

                        if(isset($productArray[$product->productsaleID])) {
                            $productsalebalanceamount = $productArray[$product->productsaleID]['productsalebalanceamount'];

                            $productArray[$product->productsaleID]['productsaleprice'] += ($product->productsaleunitprice * $product->productsalequantity);

                            $productArray[$product->productsaleID]['productsalebalanceamount'] = ($product->productsaleunitprice * $product->productsalequantity) + $productsalebalanceamount;
                        } else {
                            $productArray[$product->productsaleID]['productsaleID'] = $product->productsaleID;
                            $productArray[$product->productsaleID]['productname'] = $product->productname;
                            $productArray[$product->productsaleID]['productsalequantity'] = $product->productsalequantity;
                            $productArray[$product->productsaleID]['productsalereferenceno'] = $product->productsalereferenceno;
                            $productArray[$product->productsaleID]['productsalerefund'] = $product->productsalerefund;
                            $productArray[$product->productsaleID]['productsalecustomertype'] = isset($usertypes[$product->productsalecustomertypeID]) ? $usertypes[$product->productsalecustomertypeID] : '';
                            
                            $name = '';
                            if(isset($users[$product->productsalecustomertypeID][$product->productsalecustomerID])) {
                                $name = isset($users[$product->productsalecustomertypeID][$product->productsalecustomerID]->name) ? $users[$product->productsalecustomertypeID][$product->productsalecustomerID]->name : $users[$product->productsalecustomertypeID][$product->productsalecustomerID]->srname;
                            }
                            $productArray[$product->productsaleID]['productsalecustomerName'] = $name;
                            

                            $productArray[$product->productsaleID]['productsaledate'] = date('d M Y', strtotime($product->productsaledate));
                            $productArray[$product->productsaleID]['productsaleprice'] = ($product->productsaleunitprice * $product->productsalequantity);
                            $productArray[$product->productsaleID]['productsalepaidamount'] = isset($productsalepaidsArray[$product->productsaleID]) ? $productsalepaidsArray[$product->productsaleID] : '0';
                            $productArray[$product->productsaleID]['productsalebalanceamount'] = ($productArray[$product->productsaleID]['productsaleprice'] - $productArray[$product->productsaleID]['productsalepaidamount']);
                            
                            $totalproductsalepaidamount += isset($productsalepaidsArray[$product->productsaleID]) ? $productsalepaidsArray[$product->productsaleID] : '0';
                        }
                        $totalproductsaleprice += ($product->productsaleunitprice * $product->productsalequantity);
                    }
                    $totalproductsalebalanceamount = $totalproductsaleprice - $totalproductsalepaidamount;
                }

                $this->data['totalproductsaleprice'] = $totalproductsaleprice;
                $this->data['totalproductsalepaidamount'] = $totalproductsalepaidamount;
                $this->data['totalproductsalebalanceamount'] = $totalproductsalebalanceamount;
                $this->data['productsales'] = $productArray;
                $this->reportPDF('productsalereport.css', $this->data, 'report/productsale/ProductSaleReportPDF');
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
		if(permissionChecker('productsalereport')) {
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
                    $this->data['productsalecustomertypeID'] = $this->input->post('productsalecustomertypeID');
                    $this->data['productsaleclassesID'] = $this->input->post('productsaleclassesID');
                    $this->data['productsalecustomerID'] = $this->input->post('productsalecustomerID');
                    $this->data['reference_no'] = !empty($this->input->post('reference_no')) ? $this->input->post('reference_no') : '0';
                    $this->data['statusID'] = $this->input->post('statusID');
                    $this->data['fromdate'] = $this->input->post('fromdate');
                    $this->data['todate'] = $this->input->post('todate');

                    $usertypes = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
                    $this->data['usertypes'] = $usertypes;
                    $users = $this->getuserlist($_POST);
                    $this->data['users'] = $users;
                    $productsales = $this->productsale_m->get_all_productsale_for_report($this->input->post());

                    $productsalepaidsArray = [];
                    $productsalepaids = $this->productsalepaid_m->get_order_by_productsalepaid(array('schoolyearID' => $schoolyearID));
                    if(count($productsalepaids)) {
                        foreach($productsalepaids as $productsalepaid) {
                            if(isset($productsalepaidsArray[$productsalepaid->productsaleID])) {
                                $productsalepaidsArray[$productsalepaid->productsaleID] += $productsalepaid->productsalepaidamount;
                            } else {
                                $productsalepaidsArray[$productsalepaid->productsaleID] = $productsalepaid->productsalepaidamount;
                            }
                        }
                    }

                    $productArray = [];
                    $totalproductsaleprice = 0;
                    $totalproductsalepaidamount = 0;
                    $totalproductsalebalanceamount = 0;
                    
                    $lCheck = FALSE;
                    if($this->data['productsalecustomertypeID'] == 3) {
                        $lCheck = TRUE;
                    }

                    if(count($productsales)) {
                        foreach($productsales as $product) {
                            if($lCheck) {
                                $classesID = (int)$this->data['productsaleclassesID'];
                                if($classesID) {
                                    if(!(isset($users[3][$product->productsalecustomerID]) && ($users[3][$product->productsalecustomerID]->srclassesID == $classesID))) {
                                        continue;
                                    }
                                }
                            }   

                            if(isset($productArray[$product->productsaleID])) {
                                $productsalebalanceamount = $productArray[$product->productsaleID]['productsalebalanceamount'];

                                $productArray[$product->productsaleID]['productsaleprice'] += ($product->productsaleunitprice * $product->productsalequantity);

                                $productArray[$product->productsaleID]['productsalebalanceamount'] = ($product->productsaleunitprice * $product->productsalequantity) + $productsalebalanceamount;
                            } else {
                                $productArray[$product->productsaleID]['productsaleID'] = $product->productsaleID;
                                $productArray[$product->productsaleID]['productsalereferenceno'] = $product->productsalereferenceno;
                                $productArray[$product->productsaleID]['productsalerefund'] = $product->productsalerefund;
                                $productArray[$product->productsaleID]['productsalecustomertype'] = isset($usertypes[$product->productsalecustomertypeID]) ? $usertypes[$product->productsalecustomertypeID] : '';
                                
                                $name = '';
                                if(isset($users[$product->productsalecustomertypeID][$product->productsalecustomerID])) {
                                    $name = isset($users[$product->productsalecustomertypeID][$product->productsalecustomerID]->name) ? $users[$product->productsalecustomertypeID][$product->productsalecustomerID]->name : $users[$product->productsalecustomertypeID][$product->productsalecustomerID]->srname;
                                }
                                $productArray[$product->productsaleID]['productsalecustomerName'] = $name;
                                

                                $productArray[$product->productsaleID]['productsaledate'] = date('d M Y', strtotime($product->productsaledate));
                                $productArray[$product->productsaleID]['productsaleprice'] = ($product->productsaleunitprice * $product->productsalequantity);
                                $productArray[$product->productsaleID]['productsalepaidamount'] = isset($productsalepaidsArray[$product->productsaleID]) ? $productsalepaidsArray[$product->productsaleID] : '0';
                                $productArray[$product->productsaleID]['productsalebalanceamount'] = ($productArray[$product->productsaleID]['productsaleprice'] - $productArray[$product->productsaleID]['productsalepaidamount']);
                                
                                $totalproductsalepaidamount += isset($productsalepaidsArray[$product->productsaleID]) ? $productsalepaidsArray[$product->productsaleID] : '0';
                            }
                            $totalproductsaleprice += ($product->productsaleunitprice * $product->productsalequantity);
                        }
                        $totalproductsalebalanceamount = $totalproductsaleprice - $totalproductsalepaidamount;
                    }

                    $this->data['totalproductsaleprice'] = $totalproductsaleprice;
                    $this->data['totalproductsalepaidamount'] = $totalproductsalepaidamount;
                    $this->data['totalproductsalebalanceamount'] = $totalproductsalebalanceamount;
                    $this->data['productsales'] = $productArray;

                    $this->reportSendToMail('productsalereport.css', $this->data, 'report/productsale/ProductSaleReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
				    echo json_encode($retArray);
				}
			} else {
				$retArray['message'] = $this->lang->line('productsalereport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('productsalereport_permission');
			echo json_encode($retArray);
			exit;
		}

	}

	public function xlsx() {
		if(permissionChecker('productsalereport')) {
			$this->load->library('phpspreadsheet');
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            $sheet->getDefaultColumnDimension()->setWidth(25);
            $sheet->getDefaultRowDimension()->setRowHeight(25);
            $sheet->getRowDimension('1')->setRowHeight(25);
            $sheet->getRowDimension('2')->setRowHeight(25);

            $data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="productsalereport.xlsx"');
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
        $productsalecustomertypeID = htmlentities(escapeString($this->uri->segment(3)));
        $productsaleclassesID      = htmlentities(escapeString($this->uri->segment(4)));
        $productsalecustomerID     = htmlentities(escapeString($this->uri->segment(5)));
        $reference_no = htmlentities(escapeString($this->uri->segment(6)));
        $statusID     = htmlentities(escapeString($this->uri->segment(7)));
        $fromdate     = htmlentities(escapeString($this->uri->segment(8)));
        $todate       = htmlentities(escapeString($this->uri->segment(9)));

        if((int)($productsalecustomertypeID >= 0) && (int)($productsaleclassesID >= 0) && (int)($productsalecustomerID >= 0) && ((string)$reference_no || (int)$reference_no == 0) && (int)($statusID >= 0) && ((int)($fromdate >= 0) || (int)($fromdate =='')) && ((int)($todate >= 0) || (int)($todate == ''))) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $this->data['productsalecustomertypeID'] = $productsalecustomertypeID;
            $this->data['productsaleclassesID'] = $productsaleclassesID;
            $this->data['productsalecustomerID'] = $productsalecustomerID;
            $this->data['reference_no'] = $reference_no;
            $this->data['statusID'] = $statusID;
            $this->data['fromdate'] = $fromdate;
            $this->data['todate'] = $todate;

            $postArray = [];
            $postArray['productsalecustomertypeID'] = $productsalecustomertypeID;
            $postArray['productsaleclassesID']      = $productsaleclassesID;
            $postArray['productsalecustomerID']     = $productsalecustomerID;
            $postArray['reference_no']   = $reference_no;
            $postArray['statusID']       = $statusID;
            if($fromdate != '' && $todate != '') {
                $postArray['fromdate'] = date('d-m-Y',$fromdate);
                $postArray['todate'] = date('d-m-Y',$todate);
            }

            $usertypes = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
            $this->data['usertypes'] = $usertypes;
            $users = $this->getuserlist($postArray);
            $this->data['users'] = $users;
            $productsales = $this->productsale_m->get_all_productsale_for_report($postArray);

            $productsalepaidsArray = [];
            $productsalepaids = $this->productsalepaid_m->get_order_by_productsalepaid(array('schoolyearID' => $schoolyearID));
            if(count($productsalepaids)) {
                foreach($productsalepaids as $productsalepaid) {
                    if(isset($productsalepaidsArray[$productsalepaid->productsaleID])) {
                        $productsalepaidsArray[$productsalepaid->productsaleID] += $productsalepaid->productsalepaidamount;
                    } else {
                        $productsalepaidsArray[$productsalepaid->productsaleID] = $productsalepaid->productsalepaidamount;
                    }
                }
            }

            $productArray = [];
            $totalproductsaleprice = 0;
            $totalproductsalepaidamount = 0;
            $totalproductsalebalanceamount = 0;
            
            $lCheck = FALSE;
            if($this->data['productsalecustomertypeID'] == 3) {
                $lCheck = TRUE;
            }

            if(count($productsales)) {
                foreach($productsales as $product) {
                    if($lCheck) {
                        $classesID = (int)$this->data['productsaleclassesID'];
                        if($classesID) {
                            if(!(isset($users[3][$product->productsalecustomerID]) && ($users[3][$product->productsalecustomerID]->srclassesID == $classesID))) {
                                continue;
                            }
                        }
                    }   

                    if(isset($productArray[$product->productsaleID])) {
                        $productsalebalanceamount = $productArray[$product->productsaleID]['productsalebalanceamount'];

                        $productArray[$product->productsaleID]['productsaleprice'] += ($product->productsaleunitprice * $product->productsalequantity);

                        $productArray[$product->productsaleID]['productsalebalanceamount'] = ($product->productsaleunitprice * $product->productsalequantity) + $productsalebalanceamount;
                    } else {
                        $productArray[$product->productsaleID]['productsaleID'] = $product->productsaleID;
                        $productArray[$product->productsaleID]['productsalereferenceno'] = $product->productsalereferenceno;
                        $productArray[$product->productsaleID]['productsalerefund'] = $product->productsalerefund;
                        $productArray[$product->productsaleID]['productsalecustomertype'] = isset($usertypes[$product->productsalecustomertypeID]) ? $usertypes[$product->productsalecustomertypeID] : '';
                        
                        $name = '';
                        if(isset($users[$product->productsalecustomertypeID][$product->productsalecustomerID])) {
                            $name = isset($users[$product->productsalecustomertypeID][$product->productsalecustomerID]->name) ? $users[$product->productsalecustomertypeID][$product->productsalecustomerID]->name : $users[$product->productsalecustomertypeID][$product->productsalecustomerID]->srname;
                        }
                        $productArray[$product->productsaleID]['productsalecustomerName'] = $name;
                        

                        $productArray[$product->productsaleID]['productsaledate'] = date('d M Y', strtotime($product->productsaledate));
                        $productArray[$product->productsaleID]['productsaleprice'] = ($product->productsaleunitprice * $product->productsalequantity);
                        $productArray[$product->productsaleID]['productsalepaidamount'] = isset($productsalepaidsArray[$product->productsaleID]) ? $productsalepaidsArray[$product->productsaleID] : '0';
                        $productArray[$product->productsaleID]['productsalebalanceamount'] = ($productArray[$product->productsaleID]['productsaleprice'] - $productArray[$product->productsaleID]['productsalepaidamount']);
                        
                        $totalproductsalepaidamount += isset($productsalepaidsArray[$product->productsaleID]) ? $productsalepaidsArray[$product->productsaleID] : '0';
                    }
                    $totalproductsaleprice += ($product->productsaleunitprice * $product->productsalequantity);
                }
                $totalproductsalebalanceamount = $totalproductsaleprice - $totalproductsalepaidamount;
            }

            $this->data['totalproductsaleprice'] = $totalproductsaleprice;
            $this->data['totalproductsalepaidamount'] = $totalproductsalepaidamount;
            $this->data['totalproductsalebalanceamount'] = $totalproductsalebalanceamount;
            $this->data['productsales'] = $productArray;

            return $this->generateXML($this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);		
		}
	}

	private function generateXML($arrays) {
		extract($arrays);
		if(count($productsales)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            $topCellMerge = TRUE;
            $row = 1;        
            if($fromdate != '' || $todate != '') {
                $datefrom   = $this->lang->line('productsalereport_fromdate')." : ";
                $datefrom   .= date('d M Y',$fromdate);
                $dateto     = $this->lang->line('productsalereport_todate')." : ";
                $dateto     .= date('d M Y', $todate);

                $sheet->setCellValue('A'.$row, $datefrom);
                $sheet->setCellValue('H'.$row, $dateto);
            } elseif($statusID != 0) {
                $topCellMerge = FALSE;
                $status = $this->lang->line('productsalereport_status')." : ";
                if($statusID == 1) {
                    $status .= $this->lang->line("productsalereport_pending");
                } elseif($statusID == 2) {
                    $status .= $this->lang->line("productsalereport_partial");
                } elseif($statusID == 3) {
                    $status .= $this->lang->line("productsalereport_fully_paid");
                } elseif($statusID == 4) {
                    $status .= $this->lang->line("productsalereport_refund");
                }

                $sheet->setCellValue('A'.$row, $status);
            } elseif($reference_no != '0') {
                $topCellMerge = FALSE;
                $referenceno = $this->lang->line('productsalereport_referenceNo')." : ";
                $referenceno .= $reference_no;

                $sheet->setCellValue('A'.$row, $referenceno);
            } elseif($productsalecustomertypeID != 0 && $productsalecustomerID != 0) {
                
                $usertype = $this->lang->line('productsalereport_role')." : ";
                $usertype .= isset($usertypes[$productsalecustomertypeID]) ? $usertypes[$productsalecustomertypeID] : '';
                
                $userName = $this->lang->line('productsalereport_user')." : ";

                if(isset($users[3][$productsalecustomerID])) {
                    $userName .= isset($users[3][$productsalecustomerID]->name) ? $users[3][$productsalecustomerID]->name : $users[3][$productsalecustomerID]->srname;
                }

                $sheet->setCellValue('A'.$row, $usertype);
                $sheet->setCellValue('H'.$row, $userName);
            } else {
                $topCellMerge = FALSE;
                $usertype = $this->lang->line('productsalereport_role')." : ";
                $usertype .= isset($usertypes[$productsalecustomertypeID]) ? $usertypes[$productsalecustomertypeID] : $this->lang->line('productsalereport_all');
                $sheet->setCellValue('A'.$row, $usertype);
            }

			$headers = array();
			$headers['slno'] = $this->lang->line('slno');
			$headers['referenceNo'] = $this->lang->line('productsalereport_referenceNo');
			$headers['role'] = $this->lang->line('productsalereport_role');
			$headers['user'] = $this->lang->line('productsalereport_user');
            $headers['date'] = $this->lang->line('productsalereport_date');
            $headers['total'] = $this->lang->line('productsalereport_total');
            $headers['paid'] = $this->lang->line('productsalereport_paid');
            $headers['balance'] = $this->lang->line('productsalereport_balance');

            if(count($headers)) {
                $column = "A";
                $row = 2;
                foreach($headers as $header) {
                    $sheet->setCellValue($column.$row, $header);
                    $column++;
                }
            }



			$i= 0;
            $bodys = array();
            foreach($productsales as $productsale) {
                $bodys[$i][] = $i+1;
                $bodys[$i][] = $productsale['productsalereferenceno'];
                $bodys[$i][] = $productsale['productsalecustomertype'];
                $bodys[$i][] = $productsale['productsalecustomerName'];
                $bodys[$i][] = $productsale['productsaledate'];
                $bodys[$i][] = number_format($productsale['productsaleprice'],2);
                $bodys[$i][] = number_format($productsale['productsalepaidamount'],2);
                $bodys[$i][] = number_format($productsale['productsalebalanceamount'],2);
                $i++;
            }
            $bodys[$i][] = $this->lang->line('productsalereport_grandtotal').' '.(!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : '');
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = number_format($totalproductsaleprice,2);
            $bodys[$i][] = number_format($totalproductsalepaidamount,2);
            $bodys[$i][] = number_format($totalproductsalebalanceamount,2);

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
			$sheet->getStyle('A1:H2')->applyFromArray($styleArray);

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

			$styleColumn = $row-2;
			$sheet->getStyle('A3:H'.$styleColumn)->applyFromArray($styleArray);

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
			$sheet->getStyle('A'.$styleColumn.':H'.$styleColumn)->applyFromArray($styleArray);

            $startmerge = "A".$styleColumn;
            $endmerge = "E".$styleColumn;
            $sheet->mergeCells("$startmerge:$endmerge");


            if($topCellMerge) {
                $sheet->mergeCells("B1:G1");
            } else {
                $sheet->mergeCells("B1:H1");
            }				
		} else {
		    redirect('productsalereport');
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


    private function getuserlist($queryArray) {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $retArray = [];

        $systemadmins = $this->systemadmin_m->get_systemadmin();
        if(count($systemadmins)) {
            foreach ($systemadmins as $systemadmin) {
                $retArray[1][$systemadmin->systemadminID] = $systemadmin;
            }
        }

        $teachers = $this->teacher_m->general_get_teacher();
        if(count($teachers)) {
            foreach ($teachers as $teacher) {
                $retArray[2][$teacher->teacherID] = $teacher;
            }
        }

        $sArray = [];
        $sArray['srschoolyearID'] = $schoolyearID;
        if(isset($queryArray['productsalecustomertypeID']) && $queryArray['productsalecustomertypeID'] == 3) {
            if(isset($queryArray['productsaleclassesID']) && (int)$queryArray['productsaleclassesID']) {
                $sArray['srclassesID'] = $queryArray['productsaleclassesID'];
            }

            if(isset($queryArray['productsalecustomerID']) && (int)$queryArray['productsalecustomerID']) {
                $sArray['srstudentID'] = $queryArray['productsalecustomerID'];
            }
        }

        $students = $this->studentrelation_m->get_order_by_studentrelation($sArray);
        if(count($students)) {
            foreach ($students as $student) {
                $retArray[3][$student->srstudentID] = $student;
            }
        }

        $parentss = $this->parents_m->get_parents();
        if(count($parentss)) {
            foreach ($parentss as $parents) {
                $retArray[4][$parents->parentsID] = $parents;
            }
        }

        $users = $this->user_m->get_user();
        if(count($users)) {
            foreach ($users as $user) {
                $retArray[$user->usertypeID][$user->userID] = $user;
            }
        }

        return $retArray;
    }

    public function getuser() {
        $productsalecustomertypeID = $this->input->post('productsalecustomertypeID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        echo "<option value=\"0\">",$this->lang->line('productsalereport_please_select'),"</option>";
        if((int)$productsalecustomertypeID) {
            if($productsalecustomertypeID == 1) {
                $systemadmins = $this->systemadmin_m->get_systemadmin();
                if(count($systemadmins)) {
                    foreach ($systemadmins as $systemadmin) {
                        echo "<option value=\"$systemadmin->systemadminID\">",$systemadmin->name,"</option>";
                    }
                }
            } elseif($productsalecustomertypeID == 2) {
                $teachers = $this->teacher_m->general_get_teacher();
                if(count($teachers)) {
                    foreach ($teachers as $teacher) {
                        echo "<option value=\"$teacher->teacherID\">",$teacher->name,"</option>";
                    }
                }
            } elseif($productsalecustomertypeID == 3) {
                $classesID = $this->input->post('productsaleclassesID');
                if((int)$classesID) {
                    $students = $this->studentrelation_m->get_order_by_studentrelation(array('srschoolyearID' => $schoolyearID, 'srclassesID' => $classesID));
                    if(count($students)) {
                        foreach ($students as $student) {
                            echo "<option value=\"$student->srstudentID\">".$student->srname." - ".$this->lang->line('productsalereport_roll')." - ".$student->srroll."</option>";
                        }
                    }
                }
            } elseif($productsalecustomertypeID == 4) {
                $parentss = $this->parents_m->get_parents();
                if(count($parentss)) {
                    foreach ($parentss as $parents) {
                        echo "<option value=\"$parents->parentsID\">",$parents->name,"</option>";
                    }
                }
            } else {
                $users = $this->user_m->get_order_by_user(array('usertypeID' => $productsalecustomertypeID));
                if(count($users)) {
                    foreach ($users as $user) {
                        echo "<option value=\"$user->userID\">",$user->name,"</option>";
                    }
                }
            }
        }
    }



}
