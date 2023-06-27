<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tattendance_m extends MY_Model {

	protected $_table_name = 'tattendance';
	protected $_primary_key = 'tattendanceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "monthyear asc";

	function __construct() {
		parent::__construct();
	}

	public function get_tattendance($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_tattendance($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_tattendance($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_tattendance($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_tattendance($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_batch_tattendance($data, $id = NULL) {
        parent::update_batch($data, $id);
        return TRUE;
    }

	public function delete_tattendance($id){
		parent::delete($id);
	}




	

}