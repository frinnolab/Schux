<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_group_m extends MY_Model {

    protected $_table_name = 'question_group';
    protected $_primary_key = 'questionGroupID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "questionGroupID asc";

    function __construct() {
        parent::__construct();
    }

    function get_question_group($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_question_group($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_question_group($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_question_group($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_question_group($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_question_group($id){
        parent::delete($id);
    }
}
