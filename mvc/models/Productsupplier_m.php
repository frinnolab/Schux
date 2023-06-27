<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productsupplier_m extends MY_Model {

    protected $_table_name = 'productsupplier';
    protected $_primary_key = 'productsupplierID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "productsupplierID asc";

    function __construct() {
        parent::__construct();
    }

    function get_productsupplier($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_productsupplier($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_productsupplier($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_productsupplier($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_productsupplier($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_productsupplier($id){
        parent::delete($id);
    }
}
