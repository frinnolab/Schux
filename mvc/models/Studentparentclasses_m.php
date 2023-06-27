<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentparentclasses_m extends CI_Model {
    protected $_table_name = 'classes';
	protected $_primary_key = 'classesID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classes_numeric asc";

    function __construct() {
        parent::__construct();
    }

    public function studentparentClass() {
		$studentparentID = $this->session->userdata('loginuserID');
		$usertypeID = $this->session->userdata('usertypeID');

		if($usertypeID == 3) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->db->from('studentrelation');
        	$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
       		$this->db->where('studentrelation.srschoolyearID =', $schoolyearID);
       		$this->db->where('student.studentID !=', NULL);
       		$this->db->where('studentrelation.srstudentID', $studentparentID);
       		$this->db->order_by('srroll asc');
			$studentrelationQuery = $this->db->get();
			$studentrelationResult = $studentrelationQuery->result();

			$studentClassArray = pluck($studentrelationResult, 'srclassesID', 'srclassesID');
			return $studentClassArray;
		} elseif($usertypeID == 4) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->db->from('studentrelation');
        	$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
       		$this->db->where('studentrelation.srschoolyearID =', $schoolyearID);
       		$this->db->where('student.parentID =', $studentparentID);
       		$this->db->where('student.studentID !=', NULL);
       		$this->db->order_by('srroll asc');
			$studentrelationQuery = $this->db->get();
			$studentrelationResult = $studentrelationQuery->result();

			$studentClassArray = array_unique(pluck($studentrelationResult, 'srclassesID', 'srclassesID'));
			ksort($studentClassArray);
			return $studentClassArray;
		} else {
			return [];
		}
	}

	public function get_studentparent_class($classesID = NULL, $single = FALSE) {
		$classArray = $this->studentparentClass();

		if(count($classArray)) {
			if($classesID) {
				if(in_array($classesID, $classArray)) {
					$this->db->where_in($this->_primary_key, $classArray[$classesID]);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);

					if ($classesID != NULL) {
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
				$this->db->where_in($this->_primary_key, $classArray);
				$this->db->order_by($this->_order_by); 
				$query = $this->db->get($this->_table_name);

				if ($classesID != NULL) {
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

	public function get_single_studentparent_class($array = NULL) {
		$classArray = $this->studentparentClass();

		if(count($classArray)) {
			if(is_array($array)) {
				if(isset($array['classesID'])) {
					if(in_array($array['classesID'], $classArray)) {
						$this->db->where_in($this->_primary_key, $classArray[$array['classesID']]);
						unset($array['classesID']);
						$this->db->where($array);
						$this->db->order_by($this->_order_by); 
						$query = $this->db->get($this->_table_name);
						$query = $query->result();

						if(count($query)) {
							if(in_array($query[0]->classesID, $classArray)) {
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
					$this->db->where_in($this->_primary_key, $classArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by);
					$query = $this->db->get($this->_table_name);
					return $query->row();
				}
			} else {
				$this->db->where_in($this->_primary_key, $classArray);
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

	public function get_order_by_studentparent_class($array = NULL) {
		$classArray = $this->studentparentClass();
		
		if(count($classArray)) {
			if(is_array($array)) {
				if(isset($array['classesID'])) {
					if(in_array($array['classesID'], $classArray)) {
						$this->db->where_in($this->_primary_key, $classArray[$array['classesID']]);
						unset($array['classesID']);
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
					$this->db->where_in($this->_primary_key, $classArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);
					$query = $query->result();
					return $query;
				}
			} else {
				$this->db->where_in($this->_primary_key, $classArray);
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

	public function get_studentparent_with_class() {
		$classArray = $this->studentparentClass();
		if(count($classArray)) {
			$this->db->join('teacher', 'classes.teacherID = teacher.teacherID', 'LEFT');
			$this->db->where_in('classes.'.$this->_primary_key, $classArray);
			$this->db->order_by($this->_order_by); 
			$query = $this->db->get($this->_table_name);
			return $query->result();
		} else {
			$this->db->where(array($this->_primary_key => 0));
			$query = $this->db->get($this->_table_name);
			return $query->result();
		}
	}
}
