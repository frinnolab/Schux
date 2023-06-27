<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_m extends MY_Model {

	protected $_table_name = 'update';
	protected $_primary_key = 'updateID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "updateID asc";

	function __construct() {
		parent::__construct();
	}

	public function get_update($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_update($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_update($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_update($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_update($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_update($id) {
		parent::delete($id);
	}

	public function get_max_update() {
		return $this->db->select('*')->where(array('status' => 1))->order_by('updateID','desc')->limit(1)->get('update')->row();
	}
}