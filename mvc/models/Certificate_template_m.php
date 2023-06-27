<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Certificate_template_m extends MY_Model {

    protected $_table_name = 'certificate_template';
    protected $_primary_key = 'certificate_templateID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "certificate_templateID asc";

    function __construct() {
        parent::__construct();
    }

    function get_certificate_template($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_certificate_template($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_certificate_template($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_certificate_template($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_certificate_template($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_certificate_template($id){
        parent::delete($id);
    }
}
