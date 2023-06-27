<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentparentstudent_m extends CI_Model {
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

	public function studentparentStudent() {
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

			$studentArray = pluck($studentrelationResult, 'srstudentID', 'srstudentID');
			return $studentArray;
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

			$studentArray = array_unique(pluck($studentrelationResult, 'srstudentID', 'srstudentID'));
			ksort($studentArray);
			return $studentArray;
		} else {
			return [];
		}
	}

	public function get_studentparent_student($studentID = NULL, $single = FALSE) {
		$studentArray = $this->studentparentStudent();

		if(count($studentArray)) {
			if((int)$studentID) {
				if(in_array($studentID, $studentArray)) {
					$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
					$this->db->where_in($this->_primary_key, $studentID);
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
					$this->db->where(array($this->_primary_key => 0));
					$query = $this->db->get($this->_table_name);
					return $query->result();
				}
			} else {
				$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
				$this->db->where_in($this->_primary_key, $studentArray);
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

	public function get_single_studentparent_student($array = NULL) {
		$array = $this->prefixLoad($array);
		$studentArray = $this->studentparentStudent();

		if(count($studentArray)) {
			if(is_array($array)) {
				if(isset($array[$this->_primary_key])) {
					if(in_array($array[$this->_primary_key], $studentArray)) {
						$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
						$this->db->where_in($this->_primary_key, $array[$this->_primary_key]);
						unset($array[$this->_primary_key]);
						$this->db->where($array);
						$this->db->order_by($this->_order_by); 
						$query = $this->db->get($this->_table_name);
						$query = $query->result();

						if(count($query)) {
							if(in_array($query[0]->studentID, $studentArray)) {
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
					$this->db->where_in($this->_primary_key, $studentArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by);
					$query = $this->db->get($this->_table_name);
					return $query->row();
				}
			} else {
				$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
				$this->db->where_in($this->_primary_key, $studentArray);
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

	public function get_order_by_studentparent_student($array = NULL) {
		$array = $this->prefixLoad($array);
		$studentArray = $this->studentparentStudent();

		if(count($studentArray)) {
			if(is_array($array)) {
				if(isset($array[$this->_primary_key])) {
					if(in_array($array[$this->_primary_key], $studentArray)) {
						$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
						$this->db->where_in($this->_primary_key, $array[$this->_primary_key]);
						unset($array[$this->_primary_key]);
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
					$this->db->where_in($this->_primary_key, $studentArray);
					$this->db->where($array);
					$this->db->order_by($this->_order_by); 
					$query = $this->db->get($this->_table_name);
					$query = $query->result();
					return $query;
				}
			} else {
				$this->db->join('studentextend', 'studentextend.studentID = student.studentID', 'LEFT');
				$this->db->where_in($this->_primary_key, $studentArray);
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