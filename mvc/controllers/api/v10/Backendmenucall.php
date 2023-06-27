<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Backendmenucall extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->lang->load('topbar_menu', $this->data['language']);
    }

    public function index_get() 
    {
        $this->load->model('menu_m');
        $sessionPermission = $this->session->userdata('master_permission_set');
        $this->retdata['menus'] = $this->menuTree(json_decode(json_encode(pluck($this->menu_m->get_order_by_menu(['status' => 1]), 'obj', 'menuID')), true) , $sessionPermission);

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    private function menuTree($dataset, $sessionPermission) {
        $hideMenu = array(
            'eattendance',
            'promotion',
            'conversation',
            'media',
            'take_exam',
            'manage_salary',
            'make_payment',
            'issue',
            'global_payment',
            'onlineadmission',
            'ebooks', // Already done
            'resetpassword',
            'import',
            'backup',
            'permission',
            'menu',
            'setting',
            'frontend_setting',
            'paymentsettings',
            'smssettings',
            'emailsetting',
            'main_settings',

            'main_report',
            'classreport',
            'studentreport',
            'idcardreport',
            'admitcardreport',
            'routinereport',
            'examschedulereport',
            'attendancereport',
            'attendanceoverviewreport',
            'librarybooksreport',
            'librarycardreport',
            'librarybookissuereport',
            'terminalreport',
            'meritstagereport',
            'tabulationsheetreport',
            'marksheetreport',
            'progresscardreport',
            'onlineexamreport',
            'onlineexamquestionreport',
            'onlineadmissionreport',
            'certificatereport',
            'leaveapplicationreport',
            'productpurchasereport',
            'productsalereport',
            'searchpaymentfeesreport',
            'feesreport',
            'duefeesreport',
            'balancefeesreport',
            'transactionreport',
            'studentfinereport',
            'salaryreport',
            'accountledgerreport',
        );

        $tree = [];
        foreach ($dataset as $id => &$node) {
            if(!in_array($node['menuName'], $hideMenu)) {
                if($node['link'] == '#' || (isset($sessionPermission[$node['link']]) && $sessionPermission[$node['link']] != "no") ) {
                    if ($node['parentID'] == 0) {
                        $tree[$id]=&$node;
                    } else {
                        if (!isset($dataset[$node['parentID']]['child'])) {
                            $dataset[$node['parentID']]['child'] = array();
                        }

                        $dataset[$node['parentID']]['child'][$id] =& $node;
                    }
                }
            }
        }

        $newTree = [];
        $i = 1;
        if(count($tree)) {
            foreach ($tree as $te) {
                $newTree[$i] = ['menu' => $this->lang->line('menu_'.$te['menuName']), 'link' => $te['link'], 'icon' => $te['icon']]; 
                if(isset($te['child'])) {
                    $j = 0;
                    foreach ($te['child'] as $child) {
                        $newTree[$i]['child'][$j] = ['menu' => $this->lang->line('menu_'.$child['menuName']), 'link' => $child['link'], 'icon' => $child['icon']];
                        $j++; 
                    }
                }
                $i++;
            }
        }
        $treeGenerate = [];
        if(count($newTree)) {
            $i = 1;
            foreach ($newTree as $t) {
                if(isset($t['child']) && count($t['child']) > 1) {
                    $treeGenerate[$i] = ['menu' => $t['menu'], 'link' => $t['link'], 'icon' => $t['icon']];
                    $j = 0;
                    foreach ($t['child'] as $c) {
                        $treeGenerate[$i]['child'][$j] = ['menu' => $c['menu'], 'link' => $c['link'], 'icon' => $c['icon']];
                        $j++;
                    }
                } else {
                    if(isset($t['child']) && count($t['child']) == 1) {
                        foreach ($t['child'] as $c) {
                            $treeGenerate[$i] = ['menu' => $c['menu'], 'link' => $c['link'], 'icon' => $c['icon']];
                        }
                    } else {
                        if($t['link'] != '#') {
                            $treeGenerate[$i] = ['menu' => $t['menu'], 'link' => $t['link'], 'icon' => $t['icon']];
                        }
                    }
                }
                $i++;
            }
        }
        return $treeGenerate;
    }
}