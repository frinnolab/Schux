<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentparentteacher_m extends CI_Model {
    protected $_table_name = 'teacher';
	protected $_primary_key = 'teacherID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "name asc";

    function __construct() {
        parent::__construct();
    }

    public function studentparentTeacher() {
		$studentparentID = $this->session->userdata('loginuserID');
		$usertypeID = $this->session->userdata('usertypeID');

		if($usertypeID == 3) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');

			$this->db->from('studentrelation');
        	$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
       		$this->db->where('studentrelation.srschoolyearID =', $schoolyearID);
       		$this->db->where('student.studentID !=', NULL);
       		$this->db->order_by('srroll asc');
			$studentrelationQuery = $this->db->get();
			$studentrelationResult = $studentrelationQuery->row();

			if(count($studentrelationResult)) {
				$this->db->from('classes')->where(array('classesID' => $studentrelationResult->srclassesID))->order_by('classes_numeric asc');
				$classQuery = $this->db->get();
				$classResult = $classQuery->result();

				$this->db->from('routine')->where(array('classesID' => $studentrelationResult->srclassesID, 'sectionID' => $studentrelationResult->srsectionID, 'schoolyearID' => $schoolyearID))->order_by('teacherID asc');
				$routineQuery = $this->db->get();
				$routineResult = $routineQuery->result();

				$teacherMerged = (object) array_merge((array) $classResult , (array) $routineResult);

				$teacher = array_unique(pluck($teacherMerged, 'teacherID', 'teacherID'));
				ksort($teacher);
				return $teacher;
			}
			return [];
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

			$studentArray = [];
			$studentClassArray = [];
			$studentSectionArray = [];
			if(count($studentrelationResult)) {
				foreach ($studentrelationResult as $studentrelation) {
					$studentArray[] = $studentrelation->srstudentID; 
					$studentClassArray[] = $studentrelation->srclassesID; 
					$studentSectionArray[] = $studentrelation->srsectionID; 
				}
			}

			$studentClassArray = array_unique($studentClassArray);
			
			if(count($studentClassArray)) {
				$this->db->from('classes');
				$this->db->where_in('classesID', $studentClassArray);
				$this->db->order_by('classes_numeric asc');
				$classQuery = $this->db->get();
				$classResult = $classQuery->result();

				$this->db->from('routine');
				$this->db->where_in('classesID', $studentClassArray);
				$this->db->where_in('sectionID', $studentSectionArray);
				$this->db->where(array('schoolyearID' => $schoolyearID));
				$this->db->order_by('teacherID asc');
				$routineQuery = $this->db->get();
				$routineResult = $routineQuery->result();
			} else {
				$classResult = [];
				$routineResult = [];
			}

			$teacherMerged = (object) array_merge((array) $classResult , (array) $routineResult);
			$teacher = array_unique(pluck($teacherMerged, 'teacherID', 'teacherID'));
			ksort($teacher);
			return $teacher;
		} else {
			return [];
		}
	}

    public function get_studentparent_teacher($teacherID = NULL, $single = FALSE) {
		$teacherArray = $this->studentparentTeacher();

		if($teacherArray) {
			if((int)$teacherID) {
				if(in_array($teacherID, $teacherArray)) {
					$this->db->where_in($this->_primary_key, $teacherArray[$teacherID]);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);

					if ($teacherID != NULL) {
						return $query->row();
					} elseif($single) {
						return $query->row();
					} else {
						return $query->result();
					}
				} else {
					$this->db->where(array($this->_primary_key => 0));
					$query = $this->db->get($this->_table_name);
					return $query->row();
				}
			} else {
				$this->db->where_in($this->_primary_key, $teacherArray);
				$this->db->order_by($this->_order_by); 
				$query = $this->db->get($this->_table_name);

				if ($teacherID != NULL) {
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

	public function get_single_studentparent_teacher($array = NULL) {
		$teacherArray = $this->studentparentTeacher();
		if(count($teacherArray)) {
			if(is_array($array)) {
				if(isset($array['teacherID'])) {
					if(in_array($array['teacherID'], $teacherArray)) {
						$this->db->where_in($this->_primary_key, $teacherArray[$array['teacherID']]);
						unset($array['teacherID']);
						$this->db->where($array);
						$this->db->order_by($this->_order_by); 
						$query = $this->db->get($this->_table_name);
						$query = $query->result();

						if(count($query)) {
							if(in_array($query[0]->teacherID, $teacherArray)) {
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
					$this->db->where_in($this->_primary_key, $teacherArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by);
					$query = $this->db->get($this->_table_name);
					return $query->row();
				}
			} else {
				$this->db->where_in($this->_primary_key, $teacherArray);
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

	public function get_order_by_studentparent_teacher($array = NULL) {
		$teacherArray = $this->studentparentTeacher();

		if(count($teacherArray)) {
			if(is_array($array)) {
				if(isset($array['teacherID'])) {
					if(in_array($array['teacherID'], $teacherArray)) {
						$this->db->where_in($this->_primary_key, $teacherArray[$array['teacherID']]);
						unset($array['teacherID']);
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
					$this->db->where_in($this->_primary_key, $teacherArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);
					$query = $query->result();
					return $query;
				}
			} else {
				$this->db->where_in($this->_primary_key, $teacherArray);
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
}
