<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leavecategory_m extends MY_Model {

    protected $_table_name = 'leavecategory';
    protected $_primary_key = 'leavecategoryID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "leavecategoryID desc";

    function __construct() {
        parent::__construct();
    }

    public function get_join_leavecategory_and_leaveassign($schoolyearID, $userTypeID = NULL) {
        $this->db->select('*');
        $this->db->from('leaveassign');
        $this->db->join('usertype', 'leaveassign.usertypeID = usertype.usertypeID', 'LEFT');
        $this->db->join('leavecategory', 'leaveassign.leavecategoryID = leavecategory.leavecategoryID', 'LEFT');
        // Added this myself
        if($userTypeID) {
            $this->db->where('leaveassign.usertypeID', $userTypeID);
        }
        // End of Added Code
        $this->db->where('leaveassign.schoolyearID', $schoolyearID);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_leavecategory($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_leavecategory($array) {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_leavecategory($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_leavecategory($array) {
        $id = parent::insert($array);
        return $id;
    }

    public function update_leavecategory($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_leavecategory($id){
        parent::delete($id);
    }
}
