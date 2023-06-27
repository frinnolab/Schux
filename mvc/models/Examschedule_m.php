<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Examschedule_m extends MY_Model {

	protected $_table_name = 'examschedule';
	protected $_primary_key = 'examscheduleID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classesID asc";

	function __construct() {
		parent::__construct();
	}

	private function prefixLoad($array) {
		if(is_array($array)) {
			if(count($array)) {
				foreach ($array as $arkey =>  $ar) {
					$array[$this->_table_name.'.'.$arkey] = $ar;
					unset($array[$arkey]);
				}
			}
		}
		return $array;
	}

	public function get_join_examschedule_with_exam_classes_section_subject($array) {
		$array = $this->prefixLoad($array);
		$this->db->select('*');
		$this->db->from('examschedule');
		$this->db->join('exam', 'exam.examID = examschedule.examID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = examschedule.classesID', 'LEFT');
		$this->db->join('section', 'section.sectionID = examschedule.sectionID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = examschedule.subjectID', 'LEFT');
		$this->db->where($array)->order_by('edate');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_examschedule($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_examschedule($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_examschedule($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_examschedule($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_examschedule($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_examschedule($id){
		parent::delete($id);
	}
}