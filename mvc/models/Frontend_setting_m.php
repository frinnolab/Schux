<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Frontend_setting_m extends MY_Model {

    protected $_table_name = 'frontend_setting';
    protected $_primary_key = 'option';
    protected $_primary_filter = 'intval';
    protected $_order_by = "option asc";

    function __construct() {
        parent::__construct();
    }


    function get_frontend_setting() {
        $compress = array();
        $query = $this->db->get($this->_table_name);
        foreach ($query->result() as $row) {
            $compress[$row->fieldoption] = $row->value;
        }
        return (object) $compress;
    }

    function get_frontend_setting_array() {
        $compress = array();
        $query = $this->db->get($this->_table_name);
        foreach ($query->result() as $row) {
            $compress[$row->fieldoption] = $row->value;
        }
        return $compress;
    }

    function get_frontend_setting_where($data) {
        $this->db->where('fieldoption', $data);
        $query = $this->db->get($this->_table_name);
        return $query->row();
    }

    function insertorupdate($arrays) {
        foreach ($arrays as $key => $array) {
            $this->db->query("INSERT INTO $this->_table_name (fieldoption, value) VALUES ('".$key."', '".$array."') ON DUPLICATE KEY UPDATE fieldoption='".$key."' , value='".$array."'");
        }
        return TRUE;
    }

    public function delete_frontend_setting($optionname){
        $this->db->delete($this->_table_name, array('fieldoption' => $optionname));
        return TRUE;
    }

    public function insert_frontend_setting($array) {
        $this->db->insert($this->_table_name, $array);
        return TRUE; 
    }


    public function update_frontend_setting($fieldoption, $value) {
        $array = array(
           'value' => $value,
        );

        $this->db->where('fieldoption', $fieldoption);
        $this->db->update($this->_table_name, $array);
        return TRUE;  
    }    
}