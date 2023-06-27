<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activitiesstudent_m extends MY_Model {

	protected $_table_name = 'activitiesstudent';
	protected $_primary_key = 'activitiesstudentID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "activitiesID asc";

	function __construct() {
		parent::__construct();
	}

	function get_activitiesstudent($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_activitiesstudent($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_activitiesstudent($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_activitiesstudent($array) {
		$id = parent::insert($array);
		return $id;
	}

	function update_activitiesstudent($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_activitiesstudent($id){
		parent::delete($id);
	}
}

/* End of file act_student_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/act_student_m.php */