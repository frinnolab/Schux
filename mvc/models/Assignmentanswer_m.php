<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assignmentanswer_m extends MY_Model {

	protected $_table_name = 'assignmentanswer';
	protected $_primary_key = 'assignmentanswerID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "answerdate desc";

	function __construct() {
		parent::__construct();
	}

	public function join_get_assignmentanswer($assignmentID, $schoolyearID, $studentID=NULL) {
		$this->db->select('*');
		$this->db->from('assignmentanswer');
		$this->db->join('studentrelation', 'studentrelation.srstudentID = assignmentanswer.uploaderID', 'LEFT');
		$this->db->join('section', 'section.sectionID = studentrelation.srsectionID', 'LEFT');
		$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
		$this->db->where('assignmentanswer.assignmentID', $assignmentID);
		$this->db->where('assignmentanswer.schoolyearID', $schoolyearID);
		$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		
		if($studentID == NULL) {
			$query = $this->db->get();
			return $query->result();
		} else {
			$this->db->where('assignmentanswer.uploaderID', $studentID);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function get_assignmentanswer($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_assignmentanswer($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_assignmentanswer($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_assignmentanswer($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_assignmentanswer($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_assignmentanswer($id){
		parent::delete($id);
	}
}