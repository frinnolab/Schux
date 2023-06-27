<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productsale extends Admin_Controller {
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
        $this->load->model('usertype_m');
        $this->load->model('classes_m');
        $this->load->model('systemadmin_m');
        $this->load->model('teacher_m');
        $this->load->model('student_m');
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
        $this->lang->load('productsale', $language);
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
        $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
        $this->data['users'] = $this->getuserlist();
        $this->data['productsales'] = $this->productsale_m->get_order_by_productsale(array('schoolyearID' => $schoolyearID));
        $this->data['grandtotalandpaid'] = $this->grandtotalandpaid($this->data['productsales'], $schoolyearID);
        $this->data["subview"] = "productsale/index";
        $this->load->view('_layout_main', $this->data);
    }

    private function getuserlist() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $retArray = [];

        $systemadmins = $this->systemadmin_m->get_systemadmin();
        if(count($systemadmins)) {
            foreach ($systemadmins as $systemadmin) {
                $retArray[1][$systemadmin->systemadminID] = $systemadmin;
            }
        }

        $teachers = $this->teacher_m->get_teacher();
        if(count($teachers)) {
            foreach ($teachers as $teacher) {
                $retArray[2][$teacher->teacherID] = $teacher;
            }
        }

        $students = $this->studentrelation_m->get_order_by_studentrelation(array('srschoolyearID' => $schoolyearID));
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

    private function grandtotalandpaid($productsales, $schoolyearID) {
        $retArray = [];
        
        $productsaleKey = [];
        if(count($productsales)) {
            foreach ($productsales as $productsale) {
                $productsaleKey[] = $productsale->productsaleID;
            }
        }

        if(count($productsaleKey)) {
            $productsaleitems = pluck_multi_array($this->productsaleitem_m->get_order_by_productsaleitem(array('schoolyearID' => $schoolyearID)), 'obj', 'productsaleID');

            $productsalepaids = pluck_multi_array($this->productsalepaid_m->get_where_in_productsalepaid($productsaleKey, 'productsaleID'), 'obj', 'productsaleID');

            if(count($productsales)) {
                foreach ($productsales as $productsale) {
                    if(isset($productsaleitems[$productsale->productsaleID])) {
                        if(count($productsaleitems[$productsale->productsaleID])) {
                            foreach ($productsaleitems[$productsale->productsaleID] as $productpurchaseitem) {
                                if(isset($retArray['grandtotal'][$productpurchaseitem->productsaleID])) {
                                    $retArray['grandtotal'][$productpurchaseitem->productsaleID] = (($retArray['grandtotal'][$productpurchaseitem->productsaleID]) + ($productpurchaseitem->productsaleunitprice*$productpurchaseitem->productsalequantity));
                                } else {
                                    $retArray['grandtotal'][$productpurchaseitem->productsaleID] = ($productpurchaseitem->productsaleunitprice*$productpurchaseitem->productsalequantity);
                                }
                            }
                        }
                    }

                    if(isset($productsalepaids[$productsale->productsaleID])) {
                        if(count($productsalepaids[$productsale->productsaleID])) {
                            foreach ($productsalepaids[$productsale->productsaleID] as $productsalepaid) {
                                if(isset($retArray['totalpaid'][$productsalepaid->productsaleID])) {
                                    $retArray['totalpaid'][$productsalepaid->productsaleID] = (($retArray['totalpaid'][$productsalepaid->productsaleID]) + ($productsalepaid->productsalepaidamount));
                                } else {
                                    $retArray['totalpaid'][$productsalepaid->productsaleID] = ($productsalepaid->productsalepaidamount);
                                }
                            }
                        }
                    }
                }
            }  
        }

        return $retArray;
    }

    public function download() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if(permissionChecker('productsale')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $productsale = $this->productsale_m->get_single_productsale(array('productsaleID' => $id, 'schoolyearID' => $schoolyearID));
                $file = realpath('uploads/images/'.$productsale->productsalefile);
                $originalname = $productsale->productsalefileorginalname;
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
                    redirect(base_url('productsale/index'));
                }
            } else {
                redirect(base_url('productsale/index'));
            }
        } else {
            redirect(base_url('productsale/index'));
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
            $this->data['productsale'] = $this->productsale_m->get_single_productsale(array('productsaleID' => $id, 'schoolyearID' => $schoolyearID));
            
            $this->data['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
            
            $this->data['productsaleitems'] = $this->productsaleitem_m->get_order_by_productsaleitem(array('productsaleID' => $id, 'schoolyearID' => $schoolyearID));

            $this->data['productsalepaid'] = $this->productsalepaid_m->get_productsalepaid_sum('productsalepaidamount', array('productsaleID' => $id));

            if($this->data['productsale']) {
                $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
                $this->data['user'] = $this->getuserlistobj($this->data['productsale']->productsalecustomertypeID, $this->data['productsale']->productsalecustomerID, $schoolyearID);
                $this->data['createuser'] = getNameByUsertypeIDAndUserID($this->data['productsale']->create_usertypeID, $this->data['productsale']->create_userID);

                $this->data["subview"] = "productsale/view";
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

    public function getuserlistobj($usertypeID, $userID, $schoolyearID) {
        $user = [];
        if($usertypeID == 1) {
            $user = $this->systemadmin_m->get_single_systemadmin(array('systemadminID' => $userID));
        } elseif($usertypeID == 2) {
            $user = $this->teacher_m->get_single_teacher(array('teacherID' => $userID));
        } elseif($usertypeID == 3) {
            $user = $this->studentrelation_m->get_studentrelation_join_student(array('srstudentID' => $userID, 'srschoolyearID' => $schoolyearID), TRUE);
        } elseif($usertypeID == 4) {
            $user = $this->parents_m->get_single_parents(array('parentsID' => $userID));
        } else {
            $user = $this->user_m->get_single_user(array('usertypeID' => $usertypeID, 'userID' => $userID));
        }

        return $user;
    }

    public function print_preview() {
        if(permissionChecker('productsale_view')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            if((int)$id) {
                $this->data['productsale'] = $this->productsale_m->get_single_productsale(array('productsaleID' => $id, 'schoolyearID' => $schoolyearID));
                
                $this->data['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
                
                $this->data['productsaleitems'] = $this->productsaleitem_m->get_order_by_productsaleitem(array('productsaleID' => $id, 'schoolyearID' => $schoolyearID));

                $this->data['productsalepaid'] = $this->productsalepaid_m->get_productsalepaid_sum('productsalepaidamount', array('productsaleID' => $id));

                if($this->data['productsale']) {
                    $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
                    $this->data['user'] = $this->getuserlistobj($this->data['productsale']->productsalecustomertypeID, $this->data['productsale']->productsalecustomerID, $schoolyearID);

                    $this->data['createuser'] = getNameByUsertypeIDAndUserID($this->data['productsale']->create_usertypeID, $this->data['productsale']->create_userID);

                    $this->reportPDF('productsalemodule.css', $this->data, 'productsale/print_preview');
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

    protected function send_mail_rules() {
        $rules = array(
            array(
                'field' => 'productsaleID',
                'label' => $this->lang->line('productsale_id'),
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
        if(permissionChecker('productsale_view')) {
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
                    $id         = $this->input->post('productsaleID');

                    $this->data['productsale'] = $this->productsale_m->get_single_productsale(array('productsaleID' => $id, 'schoolyearID' => $schoolyearID));
            
                    $this->data['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
                    
                    $this->data['productsaleitems'] = $this->productsaleitem_m->get_order_by_productsaleitem(array('productsaleID' => $id, 'schoolyearID' => $schoolyearID));

                    $this->data['productsalepaid'] = $this->productsalepaid_m->get_productsalepaid_sum('productsalepaidamount', array('productsaleID' => $id));

                    if($this->data['productsale']) {
                        $this->data['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
                        $this->data['user'] = $this->getuserlistobj($this->data['productsale']->productsalecustomertypeID, $this->data['productsale']->productsalecustomerID, $schoolyearID);
                        $this->data['createuser'] = getNameByUsertypeIDAndUserID($this->data['productsale']->create_usertypeID, $this->data['productsale']->create_userID);

                        $this->reportSendToMail('productsalemodule.css', $this->data, 'productsale/print_preview', $to, $subject, $message);
                        $retArray['message'] = "Success";
                        $retArray['status'] = TRUE;
                        echo json_encode($retArray);
                        exit;
                    } else {
                        $retArray['message'] = $this->lang->line('productsale_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('productsale_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('productsale_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    public function paymentlist() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $productsaleID = $this->input->post('productsaleID');

        $paymentmethodarray = array(
            1 => $this->lang->line('productsale_cash'),
            2 => $this->lang->line('productsale_cheque'),
            3 => $this->lang->line('productsale_credit_card'),
            4 => $this->lang->line('productsale_other'),
        );

        $productsale = $this->productsale_m->get_single_productsale(array('productsaleID' => $productsaleID, 'schoolyearID' => $schoolyearID));
        if(count($productsale)) {
            if(!empty($productsaleID) && (int)$productsaleID && $productsaleID > 0) {
                $productsalepaids = $this->productsalepaid_m->get_order_by_productsalepaid(array('productsaleID' => $productsaleID));
                if(count($productsalepaids)) {
                    $i = 1; 
                    foreach ($productsalepaids as $productsalepaid) {
                        echo '<tr>';
                            echo '<td data-title="'.$this->lang->line('slno').'">';
                                echo $i;
                            echo '</td>';

                            echo '<td data-title="'.$this->lang->line('productsale_date').'">';
                                echo date('d M Y', strtotime($productsalepaid->productsalepaiddate));
                            echo '</td>';

                            echo '<td data-title="'.$this->lang->line('productsale_referenceno').'">';
                                echo $productsalepaid->productsalepaidreferenceno;
                            echo '</td>';

                            echo '<td data-title="'.$this->lang->line('productsale_amount').'">';
                                echo number_format($productsalepaid->productsalepaidamount, 2);
                                if($productsalepaid->productsalepaidfile != "") {
                                    echo ' <a href="'.base_url("productsale/paymentfiledownload/".$productsalepaid->productsalepaidID).'" style="color:#428bca"><i class="fa fa-chain"></i></a>';
                                    
                                }
                            echo '</td>'; 

                            echo '<td data-title="'.$this->lang->line('productsale_paid_by').'">';
                                if(isset($paymentmethodarray[$productsalepaid->productsalepaidpaymentmethod])) {
                                    echo $paymentmethodarray[$productsalepaid->productsalepaidpaymentmethod];
                                }
                            echo '</td>';

                            if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
                                echo '<td data-title="'.$this->lang->line('action').'">';
                                    if($productsale->productsalerefund == 0) {
                                        if(permissionChecker('productsale_delete')) {
                                            echo '<a href="'.base_url('productsale/deletesalepaid/'.$productsalepaid->productsalepaidID).'" onclick="return confirm('."'".'you are about to delete a record. This cannot be undone. are you sure?'."'".')" class="btn btn-danger btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="'.$this->lang->line('delete').'"><i class="fa fa-trash-o"></i></a>';
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

    public function deletesalepaid() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            $productsalepaidID = htmlentities(escapeString($this->uri->segment(3)));
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            
            if(permissionChecker('productsale_delete')) {
                if((int)$productsalepaidID) {
                    $productsalepaid = $this->productsalepaid_m->get_single_productsalepaid(array('productsalepaidID' => $productsalepaidID));

                    if(count($productsalepaid)) {
                        $productsale = $this->productsale_m->get_single_productsale(array('productsaleID' => $productsalepaid->productsaleID, 'schoolyearID' => $schoolyearID));
                        if(count($productsale) && $productsale->productsalerefund == 0) {

                            $this->productsalepaid_m->delete_productsalepaid($productsalepaidID);

                            $productsaleitemsum = $this->productsaleitem_m->get_productsaleitem_sum(array('productsaleID' => $productsale->productsaleID, 'schoolyearID' => $schoolyearID));

                            $productsalepaidsum = $this->productsalepaid_m->get_productsalepaid_sum('productsalepaidamount', array('productsaleID' => $productsale->productsaleID));

                            $array = [];
                            if($productsalepaidsum->productsalepaidamount == NULL) {
                                $array['productsalestatus'] = 1;
                            } elseif((float)$productsaleitemsum->result == (float)$productsalepaidsum->productsalepaidamount) {
                                $array['productsalestatus'] = 3;
                            } elseif((float)$productsalepaidsum->productsalepaidamount > 0 && ((float)$productsaleitemsum->result > (float)$productsalepaidsum->productsalepaidamount)) {
                                $array['productsalestatus'] = 2;
                            } elseif((float)$productsalepaidsum->productsalepaidamount > 0 && ((float)$productsaleitemsum->result < (float)$productsalepaidsum->productsalepaidamount)) {
                                $array['productsalestatus'] = 3;
                            }

                            $this->productsale_m->update_productsale($array, $productsale->productsaleID);
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));

                            redirect(base_url('productsale/index'));
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

    public function getsaleinfo() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $productsaleID = $this->input->post('productsaleID');
        
        $retArray['status'] = FALSE;
        $retArray['dueamount'] = 0.00; 
        if(permissionChecker('productsale_add')) {
            if(!empty($productsaleID) && (int)$productsaleID && $productsaleID > 0) {
                $productsale = $this->productsale_m->get_single_productsale(array('productsaleID' => $productsaleID, 'schoolyearID' => $schoolyearID));
                if(count($productsale)) {
                    if($productsale->productsalerefund == 0 && $productsale->productsalestatus != 3) {
                        $productsaleitemsum = $this->productsaleitem_m->get_productsaleitem_sum(array('productsaleID' => $productsaleID, 'schoolyearID' => $schoolyearID));
                        $productsalepaidsum = $this->productsalepaid_m->get_productsalepaid_sum('productsalepaidamount', array('productsaleID' => $productsaleID));

                        $retArray['dueamount'] = number_format((($productsaleitemsum->result) - ($productsalepaidsum->productsalepaidamount)), 2, '.', '');
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
                'field' => 'productsalepaiddate',
                'label' => $this->lang->line("productsale_date"),
                'rules' => 'trim|required|xss_clean|callback_date_valid'
            ),
            array(
                'field' => 'productsalepaidreferenceno',
                'label' => $this->lang->line("productsale_referenceno"),
                'rules' => 'trim|xss_clean|max_length[99]'
            ),
            array(
                'field' => 'productsalepaidamount',
                'label' => $this->lang->line("productsale_amount"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[15]'
            ),
            array(
                'field' => 'productsalepaidpaymentmethod',
                'label' => $this->lang->line("productsale_paymentmethod"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[1]|callback_valid_data'
            ),
            array(
                'field' => 'productsaleID',
                'label' => $this->lang->line("productsale_description"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[11]'
            ),
            array(
                'field' => 'productsalepaidfile',
                'label' => $this->lang->line("productsale_file"),
                'rules' => 'trim|xss_clean|max_length[200]|callback_paidfileupload'
            )
        );
        return $rules;
    }

    public function paidfileupload() {
        $new_file = "";
        $original_file_name = '';
        if($_FILES["productsalepaidfile"]['name'] !="") {
            $file_name = $_FILES["productsalepaidfile"]['name'];
            $original_file_name = $file_name;
            $random = random19();
            $makeRandom = hash('sha512', $random.'productsalepaidfile'.config_item("encryption_key"));
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
                if(!$this->upload->do_upload("productsalepaidfile")) {
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

    public function saveproductsalepayment() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $productsaleID = 0;
        $retArray['status'] = FALSE;
        if(permissionChecker('productsale_add')) {
            $productsale = $this->productsale_m->get_single_productsale(array('productsaleID' => $this->input->post('productsaleID')));

            if(count($productsale)) {
                if($productsale->productsalerefund == 0 && $productsale->productsalestatus != 3) {
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
                                'productsalepaidschoolyearID' => $this->data['siteinfos']->school_year,
                                'productsaleID' => $this->input->post('productsaleID'),
                                'productsalepaiddate' => date('Y-m-d', strtotime($this->input->post("productsalepaiddate"))), 
                                'productsalepaidreferenceno' => $this->input->post('productsalepaidreferenceno'),
                                'productsalepaidamount' => $this->input->post('productsalepaidamount'),
                                'productsalepaidpaymentmethod' => $this->input->post('productsalepaidpaymentmethod'),
                                'productsalepaiddescription' => '',
                                "productsalepaidfile" => $this ->upload_data['file']['file_name'],
                                "productsalepaidorginalname" => $this ->upload_data['file']['original_file_name'],
                                'create_date' => date('Y-m-d H:i:s'),
                                'modify_date' => date('Y-m-d H:i:s'),
                                'create_userID' => $this->session->userdata('loginuserID'),
                                'create_usertypeID' => $this->session->userdata('usertypeID')
                            );

                            $this->productsalepaid_m->insert_productsalepaid($array);

                            $productsaleitemsum = $this->productsaleitem_m->get_productsaleitem_sum(array('productsaleID' => $this->input->post('productsaleID'), 'schoolyearID' => $schoolyearID));

                            $productsalepaidsum = $this->productsalepaid_m->get_productsalepaid_sum('productsalepaidamount', array('productsaleID' => $this->input->post('productsaleID')));

                            $productsalearray['productsalestatus'] = 1; 
                            if((float)$productsaleitemsum->result == (float)$productsalepaidsum->productsalepaidamount) {
                                $productsalearray['productsalestatus'] = 3;
                            } elseif((float)$productsalepaidsum->productsalepaidamount > 0 && ((float)$productsaleitemsum->result > (float)$productsalepaidsum->productsalepaidamount)) {
                                $productsalearray['productsalestatus'] = 2;
                            } elseif((float)$productsalepaidsum->productsalepaidamount > 0 && ((float)$productsaleitemsum->result < (float)$productsalepaidsum->productsalepaidamount)) {
                                $productsalearray['productsalestatus'] = 3;
                            }

                            $this->productsale_m->update_productsale($productsalearray, $this->input->post('productsaleID'));
                        
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
                $retArray['error'] = array('permission' => 'Sale ID does not found.');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['error'] = array('permission' => 'Add payment permission is required.');
            echo json_encode($retArray);
            exit;
        }
    }

    public function paymentfiledownload() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if(permissionChecker('productsale')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $productsalepaid = $this->productsalepaid_m->get_single_productsalepaid(array('productsalepaidID' => $id, 'schoolyearID' => $schoolyearID));
                $file = realpath('uploads/images/'.$productsalepaid->productsalepaidfile);
                $originalname = $productsalepaid->productsalepaidorginalname;
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
                    redirect(base_url('productsale/index'));
                }
            } else {
                redirect(base_url('productsale/index'));
            }
        } else {
            redirect(base_url('productsale/index'));
        }
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
            
            $this->data['usertypes'] = $this->usertype_m->get_usertype();
            $this->data['classes'] = $this->classes_m->general_get_classes();
            $this->data['productcategorys'] = $this->productcategory_m->get_productcategory();
            $this->data['productobj'] = json_encode(pluck($this->product_m->get_product(), 'obj', 'productID'));
            
            $this->data['productpurchasequintity'] = json_encode(pluck($this->productpurchaseitem_m->get_productpurchaseitem_quantity(), 'obj', 'productID'));

            $this->data['productsalequintity'] = json_encode(pluck($this->productsaleitem_m->get_productsaleitem_quantity(), 'obj', 'productID'));
            
            $this->data["subview"] = "productsale/add";
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
                $this->data['productsaleID'] = $id;
                $this->data['productsale'] = $this->productsale_m->get_single_productsale(array('productsaleID' => $id, 'schoolyearID' => $schoolyearID));

                $this->data['usertypes'] = $this->usertype_m->get_usertype();
                $this->data['classes'] = $this->classes_m->general_get_classes();
                $this->data['productcategorys'] = $this->productcategory_m->get_productcategory();

                $this->data['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
                $this->data['productobj'] = json_encode(pluck($this->product_m->get_product(), 'obj', 'productID'));

                $this->data['productpurchasequintity'] = json_encode(pluck($this->productpurchaseitem_m->get_productpurchaseitem_quantity(), 'obj', 'productID'));

                $this->data['productsalequintity'] = json_encode(pluck($this->productsaleitem_m->get_productsaleitem_quantity(), 'obj', 'productID'));

                $this->data['productsalepaid'] = $this->productsalepaid_m->get_productsalepaid_sum('productsalepaidamount', array('productsaleID' => $id));


                if(count($this->data['productsale'])) {
                    $this->data['productsalequintityforedit'] = json_encode(pluck($this->productsaleitem_m->get_productsaleitem_quantity($this->data['productsale']->productsaleID), 'obj', 'productID'));


                    if($this->data['productsale']->productsalecustomertypeID == 3) {
                        $srstudent = $this->studentrelation_m->get_single_studentrelation(array('srstudentID' => $this->data['productsale']->productsalecustomerID, 'srschoolyearID' => $schoolyearID));
                        if(count($srstudent)) {
                            $this->data['classesID'] = $srstudent->srclassesID;
                        } else {
                            $this->data['classesID'] = 0;
                        }
                    } else {
                        $this->data['classesID'] = 0;
                    }

                    $this->data['productsalecustomers'] = $this->getuserlistbyrole($this->data['productsale']->productsalecustomertypeID, $this->data['classesID']);

                    if(($this->data['productsale']->productsalerefund == 0) && ($this->data['productsalepaid']->productsalepaidamount == NULL)) {
                        $this->data['productsaleitems'] = $this->productsaleitem_m->get_order_by_productsaleitem(array('schoolyearID' => $schoolyearID, 'productsaleID' => $id));
                        $this->data["subview"] = "productsale/edit";
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

    public function getuserlistbyrole($usertypeID, $classesID = 0, $obj = FALSE)  {
        $userArray = [];
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        if($usertypeID == 1) {
            $systemadmins = $this->systemadmin_m->get_systemadmin();
            if($obj == FALSE) {
                $userArray = array(0 => $this->lang->line("productsale_select_user"));
                if(count($systemadmins)) {
                    foreach ($systemadmins as $systemadmin) {
                        $userArray[$systemadmin->systemadminID] = $systemadmin->name;
                    }
                }
            } else {
                $userArray = $systemadmins;
            }
        } elseif($usertypeID == 2) {
            $teachers = $this->teacher_m->get_teacher();
            if($obj == FALSE) {
                $userArray = array(0 => $this->lang->line("productsale_select_user"));
                if(count($teachers)) {
                    foreach ($teachers as $teacher) {
                        $userArray[$teacher->teacherID] = $teacher->name;
                    }
                }
            } else {
                $userArray = $teachers;
            }
        } elseif($usertypeID == 3) {
            if($classesID == 0) {
                $students = $this->studentrelation_m->get_order_by_studentrelation(array('srschoolyearID' => $schoolyearID));
            } else {
                $students = $this->studentrelation_m->get_order_by_studentrelation(array('srschoolyearID' => $schoolyearID, 'srclassesID' => $classesID));
            }

            if($obj == FALSE) {
                $userArray = array(0 => $this->lang->line("productsale_select_user"));
                if(count($students)) {
                    foreach ($students as $student) {
                        $userArray[$student->srstudentID] = $student->srname.' - '.$this->lang->line('productsale_roll').' - '.$student->srroll;
                    }
                }
            } else {
                $userArray = $students;
            }
        } elseif($usertypeID == 4) {
            $parents = $this->parents_m->get_parents();
            if($obj == FALSE) {
                $userArray = array(0 => $this->lang->line("productsale_select_user"));
                if(count($parents)) {
                    foreach ($parents as $parent) {
                        $userArray[$parent->parentsID] = $parent->name;
                    }
                }
            } else {
                $userArray = $parents;
            }
        } else {
            $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
            if($obj == FALSE) {
                $userArray = array(0 => $this->lang->line("productsale_select_user"));
                if(count($users)) {
                    foreach ($users as $user) {
                        $userArray[$user->userID] = $user->name;
                    }
                }
            } else {
                $userArray = $users;
            }
        }

        return $userArray;
    }

    public function getproductsale() {
        $productcategoryID = $this->input->post('productcategoryID');
        if((int)$productcategoryID) {
            $products = $this->product_m->get_order_by_product(array('productcategoryID' => $productcategoryID));
            echo "<option value='0'>", $this->lang->line("productsale_select_product"),"</option>";
            foreach ($products as $product) {
                echo "<option value=\"$product->productID\">",$product->productname,"</option>";
            }
        }
    }

    public function getuser() {
        $productsalecustomertypeID = $this->input->post('productsalecustomertypeID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        

        echo "<option value=\"0\">",$this->lang->line('productsale_select_user'),"</option>";
        if((int)$productsalecustomertypeID) {
            if($productsalecustomertypeID == 1) {
                $systemadmins = $this->systemadmin_m->get_systemadmin();
                if(count($systemadmins)) {
                    foreach ($systemadmins as $systemadmin) {
                        echo "<option value=\"$systemadmin->systemadminID\">",$systemadmin->name,"</option>";
                    }
                }
            } elseif($productsalecustomertypeID == 2) {
                $teachers = $this->teacher_m->get_teacher();
                if(count($teachers)) {
                    foreach ($teachers as $teacher) {
                        echo "<option value=\"$teacher->teacherID\">",$teacher->name,"</option>";
                    }
                }
            } elseif($productsalecustomertypeID == 3) {
                $classesID = $this->input->post('productsaleclassesID');
                if($this->input->post('productsaleusercalltype') == 'edit') {
                    $this->db->order_by('srroll', 'asc');
                    $students = $this->studentrelation_m->get_order_by_studentrelation(array('srschoolyearID' => $schoolyearID, 'srclassesID' => $classesID));
                    if(count($students)) {
                        foreach ($students as $student) {
                            if(!empty($student->studentID)) {
                                echo "<option value=\"$student->srstudentID\">".$student->srname." - ".$this->lang->line('productsale_roll')." - ".$student->srroll."</option>";
                            }
                        }
                    }
                } else {
                    $students = $this->studentrelation_m->get_order_by_studentrelation(array('srschoolyearID' => $schoolyearID, 'srclassesID' => $classesID));
                    if(count($students)) {
                        foreach ($students as $student) {
                            echo "<option value=\"$student->srstudentID\">".$student->srname." - ".$this->lang->line('productsale_roll')." - ".$student->srroll."</option>";
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

    protected function rules($paymentStatus = 0) {
        $rules = array(
            array(
                'field' => 'productsalecustomertypeID',
                'label' => $this->lang->line("productsale_role"),
                'rules' => 'trim|required|xss_clean|max_length[11]|numeric|callback_valid_data'
            ),
            array(
                'field' => 'productsalecustomerID',
                'label' => $this->lang->line("productsale_user"),
                'rules' => 'trim|required|xss_clean|max_length[11]|numeric|callback_valid_data'
            ),
            array(
                'field' => 'productsalereferenceno',
                'label' => $this->lang->line("productsale_referenceno"),
                'rules' => 'trim|xss_clean|max_length[99]'
            ),
            array(
                'field' => 'productsaledate',
                'label' => $this->lang->line("productsale_date"),
                'rules' => 'trim|required|xss_clean|max_length[11]|callback_date_valid'
            ),
            array(
                'field' => 'productsalepaymentstatusID',
                'label' => $this->lang->line("productsale_payment_status"),
                'rules' => 'trim|required|xss_clean|max_length[11]|callback_valid_data'
            ),
            
            array(
                'field' => 'productsalefile',
                'label' => $this->lang->line("productsale_file"),
                'rules' => 'trim|xss_clean|max_length[200]|callback_fileupload'
            ),
            array(
                'field' => 'productsaledescription',
                'label' => $this->lang->line("productsale_description"),
                'rules' => 'trim|xss_clean|max_length[520]'
            ),
            array(
                'field' => 'productitem',
                'label' => $this->lang->line("productsale_productitem"),
                'rules' => 'trim|xss_clean|callback_unique_productitem|callback_unique_productitemadjust'
            ),
            array(
                'field' => 'editID',
                'label' => $this->lang->line("productsale_editid"),
                'rules' => 'trim|required|xss_clean|numeric'
            )
        );

        if($paymentStatus != 0 && $paymentStatus != 1) {
            $rules[] = array(
                'field' => 'productsalepaidreferenceno',
                'label' => $this->lang->line("productsale_referenceno"),
                'rules' => 'trim|xss_clean|max_length[99]'
            );

            $rules[] = array(
                'field' => 'productsalepaidamount',
                'label' => $this->lang->line("productsale_amount"),
                'rules' => 'trim|required|xss_clean|max_length[15]|numeric'
            );

            $rules[] = array(
                'field' => 'productsalepaidpaymentmethod',
                'label' => $this->lang->line("productsale_paymentmethod"),
                'rules' => 'trim|required|xss_clean|max_length[11]|numeric|callback_valid_data'
            );
        } 

        return $rules;
    }

    public function valid_data($data) {
        if($data == 0) {
            $this->form_validation->set_message('valid_data','The %s field is required.');
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

    public function unique_productitemadjust() {
        $productpurchasequintity = pluck($this->productpurchaseitem_m->get_productpurchaseitem_quantity(), 'obj', 'productID');
       
        $productsalequintity = pluck($this->productsaleitem_m->get_productsaleitem_quantity(), 'obj', 'productID');

        $editID = $this->input->post('editID');
        if($editID > 0) {
            $productsalequintityforedit = pluck($this->productsaleitem_m->get_productsaleitem_quantity($editID), 'obj', 'productID');
        }

        $productobj = pluck($this->product_m->get_product(), 'obj', 'productID');
        $productitems = json_decode($this->input->post('productitem'));
        $productAmountArray = [];
        if(count($productitems)) {
            foreach ($productitems as $productitem) {
                if($productitem->unitprice != '' && $productitem->quantity != '') {
                    if(strlen($productitem->unitprice) <= 15 && strlen($productitem->quantity) <= 15) {
                        if(isset($productAmountArray[$productitem->productID])) {
                            $productAmountArray[$productitem->productID] = ($productAmountArray[$productitem->productID] + $productitem->quantity);
                        } else {
                            $productAmountArray[$productitem->productID] = $productitem->quantity;
                        }

                        if(isset($productAmountArray[$productitem->productID])) {
                            if(isset($productpurchasequintity[$productitem->productID])) {
                                $get_product_purchase_quintity = $productpurchasequintity[$productitem->productID]->quantity;
                            } else {
                                $get_product_purchase_quintity = 0;
                            }

                            if(isset($productsalequintity[$productitem->productID])) {
                                $get_product_sale_quintity = $productsalequintity[$productitem->productID]->quantity;
                            } else {
                                $get_product_sale_quintity = 0;
                            }

                            if($editID > 0) {
                                if(isset($productsalequintityforedit[$productitem->productID])) {
                                    $get_product_sale_quintity_for_edit = $productsalequintityforedit[$productitem->productID]->quantity;
                                } else {
                                    $get_product_sale_quintity_for_edit = 0; 
                                }
                            }

                            if($editID > 0) {
                                $totalQuantity = ($get_product_purchase_quintity - $get_product_sale_quintity + $get_product_sale_quintity_for_edit); 
                            } else {
                                $totalQuantity = ($get_product_purchase_quintity - $get_product_sale_quintity); 
                            }

                            if($productAmountArray[$productitem->productID] > $totalQuantity) {
                                if(isset($productobj[$productitem->productID])) {
                                    $this->form_validation->set_message("unique_productitemadjust", "The ". $productobj[$productitem->productID]->productname." is stock out.");
                                } else {
                                    $this->form_validation->set_message("unique_productitemadjust", "The product is stock out.");
                                }
                                return FALSE;
                            }      
                        } else {
                            $this->form_validation->set_message("unique_productitemadjust", "The product not found.");
                            return FALSE;
                        }
                    } else {
                        $this->form_validation->set_message("unique_productitemadjust", "Unit price and quantity ccannot exceed 15 characters in length.");
                        return FALSE;
                    }
                }
            }
        }

        return TRUE;
    }

    public function fileupload() {
        $id = $this->input->post('editID');
        $productsale = [];
        if((int)$id && $id > 0) {
            $productsale = $this->productsale_m->get_productsale($id);
        }

        $new_file = "";
        $original_file_name = '';
        if($_FILES["productsalefile"]['name'] !="") {
            $file_name = $_FILES["productsalefile"]['name'];
            $original_file_name = $file_name;
            $random = random19();
            $makeRandom = hash('sha512', $random.'productsale'.config_item("encryption_key"));
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
                if(!$this->upload->do_upload("productsalefile")) {
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
            if(count($productsale)) {
                $this->upload_data['file'] = array('file_name' => $productsale->productsalefile);
                $this->upload_data['file']['original_file_name'] = $productsale->productsalefileorginalname;
                return TRUE;
            } else {
                $this->upload_data['file'] = array('file_name' => $new_file);
                $this->upload_data['file']['original_file_name'] = $original_file_name;
                return TRUE;
            }
        }
    }

    public function saveproductsale() {
        $productpurchaseID = 0;
        $retArray['status'] = FALSE;
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            if(permissionChecker('productsale_add') || permissionChecker('productsale_edit')) {
                if($_POST) {
                    $rules = $this->rules($this->input->post('productsalepaymentstatusID'));
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
                            "productsalecustomertypeID" => $this->input->post("productsalecustomertypeID"),
                            "productsalecustomerID" => $this->input->post("productsalecustomerID"),
                            "productsalereferenceno" => $this->input->post("productsalereferenceno"),
                            "productsaledate" => date('Y-m-d', strtotime($this->input->post("productsaledate"))),
                            "productsaledescription" => $this->input->post("productsaledescription"),
                            "productsalestatus" => $this->input->post("productsalepaymentstatusID"),
                            "productsalerefund" => 0,
                            "productsalefile" => $this ->upload_data['file']['file_name'],
                            "productsalefileorginalname" => $this ->upload_data['file']['original_file_name'],
                            'create_date' => date('Y-m-d H:i:s'),
                            'modify_date' => date('Y-m-d H:i:s'),
                            'create_userID' => $this->session->userdata('loginuserID'),
                            'create_usertypeID' => $this->session->userdata('usertypeID')
                        );

                        $updateID = $this->input->post('editID');
                        if(permissionChecker('productsale_edit')) {
                            if($updateID > 0) {
                                $productsaleID = $updateID;
                                $this->productsaleitem_m->delete_productsaleitem_by_productsaleID($productsaleID);
                            } else {
                                $this->productsale_m->insert_productsale($array);
                                $productsaleID = $this->db->insert_id();
                            }
                        } else {
                            $this->productsale_m->insert_productsale($array);
                            $productsaleID = $this->db->insert_id();
                        }

                        $totalAmount = 0;
                        $productsaleitem = [];
                        $productitems = json_decode($this->input->post('productitem'));
                        if(count($productitems)) {
                            if($updateID == 0) {
                                $productitemschoolyearID = $schoolyearID;
                            } else {
                                $updatedata = $this->productsale_m->get_single_productsale(array('productsaleID' => $updateID));
                                if(count($updatedata)) {
                                    $productitemschoolyearID = $updatedata->schoolyearID;
                                } else {
                                    $productitemschoolyearID = $schoolyearID;
                                }
                            }
                            foreach ($productitems as $productitem) {
                                if($productitem->unitprice != '' && $productitem->quantity != '') {
                                    $totalAmount += (($productitem->unitprice * $productitem->quantity));
                                    $productsaleitem[] = array(
                                        'schoolyearID' => $productitemschoolyearID,
                                        'productsaleID' => $productsaleID,
                                        'productID' => $productitem->productID,
                                        'productsaleunitprice' => $productitem->unitprice,
                                        'productsalequantity' => $productitem->quantity,
                                    );
                                }
                            }
                        }

                        if(($this->input->post('productsalepaymentstatusID') != 1) && ($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID'))) {
                            if($updateID == 0) {
                                if($this->input->post('productsalepaymentstatusID') > 1) {
                                    $productsalepaidamount = $this->input->post('productsalepaidamount');
                                    if((float)$totalAmount == (float)$productsalepaidamount) {
                                        $array['productsalestatus'] = 3;
                                    } elseif((float)$productsalepaidamount > 0 && ((float)$totalAmount > (float)$productsalepaidamount)) {
                                        $array['productsalestatus'] = 2;
                                    } elseif((float)$productsalepaidamount > 0 && ((float)$totalAmount < (float)$productsalepaidamount)) {
                                        $array['productsalestatus'] = 3;
                                    }

                                    unset($array['schoolyearID'], $array['create_date'], $array['create_userID'], $array['create_usertypeID']);
                                    $this->productsale_m->update_productsale($array, $productsaleID);

                                    $productsalepaidArray = array(
                                        'productsalepaidschoolyearID' =>  $this->data['siteinfos']->school_year,
                                        'schoolyearID' =>  $this->session->userdata('defaultschoolyearID'),
                                        'productsaleID' => $productsaleID,
                                        'productsalepaiddate' => date('Y-m-d', strtotime($this->input->post("productsaledate"))),
                                        'productsalepaidreferenceno' => $this->input->post("productsalepaidreferenceno"),
                                        'productsalepaidamount' => $this->input->post("productsalepaidamount"),
                                        'productsalepaidpaymentmethod' => $this->input->post("productsalepaidpaymentmethod"),
                                        'productsalepaidfile' => '',
                                        'productsalepaidorginalname' => '',
                                        'productsalepaiddescription' => '',
                                        'create_date' => date('Y-m-d H:i:s'),
                                        'modify_date' => date('Y-m-d H:i:s'),
                                        'create_userID' => $this->session->userdata('loginuserID'),
                                        'create_usertypeID' => $this->session->userdata('usertypeID')
                                    );

                                    $this->productsalepaid_m->insert_productsalepaid($productsalepaidArray);
                                }
                            } else {
                                if($this->input->post('productsalepaidamount') > 0) {
                                    $productsalepaidArray = array(
                                        'productsalepaidschoolyearID' => $this->data['siteinfos']->school_year,
                                        'schoolyearID' => $this->session->userdata('defaultschoolyearID'),
                                        'productsaleID' => $updateID,
                                        'productsalepaiddate' => date('Y-m-d', strtotime($this->input->post("productsaledate"))),
                                        'productsalepaidreferenceno' => $this->input->post("productsalepaidreferenceno"),
                                        'productsalepaidamount' => $this->input->post("productsalepaidamount"),
                                        'productsalepaidpaymentmethod' => $this->input->post("productsalepaidpaymentmethod"),
                                        'productsalepaidfile' => '',
                                        'productsalepaidorginalname' => '',
                                        'productsalepaiddescription' => '',
                                        'create_date' => date('Y-m-d H:i:s'),
                                        'modify_date' => date('Y-m-d H:i:s'),
                                        'create_userID' => $this->session->userdata('loginuserID'),
                                        'create_usertypeID' => $this->session->userdata('usertypeID')
                                    );
                                    $this->productsalepaid_m->insert_productsalepaid($productsalepaidArray);
                                }

                                $productsalepaid = $this->productsalepaid_m->get_productsalepaid_sum('productsalepaidamount', array('productsaleID' => $updateID));
                                unset($array['schoolyearID'], $array['create_date'], $array['create_userID'], $array['create_usertypeID']);

                                if((float)$totalAmount == (float)$productsalepaid->productsalepaidamount) {
                                    $array['productsalestatus'] = 3;
                                } elseif((float)$productsalepaid->productsalepaidamount > 0 && ((float)$totalAmount > (float)$productsalepaid->productsalepaidamount)) {
                                    $array['productsalestatus'] = 2;
                                } elseif((float)$productsalepaid->productsalepaidamount > 0 && ((float)$totalAmount < (float)$productsalepaid->productsalepaidamount)) {
                                    $array['productsalestatus'] = 3;
                                }

                                $this->productsale_m->update_productsale($array, $updateID);
                            }
                        }

                        $this->productsaleitem_m->insert_batch_productsaleitem($productsaleitem);
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
                $retArray['error'] = array('permission' => 'Sale permission is required.');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['error'] = array('permission' => 'Sale permission is required.');
            echo json_encode($retArray);
            exit;
        }
    }

    public function delete() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$id) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $this->data['productsale'] = $this->productsale_m->get_single_productsale(array('productsaleID' => $id, 'schoolyearID' => $schoolyearID));
                $this->data['productsalepaid'] = $this->productsalepaid_m->get_productsalepaid_sum('productsalepaidamount', array('productsaleID' => $id));

                if(count($this->data['productsale'])) {
                    if(($this->data['productsale']->productsalerefund == 0) && ($this->data['productsalepaid']->productsalepaidamount == NULL)) {
                        $this->productsale_m->delete_productsale($id);
                        $this->productsaleitem_m->delete_productsaleitem_by_productsaleID($id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("productsale/index"));
                    } else {
                        redirect(base_url("productsale/index"));
                    }
                } else {
                    redirect(base_url("productsale/index"));
                }
            } else {
                redirect(base_url("productsale/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function cancel() {
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            if(permissionChecker('productsale_edit')) {
                $id = htmlentities(escapeString($this->uri->segment(3)));
                if((int)$id) {
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');
                    $this->data['productsale'] = $this->productsale_m->get_single_productsale(array('productsaleID' => $id, 'schoolyearID' => $schoolyearID));
                    if(count($this->data['productsale'])) {
                        $this->productsale_m->update_productsale(array('productsalerefund' => 1), $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("productsale/index"));
                    } else {
                        redirect(base_url("productsale/index"));
                    }
                } else {
                    redirect(base_url("productsale/index"));
                }
            } else {
                redirect(base_url("productsale/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }
}

    
