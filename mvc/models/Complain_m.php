<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Complain_m extends MY_Model {

    protected $_table_name = 'complain';
    protected $_primary_key = 'complainID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "complainID desc";

    function __construct() {
        parent::__construct();
    }

    function get_complain($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_complain($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_complain($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function get_complain_with_usertypeID($schoolyearID, $usertypeID = NULL, $userID = NULL) {
        $this->db->select('complain.*, usertype.usertypeID, usertype.usertype');
        $this->db->from('complain');
        $this->db->join('usertype', 'usertype.usertypeID = complain.usertypeID', 'LEFT');

        if(($usertypeID != NULL) && ($userID != NULL)) {
            $this->db->where(array('complain.create_usertypeID' => $usertypeID, 'complain.create_userID' => $userID));
        }

        $this->db->where(array('complain.schoolyearID' => $schoolyearID));
        $this->db->order_by("complain.complainID",'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    function get_single_complain_with_usertypeID($array) {
        $this->db->select('*');
        $this->db->from('complain');
        $this->db->join('usertype', 'usertype.usertypeID = complain.usertypeID', 'LEFT');
        $this->db->order_by("complainID",'DESC');
        $this->db->where($array);
        $query = $this->db->get();
        return $query->row();
    }

    function insert_complain($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_complain($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_complain($id){
        parent::delete($id);
    }
}
