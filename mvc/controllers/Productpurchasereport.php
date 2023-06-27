<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productpurchasereport extends Admin_Controller {
	public function __construct() {
		parent::__construct();

        $this->load->model("productwarehouse_m");
        $this->load->model("productsupplier_m");
        $this->load->model("productpurchase_m");
        $this->load->model("productpurchaseitem_m");
        $this->load->model("productpurchasepaid_m");
        $language = $this->session->userdata('lang');
		$this->lang->load('productpurchasereport', $language);
	}

	public function rules() {
		$rules = array(
	        array(
	                'field' => 'productsupplierID',
	                'label' => $this->lang->line('productpurchasereport_supplier'),
	                'rules' => 'trim|xss_clean'
	        ),
            array(
	                'field' => 'productwarehouseID',
	                'label' => $this->lang->line('productpurchasereport_warehouse'),
	                'rules' => 'trim|xss_clean'
	        ),
            array(
	                'field' => 'reference_no',
	                'label' => $this->lang->line('productpurchasereport_referenceNo'),
	                'rules' => 'trim|xss_clean|callback_unique_data'
	        ),
            array(
	                'field' => 'statusID',
	                'label' => $this->lang->line('productpurchasereport_status'),
	                'rules' => 'trim|xss_clean'
	        ),
            array(
	                'field' => 'fromdate',
	                'label' => $this->lang->line('productpurchasereport_fromdate'),
	                'rules' => 'trim|xss_clean|callback_date_valid|callback_unique_date'
	        ),
	        array(
	                'field' => 'todate',
	                'label' => $this->lang->line('productpurchasereport_todate'),
	                'rules' => 'trim|xss_clean|callback_date_valid'
	        )
		);
		return $rules;
	}

	public function send_pdf_to_mail_rules() {
		$rules = array(
            array(
                'field' => 'productsupplierID',
                'label' => $this->lang->line('productpurchasereport_supplier'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'productwarehouseID',
                'label' => $this->lang->line('productpurchasereport_warehouse'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'reference_no',
                'label' => $this->lang->line('productpurchasereport_referenceNo'),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line('productpurchasereport_status'),
                'rules' => 'trim|xss_clean'
            ),
		    array(
	                'field' => 'fromdate',
	                'label' => $this->lang->line('productpurchasereport_fromdate'),
                    'rules' => 'trim|xss_clean'
	        ),
	        array(
	                'field' => 'todate',
	                'label' => $this->lang->line('productpurchasereport_todate'),
                    'rules' => 'trim|xss_clean'
	        ),
	        array(
	                'field' => 'to',
	                'label' => $this->lang->line('productpurchasereport_to'),
	                'rules' => 'trim|required|xss_clean|valid_email'
	        ),
	        array(
	                'field' => 'subject',
	                'label' => $this->lang->line('productpurchasereport_subject'),
	                'rules' => 'trim|required|xss_clean'
	        ),
	        array(
	                'field' => 'message',
	                'label' => $this->lang->line('productpurchasereport_message'),
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

        $this->data['productwarehouses'] = $this->productwarehouse_m->get_productwarehouse();
        $this->data['productsuppliers'] = $this->productsupplier_m->get_productsupplier();
        $this->data["subview"] = "report/productpurchase/ProductpurchaseReportView";
		$this->load->view('_layout_main', $this->data);
	}

    public function unique_data($data) {
        if($data != "") {
            if($data == "0") {
                $this->form_validation->set_message('unique_data', 'The %s field value invalid.');
                return FALSE;
            }
            return TRUE;
        } 
        return TRUE;
    }

	public function getProductpurchaseReport() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('productpurchasereport')) {
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
                    $this->data['productsupplierID'] = $this->input->post('productsupplierID');
                    $this->data['productwarehouseID'] = $this->input->post('productwarehouseID');
                    $this->data['reference_no'] = !empty($this->input->post('reference_no')) ? $this->input->post('reference_no') : '0';
                    $this->data['statusID'] = $this->input->post('statusID');
                    $this->data['fromdate'] = $this->input->post('fromdate');
					$this->data['todate'] = $this->input->post('todate');

                    $productsuppliers = pluck($this->productsupplier_m->get_productsupplier(), 'productsuppliercompanyname', 'productsupplierID');
                    $productwarehouses = pluck($this->productwarehouse_m->get_productwarehouse(), 'productwarehousename', 'productwarehouseID');

                    $this->data['productsuppliers'] = $productsuppliers;
                    $this->data['productwarehouses'] = $productwarehouses;

					$productpurchases = $this->productpurchase_m->get_all_productpurchase_for_report($this->input->post());

                    $productpurchasepaids = $this->productpurchasepaid_m->get_order_by_productpurchasepaid(array('schoolyearID' => $schoolyearID));
                    $productpurchasepaidsArray = [];
                    if(count($productpurchasepaids)) {
                        foreach($productpurchasepaids as $productpurchasepaid) {
                            if(isset($productpurchasepaidsArray[$productpurchasepaid->productpurchaseID])) {
                                $productpurchasepaidsArray[$productpurchasepaid->productpurchaseID] += $productpurchasepaid->productpurchasepaidamount;
                            } else {
                                $productpurchasepaidsArray[$productpurchasepaid->productpurchaseID] = $productpurchasepaid->productpurchasepaidamount;
                            }
                        }
                    }

                    $productpurchaseArray = [];
                    $totalproductpurchaseprice = 0;
                    $totalproductpurchasepaidamount = 0;
                    $totalproductpurchasebalanceamount = 0;

                    if(count($productpurchases)) {
                        foreach ($productpurchases as $productpurchase) {
                            if(isset($productpurchaseArray[$productpurchase->productpurchaseID])) {
                                $productpurchaseArray[$productpurchase->productpurchaseID]['total'] += ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                                $productpurchaseArray[$productpurchase->productpurchaseID]['balance'] += ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                            } else {
                                $productpurchaseArray[$productpurchase->productpurchaseID]['reference_no'] = $productpurchase->productpurchasereferenceno;
                                $productpurchaseArray[$productpurchase->productpurchaseID]['supplier'] = isset($productsuppliers[$productpurchase->productsupplierID]) ? $productsuppliers[$productpurchase->productsupplierID] : '';
                                $productpurchaseArray[$productpurchase->productpurchaseID]['warehouse'] = isset($productwarehouses[$productpurchase->productwarehouseID]) ? $productwarehouses[$productpurchase->productwarehouseID] : '';
                                $productpurchaseArray[$productpurchase->productpurchaseID]['date'] = date('d M Y',strtotime($productpurchase->productpurchasedate));
                                $productpurchaseArray[$productpurchase->productpurchaseID]['total'] = ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                                $productpurchaseArray[$productpurchase->productpurchaseID]['paid'] = isset($productpurchasepaidsArray[$productpurchase->productpurchaseID]) ? $productpurchasepaidsArray[$productpurchase->productpurchaseID] : '0';
                                $productpurchaseArray[$productpurchase->productpurchaseID]['balance'] = ($productpurchaseArray[$productpurchase->productpurchaseID]['total'] - $productpurchaseArray[$productpurchase->productpurchaseID]['paid']);
                                $totalproductpurchasepaidamount += $productpurchaseArray[$productpurchase->productpurchaseID]['paid'];
                            }
                            $totalproductpurchaseprice += ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                        }
                        $totalproductpurchasebalanceamount = ($totalproductpurchaseprice - $totalproductpurchasepaidamount);
                    }

                    $this->data['totalproductpurchaseprice'] = $totalproductpurchaseprice;
                    $this->data['totalproductpurchasepaidamount'] = $totalproductpurchasepaidamount;
                    $this->data['totalproductpurchasebalanceamount'] = $totalproductpurchasebalanceamount;
                    $this->data['productpurchases'] = $productpurchaseArray;
					$retArray['render'] = $this->load->view('report/productpurchase/ProductpurchaseReport',$this->data,true);
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
		if(permissionChecker('productpurchasereport')) {
            $productsupplierID  = htmlentities(escapeString($this->uri->segment(3)));
            $productwarehouseID = htmlentities(escapeString($this->uri->segment(4)));
            $reference_no       = htmlentities(escapeString($this->uri->segment(5)));
            $statusID = htmlentities(escapeString($this->uri->segment(6)));
            $fromdate = htmlentities(escapeString($this->uri->segment(7)));
            $todate   = htmlentities(escapeString($this->uri->segment(8)));
            if((int)($productsupplierID >= 0) && (int)($productwarehouseID >= 0) && ((int)($reference_no >= 0) || (string)($reference_no >= 0)) && (int)($statusID >= 0) && ((int)($fromdate >= 0) || (int)($fromdate =='')) && ((int)($todate >= 0) || (int)($todate == ''))) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->data['productsupplierID']  = $productsupplierID;
                $this->data['productwarehouseID'] = $productwarehouseID;
                $this->data['reference_no'] = $reference_no;
                $this->data['statusID'] = $statusID;
                $this->data['fromdate'] = $fromdate;
                $this->data['todate']   = $todate;

                $postArray = [];
                $postArray['productsupplierID']     = $productsupplierID;
                $postArray['productwarehouseID']    = $productwarehouseID;
                $postArray['reference_no']          = $reference_no;
                $postArray['statusID']              = $statusID;
                if($fromdate !='' && $todate != '') {
                    $postArray['fromdate'] = date('d-m-Y',$fromdate);
                    $postArray['todate']   = date('d-m-Y',$todate);
                }

                $productsuppliers = pluck($this->productsupplier_m->get_productsupplier(), 'productsuppliercompanyname', 'productsupplierID');
                $productwarehouses = pluck($this->productwarehouse_m->get_productwarehouse(), 'productwarehousename', 'productwarehouseID');
                $this->data['productsuppliers'] = $productsuppliers;
                $this->data['productwarehouses'] = $productwarehouses;
                
                $productpurchases = $this->productpurchase_m->get_all_productpurchase_for_report($postArray);

                $productpurchasepaids = $this->productpurchasepaid_m->get_order_by_productpurchasepaid(array('schoolyearID' => $schoolyearID));
                $productpurchasepaidsArray = [];
                if(count($productpurchasepaids)) {
                    foreach($productpurchasepaids as $productpurchasepaid) {
                        if(isset($productpurchasepaidsArray[$productpurchasepaid->productpurchaseID])) {
                            $productpurchasepaidsArray[$productpurchasepaid->productpurchaseID] += $productpurchasepaid->productpurchasepaidamount;
                        } else {
                            $productpurchasepaidsArray[$productpurchasepaid->productpurchaseID] = $productpurchasepaid->productpurchasepaidamount;
                        }
                    }
                }

                $productpurchaseArray = [];
                $totalproductpurchaseprice = 0;
                $totalproductpurchasepaidamount = 0;
                $totalproductpurchasebalanceamount = 0;

                if(count($productpurchases)) {
                    foreach ($productpurchases as $productpurchase) {
                        if(isset($productpurchaseArray[$productpurchase->productpurchaseID])) {
                            $productpurchaseArray[$productpurchase->productpurchaseID]['total'] += ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                            $productpurchaseArray[$productpurchase->productpurchaseID]['balance'] += ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                        } else {
                            $productpurchaseArray[$productpurchase->productpurchaseID]['reference_no'] = $productpurchase->productpurchasereferenceno;
                            $productpurchaseArray[$productpurchase->productpurchaseID]['supplier'] = isset($productsuppliers[$productpurchase->productsupplierID]) ? $productsuppliers[$productpurchase->productsupplierID] : '';
                            $productpurchaseArray[$productpurchase->productpurchaseID]['warehouse'] = isset($productwarehouses[$productpurchase->productwarehouseID]) ? $productwarehouses[$productpurchase->productwarehouseID] : '';
                            $productpurchaseArray[$productpurchase->productpurchaseID]['date'] = date('d M Y',strtotime($productpurchase->productpurchasedate));
                            $productpurchaseArray[$productpurchase->productpurchaseID]['total'] = ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                            $productpurchaseArray[$productpurchase->productpurchaseID]['paid'] = isset($productpurchasepaidsArray[$productpurchase->productpurchaseID]) ? $productpurchasepaidsArray[$productpurchase->productpurchaseID] : '0';
                            $productpurchaseArray[$productpurchase->productpurchaseID]['balance'] = ($productpurchaseArray[$productpurchase->productpurchaseID]['total'] - $productpurchaseArray[$productpurchase->productpurchaseID]['paid']);
                            $totalproductpurchasepaidamount += $productpurchaseArray[$productpurchase->productpurchaseID]['paid'];
                        }
                        $totalproductpurchaseprice += ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                    }
                    $totalproductpurchasebalanceamount = ($totalproductpurchaseprice - $totalproductpurchasepaidamount);
                }


                $this->data['totalproductpurchaseprice'] = $totalproductpurchaseprice;
                $this->data['totalproductpurchasepaidamount'] = $totalproductpurchasepaidamount;
                $this->data['totalproductpurchasebalanceamount'] = $totalproductpurchasebalanceamount;
                $this->data['productpurchases'] = $productpurchaseArray;
                $this->reportPDF('productpurchasereport.css', $this->data, 'report/productpurchase/ProductpurchaseReportPDF');
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
		if(permissionChecker('productpurchasereport')) {
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
                    $this->data['productsupplierID'] = $this->input->post('productsupplierID');
                    $this->data['productwarehouseID'] = $this->input->post('productwarehouseID');
                    $this->data['reference_no'] = !empty($this->input->post('reference_no')) ? $this->input->post('reference_no') : '0';
                    $this->data['statusID'] = $this->input->post('statusID');
                    $this->data['fromdate'] = strtotime($this->input->post('fromdate'));
                    $this->data['todate'] = strtotime($this->input->post('todate'));

                    $productsuppliers = pluck($this->productsupplier_m->get_productsupplier(), 'productsuppliercompanyname', 'productsupplierID');
                    $productwarehouses = pluck($this->productwarehouse_m->get_productwarehouse(), 'productwarehousename', 'productwarehouseID');
                    $this->data['productsuppliers'] = $productsuppliers;
                    $this->data['productwarehouses'] = $productwarehouses;

                    $productpurchases = $this->productpurchase_m->get_all_productpurchase_for_report($this->input->post());


                    $productpurchasepaids = $this->productpurchasepaid_m->get_order_by_productpurchasepaid(array('schoolyearID' => $schoolyearID));
                    $productpurchasepaidsArray = [];
                    if(count($productpurchasepaids)) {
                        foreach($productpurchasepaids as $productpurchasepaid) {
                            if(isset($productpurchasepaidsArray[$productpurchasepaid->productpurchaseID])) {
                                $productpurchasepaidsArray[$productpurchasepaid->productpurchaseID] += $productpurchasepaid->productpurchasepaidamount;
                            } else {
                                $productpurchasepaidsArray[$productpurchasepaid->productpurchaseID] = $productpurchasepaid->productpurchasepaidamount;
                            }
                        }
                    }

                    $productpurchaseArray = [];
                    $totalproductpurchaseprice = 0;
                    $totalproductpurchasepaidamount = 0;
                    $totalproductpurchasebalanceamount = 0;

                    if(count($productpurchases)) {
                        foreach ($productpurchases as $productpurchase) {
                            if(isset($productpurchaseArray[$productpurchase->productpurchaseID])) {
                                $productpurchaseArray[$productpurchase->productpurchaseID]['total'] += ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                                $productpurchaseArray[$productpurchase->productpurchaseID]['balance'] += ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                            } else {
                                $productpurchaseArray[$productpurchase->productpurchaseID]['reference_no'] = $productpurchase->productpurchasereferenceno;
                                $productpurchaseArray[$productpurchase->productpurchaseID]['supplier'] = isset($productsuppliers[$productpurchase->productsupplierID]) ? $productsuppliers[$productpurchase->productsupplierID] : '';
                                $productpurchaseArray[$productpurchase->productpurchaseID]['warehouse'] = isset($productwarehouses[$productpurchase->productwarehouseID]) ? $productwarehouses[$productpurchase->productwarehouseID] : '';
                                $productpurchaseArray[$productpurchase->productpurchaseID]['date'] = date('d M Y',strtotime($productpurchase->productpurchasedate));
                                $productpurchaseArray[$productpurchase->productpurchaseID]['total'] = ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                                $productpurchaseArray[$productpurchase->productpurchaseID]['paid'] = isset($productpurchasepaidsArray[$productpurchase->productpurchaseID]) ? $productpurchasepaidsArray[$productpurchase->productpurchaseID] : '0';
                                $productpurchaseArray[$productpurchase->productpurchaseID]['balance'] = ($productpurchaseArray[$productpurchase->productpurchaseID]['total'] - $productpurchaseArray[$productpurchase->productpurchaseID]['paid']);
                                $totalproductpurchasepaidamount += $productpurchaseArray[$productpurchase->productpurchaseID]['paid'];
                            }
                            $totalproductpurchaseprice += ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                        }
                        $totalproductpurchasebalanceamount = ($totalproductpurchaseprice - $totalproductpurchasepaidamount);
                    }

                    $this->data['totalproductpurchaseprice'] = $totalproductpurchaseprice;
                    $this->data['totalproductpurchasepaidamount'] = $totalproductpurchasepaidamount;
                    $this->data['totalproductpurchasebalanceamount'] = $totalproductpurchasebalanceamount;
                    $this->data['productpurchases'] = $productpurchaseArray;

                    $this->reportSendToMail('productpurchasereport.css', $this->data, 'report/productpurchase/ProductpurchaseReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
				    echo json_encode($retArray);
				}
			} else {
				$retArray['message'] = $this->lang->line('productpurchasereport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('productpurchasereport_permission');
			echo json_encode($retArray);
			exit;
		}

	}

	public function xlsx() {
		if(permissionChecker('productpurchasereport')) {
			$this->load->library('phpspreadsheet');
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            $sheet->getDefaultColumnDimension()->setWidth(25);
            $sheet->getDefaultRowDimension()->setRowHeight(25);
            $sheet->getColumnDimension('C')->setWidth(35);
            $sheet->getRowDimension('1')->setRowHeight(25);
            $sheet->getRowDimension('2')->setRowHeight(25);

            $data = $this->xmlData();

			// Redirect output to a client’s web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="productpurchasereport.xlsx"');
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
        $productsupplierID  = htmlentities(escapeString($this->uri->segment(3)));
        $productwarehouseID = htmlentities(escapeString($this->uri->segment(4)));
        $reference_no       = htmlentities(escapeString($this->uri->segment(5)));
        $statusID = htmlentities(escapeString($this->uri->segment(6)));
        $fromdate = htmlentities(escapeString($this->uri->segment(7)));
        $todate   = htmlentities(escapeString($this->uri->segment(8)));
        if((int)($productsupplierID >= 0) && (int)($productwarehouseID >= 0) && ((int)($reference_no >= 0) || (string)($reference_no >= 0)) && (int)($statusID >= 0) && ((int)($fromdate >= 0) || (int)($fromdate =='')) && ((int)($todate >= 0) || (int)($todate == ''))) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $this->data['productsupplierID']  = $productsupplierID;
            $this->data['productwarehouseID'] = $productwarehouseID;
            $this->data['reference_no'] = $reference_no;
            $this->data['statusID'] = $statusID;
            $this->data['fromdate'] = $fromdate;
            $this->data['todate']   = $todate;

            $postArray = [];
            $postArray['productsupplierID']     = $productsupplierID;
            $postArray['productwarehouseID']    = $productwarehouseID;
            $postArray['reference_no']          = $reference_no;
            $postArray['statusID']              = $statusID;
            if($fromdate !='' && $todate != '') {
                $postArray['fromdate'] = date('d-m-Y',$fromdate);
                $postArray['todate']   = date('d-m-Y',$todate);
            }

            $productsuppliers = pluck($this->productsupplier_m->get_productsupplier(), 'productsuppliercompanyname', 'productsupplierID');
            $productwarehouses = pluck($this->productwarehouse_m->get_productwarehouse(), 'productwarehousename', 'productwarehouseID');
            $this->data['productsuppliers'] = $productsuppliers;
            $this->data['productwarehouses'] = $productwarehouses;

            $productpurchases = $this->productpurchase_m->get_all_productpurchase_for_report($postArray);
            $productpurchasepaids = $this->productpurchasepaid_m->get_order_by_productpurchasepaid(array('schoolyearID' => $schoolyearID));
            $productpurchasepaidsArray = [];
            if(count($productpurchasepaids)) {
                foreach($productpurchasepaids as $productpurchasepaid) {
                    if(isset($productpurchasepaidsArray[$productpurchasepaid->productpurchaseID])) {
                        $productpurchasepaidsArray[$productpurchasepaid->productpurchaseID] += $productpurchasepaid->productpurchasepaidamount;
                    } else {
                        $productpurchasepaidsArray[$productpurchasepaid->productpurchaseID] = $productpurchasepaid->productpurchasepaidamount;
                    }
                }
            }

            $productpurchaseArray = [];
            $totalproductpurchaseprice = 0;
            $totalproductpurchasepaidamount = 0;
            $totalproductpurchasebalanceamount = 0;

            if(count($productpurchases)) {
                foreach ($productpurchases as $productpurchase) {
                    if(isset($productpurchaseArray[$productpurchase->productpurchaseID])) {
                        $productpurchaseArray[$productpurchase->productpurchaseID]['total'] += ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                        $productpurchaseArray[$productpurchase->productpurchaseID]['balance'] += ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                    } else {
                        $productpurchaseArray[$productpurchase->productpurchaseID]['reference_no'] = $productpurchase->productpurchasereferenceno;
                        $productpurchaseArray[$productpurchase->productpurchaseID]['supplier'] = isset($productsuppliers[$productpurchase->productsupplierID]) ? $productsuppliers[$productpurchase->productsupplierID] : '';
                        $productpurchaseArray[$productpurchase->productpurchaseID]['warehouse'] = isset($productwarehouses[$productpurchase->productwarehouseID]) ? $productwarehouses[$productpurchase->productwarehouseID] : '';
                        $productpurchaseArray[$productpurchase->productpurchaseID]['date'] = date('d M Y',strtotime($productpurchase->productpurchasedate));
                        $productpurchaseArray[$productpurchase->productpurchaseID]['total'] = ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                        $productpurchaseArray[$productpurchase->productpurchaseID]['paid'] = isset($productpurchasepaidsArray[$productpurchase->productpurchaseID]) ? $productpurchasepaidsArray[$productpurchase->productpurchaseID] : '0';
                        $productpurchaseArray[$productpurchase->productpurchaseID]['balance'] = ($productpurchaseArray[$productpurchase->productpurchaseID]['total'] - $productpurchaseArray[$productpurchase->productpurchaseID]['paid']);
                        $totalproductpurchasepaidamount += $productpurchaseArray[$productpurchase->productpurchaseID]['paid'];
                    }
                    $totalproductpurchaseprice += ($productpurchase->productpurchaseunitprice * $productpurchase->productpurchasequantity);
                }
                $totalproductpurchasebalanceamount = ($totalproductpurchaseprice - $totalproductpurchasepaidamount);
            }

            $this->data['totalproductpurchaseprice'] = $totalproductpurchaseprice;
            $this->data['totalproductpurchasepaidamount'] = $totalproductpurchasepaidamount;
            $this->data['totalproductpurchasebalanceamount'] = $totalproductpurchasebalanceamount;
            $this->data['productpurchases'] = $productpurchaseArray;
            return $this->generateXML($this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

	private function generateXML($arrays) {
		extract($arrays);
        if(count($productpurchases)) {
	        $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $topCellMerge = TRUE;
            if($fromdate != '' && $todate != '' ) { 
                $fdate = $this->lang->line('productpurchasereport_fromdate')." : ";
                $fdate .= date('d M Y',$fromdate);

                $tdate = $this->lang->line('productpurchasereport_todate')." : ";
                $tdate .= date('d M Y',$todate);

                $sheet->setCellValue('A1',$fdate);
                $sheet->setCellValue('H1',$tdate);
            } elseif($statusID != 0 ) {
                $status = $this->lang->line('productpurchasereport_status')." : ";
                if($statusID == 1) {
                    $status .= $this->lang->line("productpurchasereport_pending");
                } elseif($statusID == 2) {
                    $status .= $this->lang->line("productpurchasereport_partial");
                } elseif($statusID == 3) {
                    $status .= $this->lang->line("productpurchasereport_fully_paid");
                } elseif($statusID == 4) {
                    $status .= $this->lang->line("productpurchasereport_refund");
                }
                $topCellMerge = FALSE;

                $sheet->setCellValue('A1',$status);
            } elseif($reference_no != '0') {
                $reference_no = $this->lang->line('productpurchasereport_referenceNo')." : ". $reference_no;
                $topCellMerge = FALSE;
                
                $sheet->setCellValue('A1',$reference_no);
            } elseif($productsupplierID != 0 && $productwarehouseID != 0 ) {
                $supplier = $this->lang->line('productpurchasereport_supplier')." : ";
                $supplier .= isset($productsuppliers[$productsupplierID]) ? $productsuppliers[$productsupplierID] : '';
                $warehouse = $this->lang->line('productpurchasereport_warehouse')." : ";
                $warehouse .= isset($productwarehouses[$productwarehouseID]) ? $productwarehouses[$productwarehouseID] : '';

                $sheet->setCellValue('A1',$supplier);
                $sheet->setCellValue('H1',$warehouse);
            } elseif($productsupplierID != 0) {
                $supplier = $this->lang->line('productpurchasereport_supplier')." : ";
                $supplier .= isset($productsuppliers[$productsupplierID]) ? $productsuppliers[$productsupplierID] : '';
                $topCellMerge = FALSE;
                
                $sheet->setCellValue('A1',$supplier);
            } elseif($productwarehouseID != 0) {
                $warehouse = $this->lang->line('productpurchasereport_warehouse')." : ";
                $warehouse .= isset($productwarehouses[$productwarehouseID]) ? $productwarehouses[$productwarehouseID] : '';
                $topCellMerge = FALSE;

                $sheet->setCellValue('A1',$warehouse);
            } else {
                $sheet->getRowDimension('1')->setVisible(false);
            }

            $headers = array();
			$headers['slno'] = $this->lang->line('slno');
			$headers['referenceNo'] = $this->lang->line('productpurchasereport_referenceNo');
            $headers['supplier'] = $this->lang->line('productpurchasereport_supplier');
			$headers['warehouse'] = $this->lang->line('productpurchasereport_warehouse');
            $headers['date'] = $this->lang->line('productpurchasereport_date');
            $headers['total'] = $this->lang->line('productpurchasereport_total');
            $headers['paid'] = $this->lang->line('productpurchasereport_paid');
            $headers['balance'] = $this->lang->line('productpurchasereport_balance');

            $i=0;
			$bodys = array();
			foreach($productpurchases as $productpurchase) {
				$bodys[$i][] = $i+1;
				$bodys[$i][] = $productpurchase['reference_no'];
                $bodys[$i][] = $productpurchase['supplier'];
                $bodys[$i][] = $productpurchase['warehouse'];
                $bodys[$i][] = $productpurchase['date'];
                $bodys[$i][] = number_format($productpurchase['total'],2);
                $bodys[$i][] = number_format($productpurchase['paid'],2);
				$bodys[$i][] = number_format($productpurchase['balance'],2);
                $i++;
			}

            $usdLang = $this->lang->line('productpurchasereport_grandtotal');
            $usdLang .= !empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : '';

            $bodys[$i][] = $usdLang;
			$bodys[$i][] = "";
			$bodys[$i][] = "";
			$bodys[$i][] = "";
			$bodys[$i][] = "";
			$bodys[$i][] = number_format($totalproductpurchaseprice,2);
			$bodys[$i][] = number_format($totalproductpurchasepaidamount,2);
			$bodys[$i][] = number_format($totalproductpurchasebalanceamount,2);

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
				foreach($bodys as $rows) {
					$column = 'A';
					foreach ($rows as $value) {
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
            $sheet->mergeCells("A1:B1");

            if($topCellMerge) {
                $sheet->mergeCells("C1:G1");
            } else {
                $sheet->mergeCells("C1:H1");
            }
		} else {
		  redirect('productpurchasereport');
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


}
