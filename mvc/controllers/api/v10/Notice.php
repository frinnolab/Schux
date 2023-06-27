<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('notice_m');
        $this->load->model("alert_m");
    }

    public function index_get()
    {
        $schoolyearID = $this->session->userdata("defaultschoolyearID");
        $this->retdata['notices'] = $this->notice_m->get_order_by_notice(array('schoolyearID' => $schoolyearID));
        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = null) 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if((int)$id) {
            $this->retdata['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
            if(count($this->retdata['notice'])) {
                $alert = $this->alert_m->get_single_alert(array('itemID' => $id, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'notice'));
                if(!count($alert)) {
                    $array = array(
                        "itemID" => $id,
                        "userID" => $this->session->userdata("loginuserID"),
                        "usertypeID" => $this->session->userdata("usertypeID"),
                        "itemname" => 'notice',
                    );
                    $this->alert_m->insert_alert($array);
                }

                $this->response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $this->retdata
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Error 404',
                    'data' => []
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error 404',
                'data' => []
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
