<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Themes_m extends MY_Model {

	protected $_table_name = 'themes';
	protected $_primary_key = 'themesID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "sortID asc";

	function __construct() {
		parent::__construct();
	}

	function get_themes($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_themes($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_themes($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_themes($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_themes($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	function delete_themes($id){
		parent::delete($id);
	}

	function hash($string) {
		return parent::hash($string);
	}
}

/* End of file teacher_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/teacher_m.php */
