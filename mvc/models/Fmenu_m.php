<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fmenu_m extends MY_Model {

	protected $_table_name = 'fmenu';
	protected $_primary_key = 'fmenuID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "fmenuID asc";

	function __construct() {
		parent::__construct();
	}

	function get_fmenu($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_fmenu($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_fmenu($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_fmenu($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_fmenu($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_fmenu($id){
		parent::delete($id);
	}

	function update_fmenu_by_array($array, $array2) {
		$this->db->update($this->_table_name, $array, $array2);
		return TRUE;
	}
}

/* End of file holiday_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/holiday_m.php */
