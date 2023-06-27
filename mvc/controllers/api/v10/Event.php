<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model("event_m");
        $this->load->model("eventcounter_m");
        $this->load->model("alert_m");
    }

    public function index_get() 
    {
        $schoolyearID = $this->session->userdata("defaultschoolyearID");
        $this->retdata['events'] = $this->event_m->get_order_by_event(array('schoolyearID' => $schoolyearID));

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = null) 
    {
        if((int)$id) {
            $schoolyearID = $this->session->userdata("defaultschoolyearID");
            $this->retdata['event'] = $this->event_m->get_single_event(array('eventID' => $id, 'schoolyearID' => $schoolyearID));

            $this->retdata['eventID'] = $id;
            $this->retdata['goings'] = $this->eventcounter_m->get_order_by_eventcounter(array('eventID' => $id, 'status' => 1));
            $this->retdata['ignores'] = $this->eventcounter_m->get_order_by_eventcounter(array('eventID' => $id, 'status' => 0));
            if(count($this->retdata['event'])) {
                $alert = $this->alert_m->get_single_alert(array('itemID' => $id, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'event'));
                if(!count($alert)) {
                    $array = array(
                        "itemID" => $id,
                        "userID" => $this->session->userdata("loginuserID"),
                        "usertypeID" => $this->session->userdata("usertypeID"),
                        "itemname" => 'event',
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
