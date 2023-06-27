<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productsalepaid_m extends MY_Model {

	protected $_table_name = 'productsalepaid';
	protected $_primary_key = 'productsalepaidID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "productsalepaidID asc";

	function __construct() {
		parent::__construct();
	}

	public function get_productsalepaid($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_productsalepaid($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_productsalepaid($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_productsalepaid_sum($clmn, $array) {
		$query = parent::get_sum($clmn, $array);
		return $query;
	}

	public function get_where_in_productsalepaid($arrays, $key=NULL) {
		$query = parent::get_where_in($arrays, $key);
		return $query;
	}

	public function insert_productsalepaid($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_productsalepaid($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_productsalepaid($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_productsalepaid($id){
		parent::delete($id);
	}

	public function delete_productsalepaid_by_productsaleID($id) {
		$this->db->delete($this->_table_name, array('productsaleID' => $id)); 
		return TRUE;
	}
}