<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activities_m extends MY_Model {

	protected $_table_name = 'activities';
	protected $_primary_key = 'activitiesID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "activitiesID desc";

	function __construct() {
		parent::__construct();
	}

	public function get_activities($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_activities($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_activities($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_activities($array) {
		$id = parent::insert($array);
		return $id;
	}

	public function update_activities($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_activities($id){
		parent::delete($id);
        $tables = array('activitiesmedia', 'activitiescomment', 'activitiesstudent');
        $this->db->where($this->_primary_key, $id);
        $this->db->delete($tables);
	}
}