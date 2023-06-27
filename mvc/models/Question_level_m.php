<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_level_m extends MY_Model {

    protected $_table_name = 'question_level';
    protected $_primary_key = 'questionLevelID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "questionLevelID asc";

    function __construct() {
        parent::__construct();
    }

    function get_question_level($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_question_level($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_question_level($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_question_level($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_question_level($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_question_level($id){
        parent::delete($id);
    }
}
