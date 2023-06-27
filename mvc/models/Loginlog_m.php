<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class Loginlog_m extends MY_Model
    {

        protected $_table_name = 'loginlog';
        protected $_primary_key = 'loginlogID';
        protected $_primary_filter = 'intval';
        protected $_order_by = "loginlogID asc";

        public function __construct()
        {
            parent::__construct();
        }

        public function get_loginlog( $array = null, $signal = false )
        {
            $query = parent::get($array, $signal);
            return $query;
        }

        public function get_order_by_loginlog( $array = null )
        {
            $query = parent::get_order_by($array);
            return $query;
        }

        public function get_single_loginlog( $array = null )
        {
            $query = parent::get_single($array);
            return $query;
        }

        public function insert_loginlog( $array )
        {
            parent::insert($array);
            return true;
        }

        public function update_loginlog( $data, $id = null )
        {
            parent::update($data, $id);
            return $id;
        }

        public function delete_loginlog( $id )
        {
            parent::delete($id);
        }
    }