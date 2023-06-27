<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Frontend_template_m extends MY_Model {

	protected $_table_name = 'frontend_template';
	protected $_primary_key = 'frontend_templateID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "frontend_templateID asc";

	function __construct() {
		parent::__construct();
	}

	function get_frontend_template($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_frontend_template($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_frontend_template($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_frontend_template($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_frontend_template($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_frontend_template($id){
		parent::delete($id);
	}
}

/* End of file exam_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/exam_m.php */