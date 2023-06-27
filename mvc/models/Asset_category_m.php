<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset_category_m extends MY_Model {

    protected $_table_name = 'asset_category';
    protected $_primary_key = 'asset_categoryID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "asset_categoryID asc";

    function __construct() {
        parent::__construct();
    }

    function get_asset_category($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_asset_category($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_asset_category($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_asset_category($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_asset_category($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_asset_category($id){
        parent::delete($id);
    }
}
