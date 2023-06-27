<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_answer_m extends MY_Model {

    protected $_table_name = 'question_answer';
    protected $_primary_key = 'answerID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "answerID asc";

    function __construct() {
        parent::__construct();
    }

    function get_question_answer($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_question_answer($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_question_answer($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_where_in_question_answer($array, $key=NULL) {
        $query = parent::get_where_in($array, $key);
        return $query;
    }

    function insert_question_answer($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_question_answer($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_question_answer($id){
        parent::delete($id);
    }

    public function delete_question_answer_by_questionID($id){
        $this->db->delete($this->_table_name, array('questionID' => $id)); 
        return TRUE;
    }

    public function delete_batch_question_answer($array){
        parent::delete_batch($array);
        return TRUE;
    }
}
