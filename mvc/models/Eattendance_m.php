<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eattendance_m extends MY_Model {

	protected $_table_name = 'eattendance';
	protected $_primary_key = 'eattendanceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "eattendance desc";

	function __construct() {
		parent::__construct();
	}

	public function get_eattendance($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_eattendance($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_eattendance($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_eattendance($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_eattendance($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_eattendance_via_array($array, $wheraarray) {
		$this->db->update($this->_table_name, $array, $wheraarray);
		return TRUE;
	}

	public function delete_eattendance($id){
		parent::delete($id);
	}

	public function get_eattendance_with_student($examID, $studentID, $schoolyearID) {
		$this->db->select('*');
		$this->db->from('eattendance');
		$this->db->join('exam', 'exam.examID = eattendance.examID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = eattendance.subjectID', 'LEFT');
		$this->db->join('student', 'student.studentID = eattendance.studentID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = eattendance.classesID', 'LEFT');
		$this->db->where(array('eattendance.examID' => $examID, 'eattendance.studentID' => $studentID, 'eattendance.schoolyearID' => $schoolyearID));
		$query = $this->db->get();
		return $query->result();
	}
}