<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_type_m extends MY_Model {

    protected $_table_name = 'question_type';
    protected $_primary_key = 'questionTypeID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "questionTypeID asc";

    function __construct() {
        parent::__construct();
    }

    function get_question_type($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_question_type($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_question_type($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_question_type($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_question_type($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_question_type($id){
        parent::delete($id);
    }
}
