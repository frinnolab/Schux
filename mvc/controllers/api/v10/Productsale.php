<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Productsale extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('user_m');
        $this->load->model('teacher_m');
        $this->load->model('parents_m');
        $this->load->model('usertype_m');
        $this->load->model('systemadmin_m');
        $this->load->model('studentrelation_m');
        $this->load->model('product_m');
        $this->load->model('productsale_m');
        $this->load->model('productsaleitem_m');
        $this->load->model('productsalepaid_m');
    }

    public function index_get() 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->retdata['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
        $this->retdata['users']     = $this->getuserlist();
        $this->retdata['productsales']      = $this->productsale_m->get_order_by_productsale(array('schoolyearID' => $schoolyearID));
        $this->retdata['grandtotalandpaid'] = $this->grandtotalandpaid($this->retdata['productsales'], $schoolyearID);

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
            $this->retdata['productsale'] = $this->productsale_m->get_single_productsale(array('productsaleID' => $id, 'schoolyearID' => $schoolyearID));
            
            $this->retdata['products'] = pluck($this->product_m->get_product(), 'productname', 'productID');
            
            $this->retdata['productsaleitems'] = $this->productsaleitem_m->get_order_by_productsaleitem(array('productsaleID' => $id, 'schoolyearID' => $schoolyearID));

            $this->retdata['productsalepaid'] = $this->productsalepaid_m->get_productsalepaid_sum('productsalepaidamount', array('productsaleID' => $id));

            if($this->retdata['productsale']) {
                $this->retdata['usertypes'] = pluck($this->usertype_m->get_usertype(), 'usertype', 'usertypeID');
                $this->retdata['user'] = $this->getuserlistobj($this->retdata['productsale']->productsalecustomertypeID, $this->retdata['productsale']->productsalecustomerID, $schoolyearID);
                $this->retdata['createuser'] = getNameByUsertypeIDAndUserID($this->retdata['productsale']->create_usertypeID, $this->retdata['productsale']->create_userID);

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

    private function getuserlist() 
    {
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

    private function grandtotalandpaid($productsales, $schoolyearID) 
    {
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

    private function getuserlistobj($usertypeID, $userID, $schoolyearID) 
    {
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

    public function paymentlist_get($id = null) 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $productsaleID = $id;

        $this->retdata['paymentmethods'] = array(
            1 => $this->lang->line('productsale_cash'),
            2 => $this->lang->line('productsale_cheque'),
            3 => $this->lang->line('productsale_credit_card'),
            4 => $this->lang->line('productsale_other'),
        );

        if(!empty($productsaleID) && (int)$productsaleID && $productsaleID > 0) {
            $productsale = $this->productsale_m->get_single_productsale(array('productsaleID' => $productsaleID, 'schoolyearID' => $schoolyearID));
            if(count($productsale)) {
                $this->retdata['productsalepaids'] = $this->productsalepaid_m->get_order_by_productsalepaid(array('productsaleID' => $productsaleID));

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
}
