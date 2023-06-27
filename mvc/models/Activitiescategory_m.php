<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activitiescategory_m extends MY_Model {

	protected $_table_name = 'activitiescategory';
	protected $_primary_key = 'activitiescategoryID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "activitiescategoryID asc";

	function __construct() {
		parent::__construct();
	}

	function get_activitiescategory($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_activitiescategory($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_activitiescategory($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_activitiescategory($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_activitiescategory($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_activitiescategory($id){
		parent::delete($id);
	}
}

/* End of file activitiescategory_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/activities_category_m.php */