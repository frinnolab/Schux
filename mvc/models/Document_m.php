<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document_m extends MY_Model {

	protected $_table_name = 'document';
	protected $_primary_key = 'documentID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "documentID desc";

	public function __construct() {
		parent::__construct();
	}

	public function get_document($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_document($array) {
        $query = parent::get_single($array);
        return $query;
    }

	public function get_order_by_document($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_document($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_document($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_document($id){
		parent::delete($id);
	}


	

}

/* End of file Document_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/Document_m.php */