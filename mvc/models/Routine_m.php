<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Classes_m.php";

class Routine_m extends MY_Model {

	protected $_table_name = 'routine';
	protected $_primary_key = 'routineID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classesID asc";

	function __construct() {
		parent::__construct();
	}

	private function prefixLoad($array) {
		if(is_array($array)) {
			if(count($array)) {
				foreach ($array as $arkey =>  $ar) {
					$array[$this->_table_name.'.'.$arkey] = $ar;
					unset($array[$arkey]);
				}
			}
		}
		return $array;
	}

	public function get_routine_with_teacher_class_section_subject($array) {
		$array = $this->prefixLoad($array);
		$this->db->select('routine.*, teacher.*, combinedTeacher.name as combinedTeacher, classes.*, section.*, subject.*, combinedSubject.subject as combinedSubject');
		$this->db->from('routine');
		$this->db->join('teacher', 'teacher.teacherID = routine.teacherID', 'LEFT');
		$this->db->join('teacher as combinedTeacher', 'combinedTeacher.teacherID = routine.combinedTeacherID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = routine.classesID', 'LEFT');
		$this->db->join('section', 'section.sectionID = routine.sectionID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = routine.subjectID AND subject.classesID = routine.classesID', 'LEFT');
		$this->db->join('subject as combinedSubject', 'combinedSubject.subjectID = routine.combinedSubjectID AND subject.classesID = routine.classesID', 'LEFT');
		$this->db->where($array);
		$query = $this->db->get();
		return $query->result();
	}

	public function check_teacher_collision_in_routine($array) {

		$query  = "SELECT * FROM routine WHERE day = ".$array['day']." AND ";
		$query .= "(start_time = '".$array['start_time']."' OR ";
		$query .= "end_time = '".$array['end_time']."') AND ";
		$query .= "schoolyearID = ".$array['schoolyearID']." AND ";

		if($array['id'])
		{

			$query .= "routineID != ".$array['id']." AND ";

			if ($array['combinedTeacherID']) {
				
				$query .= "((teacherID = ".$array['teacherID']." OR teacherID = ".$array['combinedTeacherID'].") OR ";
				$query .= "(combinedTeacherID = ".$array['teacherID']." OR combinedTeacherID = ".$array['combinedTeacherID']."))";
			
			} else {

				$query .= "teacherID = ".$array['teacherID'];
			
			}

		} else {

			if ($array['combinedTeacherID']) {
				
				$query .= "(teacherID = ".$array['teacherID']." OR teacherID = ".$array['combinedTeacherID'].")";
			
			} else {

				$query .= "teacherID = ".$array['teacherID'];
			
			}

		}
		$result = $this->db->query($query);
		return $result->row();
	}

	public function check_combined_teacher_collision_in_routine($array) {

		$query  = "SELECT * FROM routine WHERE day = ".$array['day']." AND ";
		$query .= "(start_time = '".$array['start_time']."' OR ";
		$query .= "end_time = '".$array['end_time']."') AND ";
		$query .= "schoolyearID = ".$array['schoolyearID']." AND ";

		if($array['id'])
		{

			$query .= "routineID != ".$array['id']." AND ";

			$query .= "(combinedTeacherID = ".$array['teacherID']." OR combinedTeacherID = ".$array['combinedTeacherID'].")";
			

		} else {

			$query .= "(combinedTeacherID = ".$array['teacherID']." OR combinedTeacherID = ".$array['combinedTeacherID'].")";
			
		}
		$result = $this->db->query($query);
		return $result->row();
	}

	public function check_room_collision_in_routine($array) {

		$query  = "SELECT * FROM routine WHERE day = ".$array['day']." AND ";
		$query .= "(start_time = '".$array['start_time']."' OR ";
		$query .= "end_time = '".$array['end_time']."') AND ";
		$query .= "schoolyearID = ".$array['schoolyearID']." AND ";

		if($array['id'])
		{

			$query .= "routineID != ".$array['id']." AND ";

			if ($array['combinedRoom']) {
				
				$query .= "(room = '".$array['room']."' OR room = '".$array['combinedRoom']."')";
			
			} else {

				$query .= "room = '".$array['room']."'";
			
			}

		} else {

			if ($array['combinedRoom']) {
				
				$query .= "(room = '".$array['room']."' OR room = '".$array['combinedRoom']."')";
			
			} else {

				$query .= "room = '".$array['room']."'";
			
			}

		}
		$result = $this->db->query($query);
		return $result->row();
	}

	public function check_combined_room_collision_in_routine($array) {

		$query  = "SELECT * FROM routine WHERE day = ".$array['day']." AND ";
		$query .= "(start_time = '".$array['start_time']."' OR ";
		$query .= "end_time = '".$array['end_time']."') AND ";
		$query .= "schoolyearID = ".$array['schoolyearID']." AND ";

		if($array['id'])
		{

			$query .= "routineID != ".$array['id']." AND ";

			$query .= "(combinedRoom = '".$array['room']."' OR combinedRoom = '".$array['combinedRoom']."')";

		} else {

			$query .= "(combinedRoom = '".$array['room']."' OR combinedRoom = '".$array['combinedRoom']."')";

		}
		$result = $this->db->query($query);
		return $result->row();
	}

	public function get_routine($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_routine($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_routine($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_routine($array) {
		$id = parent::insert($array);
		return $id;
	}

	public function update_routine($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_routine($id){
		parent::delete($id);
	}
}