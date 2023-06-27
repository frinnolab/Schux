<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ebooks_m extends MY_Model {

	protected $_table_name = 'ebooks';
	protected $_primary_key = 'ebooksID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "ebooksID asc";

	public function __construct() {
		parent::__construct();
	}

	public function get_ebooks($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_ebooks($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_ebooks($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_ebooks($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_ebooks($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_ebooks($id){
		parent::delete($id);
	}

	public function get_order_by_ebooks_with_authority($classes) {
		$this->db->select('*');
		$this->db->from('ebooks');
		$this->db->where('authority', '0');
		if(count($classes)) {
			foreach ($classes as $classesID) {
				$this->db->or_where('classesID', $classesID);
			}
		}
		$this->db->order_by('name','ASC');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_single_ebooks_with_authority($classes, $ebooksID) {
		$this->db->select('*');
		$this->db->from('ebooks');
		$this->db->group_start();
			$this->db->where('authority', '0');
			if(count($classes)) {
				foreach ($classes as $classesID) {
					$this->db->or_where('classesID', $classesID);
				}
			}
		$this->db->group_end();
		$this->db->where('ebooksID', $ebooksID);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_order_by_ebooks_with_authority_pagination($classes, $limit, $offset) {
		$this->db->select('*');
		$this->db->from('ebooks');
		$this->db->where('authority', '0');
		if(count($classes)) {
			foreach ($classes as $classesID) {
				$this->db->or_where('classesID', $classesID);
			}
		}
        $this->db->limit($limit,$offset);
		$this->db->order_by('name','ASC');
		$query = $this->db->get();
		return $query->result();
	}

}

/* End of file ebooks_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/ebooks_m.php */