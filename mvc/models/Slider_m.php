<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slider_m extends MY_Model {

	protected $_table_name = 'slider';
	protected $_primary_key = 'sliderID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "sliderID asc";

	function __construct() {
		parent::__construct();
	}

	function get_slider($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_slider($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_slider($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_slider($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_slider($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_slider($id){
		parent::delete($id);
	}

	public function get_slider_join_with_media_gallery($pageID) {
		$this->db->select('*');
		$this->db->from('slider');
		$this->db->join('media_gallery', 'media_gallery.media_galleryID = slider.slider', 'FULL');
		$this->db->where('slider.pagesID', $pageID);
		$query = $this->db->get();
		return $query->result();
	}
}

/* End of file holiday_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/holiday_m.php */
