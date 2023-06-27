<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media_gallery_m extends MY_Model {

    protected $_table_name = 'media_gallery';
    protected $_primary_key = 'media_galleryID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "media_galleryID asc";

    function __construct() {
        parent::__construct();
    }

    function get_media_gallery($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_media_gallery($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_media_gallery($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_media_gallery($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_media_gallery($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_media_gallery($id){
        parent::delete($id);
    }
}
