<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset_assignment_m extends MY_Model {

    protected $_table_name = 'asset_assignment';
    protected $_primary_key = 'asset_assignmentID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "asset_assignmentID asc";

    function __construct() {
        parent::__construct();
    }

    function get_asset_assignment($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_asset_assignment_with_userypeID() {
        $this->db->select('asset_assignment.*, asset.description, usertype.usertype');
        $this->db->from('asset_assignment');
        $this->db->join('asset', 'asset_assignment.assetID = asset.assetID', 'LEFT');
        $this->db->join('usertype', 'usertype.usertypeID = asset_assignment.usertypeID', 'LEFT');
        $this->db->order_by("asset_assignmentID",'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    function get_single_asset_assignment_with_usertypeID($array) {
        $this->db->select('asset_assignment.*, asset.description, usertype.usertype, location.location');
        $this->db->from('asset_assignment');
        $this->db->join('asset', 'asset_assignment.assetID = asset.assetID', 'LEFT');
        $this->db->join('usertype', 'usertype.usertypeID = asset_assignment.usertypeID', 'LEFT');
        $this->db->join('location', 'location.locationID = asset_assignment.asset_locationID', 'LEFT');
        $this->db->order_by("asset_assignmentID",'DESC');
        $this->db->where($array);
        $query = $this->db->get();
        return $query->row();
    }

    function get_single_asset_assignment($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_asset_assignment($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_asset_assignment($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_asset_assignment($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_asset_assignment($id){
        parent::delete($id);
    }
}
