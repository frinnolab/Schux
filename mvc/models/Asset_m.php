<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asset_m extends MY_Model {

    protected $_table_name = 'asset';
    protected $_primary_key = 'assetID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "assetID asc";

    function __construct() {
        parent::__construct();
    }

    function get_asset($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_asset($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_asset_with_category_and_location() {
        $this->db->select('asset.*, asset_category.category, location.location');
        $this->db->from('asset');
        $this->db->join('asset_category', 'asset_category.asset_categoryID = asset.asset_categoryID', 'LEFT');
        $this->db->join('location', 'location.locationID = asset.asset_locationID', 'LEFT');
        $this->db->order_by("assetID",'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    function get_single_asset_with_category_and_location($array=array()) {
        $this->db->select('asset.assetID, asset.serial, asset.description as adescription, asset.manufacturer, asset.brand, asset.asset_number, asset.status, asset.asset_condition, asset.attachment, asset.originalfile, asset.asset_categoryID, asset.asset_locationID, asset.create_date as acreate_date, asset_category.*,  location.location, location.description, location.active as lactive');
        $this->db->from('asset');
        $this->db->join('asset_category', 'asset_category.asset_categoryID = asset.asset_categoryID', 'LEFT');
        $this->db->join('location', 'location.locationID = asset.asset_locationID', 'LEFT');
        $this->db->order_by("assetID",'DESC');
        $this->db->where($array);
        $query = $this->db->get();
        return $query->row();
    }

    function get_order_by_asset($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_asset($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_asset($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_asset($id){
        parent::delete($id);
    }

    public function get_asset_purchased_quantity()
    {
        $string = 'SELECT COALESCE(SUM(purchase.quantity),0) AS quantity, asset.assetID AS assetID FROM asset LEFT JOIN purchase on asset.assetID = purchase.assetID GROUP BY purchase.assetID ORDER BY asset.assetID DESC';
		$query = $this->db->query($string);
		return $query->result();
    }

    public function get_asset_assigned_quantitiy()
    {
        $string = 'SELECT COALESCE(SUM(asset_assignment.assigned_quantity),0) AS quantity, asset.assetID AS assetID FROM asset LEFT JOIN asset_assignment on asset.assetID = asset_assignment.assetID AND asset_assignment.status = 1 GROUP BY asset_assignment.assetID ORDER BY asset.assetID DESC';
		$query = $this->db->query($string);
		return $query->result();
    }
}
