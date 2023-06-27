<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class Setting_m extends MY_Model
    {

        protected $_table_name = 'setting';
        protected $_primary_key = 'option';
        protected $_primary_filter = 'intval';
        protected $_order_by = "option asc";

        public function __construct()
        {
            parent::__construct();
        }

        public function get_setting( $id = 1 )
        {
            $compress = [];
            $query    = $this->db->get('setting');
            foreach ( $query->result() as $row ) {
                $compress[ $row->fieldoption ] = $row->value;
            }
            return (object) $compress;
        }

        public function get_setting_array()
        {
            $compress = [];
            $query    = $this->db->get('setting');
            foreach ( $query->result() as $row ) {
                $compress[ $row->fieldoption ] = $row->value;
            }
            return $compress;
        }

        public function get_setting_where( $data )
        {
            $this->db->where('fieldoption', $data);
            $query = $this->db->get('setting');
            return $query->row();
        }

        public function insertorupdate( $arrays )
        {
            foreach ( $arrays as $key => $array ) {
                $this->db->query("INSERT INTO setting (fieldoption, value) VALUES ('" . $key . "', '" . $array . "') ON DUPLICATE KEY UPDATE fieldoption='" . $key . "' , value='" . $array . "'");
            }
            return true;
        }

        public function delete_setting( $optionname )
        {
            $this->db->delete('setting', [ 'fieldoption' => $optionname ]);
            return true;
        }

        public function insert_setting( $array )
        {
            $this->db->insert('setting', $array);
            return true;
        }

        public function update_setting( $fieldoption, $value )
        {
            $array = [
                'value' => $value,
            ];

            $this->db->where('fieldoption', $fieldoption);
            $this->db->update($this->_table_name, $array);
            return true;
        }
    }