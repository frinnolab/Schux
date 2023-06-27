<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Productpurchase extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('productsupplier_m');
        $this->load->model('productpurchase_m');
        $this->load->model('product_m');
        $this->load->model('productpurchaseitem_m');
        $this->load->model('productpurchasepaid_m');
        $this->load->model('productwarehouse_m');
    }

    public function index_get() 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->retdata['productsuppliers'] = pluck($this->productsupplier_m->get_productsupplier(), 'productsuppliercompanyname', 'productsupplierID');
        $this->retdata['productpurchases'] = $this->productpurchase_m->get_order_by_productpurchase(array('schoolyearID' => $schoolyearID));
        $this->retdata['grandtotalandpaid'] = $this->grandtotalandpaid($this->retdata['productpurchases'], $schoolyearID);

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = null) 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if((int)$id) {
            $this->retdata['productpurchase'] = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $id, 'schoolyearID' => $schoolyearID));
            
            $this->retdata['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
            
            $this->retdata['productpurchaseitems'] = $this->productpurchaseitem_m->get_order_by_productpurchaseitem(array('productpurchaseID' => $id, 'schoolyearID' => $schoolyearID));

            $this->retdata['productpurchasepaid'] = $this->productpurchasepaid_m->get_productpurchasepaid_sum('productpurchasepaidamount', array('productpurchaseID' => $id));


            if($this->retdata['productpurchase']) {
                $this->retdata['createuser'] = getNameByUsertypeIDAndUserID($this->retdata['productpurchase']->create_usertypeID, $this->retdata['productpurchase']->create_userID);

                $this->retdata['productsupplier'] = $this->productsupplier_m->get_single_productsupplier(array('productsupplierID' => $this->retdata['productpurchase']->productsupplierID));
                $this->retdata['productwarehouse'] = $this->productwarehouse_m->get_single_productwarehouse(array('productwarehouseID' => $this->retdata['productpurchase']->productwarehouseID));

                $this->response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $this->retdata
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status'    => false,
                    'message'   => 'Error 404',
                    'data'      => []
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Error 404',
                'data'      => []
            ], REST_Controller::HTTP_OK);
        }
    }

    private function grandtotalandpaid($productpurchases, $schoolyearID) 
    {
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

    public function paymentlist_get($id = null) 
    {
        if(permissionChecker('productpurchase')) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $productpurchaseID = $id;

            $this->retdata['paymentmethods'] = array(
                1 => $this->lang->line('productpurchase_cash'),
                2 => $this->lang->line('productpurchase_cheque'),
                3 => $this->lang->line('productpurchase_credit_card'),
                4 => $this->lang->line('productpurchase_other'),
            );

            if(!empty($productpurchaseID) && (int)$productpurchaseID && $productpurchaseID > 0) {
                $productpurchase = $this->productpurchase_m->get_single_productpurchase(array('productpurchaseID' => $productpurchaseID, 'schoolyearID' => $schoolyearID));
                if(count($productpurchase)) {
                    $this->retdata['productpurchasepaids'] = $this->productpurchasepaid_m->get_order_by_productpurchasepaid(array('productpurchaseID' => $productpurchaseID));

                    $this->response([
                        'status'    => true,
                        'message'   => 'Success',
                        'data'      => $this->retdata
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status'    => false,
                        'message'   => 'Error 404',
                        'data'      => []
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    'status'    => false,
                    'message'   => 'Error 404',
                    'data'      => []
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Permission Deny',
                'data'      => []
            ], REST_Controller::HTTP_OK);
        }
    }
}
