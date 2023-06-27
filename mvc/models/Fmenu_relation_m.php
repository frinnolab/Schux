<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fmenu_relation_m extends MY_Model {

	protected $_table_name = 'fmenu_relation';
	protected $_primary_key = 'fmenu_relationID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "menu_orderID asc";

	function __construct() {
		parent::__construct();
	}

	function get_fmenu_relation($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_fmenu_relation($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_fmenu_relation($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_fmenu_relation($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_fmenu_relation($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	function update_fmenu_relation($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_fmenu_relation($id){
		parent::delete($id);
	}

	public function get_join_with_page($ID = '') {
		$this->db->select('*');
		$this->db->from('fmenu_relation');
		
		if(!empty($ID)) {
			$this->db->where(array('fmenu_relation.fmenuID' => $ID));
		}
			
		$this->db->join('pages', 'pages.pagesID = fmenu_relation.menu_pagesID', 'FULL');
		$this->db->where(array('pages.status' => 1, 'menu_status' => 1, 'pages.publish_date <' => date(date("Y-m-d H:i:s"))));
		$query = $this->db->get();
		return $query->result();
	}

	function update_fmenu_relation_by_array($data, $ruleData) {
		$this->db->update($this->_table_name, $data, $ruleData);
		$updateID = $this->db->affected_rows();

		if($updateID)
			return $updateID;
		else
			return FALSE;
	}

	function delete_fmenu_relation_by_array($array) {
		$this->db->delete($this->_table_name, $array);
		return TRUE;
	}
}

/* End of file holiday_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/holiday_m.php */
