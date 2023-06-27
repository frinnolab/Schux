<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Syllabus_m extends MY_Model {

	protected $_table_name = 'syllabus';
	protected $_primary_key = 'syllabusID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "date asc";

	function __construct() {
		parent::__construct();
	}

	public function get_syllabus($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_syllabus($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_syllabus($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_syllabus($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_syllabus($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_syllabus($id){
		parent::delete($id);
	}
}