<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sociallink_m extends MY_Model {

    protected $_table_name = 'sociallink';
    protected $_primary_key = 'sociallinkID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "sociallinkID desc";

    function __construct() {
        parent::__construct();
    }

    public function get_sociallink($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_sociallink($array) {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_sociallink($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_sociallink($array) {
        $id = parent::insert($array);
        return $id;
    }

    public function update_sociallink($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_sociallink($id){
        parent::delete($id);
    }
}
