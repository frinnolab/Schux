<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaveapplication_m extends MY_Model {

    protected $_table_name = 'leaveapplications';
    protected $_primary_key = 'leaveapplicationID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "leaveapplicationID desc";

    function __construct() {
        parent::__construct();
    }

    public function get_leaveapplication($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_leaveapplication($array) {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_leaveapplication($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_leaveapplication($array) {
        $id = parent::insert($array);
        return $id;
    }

    public function update_leaveapplication($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_leaveapplication($id){
        parent::delete($id);
    }

    public function get_sum_of_leave_days_by_user($userTypeID, $userID, $schoolyearID) {
        $this->db->select('leavecategoryID, SUM(leave_days) AS days', FALSE);
        $this->db->where('create_userID', $userID);
        $this->db->where('create_usertypeID', $userTypeID);
        $this->db->where("status", 1);
        $this->db->where("schoolyearID", $schoolyearID);
        $this->db->group_by("leavecategoryID");
        $query = $this->db->get('leaveapplications');
        return $query->result();
    } 

    public function get_sum_of_leave_days_by_user_for_single_category($userTypeID, $userID, $schoolyearID, $categoryID) {
        $this->db->select('leavecategoryID, SUM(leave_days) AS days', FALSE);
        $this->db->where('create_userID', $userID);
        $this->db->where('create_usertypeID', $userTypeID);
        $this->db->where("status", 1);
        $this->db->where("schoolyearID", $schoolyearID);
        $this->db->where("leavecategoryID", $categoryID);
        $query = $this->db->get('leaveapplications');
        return $query->row();
    }

    public function get_leaveapplication_for_report($array) {
        if(isset($array['usertypeID']) && $array['usertypeID'] == 3) {
            $this->db->select('leaveapplications.*, leavecategory.leavecategory,studentrelation.srclassesID,studentrelation.srsectionID,studentrelation.srschoolyearID');
        } else {
            $this->db->select('leaveapplications.*, leavecategory.leavecategory');
        }

        $this->db->from('leaveapplications');
        $this->db->join('leavecategory', 'leaveapplications.leavecategoryID = leavecategory.leavecategoryID', 'LEFT');

        if(isset($array['usertypeID'])) {
            $this->db->where('leaveapplications.create_usertypeID', $array['usertypeID']);
        }

        if(isset($array['userID'])) {
            $this->db->where('leaveapplications.create_userID', $array['userID']);
        }

        if(isset($array['categoryID'])) {
            $this->db->where('leaveapplications.leavecategoryID', $array['categoryID']);
        }

        if(isset($array['statusID'])) {
            if($array['statusID'] == 1) {
                $this->db->where('leaveapplications.status', NULL);
            } elseif($array['statusID'] == 2) {
                $this->db->where('leaveapplications.status', 0);
            } elseif($array['statusID'] == 3) {
                $this->db->where('leaveapplications.status', 1);
            }
        }

        if(isset($array['usertypeID']) && ($array['usertypeID'] == 3)) {
            $this->db->join('studentrelation', 'studentrelation.srstudentID = leaveapplications.create_userID','LEFT');
            if(isset($array['schoolyearID'])) {
                $this->db->where('studentrelation.srschoolyearID', $array['schoolyearID']);
            }
            if(isset($array['classesID'])) {
                $this->db->where('studentrelation.srclassesID', $array['classesID']);
            }

            if(isset($array['sectionID'])) {
                $this->db->where('studentrelation.srsectionID', $array['sectionID']);
            }
        }

        if(isset($array['fromdate']) && isset($array['todate'])) {
            $this->db->where('leaveapplications.apply_date >=', $array['fromdate']);
            $this->db->where('leaveapplications.apply_date <=', $array['todate']);
        }

        $this->db->where('leaveapplications.schoolyearID', $array['schoolyearID']);
        $this->db->order_by('leaveapplications.leaveapplicationID', "desc");
        $query = $this->db->get();
        return $query->result();
    }


    public function get_order_by_leaveapplication_where_in($array) {
        $this->db->select('*');
        if($array['usercheck']) {
            $this->db->where('create_usertypeID !=', 2);
            $this->db->where('create_usertypeID !=', 3);
        } else {
            $this->db->where('create_usertypeID', $array['create_usertypeID']);
        }
        $this->db->where("status", 1);
        $this->db->where("schoolyearID", $array['schoolyearID']);
        $query = $this->db->get('leaveapplications');
        return $query->result();
    }

    /* define for 4.4 */
    public function get_order_by_leaveapply_with_user($array) {
        $this->db->select('leaveapplications.*, systemadmin.name as aname, teacher.name as tname, student.name as sname, parents.name as pname, user.name as uname');
        $this->db->from('leaveapplications');
        $this->db->join('systemadmin', 'systemadmin.usertypeID = leaveapplications.applicationto_usertypeID AND systemadmin.systemadminID = leaveapplications.applicationto_userID' , 'LEFT');
        $this->db->join('teacher', 'teacher.usertypeID = leaveapplications.applicationto_usertypeID AND teacher.teacherID = leaveapplications.applicationto_userID', 'LEFT');
        $this->db->join('student', 'student.usertypeID = leaveapplications.applicationto_usertypeID AND student.studentID = leaveapplications.applicationto_userID', 'LEFT');
        $this->db->join('parents', 'parents.usertypeID = leaveapplications.applicationto_usertypeID AND parents.parentsID = leaveapplications.applicationto_userID', 'LEFT');
        $this->db->join('user', 'user.usertypeID = leaveapplications.applicationto_usertypeID AND user.userID = leaveapplications.applicationto_userID', 'LEFT');
        $this->db->where($array);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->result();
    }

    /* define for 4.4 */
    public function get_order_by_leaveapplication_with_user($array) {
        $this->db->select('leaveapplications.*, systemadmin.name as aname, teacher.name as tname, student.name as sname, parents.name as pname, user.name as uname');
        $this->db->from('leaveapplications');
        $this->db->join('systemadmin', 'systemadmin.usertypeID = leaveapplications.create_usertypeID AND systemadmin.systemadminID = leaveapplications.create_userID' , 'LEFT');
        $this->db->join('teacher', 'teacher.usertypeID = leaveapplications.create_usertypeID AND teacher.teacherID = leaveapplications.create_userID', 'LEFT');
        $this->db->join('student', 'student.usertypeID = leaveapplications.create_usertypeID AND student.studentID = leaveapplications.create_userID', 'LEFT');
        $this->db->join('parents', 'parents.usertypeID = leaveapplications.create_usertypeID AND parents.parentsID = leaveapplications.create_userID', 'LEFT');
        $this->db->join('user', 'user.usertypeID = leaveapplications.create_usertypeID AND user.userID = leaveapplications.create_userID', 'LEFT');
        $this->db->where($array);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->result();
    }

}
