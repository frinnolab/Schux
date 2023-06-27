<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teachersubject_m extends CI_Model {
	protected $_table_name = 'subject';
	protected $_primary_key = 'subjectID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classesID asc";

	public function teacherSubject() {
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$teacherID = $this->session->userdata('loginuserID');

		$this->db->from('classes')->where(array('teacherID' => $teacherID))->order_by('classesID');
		$classQuery = $this->db->get();
		$classResult = $classQuery->result();

		$classSectionResult = [];
		if(count($classResult)) {
			$classPluck = pluck($classResult, 'classesID');
			if(count($classPluck)) {
				$this->db->where_in('classesID', $classPluck);
				$classSection = $this->db->get($this->_table_name);
				$classSectionResult = $classSection->result();
			}
		}

		$this->db->from('routine')->where(array('teacherID' => $teacherID, 'schoolyearID' => $schoolyearID))->order_by($this->_order_by);
		$routineQuery = $this->db->get();
		$routineResult = $routineQuery->result();

		$subjectMerged = (object) array_merge((array) $classSectionResult , (array) $routineResult);

		$subject = array_unique(pluck($subjectMerged, 'subjectID', 'subjectID'));
		ksort($subject);
		return $subject;
	}

	public function get_teacher_subject($subjectID = NULL, $single = FALSE) {
		$subjectArray = $this->teacherSubject();

		if(count($subjectArray)) {
			if($subjectID) {
				if(in_array($subjectID, $subjectArray)) {
					$this->db->where_in($this->_primary_key, $subjectArray[$subjectID]);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);

					if($subjectID != NULL) {
						return $query->row();
					} elseif($single) {
						return $query->row();
					} else {
						return $query->result();
					}
				} else {
					$this->db->where(array($this->_primary_key => 0));
					$query = $this->db->get($this->_table_name);
					return $query->result();
				}
			} else {
				$this->db->where_in($this->_primary_key, $subjectArray);
				$this->db->order_by($this->_order_by); 
				$query = $this->db->get($this->_table_name);

				if ($subjectID != NULL) {
					return $query->row();
				} elseif($single) {
					return $query->row();
				} else {
					return $query->result();
				}
			}
		} else {
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}

	public function get_single_teacher_subject($array = NULL) {
		$subjectArray = $this->teacherSubject();

		if(count($subjectArray)) {
			if(is_array($array)) {
				if(isset($array['subjectID'])) {
					if(in_array($array['subjectID'], $subjectArray)) {
						$this->db->where_in($this->_primary_key, $subjectArray[$array['subjectID']]);
						unset($array['subjectID']);
						$this->db->where($array);
						$this->db->order_by($this->_order_by); 
						$query = $this->db->get($this->_table_name);
						$query = $query->result();

						if(count($query)) {
							if(in_array($query[0]->subjectID, $subjectArray)) {
								return $query[0];
							} else {
								$this->db->where(array($this->_primary_key => 0));
								$query = $this->db->get($this->_table_name);
								return $query->result();
							}
						} else {
							$this->db->where(array($this->_primary_key => 0));
							$query = $this->db->get($this->_table_name);
							return $query->result();
						}
					} else {
						$this->db->where(array($this->_primary_key => 0));
						$query = $this->db->get($this->_table_name);
						return $query->result();
					}
				} else {
					$this->db->where_in($this->_primary_key, $subjectArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by);
					$query = $this->db->get($this->_table_name);
					return $query->row();
				}
			} else {
				$this->db->where_in($this->_primary_key, $subjectArray);
				$this->db->order_by($this->_order_by);
				$query = $this->db->get($this->_table_name);
				return $query->result();
			}
		} else {
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}

	public function get_order_by_teacher_subject($array = NULL) {
		$subjectArray = $this->teacherSubject();
		
		if(count($subjectArray)) {
			if(is_array($array)) {
				if(isset($array['subjectID'])) {
					if(in_array($array['subjectID'], $subjectArray)) {
						$this->db->where_in($this->_primary_key, $subjectArray[$array['subjectID']]);
						unset($array['subjectID']);
						$this->db->where($array);
						$this->db->order_by($this->_order_by); 
						$query = $this->db->get($this->_table_name);
						$query = $query->result();

						return $query;
					} else {
						$this->db->where(array($this->_primary_key => 0));
						$query = $this->db->get($this->_table_name);
						return $query->result();
					}
				} else {
					$this->db->where_in($this->_primary_key, $subjectArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);
					$query = $query->result();
					return $query;
				}
			} else {
				$this->db->where_in($this->_primary_key, $subjectArray);
				$this->db->order_by($this->_order_by);
				$query = $this->db->get($this->_table_name);
				return $query->result();
			}
		} else {
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}

	public function get_subject_with_class($id) {
		$subjectArray = $this->teacherSubject();
		if(count($subjectArray)) {
			$this->db->select('subject.*, classes.classesID, classes.classes, classes.classes_numeric, classes.studentmaxID, classes.note');
			$this->db->join('classes', 'classes.classesID = subject.classesID', 'LEFT');
			$this->db->where_in('subject.'.$this->_primary_key, $subjectArray);
			$this->db->where('subject.classesID', $id);
			$this->db->order_by('subject.'.$this->_order_by);
			$query = $this->db->get($this->_table_name);
			return $query->result();
		} else {
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}
}