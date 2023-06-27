<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salaryoption_m extends MY_Model {

	protected $_table_name = 'salary_option';
	protected $_primary_key = 'salary_optionID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "salary_optionID desc";

	function __construct() {
		parent::__construct();
	}

	function get_salaryoption($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_salaryoption($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_salaryoption($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_salaryoption($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_salaryoption($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_salaryoption($id){
		parent::delete($id);
	}

	public function delete_salaryoption_by_salary_templateID($id) {
		$this->db->delete($this->_table_name, array('salary_templateID' => $id)); 
	}

}

/* End of file salaryoption_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/salaryoption_m.php */