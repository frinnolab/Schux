<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productpurchasepaid_m extends MY_Model {

	protected $_table_name = 'productpurchasepaid';
	protected $_primary_key = 'productpurchasepaidID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "productpurchasepaidID asc";

	function __construct() {
		parent::__construct();
	}

	public function get_productpurchasepaid($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_productpurchasepaid($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_productpurchasepaid($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_productpurchasepaid_sum($clmn, $array) {
		$query = parent::get_sum($clmn, $array);
		return $query;
	}

	public function insert_productpurchasepaid($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_productpurchasepaid($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_productpurchasepaid($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_productpurchasepaid($id){
		parent::delete($id);
	}

	public function delete_productpurchasepaid_by_productpurchaseID($id) {
		$this->db->delete($this->_table_name, array('productpurchaseID' => $id)); 
		return TRUE;
	}
}