<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signin extends Admin_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://inilabs.net
| -----------------------------------------------------
*/
    public function __construct()
    {
        parent::__construct();
        $this->load->model("signin_m");
        $this->load->model("user_m");
        $this->load->helper('cookie');
        $this->load->library('updatechecker');
        $this->session->set_userdata($this->data["siteinfos"]->language);
        $language = $this->session->userdata('lang');
        $this->lang->load('signin', $language);
        if ( !isset($this->data["siteinfos"]->captcha_status) ) {
            $this->data["siteinfos"]->captcha_status = 1;
        }
    }

    protected function rules()
    {
        $rules = [
            [
                'field' => 'username',
                'label' => "Username",
                'rules' => 'trim|required|max_length[40]|xss_clean'
            ],
            [
                'field' => 'password',
                'label' => "Password",
                'rules' => 'trim|required|max_length[40]|xss_clean'
            ]
        ];

        if ( $this->data["siteinfos"]->captcha_status == 0 ) {
            $rules[] = [
                'field' => 'g-recaptcha-response',
                'label' => "captcha",
                'rules' => 'trim|required'
            ];
        }
        return $rules;
    }

    protected function rules_cpassword() {
		$rules = array(
				array(
					'field' => 'old_password',
					'label' => $this->lang->line('old_password'),
					'rules' => 'trim|required|max_length[40]|min_length[4]|xss_clean|callback_old_password_unique'
				),
				array(
					'field' => 'new_password',
					'label' => $this->lang->line('new_password'),
					'rules' => 'trim|required|max_length[40]|min_length[4]|xss_clean'
				),
				array(
					'field' => 're_password',
					'label' => $this->lang->line('re_password'),
					'rules' => 'trim|required|max_length[40]|min_length[4]|matches[new_password]|xss_clean'
				)
			);
		return $rules;
	}

	public function index() {
        if ( $this->data['siteinfos']->captcha_status == 0 ) {
            $this->load->library('recaptcha');
            $this->data['recaptcha'] = [
                'widget' => $this->recaptcha->getWidget(),
                'script' => $this->recaptcha->getScriptTag(),
            ];
        }

        $this->data['form_validation'] = 'No';
        $this->signin_m->loggedin() == FALSE || redirect(base_url('dashboard/index'));
        if($_POST) {
            $this->_setCookie();
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"]         = "signin/index";
                $this->load->view('_layout_signin', $this->data);
            } else {
                $signinManager = $this->_signInManager();
                if($signinManager['return']) {
                    redirect(base_url('dashboard/index'));
                } else {
                    $this->data['form_validation'] = $signinManager['message'];
                    $this->data["subview"]         = "signin/index";
                    $this->load->view('_layout_signin', $this->data);
                }
            }
        } else {
            $this->data["subview"]         = "signin/index";
            $this->load->view('_layout_signin', $this->data);
            $this->session->sess_destroy();
        }
	}

    public function cpassword()
    {
        $this->load->library("session");
        if ( $_POST ) {
            $rules = $this->rules_cpassword();
            $this->form_validation->set_rules($rules);
            if ( $this->form_validation->run() == false ) {
                $this->data["subview"] = "signin/cpassword";
                $this->load->view('_layout_main', $this->data);
            } else {
                redirect(base_url('signin/cpassword'));
            }
        } else {
            $this->data["subview"] = "signin/cpassword";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function old_password_unique()
    {
        if ( $this->signin_m->change_password() == true ) {
            return true;
        } else {
            $this->form_validation->set_message("old_password_unique", "%s does not match");
            return false;
        }
    }

    public function signout()
    {
        $this->signin_m->signout();
        $getPreviousData = $this->loginlog_m->get_single_loginlog([
            'userID'     => $this->session->userdata('loginuserID'),
            'usertypeID' => $this->session->userdata('usertypeID'),
            'ip'         => $this->updatechecker->getUserIP(),
            'browser'    => $this->updatechecker->getBrowser()->name,
            'logout'     => null
        ]);

        if ( count($getPreviousData) ) {
            $this->loginlog_m->update_loginlog(['logout' => strtotime(date('YmdHis'))], $getPreviousData->loginlogID);
        }

        if ( $this->data["siteinfos"]->frontendorbackend === 'YES' || $this->data['siteinfos']->frontendorbackend == 1 ) {
            redirect(base_url('frontend/index'));
        } else {
            redirect(base_url("signin/index"));
        }
    }

    private function _setCookie()
    {
        if ( isset($_POST['remember']) ) {
            set_cookie('remember_username', $this->input->post('username'), time() + ( 86400 * 30 ));
            set_cookie('remember_password', $this->input->post('password'), time() + ( 86400 * 30 ));
        } else {
            delete_cookie('remember_username');
            delete_cookie('remember_password');
        }
    }

    private function _userChecker( $username, $password )
    {
        $tables   = [
            'student'     => 'student',
            'parents'     => 'parents',
            'teacher'     => 'teacher',
            'user'        => 'user',
            'systemadmin' => 'systemadmin'
        ];
        $userInfo = [ 'info' => [], 'userID' => 0, 'idName' => '' ];
        foreach ( $tables as $table ) {
            $user = $this->user_m->get_user_table($table, $username, $password);
            if ( count($user) ) {
                $id                 = $table . 'ID';
                $userInfo['info']   = $user;
                $userInfo['userID'] = $user->$id;
                $userInfo['idName'] = $table . 'ID';
            }
        }
        return (object) $userInfo;
    }

    private function _signInManager()
    {
        if ( config_item('demo') == false ) {
            $codeChecker = $this->_updateCodeChecker();
            if ( $codeChecker->status ) {
                $verifyValidUser = true;
                $returnArray     = [ 'return' => true, 'message' => 'Success' ];
            } else {
                $returnArray = [ 'return' => false, 'message' => $codeChecker->message ];
                return $returnArray;
            }
        } else {
            $returnArray     = [ 'return' => true, 'message' => 'Success' ];
            $verifyValidUser = true;
        }

        $setting             = $this->data['siteinfos'];
        $lang                = $setting->language;
        $defaultSchoolYearID = $setting->school_year;
        $username            = $this->input->post('username');
        $password            = $this->input->post('password');
        $user                = $this->_userChecker($username, $password);
  
        $userID              = count($user) ? $user->userID : 0;
        $user                = count($user) ? $user->info : [];

        $captchaResponse = [ 'success' => true ];
        // if ( isset($setting->captcha_status) && $setting->captcha_status == 0 ) {
        //     $captchaResponse = $this->recaptcha->verifyResponse($this->input->post('g-recaptcha-response'));
        // } else {
        //     $captchaResponse = [ 'success' => true ];
        // }

        if ( $returnArray['return'] ) {
            if ( $captchaResponse['success'] ) {
                if ( count($user) ) {
                    $userType = $this->usertype_m->get_single_usertype([ 'usertypeID' => $user->usertypeID ]);
                    if ( count($userType) ) {
                        if ( $user->active ) {
                            $this->_loginLog($user->usertypeID, $userID);
                            $session = [
                                "loginuserID"         => $userID,
                                "name"                => $user->name,
                                "email"               => $user->email,
                                "usertypeID"          => $user->usertypeID,
                                'usertype'            => $userType->usertype,
                                "username"            => $user->username,
                                "photo"               => $user->photo,
                                "lang"                => $lang,
                                "defaultschoolyearID" => $defaultSchoolYearID,
                                "varifyvaliduser"     => $verifyValidUser,
                                "loggedin"            => true
                            ];

                            $this->session->set_userdata($session);
                            $returnArray = [ 'return' => true, 'message' => 'Success' ];
                        } else {
                            $returnArray = [ 'return' => false, 'message' => 'You are blocked' ];
                        }
                    } else {
                        $returnArray = [ 'return' => false, 'message' => 'This user role does not exist' ];
                    }
                } else {
                    $returnArray = [ 'return' => false, 'message' => 'Incorrect Signin' ];
                }
            } else {
                $captchaResponseError = ( is_array($captchaResponse['error-codes']) ) ? $captchaResponse['error-codes'][0] : $captchaResponse['error-codes'];
                $returnArray          = [ 'return' => false, 'message' => $captchaResponseError ];
            }
        } else {
            $returnArray = [ 'return' => false, 'message' => $returnArray['message'] ];
        }

        return $returnArray;
    }

    private function _loginLog( $userTypeID, $userID )
    {
        $getPreviousData = $this->loginlog_m->get_single_loginlog([
            'userID'     => $userID,
            'usertypeID' => $userTypeID,
            'ip'         => $this->updatechecker->getUserIP(),
            'browser'    => $this->updatechecker->getBrowser()->name,
            'logout'     => null
        ]);

        if ( count($getPreviousData) ) {
            $this->loginlog_m->update_loginlog(['logout' => ( $getPreviousData->login + ( 60 * 5 ) )], $getPreviousData->loginlogID);
        }

        $this->loginlog_m->insert_loginlog([
            'ip'              => $this->updatechecker->getUserIP(),
            'browser'         => $this->updatechecker->getBrowser()->name,
            'operatingsystem' => $this->updatechecker->getBrowser()->platform,
            'login'           => strtotime(date('YmdHis')),
            'usertypeID'      => $userTypeID,
            'userID'          => $userID,
        ]);
    }

    private function _updateCodeChecker()
    {
        return $this->updatechecker->verifyValidUser();
    }
}



