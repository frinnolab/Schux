<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Weaverandfine_m extends MY_Model {

	protected $_table_name = 'weaverandfine';
	protected $_primary_key = 'weaverandfineID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "weaverandfineID asc";

	function __construct() {
		parent::__construct();
	}

	function get_weaverandfine($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_weaverandfine($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_weaverandfine($array) {
        $query = parent::get_single($array);
        return $query;
    }

	function insert_weaverandfine($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_weaverandfine($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_weaverandfine($id){
		parent::delete($id);
	}

	public function delete_batch_weaverandfine($id){
		parent::delete_batch($id);
	}

	public function get_weaverandfine_join_with_payment($array) {
		$fromdate = date('Y-m-d',strtotime($array['fromdate']));
		$todate   = date('Y-m-d',strtotime($array['todate']));
		$this->db->select('weaverandfine.*,payment.paymentdate,studentrelation.*,invoice.feetypeID,feetypes.feetypes');
		$this->db->from($this->_table_name);
		$this->db->join('payment', 'payment.paymentID = weaverandfine.paymentID');
		$this->db->join('studentrelation', 'studentrelation.srstudentID = weaverandfine.studentID');
		$this->db->join('invoice', 'invoice.invoiceID = weaverandfine.invoiceID');
		$this->db->join('feetypes', 'feetypes.feetypesID = invoice.feetypeID');
		$this->db->where('weaverandfine.schoolyearID',$array['schoolyearID']);
		$this->db->where('studentrelation.srschoolyearID',$array['schoolyearID']);
		$this->db->where('weaverandfine.fine >','0');
		$this->db->where('payment.paymentdate >=',$fromdate);
		$this->db->where('payment.paymentdate <=',$todate);
		return $this->db->get()->result();
	}

	public function get_sum_weaverandfine($clmn, $array = []) {
		$query = parent::get_sum($clmn, $array);
		return $query;
	}
	public function get_where_weaverandfine_sum($clmn, $wherein, $arrays, $groupbyID = NULL) {
		$query = parent::get_where_sum($clmn, $wherein, $arrays, $groupbyID);
		return $query;
	}
}

/* End of file holiday_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/holiday_m.php */
