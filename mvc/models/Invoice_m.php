<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_m extends MY_Model {

	protected $_table_name = 'invoice';
	protected $_primary_key = 'invoiceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "invoiceID asc";
	

	public function __construct() {
		parent::__construct();
	}

	public function get_invoice_with_studentrelation() {
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->join('studentrelation', 'studentrelation.srstudentID = invoice.studentID AND studentrelation.srclassesID = invoice.classesID AND studentrelation.srschoolyearID = invoice.schoolyearID', 'LEFT');
		$this->db->where('invoice.deleted_at', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_invoice_with_studentrelation_by_studentID($studentID) {
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->join('studentrelation', 'studentrelation.srstudentID = invoice.studentID AND studentrelation.srclassesID = invoice.classesID AND studentrelation.srschoolyearID = invoice.schoolyearID', 'LEFT');
		$this->db->where('invoice.studentID', $studentID);
		$this->db->where('invoice.deleted_at', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_invoice_with_studentrelation_by_invoiceID($invoiceID) {
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->join('studentrelation', 'studentrelation.srstudentID = invoice.studentID AND studentrelation.srclassesID = invoice.classesID AND studentrelation.srschoolyearID = invoice.schoolyearID', 'LEFT');
		$this->db->where('invoice.invoiceID', $invoiceID);
		$this->db->where('invoice.deleted_at', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_invoice($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_invoice($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_invoice($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_invoice($array) {
		$error = parent::insert($array);
		return $error;
	}

	public function insert_batch_invoice($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_invoice($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_invoice_by_maininvoiceID($data, $id = NULL) {
		$this->db->set($data);
		$this->db->where('maininvoiceID', $id);
		$this->db->update($this->_table_name);
		return $id;
	}

	public function update_batch_invoice($data, $id = NULL) {
        parent::update_batch($data, $id);
        return TRUE;
    }

	public function delete_invoice($id){
		parent::delete($id);
	}

	public function delete_invoice_by_maininvoiceID($id){
		$this->db->delete($this->_table_name, array('maininvoiceID' => $id)); 
		return TRUE;
	}	

	public function get_all_duefees_for_report($queryArray) {
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->join('student','invoice.studentID=student.studentID');
		$this->db->where('invoice.schoolyearID',$queryArray['schoolyearID']);
		$this->db->where('student.isAdmitted',1);

		if((isset($queryArray['classesID']) && $queryArray['classesID'] != 0) || (isset($queryArray['sectionID']) && $queryArray['sectionID'] != 0) || (isset($queryArray['studentID']) && $queryArray['studentID'] != 0)) {
			
			if(isset($queryArray['classesID']) && $queryArray['classesID'] != 0) {
				$this->db->where('invoice.classesID', $queryArray['classesID']);
			}

			if(isset($queryArray['studentID']) && $queryArray['studentID'] != 0) {
				$this->db->where('invoice.studentID', $queryArray['studentID']);
			}
		}

		if(isset($queryArray['feetypeID']) && $queryArray['feetypeID'] != 0) {
			$this->db->where('invoice.feetypeID', $queryArray['feetypeID']);
		}

		if((isset($queryArray['fromdate']) && $queryArray['fromdate'] != 0) && (isset($queryArray['todate']) && $queryArray['todate'] != 0)) {
			$fromdate = date('Y-m-d', strtotime($queryArray['fromdate']));
			$todate = date('Y-m-d', strtotime($queryArray['todate']));
			$this->db->where('create_date >=', $fromdate);
			$this->db->where('create_date <=', $todate);
		}

		$this->db->where('invoice.paidstatus !=', 2);
		$this->db->where('invoice.deleted_at', 1);

		$query = $this->db->get();
		return $query->result();
	}

	public function get_all_balancefees_for_report($queryArray) {
		$this->db->select('*');
		$this->db->from('invoice');
		$this->db->where('invoice.schoolyearID',$queryArray['schoolyearID']);

		if((isset($queryArray['classesID']) && $queryArray['classesID'] != 0) || (isset($queryArray['sectionID']) && $queryArray['sectionID'] != 0) || (isset($queryArray['studentID']) && $queryArray['studentID'] != 0)) {
			
			if(isset($queryArray['classesID']) && $queryArray['classesID'] != 0) {
				$this->db->where('invoice.classesID', $queryArray['classesID']);
			}

			if(isset($queryArray['studentID']) && $queryArray['studentID'] != 0) {
				$this->db->where('invoice.studentID', $queryArray['studentID']);
			}
		}
		$this->db->where('invoice.deleted_at', 1);

		$query = $this->db->get();
		return $query->result();
	}

	public function get_dueamount($array) {
		$this->db->select('invoice.*,weaverandfine.weaver,weaverandfine.fine');
		$this->db->from('invoice');
		$this->db->join('weaverandfine','invoice.invoiceID=weaverandfine.invoiceID','LEFT');
		$this->db->where('invoice.schoolyearID',$array['schoolyearID']);
		$this->db->where('invoice.classesID',$array['classesID']);
		$this->db->where('invoice.deleted_at', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_invoice_sum($array = NULL) {
		if(isset($array['maininvoiceID'])) {
			$string = "SELECT SUM(amount) AS amount, SUM(discount) AS discount, SUM((amount/100)*discount) AS discountamount, SUM(amount-((amount/100)*discount)) AS invoiceamount FROM ".$this->_table_name." WHERE maininvoiceID = '".$array['maininvoiceID']."'";
		} else {
			$string = "SELECT SUM(amount) AS amount, SUM(discount) AS discount, SUM((amount/100)*discount) AS discountamount, SUM(amount-((amount/100)*discount)) AS invoiceamount FROM ".$this->_table_name;
		}

		$query = $this->db->query($string);
		return $query->row();
	}
}