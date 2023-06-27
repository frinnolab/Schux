<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Globalpayment_m extends MY_Model {

    protected $_table_name = 'globalpayment';
    protected $_primary_key = 'globalpaymentID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "globalpaymentID asc";

    function __construct() {
        parent::__construct();
    }

    public function get_max_globalpayment() {
        $query = $this->db->query("SELECT * FROM $this->_table_name WHERE $this->_primary_key = (SELECT MAX($this->_primary_key) FROM $this->_table_name)");
        return $query->row();
    }

    function get_globalpayment($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_globalpayment($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_globalpayment($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_globalpayment($array) {
        $id = parent::insert($array);
        return $id;
    }

    public function insert_batch_globalpayment($array) {
        $id = parent::insert_batch($array);
        return $id;
    }

    function update_globalpayment($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_globalpayment($id){
        parent::delete($id);
    }
}
