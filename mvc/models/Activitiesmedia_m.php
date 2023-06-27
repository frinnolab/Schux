<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activitiesmedia_m extends MY_Model {

	protected $_table_name = 'activitiesmedia';
	protected $_primary_key = 'activitiesmediaID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "activitiesID asc";

	function __construct() {
		parent::__construct();
	}

	function get_activitiesmedia($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_activitiesmedia($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_activitiesmedia($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_activitiesmedia($array) {
		$id = parent::insert($array);
		return $id;
	}

	function insert_batch_activitiesmedia($array) {
        $insert = $this->db->insert_batch($this->_table_name, $array);
        return $insert ? true:false;
    }

	function update_activitiesmedia($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_activitiesmedia($id){
		parent::delete($id);
	}
}

/* End of file activitiesmedia_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/activitiesmedia_m.php */