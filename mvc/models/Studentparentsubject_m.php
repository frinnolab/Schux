<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentparentsubject_m extends CI_Model {
    protected $_table_name = 'subject';
	protected $_primary_key = 'subjectID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classesID asc";

    function __construct() {
        parent::__construct();
    }

    public function studentparentSubject() {
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

			$studentClassArray = pluck($studentrelationResult, 'srclassesID');
			if(count($studentClassArray)) {
				$this->db->from('subject');
				$this->db->where_in('classesID', $studentClassArray);
				$this->db->order_by('classesID asc');
				$subjectQuery = $this->db->get();
				$subjectResult = $subjectQuery->result();
				$subject = array_unique(pluck($subjectResult, 'subjectID', 'subjectID'));
				return $subject;
			} else {
				return [];
			}
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

			$studentClassArray = array_unique(pluck($studentrelationResult, 'srclassesID'));
			
			if(count($studentClassArray)) {
				$this->db->from('subject');
				$this->db->where_in('classesID', $studentClassArray);
				$this->db->order_by('classesID asc');
				$subjectQuery = $this->db->get();
				$subjectResult = $subjectQuery->result();
				$subject = array_unique(pluck($subjectResult, 'subjectID', 'subjectID'));
				return $subject;
			} else {
				return [];
			}
		} else {
			return [];
		}
	}

	public function get_studentparent_subject($subjectID = NULL, $single = FALSE) {
		$subjectArray = $this->studentparentSubject();
		if(count($subjectArray)) {
			if($subjectID) {
				if(in_array($subjectID, $subjectArray)) {
					$this->db->where_in($this->_primary_key, $subjectArray[$subjectID]);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);

					if ($subjectID != NULL) {
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

	public function get_single_studentparent_subject($array = NULL) {
		$subjectArray = $this->studentparentSubject();

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

	public function get_order_by_studentparent_subject($array = NULL) {
		$subjectArray = $this->studentparentSubject();
		
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
		$subjectArray = $this->studentparentSubject();
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
