<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marksettingrelation_m extends MY_Model {

	protected $_table_name     = 'marksettingrelation';
	protected $_primary_key    = 'marksettingrelationID';
	protected $_primary_filter = 'intval';
	protected $_order_by       = "marksettingrelationID";

	function __construct() {
		parent::__construct();
	}

	public function get_marksettingrelation($array=NULL, $single=FALSE) {
		return parent::get($array, $single);
	}

	public function get_order_by_marksettingrelation($array=NULL) {
		return parent::get_order_by($array);
	}

	public function get_single_marksettingrelation($array=NULL) {
		return parent::get_single($array);
	}

	public function insert_marksettingrelation($array) {
		return parent::insert($array);
	}

	public function insert_batch_marksettingrelation($array) {
		return parent::insert_batch($array);
	}

	public function update_marksettingrelation($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_marksettingrelation($id){
		return parent::delete($id);
	}

	public function delete_marksettingrelation_by_array($array=[]) {
		if(count($array)) {
			$this->db->where($array);
			return $this->db->delete($this->_table_name);
		} 
		return FALSE;
	}

}