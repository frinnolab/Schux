<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productpurchase extends Admin_Controller {
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
        $this->load->model("productpurchase_m");
        $this->load->model("productcategory_m");
        $this->load->model("product_m");
        $this->load->model("productsupplier_m");
        $this->load->model("productwarehouse_m");
        $this->load->model("productpurchaseitem_m");
        $this->load->model("productpurchasepaid_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('productpurchase', $language);
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

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->data['productsuppliers'] = pluck($this->productsupplier_m->get_productsupplier(), 'productsuppliercompanyname', 'productsupplierID');
        $this->data['productpurchases'] = $this->productpurchase_m->get_order_by_productpurchase(array('schoolyearID' => $schoolyearID));
        $this->data['grandtotalandpaid'] = $this->grandtotalandpaid($this->data['productpurchases'], $schoolyearID);
        $this->data["subview"] = "productpurchase/index";
        // print(json_encode($this->data['productpurchases']));
        // exit;
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'productsupplierID',
                'label' => $this->lang->line("productpurchase_supplier"),
                'rules' => 'trim|required|xss_clean|max_length[11]|numeric|callback_unique_productsupplierID'
            ),
            array(
                'field' => 'productwarehouseID',
                'label' => $this->lang->line("productpurchase_warehouse"),
                'rules' => 'trim|required|xss_clean|max_length[11]|numeric|callback_unique_productwarehouseID'
            ),
            array(
                'field' => 'productpurchasereferenceno',
                'label' => $this->lang->line("productpurchase_referenceno"),
                'rules' => 'trim|xss_clean|max_length[100]'
            ),
            array(
                'field' => 'productpurchasedate',
                'label' => $this->lang->line("productpurchase_date"),
                'rules' => 'trim|required|xss_clean|max_length[11]|callback_date_valid'
            ),
            array(
                'field' => 'productpurchasefile',
                'label' => $this->lang->line("productpurchase_file"),
                'rules' => 'trim|xss_clean|max_length[200]|callback_fileupload'
            ),
            array(
                'field' => 'productpurchasedescription',
                'label' => $this->lang->line("productpurchase_description"),
                'rules' => 'trim|xss_clean|max_length[520]'
            ),
            array(
                'field' => 'productitem',
                'label' => $this->lang->line("productpurchase_productitem"),
                'rules' => 'trim|xss_clean|callback_unique_productitem'
            ),
            array(
                'field' => 'editID',
                'label' => $this->lang->line("productpurchase_editid"),
                'rules' => 'trim|required|xss_clean|numeric'
            )
        );
        return $rules;
    }

    public function add() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
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
            
            $this->data['productcategorys'] = $this->productcategory_m->get_productcategory();
            $this->data['productsuppliers'] = $this->productsupplier_m->get_productsupplier();
            $this->data['productwarehouses'] = $this->productwarehouse_m->get_productwarehouse();
            $this->data['productobj'] = json_encode(pluck($this->product_m->get_product(), 'obj', 'productID'));

            $this->data["subview"] = "productpurchase/add";
            $this->load->view('_layout_main', $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
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
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            if((int)$id) {
                $this->data['productpurchaseID'] = $id;
                $this->data['productpurchase'] = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $id, 'schoolyearID' => $schoolyearID));

                $this->data['productcategorys'] = $this->productcategory_m->get_productcategory();
                $this->data['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
                $this->data['productsuppliers'] = $this->productsupplier_m->get_productsupplier();
                $this->data['productwarehouses'] = $this->productwarehouse_m->get_productwarehouse();

                $this->data['productpurchasepaid'] = $this->productpurchasepaid_m->get_productpurchasepaid_sum('productpurchasepaidamount', array('productpurchaseID' => $id));

                if($this->data['productpurchase']) {
                    if(($this->data['productpurchase']->productpurchaserefund == 0) && ($this->data['productpurchasepaid']->productpurchasepaidamount == NULL)) {
                        $this->data['productpurchaseitems'] = $this->productpurchaseitem_m->get_order_by_productpurchaseitem(array('schoolyearID' => $schoolyearID, 'productpurchaseID' => $id));
                        $this->data["subview"] = "productpurchase/edit";
                        $this->load->view('_layout_main', $this->data);
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

    public function view() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
            ),
            'js' => array(
                'assets/datepicker/datepicker.js',
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if((int)$id) {
            $this->data['productpurchase'] = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $id, 'schoolyearID' => $schoolyearID));
            
            $this->data['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
            
            $this->data['productpurchaseitems'] = $this->productpurchaseitem_m->get_order_by_productpurchaseitem(array('productpurchaseID' => $id, 'schoolyearID' => $schoolyearID));

            $this->data['productpurchasepaid'] = $this->productpurchasepaid_m->get_productpurchasepaid_sum('productpurchasepaidamount', array('productpurchaseID' => $id));


            if($this->data['productpurchase']) {
                $this->data['createuser'] = getNameByUsertypeIDAndUserID($this->data['productpurchase']->create_usertypeID, $this->data['productpurchase']->create_userID);

                $this->data['productsupplier'] = $this->productsupplier_m->get_single_productsupplier(array('productsupplierID' => $this->data['productpurchase']->productsupplierID));
                $this->data['productwarehouse'] = $this->productwarehouse_m->get_single_productwarehouse(array('productwarehouseID' => $this->data['productpurchase']->productwarehouseID));

                $this->data["subview"] = "productpurchase/view";
                $this->load->view('_layout_main', $this->data);
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
                $this->data['productpurchase'] = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $id, 'schoolyearID' => $schoolyearID));
                if(count($this->data['productpurchase'])) {
                    $this->data['productpurchasepaid'] = $this->productpurchasepaid_m->get_productpurchasepaid_sum('productpurchasepaidamount', array('productpurchaseID' => $id));
                    if(($this->data['productpurchase']->productpurchaserefund == 0) && ($this->data['productpurchasepaid']->productpurchasepaidamount == NULL)) {
                        $this->productpurchase_m->delete_productpurchase($id);
                        $this->productpurchaseitem_m->delete_productpurchaseitem_by_productpurchaseID($id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("productpurchase/index"));
                    } else {
                        redirect(base_url("productpurchase/index"));
                    }
                } else {
                    redirect(base_url("productpurchase/index"));
                }
            } else {
                redirect(base_url("productpurchase/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function cancel() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            if(permissionChecker('productpurchase_edit')) {
                $id = htmlentities(escapeString($this->uri->segment(3)));
                if((int)$id) {
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');
                    $this->data['productpurchase'] = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $id, 'schoolyearID' => $schoolyearID));
                    if(count($this->data['productpurchase'])) {
                        $this->productpurchase_m->update_productpurchase(array('productpurchaserefund' => 1), $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("productpurchase/index"));
                    } else {
                        redirect(base_url("productpurchase/index"));
                    }
                } else {
                    redirect(base_url("productpurchase/index"));
                }
            } else {
                redirect(base_url("productpurchase/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function print_preview() {
        if(permissionChecker('productpurchase_view')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            if((int)$id) {
                $this->data['productpurchase'] = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $id, 'schoolyearID' => $schoolyearID));
                
                $this->data['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
                
                $this->data['productpurchaseitems'] = $this->productpurchaseitem_m->get_order_by_productpurchaseitem(array('productpurchaseID' => $id, 'schoolyearID' => $schoolyearID));

                $this->data['productpurchasepaid'] = $this->productpurchasepaid_m->get_productpurchasepaid_sum('productpurchasepaidamount', array('productpurchaseID' => $id));


                if($this->data['productpurchase']) {
                    $this->data['createuser'] = getNameByUsertypeIDAndUserID($this->data['productpurchase']->create_usertypeID, $this->data['productpurchase']->create_userID);

                    $this->data['productsupplier'] = $this->productsupplier_m->get_single_productsupplier(array('productsupplierID' => $this->data['productpurchase']->productsupplierID));
                    $this->data['productwarehouse'] = $this->productwarehouse_m->get_single_productwarehouse(array('productwarehouseID' => $this->data['productpurchase']->productwarehouseID));

                    $this->reportPDF('productpurchasemodule.css', $this->data, 'productpurchase/print_preview');
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

    public function valid_data($data) {
        if($data != '') {
            if($data == 0) {
                $this->form_validation->set_message('valid_data','The %s field is required.');
                return FALSE;
            }
            return TRUE;
        } 
        return TRUE;
    }

    protected function send_mail_rules() {
        $rules = array(
            array(
                'field' => 'productpurchaseID',
                'label' => $this->lang->line('productpurchase_id'),
                'rules' => 'trim|required|xss_clean|numeric|callback_valid_data'
            ), array(
                'field' => 'to',
                'label' => $this->lang->line('to'),
                'rules' => 'trim|required|xss_clean|valid_email'
            ), array(
                'field' => 'subject',
                'label' => $this->lang->line('subject'),
                'rules' => 'trim|required|xss_clean'
            ), array(
                'field' => 'message',
                'label' => $this->lang->line('message'),
                'rules' => 'trim|xss_clean'
            )
        );
        return $rules;
    }

    public function send_mail() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('productpurchase_view')) {
            if($_POST) {
                $rules = $this->send_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $to         = $this->input->post('to');
                    $subject    = $this->input->post('subject');
                    $message    = $this->input->post('message');
                    $id         = $this->input->post('productpurchaseID');

                    $this->data['productpurchase'] = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $id, 'schoolyearID' => $schoolyearID));
            
                    $this->data['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
                    
                    $this->data['productpurchaseitems'] = $this->productpurchaseitem_m->get_order_by_productpurchaseitem(array('productpurchaseID' => $id, 'schoolyearID' => $schoolyearID));

                    $this->data['productpurchasepaid'] = $this->productpurchasepaid_m->get_productpurchasepaid_sum('productpurchasepaidamount', array('productpurchaseID' => $id));

                    if($this->data['productpurchase']) {
                        $this->data['createuser'] = getNameByUsertypeIDAndUserID($this->data['productpurchase']->create_usertypeID, $this->data['productpurchase']->create_userID);

                        $this->data['productsupplier'] = $this->productsupplier_m->get_single_productsupplier(array('productsupplierID' => $this->data['productpurchase']->productsupplierID));
                        $this->data['productwarehouse'] = $this->productwarehouse_m->get_single_productwarehouse(array('productwarehouseID' => $this->data['productpurchase']->productwarehouseID));

                        $this->reportSendToMail('productpurchasemodule.css', $this->data, 'productpurchase/print_preview', $to, $subject, $message);
                        $retArray['message'] = "Success";
                        $retArray['status'] = TRUE;
                        echo json_encode($retArray);
                        exit;
                    } else {
                        $retArray['message'] = $this->lang->line('productpurchase_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('productpurchase_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('productpurchase_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    public function download() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if(permissionChecker('productpurchase')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $productpurchase = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $id, 'schoolyearID' => $schoolyearID));
                $file = realpath('uploads/images/'.$productpurchase->productpurchasefile);
                $originalname = $productpurchase->productpurchasefileorginalname;
                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="'.basename($originalname).'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                } else {
                    redirect(base_url('productpurchase/index'));
                }
            } else {
                redirect(base_url('productpurchase/index'));
            }
        } else {
            redirect(base_url('productpurchase/index'));
        }
    }

    public function getproductpurchase() {
        $productcategoryID = $this->input->post('productcategoryID');
        if((int)$productcategoryID) {
            $products = $this->product_m->get_order_by_product(array('productcategoryID' => $productcategoryID));
            echo "<option value='0'>", $this->lang->line("productpurchase_select_product"),"</option>";
            foreach ($products as $product) {
                echo "<option value=\"$product->productID\">",$product->productname,"</option>";
            }
        }
    }

    public function unique_productsupplierID() {
        if($this->input->post('productsupplierID') == 0) {
            $this->form_validation->set_message("unique_productsupplierID", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_productwarehouseID() {
        if($this->input->post('productwarehouseID') == 0) {
            $this->form_validation->set_message("unique_productwarehouseID", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function date_valid($date) {
        if($date) {
            if(strlen($date) <10) {
                $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                return FALSE;
            } else {
                $arr = explode("-", $date);
                $dd = $arr[0];
                $mm = $arr[1];
                $yyyy = $arr[2];
                if(checkdate($mm, $dd, $yyyy)) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    public function unique_productitem() {
        $productitems = json_decode($this->input->post('productitem'));
        $status = FALSE;
        if(count($productitems)) {
            foreach ($productitems as $productitem) {
                if($productitem->unitprice != '' && $productitem->quantity != '') {
                    $status = TRUE;
                }
            }
        }

        if($status) {
            return TRUE;
        } else {
            $this->form_validation->set_message("unique_productitem", "The product item is required.");
            return FALSE;
        }
    }

    public function fileupload() {
        $id = $this->input->post('editID');
        $productpurchase = [];
        if((int)$id && $id > 0) {
            $productpurchase = $this->productpurchase_m->get_productpurchase($id);
        }

        $new_file = "";
        $original_file_name = '';
        if($_FILES["productpurchasefile"]['name'] !="") {
            $file_name = $_FILES["productpurchasefile"]['name'];
            $original_file_name = $file_name;
            $random = random19();
            $makeRandom = hash('sha512', $random.'productpurchase'.config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name'] = $new_file;
                $config['max_size'] = '2048';
                $config['max_width'] = '30000';
                $config['max_height'] = '30000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("productpurchasefile")) {
                    $this->form_validation->set_message("fileupload", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['file'] =  $this->upload->data();
                    $this->upload_data['file']['original_file_name'] = $original_file_name;
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("fileupload", "Invalid file");
                return FALSE;
            }
        } else {
            if(count($productpurchase)) {
                $this->upload_data['file'] = array('file_name' => $productpurchase->productpurchasefile);
                $this->upload_data['file']['original_file_name'] = $productpurchase->productpurchasefileorginalname;
                return TRUE;
            } else {
                $this->upload_data['file'] = array('file_name' => $new_file);
                $this->upload_data['file']['original_file_name'] = $original_file_name;
                return TRUE;
            }
        }
    }

    private function grandtotalandpaid($productpurchases, $schoolyearID) {
        $retArray = [];
        
        $productpurchaseitems = pluck_multi_array($this->productpurchaseitem_m->get_order_by_productpurchaseitem(array('schoolyearID' => $schoolyearID)), 'obj', 'productpurchaseID');

        $productpurchasepaids = pluck_multi_array($this->productpurchasepaid_m->get_order_by_productpurchasepaid(array('schoolyearID' => $schoolyearID)), 'obj', 'productpurchaseID');

        if(count($productpurchases)) {
            foreach ($productpurchases as $productpurchase) {
                if(isset($productpurchaseitems[$productpurchase->productpurchaseID])) {
                    if(count($productpurchaseitems[$productpurchase->productpurchaseID])) {
                        foreach ($productpurchaseitems[$productpurchase->productpurchaseID] as $productpurchaseitem) {
                            if(isset($retArray['grandtotal'][$productpurchaseitem->productpurchaseID])) {
                                $retArray['grandtotal'][$productpurchaseitem->productpurchaseID] = (($retArray['grandtotal'][$productpurchaseitem->productpurchaseID]) + ($productpurchaseitem->productpurchaseunitprice*$productpurchaseitem->productpurchasequantity));
                            } else {
                                $retArray['grandtotal'][$productpurchaseitem->productpurchaseID] = ($productpurchaseitem->productpurchaseunitprice*$productpurchaseitem->productpurchasequantity);
                            }
                        }
                    }
                }

                if(isset($productpurchasepaids[$productpurchase->productpurchaseID])) {
                    if(count($productpurchasepaids[$productpurchase->productpurchaseID])) {
                        foreach ($productpurchasepaids[$productpurchase->productpurchaseID] as $productpurchasepaid) {
                            if(isset($retArray['totalpaid'][$productpurchasepaid->productpurchaseID])) {
                                $retArray['totalpaid'][$productpurchasepaid->productpurchaseID] = (($retArray['totalpaid'][$productpurchasepaid->productpurchaseID]) + ($productpurchasepaid->productpurchasepaidamount));
                            } else {
                                $retArray['totalpaid'][$productpurchasepaid->productpurchaseID] = ($productpurchasepaid->productpurchasepaidamount);
                            }
                        }
                    }
                }
            }
        }
        return $retArray;
    }

    public function saveproductpurchase() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            $productpurchaseID = 0;
            $retArray['status'] = FALSE;
            if(permissionChecker('productpurchase_add') || permissionChecker('productpurchase_edit')) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $retArray['error'] = $this->form_validation->error_array();
                        $retArray['status'] = FALSE;
                        echo json_encode($retArray);
                        exit;
                    } else {
                        $schoolyearID = $this->session->userdata('defaultschoolyearID');
                        $array = array(
                            'schoolyearID' => $schoolyearID,
                            "productsupplierID" => $this->input->post("productsupplierID"),
                            "productwarehouseID" => $this->input->post("productwarehouseID"),
                            "productpurchasereferenceno" => $this->input->post("productpurchasereferenceno"),
                            "productpurchasedate" => date('Y-m-d', strtotime($this->input->post("productpurchasedate"))),
                            "productpurchasedescription" => $this->input->post("productpurchasedescription"),
                            "purchase_product_vat_percentage" => $this->input->post("productvatselectID"),
                            "purchase_product_vat_amount" => $this->input->post("productpurchasevat"),
                            "purchase_product_discount" => $this->input->post("productpurchasediscount"),
                            "productpurchasestatus" => 0,
                            "productpurchaserefund" => 0,
                            "productpurchasefile" => $this ->upload_data['file']['file_name'],
                            "productpurchasefileorginalname" => $this ->upload_data['file']['original_file_name'],
                            'create_date' => date('Y-m-d H:i:s'),
                            'modify_date' => date('Y-m-d H:i:s'),
                            'create_userID' => $this->session->userdata('loginuserID'),
                            'create_usertypeID' => $this->session->userdata('usertypeID')
                        );

                        $updateID = $this->input->post('editID');
                        if(permissionChecker('productpurchase_edit')) {
                            if($updateID > 0) {
                                $productpurchaseID = $updateID;
                                $this->productpurchaseitem_m->delete_productpurchaseitem_by_productpurchaseID($productpurchaseID);
                            } else {
                                $this->productpurchase_m->insert_productpurchase($array);
                                $productpurchaseID = $this->db->insert_id();
                            }
                        } else {
                            $this->productpurchase_m->insert_productpurchase($array);
                            $productpurchaseID = $this->db->insert_id();
                        }

                        $totalAmount = 0;
                        $productpurchaseitem = [];
                        $productitems = json_decode($this->input->post('productitem'));
                        if(count($productitems)) {

                            if($updateID == 0) {
                                $productitemschoolyearID = $schoolyearID;
                            } else {
                                $updatedata = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $updateID));
                                if(count($updatedata)) {
                                    $productitemschoolyearID = $updatedata->schoolyearID;
                                } else {
                                    $productitemschoolyearID = $schoolyearID;
                                }
                            }

                            foreach ($productitems as $productitem) {
                                if($productitem->unitprice != '' && $productitem->quantity != '') {
                                    $totalAmount += (($productitem->unitprice * $productitem->quantity));
                                    $productpurchaseitem[] = array(
                                        'schoolyearID' => $productitemschoolyearID,
                                        'productpurchaseID' => $productpurchaseID,
                                        'productID' => $productitem->productID,
                                        'productpurchaseunitprice' => $productitem->unitprice,
                                        'productpurchasequantity' => $productitem->quantity,
                                    );          
                                }
                            }
                        }

                        if(permissionChecker('productpurchase_edit')) {
                            if($updateID > 0) {
                                $productpurchasepaid = $this->productpurchasepaid_m->get_productpurchasepaid_sum('productpurchasepaidamount', array('productpurchaseID' => $updateID));
                                unset($array['schoolyearID'], $array['create_date'], $array['create_userID'], $array['create_usertypeID']);

                                if((float)$totalAmount == (float)$productpurchasepaid->productpurchasepaidamount) {
                                    $array['productpurchasestatus'] = 2;
                                } elseif((float)$productpurchasepaid->productpurchasepaidamount > 0 && ((float)$totalAmount > (float)$productpurchasepaid->productpurchasepaidamount)) {
                                    $array['productpurchasestatus'] = 1;
                                } elseif((float)$productpurchasepaid->productpurchasepaidamount > 0 && ((float)$totalAmount < (float)$productpurchasepaid->productpurchasepaidamount)) {
                                    $array['productpurchasestatus'] = 2;
                                }
                                $this->productpurchase_m->update_productpurchase($array, $updateID);
                            }
                        }

                        $this->productpurchaseitem_m->insert_batch_productpurchaseitem($productpurchaseitem);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        $retArray['status'] = TRUE;
                        $retArray['message'] = 'Success';
                        echo json_encode($retArray);
                        exit;
                    }
                } else {
                    $retArray['error'] = array('posttype', 'Post type is required.');
                    echo json_encode($retArray);
                    exit;
                }
            } else {
                $retArray['error'] = array('permission', 'Purchase permission is required.');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['error'] = array('permission', 'Purchase permission is required.');
            echo json_encode($retArray);
            exit;
        }
    }

    public function paymentlist() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $productpurchaseID = $this->input->post('productpurchaseID');

        $paymentmethodarray = array(
            1 => $this->lang->line('productpurchase_cash'),
            2 => $this->lang->line('productpurchase_cheque'),
            3 => $this->lang->line('productpurchase_credit_card'),
            4 => $this->lang->line('productpurchase_other'),
        );

        if(!empty($productpurchaseID) && (int)$productpurchaseID && $productpurchaseID > 0) {
            $productpurchase = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $productpurchaseID, 'schoolyearID' => $schoolyearID));
            if(count($productpurchase)) {
                $productpurchasepaids = $this->productpurchasepaid_m->get_order_by_productpurchasepaid(array('productpurchaseID' => $productpurchaseID));
                if(count($productpurchasepaids)) {
                    $i = 1; 
                    foreach ($productpurchasepaids as $productpurchasepaid) {
                        echo '<tr>';
                            echo '<td data-title="'.$this->lang->line('slno').'">';
                                echo $i;
                            echo '</td>';

                            echo '<td data-title="'.$this->lang->line('productpurchase_date').'">';
                                echo date('d M Y', strtotime($productpurchasepaid->productpurchasepaiddate));
                            echo '</td>';

                            echo '<td data-title="'.$this->lang->line('productpurchase_referenceno').'">';
                                echo namesorting($productpurchasepaid->productpurchasepaidreferenceno, 30);
                            echo '</td>';

                            echo '<td data-title="'.$this->lang->line('productpurchase_amount').'">';
                                echo number_format($productpurchasepaid->productpurchasepaidamount, 2);
                                if($productpurchasepaid->productpurchasepaidfile != "") {
                                    echo ' <a href="'.base_url("productpurchase/paymentfiledownload/".$productpurchasepaid->productpurchasepaidID).'" style="color:#428bca"><i class="fa fa-chain"></i></a>';
                                    
                                }
                            echo '</td>'; 

                            echo '<td data-title="'.$this->lang->line('productpurchase_paid_by').'">';
                                if(isset($paymentmethodarray[$productpurchasepaid->productpurchasepaidpaymentmethod])) {
                                    echo $paymentmethodarray[$productpurchasepaid->productpurchasepaidpaymentmethod];
                                }
                            echo '</td>';

                            if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
                                echo '<td data-title="'.$this->lang->line('action').'">';
                                    if($productpurchase->productpurchaserefund == 0) {
                                        if(permissionChecker('productpurchase_delete')) {
                                            echo '<a href="'.base_url('productpurchase/deletepurchasepaid/'.$productpurchasepaid->productpurchasepaidID).'" onclick="return confirm('."'".'you are about to delete a record. This cannot be undone. are you sure?'."'".')" class="btn btn-danger btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>';
                                        }
                                    }
                                echo '</td>';
                            }
                        echo '</tr>';

                        $i++;
                    }
                }
            }
        }
    }

    public function deletepurchasepaid() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            $productpurchasepaidID = htmlentities(escapeString($this->uri->segment(3)));
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            
            if(permissionChecker('productpurchase_delete')) {
                if((int)$productpurchasepaidID) {
                    $productpurchasepaid = $this->productpurchasepaid_m->get_single_productpurchasepaid(array('productpurchasepaidID' => $productpurchasepaidID));
                    if(count($productpurchasepaid)) {
                        $productpurchase = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $productpurchasepaid->productpurchaseID, 'schoolyearID' => $schoolyearID));
                        if(count($productpurchase) && $productpurchase->productpurchaserefund == 0) {

                            $this->productpurchasepaid_m->delete_productpurchasepaid($productpurchasepaidID);

                            $productpurchaseitemsum = $this->productpurchaseitem_m->get_productpurchaseitem_sum(array('productpurchaseID' => $productpurchase->productpurchaseID, 'schoolyearID' => $schoolyearID));

                            $productpurchasepaidsum = $this->productpurchasepaid_m->get_productpurchasepaid_sum('productpurchasepaidamount', array('productpurchaseID' => $productpurchase->productpurchaseID));

                            $array = [];
                            if($productpurchasepaidsum->productpurchasepaidamount == NULL) {
                                $array['productpurchasestatus'] = 0;
                            } elseif((float)$productpurchaseitemsum->result == (float)$productpurchasepaidsum->productpurchasepaidamount) {
                                $array['productpurchasestatus'] = 2;
                            } elseif((float)$productpurchasepaidsum->productpurchasepaidamount > 0 && ((float)$productpurchaseitemsum->result > (float)$productpurchasepaidsum->productpurchasepaidamount)) {
                                $array['productpurchasestatus'] = 1;
                            } elseif((float)$productpurchasepaidsum->productpurchasepaidamount > 0 && ((float)$productpurchaseitemsum->result < (float)$productpurchasepaidsum->productpurchasepaidamount)) {
                                $array['productpurchasestatus'] = 2;
                            }

                            $this->productpurchase_m->update_productpurchase($array, $productpurchase->productpurchaseID);

                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url('productpurchase/index'));
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

    public function paymentfiledownload() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if(permissionChecker('productpurchase')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $productpurchasepaid = $this->productpurchasepaid_m->get_single_productpurchasepaid(array('productpurchasepaidID' => $id, 'schoolyearID' => $schoolyearID));
                $file = realpath('uploads/images/'.$productpurchasepaid->productpurchasepaidfile);
                $originalname = $productpurchasepaid->productpurchasepaidorginalname;
                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="'.basename($originalname).'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                } else {
                    redirect(base_url('productpurchase/index'));
                }
            } else {
                redirect(base_url('productpurchase/index'));
            }
        } else {
            redirect(base_url('productpurchase/index'));
        }
    }

    public function getpurchaseinfo() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $productpurchaseID = $this->input->post('productpurchaseID');
        
        $retArray['status'] = FALSE;
        $retArray['dueamount'] = 0.00; 
        if(permissionChecker('productpurchase_add')) {
            if(!empty($productpurchaseID) && (int)$productpurchaseID && $productpurchaseID > 0) {
                $productpurchase = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $productpurchaseID, 'schoolyearID' => $schoolyearID));
                if(count($productpurchase)) {
                    if($productpurchase->productpurchaserefund == 0 && $productpurchase->productpurchasestatus != 2) {
                        $productpurchaseitemsum = $this->productpurchaseitem_m->get_productpurchaseitem_sum(array('productpurchaseID' => $productpurchaseID, 'schoolyearID' => $schoolyearID));
                        $productpurchasepaidsum = $this->productpurchasepaid_m->get_productpurchasepaid_sum('productpurchasepaidamount', array('productpurchaseID' => $productpurchaseID));

                        $retArray['dueamount'] = number_format((($productpurchaseitemsum->result) - ($productpurchasepaidsum->productpurchasepaidamount)), 2, '.', '');
                        $retArray['status'] = TRUE;
                    }
                }
            }
        }   

        echo json_encode($retArray);
        exit;
    }

    protected function rules_payment() {
        $rules = array(
            array(
                'field' => 'productpurchasepaiddate',
                'label' => $this->lang->line("productpurchase_date"),
                'rules' => 'trim|required|xss_clean|callback_date_valid'
            ),
            array(
                'field' => 'productpurchasepaidreferenceno',
                'label' => $this->lang->line("productpurchase_referenceno"),
                'rules' => 'trim|xss_clean|max_length[99]'
            ),
            array(
                'field' => 'productpurchasepaidamount',
                'label' => $this->lang->line("productpurchase_amount"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[15]'
            ),
            array(
                'field' => 'productpurchasepaidpaymentmethod',
                'label' => $this->lang->line("productpurchase_paymentmethod"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[1]|callback_valid_data'
            ),
            array(
                'field' => 'productpurchaseID',
                'label' => $this->lang->line("productpurchase_description"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[11]'
            ),
            array(
                'field' => 'productpurchasepaidfile',
                'label' => $this->lang->line("productpurchase_file"),
                'rules' => 'trim|xss_clean|max_length[200]|callback_paidfileupload'
            )
        );
        return $rules;
    }

    public function paidfileupload() {
        $new_file = "";
        $original_file_name = '';
        if($_FILES["productpurchasepaidfile"]['name'] !="") {
            $file_name = $_FILES["productpurchasepaidfile"]['name'];
            $original_file_name = $file_name;
            $random = random19();
            $makeRandom = hash('sha512', $random.'productpurchasepaidfile'.config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(count($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name'] = $new_file;
                $config['max_size'] = '2048';
                $config['max_width'] = '30000';
                $config['max_height'] = '30000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("productpurchasepaidfile")) {
                    $this->form_validation->set_message("fileupload", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['file'] =  $this->upload->data();
                    $this->upload_data['file']['original_file_name'] = $original_file_name;
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("fileupload", "Invalid file");
                return FALSE;
            }
        } else {
            $this->upload_data['file']['file_name'] = '';
            $this->upload_data['file']['original_file_name'] = '';
            return TRUE;
        }
    }

    public function saveproductpurchasepayment() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $productpurchaseID = 0;
        $retArray['status'] = FALSE;
        if(permissionChecker('productpurchase_add')) {
            $productpurchase = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $this->input->post('productpurchaseID')));

            if(count($productpurchase)) {
                if($productpurchase->productpurchaserefund == 0 && $productpurchase->productpurchasestatus != 2) {
                    if($_POST) {
                        $rules = $this->rules_payment();
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                            $retArray['error'] = $this->form_validation->error_array();
                            $retArray['status'] = FALSE;
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $array = array(
                                'schoolyearID' => $schoolyearID,
                                'productpurchasepaidschoolyearID' => $this->data['siteinfos']->school_year,
                                'productpurchaseID' => $this->input->post('productpurchaseID'),
                                'productpurchasepaiddate' => date('Y-m-d', strtotime($this->input->post("productpurchasepaiddate"))), 
                                'productpurchasepaidreferenceno' => $this->input->post('productpurchasepaidreferenceno'),
                                'productpurchasepaidamount' => $this->input->post('productpurchasepaidamount'),
                                'productpurchasepaidpaymentmethod  ' => $this->input->post('productpurchasepaidpaymentmethod'),
                                'productpurchasepaiddescription  ' => '',

                                "productpurchasepaidfile" => $this ->upload_data['file']['file_name'],
                                "productpurchasepaidorginalname" => $this ->upload_data['file']['original_file_name'],

                                'create_date' => date('Y-m-d H:i:s'),
                                'modify_date' => date('Y-m-d H:i:s'),
                                'create_userID' => $this->session->userdata('loginuserID'),
                                'create_usertypeID' => $this->session->userdata('usertypeID'),
                            );

                            $this->productpurchasepaid_m->insert_productpurchasepaid($array);

                            $productpurchaseitemsum = $this->productpurchaseitem_m->get_productpurchaseitem_sum(array('productpurchaseID' => $this->input->post('productpurchaseID'), 'schoolyearID' => $schoolyearID));

                            $productpurchasepaidsum = $this->productpurchasepaid_m->get_productpurchasepaid_sum('productpurchasepaidamount', array('productpurchaseID' => $this->input->post('productpurchaseID')));

                            $productpurchasearray['productpurchasestatus'] = 1; 
                            if((float)$productpurchaseitemsum->result == (float)$productpurchasepaidsum->productpurchasepaidamount) {
                                $productpurchasearray['productpurchasestatus'] = 2;
                            } elseif((float)$productpurchasepaidsum->productpurchasepaidamount > 0 && ((float)$productpurchaseitemsum->result > (float)$productpurchasepaidsum->productpurchasepaidamount)) {
                                $productpurchasearray['productpurchasestatus'] = 1;
                            } elseif((float)$productpurchasepaidsum->productpurchasepaidamount > 0 && ((float)$productpurchaseitemsum->result < (float)$productpurchasepaidsum->productpurchasepaidamount)) {
                                $productpurchasearray['productpurchasestatus'] = 2;
                            }

                            $this->productpurchase_m->update_productpurchase($productpurchasearray, $this->input->post('productpurchaseID'));
                        
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            $retArray['status'] = TRUE;
                            $retArray['message'] = 'Success';
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['error'] = array('posttype' => 'Post type is required.');
                        echo json_encode($retArray);
                        exit;
                    }
                } else {
                    $retArray['error'] = array('permission' => 'This invoice already fully paid.');
                    echo json_encode($retArray);
                    exit;
                }
            } else {
                $retArray['error'] = array('permission' => 'Purchase ID does not found.');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['error'] = array('permission' => 'Add payment permission is required.');
            echo json_encode($retArray);
            exit;
        }
    }
}
