<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lmember_m extends MY_Model {

	protected $_table_name = 'lmember';
	protected $_primary_key = 'lmemberID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "lmemberID asc";

	function __construct() {
		parent::__construct();
	}

	public function get_lmember($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_lmember($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_lmember_lastID() {
		$this->db->select('*')->from('lmember')->order_by('lmemberID desc')->limit(1, 0);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_order_by_lmember($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_lmember($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_lmember($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_lmember($id){
		parent::delete($id);
	}

	public function delete_lmember_sID($id){
		$this->db->where('studentID', $id);
		$this->db->delete("lmember");
		return TRUE;
	}

	public function delete_lmember_tID($id){
		$this->db->where('teacherID', $id);
		$this->db->delete("lmember");
		return TRUE;
	}

	public function get_all_librarybooks_for_report($queryArray) {
        $currentdate = date('Y-m-d');
        $this->db->select('*');
        $this->db->from('lmember');
        $this->db->join('issue', 'issue.lID = lmember.lID');
        $this->db->join('studentrelation', 'lmember.studentID = studentrelation.srstudentID');
            
        if(isset($queryArray['classesID']) && $queryArray['classesID'] != 0) {
            $this->db->where('studentrelation.srclassesID', $queryArray['classesID']);
        }

        if(isset($queryArray['sectionID']) && $queryArray['sectionID'] != 0) {
            $this->db->where('studentrelation.srsectionID', $queryArray['sectionID']);
        }

        if(isset($queryArray['studentID']) && $queryArray['studentID'] != 0) {
            $this->db->where('studentrelation.srstudentID', $queryArray['studentID']);
        }

        if(isset($queryArray['lID']) && $queryArray['lID'] != '') {
            $this->db->where('issue.lID', $queryArray['lID']);
        }

        if((isset($queryArray['fromdate']) && !empty($queryArray['fromdate'])) && (isset($queryArray['todate']) && !empty($queryArray['todate']))) {
            $fromdate = date('Y-m-d', strtotime($queryArray['fromdate']));
            $todate = date('Y-m-d', strtotime($queryArray['todate']));

            if(isset($queryArray['typeID']) && $queryArray['typeID'] != '0') {
                if($queryArray['typeID'] == 1) {
                	$this->db->where('issue_date >=', $fromdate);
                    $this->db->where('issue_date <=', $todate);
                    $this->db->where('return_date',null);
                } elseif($queryArray['typeID'] == 2){
                    $this->db->where('return_date >=', $fromdate);
                    $this->db->where('return_date <=', $todate);
                    $this->db->where('return_date !=',null);
                } elseif($queryArray['typeID'] == 3) {
					$this->db->where('due_date <', $currentdate);
                    $this->db->where('return_date',null);
                }
            } else {
                $this->db->where('issue_date >=', $fromdate);
                $this->db->where('issue_date <=', $todate);
            }
        } else {
            if(isset($queryArray['typeID']) && $queryArray['typeID'] != '0'){
                if($queryArray['typeID'] == 1){
                    $this->db->where('return_date',null);
                } elseif($queryArray['typeID'] == 2){
                    $this->db->where('return_date !=',null);
                } elseif($queryArray['typeID'] == 3) {
					$this->db->where('due_date <', $currentdate);
                    $this->db->where('return_date',null);
                }
            }
        }
        $this->db->where('studentrelation.srschoolyearID', $queryArray['schoolyearID']);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_join_lmember_student_studentrelation($queryArray) {
        $this->db->select('*');
        $this->db->from('lmember');
        $this->db->join('student', 'lmember.studentID = student.studentID');
        $this->db->join('studentrelation', 'lmember.studentID = studentrelation.srstudentID');
            
        if(isset($queryArray['srclassesID']) && $queryArray['srclassesID'] != 0) {
            $this->db->where('studentrelation.srclassesID', $queryArray['srclassesID']);
        }

        if(isset($queryArray['srsectionID']) && $queryArray['srsectionID'] != 0) {
            $this->db->where('studentrelation.srsectionID', $queryArray['srsectionID']);
        }

        if(isset($queryArray['srstudentID']) && $queryArray['srstudentID'] != 0) {
            $this->db->where('studentrelation.srstudentID', $queryArray['srstudentID']);
        }

        $this->db->where('studentrelation.srschoolyearID', $queryArray['srschoolyearID']);
        $this->db->where('student.library', 1);
        $this->db->where('student.studentID !=', NULL);
        $query = $this->db->get();
        return $query->result();
    }
}