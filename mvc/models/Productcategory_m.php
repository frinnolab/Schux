<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productcategory_m extends MY_Model {

    protected $_table_name = 'productcategory';
    protected $_primary_key = 'productcategoryID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "productcategoryID asc";

    function __construct() {
        parent::__construct();
    }

    function get_productcategory($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_productcategory($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_productcategory($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_productcategory($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_productcategory($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_productcategory($id){
        parent::delete($id);
    }
}
