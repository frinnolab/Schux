<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends Admin_Controller {
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
        $this->load->model("product_m");
        $this->load->model("productcategory_m");
        $this->load->model("productsaleitem_m");
        $this->load->model("productpurchaseitem_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('product', $language);
    }

    public function index() {
        $this->data['productcategorys'] = pluck($this->productcategory_m->get_productcategory(), 'productcategoryname', 'productcategoryID');
        $this->data['products'] = $this->product_m->get_product();
        $this->data["subview"] = "product/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'productname',
                'label' => $this->lang->line("product_product"),
                'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_productname'
            ),
            array(
                'field' => 'productcategoryID',
                'label' => $this->lang->line("product_category"),
                'rules' => 'trim|required|xss_clean|numeric|max_length[11]|callback_unique_prodectcategory'
            ),
            array(
                'field' => 'productbuyingprice',
                'label' => $this->lang->line("product_buyingprice"),
                'rules' => 'trim|required|xss_clean|max_length[15]|numeric'
            ),
            array(
                'field' => 'productsellingprice',
                'label' => $this->lang->line("product_sellingprice"),
                'rules' => 'trim|xss_clean|max_length[15]|numeric'
            ),
            array(
                'field' => 'productopeningstock',
                'label' => $this->lang->line("product_opening_stock"),
                'rules' => 'trim|required|xss_clean|max_length[15]|numeric'
            ),
            array(
                'field' => 'productdesc',
                'label' => $this->lang->line("product_desc"),
                'rules' => 'trim|xss_clean|max_length[250]'
            )
        );
        return $rules;
    }

    public function add() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js'
            )
        );

        $this->data['productcategorys'] = $this->productcategory_m->get_productcategory();
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = "product/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "productname" => $this->input->post("productname"),
                    "productcategoryID" => $this->input->post("productcategoryID"),
                    "productbuyingprice" => $this->input->post("productbuyingprice"),
                    "productsellingprice" => $this->input->post("productsellingprice"),
                    "productopeningstock" => $this->input->post("productopeningstock"),
                    "productdesc" => $this->input->post("productdesc"),
                    "create_date" => date("Y-m-d H:i:s"),
                    "modify_date" => date("Y-m-d H:i:s"),
                    "create_userID" => $this->session->userdata('loginuserID'),
                    "create_usertypeID" => $this->session->userdata('usertypeID')
                );
                $this->product_m->insert_product($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("product/index"));
            }
        } else {
            $this->data["subview"] = "product/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
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
        if((int)$id) {
            $this->data['product'] = $this->product_m->get_single_product(array('productID' => $id));
            $this->data['productcategorys'] = $this->productcategory_m->get_productcategory();
            if($this->data['product']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "product/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "productname" => $this->input->post("productname"),
                            "productcategoryID" => $this->input->post("productcategoryID"),
                            "productbuyingprice" => $this->input->post("productbuyingprice"),
                            "productsellingprice" => $this->input->post("productsellingprice"),
                            "productopeningstock" => $this->input->post("productopeningstock"),
                            "productdesc" => $this->input->post("productdesc"),
                            "modify_date" => date("Y-m-d H:i:s"),
                        );

                        $this->product_m->update_product($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("product/index"));
                    }
                } else {
                    $this->data["subview"] = "product/edit";
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
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['product'] = $this->product_m->get_single_product(array('productID' => $id));
            if($this->data['product']) {
                $this->product_m->delete_product($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("product/index"));
            } else {
                redirect(base_url("product/index"));
            }
        } else {
            redirect(base_url("product/index"));
        }
    }

    public function unique_productname() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $product = $this->product_m->get_order_by_product(array("productname" => $this->input->post("productname"), "productID !=" => $id));
            if(count($product)) {
                $this->form_validation->set_message("unique_productname", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $product = $this->product_m->get_order_by_product(array("productname" => $this->input->post("productname")));
            if(count($product)) {
                $this->form_validation->set_message("unique_productname", "The %s is already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function unique_prodectcategory() {
        if($this->input->post("productcategoryID") == 0) {
            $this->form_validation->set_message("unique_prodectcategory", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }
}
