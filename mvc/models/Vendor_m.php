<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor_m extends MY_Model {

    protected $_table_name = 'vendor';
    protected $_primary_key = 'vendorID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "vendorID asc";

    function __construct() {
        parent::__construct();
    }

    function get_vendor($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_vendor($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_vendor($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_vendor($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_vendor($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_vendor($id){
        parent::delete($id);
    }
}
