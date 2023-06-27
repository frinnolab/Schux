<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts_category_m extends MY_Model {

    protected $_table_name = 'posts_category';
    protected $_primary_key = 'posts_categoryID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "posts_categoryID asc";

    function __construct() {
        parent::__construct();
    }

    function get_posts_category($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_posts_category($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_posts_category($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_posts_category($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_posts_category($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_posts_category($id){
        parent::delete($id);
    }

    function delete_posts_category_by_array($array) {
        $this->db->delete($this->_table_name, $array);
        return TRUE;
    }
}
