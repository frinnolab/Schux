<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hourly_template_m extends MY_Model {

    protected $_table_name = 'hourly_template';
    protected $_primary_key = 'hourly_templateID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "hourly_templateID asc";

    function __construct() {
        parent::__construct();
    }

    function get_hourly_template($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_hourly_template($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_hourly_template($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_hourly_template($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_hourly_template($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_hourly_template($id){
        parent::delete($id);
    }
}
