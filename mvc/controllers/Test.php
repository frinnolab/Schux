<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends Admin_Controller {
// class Test extends CI_Controller {
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
	function __construct() {
		parent::__construct();
//		if(config_item('demo') == FALSE || ENVIRONMENT == 'production') {
//			redirect('dashboard/index');
//		}
	}

	public function index()
    {
        $this->load->model('teacherclasses_m');
        dump($this->teacherclasses_m->teacherClass());

	}
}
