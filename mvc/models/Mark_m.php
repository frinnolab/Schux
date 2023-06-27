<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mark_m extends MY_Model {

	protected $_table_name = 'mark';
	protected $_primary_key = 'markID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "subject asc";

	function __construct() {
		parent::__construct();
	}

	public function get_mark($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_mark($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_mark($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_mark($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_mark($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_mark($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_mark_classes($array, $id) {
		$this->db->update($this->_table_name, $array, $id);
		return $id;
	}

	public function update_mark_with_condition($array, $id) {
		$this->db->update($this->_table_name, $array, $id);
		return $this->db->affected_rows();
	}

	public function delete_mark($id){
		parent::delete($id);
	}

	public function sum_student_subject_mark($studentID, $classesID, $subjectID) {
		$array = array(
			"studentID" => $studentID,
			"classesID" => $classesID,
			"subjectID" => $subjectID
		);
		$this->db->select_sum('mark');
		$this->db->where($array);
		$query = $this->db->get('mark');
		return $query->row();
	}

	public function student_all_mark_array($array) {
		$this->db->select('*');
		$this->db->from('mark');
		$this->db->join('markrelation', 'markrelation.markID = mark.markID', 'LEFT');
		
		if(isset($array['subjectID'])) {
			$this->db->where('mark.subjectID', $array['subjectID']);
		}

		if(isset($array['schoolyearID'])) {
			$this->db->where('mark.schoolyearID', $array['schoolyearID']);
		}

		if(isset($array['examID'])) {
			$this->db->where('mark.examID', $array['examID']);
		}

		if(isset($array['classesID'])) {
			$this->db->where('mark.classesID', $array['classesID']);
		}

		if(isset($array['studentID'])) {
			$this->db->where('mark.studentID', $array['studentID']);
		}

		$query = $this->db->get();
		return $query->result();
	}

	public function count_subject_mark($studentID, $classesID, $subjectID) {
		$query = "SELECT COUNT(*) as 'total_semester' FROM mark WHERE studentID = $studentID AND classesID = $classesID AND subjectID = $subjectID AND (mark != '' || mark <= 0 || mark >0)";
	    $query = $this->db->query($query);
	    $result = $query->row();
	    return $result;
	}

	public function get_order_by_mark_with_subject($classes,$year) {
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->join('mark', 'subject.subjectID = mark.subjectID', 'LEFT');
		$this->db->join('exam', 'exam.examID = mark.examID');
		$this->db->where('mark.classesID', $classes);
		$this->db->where('mark.year', $year);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_order_by_student_mark_with_subject($classID, $yearID, $studentID=NULL) {
		$this->db->select('*,subject.subjectID subjectID, subject.classesID');
		$this->db->from('subject');
		$this->db->join('mark', 'subject.subjectID = mark.subjectID', 'LEFT');
		$this->db->join('markrelation', 'markrelation.markID = mark.markID', 'LEFT');
		$this->db->join('markpercentage', 'markpercentage.markpercentageID = markrelation.markpercentageID', 'LEFT');
		$this->db->join('student', 'mark.studentID = student.studentID', 'LEFT');
		$this->db->join('exam', 'exam.examID = mark.examID');
		$this->db->where('mark.classesID', $classID);
		if(isset($studentID) && $studentID != NULL) {
			$this->db->where('mark.studentID', $studentID);
		}
		$this->db->where('student.schoolyearID', $yearID);
		$this->db->where('mark.schoolyearID', $yearID);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_order_by_all_student_mark_with_markrelation($markArray) {
		$this->db->select('mark.*, markrelation.markrelationID, markrelation.markpercentageID, markrelation.mark');
		$this->db->from('mark');
		$this->db->join('markrelation', 'markrelation.markID = mark.markID', 'LEFT');
		if(isset($markArray['schoolyearID'])) {
			$this->db->where('mark.schoolyearID', $markArray['schoolyearID']);
		}

		if(isset($markArray['classesID'])) {
			$this->db->where('mark.classesID', $markArray['classesID']);
		}

		if(isset($markArray['examID'])) {
			$this->db->where('mark.examID', $markArray['examID']);
		}
		
		$query = $this->db->get();
		return $query->result();
	}

	public function get_order_by_mark_with_highest_mark($classID,$studentID) {
		$this->db->select('M.markID,M.examID, M.exam, M.subjectID, M.subject, M.studentID, M.classesID,  M.mark, M.year, (
		SELECT Max( mark.mark )
		FROM mark
		WHERE mark.subjectID = M.subjectID
		AND mark.examID = M.examID
		) highestmark');
		$this->db->from('exam E');
		$this->db->join('mark M', 'M.examID = E.examID', 'LEFT');
		$this->db->join('subject S', 'M.subjectID = S.subjectID');
		$this->db->where('M.classesID', $classID);
		$this->db->where('M.studentID', $studentID);
		$query = $this->db->get();
		return $query->result();
	}
}

/* End of file mark_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/mark_m.php */
