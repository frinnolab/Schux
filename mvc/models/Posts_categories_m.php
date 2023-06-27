<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts_categories_m extends MY_Model {

    protected $_table_name = 'posts_categories';
    protected $_primary_key = 'posts_categoriesID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "posts_categoriesID asc";

    function __construct() {
        parent::__construct();
    }

    function get_posts_categories($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_posts_categories($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_posts_categories($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_posts_categories($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_posts_categories($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_posts_categories($id){
        parent::delete($id);
    }
}
