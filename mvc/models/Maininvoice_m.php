<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maininvoice_m extends MY_Model {

	protected $_table_name = 'maininvoice';
	protected $_primary_key = 'maininvoiceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "maininvoiceID desc";
	

	public function __construct() {
		parent::__construct();
	}

	public function get_maininvoice_with_studentrelation($schoolyearID = NULL, $classID) {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->join('studentrelation', 'studentrelation.srstudentID = maininvoice.maininvoicestudentID AND studentrelation.srschoolyearID = maininvoice.maininvoiceschoolyearID', 'LEFT');
		$this->db->where('maininvoice.maininvoicedeleted_at', 1);
		if ($classID != 0 && $classID != NULL) {
			$this->db->where('maininvoice.maininvoiceclassesID', $classID);
		}
		if($schoolyearID != NULL) {
			$this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
			$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		}
		$this->db->order_by('maininvoice.maininvoiceID', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_maininvoice_by_class($classID, $schoolyearID) {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->join('studentrelation', 'studentrelation.srstudentID = maininvoice.maininvoicestudentID AND studentrelation.srschoolyearID = maininvoice.maininvoiceschoolyearID', 'LEFT');
		$this->db->join('student', 'student.studentID = maininvoice.maininvoicestudentID AND student.schoolyearID = maininvoice.maininvoiceschoolyearID', 'LEFT');
		$this->db->where('maininvoice.maininvoiceclassesID', $classID);
		$this->db->where('maininvoice.maininvoicedeleted_at', 1);

		if($schoolyearID != NULL) {
			$this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
			$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		}

		$this->db->order_by('maininvoice.maininvoiceID', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_maininvoice_with_studentrelation_by_studentID($studentID, $schoolyearID = NULL) {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->join('studentrelation', 'studentrelation.srstudentID = maininvoice.maininvoicestudentID AND studentrelation.srschoolyearID = maininvoice.maininvoiceschoolyearID', 'LEFT');
		$this->db->where('maininvoice.maininvoicestudentID', $studentID);
		$this->db->where('maininvoice.maininvoicedeleted_at', 1);

		if($schoolyearID != NULL) {
			$this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
			$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		}
		$this->db->order_by('maininvoice.maininvoiceID', 'desc');
		$query = $this->db->get();
		return $query->result();
	}


	public function get_maininvoice_with_studentrelation_by_multi_studentID($studentIDArrays, $schoolyearID = NULL) {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->join('studentrelation', 'studentrelation.srstudentID = maininvoice.maininvoicestudentID AND studentrelation.srschoolyearID = maininvoice.maininvoiceschoolyearID', 'LEFT');
		$this->db->where('maininvoice.maininvoicedeleted_at', 1);

		if(count($studentIDArrays)) {
			$this->db->where_in('maininvoice.maininvoicestudentID', $studentIDArrays);
		}

		if($schoolyearID != NULL) {
			$this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
			$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		}

		$this->db->order_by('maininvoice.maininvoiceID', 'desc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_maininvoice_with_studentrelation_by_maininvoiceID($invoiceID, $schoolyearID = NULL) {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->join('studentrelation', 'studentrelation.srstudentID = maininvoice.maininvoicestudentID AND studentrelation.srschoolyearID = maininvoice.maininvoiceschoolyearID', 'LEFT');
		$this->db->where('maininvoice.maininvoiceID', $invoiceID);
		$this->db->where('maininvoice.maininvoicedeleted_at', 1);

		if($schoolyearID != NULL) {
			$this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
			$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		}

		$this->db->order_by('maininvoice.maininvoiceID', 'desc');
		$query = $this->db->get();
		return $query->row();
	}

	public function get_maininvoice($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_maininvoice($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_maininvoice($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_maininvoice($array) {
		$error = parent::insert($array);
		return $error;
	}

	public function insert_batch_maininvoice($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_maininvoice($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_maininvoice($id){
		parent::delete($id);
	}
}

/* End of file invoice_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/invoice_m.php */