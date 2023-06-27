<?php Class frontendData {
    static function get_frontend_delete() {
        $CI = & get_instance();
        $CI->load->driver('cache', array('adapter' => 'file'));
        $CI->cache->delete('get_frontend');
        return 'Get frontend cache remove';
    }

    static function get_backend_delete() {
        $CI = & get_instance();
        $CI->load->driver('cache', array('adapter' => 'file'));
        $CI->cache->delete('get_backend');
        return 'Get backend cache remove';
    }

    static function get_page_delete() {
        $CI = & get_instance();
        $CI->load->driver('cache', array('adapter' => 'file'));
        $CI->cache->delete('get_page');
        return 'Get page cache remove';
    }

    static function get_frontent_topbar_menu_delete() {
        $CI = & get_instance();
        $CI->load->driver('cache', array('adapter' => 'file'));
        $CI->cache->delete('get_topbar_menu');
        return 'Get topbar cache remove';
    }

    static function get_frontend($search = NULL) {
        $CI = & get_instance();
        $CI->load->driver('cache', array('adapter' => 'file'));

        if(empty($search)) {
            if(!$cacheData = $CI->cache->get('get_frontend')) {
                $CI->load->model('frontend_setting_m');
                $getItem = $CI->frontend_setting_m->get_frontend_setting_array();
                $CI->cache->save('get_frontend', $getItem, 900);

            }
            $cacheData = $CI->cache->get('get_frontend');
            return $cacheData;
        } else {
            $cacheData = $CI->cache->get('get_frontend');
            if(isset($cacheData[$search])) {
                return $cacheData[$search];
            } else {
                $CI->load->model('frontend_setting_m');
                $getItem = $CI->frontend_setting_m->get_frontend_setting_array();
                $CI->cache->save('get_frontend', $getItem, 900);

                $cacheData = $CI->cache->get('get_frontend');

                if(isset($cacheData[$search])) {
                    return $cacheData[$search];
                } else {
                    return '';
                }
            }
        }
    }

    static function get_backend($search = NULL) {
        $CI = & get_instance();
        $CI->load->driver('cache', array('adapter' => 'file'));

        if(empty($search)) {
            if(!$cacheData = $CI->cache->get('get_backend')) {
                $CI->load->model('setting_m');
                $getItem = $CI->setting_m->get_setting_array();
                $CI->cache->save('get_backend', $getItem, 900);

            }
            $cacheData = $CI->cache->get('get_backend');
            return $cacheData;
        } else {
            $cacheData = $CI->cache->get('get_backend');
            if(isset($cacheData[$search])) {
                return $cacheData[$search];
            } else {
                $CI->load->model('setting_m');
                $getItem = $CI->setting_m->get_setting_array();
                $CI->cache->save('get_backend', $getItem, 900);

                $cacheData = $CI->cache->get('get_backend');

                if(isset($cacheData[$search])) {
                    return $cacheData[$search];
                } else {
                    return '';
                }
            }
        }
    }

    static function get_page($search = NULL) {
        $CI = & get_instance();
        $CI->load->driver('cache', array('adapter' => 'file'));

        if(empty($search)) {
            if(!$cacheData = $CI->cache->get('get_page')) {
                $CI->load->model('pages_m');
                $getItem = $CI->pages_m->get_pages();
                $CI->cache->save('get_page', $getItem, 900);

            }
            $cacheData = $CI->cache->get('get_page');
            return $cacheData;
        } else {
            $CI->load->model('pages_m');

            $cacheData = $CI->cache->get('get_page');
            if(isset($cacheData[$search])) {
                return $cacheData[$search];
            } else {
                $CI->load->model('pages_m');
                $getItem = pluck($CI->pages_m->get_pages(), 'obj', 'pagesID');
                $CI->cache->save('get_page', $getItem, 900);

                $cacheData = $CI->cache->get('get_page');

                if(isset($cacheData[$search])) {
                    return $cacheData[$search];
                } else {
                    return '';
                }
            }
        }
    }

    static function get_frontent_topbar_menu() {
        $CI = & get_instance();
        $CI->load->driver('cache', array('adapter' => 'file'));
        $cacheData = $CI->cache->get('get_topbar_menu');
        if($cacheData != FALSE) {
            return $cacheData;
        } else {
            $CI->load->model('fmenu_relation_m');
            $CI->load->model('fmenu_m');
            $topbar = $CI->fmenu_m->get_single_fmenu(array('topbar' => 1));
            $getItem = $CI->fmenu_relation_m->get_join_with_page($topbar->fmenuID);

            $cat = '';
            if(count($getItem)) {
                foreach ($getItem as $key => $pageValue) {
                    $cat .= "<li><a href='".base_url('frontend/page/'.$pageValue->url)."'>".$pageValue->menu_label."</a></li>";
                }
            }
            $cat .= "<li><a target='_blank' href='".base_url('signin/index')."'>".'Login'."</a></li>";

            $CI->cache->save('get_topbar_menu', $cat, 900);
            $cacheData = $CI->cache->get('get_topbar_menu');

            return $cacheData;
        }
    }

    static function get_user( $usertypeID, $userID )
    { /* DD OK */
        $CI = &get_instance();
        $CI->load->model('systemadmin_m');
        $CI->load->model('teacher_m');
        $CI->load->model('student_m');
        $CI->load->model('parents_m');
        $CI->load->model('user_m');

        $findUserName = '';
        if ( $usertypeID == 1 ) {
            $user        = $CI->db->get_where('systemadmin',
                [ "usertypeID" => $usertypeID, 'systemadminID' => $userID ]);
            $alluserdata = $user->row();
            if ( count($alluserdata) ) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } elseif ( $usertypeID == 2 ) {
            $user        = $CI->db->get_where('teacher', [ "usertypeID" => $usertypeID, 'teacherID' => $userID ]);
            $alluserdata = $user->row();
            if ( count($alluserdata) ) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } elseif ( $usertypeID == 3 ) {
            $user        = $CI->db->get_where('student', [ "usertypeID" => $usertypeID, 'studentID' => $userID ]);
            $alluserdata = $user->row();
            if ( count($alluserdata) ) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } elseif ( $usertypeID == 4 ) {
            $user        = $CI->db->get_where('parents', [ "usertypeID" => $usertypeID, 'parentsID' => $userID ]);
            $alluserdata = $user->row();
            if ( count($alluserdata) ) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        } else {
            $user        = $CI->db->get_where('user', [ "usertypeID" => $usertypeID, 'userID' => $userID ]);
            $alluserdata = $user->row();
            if ( count($alluserdata) ) {
                $findUserName = $alluserdata->name;
            }
            return $findUserName;
        }
    }
}
?>