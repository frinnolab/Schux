<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_option_m extends MY_Model {

    protected $_table_name = 'question_option';
    protected $_primary_key = 'optionID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "optionID asc";

    function __construct() {
        parent::__construct();
    }

    function get_question_option($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_question_option($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_question_option($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_where_in_question_option($array, $key=NULL) {
        $query = parent::get_where_in($array, $key);
        return $query;
    }

    function insert_batch_question_option($array) {
        $id = parent::insert_batch($array);
        return $id;
    }

    function insert_question_option($array) {
        $id = parent::insert($array);
        return $id;
    }


    function update_question_option($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_question_option($id){
        parent::delete($id);
    } 

    public function delete_batch_option($array){
        parent::delete_batch($array);
        return TRUE;
    }

    function get_question_option_by_questionArray($array=NULL, $key=FALSE) {
        $query = parent::get_where_in($array, $key);
        return $query;
    }

}
