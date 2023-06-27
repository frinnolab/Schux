<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class User_m extends MY_Model
    {

        protected $_table_name = 'user';
        protected $_primary_key = 'userID';
        protected $_primary_filter = 'intval';
        protected $_order_by = "usertypeID";

        public function __construct()
        {
            parent::__construct();
        }

        public function get_username( $table, $data = null )
        {
            $query = $this->db->get_where($table, $data);
            return $query->result();
        }

        public function get_user_by_usertype( $userID = null )
        {
            $this->db->select('*');
            $this->db->from('user');
            $this->db->join('usertype', 'usertype.usertypeID = user.usertypeID', 'LEFT');
            if ( $userID ) {
                $this->db->where([ 'userID' => $userID ]);
                $query = $this->db->get();
                return $query->row();
            } else {
                $query = $this->db->get();
                return $query->result();
            }
        }

        public function get_user( $array = null, $signal = false )
        {
            $query = parent::get($array, $signal);
            return $query;
        }

        public function get_order_by_user( $array = null )
        {
            $query = parent::get_order_by($array);
            return $query;
        }

        public function get_single_user( $array )
        {
            $query = parent::get_single($array);
            return $query;
        }

        public function get_select_user( $select = null, $array = [] )
        {
            if ( $select == null ) {
                $select = 'userID, usertypeID, name, photo';
            }

            $this->db->select($select);
            $this->db->from($this->_table_name);

            if ( count($array) ) {
                $this->db->where($array);
            }

            $query = $this->db->get();
            return $query->result();
        }

        public function insert_user( $array )
        {
            parent::insert($array);
            return true;
        }

        public function update_user( $data, $id = null )
        {
            parent::update($data, $id);
            return $id;
        }

        public function delete_user( $id )
        {
            parent::delete($id);
        }

        public function hash( $string )
        {
            return parent::hash($string);
        }

        public function get_user_info($usertypeID, $userID)
        {
            if ( $usertypeID == 1 ) {
                $table = "systemadmin";
            } elseif ( $usertypeID == 2 ) {
                $table = "teacher";
            } elseif ( $usertypeID == 3 ) {
                $table = 'student';
            } elseif ( $usertypeID == 4 ) {
                $table = 'parents';
            } else {
                $table = 'user';
            }

            $query = $this->db->get_where($table, [ $table . 'ID' => $userID ]);
            return $query->row();
        }

        public function get_user_table($table, $username, $password)
        {
            $query = $this->db->get_where($table, [ 'username' => $username, 'password' => $this->hash($password) ]);
            return $query->row();
        }
    }