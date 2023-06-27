<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class conversation_m extends MY_Model
    {
        protected $_table_name = 'conversation_message_info';
        protected $_primary_key = 'id';
        protected $_primary_filter = 'intval';
        protected $_order_by = "id ASC";

        public function __construct()
        {
            parent::__construct();
        }

        public function get_conversation( $array = null, $signal = false )
        {
            $query = parent::get($array, $signal);
            return $query;
        }

        public function get_my_conversations()
        {
            $userID     = $this->session->userdata("loginuserID");
            $usertypeID = $this->session->userdata("usertypeID");
            $this->db->distinct();
            $this->db->select('*');
            $this->db->from('conversation_user');
            $this->db->join('conversation_message_info',
                'conversation_user.conversation_id=conversation_message_info.id', 'left');
            $this->db->join('conversation_msg', 'conversation_user.conversation_id=conversation_msg.conversation_id',
                'left');
            $this->db->where('conversation_user.user_id', $userID);
            $this->db->where('conversation_user.usertypeID', $usertypeID);
            $this->db->where('conversation_user.trash', 0);
            $this->db->where('conversation_msg.start', 1);
            $this->db->where('conversation_message_info.draft', 0);
            $this->db->order_by('conversation_message_info.id', 'desc');
            $this->db->group_by('conversation_message_info.id');
            $query = $this->db->get();
            return $query->result();
        }

        public function get_my_conversations_draft()
        {
            $userID     = $this->session->userdata("loginuserID");
            $usertypeID = $this->session->userdata("usertypeID");
            $this->db->select('*');
            $this->db->from('conversation_user');
            $this->db->join('conversation_message_info',
                'conversation_user.conversation_id=conversation_message_info.id', 'left');
            $this->db->join('conversation_msg', 'conversation_user.conversation_id=conversation_msg.conversation_id',
                'left');
            $this->db->where('conversation_user.user_id', $userID);
            $this->db->where('conversation_user.usertypeID', $usertypeID);
            $this->db->where('conversation_user.trash', 0);
            $this->db->where('conversation_user.is_sender', 1);
            $this->db->where('conversation_msg.start', 1);
            $this->db->where('conversation_message_info.draft', 1);
            $this->db->order_by('conversation_message_info.id', 'desc');
            $query = $this->db->get();
            return $query->result();
        }

        public function get_my_conversations_sent()
        {
            $userID     = $this->session->userdata("loginuserID");
            $usertypeID = $this->session->userdata("usertypeID");
            $this->db->select('*');
            $this->db->from('conversation_user');
            $this->db->join('conversation_message_info',
                'conversation_user.conversation_id=conversation_message_info.id', 'left');
            $this->db->join('conversation_msg', 'conversation_user.conversation_id=conversation_msg.conversation_id',
                'left');
            $this->db->where('conversation_user.user_id', $userID);
            $this->db->where('conversation_user.usertypeID', $usertypeID);
            $this->db->where('conversation_user.trash', 0);
            $this->db->where('conversation_user.is_sender', 1);
            $this->db->where('conversation_msg.start', 1);
            $this->db->where('conversation_message_info.draft', 0);
            $this->db->order_by('conversation_message_info.id', 'desc');
            $query = $this->db->get();
            return $query->result();
        }

        public function get_my_conversations_trash()
        {
            $userID     = $this->session->userdata("loginuserID");
            $usertypeID = $this->session->userdata("usertypeID");
            $this->db->select('*');
            $this->db->from('conversation_user');
            $this->db->join('conversation_message_info',
                'conversation_user.conversation_id=conversation_message_info.id', 'left');
            $this->db->join('conversation_msg', 'conversation_user.conversation_id=conversation_msg.conversation_id',
                'left');
            $this->db->where('conversation_user.user_id', $userID);
            $this->db->where('conversation_user.usertypeID', $usertypeID);
            $this->db->where('conversation_user.trash', 1);
            $this->db->where('conversation_msg.start', 1);
            $this->db->where('conversation_message_info.draft', 0);
            $this->db->order_by('conversation_message_info.id', 'desc');
            $query = $this->db->get();
            return $query->result();
        }

        public function get_single_conversation_msg( $array )
        {
            $this->db->from('conversation_msg');
            $this->db->where($array);
            $query = $this->db->get();
            return $query->row();
        }

        public function get_conversation_msg_by_id( $conversationID = 0 )
        {
            $this->db->order_by("msg_id", "asc");
            $query = $this->db->get_where('conversation_msg', [ 'conversation_id' => $conversationID ]);
            return $query->result();
        }

        public function insert_conversation( $array )
        {
            $insetID = parent::insert($array);
            return $insetID;
        }

        public function insert_conversation_user( $array )
        {
            $this->db->insert("conversation_user", $array);
            return true;
        }

        public function batch_insert_conversation_user( $array )
        {
            $this->db->insert_batch('conversation_user', $array);
            $id = $this->db->insert_id();
            return $id;
        }

        public function insert_conversation_msg( $array )
        {
            $this->db->insert("conversation_msg", $array);
            $id = $this->db->insert_id();
            return $id;
        }

        public function update_conversation( $data, $id = null )
        {
            parent::update($data, $id);
            return $id;
        }

        public function delete_conversation( $id )
        {
            parent::delete($id);
            return true;
        }

        public function user_check( $conv_id, $user_id, $usertypeID )
        {
            $query = $this->db->get_where('conversation_user',
                [ 'conversation_id' => $conv_id, 'user_id' => $user_id, 'usertypeID' => $usertypeID ]);
            return $query->row();
        }

        public function trash_conversation( $data, $id )
        {
            $usertypeID = $this->session->userdata("usertypeID");
            $userID     = $this->session->userdata("loginuserID");
            $query      = $this->db->get_where('conversation_user',
                [ 'conversation_id' => $id, 'user_id' => $userID, 'usertypeID' => $usertypeID ]);
            if ( count($query->row()) == 1 ) {
                $this->db->where('conversation_id', $id);
                $this->db->where('user_id', $userID);
                $this->db->where('usertypeID', $usertypeID);
                $this->db->update('conversation_user', $data);
            }
            return true;
        }

        public function get_usertype_by_permission()
        {
            $this->db->select('*');
            $this->db->from('permission_relationships');
            $this->db->join('permissions', 'permissions.permissionID = permission_relationships.permission_id', 'LEFT');
            $this->db->join('usertype', 'usertype.usertypeID = permission_relationships.usertype_id', 'LEFT');
            $this->db->where([ 'permissions.name' => 'conversation' ]);
            $query = $this->db->get();
            return $query->result();
        }
    }