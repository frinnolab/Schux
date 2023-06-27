<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productwarehouse_m extends MY_Model {

    protected $_table_name = 'productwarehouse';
    protected $_primary_key = 'productwarehouseID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "productwarehouseID asc";

    function __construct() {
        parent::__construct();
    }

    function get_productwarehouse($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_productwarehouse($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_productwarehouse($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_productwarehouse($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_productwarehouse($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_productwarehouse($id){
        parent::delete($id);
    }
}
