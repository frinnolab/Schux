<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Teacherclasses_m.php';
require_once 'Studentparentclasses_m.php';

class Classes_m extends MY_Model {

	protected $_table_name = 'classes';
	protected $_primary_key = 'classesID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classes_numeric asc";

	function __construct() {
		parent::__construct();
	}

	public function get_join_classes() {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherclass = new Teacherclasses_m;
	    	return $teacherclass->get_teacher_with_class();
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentclasses = new Studentparentclasses_m;
	    	return $studentparentclasses->get_studentparent_with_class();
		} else {
			$this->db->select('*');
			$this->db->from('classes');
			$this->db->join('teacher', 'classes.teacherID = teacher.teacherID', 'LEFT');
			$this->db->order_by('classes_numeric asc');
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function general_get_classes($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function general_get_order_by_classes($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function general_get_single_classes($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function general_get_single_classes_attendance($array=NULL) {
		$query = parent::get_multiple_classes($array);
		return $query;
	}

	public function get_classes($id=NULL, $signal=false) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherclass = new Teacherclasses_m;
	    	return $teacherclass->get_teacher_class($id, $signal);
	    } elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentclasses = new Studentparentclasses_m;
	    	return $studentparentclasses->get_studentparent_class($id, $signal);
		} else {
			$query = parent::get($id, $signal);
			return $query;
		}
	}

	public function get_single_classes($array=NULL) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherclass = new Teacherclasses_m;
	    	return $teacherclass->get_single_teacher_class($array);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentclasses = new Studentparentclasses_m;
	    	return $studentparentclasses->get_single_studentparent_class($array);
		} else {
			$query = parent::get_single($array);
			return $query;
		}
	}

	public function get_order_by_classes($array=NULL) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teacherclass = new Teacherclasses_m;
	    	return $teacherclass->get_order_by_teacher_class($array);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentparentclasses = new Studentparentclasses_m;
	    	return $studentparentclasses->get_order_by_studentparent_class($array);
		} else {
			$query = parent::get_order_by($array);
			return $query;
		}
	}

	public function insert_classes($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_classes($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_classes($id){
		parent::delete($id);
	}

	
}