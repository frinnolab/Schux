<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class Signin_m extends MY_Model
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model("setting_m");
            $this->load->model('usertype_m');
            $this->load->model('loginlog_m');
        }

        public function change_password()
        {
            $tables = [
                'student'     => 'student',
                'parents'     => 'parents',
                'teacher'     => 'teacher',
                'user'        => 'user',
                'systemadmin' => 'systemadmin'
            ];

            $username        = $this->session->userdata("username");
            $old_password    = $this->hash($this->input->post('old_password'));
            $new_password    = $this->hash($this->input->post('new_password'));
            $getOrginalData  = '';
            $getOrginalTable = '';
            foreach ( $tables as $key => $table ) {
                $user        = $this->db->get_where($table, [ "username" => $username, "password" => $old_password ]);
                $alluserdata = $user->row();
                if ( count($alluserdata) ) {
                    $getOrginalData  = $alluserdata;
                    $getOrginalTable = $table;
                }
            }

            if ( isset($getOrginalData->password) && ( $getOrginalData->password == $old_password ) ) {
                $array = [
                    "password" => $new_password
                ];
                $this->db->where([ "username" => $username, "password" => $old_password ]);
                $this->db->update($getOrginalTable, $array);
                return true;
            }
            return false;
        }

        public function signout()
        {
            $this->session->sess_destroy();
        }

        public function loggedin()
        {
            return (bool) $this->session->userdata("loggedin");
        }
    }
