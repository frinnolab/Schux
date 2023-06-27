<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subjectattendance_m extends MY_Model {

	protected $_table_name = 'sub_attendance';
	protected $_primary_key = 'attendanceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "monthyear asc";

	function __construct() {
		parent::__construct();
	}

	public function get_sub_attendance($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_sub_attendance($array=NULL) {
		// $query = parent::get_order_by($array);
		// return $query;
		$query = parent::get_order_by_multiple_classes($array);
		return $query;
	}

	public function insert_sub_attendance($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_sub_attendance($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_sub_attendance($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_batch_sub_attendance($data, $id = NULL) {
        parent::update_batch($data, $id);
        return TRUE;
    }

	public function delete_sub_attendance($id){
		parent::delete($id);
	}
}