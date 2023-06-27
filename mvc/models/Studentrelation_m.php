<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentrelation_m extends MY_Model {

	protected $_table_name = 'studentrelation';
	protected $_primary_key = 'studentrelationID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "srroll asc";

	protected $_relation_array = [];
	protected $_user_role_array = [2, 3, 4];
	

	function __construct() {
		parent::__construct();
	}

	protected $_extend_array = [
		'studentextendID',
		'studentgroupID',
		'optionalsubjectID',
		'extracurricularactivities',
		'remarks'
	];

	private function prefixLoad($array) {
		if(is_array($array)) {
			if(count($array)) {
				foreach ($array as $arkey =>  $ar) {
					if(in_array($arkey, $this->_extend_array)) {
						unset($array[$arkey]);
						$array['studentextend.'.$arkey] = $ar;
					} elseif(substr($arkey, 0, 2) == 'sr') {
						unset($array[$arkey]);
						$array['studentrelation.'.$arkey] = $ar;
					} else {
						unset($array[$arkey]);
						$array['student.'.$arkey] = $ar;
					}
				}
			}
		}

		return $array;
	}

	private function userRelation() {
		$usertypeID = $this->session->userdata('usertypeID');
		$userID = $this->session->userdata('loginuserID');
		if($usertypeID == 2) {
			$this->db->from('classes')->where(array('teacherID' => $userID))->order_by('classesID');
			$classQuery = $this->db->get();
			$classResult = $classQuery->result();

			$this->db->from('routine')->where(array('teacherID' => $userID))->order_by('classesID');
			$routineQuery = $this->db->get();
			$routineResult = $routineQuery->result();

			$classMerged = (object) array_merge((array) $classResult , (array) $routineResult);

			$classes = array_unique(pluck($classMerged, 'classesID', 'classesID'));
			ksort($classes);
			return $classes;
		} elseif($usertypeID == 3) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->db->from('studentrelation');
        	$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
       		$this->db->where('studentrelation.srschoolyearID =', $schoolyearID);
       		$this->db->where('student.studentID !=', NULL);
       		$this->db->where('studentrelation.srstudentID', $userID);
       		$this->db->order_by('srroll asc');
			$studentrelationQuery = $this->db->get();
			$studentrelationResult = $studentrelationQuery->result();

			$classesArray = pluck($studentrelationResult, 'srclassesID', 'srclassesID');
			return $classesArray;
		} elseif($usertypeID == 4) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->db->from('studentrelation');
        	$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
       		$this->db->where('studentrelation.srschoolyearID =', $schoolyearID);
       		$this->db->where('student.parentID =', $userID);
       		$this->db->where('student.studentID !=', NULL);
       		$this->db->order_by('srroll asc');
			$studentrelationQuery = $this->db->get();
			$studentrelationResult = $studentrelationQuery->result();

			$studentArray = array_unique(pluck($studentrelationResult, 'srstudentID', 'srstudentID'));
			ksort($studentArray);
			return $studentArray;
		}
	}

	public function general_get_student($studentExtend = FALSE) {
        $this->db->select('*');
        $this->db->from('studentrelation');
        $this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');

        if($studentExtend) {
        	$this->db->join('studentextend', 'studentextend.studentID = studentrelation.srstudentID', 'LEFT');
        }

        $this->db->where('student.studentID !=', NULL);
        $this->db->order_by('srroll asc');
        $query = $this->db->get();
        return $query->result();
    }

	public function general_get_single_student($arrays = [], $studentExtend = FALSE) {
		$arrays = $this->prefixLoad($arrays);
        $this->db->select('*');
        $this->db->from('studentrelation');
        $this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');

        if($studentExtend) {
        	$this->db->join('studentextend', 'studentextend.studentID = studentrelation.srstudentID', 'LEFT');
        }

        if(count($arrays)) {
            $this->db->where($arrays);
        }
        $this->db->where('student.studentID !=', NULL);
        $this->db->order_by('srroll asc');
        $query = $this->db->get();
        return $query->row();
    }

	public function general_get_order_by_student($arrays = [], $studentExtend = FALSE, $studentAdmissionConsideration = TRUE) {
		$arrays = $this->prefixLoad($arrays);
        $this->db->select('*');
        $this->db->from('studentrelation');
		$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
		
		if ($studentAdmissionConsideration) {
			$this->db->where('isAdmitted = 1');
		}

        if($studentExtend) {
        	$this->db->join('studentextend', 'studentextend.studentID = studentrelation.srstudentID', 'LEFT');
        }

        if(count($arrays)) {
            $this->db->where($arrays);
        }
        $this->db->where('student.studentID !=', NULL);
        $this->db->order_by('srroll asc');
        $query = $this->db->get();
        return $query->result();
    }

	public function general_get_order_by_student_attendance($arrays = [], $studentExtend = FALSE, $studentAdmissionConsideration = TRUE) {
		$arrays = $this->prefixLoad($arrays);
        $this->db->select('*');
        $this->db->from('studentrelation');
		$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
		
		if ($studentAdmissionConsideration) {
			$this->db->where('isAdmitted = 1');
		}

        if($studentExtend) {
        	$this->db->join('studentextend', 'studentextend.studentID = studentrelation.srstudentID', 'LEFT');
        }

        if(count($arrays)) {
			if (!$arrays['studentrelation.srclassesID']) {
				unset($arrays['studentrelation.srclassesID']);
			}
			$this->db->where($arrays);
        }
        $this->db->where('student.studentID !=', NULL);
        $this->db->order_by('srroll asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_single_student($arrays = [], $studentExtend = FALSE) {
        $this->_relation_array = $this->userRelation();
		if(!count($this->_relation_array) && in_array($this->session->userdata('usertypeID'), $this->_user_role_array)) {
			return [];
		}

		$arrays = $this->prefixLoad($arrays);
        $this->db->select('*');
        $this->db->from('studentrelation');
        $this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');

        if($studentExtend) {
        	$this->db->join('studentextend', 'studentextend.studentID = studentrelation.srstudentID', 'LEFT');
        }

        if($this->session->userdata('usertypeID') == 2) {
        	$this->db->where_in('studentrelation.srclassesID', $this->_relation_array);
        } elseif($this->session->userdata('usertypeID') == 3) {
        	$this->db->where_in('studentrelation.srclassesID', $this->_relation_array);
        } elseif($this->session->userdata('usertypeID') == 4) {
        	$this->db->where_in('studentrelation.srstudentID', $this->_relation_array);
        } 
        
        if(count($arrays)) {
            $this->db->where($arrays);
        }
        $this->db->where('student.studentID !=', NULL);
        $this->db->order_by('srroll asc');
        $query = $this->db->get();
        return $query->row();
	}
	
	public function get_order_by_teacher($array = NULL)
	{
		$this->db->select('*');
		$this->db->from('teacher');
		$query = $this->db->get();
        return $query->result();
	}

    public function get_order_by_student($arrays = [], $studentExtend = FALSE) {
		$this->_relation_array = $this->userRelation();
		if(!count($this->_relation_array) && in_array($this->session->userdata('usertypeID'), $this->_user_role_array)) {
			return [];
		}

		$arrays = $this->prefixLoad($arrays);
        $this->db->select('*');
        $this->db->from('studentrelation');
		$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
		
		if ($arrays['studentrelation.srsectionID'] == '-1') {
			$this->db->where('isAdmitted = 0');
			unset($arrays['studentrelation.srsectionID']);
		} else {
			$this->db->where('isAdmitted = 1');
		}

        if($studentExtend) {
        	$this->db->join('studentextend', 'studentextend.studentID = studentrelation.srstudentID', 'LEFT');
        }

        if($this->session->userdata('usertypeID') == 2) {
        	$this->db->where_in('studentrelation.srclassesID', $this->_relation_array);
        } elseif($this->session->userdata('usertypeID') == 3) {
        	$this->db->where_in('studentrelation.srclassesID', $this->_relation_array);
        } elseif($this->session->userdata('usertypeID') == 4) {
        	$this->db->where_in('studentrelation.srstudentID', $this->_relation_array);
        } 

        if(count($arrays)) {
			if ($arrays['studentrelation.sroptionalsubjectID']) {
				$this->db->where('studentrelation.sroptionalsubjectID LIKE "%'."{$arrays['studentrelation.sroptionalsubjectID']}".'%"');
				unset($arrays['studentrelation.srsectionID']);
			} else {
				$this->db->where($arrays);
			}
        }
        $this->db->where('student.studentID !=', NULL);
        $this->db->order_by('srroll asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_order_by_student_filter($array) {
		// return $array['srclassesID'];
        $this->db->select('*');
        $this->db->from('studentrelation');
		$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');

        if($array['srclassesID'] >= 1) {
            $this->db->where('srclassesID = '.$array["srclassesID"]);
		}
		
		if($array['name']) {
			$this->db->where('srname like "%'.urldecode($array["name"]).'%"');
		}
		
		if(is_string($array['srsectionID'])) {
			$this->db->where('srsectionID = '.$array["srsectionID"]);
		}

		if ($array['srclassesID'] != 'all') {
			$this->db->where('isAdmitted = '.$array["isAdmitted"]);
		}
		
        $this->db->where('student.studentID !=', NULL);
        $this->db->where('studentrelation.srschoolyearID = ' .$array["srschoolyearID"]);
        $this->db->order_by('srroll asc');
        $query = $this->db->get();
        return $query->result();
    }

	public function get_studentrelation_join_student($arrays = [], $single = FALSE) {
        $arrays = $this->prefixLoad($arrays);
        $this->db->select('*');
        $this->db->from('studentrelation');
        $this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
        if(count($arrays)) {
            $this->db->where($arrays);
        }
        $this->db->order_by('srroll asc');
        $query = $this->db->get();

        if($single) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    
	public function get_studentrelation_join_with_student_student_extend($arrays = [], $single = FALSE) {
		$arrays = $this->prefixLoad($arrays);
		$this->db->select('*');
		$this->db->from('studentrelation');
        $this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
		$this->db->join('studentextend', 'studentextend.studentID = studentrelation.srstudentID', 'LEFT');
		if(count($arrays)) {
			$this->db->where($arrays);
		}
		$query = $this->db->get();

		if($single) {
			return $query->row();
		} else {
			return $query->result();
		}
	}
	
	public function update_studentrelation_with_multicondition($array, $multiCondition) {
		$this->db->update($this->_table_name, $array, $multiCondition);
	}

	public function get_studentrelation($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_studentrelation($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_studentrelation($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_studentrelation($array) {
		$error = parent::insert($array);
		return $error;
	}

	public function update_studentrelation($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_studentrelation($id){
		parent::delete($id);
	}
}