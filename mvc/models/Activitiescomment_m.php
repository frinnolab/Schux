<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activitiescomment_m extends MY_Model {

	protected $_table_name = 'activitiescomment';
	protected $_primary_key = 'activitiescommentID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "activitiescommentID asc";

	function __construct() {
		parent::__construct();
	}

	function get_activitiescomment($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_activitiescomment($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_activitiescomment($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_activitiescomment($array) {
		$id = parent::insert($array);
		return $id;
	}

	function update_activitiescomment($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_activitiescomment($id){
		parent::delete($id);
	}
}

/* End of file activitiescomment_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/activitiescomment_m.php */