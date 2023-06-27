<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Teacherstudent_m.php';
require_once 'Studentparentstudent_m.php';

class student_m extends MY_Model {

	protected $_table_name = 'student';
	protected $_primary_key = 'student.studentID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "roll asc";

	function __construct() {
		parent::__construct();
	}

	public function get_username($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	public function get_single_username($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->row();
	}

	function get_class($id=NULL) {
		$class = new Classes_m;
	    return $class->get_classes($id);
	}

	function get_classes() {
	    $class = new Classes_m;
	    return $class->get_order_by_classes();
	}


	public function general_get_student($array=NULL, $signal=FALSE) {
		$array = $this->makeArrayWithTableName($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get($array, $signal);
		return $query;
	}

	public function general_get_order_by_student($array=NULL) {
		$teacherstudent = new Teacherstudent_m;
		$array = $teacherstudent->prefixLoad($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get_order_by($array);
		return $query;
	}

	public function general_get_single_student($array) {
		$teacherstudent = new Teacherstudent_m;
		$array = $teacherstudent->prefixLoad($array);
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$query = parent::get_single($array);
		return $query;
	}

	public function general_get_where_in_student($array, $key = NULL) {
		$query = parent::get_where_in($array, $key);
		return $query;
	}

	public function get_student($id=NULL, $single=FALSE) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherstudent = new Teacherstudent_m;
	    	return $teacherstudent->get_teacher_student($id, $single);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentstudent = new Studentparentstudent_m;
			return $studentparentstudent->get_studentparent_student($id, $single);
		} else {
	        $this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
			$query = parent::get($id, $single);
			return $query;
		}
	}

	public function get_single_student($array) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherstudent = new Teacherstudent_m;
	    	return $teacherstudent->get_single_teacher_student($array);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentstudent = new Studentparentstudent_m;
			return $studentparentstudent->get_single_studentparent_student($array);
		} else {
			$teacherstudent = new Teacherstudent_m;
			$array = $teacherstudent->prefixLoad($array);
	        $this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
			$query = parent::get_single($array);
			return $query;
		}
	}

	public function get_order_by_student($array=[]) {
        $usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherstudent = new Teacherstudent_m;
	    	return $teacherstudent->get_order_by_teacher_student($array);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentstudent = new Studentparentstudent_m;
			return $studentparentstudent->get_order_by_studentparent_student($array);
		} else {
			$teacherstudent = new Teacherstudent_m;
			$array = $teacherstudent->prefixLoad($array);
	        $this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
			$query = parent::get_order_by($array);
			return $query;
		}
	}

	public function get_select_student($select = NULL, $array=[]) {
		if($select == NULL) {
			$select = 'studentID, name, photo';
		}

		$this->db->select($select);
		$this->db->from($this->_table_name);

		if(count($array)) {
			$this->db->where($array);
		}

		$query = $this->db->get();
		return $query->result();
	}

	public function insert_student($array) {
		$id = parent::insert($array);
		return $id;
	}

	public function insert_parent($array) {
		$this->db->insert('parent', $array);
		return TRUE;
	}

	public function update_student($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_student_classes($data, $array = NULL) {
		$this->db->set($data);
		$this->db->where($array);
		$this->db->update($this->_table_name);
	}

	public function delete_student($id){
		parent::delete($id);
	}

	public function delete_parent($id){
		$this->db->delete('parent', array('studentID' => $id));
	}

	public function hash($string) {
		return parent::hash($string);
	}

	public function profileUpdate($table, $data, $username) {
		$this->db->update($table, $data, "username = '".$username."'");
		return TRUE;
	}

	public function profileRelationUpdate($table, $data, $studentID, $schoolyearID) {
		$this->db->update($table, $data, "srstudentID = '".$studentID."' AND srschoolyearID = '".$schoolyearID."'");
		return TRUE;
	}

	/* Start For Promotion */
	public function get_order_by_student_year($classesID) {
		$query = $this->db->query("SELECT * FROM student WHERE year = (SELECT MIN(year) FROM student) AND classesID = $classesID order by roll asc");
		return $query->result();
	}

	public function get_order_by_student_single_year($classesID) {
		$query = $this->db->query("SELECT year FROM student WHERE year = (SELECT MIN(year) FROM student) AND classesID = $classesID order by roll asc");
		return $query->row();
	}

	public function get_order_by_student_single_max_year($classesID) {
		$query = $this->db->query("SELECT year FROM student WHERE year = (SELECT MAX(year) FROM student) AND classesID = $classesID order by roll asc");
		return $query->row();
	}
	/* End For Promotion */


	/* Start For Report */
	public function get_order_by_student_with_section($classesID, $schoolyearID, $sectionID=NULL) {
		$this->db->select('*');
		$this->db->from('student');
		$this->db->join('classes', 'student.classesID = classes.classesID', 'LEFT');
		$this->db->join('section', 'student.sectionID = section.sectionID', 'LEFT');
		$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
		$this->db->where('student.classesID', $classesID);
		$this->db->where('student.schoolyearID', $schoolyearID);
		if($sectionID != NULL) {
			$this->db->where('student.sectionID', $sectionID);
		}
		$query = $this->db->get();
		return $query->result();
	}

	/* End For Report */

	public function get_max_student() {
		$query = $this->db->query("SELECT * FROM $this->_table_name WHERE studentID = (SELECT MAX(studentID) FROM $this->_table_name)");
		return $query->row();
	}
}