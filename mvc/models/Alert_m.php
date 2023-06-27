<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class alert_m extends MY_Model
    {

        protected $_table_name = 'alert';
        protected $_primary_key = 'alertID';
        protected $_primary_filter = 'intval';
        protected $_order_by = "alertID asc";

        public function __construct()
        {
            parent::__construct();
        }

        public function get_alert( $array = null, $signal = false )
        {
            $query = parent::get($array, $signal);
            return $query;
        }

        public function get_single_alert( $array )
        {
            $query = parent::get_single($array);
            return $query;
        }

        public function get_order_by_alert( $array = null )
        {
            $query = parent::get_order_by($array);
            return $query;
        }

        public function insert_alert( $array )
        {
            parent::insert($array);
            return true;
        }

        public function insert_batch_alert( $array )
        {
            $id = parent::insert_batch($array);
            return $id;
        }

        public function update_alert( $data, $id = null )
        {
            parent::update($data, $id);
            return $id;
        }

        public function delete_alert( $id )
        {
            parent::delete($id);
        }
    }