<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacherstudent_m extends CI_Model {
	protected $_table_name = 'student';
	protected $_primary_key = 'student.studentID';
	protected $_primary_class_key = 'student.classesID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "roll asc";

	protected $_extend_array = [
		'studentextendID',
		'studentgroupID',
		'optionalsubjectID',
		'extracurricularactivities',
		'remarks'
	];

	public function prefixLoad($array) {
		if(is_array($array)) {
			if(count($array)) {
				foreach ($array as $arkey =>  $ar) {
					if(in_array($arkey, $this->_extend_array)) {
						unset($array[$arkey]);
						$array['studentextend.'.$arkey] = $ar;
					} else {
						unset($array[$arkey]);
						$array['student.'.$arkey] = $ar;
					}
				}
			}
		}

		return $array;
	} 

	public function teacherClass() {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$teacherID = $this->session->userdata('loginuserID');

		$this->db->from('classes')->where(array('teacherID' => $teacherID))->order_by('classesID');
		$classQuery = $this->db->get();
		$classResult = $classQuery->result();

		$this->db->from('routine')->where(array('teacherID' => $teacherID, 'schoolyearID' => $schoolyearID))->order_by('classesID');
		$routineQuery = $this->db->get();
		$routineResult = $routineQuery->result();

		$classMerged = (object) array_merge((array) $classResult , (array) $routineResult);

		$classes = array_unique(pluck($classMerged, 'classesID', 'classesID'));
		ksort($classes);
		return $classes;
	}

	public function get_teacher_student($studentID = NULL, $single = FALSE) {
		$classArray = $this->teacherClass();

		if(count($classArray)) {
			if((int)$studentID) {
				$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
				$this->db->where_in($this->_primary_class_key, $classArray);
				$this->db->where(array($this->_primary_key => $studentID));
				$this->db->order_by($this->_order_by); 
				$query = $this->db->get($this->_table_name);

				if($studentID != NULL) {
					return $query->row();
				} elseif($single) {
					return $query->row();
				} else {
					return $query->result();
				}
			} else {
				$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
				$this->db->where_in($this->_primary_class_key, $classArray);
				$this->db->order_by($this->_order_by); 
				$query = $this->db->get($this->_table_name);

				if ($studentID != NULL) {
					return $query->row();
				} elseif($single) {
					return $query->row();
				} else {
					return $query->result();
				}
			}
		} else {
			$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}

	public function get_single_teacher_student($array = NULL) {
		$array = $this->prefixLoad($array);
		$classArray = $this->teacherClass();

		if(count($classArray)) {
			if(is_array($array)) {
				if(isset($array[$this->_primary_class_key])) {
					if(in_array($array[$this->_primary_class_key], $classArray)) {
						$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
						$this->db->where_in($this->_primary_class_key, $classArray[$array[$this->_primary_class_key]]);
						unset($array[$this->_primary_class_key]);
						$this->db->where($array);
						$this->db->order_by($this->_order_by); 
						$query = $this->db->get($this->_table_name);
						$query = $query->result();
						if(count($query)) {
							if(in_array($query[0]->classesID, $classArray)) {
								return $query[0];
							} else {
								$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
								$this->db->where(array($this->_primary_key => 0));
								$query = $this->db->get($this->_table_name);
								return $query->result();
							}
						} else {
							$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
							$this->db->where(array($this->_primary_key => 0));
							$query = $this->db->get($this->_table_name);
							return $query->result();
						}
					} else {
						$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
						$this->db->where(array($this->_primary_key => 0));
						$query = $this->db->get($this->_table_name);
						return $query->result();
					}
				} else {
					$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
					$this->db->where_in($this->_primary_class_key, $classArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by);
					$query = $this->db->get($this->_table_name);
					$query = $query->result();

					if(count($query)) {
						if(in_array($query[0]->classesID, $classArray)) {
							return $query[0];
						} else {
							$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
							$this->db->where(array($this->_primary_key => 0));
							$query = $this->db->get($this->_table_name);
							return $query->result();
						}
					} else {
						$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
						$this->db->where(array($this->_primary_key => 0));
						$query = $this->db->get($this->_table_name);
						return $query->result();
					}
				}
			} else {
				$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
				$this->db->where_in($this->_primary_class_key, $classArray);
				$this->db->order_by($this->_order_by);
				$query = $this->db->get($this->_table_name);
				return $query->result();
			}
		} else {
			$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}

	public function get_order_by_teacher_student($array = NULL) {
		$array = $this->prefixLoad($array);
		$classArray = $this->teacherClass();

		if(count($classArray)) {
			if(is_array($array)) {
				if(isset($array[$this->_primary_class_key])) {
					if(in_array($array[$this->_primary_class_key], $classArray)) {
						$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
						$this->db->where_in($this->_primary_class_key, $classArray[$array[$this->_primary_class_key]]);
						unset($array[$this->_primary_class_key]);
						$this->db->where($array);
						$this->db->order_by($this->_order_by); 
						$query = $this->db->get($this->_table_name);
						$query = $query->result();
						return $query;
					} else {
						$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
						$this->db->where(array($this->_primary_key => 0));
						$query = $this->db->get($this->_table_name);
						return $query->result();
					}
				} else {
					$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
					$this->db->where_in($this->_primary_class_key, $classArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by);
					$query = $this->db->get($this->_table_name);
					$query = $query->result();
					return $query;
				}
			} else {
				$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
				$this->db->where_in($this->_primary_class_key, $classArray);
				$this->db->order_by($this->_order_by);
				$query = $this->db->get($this->_table_name);
				return $query->result();
			}
		} else {
			$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}
}