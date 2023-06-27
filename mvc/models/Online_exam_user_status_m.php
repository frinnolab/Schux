<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Online_exam_user_status_m extends MY_Model {

    protected $_table_name = 'online_exam_user_status';
    protected $_primary_key = 'onlineExamUserStatus';
    protected $_primary_filter = 'intval';
    protected $_order_by = "totalObtainedMark desc";

    function __construct() {
        parent::__construct();
    }

    function get_online_exam_user_status($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_online_exam_user_status($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_online_exam_user_status($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_online_exam_user_status($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_online_exam_user_status($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_online_exam_user_status($id){
        parent::delete($id);
    }

    public function get_join_online_exam_user_status($array, $schoolyearID) {
        $this->db->select('online_exam_user_status.*,online_exam.schoolYearID,online_exam.subjectID');
        $this->db->from('online_exam_user_status');
        $this->db->join('online_exam', 'online_exam.onlineExamID = online_exam_user_status.onlineExamID');
        if(count($array)) {
            foreach ($array as $key => $value) {
                $this->db->where('online_exam_user_status.'.$key,$value);
            }
        }
        $this->db->where('online_exam.schoolYearID', $schoolyearID);
        $query = $this->db->get();
        return $query->result();
    }
}
