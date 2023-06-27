<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class productpurchaseitem_m extends MY_Model {

	protected $_table_name = 'productpurchaseitem';
	protected $_primary_key = 'productpurchaseitemID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "productpurchaseitemID asc";

	function __construct() {
		parent::__construct();
	}

	public function get_productpurchaseitem($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_productpurchaseitem($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_productpurchaseitem($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_productpurchaseitem($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_productpurchaseitem($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_productpurchaseitem($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_productpurchaseitem($id){
		parent::delete($id);
	}

	public function delete_productpurchaseitem_by_productpurchaseID($id) {
		$this->db->delete($this->_table_name, array('productpurchaseID' => $id)); 
		return TRUE;
	}

	public function get_productpurchaseitem_sum($array) {
		if(isset($array['productpurchaseID']) && isset($array['schoolyearID'])) {
			$string = "SELECT SUM(productpurchaseunitprice), SUM(productpurchasequantity), SUM(productpurchaseunitprice*productpurchasequantity) AS result FROM ".$this->_table_name." WHERE productpurchaseID = '".$array['productpurchaseID']."' && schoolyearID = '".$array['schoolyearID']."'";
		} else {
			$string = "SELECT SUM(productpurchaseunitprice), SUM(productpurchasequantity), SUM(productpurchaseunitprice*productpurchasequantity) AS result FROM ".$this->_table_name;
		}

		$query = $this->db->query($string);
		return $query->row();
	}

	public function get_productpurchaseitem_quantity() {
		$string = 'SELECT SUM(productpurchaseitem.productpurchasequantity) AS quantity, productpurchaseitem.productID AS productID FROM productpurchaseitem LEFT JOIN productpurchase on productpurchase.productpurchaseID = productpurchaseitem.productpurchaseID WHERE productpurchaserefund = 0 GROUP BY productpurchaseitem.productID';
		$query = $this->db->query($string);
		return $query->result();
	}

	public function get_productpurchaseitem_qty() {
		$string = 'SELECT COALESCE(SUM(productpurchaseitem.productpurchasequantity),0) AS quantity, product.productID AS productID FROM product LEFT JOIN productpurchaseitem on product.productID = productpurchaseitem.productID LEFT JOIN productpurchase on productpurchase.productpurchaseID = productpurchaseitem.productpurchaseID WHERE productpurchaseitem.productID IS NULL OR productpurchaserefund = 0 GROUP BY productpurchaseitem.productID ORDER BY product.productID ASC';
		$query = $this->db->query($string);
		return $query->result();
	}
}