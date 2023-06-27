<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subjectteacher_m extends MY_Model {

	protected $_table_name = 'subjectteacher';
	protected $_primary_key = 'subjectteacherID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "subjectteacherID asc";

	public function __construct() {
		parent::__construct();
	}

	public function get_subjectteacher_with_teacher($subjectID) {
		$this->db->from($this->_table_name);
    	$this->db->join('teacher', 'teacher.teacherID = subjectteacher.teacherID', 'LEFT');
   		$this->db->where('subjectteacher.subjectID', $subjectID);
   		$this->db->order_by('teacher.name asc');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_subjectteacher($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_subjectteacher($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_where_in_subjectteacher($array, $key=NULL) {
		$query = parent::get_where_in($array, $key);
		return $query;
	}

	public function insert_subjectteacher($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_subjectteacher($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_subjectteacher($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_subjectteacher($id){
		parent::delete($id);
	}

	public function delete_subjectteacher_by_array($array) {
		$this->db->delete($this->_table_name, $array); 
		return TRUE;
	}
}