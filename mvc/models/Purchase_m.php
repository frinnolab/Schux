<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_m extends MY_Model {

    protected $_table_name = 'purchase';
    protected $_primary_key = 'purchaseID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "purchaseID asc";

    function __construct() {
        parent::__construct();
    }

    function get_purchase($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_purchase($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_purchase_with_all() {
        $this->db->select('purchase.*, asset.description, productsupplier.productsuppliercompanyname, user.name as purchaser_name');
        $this->db->from('purchase');
        $this->db->join('asset', 'purchase.assetID = asset.assetID', 'LEFT');
        $this->db->join('productsupplier', 'purchase.vendorID = productsupplier.productsupplierID', 'LEFT');
        $this->db->join('user', 'purchase.purchased_by = user.userID', 'LEFT');
        $this->db->order_by("purchaseID",'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    function get_single_purchase_with_all($array=array()) {
        $this->db->select('purchase.*, asset.description, productsupplier.productsuppliercompanyname, user.name as purchaser_name');
        $this->db->from('purchase');
        $this->db->join('asset', 'purchase.assetID = asset.assetID', 'LEFT');
        $this->db->join('productsupplier', 'purchase.vendorID = productsupplier.productsupplierID', 'LEFT');
        $this->db->join('user', 'purchase.purchased_by = user.userID', 'LEFT');
        $this->db->order_by("purchaseID",'DESC');
        $this->db->where($array);
        $query = $this->db->get();
        return $query->row();
    }
    function get_order_by_purchase($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_purchase($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_purchase($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_purchase($id){
        parent::delete($id);
    }
}
