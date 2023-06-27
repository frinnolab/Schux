<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exceptionpage extends Admin_Controller {
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
        $language = $this->session->userdata('lang');
        $this->lang->load('exceptionpage', $language);
    }

    public function index()
    {
        $this->data["subview"] = "exceptionpage/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function error()
    {
        $this->data["subview"] = "errorpermission";
        $this->load->view('_layout_main', $this->data);
    }
}