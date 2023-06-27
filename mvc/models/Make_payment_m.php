<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Make_payment_m extends MY_Model {

    protected $_table_name = 'make_payment';
    protected $_primary_key = 'make_paymentID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "make_paymentID asc";

    function __construct() {
        parent::__construct();
    }

    function get_make_payment($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_make_payment($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_make_payment($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_make_payment($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_make_payment($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_make_payment($id){
        parent::delete($id);
    }

    public function get_payment_salary_by_date($array) {
        $fromdate = date('Y-m-d',strtotime($array['fromdate']))." 00:00:00";
        $todate   = date('Y-m-d',strtotime($array['todate']))." 23:59:59";
        $query = $this->db->query("SELECT * FROM make_payment WHERE create_date BETWEEN '$fromdate' AND '$todate'");
        return $query->result();
    }

    public function get_all_salary_for_report($queryArray) {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $this->db->select('*');
        $this->db->from('make_payment');

        if(isset($queryArray['usertypeID']) && $queryArray['usertypeID'] != 0) {
            $this->db->where('usertypeID', $queryArray['usertypeID']);
            if(isset($queryArray['userID']) && $queryArray['userID'] != 0) {
                $this->db->where('userID', $queryArray['userID']);
            }
        }

        if((isset($queryArray['fromdate']) && $queryArray['fromdate'] != '') && (isset($queryArray['todate']) && $queryArray['todate'] != '')) {
             $fromdate = date('Y-m-d',strtotime($queryArray['fromdate']))." 00:00:00";
            $todate   = date('Y-m-d',strtotime($queryArray['todate']))." 23:59:59";

            $this->db->where('create_date >=', $fromdate);
            $this->db->where('create_date <=', $todate);
        }

        if((isset($queryArray['month']) && $queryArray['month'] != '')) {
            $this->db->where('month',$queryArray['month']);
        }

        $this->db->where('schoolyearID',$schoolyearID);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_payment_salary_with_date_schoolyear($array) {
        $this->db->select_sum('payment_amount');
        $this->db->from($this->_table_name);
        if(isset($array['fromdate']) && isset($array['todate'])) {
            $this->db->where('create_date >=',$array['fromdate']." 00:00:00");
            $this->db->where('create_date <=',$array['todate']." 23:59:59");
        }
        if(isset($array['schoolyearID'])) {
            $this->db->where('schoolyearID',$array['schoolyearID']);
        }
        $query = $this->db->get();
        return $query->row();
    }
}
