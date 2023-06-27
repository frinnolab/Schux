<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salary_template_m extends MY_Model {

    protected $_table_name = 'salary_template';
    protected $_primary_key = 'salary_templateID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "salary_templateID asc";

    function __construct() {
        parent::__construct();
    }

    function get_salary_template($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_salary_template($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_salary_template($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_salary_template($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_salary_template($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_salary_template($id){
        parent::delete($id);
    }
}
